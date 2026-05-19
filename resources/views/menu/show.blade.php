@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['items' => $breadcrumbs])

    @php
        $gallery = collect($dish->gallery ?: [])->filter()->values();
        $displayImages = collect([$dish->image])->merge($gallery)->filter()->unique()->values();
        $currentPrice = $dish->sale_price ?: $dish->price;
    @endphp

    <span class="sr-only" data-track-view="view_dish" data-track-category="dish" data-track-label="{{ $dish->name }}" data-item-name="{{ $dish->name }}" data-value="{{ (int) $currentPrice }}" data-currency="VND" data-facebook-event="ViewDish"></span>

    <section class="section-block pt-8">
        <div class="grid gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:items-start">
            <div class="space-y-4">
                <div class="overflow-hidden rounded-3xl border border-emerald-900/10 bg-white shadow-xl">
                    <img
                        src="{{ media_url($dish->image) }}"
                        alt="{{ $dish->name }}"
                        class="aspect-[4/3] w-full object-cover"
                        fetchpriority="high"
                    >
                </div>

                @if ($displayImages->count() > 1)
                    <div class="grid grid-cols-4 gap-3">
                        @foreach ($displayImages as $image)
                            <img
                                src="{{ media_url($image) }}"
                                alt="{{ $dish->name }} - ảnh {{ $loop->iteration }}"
                                class="aspect-square rounded-2xl border border-emerald-900/10 object-cover"
                                loading="lazy"
                            >
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <p class="eyebrow">{{ $dish->category->name }}</p>
                <h1 class="mt-4 text-4xl font-semibold text-emerald-950 sm:text-5xl">{{ $dish->name }}</h1>

                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <p class="text-3xl font-semibold text-amber-800">{{ number_format($currentPrice, 0, ',', '.') }}đ</p>
                    @if ($dish->sale_price)
                        <p class="text-sm text-stone-500 line-through">{{ number_format($dish->price, 0, ',', '.') }}đ</p>
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold uppercase text-amber-800">Đang ưu đãi</span>
                    @endif
                    @if ($dish->is_featured)
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold uppercase text-emerald-800">Món nổi bật</span>
                    @endif
                </div>

                <p class="mt-6 text-lg leading-8 text-stone-700">{{ $dish->description }}</p>

                @if ($dish->content)
                    <div class="article-content mt-6">{!! $dish->content !!}</div>
                @endif

                <div class="mt-8 rounded-2xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-emerald-950">Thành phần</h2>
                    <p class="mt-3 leading-8 text-stone-700">{{ $dish->ingredients }}</p>
                </div>
            </div>
        </div>

        <div class="mt-10 overflow-hidden rounded-3xl bg-emerald-950 text-white shadow-xl">
            <div class="grid gap-6 p-6 sm:p-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
                <div>
                    <p class="eyebrow text-amber-100">Gợi ý đặt bàn</p>
                    <h2 class="mt-3 text-3xl font-semibold">Muốn thử món này hôm nay?</h2>
                    <p class="mt-4 leading-8 text-emerald-50/80">
                        Đặt bàn trước để quán giữ chỗ, chuẩn bị trải nghiệm tốt hơn và tư vấn thêm món đi kèm phù hợp với số người.
                    </p>
                </div>
                <div class="grid gap-3 sm:grid-cols-3 lg:grid-cols-1 xl:grid-cols-3">
                    <a href="{{ route('reservations.create') }}" class="btn-primary justify-center" data-track-event="click_dish_reservation" data-track-category="dish" data-track-label="{{ $dish->name }}" data-facebook-event="ClickDishReservation">Đặt bàn</a>
                    <a href="{{ route('local.vegetarian-catering-hai-phong') }}" class="btn-ghost justify-center" data-track-event="click_dish_catering" data-track-category="dish" data-track-label="{{ $dish->name }}" data-facebook-event="ClickCatering">Đặt tiệc</a>
                    <a href="{{ route('menu.index', ['category' => $dish->category->slug]) }}" class="btn-secondary justify-center">Cùng danh mục</a>
                </div>
            </div>
        </div>
    </section>

    @if ($pairingDishes->isNotEmpty())
        <section class="section-block pt-0">
            <div class="section-heading">
                <p class="eyebrow">Gọi cùng món này</p>
                <h2>Gợi ý kết hợp cho bàn ăn trọn vị hơn</h2>
            </div>
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($pairingDishes as $dish)
                    @include('partials.dish-card', ['dish' => $dish])
                @endforeach
            </div>
        </section>
    @endif

    @if ($relatedDishes->isNotEmpty())
        <section class="section-block pt-0">
            <div class="section-heading">
                <p class="eyebrow">Gợi ý thêm</p>
                <h2>Món cùng danh mục</h2>
            </div>
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($relatedDishes as $dish)
                    @include('partials.dish-card', ['dish' => $dish])
                @endforeach
            </div>
        </section>
    @endif
@endsection
