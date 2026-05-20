@extends('layouts.app')

@section('content')
    <section class="subpage-hero">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:items-center lg:px-8">
            <div>
                <p class="eyebrow">Quán chay Buôn Ma Thuột</p>
                <h1 class="mt-4 text-4xl font-semibold leading-tight text-emerald-950 sm:text-5xl">
                    Đàn Hương Chay Buôn Ma Thuột
                </h1>
                <p class="mt-6 text-lg leading-8 text-stone-700">
                    Cơ sở Buôn Ma Thuột dùng chung tinh thần ẩm thực chay fusion của Đàn Hương Chay: món chay hiện đại, vị thanh, nguyên liệu sạch và không gian an yên cho bữa ăn gia đình, bạn bè.
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('reservations.create', ['branch' => 'buon-ma-thuot']) }}" class="btn-primary" data-track-event="click_reservation_cta" data-track-category="reservation" data-track-label="BMT local reservation" data-facebook-event="ClickReservation">Đặt bàn cơ sở Buôn Ma Thuột</a>
                    <a href="{{ route('gallery.index') }}" class="btn-ghost">Xem không gian quán</a>
                </div>
            </div>
            <div class="rounded-[2rem] border border-emerald-900/10 bg-white p-6 shadow-sm">
                <h2 class="text-2xl font-semibold text-emerald-950">Thông tin cơ sở</h2>
                <dl class="mt-5 space-y-4 text-stone-700">
                    <div><dt class="font-semibold text-emerald-900">Địa chỉ</dt><dd>{{ $branch?->address ?: 'Địa chỉ cơ sở Buôn Ma Thuột đang cập nhật' }}</dd></div>
                    <div><dt class="font-semibold text-emerald-900">Hotline</dt><dd>{{ $branch?->hotline ?: setting('hotline', '0947 361 515') }}</dd></div>
                    <div><dt class="font-semibold text-emerald-900">Giờ mở cửa</dt><dd>{{ $branch?->opening_hours ?: localized_setting('opening_hours', '09:00 - 14:00 | 16:00 - 21:00 hằng ngày') }}</dd></div>
                </dl>
            </div>
        </div>
    </section>

    @if ($featuredDishes->isNotEmpty())
        <section class="section-block">
            <div class="mb-8">
                <p class="eyebrow">Món gợi ý</p>
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950">Thực đơn chay dễ chọn</h2>
            </div>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredDishes as $dish)
                    @include('partials.dish-card', ['dish' => $dish])
                @endforeach
            </div>
        </section>
    @endif

    <section class="section-block">
        <div class="rounded-[2rem] bg-emerald-950 p-6 text-emerald-50 sm:p-10">
            <h2 class="text-3xl font-semibold">Tìm quán chay ở Buôn Ma Thuột?</h2>
            <p class="mt-4 max-w-3xl leading-8 text-emerald-50/80">
                Đàn Hương Chay phù hợp cho khách muốn ăn chay ngon, nhẹ bụng, có không gian yên và có thể đặt bàn trước. Thực đơn chung giúp khách dễ chọn món, còn thông tin cơ sở sẽ được xác nhận khi đặt bàn.
            </p>
            <div class="mt-6 flex flex-wrap gap-4">
                <a href="{{ route('reservations.create', ['branch' => 'buon-ma-thuot']) }}" class="btn-secondary">Đặt bàn</a>
                <a href="tel:{{ preg_replace('/\D+/', '', $branch?->hotline ?: setting('hotline', '0947361515')) }}" class="btn-ghost text-emerald-50 ring-emerald-50/30">Gọi hotline</a>
            </div>
        </div>
    </section>
@endsection
