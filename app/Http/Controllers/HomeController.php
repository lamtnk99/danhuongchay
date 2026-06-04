<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Branch;
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

        $branches = Branch::query()
            ->active()
            ->orderBy('sort_order')
            ->get();

        $seo = SeoService::page(
            localized_setting('default_meta_title', __('site.seo_fallback.default_title')),
            localized_setting('default_meta_description', __('site.seo_fallback.default_description')),
            localized_setting('default_meta_keywords', __('site.seo_fallback.default_keywords')),
            localized_route('home')
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        return view('home', compact('featuredDishes', 'latestPosts', 'testimonials', 'homePromotions', 'homeGalleryImages', 'banners', 'branches', 'seo', 'schemas'));
    }
}
