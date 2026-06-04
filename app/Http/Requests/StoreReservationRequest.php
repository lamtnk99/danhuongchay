<?php

namespace App\Http\Requests;

use App\Models\Branch;
use App\Support\OpeningHours;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReservationRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        if (! $this->filled('branch_id')) {
            $defaultBranchId = Branch::query()->active()->orderBy('sort_order')->value('id');

            if ($defaultBranchId) {
                $this->merge(['branch_id' => $defaultBranchId]);
            }
        }
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'branch_id' => ['required', Rule::exists('branches', 'id')->where('is_active', true)],
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9+\-\s().]{8,20}$/'],
            'email' => ['nullable', 'email:rfc,dns', 'max:160'],
            'reservation_date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
            'reservation_time' => ['required', 'date_format:H:i'],
            'guests' => ['required', 'integer', 'min:1', 'max:40'],
            'note' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $date = $this->input('reservation_date');
            $time = $this->input('reservation_time');

            if (! $date || ! $time || $validator->errors()->has('reservation_date') || $validator->errors()->has('reservation_time')) {
                return;
            }

            $branch = Branch::query()->active()->find($this->input('branch_id'));
            $openingHours = OpeningHours::fromBranch($branch);

            if (! $openingHours->isWithin($time)) {
                $validator->errors()->add('reservation_time', $openingHours->message().' '.__('site.reservation.opening_hours_extra'));

                return;
            }

            if ($openingHours->isPastToday($date, $time)) {
                $validator->errors()->add('reservation_time', __('site.reservation.past_time'));
            }
        });
    }

    public function messages(): array
    {
        return [
            'name.required' => __('site.reservation.validation.name_required'),
            'branch_id.required' => __('site.reservation.validation.branch_required'),
            'branch_id.exists' => __('site.reservation.validation.branch_exists'),
            'phone.required' => __('site.reservation.validation.phone_required'),
            'phone.regex' => __('site.reservation.validation.phone_regex'),
            'email.email' => __('site.reservation.validation.email_email'),
            'reservation_date.required' => __('site.reservation.validation.date_required'),
            'reservation_date.date_format' => __('site.reservation.validation.date_format'),
            'reservation_date.after_or_equal' => __('site.reservation.validation.date_future'),
            'reservation_time.required' => __('site.reservation.validation.time_required'),
            'reservation_time.date_format' => __('site.reservation.validation.time_format'),
            'guests.required' => __('site.reservation.validation.guests_required'),
            'guests.min' => __('site.reservation.validation.guests_min'),
            'guests.max' => __('site.reservation.validation.guests_max'),
        ];
    }
}
