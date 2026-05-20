@extends('admin.layouts.app')

@section('title', $title)

@section('content')
    <form method="POST" action="{{ $action }}" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        @method('PUT')

        <div class="admin-form-tabs" data-admin-tabs>
            <div class="admin-tab-nav" role="tablist" aria-label="Ngôn ngữ cấu hình website">
                <button type="button" class="admin-tab-button is-active" data-admin-tab="vi">Tiếng Việt</button>
                <button type="button" class="admin-tab-button" data-admin-tab="en">English</button>
            </div>

            <div class="admin-tab-panel is-active" data-admin-tab-panel="vi">
                <div class="grid gap-5 lg:grid-cols-2">
                    @foreach ($keys as $key => $label)
                        <div @class(['lg:col-span-2' => in_array($key, ['short_description', 'address', 'google_map_iframe', 'footer_description'])])>
                            <label for="{{ $key }}" class="admin-label">{{ $label }}</label>
                            @if (in_array($key, ['short_description', 'address', 'google_map_iframe', 'footer_description']))
                                <textarea id="{{ $key }}" name="{{ $key }}" rows="4" class="admin-input">{{ old($key, setting($key)) }}</textarea>
                            @elseif ($key === 'reservation_last_order_buffer_minutes')
                                <input id="{{ $key }}" type="number" min="0" max="240" name="{{ $key }}" value="{{ old($key, setting($key, 30)) }}" class="admin-input">
                            @elseif ($key === 'show_dish_prices')
                                <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 font-semibold text-slate-700">
                                    <input type="checkbox" name="{{ $key }}" value="1" @checked(old($key, setting($key, '1')) === '1')>
                                    Hiện giá món ăn ở trang chủ, thực đơn và chi tiết món
                                </label>
                            @else
                                <input id="{{ $key }}" name="{{ $key }}" value="{{ old($key, setting($key)) }}" class="admin-input">
                            @endif
                            @if ($key === 'reservation_time_slots')
                                <p class="mt-2 text-xs text-slate-500">Ví dụ: <code>09:00-14:00,16:00-21:00</code> (có thể ngăn cách bằng dấu phẩy, chấm phẩy hoặc xuống dòng).</p>
                            @elseif ($key === 'reservation_last_booking_time')
                                <p class="mt-2 text-xs text-slate-500">Ví dụ: <code>20:30</code>. Để trống nếu muốn hệ thống tự tính theo giờ đóng ca trừ đi số phút chặn.</p>
                            @elseif ($key === 'reservation_last_order_buffer_minutes')
                                <p class="mt-2 text-xs text-slate-500">Số phút ngừng nhận đặt bàn trước giờ kết thúc mỗi ca (đề xuất: 30).</p>
                            @endif
                            @error($key) <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="admin-tab-panel" data-admin-tab-panel="en" hidden>
                <div class="mb-5 rounded-2xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
                    Các trường này dùng cho bản tiếng Anh ở header, footer, trang liên hệ và các meta mặc định. Có thể để trống để hệ thống tự dùng bản tiếng Việt.
                </div>
                <div class="grid gap-5 lg:grid-cols-2">
                    @foreach ($translationKeys as $key => $label)
                        <div @class(['lg:col-span-2' => in_array($key, ['short_description_en', 'address_en', 'footer_description_en'])])>
                            <label for="{{ $key }}" class="admin-label">{{ $label }}</label>
                            @if (in_array($key, ['short_description_en', 'address_en', 'footer_description_en']))
                                <textarea id="{{ $key }}" name="{{ $key }}" rows="4" class="admin-input">{{ old($key, setting($key)) }}</textarea>
                            @else
                                <input id="{{ $key }}" name="{{ $key }}" value="{{ old($key, setting($key)) }}" class="admin-input">
                            @endif
                            @error($key) <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <button type="submit" class="admin-btn-primary">Lưu cài đặt</button>
            <a href="{{ route('admin.dashboard') }}" class="admin-btn-secondary">Quay lại</a>
        </div>
    </form>
@endsection
