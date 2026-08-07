<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use App\Services\BlogTranslationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BlogTranslationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_it_translates_long_html_in_chunks_and_preserves_links_and_headings(): void
    {
        SiteSetting::set('translation_enabled', '1', 'boolean', 'translation');
        SiteSetting::set('translation_provider', 'deepl', 'text', 'translation');
        SiteSetting::set('deepl_api_key', 'test-key', 'password', 'translation');
        SiteSetting::set('deepl_api_plan', 'free', 'text', 'translation');

        Http::fake(function (Request $request) {
            if (str_ends_with($request->url(), '/v2/usage')) {
                return Http::response(['character_count' => 100, 'character_limit' => 500000], 200);
            }

            $translations = collect($request->data()['text'] ?? [])->map(fn (string $text): array => [
                'text' => str_replace(['Tiêu đề', 'Nội dung'], ['Title', 'Content'], $text),
            ])->all();

            return Http::response(['translations' => $translations], 200);
        });

        $paragraphs = implode('', array_fill(0, 180, '<p>Nội dung bài viết khá dài để kiểm tra việc chia khối dịch tự động an toàn.</p>'));
        $content = '<h2>Tiêu đề phần một</h2>'.$paragraphs
            .'<h3>Tiêu đề nhỏ</h3><p><a href="/blog/bai-lien-quan" rel="related">Nội dung liên kết</a></p>';

        $result = app(BlogTranslationService::class)->translate([
            'title' => 'Tiêu đề bài viết',
            'content' => $content,
            'focus_keyword' => 'bài viết dài',
        ]);

        $this->assertGreaterThan(12000, mb_strlen($content));
        $this->assertStringContainsString('<h2>Title phần một</h2>', $result['translations']['content']);
        $this->assertStringContainsString('href="/blog/bai-lien-quan"', $result['translations']['content']);
        $this->assertSame(64, strlen($result['source_hash']));
        $this->assertSame('deepl', $result['provider']);
    }
}
