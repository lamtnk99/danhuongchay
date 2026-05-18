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
        $page = Page::active()->where('slug', 'gioi-thieu')->first();
        $galleryImages = GalleryImage::query()
            ->active()
            ->featured()
            ->orderBy('sort_order')
            ->latest()
            ->limit(3)
            ->get();

        $seo = SeoService::page(
            $page?->meta_title ?: 'Giới thiệu Đàn Hương Chay | Câu chuyện thương hiệu',
            $page?->meta_description ?: 'Tìm hiểu câu chuyện Đàn Hương Chay, triết lý ẩm thực chay thanh lành, không gian quán và cam kết nguyên liệu sạch.',
            $page?->meta_keywords ?: 'giới thiệu Đàn Hương Chay, triết lý ăn chay, nhà hàng chay sạch',
            route('about'),
            $page?->image
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        return view('about', compact('seo', 'schemas', 'page', 'galleryImages'));
    }

    public function show(Page $page): View
    {
        abort_unless($page->is_active, 404);

        $seo = SeoService::page(
            $page->meta_title ?: $page->title,
            $page->meta_description,
            $page->meta_keywords,
            route('pages.show', $page),
            $page->image
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        return view('page', compact('page', 'seo', 'schemas'));
    }
}
