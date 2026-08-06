<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PostInternalLinkService
{
    private const STOP_WORDS = [
        'about', 'and', 'cach', 'cho', 'cua', 'dan', 'duoc', 'hai', 'hay', 'hieu', 'huong',
        'khong', 'la', 'lam', 'mot', 'nhung', 'phong', 'restaurant', 'sao', 'the', 'this',
        'thuc', 'trong', 'vegetarian', 'viet', 'voi', 'what', 'when', 'where', 'which', 'your',
    ];

    public function relatedPosts(Post $post, int $limit = 6): Collection
    {
        $relations = ['category.translations', 'translations'];

        $curated = $post->internalLinks()
            ->with($relations)
            ->published()
            ->limit($limit)
            ->get();

        if ($curated->count() >= $limit) {
            return $curated;
        }

        $excludedIds = $curated->modelKeys();
        $excludedIds[] = $post->getKey();
        $sourceTokens = $this->topicTokens($post);

        $automatic = Post::query()
            ->with($relations)
            ->published()
            ->whereKeyNot($excludedIds)
            ->latest('published_at')
            ->limit(50)
            ->get()
            ->sortByDesc(fn (Post $candidate): int => $this->relevanceScore($post, $candidate, $sourceTokens))
            ->take($limit - $curated->count());

        return $curated->concat($automatic)->values();
    }

    /**
     * @return array{previous: ?Post, next: ?Post}
     */
    public function chronologicalNeighbors(Post $post): array
    {
        $relations = ['category.translations', 'translations'];

        $previous = Post::query()
            ->with($relations)
            ->published()
            ->where(function ($query) use ($post): void {
                $query->where('published_at', '<', $post->published_at)
                    ->orWhere(function ($query) use ($post): void {
                        $query->where('published_at', $post->published_at)
                            ->whereKeyNot($post->getKey())
                            ->where('id', '<', $post->getKey());
                    });
            })
            ->latest('published_at')
            ->latest('id')
            ->first();

        $next = Post::query()
            ->with($relations)
            ->published()
            ->where(function ($query) use ($post): void {
                $query->where('published_at', '>', $post->published_at)
                    ->orWhere(function ($query) use ($post): void {
                        $query->where('published_at', $post->published_at)
                            ->whereKeyNot($post->getKey())
                            ->where('id', '>', $post->getKey());
                    });
            })
            ->oldest('published_at')
            ->oldest('id')
            ->first();

        return ['previous' => $previous, 'next' => $next];
    }

    private function relevanceScore(Post $source, Post $candidate, Collection $sourceTokens): int
    {
        $candidateTokens = $this->topicTokens($candidate);
        $overlap = $sourceTokens->intersect($candidateTokens)->count();

        return ($source->category_id === $candidate->category_id ? 100 : 0)
            + ($overlap * 12)
            + ($candidate->is_featured ? 5 : 0);
    }

    private function topicTokens(Post $post): Collection
    {
        $text = implode(' ', array_filter([
            $post->localized('title'),
            $post->localized('excerpt'),
            $post->localized('meta_keywords'),
            $post->category?->localized('name'),
        ]));

        return collect(preg_split('/\s+/', strtolower(Str::ascii($text))) ?: [])
            ->map(fn (string $word): string => preg_replace('/[^a-z0-9]/', '', $word) ?: '')
            ->filter(fn (string $word): bool => strlen($word) >= 4 && ! in_array($word, self::STOP_WORDS, true))
            ->unique()
            ->values();
    }
}
