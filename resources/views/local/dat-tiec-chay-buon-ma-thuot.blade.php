@extends('layouts.app')

@section('content')
    @php
        $fitItems = __('site.local.fit_items.buon_ma_thuot');
    @endphp

    <section class="relative isolate overflow-hidden bg-stone-950 text-white">
        <img
            src="{{ media_variant_url($branch?->image ?: setting('default_background'), 'hero', 'https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?auto=format&fit=crop&w=1600&q=85') }}"
            alt="{{ __('site.auto.auto_485747b6a0cb') }}"
            class="absolute inset-0 -z-20 h-full w-full object-cover"
            fetchpriority="high"
            sizes="100vw"
        >
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[#3d4226]/92 via-[#3d4226]/72 to-amber-900/35"></div>
        <div class="mx-auto grid min-h-[70vh] max-w-7xl gap-10 px-4 py-20 sm:px-6 lg:grid-cols-[1fr_0.9fr] lg:items-center lg:px-8">
            <div>
                <p class="eyebrow text-amber-100">{{ __('site.auto.auto_485747b6a0cb') }}</p>
                <h1 class="mt-5 max-w-3xl text-4xl font-semibold leading-tight sm:text-5xl lg:text-6xl">
                    {{ __('site.auto.auto_64840383337c') }}
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-[#f5f0e0]/90">
                    {{ __('site.auto.auto_981597ac3335') }}
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ localized_route('reservations.create', ['branch' => 'buon-ma-thuot']) }}" class="btn-primary">{{ __('site.auto.auto_5aa21b69bdb1') }}</a>
                    <a href="tel:{{ preg_replace('/\D+/', '', $branch?->hotline ?: setting('hotline', '0947361515')) }}" class="btn-ghost">{{ __('site.auto.auto_d88b137620bd') }}</a>
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
            'title' => __('site.auto.auto_f4e38cbbad44'),
            'text' => __('site.auto.auto_94fbf111a40f'),
            'primaryLabel' => __('site.auto.auto_5aa21b69bdb1'),
            'secondaryLabel' => __('site.auto.auto_01818bd8ddf3'),
            'secondaryUrl' => '#mon-goi-y',
            'trackLabel' => 'BMT catering landing smart CTA',
        ])
    </div>

    <section class="section-block">
        <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
            <div>
                <p class="eyebrow">{{ __('site.auto.auto_db2a0021af5c') }}</p>
                <h2 class="mt-3 text-3xl font-semibold text-[#3d4226] sm:text-4xl">{{ __('site.auto.auto_2ab8c397578d') }}</h2>
                <p class="mt-5 leading-8 text-stone-700">
                    {{ __('site.auto.auto_89ec66bfd036') }}
                </p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <article class="rounded-3xl border border-[#667132]/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-[#3d4226]">{{ __('site.auto.auto_8815640d4294') }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ __('site.auto.auto_63d852087adf') }}</p>
                </article>
                <article class="rounded-3xl border border-[#667132]/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-[#3d4226]">{{ __('site.auto.auto_de1f0731448c') }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ __('site.auto.auto_96abb8e5cd77') }}</p>
                </article>
                <article class="rounded-3xl border border-[#667132]/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-[#3d4226]">{{ __('site.auto.auto_c11ff7231835') }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ __('site.auto.auto_e6013679ccd2') }}</p>
                </article>
                <article class="rounded-3xl border border-[#667132]/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-[#3d4226]">{{ __('site.auto.auto_045e81632804') }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ __('site.auto.auto_bfa2b9fb6d6c') }}</p>
                </article>
            </div>
        </div>
    </section>

    @if ($suggestedDishes->isNotEmpty())
        <section id="mon-goi-y" class="section-block pt-0">
            <div class="section-heading">
                <p class="eyebrow">{{ __('site.auto.auto_52d47ed614b1') }}</p>
                <h2>{{ __('site.auto.auto_b2637a151f5a') }}</h2>
                <p>{{ __('site.auto.auto_294211ad86dc') }}</p>
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
                <p class="eyebrow text-amber-100">{{ __('site.auto.auto_d2cbeac52f93') }}</p>
                <h2 class="mt-3 text-3xl font-semibold sm:text-4xl">{{ $branch?->name ?: 'Đàn Hương Chay Buôn Ma Thuột' }}</h2>
                <p class="mt-5 leading-8 text-[#f5f0e0]/80">{{ $branch?->address }}</p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ localized_route('reservations.create', ['branch' => 'buon-ma-thuot']) }}" class="btn-primary">{{ __('site.auto.auto_3e253ba17360') }}</a>
                    @if ($branch?->zalo_url ?: setting('zalo_url'))
                        <a href="{{ $branch?->zalo_url ?: setting('zalo_url') }}" class="btn-ghost" target="_blank" rel="noopener">{{ __('site.auto.auto_be5c9ba6c231') }}</a>
                    @endif
                </div>
            </div>
            <div class="overflow-hidden rounded-3xl border border-white/15 bg-white/10 shadow-2xl">
                @if ($branch?->google_map_iframe)
                    {!! $branch->google_map_iframe !!}
                @endif
            </div>
        </div>
    </section>

    <section class="section-block">
        <div class="grid gap-10 lg:grid-cols-[0.8fr_1.2fr]">
            <div>
                <p class="eyebrow">{{ __('site.auto.auto_2dddf641c7f3') }}</p>
                <h2 class="mt-3 text-3xl font-semibold text-[#3d4226] sm:text-4xl">{{ __('site.auto.auto_80a7822e9d32') }}</h2>
            </div>
            <div class="space-y-4">
                @foreach ($faqs as $faq)
                    <details class="group rounded-3xl border border-[#667132]/10 bg-white p-6 shadow-sm" @if ($loop->first) open @endif>
                        <summary class="cursor-pointer list-none text-lg font-semibold text-[#3d4226]">{{ $faq['question'] }}</summary>
                        <p class="mt-4 leading-8 text-stone-700">{{ $faq['answer'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
@endsection
