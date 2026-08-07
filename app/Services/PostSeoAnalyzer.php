<?php

namespace App\Services;

use App\Models\Post;
use DOMDocument;
use Illuminate\Support\Str;

class PostSeoAnalyzer
{
    public function __construct(private readonly ArticlePresentationService $articles) {}

    /** @return array{score: int, status: string, checks: array<int, array<string, mixed>>, warnings: array<int, array<string, string>>, metrics: array<string, int>} */
    public function analyze(array $data, ?int $postId = null): array
    {
        $title = trim((string) ($data['title'] ?? ''));
        $metaTitle = trim((string) ($data['meta_title'] ?? '')) ?: $title;
        $description = trim((string) ($data['meta_description'] ?? '')) ?: trim((string) ($data['excerpt'] ?? ''));
        $slug = trim((string) ($data['slug'] ?? '')) ?: Str::slug($title);
        $content = (string) ($data['content'] ?? '');
        $keyword = trim((string) ($data['focus_keyword'] ?? '')) ?: $this->suggestFocusKeyword($title);
        $presentation = $this->articles->present($content);
        $dom = $this->inspectHtml($content);
        $normalizedKeyword = $this->normalize($keyword);
        $intro = $dom['paragraphs'][0] ?? '';
        $headingText = implode(' ', $dom['headings']);
        $relatedCount = count(array_filter((array) ($data['related_post_ids'] ?? [])));
        $hasInternalLink = $relatedCount > 0 || $this->hasInternalLink($dom['links']);

        $definitions = [
            ['key' => 'keyword_title', 'label' => 'Từ khóa có trong tiêu đề SEO', 'weight' => 12, 'pass' => $this->contains($metaTitle, $normalizedKeyword), 'hint' => 'Đưa từ khóa chính vào tiêu đề một cách tự nhiên.'],
            ['key' => 'keyword_slug', 'label' => 'Từ khóa có trong đường dẫn', 'weight' => 8, 'pass' => $this->contains(str_replace('-', ' ', $slug), $normalizedKeyword), 'hint' => 'Rút gọn slug và thêm từ khóa chính.'],
            ['key' => 'keyword_description', 'label' => 'Từ khóa có trong mô tả SEO', 'weight' => 10, 'pass' => $this->contains($description, $normalizedKeyword), 'hint' => 'Nhắc từ khóa trong meta description.'],
            ['key' => 'keyword_intro', 'label' => 'Từ khóa có trong đoạn mở đầu', 'weight' => 10, 'pass' => $this->contains($intro, $normalizedKeyword), 'hint' => 'Giới thiệu chủ đề ngay trong đoạn đầu tiên.'],
            ['key' => 'keyword_heading', 'label' => 'Từ khóa có trong H2 hoặc H3', 'weight' => 8, 'pass' => $this->contains($headingText, $normalizedKeyword), 'hint' => 'Thêm từ khóa vào một đầu mục phù hợp.'],
            ['key' => 'title_length', 'label' => 'Tiêu đề SEO dài 30–65 ký tự', 'weight' => 10, 'pass' => mb_strlen($metaTitle) >= 30 && mb_strlen($metaTitle) <= 65, 'hint' => 'Giữ tiêu đề SEO trong khoảng 30–65 ký tự.'],
            ['key' => 'description_length', 'label' => 'Mô tả SEO dài 120–165 ký tự', 'weight' => 10, 'pass' => mb_strlen($description) >= 120 && mb_strlen($description) <= 165, 'hint' => 'Viết mô tả SEO rõ ràng trong khoảng 120–165 ký tự.'],
            ['key' => 'heading_structure', 'label' => 'Cấu trúc đầu mục hợp lý, không có H1', 'weight' => 8, 'pass' => count($dom['headings']) >= 2 && $dom['h1_count'] === 0, 'hint' => 'Dùng ít nhất hai H2/H3 và không dùng H1 trong nội dung.'],
            ['key' => 'internal_link', 'label' => 'Có liên kết nội bộ hoặc bài liên quan', 'weight' => 10, 'pass' => $hasInternalLink, 'hint' => 'Chọn ít nhất một bài liên quan hoặc chèn link nội bộ.'],
            ['key' => 'thumbnail', 'label' => 'Có ảnh đại diện', 'weight' => 5, 'pass' => filter_var($data['thumbnail_present'] ?? false, FILTER_VALIDATE_BOOLEAN), 'hint' => 'Chọn ảnh đại diện cho bài viết.'],
            ['key' => 'unique_keyword', 'label' => 'Từ khóa chính không trùng bài khác', 'weight' => 9, 'pass' => $normalizedKeyword !== '' && ! $this->keywordExists($keyword, $postId), 'hint' => 'Chọn từ khóa chính khác với các bài đã có.'],
        ];

        $score = collect($definitions)->where('pass', true)->sum('weight');
        $checks = collect($definitions)->map(fn (array $check): array => [
            'key' => $check['key'],
            'label' => $check['label'],
            'weight' => $check['weight'],
            'state' => $check['pass'] ? 'pass' : 'improve',
            'message' => $check['pass'] ? 'Đạt' : $check['hint'],
        ])->all();

        return [
            'score' => $score,
            'status' => $score >= 80 ? 'good' : ($score >= 55 ? 'okay' : 'poor'),
            'checks' => $checks,
            'warnings' => $this->warnings($content, $dom, $normalizedKeyword, $presentation['word_count']),
            'metrics' => [
                'word_count' => $presentation['word_count'],
                'reading_minutes' => $presentation['reading_minutes'],
                'title_length' => mb_strlen($metaTitle),
                'description_length' => mb_strlen($description),
                'heading_count' => count($dom['headings']),
                'image_count' => count($dom['images']),
            ],
        ];
    }

