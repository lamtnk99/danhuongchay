@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['items' => $breadcrumbs])

    <section class="section-block pt-8">
        <div class="grid gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:items-start">
            <div class="overflow-hidden rounded-3xl border border-emerald-900/10 bg-white shadow-xl">
                <img
                    src="{{ media_url($dish->image) }}"
                    alt="{{ $dish->name }}"
                    class="aspect-[4/3] w-full object-cover"
                    fetchpriority="high"
                >
            </div>
            <div>
                <p class="eyebrow">{{ $dish->category->name }}</p>
                <h1 class="mt-4 text-4xl font-semibold text-emerald-950 sm:text-5xl">{{ $dish->name }}</h1>
                <p class="mt-5 text-2xl font-semibold text-amber-800">{{ number_format($dish->sale_price ?: $dish->price, 0, ',', '.') }}đ</p>
                @if ($dish->sale_price)
                    <p class="mt-1 text-sm text-stone-500 line-through">{{ number_format($dish->price, 0, ',', '.') }}đ</p>
                @endif
                <p class="mt-6 text-lg leading-8 text-stone-700">{{ $dish->description }}</p>

                @if ($dish->content)
                    <div class="article-content mt-6">{!! $dish->content !!}</div>
                @endif

                <div class="mt-8 rounded-2xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h2 class="text-xl font-semibold text-emerald-950">Thành phần</h2>
                    <p class="mt-3 leading-8 text-stone-700">{{ $dish->ingredients }}</p>
                </div>

                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('reservations.create') }}" class="btn-primary">Đặt bàn</a>
                    <a href="{{ route('menu.index', ['category' => $dish->category->slug]) }}" class="btn-secondary">Xem cùng danh mục</a>
                </div>
            </div>
        </div>
    </section>

    @if ($relatedDishes->isNotEmpty())
        <section class="section-block pt-0">
            <div class="section-heading">
                <p class="eyebrow">Gợi ý thêm</p>
                <h2>Món liên quan</h2>
            </div>
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($relatedDishes as $dish)
                    @include('partials.dish-card', ['dish' => $dish])
                @endforeach
            </div>
        </section>
    @endif
@endsection
