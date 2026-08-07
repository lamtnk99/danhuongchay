<?php

namespace App\Services;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Illuminate\Support\Str;

class ArticlePresentationService
{
    /**
     * @return array{html: string, toc: array<int, array{id: string, text: string, level: int}>, word_count: int, reading_minutes: int}
     */
    public function present(?string $html, string $locale = 'vi'): array
    {
        $html = (string) $html;
        $wordCount = $this->wordCount(strip_tags($html));
        $readingMinutes = max(1, (int) ceil($wordCount / ($locale === 'en' ? 200 : 220)));
        $toc = [];

        if (trim($html) === '' || ! class_exists(DOMDocument::class)) {
            return [
                'html' => $html,
                'toc' => $toc,
                'word_count' => $wordCount,
                'reading_minutes' => $readingMinutes,
            ];
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $wrapperId = 'article-presentation-root';
        $previous = libxml_use_internal_errors(true);

        try {
            $loaded = $document->loadHTML(
                '<?xml encoding="UTF-8"><div id="'.$wrapperId.'">'.$html.'</div>',
                LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD
            );

            if (! $loaded) {
                return [
                    'html' => $html,
                    'toc' => [],
                    'word_count' => $wordCount,
                    'reading_minutes' => $readingMinutes,
                ];
            }

            $usedIds = [];
            $xpath = new DOMXPath($document);
            /** @var DOMElement $heading */
            foreach ($xpath->query('//h2 | //h3') ?: [] as $heading) {
                    if (! $this->isInsideWrapper($heading, $wrapperId)) {
                        continue;
                    }

                    $text = trim(preg_replace('/\s+/u', ' ', $heading->textContent) ?? '');
                    if ($text === '') {
                        continue;
                    }

                    $base = $heading->getAttribute('id') ?: Str::slug(Str::ascii($text));
                    $base = $base !== '' ? $base : 'muc-noi-dung';
                    $id = $base;
                    $suffix = 2;
                    while (isset($usedIds[$id])) {
                        $id = $base.'-'.$suffix++;
                    }

                    $usedIds[$id] = true;
                    $heading->setAttribute('id', $id);
                    $toc[] = ['id' => $id, 'text' => $text, 'level' => (int) substr($heading->tagName, 1)];
            }

            $wrapper = $document->getElementById($wrapperId);
            $rendered = '';
            if ($wrapper) {
                foreach ($wrapper->childNodes as $child) {
                    $rendered .= $document->saveHTML($child);
                }
            }

            return [
                'html' => $rendered ?: $html,
                'toc' => count($toc) >= 2 ? $toc : [],
                'word_count' => $wordCount,
                'reading_minutes' => $readingMinutes,
            ];
        } catch (\Throwable) {
            return [
                'html' => $html,
                'toc' => [],
                'word_count' => $wordCount,
                'reading_minutes' => $readingMinutes,
            ];
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }
    }

    public function wordCount(?string $text): int
    {
        $text = trim(html_entity_decode(strip_tags((string) $text), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

        return count(preg_split('/\s+/u', $text, -1, PREG_SPLIT_NO_EMPTY) ?: []);
    }

    private function isInsideWrapper(DOMElement $element, string $wrapperId): bool
    {
        $parent = $element->parentNode;
        while ($parent) {
            if ($parent instanceof DOMElement && $parent->getAttribute('id') === $wrapperId) {
                return true;
            }
            $parent = $parent->parentNode;
        }

        return false;
    }
}
