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
            __('site.auto.auto_3fc5d2c4c698'),
            __('site.auto.auto_405bcd97f29c'),
            __('site.auto.auto_eb3b80307a24'),
            localized_route('local.vegetarian-catering')
        );

        $breadcrumbs = [
            ['label' => __('site.nav.home'), 'url' => localized_route('home')],
            ['label' => __('site.auto.auto_ed75d636940b'), 'url' => localized_route('local.vegetarian-catering')],
        ];

        $schemas = [
            SeoService::restaurantSchema(),
            SeoService::breadcrumbSchema($breadcrumbs),
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

        $faqs = __('site.local.faqs.vegetarian_restaurant_hai_phong');

        $seo = SeoService::page(
            __('site.auto.auto_9b8af8176082'),
            __('site.auto.auto_e082cd986084'),
            __('site.auto.auto_b582e494753a'),
            localized_route('local.vegetarian-restaurant-hai-phong')
        );

        $breadcrumbs = [
            ['label' => __('site.nav.home'), 'url' => localized_route('home')],
            ['label' => __('site.auto.auto_9b8af8176082'), 'url' => localized_route('local.vegetarian-restaurant-hai-phong')],
        ];

        $schemas = [
            SeoService::restaurantSchema($branch),
            SeoService::faqSchema($faqs),
            SeoService::breadcrumbSchema($breadcrumbs),
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

        $faqs = __('site.local.faqs.vegetarian_catering_hai_phong');

        $seo = SeoService::page(
            __('site.auto.auto_2b5073fadb8f'),
            __('site.auto.auto_9c263959a6b6'),
            __('site.auto.auto_689819f2eacc'),
            localized_route('local.vegetarian-catering-hai-phong')
        );

        $breadcrumbs = [
            ['label' => __('site.nav.home'), 'url' => localized_route('home')],
            ['label' => __('site.auto.auto_2b5073fadb8f'), 'url' => localized_route('local.vegetarian-catering-hai-phong')],
        ];

        $schemas = [
            SeoService::restaurantSchema($branch),
            SeoService::faqSchema($faqs),
            SeoService::breadcrumbSchema($breadcrumbs),
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

        $faqs = __('site.local.faqs.vegetarian_restaurant_buon_ma_thuot');

        $seo = SeoService::page(
            __('site.auto.auto_e9e82c322a92'),
            __('site.auto.auto_3f7a4fdc2bd4'),
            __('site.auto.auto_59ec58cc989f'),
            localized_route('local.vegetarian-restaurant-buon-ma-thuot'),
            $branch?->image
        );

        $breadcrumbs = [
            ['label' => __('site.nav.home'), 'url' => localized_route('home')],
            ['label' => __('site.auto.auto_e9e82c322a92'), 'url' => localized_route('local.vegetarian-restaurant-buon-ma-thuot')],
        ];

        $schemas = [
            SeoService::restaurantSchema($branch),
            SeoService::faqSchema($faqs),
            SeoService::breadcrumbSchema($breadcrumbs),
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

        $faqs = __('site.local.faqs.vegetarian_catering_buon_ma_thuot');

        $seo = SeoService::page(
            __('site.auto.auto_be2fcf7a242c'),
            __('site.auto.auto_746acecf24e7'),
            __('site.auto.auto_fe38157b34b0'),
            localized_route('local.vegetarian-catering-buon-ma-thuot'),
            $branch?->image
        );

        $breadcrumbs = [
            ['label' => __('site.nav.home'), 'url' => localized_route('home')],
            ['label' => __('site.auto.auto_be2fcf7a242c'), 'url' => localized_route('local.vegetarian-catering-buon-ma-thuot')],
        ];

        $schemas = [
            SeoService::restaurantSchema($branch),
            SeoService::faqSchema($faqs),
            SeoService::breadcrumbSchema($breadcrumbs),
        ];

        return view('local.dat-tiec-chay-buon-ma-thuot', compact('branch', 'suggestedDishes', 'faqs', 'seo', 'schemas'));
    }
}
