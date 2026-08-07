<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Services\BlogTranslationService;
use App\Services\PostMediaService;
use App\Services\PostSeoAnalyzer;
use App\Services\UploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function __construct(
        private readonly UploadService $uploads,
        private readonly PostSeoAnalyzer $seoAnalyzer,
        private readonly BlogTranslationService $blogTranslator,
        private readonly PostMediaService $postMedia,
    ) {}

    public function index(Request $request): View
    {
        $categories = Category::post()->orderBy('name')->get();
        $posts = Post::query()
            ->with('category')
            ->withCount('internalLinks')
            ->when($request->filled('q'), fn ($query) => $query->where('title', 'like', '%'.$request->q.'%'))
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->status === 'active'))
            ->latest('published_at')
            ->paginate(10)
            ->withQueryString();

        $posts->getCollection()->each(function (Post $post): void {
            $post->setAttribute('seo_analysis', $this->seoAnalyzer->analyze([
                'title' => $post->title,
                'slug' => $post->slug,
                'excerpt' => $post->excerpt,
                'content' => $post->content,
                'meta_title' => $post->meta_title,
                'meta_description' => $post->meta_description,
                'focus_keyword' => $post->focus_keyword,
                'thumbnail_present' => filled($post->thumbnail),
                'related_post_ids' => array_fill(0, $post->internal_links_count, 1),
            ], $post->getKey()));
        });

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::post()->active()->orderBy('name')->get();
        $linkablePosts = Post::query()
            ->with('category:id,name')
            ->orderBy('title')
            ->get(['id', 'category_id', 'title']);

        return view('admin.posts.create', [
            'post' => new Post(['is_active' => true]),
            'categories' => $categories,
            'linkablePosts' => $linkablePosts,
            'translationState' => 'missing',
            'englishChecks' => [],
        ]);
    }

    public function store(PostRequest $request): RedirectResponse
    {
        $data = $this->normalizedData($request);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $this->uploads->uploadImage($request->file('thumbnail'), 'posts');
        }

        $post = Post::create($data);
        $this->syncEnglishTranslation($request, $post);
        $this->syncInternalLinks($request, $post);
        $this->postMedia->syncForPost($post, $post->content, data_get($request->validated(), 'translations.en.content'));

        return redirect()->route('admin.posts.index')->with('success', 'Đã thêm bài viết.');
    }

    public function edit(Post $post): View
    {
        $categories = Category::post()->orderBy('name')->get();
        $linkablePosts = Post::query()
            ->with('category:id,name')
            ->whereKeyNot($post->getKey())
            ->orderBy('title')
            ->get(['id', 'category_id', 'title']);
        $post->load(['internalLinks', 'translations']);

        $translation = $post->translation('en');
        $currentHash = $this->blogTranslator->sourceHash($this->sourceFields($post->toArray()));
        $translationState = ! $translation
            ? 'missing'
            : (! $translation->source_hash ? 'unknown' : ($translation->source_hash === $currentHash ? 'synced' : 'stale'));
        $englishChecks = $this->englishChecks($post, $translation);

        return view('admin.posts.edit', compact('post', 'categories', 'linkablePosts', 'translationState', 'englishChecks'));
    }

    public function update(PostRequest $request, Post $post): RedirectResponse
    {
        $data = $this->normalizedData($request);

        if ($request->hasFile('thumbnail')) {
            $oldThumbnail = $post->thumbnail;
            $data['thumbnail'] = $this->uploads->uploadImage($request->file('thumbnail'), 'posts');
            $this->uploads->deleteImage($oldThumbnail);
        }

        $post->update($data);
        $this->syncEnglishTranslation($request, $post);
        $this->syncInternalLinks($request, $post);
        $this->postMedia->syncForPost($post, $post->content, data_get($request->validated(), 'translations.en.content'));

        return redirect()->route('admin.posts.index')->with('success', 'Đã cập nhật bài viết.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->uploads->deleteImage($post->thumbnail);
        $post->media()->pluck('path')->each(fn (string $path) => $this->uploads->deleteImage($path));
        $post->delete();

        return back()->with('success', 'Đã xóa bài viết.');
    }

    private function normalizedData(PostRequest $request): array
    {
        return collect($request->validated())
            ->except(['thumbnail', 'translations', 'related_post_ids', 'translation_source_hash', 'translation_provider'])
            ->merge([
                'is_featured' => $request->boolean('is_featured'),
                'is_active' => $request->boolean('is_active'),
            ])
            ->all();
    }

    private function syncEnglishTranslation(PostRequest $request, Post $post): void
    {
        $translation = collect(data_get($request->validated(), 'translations.en', []))
            ->map(fn ($value) => $value === '' ? null : $value);

        if ($translation->filter(fn ($value) => $value !== null)->isEmpty()) {
            $post->translations()->where('locale', 'en')->delete();

            return;
        }

        $metadata = [];
        $submittedHash = $request->validated('translation_source_hash');
        $currentHash = $this->blogTranslator->sourceHash($this->sourceFields($request->validated()));
        if ($submittedHash && hash_equals($currentHash, $submittedHash)) {
            $metadata = [
                'source_hash' => $submittedHash,
                'translated_at' => now(),
                'translation_provider' => $request->validated('translation_provider'),
            ];
        }

        $post->translations()->updateOrCreate(['locale' => 'en'], $translation->all() + $metadata);
    }

    private function syncInternalLinks(PostRequest $request, Post $post): void
    {
        $links = collect($request->validated('related_post_ids', []))
            ->reject(fn (int|string $id): bool => (int) $id === $post->getKey())
            ->values()
            ->mapWithKeys(fn (int|string $id, int $position): array => [
                (int) $id => ['position' => $position],
            ])
            ->all();

        $post->internalLinks()->sync($links);
    }

    private function sourceFields(array $data): array
    {
        return collect(['title', 'excerpt', 'content', 'meta_title', 'meta_description', 'focus_keyword'])
            ->mapWithKeys(fn (string $field): array => [$field => $data[$field] ?? null])
            ->all();
    }

    private function englishChecks(Post $post, mixed $translation): array
    {
        if (! $translation) {
            return [];
        }

        $required = ['title', 'slug', 'excerpt', 'content', 'meta_title', 'meta_description'];
        $checks = collect($required)->mapWithKeys(fn (string $field): array => [
            $field => filled($translation->{$field}),
        ])->all();
        $checks['different_content'] = trim(strip_tags((string) $translation->content)) !== trim(strip_tags((string) $post->content));
        $checks['matching_structure'] = $this->htmlStructure($translation->content) === $this->htmlStructure($post->content);

        return $checks;
    }

    private function htmlStructure(?string $html): array
    {
        return [
            'h2' => preg_match_all('/<h2\b/i', (string) $html),
            'h3' => preg_match_all('/<h3\b/i', (string) $html),
            'a' => preg_match_all('/<a\b/i', (string) $html),
            'img' => preg_match_all('/<img\b/i', (string) $html),
        ];
    }
}
