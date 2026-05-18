<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $promotions = [
            [
                'title' => 'Combo An Lành cho 2 người',
                'subtitle' => 'Gợi ý bữa tối thanh nhẹ',
                'description' => 'Combo gồm gỏi cuốn nấm, cơm gạo lứt hạt sen, đậu hũ sốt nấm và trà thảo mộc. Phù hợp cho bữa chay nhẹ nhàng trong tuần.',
                'badge' => 'Ưu đãi tuần này',
                'button_text' => 'Xem món ngay',
                'button_link' => '/thuc-don',
                'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1200&q=85',
                'placement' => 'home',
                'template' => 'split',
                'accent_color' => '#047857',
                'sort_order' => 1,
                'show_once' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Món mới: Lẩu nấm dưỡng sinh',
                'subtitle' => 'Đậm vị rau củ theo mùa',
                'description' => 'Nước dùng ninh từ củ quả, nấm tươi và thảo mộc nhẹ. Phần lẩu dùng được cho 2-3 người.',
                'badge' => 'Món nổi bật',
                'button_text' => 'Đặt bàn thử món',
                'button_link' => '/dat-ban',
                'image' => 'https://images.unsplash.com/photo-1625398407796-82650a8c135f?auto=format&fit=crop&w=1200&q=85',
                'placement' => 'home',
                'template' => 'split',
                'accent_color' => '#92400e',
                'sort_order' => 2,
                'show_once' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Set trưa văn phòng giảm 12%',
                'subtitle' => 'Ưu đãi ngày thường 11h-14h',
                'description' => 'Áp dụng cho nhóm từ 2 người với set cơm chay theo ngày, tặng thêm trà thanh nhiệt khi đặt bàn online.',
                'badge' => 'Ưu đãi giờ vàng',
                'button_text' => 'Đặt bàn nhận ưu đãi',
                'button_link' => '/dat-ban',
                'image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=1200&q=85',
                'placement' => 'home',
                'template' => 'split',
                'accent_color' => '#b45309',
                'sort_order' => 4,
                'show_once' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Tặng trà thảo mộc cho bàn đặt trước',
                'subtitle' => 'Thông báo từ Đàn Hương Chay',
                'description' => 'Khách đặt bàn online trong tuần này được tặng một bình trà thảo mộc cho nhóm từ 2 người.',
                'badge' => 'Quà nhỏ an lành',
                'button_text' => 'Đặt bàn',
                'button_link' => '/dat-ban',
                'image' => 'https://images.unsplash.com/photo-1545389336-cf090694435e?auto=format&fit=crop&w=1000&q=85',
                'placement' => 'popup',
                'template' => 'center',
                'accent_color' => '#0f766e',
                'sort_order' => 1,
                'show_once' => true,
                'is_active' => true,
            ],
        ];

        foreach ($promotions as $promotion) {
            Promotion::updateOrCreate(
                ['title' => $promotion['title'], 'placement' => $promotion['placement']],
                $promotion
            );
        }
    }
}
