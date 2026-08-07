<?php

namespace App\Services;

use App\Services\Translation\DeepLTranslationService;
use App\Services\Translation\MicrosoftTranslatorService;
use DOMDocument;
use Illuminate\Support\Str;
use RuntimeException;

class BlogTranslationService
{
    public function __construct(
        private readonly DeepLTranslationService $deepL,
        private readonly MicrosoftTranslatorService $microsoft,
    ) {}

    /** @return array{translations: array<string, string>, usage: ?array, source_hash: string, provider: string, warnings: array} */
    public function translate(array $fields): array
    {
        $service = $this->service();
        $provider = setting('translation_provider', 'deepl') === 'microsoft' ? 'microsoft' : 'deepl';
        $content = trim((string) ($fields['content'] ?? ''));
        $baseFields = collect($fields)->except('content')->filter(fn ($value) => filled($value))->all();
        $translations = [];
        $usage = null;

        if ($baseFields !== []) {
            $result = $service->translateFields($baseFields);
            $translations = $result['translations'];
            $usage = $result['usage'] ?? null;
        }

        if ($content !== '') {
            $translatedChunks = [];
            foreach ($this->contentBatches($content) as $batch) {
                $result = $service->translateFields($batch);
                foreach ($result['translations'] as $key => $value) {
                    $translatedChunks[(int) str_replace('content_', '', $key)] = $value;
                }
                $usage = $result['usage'] ?? $usage;
            }
            ksort($translatedChunks);
            $translations['content'] = implode('', $translatedChunks);

            if (! $this->hasMatchingStructure($content, $translations['content'])) {
                throw new RuntimeException('Bản dịch làm thay đổi cấu trúc đầu mục hoặc liên kết. Nội dung tiếng Anh cũ được giữ nguyên; vui lòng thử lại.');
            }
        }

        if ($translations === []) {
            throw new RuntimeException('Không có nội dung tiếng Việt để dịch.');
        }

        if (filled($translations['title'] ?? null)) {
            $translations['slug'] = Str::slug(Str::ascii($translations['title']));
        }

        return [
            'translations' => $translations,
            'usage' => $usage,
            'source_hash' => $this->sourceHash($fields),
            'provider' => $provider,
            'warnings' => [],
        ];
    }

    public function sourceHash(array $fields): string
    {
        $source = collect(['title', 'excerpt', 'content', 'meta_title', 'meta_description', 'focus_keyword'])
            ->mapWithKeys(fn (string $key): array => [$key => $this->normalizeSource((string) ($fields[$key] ?? ''))])
            ->all();

        return hash('sha256', json_encode($source, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /** @return array<int, string> */
    private function contentChunks(string $content): array
    {
        $blocks = preg_split('/(?=<(?:p|h2|h3|ul|ol|blockquote)\b)/i', $content, -1, PREG_SPLIT_NO_EMPTY) ?: [$content];
        $chunks = [];
        $current = '';

        foreach ($blocks as $block) {
            if ($current !== '' && mb_strlen($current.$block) > 9000) {
                $chunks[] = $current;
                $current = '';
            }
            $current .= $block;
        }

        if ($current !== '') {
            $chunks[] = $current;
        }

        return $chunks ?: [$content];
    }

    /** @return array<int, array<string, string>> */
    private function contentBatches(string $content): array
    {
        $batches = [];
        $batch = [];
        $batchLength = 0;

        foreach ($this->contentChunks($content) as $index => $chunk) {
            $length = mb_strlen($chunk);
            if ($batch !== [] && $batchLength + $length > 35000) {
                $batches[] = $batch;
                $batch = [];
                $batchLength = 0;
            }
            $batch['content_'.$index] = $chunk;
            $batchLength += $length;
        }

        if ($batch !== []) {
            $batches[] = $batch;
        }

        return $batches;
    }

    private function hasMatchingStructure(string $source, string $translated): bool
    {
        return $this->structureSignature($source) === $this->structureSignature($translated);
    }

    /** @return array{h2: int, h3: int, links: array<int, string>} */
    private function structureSignature(string $html): array
    {
        $signature = ['h2' => 0, 'h3' => 0, 'links' => []];
        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        try {
            if (! $document->loadHTML('<?xml encoding="UTF-8"><div>'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)) {
                return $signature;
            }
            $signature['h2'] = $document->getElementsByTagName('h2')->length;
            $signature['h3'] = $document->getElementsByTagName('h3')->length;
            foreach ($document->getElementsByTagName('a') as $link) {
                $signature['links'][] = $link->getAttribute('href').'|'.$link->getAttribute('target').'|'.$link->getAttribute('rel');
            }
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }

        return $signature;
    }

    private function normalizeSource(string $value): string
    {
        return trim(preg_replace('/\s+/u', ' ', str_replace(["\r\n", "\r"], "\n", $value)) ?? '');
    }

    private function service(): DeepLTranslationService|MicrosoftTranslatorService
    {
        return setting('translation_provider', 'deepl') === 'microsoft' ? $this->microsoft : $this->deepL;
    }
}
