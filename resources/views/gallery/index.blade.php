@extends('layouts.app')

@section('content')
    <section class="subpage-hero gallery-hero">
        <div class="mx-auto max-w-5xl px-4 py-16 text-center sm:px-6 lg:px-8">
            <p class="eyebrow">{{ __('site.gallery.eyebrow') }}</p>
            <h1 class="mt-4 text-4xl font-semibold text-emerald-950 sm:text-5xl">{{ __('site.gallery.title') }}</h1>
            <p class="mx-auto mt-6 max-w-3xl text-lg leading-8 text-stone-700">{{ __('site.gallery.description') }}</p>
        </div>
    </section>

    <section class="section-block">
        <div class="gallery-masonry">
            @forelse ($galleryImages as $image)
                <article class="gallery-tile">
                    <img src="{{ media_url($image->image) }}" alt="{{ $image->localized('alt_text', $image->localized('title')) }}" loading="lazy">
                    <div>
                        <h2>{{ $image->localized('title') }}</h2>
                        @if ($image->localized('description'))
                            <p>{{ $image->localized('description') }}</p>
                        @endif
                    </div>
                </article>
            @empty
                <p class="text-center text-stone-600">{{ is_english() ? 'No space photos are currently displayed.' : 'Chưa có ảnh không gian hiển thị.' }}</p>
            @endforelse
        </div>

        <div class="mt-8">{{ $galleryImages->links() }}</div>
    </section>
@endsection
