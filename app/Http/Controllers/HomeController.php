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
            ->with('category')
            ->active()
            ->featured()
            ->orderBy('sort_order')
            ->latest()
            ->limit(6)
            ->get();

        $latestPosts = Post::query()
            ->with('category')
            ->published()
            ->latest('published_at')
            ->limit(3)
            ->get();

        $banners = Banner::active()
            ->where('position', 'home')
            ->orderBy('sort_order')
            ->get();

        $testimonials = Testimonial::active()
            ->orderBy('sort_order')
            ->latest()
            ->limit(10)
            ->get();

        $homePromotions = Promotion::current()
            ->where('placement', 'home')
            ->orderBy('sort_order')
            ->latest()
            ->limit(4)
            ->get();

        $homeGalleryImages = GalleryImage::query()
            ->active()
            ->featured()
            ->orderBy('sort_order')
            ->latest()
            ->limit(5)
            ->get();

        $seo = SeoService::page(
            setting('default_meta_title', 'Đàn Hương Chay - Hải Phòng | Quán chay ngon, món chay fusion, đặt bàn'),
            setting('default_meta_description', 'Đàn Hương Chay phục vụ ẩm thực chay fusion tại Hải Phòng, với món chay ngon, thực đơn đa dạng, không gian an yên và đặt bàn tiện lợi.'),
            setting('default_meta_keywords', 'quán chay Hải Phòng, nhà hàng chay Hải Phòng, món chay ngon, thực đơn chay, đặt bàn quán chay, tiệc chay, mâm cúng chay, ăn chay healthy'),
            route('home')
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        return view('home', compact('featuredDishes', 'latestPosts', 'testimonials', 'homePromotions', 'homeGalleryImages', 'banners', 'seo', 'schemas'));
    }
}