    private function normalize(string $value): string
    {
        return trim(preg_replace('/\s+/u', ' ', Str::lower(Str::ascii($value))) ?? '');
    }

    public function suggestFocusKeyword(string $title): string
    {
        $mainTitle = preg_split('/[|–—:]/u', trim($title), 2)[0] ?? '';
        $words = preg_split('/\s+/u', trim($mainTitle), -1, PREG_SPLIT_NO_EMPTY) ?: [];

        return implode(' ', array_slice($words, 0, 7));
    }

    private function contains(string $haystack, string $normalizedNeedle): bool
    {
        return $normalizedNeedle !== '' && str_contains($this->normalize($haystack), $normalizedNeedle);
    }

    private function keywordExists(string $keyword, ?int $postId): bool
    {
        if ($keyword === '') {
            return false;
        }

        return Post::query()
            ->when($postId, fn ($query) => $query->whereKeyNot($postId))
            ->get(['focus_keyword'])
            ->contains(fn (Post $post): bool => $this->normalize((string) $post->focus_keyword) === $this->normalize($keyword));
    }

    /** @return array{paragraphs: array<int, string>, headings: array<int, string>, links: array<int, array{href: string, text: string}>, images: array<int, array{src: string, alt: string, width: int}>, h1_count: int, empty_headings: int} */
    private function inspectHtml(string $html): array
    {
        $result = ['paragraphs' => [], 'headings' => [], 'links' => [], 'images' => [], 'h1_count' => 0, 'empty_headings' => 0];
        if (trim($html) === '') {
            return $result;
        }

        $document = new DOMDocument('1.0', 'UTF-8');
        $previous = libxml_use_internal_errors(true);
        try {
            $document->loadHTML('<?xml encoding="UTF-8"><div>'.$html.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
            foreach ($document->getElementsByTagName('p') as $node) {
                $result['paragraphs'][] = trim($node->textContent);
            }
            foreach (['h2', 'h3'] as $tag) {
                foreach ($document->getElementsByTagName($tag) as $node) {
                    $text = trim($node->textContent);
                    $result['headings'][] = $text;
                    $result['empty_headings'] += $text === '' ? 1 : 0;
                }
            }
            $result['h1_count'] = $document->getElementsByTagName('h1')->length;
            foreach ($document->getElementsByTagName('a') as $node) {
                $result['links'][] = ['href' => $node->getAttribute('href'), 'text' => trim($node->textContent)];
            }
            foreach ($document->getElementsByTagName('img') as $node) {
                $result['images'][] = [
                    'src' => $node->getAttribute('src'),
                    'alt' => trim($node->getAttribute('alt')),
                    'width' => (int) $node->getAttribute('width'),
                ];
            }
        } catch (\Throwable) {
            // Invalid legacy HTML is reported through the structural checks without breaking the form.
        } finally {
            libxml_clear_errors();
            libxml_use_internal_errors($previous);
        }

        return $result;
    }

    private function hasInternalLink(array $links): bool
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST);
        foreach ($links as $link) {
            $href = $link['href'];
            if (str_starts_with($href, '/') || ($host && parse_url($href, PHP_URL_HOST) === $host)) {
                return true;
            }
        }

