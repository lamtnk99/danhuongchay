@extends('layouts.app')

@section('content')
    <section class="catering-hub-hero">
        <img
            src="{{ media_variant_url(setting('default_background'), 'hero', 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1800&q=85') }}"
            alt="{{ __('site.auto.auto_e9324810c831') }}"
            class="catering-hub-bg"
            fetchpriority="high"
        >
        <div class="catering-hub-overlay"></div>
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="max-w-3xl text-white">
                <p class="eyebrow text-amber-100">{{ __('site.auto.auto_6137f2ef34fc') }}</p>
                <h1>{{ __('site.auto.auto_ed75d636940b') }}</h1>
                <p>{{ __('site.auto.auto_c1cf8f9a741f') }}</p>
            </div>
        </div>
    </section>

    <section class="section-block">
        <div class="catering-branch-grid">
            @foreach ($branches as $branch)
                @php
                    $isBmt = $branch->slug === 'buon-ma-thuot';
                    $detailUrl = $isBmt ? localized_route('local.vegetarian-catering-buon-ma-thuot') : localized_route('local.vegetarian-catering-hai-phong');
                @endphp
                <article class="catering-branch-card">
                    <div class="catering-branch-image">
                        <img
                            src="{{ media_variant_url($branch->image, 'large', $isBmt ? 'https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?auto=format&fit=crop&w=1000&q=82' : 'https://images.unsplash.com/photo-1551218808-94e220e084d2?auto=format&fit=crop&w=1000&q=82') }}"
                            alt="{{ $branch->name }}"
                            loading="lazy"
                        >
                    </div>
                    <div class="catering-branch-copy">
                        <p>{{ $branch->city ?: ($isBmt ? 'Buôn Ma Thuột' : 'Hải Phòng') }}</p>
                        <h2>{{ $branch->name }}</h2>
                        <span>{{ $branch->address }}</span>
                        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ $detailUrl }}" class="btn-primary">{{ __('site.auto.auto_17dabdfd0eb5') }}</a>
                            <a href="{{ localized_route('reservations.create', ['branch' => $branch->slug]) }}" class="btn-secondary">{{ __('site.auto.auto_50ddbd8a5806') }}</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
