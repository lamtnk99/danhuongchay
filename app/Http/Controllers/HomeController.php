<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Dish;
use App\Models\GalleryImage;
use App\Models\Post;
use App\Models\Promotion;
use App\Models\Testimonial;
use App\Services\SeoService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $featuredDishes = Dish::query()
            ->with(['category.translations', 'translations'])
            ->active()
            ->featured()
            ->orderBy('sort_order')
            ->latest()
            ->limit(6)
            ->get();

        $latestPosts = Post::query()
            ->with(['category.translations', 'translations'])
            ->published()
            ->latest('published_at')
            ->limit(3)
            ->get();

        $banners = Banner::active()
            ->with('translations')
            ->where('position', 'home')
            ->orderBy('sort_order')
            ->get();

        $testimonials = Testimonial::active()
            ->with('translations')
            ->orderBy('sort_order')
            ->latest()
            ->limit(10)
            ->get();

        $homePromotions = Promotion::current()
            ->with('translations')
            ->where('placement', 'home')
            ->orderBy('sort_order')
            ->latest()
            ->limit(4)
            ->get();

        $homeGalleryImages = GalleryImage::query()
            ->with('translations')
            ->active()
            ->featured()
            ->orderBy('sort_order')
            ->latest()
            ->limit(5)
            ->get();

        $seo = SeoService::page(
            is_english() ? 'Dan Huong Chay - Hai Phong | Vegetarian fusion kitchen' : setting('default_meta_title', 'Đàn Hương Chay - Hải Phòng | Quán chay ngon, món chay fusion, đặt bàn'),
            is_english() ? 'Dan Huong Chay serves vegetarian fusion cuisine in Hai Phong with creative dishes, clean ingredients, a peaceful space and easy table booking.' : setting('default_meta_description', 'Đàn Hương Chay phục vụ ẩm thực chay fusion tại Hải Phòng, với món chay ngon, thực đơn đa dạng, không gian an yên và đặt bàn tiện lợi.'),
            is_english() ? 'vegetarian restaurant Hai Phong, vegetarian food, vegan food Hai Phong, vegetarian menu, Dan Huong Chay' : setting('default_meta_keywords', 'quán chay Hải Phòng, nhà hàng chay Hải Phòng, món chay ngon, thực đơn chay, đặt bàn quán chay, tiệc chay, mâm cúng chay, ăn chay healthy'),
            localized_route('home')
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        return view('home', compact('featuredDishes', 'latestPosts', 'testimonials', 'homePromotions', 'homeGalleryImages', 'banners', 'seo', 'schemas'));
    }
}
