@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['items' => $breadcrumbs])

    <article class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="text-center">
            <p class="eyebrow">{{ $post->category->localized('name') }}</p>
            <h1 class="mt-4 text-4xl font-semibold leading-tight text-[#3d4226] sm:text-5xl">{{ $post->localized('title') }}</h1>
            <div class="mt-5 flex flex-wrap items-center justify-center gap-x-3 gap-y-1 text-sm font-medium text-stone-500">
                <span>{{ __('site.blog.by') }} {{ localized_setting('restaurant_name', __('site.email.customer_reservation.brand')) }}</span>
                <span aria-hidden="true">·</span>
                <time datetime="{{ $post->published_at->toAtomString() }}">{{ $post->published_at->format('d/m/Y') }}</time>
            </div>
        </div>

        <img
            src="{{ media_variant_url($post->thumbnail, 'large') }}"
            @if (media_srcset($post->thumbnail, ['card', 'large', 'hero'])) srcset="{{ media_srcset($post->thumbnail, ['card', 'large', 'hero']) }}" @endif
            alt="{{ $post->localized('title') }}"
            class="mt-10 aspect-[16/9] w-full rounded-3xl object-cover shadow-xl"
            fetchpriority="high"
            sizes="(max-width: 1024px) 100vw, 960px"
        >

        <div class="article-content mt-10">
            {!! $post->localized('content') !!}
        </div>

        @if ($previousPost || $nextPost)
            <nav class="mt-12 grid gap-4 border-t border-[#667132]/15 pt-8 sm:grid-cols-2" aria-label="{{ __('site.blog.related_posts') }}">
                @if ($previousPost)
                    <a href="{{ localized_route('blog.show', ['slug' => $previousPost->localizedSlug()]) }}" rel="prev" class="rounded-2xl border border-[#667132]/15 bg-white p-5 transition hover:border-[#667132]/40 hover:shadow-md">
                        <span class="text-xs font-bold uppercase tracking-[0.18em] text-stone-500">{{ __('site.blog.previous_post') }}</span>
                        <span class="mt-2 block font-semibold leading-snug text-[#3d4226]">{{ $previousPost->localized('title') }}</span>
                    </a>
                @endif
                @if ($nextPost)
                    <a href="{{ localized_route('blog.show', ['slug' => $nextPost->localizedSlug()]) }}" rel="next" @class([
                        'rounded-2xl border border-[#667132]/15 bg-white p-5 transition hover:border-[#667132]/40 hover:shadow-md',
                        'text-right sm:col-start-2' => ! $previousPost,
                        'text-right' => $previousPost,
                    ])>
                        <span class="text-xs font-bold uppercase tracking-[0.18em] text-stone-500">{{ __('site.blog.next_post') }}</span>
                        <span class="mt-2 block font-semibold leading-snug text-[#3d4226]">{{ $nextPost->localized('title') }}</span>
                    </a>
                @endif
            </nav>
        @endif
    </article>

    @if ($relatedPosts->isNotEmpty())
        <section class="section-block pt-0">
            <div class="section-heading">
                <p class="eyebrow">{{ __('site.blog.related_posts') }}</p>
                <h2>{{ __('site.blog.read_more') }}</h2>
                <p>{{ __('site.blog.related_description') }}</p>
            </div>
            <div class="mt-10 grid gap-6 md:grid-cols-3">
                @foreach ($relatedPosts as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            </div>
        </section>
    @endif
@endsection
