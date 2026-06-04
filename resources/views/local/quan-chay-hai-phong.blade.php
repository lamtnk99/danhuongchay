@extends('layouts.app')

@section('content')
    <section class="relative isolate overflow-hidden bg-[#3d4226] text-white">
        <img
            src="{{ media_variant_url(setting('default_background'), 'hero', 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1600&q=85') }}"
            @if (media_srcset(setting('default_background'), ['card', 'large', 'hero'])) srcset="{{ media_srcset(setting('default_background'), ['card', 'large', 'hero']) }}" @endif
            alt="{{ __('site.auto.auto_d254bebfc8ee') }}"
            class="absolute inset-0 -z-20 h-full w-full object-cover"
            fetchpriority="high"
            sizes="100vw"
        >
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-[#3d4226]/90 via-[#3d4226]/72 to-[#3d4226]/35"></div>
        <div class="mx-auto grid min-h-[72vh] max-w-7xl gap-10 px-4 py-20 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:items-center lg:px-8">
            <div>
                <p class="eyebrow text-amber-100">{{ __('site.auto.auto_e9bd16337dcf') }}</p>
                <h1 class="mt-5 max-w-3xl text-4xl font-semibold leading-tight sm:text-5xl lg:text-6xl">
                    {{ __('site.auto.auto_c18a64dcc6ec') }}
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-[#f5f0e0]/90">
                    {{ __('site.auto.auto_a41d6fe41740') }}
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ localized_route('reservations.create') }}" class="btn-primary" data-track-event="click_reservation_cta" data-track-category="reservation" data-track-label="Local SEO hero reservation" data-facebook-event="ClickReservation">{{ __('site.auto.auto_64a2210d034c') }}</a>
                    <a href="{{ localized_route('menu.index') }}" class="btn-secondary">{{ __('site.auto.auto_c5f9d6d26af7') }}</a>
                    <a href="{{ localized_route('local.vegetarian-catering-hai-phong') }}" class="btn-ghost" data-track-event="click_catering_cta" data-track-category="catering" data-track-label="Local SEO hero catering" data-facebook-event="ClickCatering">{{ __('site.auto.auto_637bb8414ef9') }}</a>
                    <a href="tel:{{ preg_replace('/\D+/', '', setting('hotline', '0947361515')) }}" class="btn-ghost" data-track-event="click_call" data-track-category="contact" data-track-label="Local SEO hero call" data-facebook-event="ClickCall">{{ __('site.auto.auto_5cab6ab48928') }}</a>
                </div>
            </div>

            <div class="rounded-[2rem] border border-white/15 bg-white/12 p-5 shadow-2xl backdrop-blur">
                <dl class="grid gap-4 text-sm text-[#f5f0e0] sm:grid-cols-2">
                    <div class="rounded-2xl bg-white/12 p-4">
                        <dt class="font-semibold text-amber-100">{{ __('site.auto.auto_1fdae2306b86') }}</dt>
                        <dd class="mt-2 leading-6">{{ setting('address') }}</dd>
                    </div>
                    <div class="rounded-2xl bg-white/12 p-4">
                        <dt class="font-semibold text-amber-100">{{ __('site.auto.auto_bf551503266a') }}</dt>
                        <dd class="mt-2 leading-6">{{ setting('hotline', setting('phone')) }}</dd>
                    </div>
                    <div class="rounded-2xl bg-white/12 p-4">
                        <dt class="font-semibold text-amber-100">{{ __('site.auto.auto_1a8ba12780ee') }}</dt>
                        <dd class="mt-2 leading-6">{{ setting('opening_hours') }}</dd>
                    </div>
                    <div class="rounded-2xl bg-white/12 p-4">
                        <dt class="font-semibold text-amber-100">{{ __('site.auto.auto_c954ff648890') }}</dt>
                        <dd class="mt-2 leading-6">{{ __('site.auto.auto_7169559081ec') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    <section class="section-block">
        <div class="grid gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:items-start">
            <div>
                <p class="eyebrow">{{ __('site.auto.auto_a7474110ddab') }}</p>
                <h2 class="mt-3 text-3xl font-semibold text-[#3d4226] sm:text-4xl">{{ __('site.auto.auto_9bc00e141651') }}</h2>
                <p class="mt-5 leading-8 text-stone-700">
                    {{ __('site.auto.auto_304cae3f82ac') }}
                </p>
                <p class="mt-4 leading-8 text-stone-700">
                    {{ __('site.auto.auto_24500c52e98f') }}
                </p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <article class="rounded-3xl border border-[#667132]/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-[#3d4226]">{{ __('site.auto.auto_6c39c70fe0aa') }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ __('site.auto.auto_efaebf7c80dc') }}</p>
                </article>
                <article class="rounded-3xl border border-[#667132]/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-[#3d4226]">{{ __('site.auto.auto_84f701e455d6') }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ __('site.auto.auto_cd9b67558979') }}</p>
                </article>
                <article class="rounded-3xl border border-[#667132]/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-[#3d4226]">{{ __('site.auto.auto_6284d262d997') }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ __('site.auto.auto_70d9ffc75a4c') }}</p>
                </article>
                <article class="rounded-3xl border border-[#667132]/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-[#3d4226]">{{ __('site.auto.auto_3eec347b6474') }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ __('site.auto.auto_a7e68e3d0676') }}</p>
                </article>
            </div>
        </div>
    </section>

    @if ($featuredDishes->isNotEmpty())
        <section class="section-block pt-0">
            <div class="section-heading">
                <p class="eyebrow">{{ __('site.auto.auto_3c82842d3188') }}</p>
                <h2>{{ __('site.auto.auto_307a9e8814a7') }}</h2>
                <p>{{ __('site.auto.auto_b975c903cdd2') }}</p>
            </div>
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredDishes as $dish)
                    @include('partials.dish-card', ['dish' => $dish])
                @endforeach
            </div>
        </section>
    @endif

    <section class="bg-[#3d4226] text-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-16 sm:px-6 lg:grid-cols-[0.85fr_1.15fr] lg:items-center lg:px-8">
            <div>
                <p class="eyebrow text-amber-100">{{ __('site.auto.auto_ddb9ba3ddf0e') }}</p>
                <h2 class="mt-3 text-3xl font-semibold sm:text-4xl">{{ __('site.auto.auto_6f0c2196072c') }}</h2>
                <p class="mt-5 leading-8 text-[#f5f0e0]/80">
                    {{ __('site.auto.auto_c36bdd09c4d4') }}
                </p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ localized_route('reservations.create') }}" class="btn-primary" data-track-event="click_reservation_cta" data-track-category="reservation" data-track-label="Local SEO middle reservation" data-facebook-event="ClickReservation">{{ __('site.auto.auto_ca83f60df53d') }}</a>
                    <a href="{{ localized_route('contact') }}" class="btn-ghost">{{ __('site.auto.auto_4ea492a6054d') }}</a>
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
                <h2 class="mt-3 text-3xl font-semibold text-[#3d4226] sm:text-4xl">{{ __('site.auto.auto_1ff8d206fa06') }}</h2>
                <p class="mt-5 leading-8 text-stone-700">
                    {{ __('site.auto.auto_b5f31f815888') }}
                </p>
            </div>
            <div class="space-y-4">
                @foreach ($faqs as $faq)
                    <details class="group rounded-3xl border border-[#667132]/10 bg-white p-6 shadow-sm" @if ($loop->first) open @endif>
                        <summary class="cursor-pointer list-none text-lg font-semibold text-[#3d4226]">
                            {{ $faq['question'] }}
                        </summary>
                        <p class="mt-4 leading-8 text-stone-700">{{ $faq['answer'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    @if ($latestPosts->isNotEmpty())
        <section class="section-block pt-0">
            <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                <div class="max-w-2xl">
                    <p class="eyebrow">{{ __('site.auto.auto_71fad52aab3b') }}</p>
                    <h2 class="mt-3 text-3xl font-semibold text-[#3d4226] sm:text-4xl">{{ __('site.auto.auto_4016df57a763') }}</h2>
                </div>
                <a href="{{ localized_route('blog.index') }}" class="inline-flex font-semibold text-[#667132] hover:text-[#3d4226]">{{ __('site.auto.auto_200e3ca40fa7') }}</a>
            </div>
            <div class="mt-10 grid gap-6 md:grid-cols-3">
                @foreach ($latestPosts as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            </div>
        </section>
    @endif
@endsection
