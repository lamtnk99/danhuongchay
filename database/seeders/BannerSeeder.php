<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        Banner::updateOrCreate(
            ['title' => 'Ẩm thực chay thanh lành - Nuôi dưỡng thân tâm'],
            [
                'subtitle' => 'Đàn Hương Chay',
                'description' => 'Món chay Việt hiện đại, nguyên liệu tươi sạch, hương vị nhẹ nhàng và không gian yên tĩnh cho những bữa ăn an nhiên.',
                'button_text' => 'Xem thực đơn',
                'button_link' => route('menu.index'),
                'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1600&q=85',
                'position' => 'home',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );
    }
}
