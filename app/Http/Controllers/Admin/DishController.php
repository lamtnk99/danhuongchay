<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DishRequest;
use App\Models\Category;
use App\Models\Dish;
use App\Services\UploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DishController extends Controller
{
    public function __construct(private readonly UploadService $uploads) {}

    public function index(Request $request): View
    {
        $categories = Category::dish()->orderBy('name')->get();
        $dishes = Dish::query()
            ->with('category')
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->q.'%'))
            ->when($request->filled('category_id'), fn ($query) => $query->where('category_id', $request->integer('category_id')))
            ->when($request->filled('status'), fn ($query) => $query->where('is_active', $request->status === 'active'))
            ->orderBy('sort_order')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.dishes.index', compact('dishes', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::dish()->active()->orderBy('name')->get();

        return view('admin.dishes.create', ['dish' => new Dish(['is_active' => true]), 'categories' => $categories]);
    }

    public function store(DishRequest $request): RedirectResponse
    {
        $data = $this->normalizedData($request);

        if ($request->hasFile('image')) {
            $data['image'] = $this->uploads->uploadImage($request->file('image'), 'dishes');
        }

        $data['gallery'] = $this->uploads->uploadMultipleImages($request->file('gallery', []), 'dishes');

        $dish = Dish::create($data);
        $this->syncEnglishTranslation($request, $dish);

        return redirect()->route('admin.dishes.index')->with('success', 'Đã thêm món ăn.');
    }

    public function edit(Dish $dish): View
    {
        $categories = Category::dish()->orderBy('name')->get();

        return view('admin.dishes.edit', compact('dish', 'categories'));
    }

    public function update(DishRequest $request, Dish $dish): RedirectResponse
    {
        $data = $this->normalizedData($request);
        $gallery = $dish->gallery ?? [];
        $removed = $request->input('remove_gallery', []);

        if ($request->hasFile('image')) {
            $oldImage = $dish->image;
            $data['image'] = $this->uploads->uploadImage($request->file('image'), 'dishes');
            $this->uploads->deleteImage($oldImage);
        }

        if ($removed) {
            $this->uploads->deleteImages($removed);
            $gallery = array_values(array_diff($gallery, $removed));
        }

        $gallery = array_merge($gallery, $this->uploads->uploadMultipleImages($request->file('gallery', []), 'dishes'));
        $data['gallery'] = $gallery;

        $dish->update($data);
        $this->syncEnglishTranslation($request, $dish);

        return redirect()->route('admin.dishes.index')->with('success', 'Đã cập nhật món ăn.');
    }

    public function destroy(Dish $dish): RedirectResponse
    {
        $this->uploads->deleteImage($dish->image);
        $this->uploads->deleteImages($dish->gallery ?? []);
        $dish->delete();

        return back()->with('success', 'Đã xóa món ăn.');
    }

    private function normalizedData(DishRequest $request): array
    {
        return collect($request->validated())
            ->except(['image', 'gallery', 'remove_gallery', 'translations'])
            ->merge([
                'is_featured' => $request->boolean('is_featured'),
                'is_active' => $request->boolean('is_active'),
                'price' => (int) $request->input('price'),
                'sale_price' => $request->filled('sale_price') ? (int) $request->input('sale_price') : null,
            ])
            ->all();
    }

    private function syncEnglishTranslation(DishRequest $request, Dish $dish): void
    {
        $translation = data_get($request->validated(), 'translations.en', []);

        if ($translation === []) {
            return;
        }

        $dish->translations()->updateOrCreate(['locale' => 'en'], collect($translation)->map(fn ($value) => $value === '' ? null : $value)->all());
    }
}
