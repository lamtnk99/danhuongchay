<?php

namespace Database\Seeders;

use App\Models\NavigationMenu;
use Illuminate\Database\Seeder;

class NavigationMenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            ['title' => 'Trang chủ', 'url' => '/', 'location' => 'header', 'sort_order' => 1],
            ['title' => 'Giới thiệu', 'url' => '/gioi-thieu', 'location' => 'header', 'sort_order' => 2],
            ['title' => 'Không gian', 'url' => '/khong-gian', 'location' => 'header', 'sort_order' => 3],
            ['title' => 'Thực đơn', 'url' => '/thuc-don', 'location' => 'header', 'sort_order' => 4],
            ['title' => 'Blog', 'url' => '/blog', 'location' => 'header', 'sort_order' => 5],
            ['title' => 'Đặt bàn', 'url' => '/dat-ban', 'location' => 'header', 'sort_order' => 6],
            ['title' => 'Liên hệ', 'url' => '/lien-he', 'location' => 'header', 'sort_order' => 7],
            ['title' => 'Giới thiệu', 'url' => '/gioi-thieu', 'location' => 'footer', 'sort_order' => 1],
            ['title' => 'Không gian', 'url' => '/khong-gian', 'location' => 'footer', 'sort_order' => 2],
            ['title' => 'Thực đơn', 'url' => '/thuc-don', 'location' => 'footer', 'sort_order' => 3],
            ['title' => 'Chính sách đặt bàn', 'url' => '/trang/chinh-sach-dat-ban', 'location' => 'footer', 'sort_order' => 4],
            ['title' => 'Chính sách bảo mật', 'url' => '/trang/chinh-sach-bao-mat', 'location' => 'footer', 'sort_order' => 5],
            ['title' => 'Liên hệ', 'url' => '/lien-he', 'location' => 'footer', 'sort_order' => 6],
        ];

        foreach ($menus as $menu) {
            NavigationMenu::updateOrCreate(
                ['title' => $menu['title'], 'location' => $menu['location']],
                $menu + ['is_active' => true, 'open_new_tab' => false]
            );
        }
    }
}
