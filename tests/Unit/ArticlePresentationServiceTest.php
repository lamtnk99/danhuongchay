<?php

namespace Tests\Unit;

use App\Services\ArticlePresentationService;
use PHPUnit\Framework\TestCase;

class ArticlePresentationServiceTest extends TestCase
{
    public function test_it_builds_toc_in_document_order_and_keeps_duplicate_ids_unique(): void
    {
        $result = (new ArticlePresentationService)->present(
            '<p>Mở đầu bài viết</p><h2>Không gian quán</h2><p>Nội dung</p><h3>Bàn riêng</h3><h2>Không gian quán</h2>',
            'vi'
        );

        $this->assertSame(['khong-gian-quan', 'ban-rieng', 'khong-gian-quan-2'], array_column($result['toc'], 'id'));
        $this->assertSame([2, 3, 2], array_column($result['toc'], 'level'));
        $this->assertStringContainsString('id="khong-gian-quan-2"', $result['html']);
        $this->assertSame(1, $result['reading_minutes']);
    }

    public function test_it_hides_toc_when_article_has_fewer_than_two_headings(): void
    {
        $result = (new ArticlePresentationService)->present('<h2>Một đầu mục</h2><p>Nội dung ngắn</p>');

        $this->assertSame([], $result['toc']);
        $this->assertStringContainsString('id="mot-dau-muc"', $result['html']);
    }

    public function test_it_uses_english_reading_speed(): void
    {
        $words = implode(' ', array_fill(0, 401, 'word'));
        $result = (new ArticlePresentationService)->present('<p>'.$words.'</p>', 'en');

        $this->assertSame(401, $result['word_count']);
        $this->assertSame(3, $result['reading_minutes']);
    }

    public function test_it_preserves_article_image_structure_and_metadata(): void
    {
        $html = '<p>Mở đầu.</p><figure data-blog-image data-media-id="7" data-layout="wide">'
            .'<img src="/storage/posts/content/example.webp" alt="Mâm cơm chay" width="1200" height="800" loading="lazy">'
            .'<figcaption>Bữa ăn thanh nhẹ.</figcaption></figure>';

        $result = (new ArticlePresentationService)->present($html);

        $this->assertStringContainsString('data-media-id="7"', $result['html']);
        $this->assertStringContainsString('src="/storage/posts/content/example.webp"', $result['html']);
        $this->assertStringContainsString('alt="Mâm cơm chay"', $result['html']);
        $this->assertStringContainsString('<figcaption>Bữa ăn thanh nhẹ.</figcaption>', $result['html']);
    }
}
