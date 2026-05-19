<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
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

