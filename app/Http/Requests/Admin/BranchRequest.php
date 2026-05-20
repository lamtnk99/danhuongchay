<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $branch = $this->route('branch');
        $branchId = is_object($branch) ? $branch->id : $branch;

        return [
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:200', Rule::unique('branches', 'slug')->ignore($branchId)],
            'city' => ['nullable', 'string', 'max:120'],
            'address' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:40'],
            'hotline' => ['nullable', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:180'],
            'opening_hours' => ['nullable', 'string', 'max:255'],
            'reservation_time_slots' => ['nullable', 'string', 'max:500'],
            'reservation_last_booking_time' => ['nullable', 'date_format:H:i'],
            'reservation_last_order_buffer_minutes' => ['nullable', 'integer', 'min:0', 'max:240'],
            'google_map_iframe' => ['nullable', 'string', 'max:5000'],
            'description' => ['nullable', 'string', 'max:1600'],
            'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:'.config('uploads.max_image_kb')],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'zalo_url' => ['nullable', 'url', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0', Rule::unique('branches', 'sort_order')->ignore($branchId)],
            'is_active' => ['nullable', 'boolean'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'sort_order.unique' => 'Thứ tự hiển thị đã được dùng cho cơ sở khác. Vui lòng chọn số khác.',
        ];
    }
}
