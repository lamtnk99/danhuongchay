<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Món khai vị', 'type' => 'dish', 'description' => 'Những món nhẹ mở đầu bữa chay thanh lành.', 'sort_order' => 1],
            ['name' => 'Món chính', 'type' => 'dish', 'description' => 'Các món chay đậm vị, cân bằng dinh dưỡng.', 'sort_order' => 2],
            ['name' => 'Lẩu chay', 'type' => 'dish', 'description' => 'Nước lẩu rau củ ngọt tự nhiên, dùng cùng nấm và rau tươi.', 'sort_order' => 3],
            ['name' => 'Cơm/Mì/Bún chay', 'type' => 'dish', 'description' => 'Những phần ăn no bụng, nhẹ nhàng cho mọi thời điểm trong ngày.', 'sort_order' => 4],
            ['name' => 'Đồ uống', 'type' => 'dish', 'description' => 'Thức uống mát lành từ thảo mộc, trái cây và hạt.', 'sort_order' => 5],
            ['name' => 'Ăn chay', 'type' => 'post', 'description' => 'Kiến thức và cảm hứng cho hành trình ăn chay.', 'sort_order' => 1],
            ['name' => 'Sức khỏe', 'type' => 'post', 'description' => 'Gợi ý chăm sóc sức khỏe bằng lối sống lành mạnh.', 'sort_order' => 2],
            ['name' => 'Sống xanh', 'type' => 'post', 'description' => 'Những thói quen nhỏ giúp cuộc sống gần thiên nhiên hơn.', 'sort_order' => 3],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(
                ['name' => $category['name'], 'type' => $category['type']],
                $category + ['is_active' => true]
            );
        }
    }
}
