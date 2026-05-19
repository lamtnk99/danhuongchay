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
            $page?->meta_title ?: 'Giới thiệu quán chay Hải Phòng | Câu chuyện Đàn Hương Chay',
            $page?->meta_description ?: 'Tìm hiểu câu chuyện thương hiệu Đàn Hương Chay, triết lý ẩm thực chay fusion, không gian quán và cam kết nguyên liệu sạch tại Hải Phòng.',
            $page?->meta_keywords ?: 'giới thiệu quán chay Hải Phòng, nhà hàng chay Hải Phòng, triết lý ăn chay, ẩm thực chay fusion, Đàn Hương Chay',
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
            $page->meta_keywords ?: "{$page->title}, quán chay Hải Phòng, Đàn Hương Chay",
            route('pages.show', $page),
            $page->image
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        return view('page', compact('page', 'seo', 'schemas'));
    }
}
