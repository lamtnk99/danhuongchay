@extends('layouts.app')

@section('content')
    <section class="subpage-hero local-bmt-hero">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:items-center lg:px-8">
            <div>
                <p class="eyebrow">{{ __('site.auto.auto_707a2a0d223e') }}</p>
                <h1 class="mt-4 text-4xl font-semibold leading-tight text-[#3d4226] sm:text-5xl">
                    Đàn Hương Chay Buôn Ma Thuột
                </h1>
                <p class="mt-6 text-lg leading-8 text-stone-700">
                    {{ __('site.auto.auto_fb10902a4ac4') }}
                </p>
                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ localized_route('reservations.create', ['branch' => 'buon-ma-thuot']) }}" class="btn-primary" data-track-event="click_reservation_cta" data-track-category="reservation" data-track-label="BMT local reservation" data-facebook-event="ClickReservation">{{ __('site.auto.auto_019bf13295f2') }}</a>
                    <a href="{{ localized_route('gallery.index') }}" class="btn-ghost">{{ __('site.auto.auto_7f8ad9fef0fd') }}</a>
                </div>
            </div>
            <div class="rounded-[2rem] border border-[#667132]/10 bg-white p-6 shadow-sm">
                <h2 class="text-2xl font-semibold text-[#3d4226]">{{ __('site.auto.auto_4cbc1f905cb6') }}</h2>
                <dl class="mt-5 space-y-4 text-stone-700">
                    <div><dt class="font-semibold text-[#3d4226]">{{ __('site.auto.auto_1fdae2306b86') }}</dt><dd>{{ $branch?->address ?: (__('site.auto.auto_46eb2eda8186')) }}</dd></div>
                    <div><dt class="font-semibold text-[#3d4226]">Hotline</dt><dd>{{ $branch?->hotline ?: setting('hotline', '0947 361 515') }}</dd></div>
                    <div><dt class="font-semibold text-[#3d4226]">{{ __('site.auto.auto_1a8ba12780ee') }}</dt><dd>{{ $branch?->opening_hours ?: localized_setting('opening_hours', __('site.auto.auto_a1a3b721be2c')) }}</dd></div>
                </dl>
            </div>
        </div>
    </section>

    @if ($featuredDishes->isNotEmpty())
        <section class="section-block">
            <div class="mb-8">
                <p class="eyebrow">{{ __('site.auto.auto_71df4f3a0fc4') }}</p>
                <h2 class="mt-3 text-3xl font-semibold text-[#3d4226]">{{ __('site.auto.auto_ce3106f12b87') }}</h2>
            </div>
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredDishes as $dish)
                    @include('partials.dish-card', ['dish' => $dish])
                @endforeach
            </div>
        </section>
    @endif

    <section class="section-block">
        <div class="rounded-[2rem] bg-[#3d4226] p-6 text-[#f5f0e0] sm:p-10">
            <h2 class="text-3xl font-semibold">{{ __('site.auto.auto_d3e0da51cfea') }}</h2>
            <p class="mt-4 max-w-3xl leading-8 text-[#f5f0e0]/80">
                {{ __('site.auto.auto_5212cd7f1237') }}
            </p>
            <div class="mt-6 flex flex-wrap gap-4">
                <a href="{{ localized_route('reservations.create', ['branch' => 'buon-ma-thuot']) }}" class="btn-secondary">{{ __('site.auto.auto_ca83f60df53d') }}</a>
                <a href="tel:{{ preg_replace('/\D+/', '', $branch?->hotline ?: setting('hotline', '0947361515')) }}" class="btn-ghost text-[#f5f0e0] ring-[#f5f0e0]/30">{{ __('site.auto.auto_5cab6ab48928') }}</a>
            </div>
        </div>
    </section>
@endsection
