<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Dish;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::dish()
            ->active()
            ->with('translations')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $selectedCategory = $request->string('category')->toString();
        $search = $request->string('q')->toString();

        $dishes = Dish::query()
            ->with(['category.translations', 'translations'])
            ->active()
            ->availableInLocale()
            ->when($selectedCategory, function ($query) use ($selectedCategory): void {
                $query->whereHas('category', function ($categoryQuery) use ($selectedCategory): void {
                    $categoryQuery->where('slug', $selectedCategory)
                        ->orWhereHas('translations', fn ($translationQuery) => $translationQuery->where('slug', $selectedCategory));
                });
            })
            ->search($search)
            ->orderBy('sort_order')
            ->latest()
            ->paginate(9)
            ->withQueryString();

        abort_if($dishes->currentPage() > $dishes->lastPage(), 404);

        $schemaCategories = Category::dish()
            ->active()
            ->with(['translations', 'dishes' => fn ($query) => $query
                ->active()
                ->availableInLocale()
                ->with('translations')
                ->orderBy('name')])
            ->orderBy('id')
            ->get();

        $seo = SeoService::page(
            __('site.auto.auto_00cee6077395'),
            __('site.auto.auto_28dfc3a9cb23'),
            __('site.auto.auto_00744082b024'),
            localized_route('menu.index')
        );

        $schemas = [
            SeoService::restaurantSchema(),
            SeoService::menuSchema($schemaCategories),
        ];

        return view('menu.index', compact('categories', 'dishes', 'selectedCategory', 'search', 'seo', 'schemas'));
    }

    public function show(Dish|string $dish): RedirectResponse|View
    {
        $requestedSlug = is_string($dish) ? $dish : $dish->slug;

        if (! $dish instanceof Dish) {
            $dish = Dish::query()
                ->where('slug', $dish)
                ->orWhereHas('translations', fn ($query) => $query->where('locale', current_locale())->where('slug', $dish))
                ->firstOrFail();
        }

        $dish->load(['category.translations', 'translations']);
        abort_unless($dish->is_active, 404);

        if ($redirect = $this->localizedRedirect($dish, $requestedSlug)) {
            return $redirect;
        }

        $relatedDishes = Dish::query()
            ->with(['category.translations', 'translations'])
            ->active()
            ->availableInLocale()
            ->where('category_id', $dish->category_id)
            ->whereKeyNot($dish->getKey())
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        $pairingDishes = Dish::query()
            ->with(['category.translations', 'translations'])
            ->active()
            ->availableInLocale()
            ->featured()
            ->whereKeyNot($dish->getKey())
            ->where('category_id', '!=', $dish->category_id)
            ->orderBy('sort_order')
            ->limit(3)
            ->get();

        $breadcrumbs = [
            ['label' => __('site.nav.home'), 'url' => localized_route('home')],
            ['label' => __('site.nav.menu'), 'url' => localized_route('menu.index')],
            ['label' => $dish->localized('name')],
        ];

        $seo = SeoService::page(
            $dish->localized('meta_title') ?: "{$dish->localized('name')} | Đàn Hương Chay Hải Phòng",
            $dish->localized('meta_description') ?: $dish->localized('description'),
            $dish->localized('meta_keywords') ?: "{$dish->localized('name')}, vegetarian restaurant Hai Phong, Dan Huong Chay",
            localized_route('menu.show', ['slug' => $dish->localizedSlug()]),
            $dish->image,
            'article',
            $dish->localized('name'),
            SeoService::routeAlternates('menu.show', ['dish' => $dish])
        );

        $schemas = [
            SeoService::restaurantSchema(),
            SeoService::dishSchema($dish),
            SeoService::breadcrumbSchema($breadcrumbs),
        ];

        return view('menu.show', compact('dish', 'relatedDishes', 'pairingDishes', 'breadcrumbs', 'seo', 'schemas'));
    }

    private function localizedRedirect(Dish $dish, string $requestedSlug): ?RedirectResponse
    {
        if (current_locale() === config('locales.default', 'vi')) {
            return null;
        }

        $translation = $dish->translation(current_locale());
        if (! $translation || blank($translation->slug) || blank($translation->name)) {
            return redirect()->route('menu.show', $dish, 301);
        }

        if ($requestedSlug !== $translation->slug) {
            return redirect()->route('localized.menu.show', ['slug' => $translation->slug], 301);
        }

        return null;
    }
}
