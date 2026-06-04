<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'branch_id' => ['nullable', Rule::exists('branches', 'id')->where('is_active', true)],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-\s().]{8,20}$/'],
            'email' => ['nullable', 'email:rfc,dns', 'max:160'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('site.contact.validation.name_required'),
            'branch_id.exists' => __('site.contact.validation.branch_exists'),
            'phone.regex' => __('site.contact.validation.phone_regex'),
            'email.email' => __('site.contact.validation.email_email'),
            'message.required' => __('site.contact.validation.message_required'),
            'message.min' => __('site.contact.validation.message_min'),
        ];
    }
}
