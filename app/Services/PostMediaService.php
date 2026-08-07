<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostMedia;
use DOMDocument;
use DOMElement;

class PostMediaService
{
    public function syncForPost(Post $post, ?string ...$contents): void
    {
        $media = collect($contents)
            ->filter(fn (?string $content): bool => filled($content))
            ->flatMap(fn (string $content): array => $this->extractMedia($content))
            ->unique('id')
            ->keyBy('id');

        if ($media->isEmpty()) {
            return;
        }

        PostMedia::query()
            ->whereIn('id', $media->keys())
            ->where(fn ($query) => $query->whereNull('post_id')->orWhere('post_id', $post->getKey()))
            ->get()
            ->each(function (PostMedia $item) use ($media, $post): void {
                $attributes = $media->get($item->getKey());
                $item->update([
                    'post_id' => $post->getKey(),
                    'alt_text' => $attributes['alt_text'],
                    'caption' => $attributes['caption'],
                    'layout' => $attributes['layout'],
                ]);
            });
    }

    /** @return array<int, array{id: int, alt_text: ?string, caption: ?string, layout: string}> */
    private function extractMedia(string $html): array
    {
        if (! class_exists(DOMDocument::class) || ! str_contains($html, 'data-media-id')) {
            return [];
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);

        try {
            if (! $document->loadHTML('<?xml encoding="UTF-8"><div>'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)) {
                return [];
            }

            $result = [];
            /** @var DOMElement $figure */
            foreach ($document->getElementsByTagName('figure') as $figure) {
                $id = (int) $figure->getAttribute('data-media-id');
                if ($id < 1) {
                    continue;
                }

                $image = $figure->getElementsByTagName('img')->item(0);
                $caption = $figure->getElementsByTagName('figcaption')->item(0);
                $layout = $figure->getAttribute('data-layout');
                $result[] = [
                    'id' => $id,
                    'alt_text' => $image ? trim($image->getAttribute('alt')) ?: null : null,
                    'caption' => $caption ? trim($caption->textContent) ?: null : null,
                    'layout' => in_array($layout, ['normal', 'wide'], true) ? $layout : 'normal',
                ];
            }

            return $result;
        } catch (\Throwable) {
            return [];
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }
    }
}
