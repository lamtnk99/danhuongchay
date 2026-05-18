<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Dish;
use Illuminate\Database\Seeder;

class DishSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::menu()->pluck('id', 'name');

        $dishes = [
            [
                'category_id' => $categories['Món khai vị'],
                'name' => 'Gỏi cuốn nấm thanh vị',
                'description' => 'Gỏi cuốn rau tươi, nấm áp chảo và bún gạo, dùng kèm nước chấm mè rang.',
                'ingredients' => 'Bánh tráng, nấm đùi gà, bún gạo, rau thơm, xà lách, mè rang.',
                'price' => 58000,
                'image' => 'https://images.unsplash.com/photo-1609501676725-7186f7347e4a?auto=format&fit=crop&w=900&q=80',
                'is_featured' => true,
                'meta_title' => 'Gỏi cuốn nấm thanh vị | Đàn Hương Chay',
                'meta_description' => 'Gỏi cuốn chay thanh mát với nấm áp chảo, rau tươi và nước chấm mè rang tại Đàn Hương Chay.',
            ],
            [
                'category_id' => $categories['Món khai vị'],
                'name' => 'Đậu hũ non sốt nấm',
                'description' => 'Đậu hũ non mềm mịn phủ sốt nấm thơm, vị nhẹ và giàu đạm thực vật.',
                'ingredients' => 'Đậu hũ non, nấm đông cô, nấm mỡ, nước tương, gừng, hành boa rô.',
                'price' => 72000,
                'image' => 'https://images.unsplash.com/photo-1582878826629-29b7ad1cdc43?auto=format&fit=crop&w=900&q=80',
                'is_featured' => false,
                'meta_title' => 'Đậu hũ non sốt nấm | Đàn Hương Chay',
                'meta_description' => 'Món chay nhẹ nhàng từ đậu hũ non và sốt nấm tự nhiên, phù hợp cho bữa ăn thanh đạm.',
            ],
            [
                'category_id' => $categories['Món chính'],
                'name' => 'Cà ri rau củ Đàn Hương',
                'description' => 'Cà ri chay béo nhẹ từ sữa dừa, rau củ hầm mềm và gia vị ấm áp.',
                'ingredients' => 'Khoai lang, cà rốt, nấm, đậu hũ, sữa dừa, bột cà ri, rau thơm.',
                'price' => 98000,
                'image' => 'https://images.unsplash.com/photo-1585937421612-70a008356fbe?auto=format&fit=crop&w=900&q=80',
                'is_featured' => true,
                'meta_title' => 'Cà ri rau củ Đàn Hương | Đàn Hương Chay',
                'meta_description' => 'Cà ri chay rau củ thơm béo, dùng cùng bánh mì hoặc cơm nóng tại Đàn Hương Chay.',
            ],
            [
                'category_id' => $categories['Món chính'],
                'name' => 'Nấm kho tiêu xanh',
                'description' => 'Nấm kho keo vị tiêu xanh, đậm đà nhưng vẫn giữ được vị ngọt tự nhiên.',
                'ingredients' => 'Nấm rơm, nấm đông cô, tiêu xanh, nước dừa, nước tương, hành boa rô.',
                'price' => 88000,
                'image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=900&q=80',
                'is_featured' => true,
                'meta_title' => 'Nấm kho tiêu xanh | Đàn Hương Chay',
                'meta_description' => 'Nấm kho tiêu xanh đậm vị, thơm nhẹ, là món chính chay được yêu thích tại Đàn Hương Chay.',
            ],
            [
                'category_id' => $categories['Lẩu chay'],
                'name' => 'Lẩu nấm thảo mộc',
                'description' => 'Nước lẩu hầm từ rau củ và thảo mộc, ăn cùng nấm tươi, đậu hũ và rau xanh.',
                'ingredients' => 'Nấm kim châm, nấm đùi gà, nấm đông cô, đậu hũ, rau cải, táo đỏ, kỷ tử.',
                'price' => 245000,
                'image' => 'https://images.unsplash.com/photo-1625398407796-82650a8c135f?auto=format&fit=crop&w=900&q=80',
                'is_featured' => true,
                'meta_title' => 'Lẩu nấm thảo mộc | Đàn Hương Chay',
                'meta_description' => 'Lẩu chay nước thảo mộc thanh ngọt với nhiều loại nấm tươi và rau xanh.',
            ],
            [
                'category_id' => $categories['Cơm/Mì/Bún chay'],
                'name' => 'Cơm chay Đàn Hương',
                'description' => 'Phần cơm đủ vị với rau củ xào, nấm kho, đậu hũ và canh rau theo ngày.',
                'ingredients' => 'Gạo lứt hoặc gạo trắng, rau củ, nấm kho, đậu hũ, canh rau.',
                'price' => 79000,
                'image' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=900&q=80',
                'is_featured' => true,
                'meta_title' => 'Cơm chay Đàn Hương | Đàn Hương Chay',
                'meta_description' => 'Cơm chay cân bằng dinh dưỡng, nhiều rau củ và đạm thực vật tại Đàn Hương Chay.',
            ],
            [
                'category_id' => $categories['Cơm/Mì/Bún chay'],
                'name' => 'Bún riêu chay',
                'description' => 'Bún riêu chay vị chua dịu, riêu đậu mềm và rau sống tươi.',
                'ingredients' => 'Bún tươi, cà chua, đậu hũ, nấm, sữa đậu nành, rau sống.',
                'price' => 69000,
                'image' => 'https://images.unsplash.com/photo-1569058242253-92a9c755a0ec?auto=format&fit=crop&w=900&q=80',
                'is_featured' => false,
                'meta_title' => 'Bún riêu chay | Đàn Hương Chay',
                'meta_description' => 'Bún riêu chay thanh nhẹ với riêu đậu, cà chua và rau sống tươi.',
            ],
            [
                'category_id' => $categories['Đồ uống'],
                'name' => 'Trà sen long nhãn',
                'description' => 'Trà sen ủ nhẹ cùng long nhãn, hương thơm dịu và hậu vị thanh.',
                'ingredients' => 'Trà sen, long nhãn, hạt chia, đường phèn vừa đủ.',
                'price' => 45000,
                'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?auto=format&fit=crop&w=900&q=80',
                'is_featured' => false,
                'meta_title' => 'Trà sen long nhãn | Đàn Hương Chay',
                'meta_description' => 'Thức uống chay thanh mát từ trà sen và long nhãn, phù hợp sau bữa ăn.',
            ],
            [
                'category_id' => $categories['Đồ uống'],
                'name' => 'Sữa hạt điều rang',
                'description' => 'Sữa hạt điều rang thơm, không quá ngọt, dùng nóng hoặc lạnh.',
                'ingredients' => 'Hạt điều, yến mạch, đường thốt nốt, muối hồng.',
                'price' => 52000,
                'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?auto=format&fit=crop&w=900&q=80',
                'is_featured' => true,
                'meta_title' => 'Sữa hạt điều rang | Đàn Hương Chay',
                'meta_description' => 'Sữa hạt điều rang thơm béo nhẹ, thức uống lành mạnh tại Đàn Hương Chay.',
            ],
        ];

        foreach ($dishes as $dish) {
            Dish::updateOrCreate(
                ['name' => $dish['name']],
                $dish
            );
        }
    }
}
