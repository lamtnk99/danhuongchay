<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DishRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $dishId = $this->route('dish')?->id;

        return [
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('dishes', 'slug')->ignore($dishId)],
            'category_id' => ['required', 'exists:categories,id'],
            'description' => ['required', 'string', 'max:1200'],
            'content' => ['nullable', 'string'],
            'ingredients' => ['nullable', 'string', 'max:1600'],
            'price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0', 'lte:price'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:'.config('uploads.max_image_kb')],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['file', 'mimes:jpg,jpeg,png,webp,svg', 'max:'.config('uploads.max_image_kb')],
            'remove_gallery' => ['nullable', 'array'],
            'remove_gallery.*' => ['string'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'translations' => ['nullable', 'array'],
            'translations.en' => ['nullable', 'array'],
            'translations.en.name' => ['nullable', 'string', 'max:180'],
            'translations.en.slug' => ['nullable', 'string', 'max:200'],
            'translations.en.description' => ['nullable', 'string', 'max:1200'],
            'translations.en.content' => ['nullable', 'string'],
            'translations.en.ingredients' => ['nullable', 'string', 'max:1600'],
            'translations.en.meta_title' => ['nullable', 'string', 'max:255'],
            'translations.en.meta_description' => ['nullable', 'string', 'max:500'],
            'translations.en.meta_keywords' => ['nullable', 'string', 'max:500'],
        ];
    }
}
