<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use App\Models\Page;
use App\Services\SeoService;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        $page = Page::active()
            ->with('translations')
            ->where('slug', 'gioi-thieu')
            ->first();

        $galleryImages = GalleryImage::query()
            ->with('translations')
            ->active()
            ->featured()
            ->orderBy('sort_order')
            ->latest()
            ->limit(3)
            ->get();

        $seo = SeoService::page(
            $page?->localized('meta_title') ?: (is_english() ? 'About Dan Huong Chay | Vegetarian restaurant in Hai Phong' : 'Giới thiệu quán chay Hải Phòng | Câu chuyện Đàn Hương Chay'),
            $page?->localized('meta_description') ?: (is_english() ? 'Learn about Dan Huong Chay, our vegetarian fusion philosophy, peaceful restaurant space and commitment to clean ingredients in Hai Phong.' : 'Tìm hiểu câu chuyện thương hiệu Đàn Hương Chay, triết lý ẩm thực chay fusion, không gian quán và cam kết nguyên liệu sạch tại Hải Phòng.'),
            $page?->localized('meta_keywords') ?: (is_english() ? 'about Dan Huong Chay, vegetarian restaurant Hai Phong, vegetarian fusion cuisine' : 'giới thiệu quán chay Hải Phòng, nhà hàng chay Hải Phòng, triết lý ăn chay, ẩm thực chay fusion, Đàn Hương Chay'),
            localized_route('about'),
            $page?->image
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        return view('about', compact('seo', 'schemas', 'page', 'galleryImages'));
    }

    public function show(Page|string $page): View
    {
        if (! $page instanceof Page) {
            $page = Page::query()
                ->where('slug', $page)
                ->orWhereHas('translations', fn ($query) => $query->where('locale', current_locale())->where('slug', $page))
                ->firstOrFail();
        }

        abort_unless($page->is_active, 404);
        $page->load('translations');

        $seo = SeoService::page(
            $page->localized('meta_title') ?: $page->localized('title'),
            $page->localized('meta_description'),
            $page->localized('meta_keywords') ?: "{$page->localized('title')}, Dan Huong Chay",
            localized_route('pages.show', ['slug' => $page->localizedSlug()]),
            $page->image
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        return view('page', compact('page', 'seo', 'schemas'));
    }
}
