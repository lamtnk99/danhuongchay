<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Dish;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EnglishTranslationSeeder extends Seeder
{
    public function run(): void
    {
        $categoryNames = [
            'Món chính' => 'Main Dishes',
            'Đồ uống' => 'Drinks',
            'Ăn chay' => 'Vegetarian Living',
            'Sức khỏe' => 'Wellness',
            'Sống xanh' => 'Green Living',
            'Signature Food' => 'Signature Food',
            'Khai vị' => 'Appetizers',
            'Mỳ, Miến & Cơm' => 'Noodles, Glass Noodles & Rice',
            'Món canh & Lẩu' => 'Soups & Hot Pots',
            'Món thêm' => 'Add-ons',
            'Tráng miệng' => 'Desserts',
        ];

        Category::query()->get()->each(function (Category $category) use ($categoryNames): void {
            $name = $categoryNames[$category->name] ?? Str::headline(Str::ascii($category->name));

            $category->translations()->updateOrCreate(['locale' => 'en'], [
                'name' => $name,
                'slug' => $this->slug($name),
                'description' => $category->description
                    ? "Explore {$name} from Dan Huong Chay's vegetarian fusion menu in Hai Phong."
                    : null,
                'meta_title' => "{$name} | Dan Huong Chay",
                'meta_description' => "Explore {$name} at Dan Huong Chay, a vegetarian fusion restaurant in Hai Phong.",
            ]);
        });

        $dishNames = [
            'Súp Phật Sơn' => 'Phat Son Mushroom Soup',
            'Salad Hàn Ngọc' => 'Han Ngoc Salad',
            'Nấm Đút Lò Phô Mai' => 'Baked Mushrooms with Mozzarella',
            'Salad Đàn Hương' => 'Dan Huong Salad',
            'Nem Đàn Hương' => 'Dan Huong Spring Rolls',
            'Mâm Cuốn Đàn Hương' => 'Dan Huong Fresh Roll Platter',
            'Lẩu Nấm Thập Tam Hương' => 'Thirteen-Herb Mushroom Hot Pot',
            'Lẩu Riêu Chay Hải Phòng' => 'Hai Phong Vegetarian Rieu Hot Pot',
            'Chè Bạch Tịnh' => 'Bach Tinh Sweet Soup',
            'Soup Kem Bí Đỏ' => 'Creamy Pumpkin Soup',
            'Salad Nhiệt Đới' => 'Tropical Salad',
            'Giá Trộn Kiểu Nhật' => 'Japanese Bean Sprout Salad',
            'Miến Trộn Thái Lan' => 'Thai Glass Noodle Salad',
            'Gỏi Dừa' => 'Coconut Salad',
            'Gỏi Cuốn Đàn Hương' => 'Dan Huong Fresh Rolls',
            'Phở Cuốn Hà Thành' => 'Ha Thanh Pho Rolls',
            'Nem Vị Cảng' => 'Harbor-Style Spring Rolls',
            'Chả Giò Bắp Non' => 'Baby Corn Crispy Rolls',
            'Mẹt Bánh Hỏi Nấm Nướng' => 'Grilled Mushroom Banh Hoi Platter',
            'Tomyum Thái Lan' => 'Thai Tom Yum Soup',
            'Tảo Xoắn Chile Xào Sả Ớt' => 'Chile Seaweed Stir-Fried with Lemongrass and Chili',
            'Nấm Xúc Bánh Đa Cố Đô' => 'Imperial Crispy Rice Paper with Mushrooms',
            'Shitake Sốt Hồng Xíu' => 'Shiitake Mushrooms in Hong Xiu Sauce',
            'Steak Nấm Hầu Thủ' => 'Lion’s Mane Mushroom Steak',
            'Nấm Xông Thảo Mộc' => 'Herb-Smoked Mushrooms',
            'Nấm Om Tiêu Xanh' => 'Braised Mushrooms with Green Peppercorn',
            'Đậu Hủ Hòa Điền' => 'Hoa Dien Tofu',
            'Nấm Nướng Lá Chuối' => 'Banana Leaf Grilled Mushrooms',
            'Nấm Đoàn Viên' => 'Family Reunion Mushrooms',
            'Mẹt Cuốn Rau Rừng' => 'Wild Herb Fresh Roll Platter',
            'Mẹt Cuốn Thính' => 'Roasted Rice Powder Fresh Roll Platter',
            'Cà Tím Nướng Sốt Nhật' => 'Grilled Eggplant with Japanese Sauce',
            'Rau Luộc Kho Quẹt' => 'Boiled Vegetables with Vegetarian Kho Quet Dip',
            'Rau Cải Thìa Xào' => 'Stir-Fried Bok Choy',
            'Mì Ý Sốt Táo Cà Chua' => 'Spaghetti with Apple Tomato Sauce',
            'Mì Lạnh Zaru Soba' => 'Cold Zaru Soba',
            'Miến Xào Măng Mầm' => 'Stir-Fried Glass Noodles with Bamboo Shoots',
            'Thố Miến Trung Hoa' => 'Chinese-Style Glass Noodle Clay Bowl',
            'Cơm Chiên Thơm' => 'Pineapple Fried Rice',
            'Cơm Chiên Lạc Dương' => 'Lac Duong Fried Rice',
            'Cơm Trà Tiên Nướng Lá Chuối' => 'Tra Tien Rice Grilled in Banana Leaf',
            'Cơm Đút Lò Kiểu Nhật' => 'Japanese-Style Baked Rice',
            'Canh Chua Nam Bộ' => 'Southern Vietnamese Sweet and Sour Soup',
            'Canh Miso Rong Biển' => 'Miso Seaweed Soup',
            'Lẩu Thái' => 'Thai Hot Pot',
            'Rong Biển Trộn' => 'Seasoned Seaweed',
            'Kimchi' => 'Kimchi',
            'Củ Cải Ngâm' => 'Pickled Radish',
            'Bánh Bạch Ngọc Sương Phương' => 'Bach Ngoc Suong Phuong Cake',
            'Xôi Xoài' => 'Mango Sticky Rice',
            'Bạch Lộ Chi Hoa' => 'Bach Lo Chi Hoa Dessert',
            'Nama Chocolate' => 'Nama Chocolate',
            'Nước Mát Đàn Hương' => 'Dan Huong Herbal Cooler',
            'Trà Hương Ban Mê' => 'Ban Me Aromatic Tea',
            'Nước Thảo Mộc Đàn Hương' => 'Dan Huong Herbal Drink',
            'Tuyết Yến Matcha Hạt Chia' => 'Matcha Snow Fungus with Chia Seeds',
            'Liên Mộc Như Sương Trà' => 'Lien Moc Dew Tea',
            'Trà Nhài Cam Nhãn Tươi' => 'Jasmine Tea with Orange and Fresh Longan',
            'Coca Cola' => 'Coca-Cola',
            'Nước Khoáng Lavie Premium' => 'Lavie Premium Mineral Water',
        ];

        Dish::query()->get()->each(function (Dish $dish) use ($dishNames): void {
            $name = $dishNames[$dish->name] ?? Str::headline(Str::ascii($dish->name));
            $category = $dish->category?->translation('en')?->name ?? $dish->category?->name;

            $dish->translations()->updateOrCreate(['locale' => 'en'], [
                'name' => $name,
                'slug' => $this->slug($name),
                'description' => "A refined vegetarian {$category} from Dan Huong Chay, prepared with fresh ingredients and balanced seasoning.",
                'content' => "<p>{$name} is part of Dan Huong Chay's vegetarian fusion menu in Hai Phong. The dish is made for guests who enjoy clean ingredients, gentle flavors and a calm dining experience.</p><p>It is suitable for family meals, friendly gatherings and guests who want modern vegetarian food that still feels close to Vietnamese taste.</p>",
                'ingredients' => $dish->ingredients ? 'Fresh vegetables, mushrooms, plant-based ingredients and Dan Huong house seasoning.' : null,
                'meta_title' => "{$name} | Dan Huong Chay Hai Phong",
                'meta_description' => "View {$name} at Dan Huong Chay, a vegetarian fusion restaurant in Hai Phong. See price, ingredients and booking options.",
                'meta_keywords' => "{$name}, vegetarian restaurant Hai Phong, vegetarian food Hai Phong, Dan Huong Chay",
            ]);
        });

        $postTitles = [
            'Quán chay Hải Phòng nên chọn như thế nào để ăn ngon, sạch và đáng quay lại?' => 'How to Choose a Good Vegetarian Restaurant in Hai Phong',
            'Nhà hàng chay Hải Phòng cho gia đình, tiếp khách và đặt tiệc: nên xem gì trước khi gọi bàn?' => 'Choosing a Vegetarian Restaurant in Hai Phong for Family Meals and Catering',
            'Ẩm thực chay fusion là gì? Vì sao món chay hiện đại ngày càng được yêu thích?' => 'What Is Vegetarian Fusion Cuisine and Why Is It Becoming Popular?',
            'Ăn chay có lợi gì? Cách xây thực đơn chay no lâu, đủ chất và dễ áp dụng mỗi ngày' => 'Benefits of Vegetarian Eating and How to Build a Balanced Daily Menu',
            'Món chay ngon ở Hải Phòng nên thử gì cho bữa trưa, bữa tối và cuối tuần?' => 'Vegetarian Dishes to Try in Hai Phong for Lunch, Dinner and Weekends',
            'Mâm cúng chay và tiệc chay tại nhà: chuẩn bị gì để gọn, đẹp và trang trọng?' => 'Vegetarian Feast Trays and Home Catering: What to Prepare',
        ];

        Post::query()->get()->each(function (Post $post) use ($postTitles): void {
            $title = $postTitles[$post->title] ?? Str::headline(Str::ascii($post->title));

            $post->translations()->updateOrCreate(['locale' => 'en'], [
                'title' => $title,
                'slug' => $this->slug($title),
                'excerpt' => 'A practical guide from Dan Huong Chay about vegetarian dining, healthy food choices and mindful living in Hai Phong.',
                'content' => "<p>This guide is written for guests looking for vegetarian food in Hai Phong, from everyday meals to family gatherings, feast trays and catering.</p><h2>Why vegetarian dining is growing</h2><p>Modern vegetarian food is no longer limited to simple vegetables. A thoughtful restaurant combines fresh produce, mushrooms, tofu, grains and gentle seasoning to create meals that are easy to enjoy and balanced for different guests.</p><h2>What to look for</h2><p>When choosing a vegetarian restaurant, consider menu variety, ingredient quality, seating comfort, booking support and whether the kitchen can prepare larger orders for special occasions.</p><h2>Dan Huong Chay in Hai Phong</h2><p>Dan Huong Chay focuses on vegetarian fusion cuisine, peaceful space and flexible booking for tables, vegetarian feast trays and small events.</p>",
                'meta_title' => "{$title} | Dan Huong Chay",
                'meta_description' => 'Read practical vegetarian dining tips from Dan Huong Chay, a vegetarian fusion restaurant in Hai Phong.',
                'meta_keywords' => 'vegetarian restaurant Hai Phong, vegetarian food Hai Phong, vegan food, vegetarian catering, Dan Huong Chay',
            ]);
        });

        $pageTitles = [
            'gioi-thieu' => 'About Dan Huong Chay',
            'chinh-sach-dat-ban' => 'Reservation Policy',
            'chinh-sach-bao-mat' => 'Privacy Policy',
            'dieu-khoan-su-dung' => 'Terms of Use',
        ];

        Page::query()->get()->each(function (Page $page) use ($pageTitles): void {
            $title = $pageTitles[$page->slug] ?? Str::headline(Str::ascii($page->title));

            $page->translations()->updateOrCreate(['locale' => 'en'], [
                'title' => $title,
                'slug' => $this->slug($title),
                'content' => '<p>Dan Huong Chay is a vegetarian fusion kitchen in Hai Phong, built around clean ingredients, balanced flavors and a peaceful dining experience.</p><p>The restaurant serves modern vegetarian dishes, table reservations, vegetarian feast trays and catering for guests who value mindful, elegant meals.</p>',
                'meta_title' => "{$title} | Dan Huong Chay",
                'meta_description' => "Read {$title} information for Dan Huong Chay vegetarian restaurant in Hai Phong.",
                'meta_keywords' => 'Dan Huong Chay, vegetarian restaurant Hai Phong, vegetarian fusion cuisine',
            ]);
        });
    }

    private function slug(string $value): string
    {
        return Str::slug(Str::ascii($value));
    }
}
