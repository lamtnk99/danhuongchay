<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $postId = $this->route('post')?->id;

        return [
            'title' => ['required', 'string', 'max:220'],
            'slug' => ['nullable', 'string', 'max:240', Rule::unique('posts', 'slug')->ignore($postId)],
            'category_id' => ['required', 'exists:categories,id'],
            'excerpt' => ['required', 'string', 'max:1000'],
            'content' => ['required', 'string'],
            'thumbnail' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:'.config('uploads.max_image_kb')],
            'is_featured' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'related_post_ids' => ['nullable', 'array', 'max:6'],
            'related_post_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('posts', 'id'),
                Rule::notIn(array_filter([$postId])),
            ],
            'translations' => ['nullable', 'array'],
            'translations.en' => ['nullable', 'array'],
            'translations.en.title' => [
                'nullable',
                'required_with:translations.en.slug,translations.en.excerpt,translations.en.content,translations.en.meta_title,translations.en.meta_description,translations.en.meta_keywords',
                'string',
                'max:220',
            ],
            'translations.en.slug' => ['nullable', 'string', 'max:240'],
            'translations.en.excerpt' => ['nullable', 'string', 'max:1000'],
            'translations.en.content' => ['nullable', 'string'],
            'translations.en.meta_title' => ['nullable', 'string', 'max:255'],
            'translations.en.meta_description' => ['nullable', 'string', 'max:500'],
            'translations.en.meta_keywords' => ['nullable', 'string', 'max:500'],
        ];
    }
}
