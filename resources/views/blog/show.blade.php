@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['items' => $breadcrumbs])

    <article class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="text-center">
            <p class="eyebrow">{{ $post->category->localized('name') }}</p>
            <h1 class="mt-4 text-4xl font-semibold leading-tight text-emerald-950 sm:text-5xl">{{ $post->localized('title') }}</h1>
            <time class="mt-5 block text-sm font-medium text-stone-500" datetime="{{ $post->published_at->toDateString() }}">
                {{ $post->published_at->format('d/m/Y') }}
            </time>
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
    </article>

    @if ($relatedPosts->isNotEmpty())
        <section class="section-block pt-0">
            <div class="section-heading">
                <p class="eyebrow">{{ is_english() ? 'Related posts' : 'Bài viết liên quan' }}</p>
                <h2>{{ is_english() ? 'Read more' : 'Đọc thêm' }}</h2>
            </div>
            <div class="mt-10 grid gap-6 md:grid-cols-3">
                @foreach ($relatedPosts as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            </div>
        </section>
    @endif
@endsection
