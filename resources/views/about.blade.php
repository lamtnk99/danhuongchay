@extends('layouts.app')

@section('content')
    <section class="subpage-hero about-hero">
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
            <div class="about-branches-layout">
                <div>
                    <p class="eyebrow">{{ __('site.about.address_eyebrow') }}</p>
                    <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">{{ is_english() ? 'Two peaceful vegetarian spaces' : 'Hai điểm hẹn chay thanh lành' }}</h2>
                    <p class="mt-5 leading-8 text-stone-700">
                        {{ is_english() ? 'Each branch keeps a calm dining rhythm, warm service and a menu built for family meals, gatherings and mindful vegetarian moments.' : 'Mỗi cơ sở là một không gian riêng để ghé ăn nhẹ, dùng bữa cùng gia đình hoặc đặt trước những mâm chay chỉn chu cho dịp quan trọng.' }}
                    </p>

                    <div class="mt-7 grid gap-4">
                        @forelse ($branches as $branch)
                            <article class="about-branch-card">
                                <div>
                                    <p>{{ $branch->city ?: $branch->name }}</p>
                                    <h3>{{ $branch->name }}</h3>
                                    <span>{{ $branch->address }}</span>
                                </div>
                                @include('partials.branch-social-actions', ['branch' => $branch])
                            </article>
                        @empty
                            <article class="about-branch-card">
                                <div>
                                    <p>{{ is_english() ? 'Main branch' : 'Cơ sở chính' }}</p>
                                    <h3>{{ localized_setting('restaurant_name', 'Đàn Hương Chay') }}</h3>
                                    <span>{{ localized_setting('address', 'Villa 01-B4 Hoàng Mậu - Gia Viên, TP. Hải Phòng') }}</span>
                                </div>
                            </article>
                        @endforelse
                    </div>
                </div>

                <div class="about-map-feature" data-map-slider>
                    <div class="about-map-copy">
                        <p>{{ is_english() ? 'Map' : 'Bản đồ cơ sở' }}</p>
                        <h3 data-map-title>{{ $branches->first()?->name ?: localized_setting('restaurant_name', 'Đàn Hương Chay') }}</h3>
                        @if ($branches->count() > 1)
                            <div class="about-map-tabs">
                                @foreach ($branches as $branch)
                                    <button type="button" @class(['is-active' => $loop->first]) data-map-tab="{{ $loop->index }}">{{ $branch->city ?: $branch->name }}</button>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="about-map-slides">
                        @forelse ($branches as $branch)
                            <div @class(['about-map-slide', 'is-active' => $loop->first]) data-map-slide="{{ $loop->index }}" data-title="{{ $branch->name }}">
                                <div class="map-frame">
                                    @if ($branch->google_map_iframe)
                                        {!! $branch->google_map_iframe !!}
                                    @elseif (setting('google_map_iframe') && $loop->first)
                                        {!! setting('google_map_iframe') !!}
                                    @else
                                        <iframe title="{{ $branch->name }}" src="https://www.google.com/maps?q={{ urlencode($branch->address ?? $branch->name) }}&output=embed" class="h-80 w-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="about-map-slide is-active" data-map-slide="0" data-title="{{ localized_setting('restaurant_name', 'Đàn Hương Chay') }}">
                                <div class="map-frame">
                                    @if (setting('google_map_iframe'))
                                        {!! setting('google_map_iframe') !!}
                                    @else
                                        <iframe title="{{ __('site.about.map_title') }}" src="https://www.google.com/maps?q={{ urlencode(localized_setting('address', 'Hai Phong')) }}&output=embed" class="h-80 w-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                    @endif
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-map-slider]').forEach((slider) => {
                const tabs = [...slider.querySelectorAll('[data-map-tab]')];
                const slides = [...slider.querySelectorAll('[data-map-slide]')];
                const title = slider.querySelector('[data-map-title]');
                if (!tabs.length || slides.length < 2) return;

                let active = 0;
                const show = (index) => {
                    active = index;
                    tabs.forEach((tab, tabIndex) => tab.classList.toggle('is-active', tabIndex === index));
                    slides.forEach((slide, slideIndex) => slide.classList.toggle('is-active', slideIndex === index));
                    if (title) title.textContent = slides[index]?.dataset.title || title.textContent;
                };

                tabs.forEach((tab, index) => tab.addEventListener('click', () => show(index)));
                window.setInterval(() => show((active + 1) % slides.length), 6500);
            });
        });
    </script>
@endsection
