<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Dish;
use App\Services\SeoService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::dish()->active()->orderBy('sort_order')->orderBy('id')->get();
        $selectedCategory = $request->string('category')->toString();
        $search = $request->string('q')->toString();

        $dishes = Dish::query()
            ->with('category')
            ->active()
            ->when($selectedCategory, function ($query) use ($selectedCategory): void {
                $query->whereHas('category', fn ($categoryQuery) => $categoryQuery->where('slug', $selectedCategory));
            })
            ->search($search)
            ->orderBy('sort_order')
            ->latest()
            ->paginate(9)
            ->withQueryString();

        $schemaCategories = Category::dish()
            ->active()
            ->with(['dishes' => fn ($query) => $query->active()->orderBy('name')])
            ->orderBy('id')
            ->get();

        $seo = SeoService::page(
            'Thực đơn chay | Đàn Hương Chay',
            'Khám phá thực đơn Đàn Hương Chay với món khai vị, món chính, lẩu chay, cơm mì bún chay và đồ uống thanh lành.',
            'thực đơn chay, món chay ngon, lẩu chay, cơm chay, Đàn Hương Chay',
            route('menu.index')
        );

        $schemas = [
            SeoService::restaurantSchema(),
            SeoService::menuSchema($schemaCategories),
        ];

        return view('menu.index', compact('categories', 'dishes', 'selectedCategory', 'search', 'seo', 'schemas'));
    }

    public function show(Dish $dish): View
    {
        $dish->load('category');
        abort_unless($dish->is_active, 404);

        $relatedDishes = Dish::query()
            ->with('category')
            ->active()
            ->where('category_id', $dish->category_id)
            ->whereKeyNot($dish->getKey())
            ->latest()
            ->limit(3)
            ->get();

        $breadcrumbs = [
            ['label' => 'Trang chủ', 'url' => route('home')],
            ['label' => 'Thực đơn', 'url' => route('menu.index')],
            ['label' => $dish->name],
        ];

        $seo = SeoService::page(
            $dish->meta_title ?: "{$dish->name} | Đàn Hương Chay",
            $dish->meta_description ?: $dish->description,
            $dish->meta_keywords ?: "{$dish->name}, món chay, {$dish->category->name}, Đàn Hương Chay",
            route('menu.show', $dish),
            $dish->image,
            'article'
        );

        $schemas = [
            SeoService::restaurantSchema(),
            SeoService::breadcrumbSchema($breadcrumbs),
        ];

        return view('menu.show', compact('dish', 'relatedDishes', 'breadcrumbs', 'seo', 'schemas'));
    }
}
