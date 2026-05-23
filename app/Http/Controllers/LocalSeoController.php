<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Dish;
use App\Models\Post;
use App\Services\SeoService;
use Illuminate\View\View;

class LocalSeoController extends Controller
{
    public function cateringHub(): View
    {
        $branches = Branch::query()
            ->active()
            ->orderBy('sort_order')
            ->get();

        $seo = SeoService::page(
            is_english() ? 'Vegetarian Catering | Dan Huong Chay' : 'Đặt tiệc chay | Đàn Hương Chay',
            is_english()
                ? 'Choose your branch to request vegetarian catering or ceremonial vegetarian trays.'
                : 'Chọn cơ sở để gửi yêu cầu đặt tiệc chay hoặc mâm cúng chay nhanh chóng.',
            is_english()
                ? 'vegetarian catering, dan huong chay'
                : 'đặt tiệc chay, mâm cúng chay, đàn hương chay',
            localized_route('local.vegetarian-catering')
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        return view('local.dat-tiec-chay', compact('branches', 'seo', 'schemas'));
    }

    public function vegetarianRestaurantHaiPhong(): View
    {
        $branch = Branch::query()->active()->where('slug', 'hai-phong')->first();

        $featuredDishes = Dish::query()
            ->with('category')
            ->active()
            ->featured()
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $latestPosts = Post::query()
            ->with('category')
            ->published()
            ->latest('published_at')
            ->limit(3)
            ->get();

        $faqs = is_english() ? [
            [
                'question' => 'Is Dan Huong Chay a vegetarian restaurant in Hai Phong?',
                'answer' => 'Yes. Dan Huong Chay is located at Villa 01-B4 Hoang Mau - Gia Vien, Hai Phong, serving vegetarian fusion dishes, table reservations and vegetarian catering needs.',
            ],
            [
                'question' => 'Can I book a table in advance?',
                'answer' => 'Yes. Guests can reserve through the website or hotline. Booking hours are arranged by service periods so the restaurant can prepare more thoughtfully.',
            ],
            [
                'question' => 'Does Dan Huong Chay prepare vegetarian catering or ceremonial trays?',
                'answer' => 'Yes. The restaurant can advise on vegetarian catering, ceremonial trays and group orders. Please contact us in advance so we can suggest dishes, portions and preparation time.',
            ],
            [
                'question' => 'Is the menu suitable for people new to vegetarian food?',
                'answer' => 'Yes. The menu includes easy-to-enjoy rolls, rice, noodles, vegetarian hotpot, mushroom dishes and gentle drinks for both long-time vegetarian guests and first-time diners.',
            ],
            [
                'question' => 'What are the opening hours?',
                'answer' => 'Current opening hours are 09:00 - 14:00 and 16:00 - 21:00 daily. The kitchen may stop taking orders earlier according to branch booking settings.',
            ],
        ] : [
            [
                'question' => 'Đàn Hương Chay có phải quán chay ở Hải Phòng không?',
                'answer' => 'Có. Đàn Hương Chay nằm tại Villa 01-B4 Hoàng Mậu - Gia Viên, TP. Hải Phòng, phục vụ ẩm thực chay fusion, món chay gọi món, đặt bàn và các nhu cầu tiệc chay.',
            ],
            [
                'question' => 'Quán có nhận đặt bàn trước không?',
                'answer' => 'Có. Khách có thể đặt bàn trước qua website hoặc hotline. Khung giờ nhận đặt bàn hiện được chia theo ca sáng và ca chiều để quán phục vụ ổn định hơn.',
            ],
            [
                'question' => 'Đàn Hương Chay có nhận đặt tiệc chay hoặc mâm cúng chay không?',
                'answer' => 'Quán có hỗ trợ nhu cầu đặt tiệc chay, đặt mâm cúng và đặt món theo nhóm. Khách nên liên hệ trước để được tư vấn số lượng món, khẩu vị và thời gian chuẩn bị phù hợp.',
            ],
            [
                'question' => 'Thực đơn có phù hợp với người mới ăn chay không?',
                'answer' => 'Có. Thực đơn có nhiều món dễ ăn như món cuốn, cơm, mì, bún, lẩu chay, nấm và đồ uống thanh nhẹ, phù hợp cho cả người ăn chay lâu năm lẫn người mới thử.',
            ],
            [
                'question' => 'Quán mở cửa khung giờ nào?',
                'answer' => 'Giờ mở cửa hiện tại là 09:00 - 14:00 và 16:00 - 21:00 hằng ngày. Bếp ngừng nhận món muộn theo cấu hình đặt bàn của quán.',
            ],
        ];

        $seo = SeoService::page(
            is_english() ? 'Vegetarian Restaurant in Hai Phong | Dan Huong Chay' : 'Quán chay Hải Phòng ngon, sạch, có đặt bàn | Đàn Hương Chay',
            is_english()
                ? 'Dan Huong Chay is a vegetarian restaurant in Hai Phong serving vegetarian fusion dishes, peaceful dining, table reservations, catering and ceremonial trays.'
                : 'Đàn Hương Chay là quán chay Hải Phòng phục vụ ẩm thực chay fusion, món chay ngon, thực đơn sáng tạo, không gian an yên, có đặt bàn, tiệc chay và mâm cúng chay.',
            is_english()
                ? 'vegetarian restaurant Hai Phong, vegan food Hai Phong, vegetarian catering Hai Phong, Dan Huong Chay'
                : 'quán chay Hải Phòng, nhà hàng chay Hải Phòng, món chay ngon Hải Phòng, đặt bàn quán chay, tiệc chay Hải Phòng, mâm cúng chay Hải Phòng, thực đơn chay',
            localized_route('local.vegetarian-restaurant-hai-phong')
        );

        $schemas = [
            SeoService::restaurantSchema(),
            SeoService::faqSchema($faqs),
        ];

        return view('local.quan-chay-hai-phong', compact('branch', 'featuredDishes', 'latestPosts', 'faqs', 'seo', 'schemas'));
    }

    public function vegetarianCateringHaiPhong(): View
    {
        $branch = Branch::query()->active()->where('slug', 'hai-phong')->first();

        $suggestedDishes = Dish::query()
            ->with('category')
            ->active()
            ->featured()
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $faqs = is_english() ? [
            [
                'question' => 'Does Dan Huong Chay offer vegetarian catering in Hai Phong?',
                'answer' => 'Yes. The restaurant advises on vegetarian catering for families, friends, guest meals and occasions that need a thoughtful vegetarian menu.',
            ],
            [
                'question' => 'Can I order ceremonial vegetarian trays?',
                'answer' => 'Yes. Dan Huong Chay can suggest ceremonial vegetarian trays with a neat, elegant layout and dishes that keep their flavor well.',
            ],
            [
                'question' => 'How early should I place a catering request?',
                'answer' => 'For group tables or small gatherings, a few hours in advance is helpful. For ceremonial trays or menus with many dishes, please contact us one day ahead.',
            ],
            [
                'question' => 'Can the restaurant suggest a menu by group size?',
                'answer' => 'Yes. We can suggest dish quantities based on guest count, occasion, preferred flavor and budget.',
            ],
        ] : [
            [
                'question' => 'Đàn Hương Chay có nhận đặt tiệc chay tại Hải Phòng không?',
                'answer' => 'Có. Quán hỗ trợ tư vấn đặt tiệc chay cho gia đình, nhóm bạn, tiếp khách hoặc các dịp cần thực đơn chay chỉn chu. Khách nên liên hệ trước để thống nhất số người, khẩu vị và thời gian chuẩn bị.',
            ],
            [
                'question' => 'Quán có nhận đặt mâm cúng chay không?',
                'answer' => 'Có. Đàn Hương Chay có thể tư vấn mâm cúng chay theo nhu cầu, ưu tiên bố cục gọn, đẹp, trang trọng và món ăn giữ vị ổn định.',
            ],
            [
                'question' => 'Nên đặt tiệc chay trước bao lâu?',
                'answer' => 'Với bàn nhóm hoặc tiệc nhỏ, khách nên đặt trước ít nhất vài giờ. Với mâm cúng chay hoặc tiệc cần nhiều món, nên liên hệ trước 1 ngày để quán chuẩn bị nguyên liệu và sắp xếp bếp tốt hơn.',
            ],
            [
                'question' => 'Có thể tư vấn thực đơn theo số người không?',
                'answer' => 'Có. Quán có thể gợi ý số lượng món theo số khách, mục đích dùng bữa, khẩu vị nhẹ hoặc đậm và mức ngân sách mong muốn.',
            ],
        ];

        $seo = SeoService::page(
            is_english() ? 'Vegetarian Catering in Hai Phong | Dan Huong Chay' : 'Đặt tiệc chay, mâm cúng chay Hải Phòng | Đàn Hương Chay',
            is_english()
                ? 'Order vegetarian catering and ceremonial trays in Hai Phong with Dan Huong Chay: gentle fusion dishes, thoughtful plating and menu advice by group size.'
                : 'Đặt tiệc chay và mâm cúng chay tại Hải Phòng với Đàn Hương Chay: thực đơn chay fusion, món đẹp, vị thanh, tư vấn theo số người và nhu cầu gia đình.',
            is_english()
                ? 'vegetarian catering Hai Phong, vegetarian feast tray, vegan catering, Dan Huong Chay'
                : 'đặt tiệc chay Hải Phòng, mâm cúng chay Hải Phòng, tiệc chay, đặt món chay Hải Phòng, quán chay Hải Phòng, nhà hàng chay Hải Phòng',
            localized_route('local.vegetarian-catering-hai-phong')
        );

        $schemas = [
            SeoService::restaurantSchema(),
            SeoService::faqSchema($faqs),
        ];

        return view('local.dat-tiec-chay-hai-phong', compact('branch', 'suggestedDishes', 'faqs', 'seo', 'schemas'));
    }
    public function vegetarianRestaurantBuonMaThuot(): View
    {
        $branch = Branch::query()->active()->where('slug', 'buon-ma-thuot')->first();

        $featuredDishes = Dish::query()
            ->with('category')
            ->active()
            ->featured()
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $latestPosts = Post::query()
            ->with('category')
            ->published()
            ->latest('published_at')
            ->limit(3)
            ->get();

        $faqs = is_english() ? [
            [
                'question' => 'Does Dan Huong Chay have a branch in Buon Ma Thuot?',
                'answer' => 'Yes. Dan Huong Chay has a Buon Ma Thuot branch serving vegetarian fusion dishes, a la carte meals and table reservations according to branch information on the website.',
            ],
            [
                'question' => 'Is the Buon Ma Thuot menu different from Hai Phong?',
                'answer' => 'The website currently uses a shared menu and pricing so guests can browse easily. Seasonal dishes or branch-specific suggestions can be discussed when booking.',
            ],
            [
                'question' => 'Can I reserve a table at the Buon Ma Thuot branch?',
                'answer' => 'Yes. Choose the Buon Ma Thuot branch in the reservation form; the restaurant will receive your request and confirm the details.',
            ],
        ] : [
            [
                'question' => 'Đàn Hương Chay có cơ sở tại Buôn Ma Thuột không?',
                'answer' => 'Có. Đàn Hương Chay có cơ sở tại Buôn Ma Thuột, phục vụ ẩm thực chay fusion, món chay gọi món và các nhu cầu đặt bàn theo thông tin cơ sở đang cập nhật trên website.',
            ],
            [
                'question' => 'Thực đơn Buôn Ma Thuột có khác Hải Phòng không?',
                'answer' => 'Website hiện dùng thực đơn và giá chung để khách dễ xem món. Một số món theo mùa hoặc chương trình tại từng cơ sở có thể được tư vấn khi khách đặt bàn.',
            ],
            [
                'question' => 'Có thể đặt bàn trước tại cơ sở Buôn Ma Thuột không?',
                'answer' => 'Có. Khách chọn cơ sở Buôn Ma Thuột trong form đặt bàn, hệ thống sẽ kiểm tra khung giờ nhận đặt của cơ sở và lưu thông tin để quán xác nhận.',
            ],
        ];

        $seo = SeoService::page(
            is_english() ? 'Vegetarian Restaurant in Buon Ma Thuot | Dan Huong Chay' : 'Quán chay Buôn Ma Thuột | Đàn Hương Chay',
            is_english()
                ? 'Dan Huong Chay Buon Ma Thuot serves vegetarian fusion dishes, gentle flavors, a peaceful dining space and branch-based reservations.'
                : 'Đàn Hương Chay Buôn Ma Thuột phục vụ ẩm thực chay fusion, món chay ngon, không gian an yên và có form đặt bàn theo cơ sở.',
            is_english()
                ? 'vegetarian restaurant Buon Ma Thuot, vegan food Buon Ma Thuot, Dan Huong Chay Buon Ma Thuot'
                : 'quán chay Buôn Ma Thuột, nhà hàng chay Buôn Ma Thuột, món chay ngon Buôn Ma Thuột, Đàn Hương Chay Buôn Ma Thuột',
            localized_route('local.vegetarian-restaurant-buon-ma-thuot'),
            $branch?->image
        );

        $schemas = [
            SeoService::restaurantSchema(),
            SeoService::faqSchema($faqs),
        ];

        return view('local.quan-chay-buon-ma-thuot', compact('branch', 'featuredDishes', 'latestPosts', 'faqs', 'seo', 'schemas'));
    }

    public function vegetarianCateringBuonMaThuot(): View
    {
        $branch = Branch::query()->active()->where('slug', 'buon-ma-thuot')->first();

        $suggestedDishes = Dish::query()
            ->with('category')
            ->active()
            ->featured()
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $faqs = is_english() ? [
            [
                'question' => 'Does Dan Huong Chay offer vegetarian catering in Buon Ma Thuot?',
                'answer' => 'Yes. The restaurant can advise on vegetarian catering, ceremonial trays and set dishes for the Buon Ma Thuot branch.',
            ],
            [
                'question' => 'Are prices different between branches?',
                'answer' => 'The website currently uses a shared price list so guests can browse and request service more easily.',
            ],
            [
                'question' => 'How early should I book?',
                'answer' => 'Please book early so the kitchen can prepare ingredients and arrange the service rhythm, especially for ceremonial trays or larger groups.',
            ],
        ] : [
            [
                'question' => 'Đàn Hương Chay có nhận đặt tiệc chay tại Buôn Ma Thuột không?',
                'answer' => 'Có. Quán nhận tư vấn tiệc chay, mâm cúng chay và set món theo số lượng khách tại cơ sở Buôn Ma Thuột.',
            ],
            [
                'question' => 'Giá món có khác giữa 2 cơ sở không?',
                'answer' => 'Hiện tại website đang áp dụng bảng giá món đồng nhất để khách dễ theo dõi và đặt dịch vụ.',
            ],
            [
                'question' => 'Nên đặt trước bao lâu?',
                'answer' => 'Bạn nên đặt sớm để quán sắp xếp bếp và nguyên liệu tốt hơn, đặc biệt với mâm cúng hoặc nhóm đông.',
            ],
        ];

        $seo = SeoService::page(
            is_english() ? 'Vegetarian Catering in Buon Ma Thuot | Dan Huong Chay' : 'Đặt tiệc chay Buôn Ma Thuột | Đàn Hương Chay',
            is_english()
                ? 'Order vegetarian catering and ceremonial trays in Buon Ma Thuot with Dan Huong Chay. Menu advice by guest count, taste and service time.'
                : 'Đặt tiệc chay và mâm cúng chay tại Buôn Ma Thuột với Đàn Hương Chay. Tư vấn theo số lượng khách, khẩu vị và khung giờ phục vụ.',
            is_english()
                ? 'vegetarian catering Buon Ma Thuot, vegetarian feast tray Buon Ma Thuot, Dan Huong Chay'
                : 'đặt tiệc chay Buôn Ma Thuột, mâm cúng chay Buôn Ma Thuột, quán chay Buôn Ma Thuột',
            localized_route('local.vegetarian-catering-buon-ma-thuot'),
            $branch?->image
        );

        $schemas = [
            SeoService::restaurantSchema(),
            SeoService::faqSchema($faqs),
        ];

        return view('local.dat-tiec-chay-buon-ma-thuot', compact('branch', 'suggestedDishes', 'faqs', 'seo', 'schemas'));
    }
}
