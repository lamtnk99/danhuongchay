<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Dish;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DanHuongHaiPhongMenuSeeder extends Seeder
{
    public function run(): void
    {
        $this->removeOldSampleMenuData();

        $categories = collect([
            ['name' => 'Signature Food', 'description' => 'Những món đặc trưng của Đàn Hương Hải Phòng.', 'sort_order' => 10],
            ['name' => 'Khai vị', 'description' => 'Món mở đầu thanh nhẹ, cân bằng vị giác.', 'sort_order' => 20],
            ['name' => 'Món chính', 'description' => 'Các món chay đậm vị, giàu dinh dưỡng.', 'sort_order' => 30],
            ['name' => 'Mỳ, Miến & Cơm', 'description' => 'Những phần ăn no bụng, phù hợp bữa chính.', 'sort_order' => 40],
            ['name' => 'Món canh & Lẩu', 'description' => 'Canh và lẩu chay ấm bụng, dùng chung rất hợp.', 'sort_order' => 50],
            ['name' => 'Món thêm', 'description' => 'Các món ăn kèm cho bữa chay tròn vị.', 'sort_order' => 60],
            ['name' => 'Tráng miệng', 'description' => 'Món ngọt thanh, nhẹ nhàng sau bữa ăn.', 'sort_order' => 70],
            ['name' => 'Đồ uống', 'description' => 'Thức uống thảo mộc, trà và nước đóng lon.', 'sort_order' => 80],
        ])->mapWithKeys(function (array $category): array {
            $model = Category::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'description' => $category['description'],
                    'type' => 'dish',
                    'sort_order' => $category['sort_order'],
                    'is_active' => true,
                    'meta_title' => $category['name'].' | Menu Đàn Hương Chay Hải Phòng',
                    'meta_description' => $category['description'],
                ]
            );

            return [$category['name'] => $model];
        });

        $featuredDishNames = [
            'Súp Phật Sơn',
            'Nấm Đút Lò Phô Mai',
            'Mâm Cuốn Đàn Hương',
            'Lẩu Nấm Thập Tam Hương',
            'Lẩu Riêu Chay Hải Phòng',
            'Steak Nấm Hầu Thủ',
        ];

        foreach ($this->dishes() as $index => $dish) {
            $slug = Str::slug($dish['name']);
            $category = $categories[$dish['category']];

            Dish::updateOrCreate(
                ['slug' => $slug],
                [
                    'category_id' => $category->id,
                    'name' => $dish['name'],
                    'description' => $dish['description'],
                    'content' => $dish['description'],
                    'ingredients' => $dish['ingredients'] ?? null,
                    'price' => $dish['price'],
                    'sale_price' => null,
                    'image' => $dish['image'],
                    'gallery' => [],
                    'is_featured' => in_array($dish['name'], $featuredDishNames, true),
                    'is_active' => true,
                    'sort_order' => $index + 1,
                    'meta_title' => $dish['name'].' | Đàn Hương Chay Hải Phòng',
                    'meta_description' => Str::limit($dish['description'], 155),
                    'meta_keywords' => $dish['name'].', Đàn Hương Chay Hải Phòng, món chay ngon',
                ]
            );
        }
    }

    private function dishes(): array
    {
        return [
            [
                'category' => 'Signature Food',
                'name' => 'Súp Phật Sơn',
                'description' => 'Tinh túy nấm quý nhập khẩu hòa quyện cùng vị ngọt thanh của táo đỏ, kỷ tử trong nước dùng nấu chậm, mang đến hương vị bổ dưỡng từ ẩm thực Trung Hoa.',
                'ingredients' => 'Nấm quý nhập khẩu, táo đỏ, kỷ tử, nước dùng chay nấu chậm.',
                'price' => 63000,
                'image' => '/images/menu-hai-phong/page-03-01.webp',
            ],
            [
                'category' => 'Signature Food',
                'name' => 'Salad Hàn Ngọc',
                'description' => 'Món khai vị thanh mát từ đậu hũ non mềm mịn, rong nho giòn sực và xà lách tươi xanh, quyện cùng sốt mè rang Goma thơm bùi.',
                'ingredients' => 'Đậu hũ non, rong nho, xà lách, sốt mè rang Goma.',
                'price' => 95000,
                'image' => '/images/menu-hai-phong/page-03-02.webp',
            ],
            [
                'category' => 'Signature Food',
                'name' => 'Nấm Đút Lò Phô Mai',
                'description' => 'Vị béo ngậy đặc trưng của phô mai Mozzarella kéo sợi bao phủ các loại nấm và bí đỏ bùi vị, ăn kèm bánh mì lát nướng giòn.',
                'ingredients' => 'Nấm, bí đỏ, phô mai Mozzarella, bánh mì nướng.',
                'price' => 125000,
                'image' => '/images/menu-hai-phong/page-04-01.webp',
            ],
            [
                'category' => 'Signature Food',
                'name' => 'Salad Đàn Hương',
                'description' => 'Món salad thanh nhẹ đầy dưỡng chất với nấm mỡ và củ dền nướng đậm đà, kết hợp xà lách tươi sạch, hạt điều rang thơm bùi.',
                'ingredients' => 'Nấm mỡ, củ dền nướng, xà lách, hạt điều.',
                'price' => 98000,
                'image' => '/images/menu-hai-phong/page-04-02.webp',
            ],
            [
                'category' => 'Signature Food',
                'name' => 'Nem Đàn Hương',
                'description' => 'Những chiếc nem vuông vàng óng lấy cảm hứng từ đất cảng, gói trọn tinh hoa của năm loại nấm quý và rau củ tươi ngon.',
                'ingredients' => 'Năm loại nấm, rau củ tươi, bánh nem.',
                'price' => 105000,
                'image' => '/images/menu-hai-phong/page-05-01.webp',
            ],
            [
                'category' => 'Signature Food',
                'name' => 'Mâm Cuốn Đàn Hương',
                'description' => 'Mâm cuốn đủ đầy với lá lốt cuốn nấm áp chảo vàng óng, bánh hỏi dẻo và rau xanh mướt mắt, dùng cùng mắm hạt đặc trưng.',
                'ingredients' => 'Lá lốt, nấm áp chảo, bánh hỏi, rau xanh, mắm hạt chay.',
                'price' => 125000,
                'image' => '/images/menu-hai-phong/page-06-01.webp',
            ],
            [
                'category' => 'Signature Food',
                'name' => 'Lẩu Nấm Thập Tam Hương',
                'description' => 'Bản sắc độc bản từ 13 vị thảo mộc phương Đông kết hợp cùng hệ sinh thái nấm quý, mang lại trải nghiệm ẩm thực đậm chiều sâu.',
                'ingredients' => '13 vị thảo mộc, các loại nấm, nước lẩu chay.',
                'price' => 350000,
                'image' => '/images/menu-hai-phong/page-07-01.webp',
            ],
            [
                'category' => 'Signature Food',
                'name' => 'Lẩu Riêu Chay Hải Phòng',
                'description' => 'Hương vị lẩu riêu truyền thống đậm đà xứ Cảng với nước dùng chua thanh, riêu chay béo ngậy từ đậu nành.',
                'ingredients' => 'Riêu chay đậu nành, nước lẩu chua thanh, rau và nấm.',
                'price' => 315000,
                'image' => '/images/menu-hai-phong/page-08-01.webp',
            ],
            [
                'category' => 'Signature Food',
                'name' => 'Chè Bạch Tịnh',
                'description' => 'Món chè trắng tinh khôi, gói trọn sự thanh sạch của đất trời, mang lại cảm giác bình yên trong từng muỗng nhỏ.',
                'ingredients' => 'Nguyên liệu chè thanh mát theo công thức Đàn Hương.',
                'price' => 38000,
                'image' => '/images/menu-hai-phong/page-09-01.webp',
            ],
            [
                'category' => 'Khai vị',
                'name' => 'Soup Kem Bí Đỏ',
                'description' => 'Bí đỏ tươi nguyên chất ninh nhừ, hòa quyện cùng sữa tươi béo ngậy và hương thảo mộc đặc trưng.',
                'ingredients' => 'Bí đỏ, sữa tươi, thảo mộc.',
                'price' => 39000,
                'image' => '/images/menu-hai-phong/page-10-01.webp',
            ],
            [
                'category' => 'Khai vị',
                'name' => 'Salad Nhiệt Đới',
                'description' => 'Vị tươi mát của trái cây nhiệt đới quyện cùng xà lách giòn rau và sốt mè rang chua ngọt.',
                'ingredients' => 'Trái cây nhiệt đới, xà lách, sốt mè rang.',
                'price' => 95000,
                'image' => '/images/menu-hai-phong/page-11-01.webp',
            ],
            [
                'category' => 'Khai vị',
                'name' => 'Giá Trộn Kiểu Nhật',
                'description' => 'Giá đậu nhà làm tươi sạch, giòn ngọt tự nhiên, kết hợp cùng sốt tương Nhật đậm đà.',
                'ingredients' => 'Giá đậu, sốt tương Nhật.',
                'price' => 39000,
                'image' => '/images/menu-hai-phong/page-12-01.webp',
            ],
            [
                'category' => 'Khai vị',
                'name' => 'Miến Trộn Thái Lan',
                'description' => 'Miến sợi trong dai mềm vừa đủ, hòa quyện trong phần nước sốt chua cay và rau củ mix vị thanh mát.',
                'ingredients' => 'Miến, rau củ, sốt Thái chua cay.',
                'price' => 125000,
                'image' => '/images/menu-hai-phong/page-13-01.webp',
            ],
            [
                'category' => 'Khai vị',
                'name' => 'Gỏi Dừa',
                'description' => 'Dừa tươi mọng nước, nấm nướng và rau thơm cân bằng trong làn sốt Thái chua cay đậm đà.',
                'ingredients' => 'Dừa tươi, nấm nướng, rau thơm, sốt Thái.',
                'price' => 110000,
                'image' => '/images/menu-hai-phong/page-14-01.webp',
            ],
            [
                'category' => 'Khai vị',
                'name' => 'Gỏi Cuốn Đàn Hương',
                'description' => 'Nấm nướng xá xíu đậm đà và rau củ tươi giòn, nâng vị khi quyện cùng sốt đậu phộng béo ngậy.',
                'ingredients' => 'Nấm nướng xá xíu, rau củ, sốt đậu phộng.',
                'price' => 68000,
                'image' => '/images/menu-hai-phong/page-15-01.webp',
            ],
            [
                'category' => 'Khai vị',
                'name' => 'Phở Cuốn Hà Thành',
                'description' => 'Phở lá cuốn rau thơm cùng nấm, đậu hũ, dùng kèm rau củ ngâm chua và nước chấm chua ngọt.',
                'ingredients' => 'Phở lá, rau thơm, nấm, đậu hũ, rau củ ngâm.',
                'price' => 75000,
                'image' => '/images/menu-hai-phong/page-16-01.webp',
            ],
            [
                'category' => 'Khai vị',
                'name' => 'Nem Vị Cảng',
                'description' => 'Chả giò giòn tan với nhân bắp non ngọt bùi và nấm thanh tao, cân bằng vị giác cùng đồ chua dịu nhẹ.',
                'ingredients' => 'Bắp non, nấm, đồ chua.',
                'price' => 75000,
                'image' => '/images/menu-hai-phong/page-17-01.webp',
            ],
            [
                'category' => 'Khai vị',
                'name' => 'Chả Giò Bắp Non',
                'description' => 'Chả giò giòn tan với nhân bắp non ngọt bùi và nấm thanh tao, dùng cùng đồ chua dịu nhẹ.',
                'ingredients' => 'Bắp non, nấm, bánh chả giò, đồ chua.',
                'price' => 75000,
                'image' => '/images/menu-hai-phong/page-18-01.webp',
            ],
            [
                'category' => 'Món chính',
                'name' => 'Mẹt Bánh Hỏi Nấm Nướng',
                'description' => 'Bánh hỏi rau củ sắc màu sánh cùng nấm nướng BBQ thơm lừng hương khói, quyện trong vị mắm chua ngọt.',
                'ingredients' => 'Bánh hỏi, nấm nướng BBQ, rau xanh, mắm chay.',
                'price' => 125000,
                'image' => '/images/menu-hai-phong/page-20-01.webp',
            ],
            [
                'category' => 'Món chính',
                'name' => 'Tomyum Thái Lan',
                'description' => 'Nước dùng Tomyum chuẩn vị Thái, béo ngậy nước cốt dừa, hòa quyện vị chua cay đặc trưng, ăn kèm bún tươi.',
                'ingredients' => 'Nước dùng Tomyum, nước cốt dừa, bún tươi, rau nấm.',
                'price' => 95000,
                'image' => '/images/menu-hai-phong/page-21-01.webp',
            ],
            [
                'category' => 'Món chính',
                'name' => 'Tảo Xoắn Chile Xào Sả Ớt',
                'description' => 'Tảo ống Chile nhập khẩu với kết cấu giòn sần sật, xào cùng sả ớt thơm nồng, cay tê đậm đà.',
                'ingredients' => 'Tảo ống Chile, sả, ớt.',
                'price' => 125000,
                'image' => '/images/menu-hai-phong/page-22-01.webp',
            ],
            [
                'category' => 'Món chính',
                'name' => 'Nấm Xúc Bánh Đa Cố Đô',
                'description' => 'Nấm hỗn hợp và đậu khuôn cùng gia vị thảo mộc nồng nàn, dùng với bánh đa nướng vàng óng.',
                'ingredients' => 'Nấm hỗn hợp, đậu khuôn, thảo mộc, bánh đa nướng.',
                'price' => 89000,
                'image' => '/images/menu-hai-phong/page-23-01.webp',
            ],
            [
                'category' => 'Món chính',
                'name' => 'Shitake Sốt Hồng Xíu',
                'description' => 'Nấm đùi gà Nhật hầm mềm mọng, thấm trong nước sốt hồng xíu kết chế từ 6 loại nấm thượng hạng và thảo mộc tự nhiên.',
                'ingredients' => 'Nấm Shitake, sốt hồng xíu, nấm thượng hạng, thảo mộc.',
                'price' => 95000,
                'image' => '/images/menu-hai-phong/page-24-01.webp',
            ],
            [
                'category' => 'Món chính',
                'name' => 'Steak Nấm Hầu Thủ',
                'description' => 'Nấm hầu thủ áp chảo mềm ngọt tự nhiên, kết hợp rau củ đút lò, bánh mì giòn và sốt tiêu kiểu Pháp cay nồng.',
                'ingredients' => 'Nấm hầu thủ, rau củ đút lò, bánh mì, sốt tiêu.',
                'price' => 175000,
                'image' => '/images/menu-hai-phong/page-24-01.webp',
            ],
            [
                'category' => 'Món chính',
                'name' => 'Nấm Xông Thảo Mộc',
                'description' => 'Nấm lộc nhung cuốn nấm bào ngư, xông trên thảo mộc muối nóng và dùng cùng nước mắm gừng ấm áp.',
                'ingredients' => 'Nấm lộc nhung, nấm bào ngư, thảo mộc, muối nóng, mắm gừng chay.',
                'price' => 115000,
                'image' => '/images/menu-hai-phong/page-25-01.webp',
            ],
            [
                'category' => 'Món chính',
                'name' => 'Nấm Om Tiêu Xanh',
                'description' => 'Vị thanh ngọt của nấm hòa quyện cùng hậu vị cay nồng đặc trưng của tiêu xanh.',
                'ingredients' => 'Nấm, tiêu xanh, sốt om chay.',
                'price' => 75000,
                'image' => '/images/menu-hai-phong/page-26-01.webp',
            ],
            [
                'category' => 'Món chính',
                'name' => 'Đậu Hủ Hòa Điền',
                'description' => 'Món ăn lấy cảm hứng từ ruộng đồng và đất mẹ, nơi vị béo mềm của đậu hũ quyện cùng làn sốt ấm nóng.',
                'ingredients' => 'Đậu hũ, sốt chay ấm nóng.',
                'price' => 95000,
                'image' => '/images/menu-hai-phong/page-26-02.webp',
            ],
            [
                'category' => 'Món chính',
                'name' => 'Nấm Nướng Lá Chuối',
                'description' => 'Nấm bào ngư và đùi gà thấm vị củ nén, gừng, lá chanh, gói trong lá chuối nướng thơm lừng.',
                'ingredients' => 'Nấm bào ngư, nấm đùi gà, củ nén, gừng, lá chanh, lá chuối.',
                'price' => 125000,
                'image' => '/images/menu-hai-phong/page-27-01.webp',
            ],
            [
                'category' => 'Món chính',
                'name' => 'Nấm Đoàn Viên',
                'description' => 'Nấm hỗn hợp chiên bột, mang đến sự kết hợp mộc mạc mà tinh tế cho bữa cơm ấm cúng.',
                'ingredients' => 'Nấm hỗn hợp, bột chiên.',
                'price' => 125000,
                'image' => '/images/menu-hai-phong/page-27-02.webp',
            ],
            [
                'category' => 'Món chính',
                'name' => 'Mẹt Cuốn Rau Rừng',
                'description' => 'Hành trình khám phá ẩm thực nguyên bản với mẹt rau rừng đặc sản tươi sạch được thu hái từ đại ngàn.',
                'ingredients' => 'Rau rừng theo mùa, món cuốn chay.',
                'price' => 135000,
                'image' => '/images/menu-hai-phong/page-27-03.webp',
            ],
            [
                'category' => 'Món chính',
                'name' => 'Mẹt Cuốn Thính',
                'description' => 'Món cuốn thanh nhẹ gồm nấm áp chảo ngọt dai và bì chay trộn thính thơm phức, dùng cùng rau thơm theo mùa.',
                'ingredients' => 'Nấm áp chảo, bì chay, thính, rau thơm.',
                'price' => 115000,
                'image' => '/images/menu-hai-phong/page-27-04.webp',
            ],
            [
                'category' => 'Món chính',
                'name' => 'Cà Tím Nướng Sốt Nhật',
                'description' => 'Cà tím mềm ngọt cuộn chặt nấm tươi dai giòn, nướng chín tới thơm lừng với sốt Nhật.',
                'ingredients' => 'Cà tím, nấm tươi, sốt Nhật.',
                'price' => 105000,
                'image' => '/images/menu-hai-phong/page-28-01.webp',
            ],
            [
                'category' => 'Món chính',
                'name' => 'Rau Luộc Kho Quẹt',
                'description' => 'Rau củ tươi theo mùa dùng cùng kho quẹt kiểu miền Tây, tạo điểm nhấn dân dã cho bữa cơm gia đình.',
                'ingredients' => 'Rau củ theo mùa, kho quẹt chay.',
                'price' => 85000,
                'image' => '/images/menu-hai-phong/page-29-01.webp',
            ],
            [
                'category' => 'Món chính',
                'name' => 'Rau Cải Thìa Xào',
                'description' => 'Vị giòn ngọt của cải thìa kết hợp sốt Trung Hoa và hương thơm đặc trưng từ nấm.',
                'ingredients' => 'Cải thìa, nấm, sốt Trung Hoa.',
                'price' => 65000,
                'image' => '/images/menu-hai-phong/page-29-02.webp',
            ],
            [
                'category' => 'Mỳ, Miến & Cơm',
                'name' => 'Mì Ý Sốt Táo Cà Chua',
                'description' => 'Mì Ý sợi dai, sốt táo tươi và cà chua chua ngọt dịu, dùng kèm nấm, cà chua nướng sốt BBQ kiểu Mỹ.',
                'ingredients' => 'Mì Ý, táo, cà chua, nấm, sốt BBQ.',
                'price' => 135000,
                'image' => '/images/menu-hai-phong/page-30-02.webp',
            ],
            [
                'category' => 'Mỳ, Miến & Cơm',
                'name' => 'Mì Lạnh Zaru Soba',
                'description' => 'Mì lạnh truyền thống Nhật Bản với sợi soba dai mát, dùng kèm sốt chấm Tsuyu chay đậm đà.',
                'ingredients' => 'Mì soba, sốt Tsuyu chay.',
                'price' => 125000,
                'image' => '/images/menu-hai-phong/page-31-01.webp',
            ],
            [
                'category' => 'Mỳ, Miến & Cơm',
                'name' => 'Miến Xào Măng Mầm',
                'description' => 'Miến dong thượng hạng, măng khô Madrak giòn sực và các loại nấm rau củ thấm vị ngọt thanh tự nhiên.',
                'ingredients' => 'Miến dong, măng khô Madrak, nấm, rau củ.',
                'price' => 85000,
                'image' => '/images/menu-hai-phong/page-32-01.webp',
            ],
            [
                'category' => 'Mỳ, Miến & Cơm',
                'name' => 'Thố Miến Trung Hoa',
                'description' => 'Sợi miến Thái dẻo dai hòa quyện cùng nấm, rau củ thanh đạm và nước sốt Trung Hoa trong thố đất nóng hổi.',
                'ingredients' => 'Miến Thái, nấm, rau củ, sốt Trung Hoa.',
                'price' => 65000,
                'image' => '/images/menu-hai-phong/page-33-01.webp',
            ],
            [
                'category' => 'Mỳ, Miến & Cơm',
                'name' => 'Cơm Chiên Thơm',
                'description' => 'Cơm chiên vàng hòa quyện cùng nấm, rau củ và vị chua ngọt thanh mát từ thơm tươi trong nước sốt Thái.',
                'ingredients' => 'Cơm, thơm, nấm, rau củ, sốt Thái.',
                'price' => 105000,
                'image' => '/images/menu-hai-phong/page-34-01.webp',
            ],
            [
                'category' => 'Mỳ, Miến & Cơm',
                'name' => 'Cơm Chiên Lạc Dương',
                'description' => 'Hạt cơm chiên săn kết hợp cơm lứt giòn tan, thấm sốt xá xíu đậm đà cùng các loại nấm rừng thanh ngọt.',
                'ingredients' => 'Cơm, cơm lứt, sốt xá xíu chay, nấm rừng.',
                'price' => 95000,
                'image' => '/images/menu-hai-phong/page-35-01.webp',
            ],
            [
                'category' => 'Mỳ, Miến & Cơm',
                'name' => 'Cơm Trà Tiên Nướng Lá Chuối',
                'description' => 'Cơm đảo nấm, ớt xiêm đồng bào và sốt trà tiên tươi thơm thảo mộc, bọc lá chuối nướng hương khói mộc mạc.',
                'ingredients' => 'Cơm, nấm, ớt xiêm, sốt trà tiên, lá chuối.',
                'price' => 95000,
                'image' => '/images/menu-hai-phong/page-36-01.webp',
            ],
            [
                'category' => 'Mỳ, Miến & Cơm',
                'name' => 'Cơm Đút Lò Kiểu Nhật',
                'description' => 'Cơm trộn nấm nướng, dấm và sốt tương Nhật, phủ phô mai Mozzarella đút lò vàng óng.',
                'ingredients' => 'Cơm, nấm nướng, sốt tương Nhật, phô mai Mozzarella.',
                'price' => 135000,
                'image' => '/images/menu-hai-phong/page-37-01.webp',
            ],
            [
                'category' => 'Món canh & Lẩu',
                'name' => 'Canh Chua Nam Bộ',
                'description' => 'Vị chua thanh của me vườn quyện cùng nấm, đậu bắp và bạc hà giòn ngọt, tái hiện phong vị canh chua Nam Bộ.',
                'ingredients' => 'Me vườn, nấm, đậu bắp, bạc hà.',
                'price' => 85000,
                'image' => '/images/menu-hai-phong/page-39-01.webp',
            ],
            [
                'category' => 'Món canh & Lẩu',
                'name' => 'Canh Miso Rong Biển',
                'description' => 'Phong vị Nhật Bản với tương Miso lên men tự nhiên, hòa quyện cùng nấm tươi mọng nước và rong biển thượng hạng.',
                'ingredients' => 'Miso, nấm tươi, rong biển.',
                'price' => 95000,
                'image' => '/images/menu-hai-phong/page-39-02.webp',
            ],
            [
                'category' => 'Món canh & Lẩu',
                'name' => 'Lẩu Thái',
                'description' => 'Nước dùng lẩu Thái chua cay, dậy hương sả và riềng nồng nàn, quyện cùng nấm tươi mọng nước.',
                'ingredients' => 'Nước lẩu Thái, sả, riềng, nấm tươi.',
                'price' => 305000,
                'image' => '/images/menu-hai-phong/page-40-01.webp',
            ],
            [
                'category' => 'Món thêm',
                'name' => 'Rong Biển Trộn',
                'description' => 'Món ăn kèm thanh nhẹ, giúp bữa ăn thêm cân bằng.',
                'ingredients' => 'Rong biển, gia vị trộn chay.',
                'price' => 15000,
                'image' => '/images/menu-hai-phong/page-41-01.webp',
            ],
            [
                'category' => 'Món thêm',
                'name' => 'Kimchi',
                'description' => 'Kimchi chay chua cay nhẹ, dùng kèm các món chính và lẩu.',
                'ingredients' => 'Rau củ muối kiểu kimchi chay.',
                'price' => 15000,
                'image' => '/images/menu-hai-phong/page-41-02.webp',
            ],
            [
                'category' => 'Món thêm',
                'name' => 'Củ Cải Ngâm',
                'description' => 'Củ cải ngâm giòn mát, làm dịu vị và cân bằng bữa ăn.',
                'ingredients' => 'Củ cải ngâm chua ngọt.',
                'price' => 15000,
                'image' => '/images/menu-hai-phong/page-41-03.webp',
            ],
            [
                'category' => 'Tráng miệng',
                'name' => 'Bánh Bạch Ngọc Sương Phương',
                'description' => 'Bánh trắng ngần như ngọc với kết cấu mềm mịn, hòa quyện sữa tươi, cốt dừa và kem tươi whipping ngọt dịu.',
                'ingredients' => 'Sữa tươi, cốt dừa, kem tươi whipping.',
                'price' => 69000,
                'image' => '/images/menu-hai-phong/page-42-01.webp',
            ],
            [
                'category' => 'Tráng miệng',
                'name' => 'Xôi Xoài',
                'description' => 'Nếp dẻo thơm, xoài chín ngọt mọng và nước cốt dừa béo thanh, mang hương vị nhiệt đới cuốn hút.',
                'ingredients' => 'Nếp, xoài chín, nước cốt dừa.',
                'price' => 55000,
                'image' => '/images/menu-hai-phong/page-43-01.webp',
            ],
            [
                'category' => 'Tráng miệng',
                'name' => 'Bạch Lộ Chi Hoa',
                'description' => 'Nhãn tươi mọng nước, trái cây chín ngọt và pudding sữa mềm mịn, mát lạnh cho ngày hè.',
                'ingredients' => 'Nhãn tươi, trái cây, pudding sữa.',
                'price' => 55000,
                'image' => '/images/menu-hai-phong/page-44-01.webp',
            ],
            [
                'category' => 'Tráng miệng',
                'name' => 'Nama Chocolate',
                'description' => 'Dòng chocolate tươi Nhật Bản mềm mịn, mát lạnh, tan chảy ngay trên đầu lưỡi với vị ngọt đắng hài hòa.',
                'ingredients' => 'Chocolate tươi phong cách Nhật Bản.',
                'price' => 65000,
                'image' => '/images/menu-hai-phong/page-45-01.webp',
            ],
            [
                'category' => 'Đồ uống',
                'name' => 'Nước Mát Đàn Hương',
                'description' => 'Thức uống thanh nhiệt từ tía tô, vị nhẹ nhàng, phù hợp dùng cùng các món chay.',
                'ingredients' => 'Tía tô và thảo mộc thanh nhiệt.',
                'price' => 41000,
                'image' => '/images/menu-hai-phong/page-47-01.webp',
            ],
            [
                'category' => 'Đồ uống',
                'name' => 'Trà Hương Ban Mê',
                'description' => 'Mật hoa cà phê tươi, trà, cam, quế và cà phê tạo hương thơm thanh khiết, nhẹ nhàng đánh thức vị giác.',
                'ingredients' => 'Trà, mật hoa cà phê, cam, quế, cà phê.',
                'price' => 42000,
                'image' => '/images/menu-hai-phong/page-47-02.webp',
            ],
            [
                'category' => 'Đồ uống',
                'name' => 'Nước Thảo Mộc Đàn Hương',
                'description' => 'Lá sen, táo đỏ, chanh và thảo mộc tạo nên hương vị sâu sắc, hài hòa.',
                'ingredients' => 'Lá sen, táo đỏ, chanh, thảo mộc.',
                'price' => 41000,
                'image' => '/images/menu-hai-phong/page-47-03.webp',
            ],
            [
                'category' => 'Đồ uống',
                'name' => 'Tuyết Yến Matcha Hạt Chia',
                'description' => 'Uji Matcha, tuyết yến, hạt chia Úc và thạch matcha tạo nên thức uống mát lành, giàu kết cấu.',
                'ingredients' => 'Uji Matcha, tuyết yến, hạt chia Úc, thạch matcha.',
                'price' => 48000,
                'image' => '/images/menu-hai-phong/page-47-04.webp',
            ],
            [
                'category' => 'Đồ uống',
                'name' => 'Liên Mộc Như Sương Trà',
                'description' => 'Lê tươi, táo đỏ, hạt sen và thảo mộc hầm chậm tạo nên nước uống thanh nhiệt, dưỡng thần.',
                'ingredients' => 'Lê tươi, táo đỏ, hạt sen, thảo mộc.',
                'price' => 55000,
                'image' => '/images/menu-hai-phong/page-48-01.webp',
            ],
            [
                'category' => 'Đồ uống',
                'name' => 'Trà Nhài Cam Nhãn Tươi',
                'description' => 'Trà nhài kết hợp cam navel và nhãn tươi, vị thơm nhẹ, mát lành.',
                'ingredients' => 'Trà nhài, cam navel, nhãn tươi.',
                'price' => 50000,
                'image' => '/images/menu-hai-phong/page-48-02.webp',
            ],
            [
                'category' => 'Đồ uống',
                'name' => 'Coca Cola',
                'description' => 'Nước đóng lon dùng kèm bữa ăn.',
                'ingredients' => 'Nước giải khát đóng lon.',
                'price' => 20000,
                'image' => '/images/menu-hai-phong/page-49-01.webp',
            ],
            [
                'category' => 'Đồ uống',
                'name' => 'Nước Khoáng Lavie Premium',
                'description' => 'Nước khoáng đóng chai Lavie Premium.',
                'ingredients' => 'Nước khoáng.',
                'price' => 19000,
                'image' => '/images/menu-hai-phong/page-49-01.webp',
            ],
        ];
    }

    private function removeOldSampleMenuData(): void
    {
        Dish::query()
            ->whereIn('name', [
                'Gỏi cuốn nấm thanh vị',
                'Đậu hũ non sốt nấm',
                'Cà ri rau củ Đàn Hương',
                'Nấm kho tiêu xanh',
                'Lẩu nấm thảo mộc',
                'Cơm chay Đàn Hương',
                'Bún riêu chay',
                'Trà sen long nhãn',
                'Sữa hạt điều rang',
            ])
            ->orWhere('image', 'like', 'https://images.unsplash.com/%')
            ->delete();

        Category::query()
            ->where('type', 'dish')
            ->whereIn('name', [
                'Món khai vị',
                'Món chính',
                'Lẩu chay',
                'Cơm/Mì/Bún chay',
                'Đồ uống',
            ])
            ->doesntHave('dishes')
            ->delete();
    }
}
