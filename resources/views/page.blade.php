@extends('layouts.app')

@section('content')
    <section class="subpage-hero">
        <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
            <p class="eyebrow">{{ __('site.page.eyebrow') }}</p>
            <h1 class="mt-4 text-4xl font-semibold text-emerald-950 sm:text-5xl">{{ $page->localized('title') }}</h1>
            @if ($page->localized('meta_description'))
                <p class="mt-6 text-lg leading-8 text-stone-700">{{ $page->localized('meta_description') }}</p>
            @endif
        </div>
    </section>

    <article class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        @if ($page->image)
            <img
                src="{{ media_variant_url($page->image, 'large') }}"
                @if (media_srcset($page->image, ['card', 'large', 'hero'])) srcset="{{ media_srcset($page->image, ['card', 'large', 'hero']) }}" @endif
                alt="{{ $page->localized('title') }}"
                class="mb-10 aspect-[16/8] w-full rounded-3xl object-cover shadow-xl"
                sizes="(max-width: 1024px) 100vw, 960px"
            >
        @endif

        <div class="article-content">
            {!! $page->localized('content') !!}
        </div>
    </article>
@endsection
