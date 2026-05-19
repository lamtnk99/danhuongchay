@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['items' => $breadcrumbs])

    @php
        $gallery = collect($dish->gallery ?: [])->filter()->values();
        $displayImages = collect([$dish->image])->merge($gallery)->filter()->unique()->values();
        $currentPrice = $dish->sale_price ?: $dish->price;
        $dishName = $dish->localized('name');
    @endphp

    <span class="sr-only" data-track-view="view_dish" data-track-category="dish" data-track-label="{{ $dishName }}" data-item-name="{{ $dishName }}" data-value="{{ (int) $currentPrice }}" data-currency="VND" data-facebook-event="ViewDish"></span>

    <section class="section-block pt-8">
        <div class="grid gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:items-start">
            <div class="space-y-4">
                <div class="overflow-hidden rounded-3xl border border-emerald-900/10 bg-white shadow-xl">
                    <img src="{{ media_url($dish->image) }}" alt="{{ $dishName }}" class="aspect-[4/3] w-full object-cover" fetchpriority="high">
                </div>

                @if ($displayImages->count() > 1)
                    <div class="grid grid-cols-4 gap-3">
                        @foreach ($displayImages as $image)
                            <img src="{{ media_url($image) }}" alt="{{ $dishName }} - {{ $loop->iteration }}" class="aspect-square rounded-2xl border border-emerald-900/10 object-cover" loading="lazy">
                        @endforeach
                    </div>
                @endif
            </div>

            <div>
                <p class="eyebrow">{{ $dish->category->localized('name') }}</p>
                <h1 class="mt-4 text-4xl font-semibold text-emerald-950 sm:text-5xl">{{ $dishName }}</h1>

                <div class="mt-5 flex flex-wrap items-center gap-3">
                    <p class="text-3xl font-semibold text-amber-800">{{ number_format($currentPrice, 0, ',', '.') }}đ</p>
                    @if ($dish->sale_price)
                        <p class="text-sm text-stone-500 line-through">{{ number_format($dish->price, 0, ',', '.') }}đ</p>
                        <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-bold uppercase text-amber-800">{{ __('site.dish.sale') }}</span>
                    @endif
                    @if ($dish->is_featured)
                        <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold uppercase text-emerald-800">{{ __('site.dish.featured') }}</span>
                    @endif
                </div>

                <p class="mt-6 text-lg leading-8 text-stone-700">{{ $dish->localized('description') }}</p>

                @if ($dish->localized('content'))
                    <div class="article-content mt-6">{!! $dish->localized('content') !!}</div>
                @endif

                <div class="mt-8 rounded-2xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-emerald-950">{{ __('site.dish.ingredients') }}</h2>
                    <p class="mt-3 leading-8 text-stone-700">{{ $dish->localized('ingredients') }}</p>
                </div>
            </div>
        </div>

        <div class="mt-10 overflow-hidden rounded-3xl bg-emerald-950 text-white shadow-xl">
            <div class="grid gap-6 p-6 sm:p-8 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
                <div>
                    <p class="eyebrow text-amber-100">{{ __('site.dish.reservation_eyebrow') }}</p>
                    <h2 class="mt-3 text-3xl font-semibold">{{ __('site.dish.reservation_title') }}</h2>
                    <p class="mt-4 leading-8 text-emerald-50/80">{{ __('site.dish.reservation_text') }}</p>
                </div>
                <div class="grid gap-3 sm:grid-cols-3 lg:grid-cols-1 xl:grid-cols-3">
                    <a href="{{ localized_route('reservations.create') }}" class="btn-primary justify-center" data-track-event="click_dish_reservation" data-track-category="dish" data-track-label="{{ $dishName }}" data-facebook-event="ClickDishReservation">{{ __('site.dish.book') }}</a>
                    <a href="{{ localized_route('local.vegetarian-catering-hai-phong') }}" class="btn-ghost justify-center" data-track-event="click_dish_catering" data-track-category="dish" data-track-label="{{ $dishName }}" data-facebook-event="ClickCatering">{{ __('site.dish.catering') }}</a>
                    <a href="{{ localized_route('menu.index', ['category' => $dish->category->localizedSlug()]) }}" class="btn-secondary justify-center">{{ __('site.dish.same_category') }}</a>
                </div>
            </div>
        </div>
    </section>

    @if ($pairingDishes->isNotEmpty())
        <section class="section-block pt-0">
            <div class="section-heading">
                <p class="eyebrow">{{ __('site.dish.pairing') }}</p>
                <h2>{{ __('site.dish.pairing_title') }}</h2>
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
                <p class="eyebrow">{{ __('site.dish.related') }}</p>
                <h2>{{ __('site.dish.related_title') }}</h2>
            </div>
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($relatedDishes as $dish)
                    @include('partials.dish-card', ['dish' => $dish])
                @endforeach
            </div>
        </section>
    @endif
@endsection
