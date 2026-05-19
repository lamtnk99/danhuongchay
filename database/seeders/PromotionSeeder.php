<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        Promotion::query()
            ->whereIn('placement', ['home', 'popup'])
            ->delete();

        $promotions = [
            [
                'title' => 'Lẩu Nấm Thập Tam Hương',
                'subtitle' => 'Signature mới từ menu Hải Phòng',
                'description' => 'Nước lẩu 13 vị thảo mộc phương Đông kết hợp hệ sinh thái nấm quý, phù hợp cho nhóm 2-4 người.',
                'badge' => 'Món signature',
                'button_text' => 'Xem món ngay',
                'button_link' => '/mon-an/lau-nam-thap-tam-huong',
                'image' => '/images/menu-hai-phong/page-07-01.webp',
                'placement' => 'home',
                'template' => 'split',
                'accent_color' => '#047857',
                'sort_order' => 1,
                'show_once' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Mâm Cuốn Đàn Hương',
                'subtitle' => 'Gợi ý bữa chay nhẹ cho nhóm bạn',
                'description' => 'Lá lốt cuốn nấm áp chảo, bánh hỏi dẻo và rau xanh dùng cùng mắm hạt đặc trưng.',
                'badge' => 'Bán chạy',
                'button_text' => 'Đặt bàn thử món',
                'button_link' => '/dat-ban',
                'image' => '/images/menu-hai-phong/page-06-01.webp',
                'placement' => 'home',
                'template' => 'split',
                'accent_color' => '#0f766e',
                'sort_order' => 2,
                'show_once' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Nấm Đút Lò Phô Mai',
                'subtitle' => 'Vị Âu béo thơm, dễ chia sẻ',
                'description' => 'Nấm và bí đỏ phủ Mozzarella kéo sợi, ăn kèm bánh mì nướng giòn.',
                'badge' => 'Món mới nổi bật',
                'button_text' => 'Xem thực đơn',
                'button_link' => '/thuc-don?category=signature-food',
                'image' => '/images/menu-hai-phong/page-04-01.webp',
                'placement' => 'home',
                'template' => 'split',
                'accent_color' => '#b45309',
                'sort_order' => 3,
                'show_once' => false,
                'is_active' => true,
            ],
            [
                'title' => 'Trà Nhài Cam Nhãn Tươi',
                'subtitle' => 'Tặng kèm cho bàn đặt trước',
                'description' => 'Đặt bàn online trong tuần này, nhóm từ 2 người được tặng phần trà nhài cam nhãn tươi mát.',
                'badge' => 'Quà nhỏ an lành',
                'button_text' => 'Đặt bàn',
                'button_link' => '/dat-ban',
                'image' => '/images/menu-hai-phong/page-48-02.webp',
                'placement' => 'popup',
                'template' => 'center',
                'accent_color' => '#0f766e',
                'sort_order' => 1,
                'show_once' => true,
                'is_active' => true,
            ],
        ];

        foreach ($promotions as $promotion) {
            Promotion::create($promotion);
        }
    }
}

