@extends('layouts.app')

@section('content')
    <section class="subpage-hero">
        <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
            <p class="eyebrow">Liên hệ</p>
            <h1 class="mt-4 text-4xl font-semibold text-emerald-950 sm:text-5xl">Gửi lời nhắn đến Đàn Hương Chay</h1>
            <p class="mt-6 text-lg leading-8 text-stone-700">
                Chúng tôi luôn sẵn sàng hỗ trợ thông tin thực đơn, đặt bàn, sự kiện nhỏ và các nhu cầu ăn chay riêng.
            </p>
        </div>
    </section>

    <section class="section-block">
        <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr]">
            <div class="space-y-6">
                <div class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-semibold text-emerald-950">Thông tin quán</h2>
                    <dl class="mt-5 space-y-4 text-stone-700">
                        <div>
                            <dt class="font-semibold text-emerald-900">Tên</dt>
                            <dd>{{ setting('restaurant_name', 'Đàn Hương Chay') }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-emerald-900">Địa chỉ</dt>
                            <dd>{{ setting('address', '123 Đường An Nhiên, Quận 3, TP. Hồ Chí Minh') }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-emerald-900">Điện thoại</dt>
                            <dd>{{ setting('phone', '0912 345 678') }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-emerald-900">Email</dt>
                            <dd>{{ setting('email', 'hello@danhuongchay.vn') }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-emerald-900">Giờ mở cửa</dt>
                            <dd>{{ setting('opening_hours', '09:00 - 21:30 hằng ngày') }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="overflow-hidden rounded-3xl border border-emerald-900/10 bg-white shadow-sm">
                    @if (setting('google_map_iframe'))
                        {!! setting('google_map_iframe') !!}
                    @else
                        <iframe
                            title="Bản đồ mẫu Đàn Hương Chay"
                            src="https://www.google.com/maps?q=Ho%20Chi%20Minh%20City&output=embed"
                            class="h-80 w-full"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    @endif
                </div>
            </div>

            <div class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm sm:p-8">
                @if (session('success'))
                    <div class="mb-6 rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST" class="grid gap-5" data-submit-loading>
                    @csrf
                    <div>
                        <label for="name" class="form-label">Họ tên</label>
                        <input id="name" name="name" value="{{ old('name') }}" class="form-input" autocomplete="name" required>
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" class="form-input" inputmode="tel" autocomplete="tel">
                            @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" inputmode="email" autocomplete="email">
                            @error('email') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label for="message" class="form-label">Nội dung</label>
                        <textarea id="message" name="message" rows="6" class="form-input" required>{{ old('message') }}</textarea>
                        @error('message') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="btn-primary justify-center" data-loading-text="Đang gửi liên hệ...">Gửi liên hệ</button>
                </form>
            </div>
        </div>
    </section>
@endsection
