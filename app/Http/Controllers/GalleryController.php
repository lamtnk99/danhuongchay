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
            ->with('translations')
            ->active()
            ->orderBy('sort_order')
            ->latest()
            ->paginate(12);

        $seo = SeoService::page(
            is_english() ? 'Dan Huong Chay Space | Peaceful vegetarian restaurant corners' : 'Không gian Đàn Hương Chay | Góc quán thanh tịnh',
            is_english() ? 'Explore Dan Huong Chay space with wood, greenery, soft light and calm corners for wholesome vegetarian meals.' : 'Khám phá không gian Đàn Hương Chay với chất liệu gỗ, cây xanh, ánh sáng dịu và những góc ngồi yên cho bữa chay thanh lành.',
            is_english() ? 'Dan Huong Chay space, vegetarian restaurant photos, Hai Phong vegetarian restaurant' : 'không gian Đàn Hương Chay, ảnh quán chay, nhà hàng chay đẹp',
            localized_route('gallery.index'),
            $galleryImages->first()?->image
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        return view('gallery.index', compact('galleryImages', 'seo', 'schemas'));
    }
}
