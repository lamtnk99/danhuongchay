<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Services\PostSeoAnalyzer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostSeoAnalyzerTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    public function test_it_applies_the_documented_one_hundred_point_score(): void
    {
        $description = 'Nhà hàng chay Hải Phòng với không gian thanh tịnh, món ăn theo mùa và nguyên liệu tươi lành dành cho gia đình cùng bạn bè mỗi ngày.';
        $content = '<p>Nhà hàng chay Hải Phòng mang đến trải nghiệm ẩm thực thanh lành.</p>'
            .'<h2>Nhà hàng chay Hải Phòng có gì đặc biệt?</h2><p>Không gian yên tĩnh và gần gũi.</p>'
            .'<h2>Thực đơn theo mùa</h2><p>Xem <a href="https://example.com/source">nguồn nguyên liệu</a>.</p>';

        $result = app(PostSeoAnalyzer::class)->analyze([
            'title' => 'Nhà hàng chay Hải Phòng thanh tịnh',
            'slug' => 'nha-hang-chay-hai-phong-thanh-tinh',
            'excerpt' => $description,
            'content' => $content,
            'meta_title' => 'Nhà hàng chay Hải Phòng thanh tịnh',
            'meta_description' => $description,
            'focus_keyword' => 'nhà hàng chay Hải Phòng',
            'thumbnail_present' => true,
            'related_post_ids' => [1],
        ]);

        $this->assertSame(100, $result['score']);
        $this->assertSame('good', $result['status']);
        $this->assertCount(11, $result['checks']);
    }

    public function test_it_detects_duplicate_focus_keyword_without_counting_accents_or_case(): void
    {
        Post::query()->firstOrFail()->update(['focus_keyword' => 'Nhà Hàng Chay Hải Phòng']);

        $result = app(PostSeoAnalyzer::class)->analyze(['focus_keyword' => 'nha hang chay hai phong']);
        $check = collect($result['checks'])->firstWhere('key', 'unique_keyword');

        $this->assertSame('improve', $check['state']);
    }

    public function test_list_and_edit_scores_match_when_focus_keyword_uses_title_suggestion(): void
    {
        $data = [
            'title' => 'Cơm chay Hải Phòng: ăn ở đâu để no vừa, thanh nhẹ và đủ chất?',
            'slug' => 'com-chay-hai-phong-an-o-dau-de-no-vua-thanh-nhe-va-du-chat',
            'excerpt' => 'Gợi ý thực tế về cơm chay Hải Phòng dành cho khách muốn ăn ngon, thanh nhẹ và thuận tiện khi đặt bàn.',
            'content' => '<p>Cơm chay Hải Phòng là lựa chọn thanh nhẹ.</p><h2>Cơm chay Hải Phòng ngon</h2><p>Nội dung.</p><h2>Cách chọn món</h2>',
            'meta_title' => 'Cơm chay Hải Phòng: ăn ở đâu để no vừa, thanh nhẹ?',
            'meta_description' => 'Tìm hiểu cơm chay Hải Phòng với gợi ý chọn món, đặt bàn và trải nghiệm bữa chay thanh lành, chỉn chu, phù hợp cho gia đình và nhóm bạn.',
            'thumbnail_present' => true,
            'related_post_ids' => [1],
        ];

        $analyzer = app(PostSeoAnalyzer::class);
        $listResult = $analyzer->analyze($data);
        $editResult = $analyzer->analyze($data + ['focus_keyword' => 'Cơm chay Hải Phòng']);

        $this->assertSame('Cơm chay Hải Phòng', $analyzer->suggestFocusKeyword($data['title']));
        $this->assertSame($editResult['score'], $listResult['score']);
        $this->assertSame($editResult['checks'], $listResult['checks']);
    }
}
