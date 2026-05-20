@extends('layouts.app')

@section('content')
    <section class="subpage-hero">
        <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
            <p class="eyebrow">{{ __('site.about.eyebrow') }}</p>
            <h1 class="mt-4 text-4xl font-semibold text-emerald-950 sm:text-5xl">{{ $page?->localized('title') ?: __('site.about.fallback_title') }}</h1>
            <p class="mt-6 text-lg leading-8 text-stone-700">{{ $page?->localized('meta_description') ?: __('site.about.fallback_description') }}</p>
        </div>
    </section>

    <section class="section-block">
        <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
            <div>
                <img
                    src="{{ media_variant_url($page?->image, 'large', 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1200&q=80') }}"
                    @if (media_srcset($page?->image, ['card', 'large', 'hero'])) srcset="{{ media_srcset($page?->image, ['card', 'large', 'hero']) }}" @endif
                    alt="{{ __('site.about.image_alt') }}"
                    class="rounded-3xl object-cover shadow-2xl"
                    loading="lazy"
                    sizes="(max-width: 1024px) 100vw, 50vw"
                >
            </div>
            <div>
                <p class="eyebrow">{{ __('site.about.philosophy_eyebrow') }}</p>
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">{{ __('site.about.philosophy_title') }}</h2>
                @if ($page)
                    <div class="article-content mt-5">{!! $page->localized('content') !!}</div>
                @else
                    <p class="mt-5 leading-8 text-stone-700">{{ __('site.about.fallback_p1') }}</p>
                    <p class="mt-4 leading-8 text-stone-700">{{ __('site.about.fallback_p2') }}</p>
                @endif
            </div>
        </div>
    </section>

    <section class="commitment-section-visual">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-3">
                <div class="commitment-card has-card-bg" style="--card-bg: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=900&q=80')">
                    <h2>{{ __('site.about.commitment_space_title') }}</h2>
                    <p>{{ __('site.about.commitment_space_text') }}</p>
                </div>
                <div class="commitment-card has-card-bg" style="--card-bg: url('https://images.unsplash.com/photo-1506806732259-39c2d0268443?auto=format&fit=crop&w=900&q=80')">
                    <h2>{{ __('site.about.commitment_source_title') }}</h2>
                    <p>{{ __('site.about.commitment_source_text') }}</p>
                </div>
                <div class="commitment-card has-card-bg" style="--card-bg: url('https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=900&q=80')">
                    <h2>{{ __('site.about.commitment_healthy_title') }}</h2>
                    <p>{{ __('site.about.commitment_healthy_text') }}</p>
                </div>
            </div>
        </div>
    </section>

    @if ($galleryImages->isNotEmpty())
        <section class="section-block">
            <div class="section-heading">
                <p class="eyebrow">{{ __('site.about.gallery_eyebrow') }}</p>
                <h2>{{ __('site.about.gallery_title') }}</h2>
            </div>
            <div class="about-gallery-strip mt-10">
                @foreach ($galleryImages as $image)
                    <a href="{{ localized_route('gallery.index') }}" class="about-gallery-card">
                        <img src="{{ media_variant_url($image->image, 'card') }}" alt="{{ $image->localized('alt_text', $image->localized('title')) }}" loading="lazy">
                        <span>{{ $image->localized('title') }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="about-map-section">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[0.8fr_1.2fr] lg:items-center">
                <div>
                    <p class="eyebrow">{{ __('site.about.address_eyebrow') }}</p>
                    <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">{{ __('site.about.address_title') }}</h2>
                    <p class="mt-5 leading-8 text-stone-700">{{ localized_setting('address', 'Villa 01-B4 Hoàng Mậu - Gia Viên, TP. Hải Phòng') }}</p>
                    <p class="mt-2 leading-8 text-stone-700">{{ __('site.about.opening_hours') }}: {{ localized_setting('opening_hours', '09:00 - 21:30') }}</p>
                    <a href="{{ localized_route('reservations.create') }}" class="btn-primary mt-6">{{ __('site.nav.reservation') }}</a>
                </div>
                <div class="map-frame">
                    @if (setting('google_map_iframe'))
                        {!! setting('google_map_iframe') !!}
                    @else
                        <iframe title="{{ __('site.about.map_title') }}" src="https://www.google.com/maps?q=Hai%20Phong&output=embed" class="h-80 w-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
