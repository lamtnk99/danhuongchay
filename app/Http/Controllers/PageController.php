<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\GalleryImage;
use App\Models\Page;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
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

        $branches = Branch::query()
            ->active()
            ->orderBy('sort_order')
            ->get();

        $seo = SeoService::page(
            $page?->localized('meta_title') ?: (__('site.auto.auto_9b3bebffdbd8')),
            $page?->localized('meta_description') ?: (__('site.auto.auto_9f82ed8d864b')),
            $page?->localized('meta_keywords') ?: (__('site.auto.auto_ed5a3cf0da43')),
            localized_route('about'),
            $page?->image
        );

        $breadcrumbs = [
            ['label' => __('site.nav.home'), 'url' => localized_route('home')],
            ['label' => $page?->localized('title') ?: __('site.about.fallback_title')],
        ];

        $schemas = [
            SeoService::restaurantSchema(),
            SeoService::breadcrumbSchema($breadcrumbs),
        ];

        return view('about', compact('seo', 'schemas', 'page', 'galleryImages', 'branches'));
    }

    public function show(Page|string $page): RedirectResponse|View
    {
        $requestedSlug = is_string($page) ? $page : $page->slug;

        if (! $page instanceof Page) {
            $page = Page::query()
                ->where('slug', $page)
                ->orWhereHas('translations', fn ($query) => $query->where('locale', current_locale())->where('slug', $page))
                ->firstOrFail();
        }

        abort_unless($page->is_active, 404);
        $page->load('translations');

        if ($redirect = $this->localizedRedirect($page, $requestedSlug)) {
            return $redirect;
        }

        $seo = SeoService::page(
            $page->localized('meta_title') ?: $page->localized('title'),
            $page->localized('meta_description'),
            $page->localized('meta_keywords') ?: "{$page->localized('title')}, Dan Huong Chay",
            localized_route('pages.show', ['slug' => $page->localizedSlug()]),
            $page->image,
            'website',
            $page->localized('title'),
            SeoService::routeAlternates('pages.show', ['page' => $page])
        );

        $breadcrumbs = [
            ['label' => __('site.nav.home'), 'url' => localized_route('home')],
            ['label' => $page->localized('title')],
        ];

        $schemas = [
            SeoService::restaurantSchema(),
            SeoService::breadcrumbSchema($breadcrumbs),
        ];

        return view('page', compact('page', 'seo', 'schemas'));
    }

    private function localizedRedirect(Page $page, string $requestedSlug): ?RedirectResponse
    {
        if (current_locale() === config('locales.default', 'vi')) {
            return null;
        }

        $translation = $page->translation(current_locale());
        if (! $translation || blank($translation->slug) || blank($translation->title) || blank($translation->content)) {
            return redirect()->route('pages.show', $page, 301);
        }

        if ($requestedSlug !== $translation->slug) {
            return redirect()->route('localized.pages.show', ['page' => $translation->slug], 301);
        }

        return null;
    }
}
