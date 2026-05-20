<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PromotionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $promotion = $this->route('promotion');
        $promotionId = is_object($promotion) ? $promotion->id : $promotion;

        return [
            'title' => ['required', 'string', 'max:180'],
            'subtitle' => ['nullable', 'string', 'max:180'],
            'description' => ['nullable', 'string', 'max:1200'],
            'badge' => ['nullable', 'string', 'max:80'],
            'button_text' => ['nullable', 'string', 'max:80'],
            'button_link' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:'.config('uploads.max_image_kb')],
            'placement' => ['required', Rule::in(['home', 'popup'])],
            'template' => ['required', Rule::in(['split', 'center', 'minimal'])],
            'accent_color' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
                Rule::unique('promotions', 'sort_order')
                    ->where(fn ($query) => $query->where('placement', $this->input('placement')))
                    ->ignore($promotionId),
            ],
            'show_once' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'starts_at' => ['nullable', 'date'],
            'ends_at' => ['nullable', 'date', 'after_or_equal:starts_at'],
            'translations' => ['nullable', 'array'],
            'translations.en' => ['nullable', 'array'],
            'translations.en.title' => ['nullable', 'string', 'max:180'],
            'translations.en.subtitle' => ['nullable', 'string', 'max:180'],
            'translations.en.description' => ['nullable', 'string', 'max:1200'],
            'translations.en.badge' => ['nullable', 'string', 'max:80'],
            'translations.en.button_text' => ['nullable', 'string', 'max:80'],
        ];
    }

    public function messages(): array
    {
        return [
            'sort_order.unique' => 'Thứ tự này đã được dùng ở cùng vị trí hiển thị. Bạn chọn một số khác giúp mình nhé.',
        ];
    }
}
