<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\GalleryImage;
use App\Services\SeoService;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function __invoke(): View
    {
        $branches = Branch::query()
            ->active()
            ->with(['galleryImages' => function ($query): void {
                $query->with('translations')
                    ->active()
                    ->where('location', 'space')
                    ->orderBy('sort_order')
                    ->latest();
            }])
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $sharedImages = GalleryImage::query()
            ->with('translations')
            ->active()
            ->whereNull('branch_id')
            ->where('location', 'space')
            ->orderBy('sort_order')
            ->latest()
            ->get();

        $firstImage = $branches->flatMap->galleryImages->first() ?: $sharedImages->first();

        $seo = SeoService::page(
            is_english() ? 'Dan Huong Chay Space | Branch interiors' : 'Không gian Đàn Hương Chay | Các cơ sở',
            is_english() ? 'Explore Dan Huong Chay spaces by branch with wood, greenery, soft light and peaceful corners for vegetarian meals.' : 'Khám phá không gian Đàn Hương Chay theo từng cơ sở với chất liệu gỗ, cây xanh, ánh sáng dịu và góc ngồi an yên.',
            is_english() ? 'Dan Huong Chay space, vegetarian restaurant photos, vegetarian restaurant Vietnam' : 'không gian Đàn Hương Chay, ảnh quán chay, nhà hàng chay đẹp, quán chay Hải Phòng, quán chay Buôn Ma Thuột',
            localized_route('gallery.index'),
            $firstImage?->image
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        return view('gallery.index', compact('branches', 'sharedImages', 'seo', 'schemas'));
    }
}