        return false;
    }

    private function warnings(string $content, array $dom, string $keyword, int $wordCount): array
    {
        $warnings = [];
        foreach ($dom['paragraphs'] as $paragraph) {
            if ($this->articles->wordCount($paragraph) > 120) {
                $warnings[] = ['key' => 'long_paragraph', 'message' => 'Có đoạn văn dài trên 120 từ; nên tách nhỏ để dễ đọc.'];
                break;
            }
        }
        if ($wordCount > 350 && count($dom['headings']) < 2) {
            $warnings[] = ['key' => 'long_section', 'message' => 'Nội dung dài nhưng ít đầu mục; nên chia thêm H2/H3.'];
        }
        if ($wordCount >= 600 && count($dom['images']) === 0) {
            $warnings[] = ['key' => 'missing_content_image', 'message' => 'Bài khá dài nhưng chưa có ảnh minh họa trong nội dung.'];
        }
        if (collect($dom['images'])->contains(fn (array $image): bool => $image['alt'] === '')) {
            $warnings[] = ['key' => 'missing_image_alt', 'message' => 'Có ảnh chưa có văn bản thay thế (alt).'];
        }
        if (collect($dom['images'])->contains(fn (array $image): bool => in_array($this->normalize($image['alt']), ['anh', 'anh 1', 'hinh anh', 'image', 'photo'], true))) {
            $warnings[] = ['key' => 'generic_image_alt', 'message' => 'Có alt ảnh quá chung chung; hãy mô tả đúng nội dung ảnh.'];
        }
        if (collect($dom['images'])->contains(fn (array $image): bool => $image['width'] > 1800)) {
            $warnings[] = ['key' => 'oversized_content_image', 'message' => 'Có ảnh rộng trên 1.800 px; nên tối ưu lại để trang tải nhanh hơn.'];
        }
        if ($dom['empty_headings'] > 0) {
            $warnings[] = ['key' => 'empty_heading', 'message' => 'Có đầu mục đang để trống.'];
        }
        foreach ($dom['links'] as $link) {
            if (in_array($this->normalize($link['text']), ['xem tai day', 'bam vao day', 'xem them'], true)) {
                $warnings[] = ['key' => 'generic_anchor', 'message' => 'Có link dùng chữ chung chung; hãy mô tả rõ nội dung liên kết.'];
                break;
            }
        }
        if (! collect($dom['links'])->contains(fn (array $link): bool => str_starts_with($link['href'], 'http') && ! $this->hasInternalLink([$link]))) {
            $warnings[] = ['key' => 'external_link', 'message' => 'Chưa có liên kết ngoài tới nguồn tham khảo đáng tin cậy.'];
        }
        if ($keyword !== '' && $wordCount > 0) {
            $occurrences = substr_count($this->normalize(strip_tags($content)), $keyword);
            $density = ($occurrences * max(1, count(explode(' ', $keyword)))) / $wordCount * 100;
            if ($density > 2.5) {
                $warnings[] = ['key' => 'keyword_density', 'message' => 'Từ khóa xuất hiện khá dày; ưu tiên cách viết tự nhiên.'];
            }
        }

        return collect($warnings)->unique('key')->values()->all();
    }
}
