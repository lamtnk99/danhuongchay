@extends('layouts.app')

@section('content')
    <section class="subpage-hero gallery-hero">
        <div class="mx-auto max-w-5xl px-4 py-16 text-center sm:px-6 lg:px-8">
            <p class="eyebrow">{{ __('site.gallery.eyebrow') }}</p>
            <h1 class="mt-4 text-4xl font-semibold text-[#3d4226] sm:text-5xl">{{ __('site.gallery.title') }}</h1>
            <p class="mx-auto mt-6 max-w-3xl text-lg leading-8 text-stone-700">{{ __('site.gallery.description') }}</p>
        </div>
    </section>

    <section class="section-block space-y-14">
        @forelse ($branches as $branch)
            <section class="rounded-[2rem] bg-stone-50 p-5 ring-1 ring-[#667132]/10 sm:p-8">
                <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
                    <div class="max-w-3xl">
                        <p class="eyebrow">{{ $branch->city ?: __('site.gallery.branch') }}</p>
                        <h2 class="mt-3 text-3xl font-semibold text-[#3d4226] sm:text-4xl">{{ $branch->name }}</h2>
                        @if ($branch->description || $branch->address)
                            <p class="mt-4 leading-8 text-stone-700">{{ $branch->description ?: $branch->address }}</p>
                        @endif
                    </div>
                    <a href="{{ localized_route('reservations.create', ['branch' => $branch->slug]) }}" class="btn-primary self-start md:self-auto">{{ __('site.gallery.book_branch') }}</a>
                </div>

                @if ($branch->galleryImages->isNotEmpty())
                    <div class="gallery-masonry mt-8">
                        @foreach ($branch->galleryImages as $image)
                            <article class="gallery-tile">
                                <img
                                    src="{{ media_variant_url($image->image, 'card') }}"
                                    @if (media_srcset($image->image, ['thumb', 'card', 'large'])) srcset="{{ media_srcset($image->image, ['thumb', 'card', 'large']) }}" @endif
                                    alt="{{ $image->localized('alt_text', $image->localized('title')) }}"
                                    loading="lazy"
                                    sizes="(max-width: 768px) 100vw, 33vw"
                                >
                                <div>
                                    <h3>{{ $image->localized('title') }}</h3>
                                    @if ($image->localized('description'))
                                        <p>{{ $image->localized('description') }}</p>
                                    @endif
                                </div>
                            </article>
                        @endforeach
                    </div>
                @else
                    <p class="mt-8 rounded-2xl bg-white p-5 text-stone-600">{{ __('site.gallery.branch_photos_updating') }}</p>
                @endif
            </section>
        @empty
            <p class="text-center text-stone-600">{{ __('site.gallery.empty_branches') }}</p>
        @endforelse

        @if ($sharedImages->isNotEmpty())
            <section>
                <div class="mb-8">
                    <p class="eyebrow">{{ __('site.gallery.shared_moments') }}</p>
                    <h2 class="mt-3 text-3xl font-semibold text-[#3d4226]">{{ __('site.gallery.details_title') }}</h2>
                </div>
                <div class="gallery-masonry">
                    @foreach ($sharedImages as $image)
                        <article class="gallery-tile">
                            <img
                                src="{{ media_variant_url($image->image, 'card') }}"
                                @if (media_srcset($image->image, ['thumb', 'card', 'large'])) srcset="{{ media_srcset($image->image, ['thumb', 'card', 'large']) }}" @endif
                                alt="{{ $image->localized('alt_text', $image->localized('title')) }}"
                                loading="lazy"
                                sizes="(max-width: 768px) 100vw, 33vw"
                            >
                            <div>
                                <h3>{{ $image->localized('title') }}</h3>
                                @if ($image->localized('description'))
                                    <p>{{ $image->localized('description') }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </section>
@endsection
