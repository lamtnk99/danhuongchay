<?php

namespace App\Http\Controllers;

use App\Models\GalleryImage;
use App\Services\SeoService;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function __invoke(): View
    {
        $galleryImages = GalleryImage::query()
            ->active()
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12);

        $seo = SeoService::page(
            'Không gian Đàn Hương Chay | Góc quán thanh tịnh',
            'Khám phá không gian Đàn Hương Chay với chất liệu gỗ, cây xanh, ánh sáng dịu và những góc ngồi yên cho bữa chay thanh lành.',
            'không gian Đàn Hương Chay, ảnh quán chay, nhà hàng chay đẹp',
            route('gallery.index'),
            $galleryImages->first()?->image
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        return view('gallery.index', compact('galleryImages', 'seo', 'schemas'));
    }
}
