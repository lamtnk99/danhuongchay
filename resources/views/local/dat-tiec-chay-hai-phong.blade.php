@extends('layouts.app')

@section('content')
    @php
        $fitItems = __('site.local.fit_items.hai_phong');
    @endphp

    <section class="relative isolate overflow-hidden bg-stone-950 text-white">
        <img
            src="{{ media_variant_url(setting('default_background'), 'hero', 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1600&q=85') }}"
            @if (media_srcset(setting('default_background'), ['card', 'large', 'hero'])) srcset="{{ media_srcset(setting('default_background'), ['card', 'large', 'hero']) }}" @endif
            alt="{{ __('site.auto.auto_8c0a6ee05bf2') }}"
            class="absolute inset-0 -z-20 h-full w-full object-cover"
            fetchpriority="high"
            sizes="100vw"
        >
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[#3d4226]/92 via-[#3d4226]/72 to-amber-900/35"></div>
        <div class="mx-auto grid min-h-[70vh] max-w-7xl gap-10 px-4 py-20 sm:px-6 lg:grid-cols-[1fr_0.9fr] lg:items-center lg:px-8">
            <div>
                <p class="eyebrow text-amber-100">{{ __('site.auto.auto_f6c3a3c301d9') }}</p>
                <h1 class="mt-5 max-w-3xl text-4xl font-semibold leading-tight sm:text-5xl lg:text-6xl">
                    {{ __('site.auto.auto_3374f30d45e9') }}
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-[#f5f0e0]/90">
                    {{ __('site.auto.auto_8c86d6bcd85c') }}
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ localized_route('reservations.create') }}" class="btn-primary" data-track-event="click_catering_reservation" data-track-category="catering" data-track-label="Catering hero reservation" data-facebook-event="ClickCatering">{{ __('site.auto.auto_5aa21b69bdb1') }}</a>
                    <a href="tel:{{ preg_replace('/\D+/', '', setting('hotline', '0947361515')) }}" class="btn-ghost" data-track-event="click_call" data-track-category="contact" data-track-label="Catering hero call" data-facebook-event="ClickCall">{{ __('site.auto.auto_d88b137620bd') }}</a>
                </div>
            </div>

            <div class="rounded-[2rem] border border-white/15 bg-white/12 p-5 shadow-2xl backdrop-blur">
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-100">{{ __('site.auto.auto_b840b4d839ad') }}</p>
                <div class="mt-5 grid gap-3">
                    @foreach ($fitItems as $item)
                        <div class="rounded-2xl bg-white/12 px-4 py-3 text-sm font-semibold text-[#f5f0e0]">{{ $item }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <div class="section-block pb-0">
        @include('partials.contextual-cta', [
            'context' => 'catering',
            'kicker' => __('site.auto.auto_f284bcf40e37'),
            'title' => __('site.auto.auto_01c0e3bdc14d'),
            'text' => __('site.auto.auto_ffcab5ff87fc'),
            'primaryLabel' => __('site.auto.auto_5aa21b69bdb1'),
            'secondaryLabel' => __('site.auto.auto_01818bd8ddf3'),
            'secondaryUrl' => '#mon-goi-y',
            'trackLabel' => 'Catering landing smart CTA',
        ])
    </div>

    <section class="section-block">
        <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
            <div>
                <p class="eyebrow">{{ __('site.auto.auto_4c5bc196f8ae') }}</p>
                <h2 class="mt-3 text-3xl font-semibold text-[#3d4226] sm:text-4xl">{{ __('site.auto.auto_da31ce7c5db7') }}</h2>
                <p class="mt-5 leading-8 text-stone-700">
                    {{ __('site.auto.auto_4fb89a84fd3f') }}
                </p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <article class="rounded-3xl border border-[#667132]/10 bg-[#f5f5ec] p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-[#3d4226]">{{ __('site.auto.auto_db5b1df25323') }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ __('site.auto.auto_eda70abd80ce') }}</p>
                </article>
                <article class="rounded-3xl border border-[#667132]/10 bg-[#f5f5ec] p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-[#3d4226]">{{ __('site.auto.auto_78f1b1f3c1ed') }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ __('site.auto.auto_e4c5f822d7a4') }}</p>
                </article>
                <article class="rounded-3xl border border-[#667132]/10 bg-[#f5f5ec] p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-[#3d4226]">{{ __('site.auto.auto_d1480870ce1d') }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ __('site.auto.auto_b0b8fe2060b9') }}</p>
                </article>
                <article class="rounded-3xl border border-[#667132]/10 bg-[#f5f5ec] p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-[#3d4226]">{{ __('site.auto.auto_c47dda37fbb1') }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ __('site.auto.auto_122c166b0a1c') }}</p>
                </article>
            </div>
        </div>
    </section>

    @if ($suggestedDishes->isNotEmpty())
        <section id="mon-goi-y" class="section-block pt-0">
            <div class="section-heading">
                <p class="eyebrow">{{ __('site.auto.auto_52d47ed614b1') }}</p>
                <h2>{{ __('site.auto.auto_b2637a151f5a') }}</h2>
                <p>{{ __('site.auto.auto_68b5e4405855') }}</p>
            </div>
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($suggestedDishes as $dish)
                    @include('partials.dish-card', ['dish' => $dish])
                @endforeach
            </div>
        </section>
    @endif

    <section class="bg-[#3d4226] text-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-16 sm:px-6 lg:grid-cols-[0.8fr_1.2fr] lg:items-center lg:px-8">
            <div>
                <p class="eyebrow text-amber-100">{{ __('site.auto.auto_8774eed2926f') }}</p>
                <h2 class="mt-3 text-3xl font-semibold sm:text-4xl">{{ __('site.auto.auto_5635684aabf2') }}</h2>
                <p class="mt-5 leading-8 text-[#f5f0e0]/80">
                    {{ __('site.auto.auto_7a4bd4295a02') }}
                </p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ localized_route('reservations.create') }}" class="btn-primary" data-track-event="click_catering_reservation" data-track-category="catering" data-track-label="Catering middle reservation" data-facebook-event="ClickCatering">{{ __('site.auto.auto_8231b41edb40') }}</a>
                    <a href="{{ setting('zalo_url') }}" class="btn-ghost" target="_blank" rel="noopener" data-track-event="click_zalo" data-track-category="contact" data-track-label="Catering Zalo" data-facebook-event="ClickZalo">{{ __('site.auto.auto_be5c9ba6c231') }}</a>
                </div>
            </div>
            <div class="overflow-hidden rounded-3xl border border-white/15 bg-white/10 shadow-2xl">
                @if (setting('google_map_iframe'))
                    {!! setting('google_map_iframe') !!}
                @endif
            </div>
        </div>
    </section>

    <section class="section-block">
        <div class="grid gap-10 lg:grid-cols-[0.8fr_1.2fr]">
            <div>
                <p class="eyebrow">{{ __('site.auto.auto_2dddf641c7f3') }}</p>
                <h2 class="mt-3 text-3xl font-semibold text-[#3d4226] sm:text-4xl">{{ __('site.auto.auto_99b090f6110f') }}</h2>
            </div>
            <div class="space-y-4">
                @foreach ($faqs as $faq)
                    <details class="group rounded-3xl border border-[#667132]/10 bg-[#f5f5ec] p-6 shadow-sm" @if ($loop->first) open @endif>
                        <summary class="cursor-pointer list-none text-lg font-semibold text-[#3d4226]">{{ $faq['question'] }}</summary>
                        <p class="mt-4 leading-8 text-stone-700">{{ $faq['answer'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
@endsection
