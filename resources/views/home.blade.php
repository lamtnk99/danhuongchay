@extends('layouts.app')

@section('content')
    @php
        $hero = $banners->first();
    @endphp

    <section class="hero-section relative isolate overflow-hidden">
        <img
            src="{{ media_url($hero?->image, media_url(setting('default_background'), 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1600&q=85')) }}"
            alt="{{ $hero?->localized('title') ?: localized_setting('restaurant_name', 'Đàn Hương Chay') }}"
            class="absolute inset-0 -z-20 h-full w-full object-cover"
            fetchpriority="high"
        >
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-emerald-950/82 via-emerald-950/58 to-amber-950/18"></div>
        <div class="mx-auto flex min-h-[74vh] max-w-7xl items-center px-4 py-20 sm:px-6 lg:px-8">
            <div class="max-w-3xl text-white">
                <p class="text-sm font-semibold uppercase tracking-[0.32em] text-amber-100">{{ $hero?->localized('subtitle') ?: localized_setting('restaurant_name', 'Đàn Hương Chay') }}</p>
                <h1 class="mt-5 max-w-2xl text-4xl font-semibold leading-tight sm:text-5xl lg:text-6xl">
                    {{ $hero?->localized('title') ?: localized_setting('slogan', __('site.home.reason_title')) }}
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-8 text-emerald-50/88">
                    {{ $hero?->localized('description') ?: localized_setting('short_description', __('site.home.reason_description')) }}
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ $hero?->button_link ?: localized_route('menu.index') }}" class="btn-primary">{{ $hero?->localized('button_text') ?: __('site.hero.menu') }}</a>
                    <a href="{{ localized_route('reservations.create') }}" class="btn-secondary">{{ __('site.hero.reservation') }}</a>
                    <a href="{{ localized_route('local.vegetarian-catering-hai-phong') }}" class="btn-ghost" data-track-event="click_catering_cta" data-track-label="Hero catering">{{ __('site.hero.catering') }}</a>
                </div>
            </div>
        </div>
    </section>

    @if ($homePromotions->isNotEmpty())
        <section class="promo-showcase">
            <div class="promo-shell">
                <div class="promo-head">
                    <div>
                        <p class="promo-kicker">{{ __('site.home.promo_kicker') }}</p>
                        <h2>{{ __('site.home.promo_title') }}</h2>
                        <p>{{ __('site.home.promo_description') }}</p>
                    </div>
                    <a href="{{ localized_route('menu.index') }}" class="promo-head-link">{{ __('site.hero.menu') }}</a>
                </div>

                <div @class([
                    'promo-grid',
                    'promo-grid-single' => $homePromotions->count() === 1,
                    'promo-grid-two' => $homePromotions->count() === 2,
                    'promo-grid-three' => $homePromotions->count() === 3,
                    'promo-grid-four' => $homePromotions->count() >= 4,
                ])>
                    @foreach ($homePromotions as $promotion)
                        <article @class(['promo-card', 'is-featured' => $loop->first && $homePromotions->count() > 2]) style="--promo-accent: {{ $promotion->accent_color ?: '#047857' }}">
                            <img src="{{ media_url($promotion->image, 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=900&q=85') }}" alt="{{ $promotion->localized('title') }}" loading="lazy">
                            <div class="promo-card-copy">
                                @if ($promotion->localized('badge'))
                                    <span class="promo-badge">{{ $promotion->localized('badge') }}</span>
                                @endif
                                @if ($promotion->localized('subtitle'))
                                    <p class="promo-subtitle">{{ $promotion->localized('subtitle') }}</p>
                                @endif
                                <h3>{{ $promotion->localized('title') }}</h3>
                                @if ($promotion->localized('description'))
                                    <p>{{ $promotion->localized('description') }}</p>
                                @endif
                                @if ($promotion->localized('button_text') && $promotion->button_link)
                                    <a href="{{ $promotion->button_link }}" class="promo-link">{{ $promotion->localized('button_text') }}</a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section-block">
        <div class="section-heading">
            <p class="eyebrow">{{ __('site.home.featured_eyebrow') }}</p>
            <h2>{{ __('site.home.featured_title') }}</h2>
            <p>{{ __('site.home.featured_description') }}</p>
        </div>
        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($featuredDishes as $dish)
                @include('partials.dish-card', ['dish' => $dish])
            @endforeach
        </div>
    </section>

    <section class="reason-section-visual">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
            <div>
                <p class="eyebrow">{{ __('site.home.reason_eyebrow') }}</p>
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">{{ __('site.home.reason_title') }}</h2>
                <p class="mt-5 leading-8 text-stone-700">{{ __('site.home.reason_description') }}</p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="reason-card has-card-bg" style="--card-bg: url('https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=900&q=80')">
                    <h3>{{ __('site.home.reason_clean_title') }}</h3>
                    <p>{{ __('site.home.reason_clean_text') }}</p>
                </div>
                <div class="reason-card has-card-bg" style="--card-bg: url('https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=900&q=80')">
                    <h3>{{ __('site.home.reason_flavor_title') }}</h3>
                    <p>{{ __('site.home.reason_flavor_text') }}</p>
                </div>
                <div class="reason-card has-card-bg" style="--card-bg: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=900&q=80')">
                    <h3>{{ __('site.home.reason_space_title') }}</h3>
                    <p>{{ __('site.home.reason_space_text') }}</p>
                </div>
                <div class="reason-card has-card-bg" style="--card-bg: url('https://images.unsplash.com/photo-1556745757-8d76bdb6984b?auto=format&fit=crop&w=900&q=80')">
                    <h3>{{ __('site.home.reason_booking_title') }}</h3>
                    <p>{{ __('site.home.reason_booking_text') }}</p>
                </div>
            </div>
        </div>
    </section>

    @if ($homeGalleryImages->isNotEmpty())
        <section class="gallery-preview-section">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                    <div class="max-w-2xl">
                        <p class="eyebrow">{{ __('site.home.gallery_eyebrow') }}</p>
                        <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">{{ __('site.home.gallery_title') }}</h2>
                        <p class="mt-4 leading-8 text-stone-700">{{ __('site.home.gallery_description') }}</p>
                    </div>
                    <a href="{{ localized_route('gallery.index') }}" class="inline-flex font-semibold text-emerald-800 hover:text-emerald-950">{{ __('site.home.gallery_link') }}</a>
                </div>

                <div class="home-gallery-grid mt-10">
                    @foreach ($homeGalleryImages as $image)
                        <article @class(['home-gallery-card', 'is-large' => $loop->first])>
                            <img src="{{ media_url($image->image) }}" alt="{{ $image->localized('alt_text', $image->localized('title')) }}" loading="lazy">
                            <div>
                                <h3>{{ $image->localized('title') }}</h3>
                                @if ($image->localized('description'))
                                    <p>{{ $image->localized('description') }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section-block">
        <div class="section-heading">
            <p class="eyebrow">{{ __('site.home.testimonials_eyebrow') }}</p>
            <h2>{{ __('site.home.testimonials_title') }}</h2>
        </div>
        @if ($testimonials->isNotEmpty())
            <div class="testimonial-slider mt-10" data-testimonial-slider>
                <div class="testimonial-viewport">
                    <div class="testimonial-track" data-testimonial-track>
                        @foreach ($testimonials as $testimonial)
                            <figure class="testimonial-card" data-testimonial-slide>
                                <div class="flex items-center gap-3">
                                    @if ($testimonial->avatar)
                                        <img src="{{ media_url($testimonial->avatar) }}" alt="{{ $testimonial->name }}" loading="lazy">
                                    @else
                                        <span class="testimonial-avatar">{{ mb_substr($testimonial->name, 0, 1) }}</span>
                                    @endif
                                    <figcaption>
                                        <strong>{{ $testimonial->name }}</strong>
                                        @if ($testimonial->localized('role'))
                                            <span>{{ $testimonial->localized('role') }}</span>
                                        @endif
                                    </figcaption>
                                </div>
                                <div class="testimonial-stars" aria-label="{{ $testimonial->rating }} / 5">
                                    @for ($star = 1; $star <= 5; $star++)
                                        <span @class(['is-muted' => $star > $testimonial->rating])>★</span>
                                    @endfor
                                </div>
                                <blockquote>“{{ $testimonial->localized('content') }}”</blockquote>
                            </figure>
                        @endforeach
                    </div>
                </div>

                <div class="testimonial-controls">
                    <button type="button" data-testimonial-prev aria-label="Previous review">‹</button>
                    <div class="testimonial-dots" data-testimonial-dots>
                        @foreach ($testimonials as $testimonial)
                            <button type="button" data-testimonial-dot data-index="{{ $loop->index }}" aria-label="Review {{ $loop->iteration }}" @class(['is-active' => $loop->first])></button>
                        @endforeach
                    </div>
                    <button type="button" data-testimonial-next aria-label="Next review">›</button>
                </div>
            </div>
        @else
            <p class="mt-8 text-center text-stone-600">{{ __('site.home.testimonials_empty') }}</p>
        @endif
    </section>

    <section class="section-block pt-0">
        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
            <div class="max-w-2xl">
                <p class="eyebrow">{{ __('site.home.blog_eyebrow') }}</p>
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">{{ __('site.home.blog_title') }}</h2>
            </div>
            <a href="{{ localized_route('blog.index') }}" class="inline-flex font-semibold text-emerald-800 hover:text-emerald-950">{{ __('site.home.blog_link') }}</a>
        </div>
        <div class="mt-10 grid gap-6 md:grid-cols-3">
            @foreach ($latestPosts as $post)
                @include('partials.post-card', ['post' => $post])
            @endforeach
        </div>
    </section>

    <section class="section-block pt-0">
        <div class="rounded-[2rem] border border-emerald-900/10 bg-white p-6 shadow-sm sm:p-8 lg:flex lg:items-center lg:justify-between lg:gap-10">
            <div class="max-w-3xl">
                <p class="eyebrow">{{ __('site.home.local_eyebrow') }}</p>
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">{{ __('site.home.local_title') }}</h2>
                <p class="mt-4 leading-8 text-stone-700">{{ __('site.home.local_text') }}</p>
            </div>
            <div class="mt-6 flex shrink-0 flex-wrap gap-3 lg:mt-0">
                <a href="{{ localized_route('local.vegetarian-restaurant-hai-phong') }}" class="btn-primary justify-center">{{ __('site.home.local_primary') }}</a>
                <a href="{{ localized_route('local.vegetarian-catering-hai-phong') }}" class="btn-secondary justify-center" data-track-event="click_catering_cta" data-track-category="catering" data-track-label="Home local SEO block" data-facebook-event="ClickCatering">{{ __('site.home.local_secondary') }}</a>
            </div>
        </div>
    </section>
@endsection
