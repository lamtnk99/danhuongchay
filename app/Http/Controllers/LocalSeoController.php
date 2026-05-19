<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Models\Post;
use App\Services\SeoService;
use Illuminate\View\View;

class LocalSeoController extends Controller
{
    public function vegetarianRestaurantHaiPhong(): View
    {
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

        $faqs = [
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
            'Quán chay Hải Phòng ngon, sạch, có đặt bàn | Đàn Hương Chay',
            'Đàn Hương Chay là quán chay Hải Phòng phục vụ ẩm thực chay fusion, món chay ngon, thực đơn sáng tạo, không gian an yên, có đặt bàn, tiệc chay và mâm cúng chay.',
            'quán chay Hải Phòng, nhà hàng chay Hải Phòng, món chay ngon Hải Phòng, đặt bàn quán chay, tiệc chay Hải Phòng, mâm cúng chay Hải Phòng, thực đơn chay',
            route('local.vegetarian-restaurant-hai-phong')
        );

        $schemas = [
            SeoService::restaurantSchema(),
            SeoService::faqSchema($faqs),
        ];

        return view('local.quan-chay-hai-phong', compact('featuredDishes', 'latestPosts', 'faqs', 'seo', 'schemas'));
    }

    public function vegetarianCateringHaiPhong(): View
    {
        $suggestedDishes = Dish::query()
            ->with('category')
            ->active()
            ->featured()
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $faqs = [
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
            'Đặt tiệc chay, mâm cúng chay Hải Phòng | Đàn Hương Chay',
            'Đặt tiệc chay và mâm cúng chay tại Hải Phòng với Đàn Hương Chay: thực đơn chay fusion, món đẹp, vị thanh, tư vấn theo số người và nhu cầu gia đình.',
            'đặt tiệc chay Hải Phòng, mâm cúng chay Hải Phòng, tiệc chay, đặt món chay Hải Phòng, quán chay Hải Phòng, nhà hàng chay Hải Phòng',
            route('local.vegetarian-catering-hai-phong')
        );

        $schemas = [
            SeoService::restaurantSchema(),
            SeoService::faqSchema($faqs),
        ];

        return view('local.dat-tiec-chay-hai-phong', compact('suggestedDishes', 'faqs', 'seo', 'schemas'));
    }
}
