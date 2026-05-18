@extends('layouts.app')

@section('content')
    <section class="subpage-hero">
        <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
            <p class="eyebrow">Trang thông tin</p>
            <h1 class="mt-4 text-4xl font-semibold text-emerald-950 sm:text-5xl">{{ $page->title }}</h1>
            @if ($page->meta_description)
                <p class="mt-6 text-lg leading-8 text-stone-700">{{ $page->meta_description }}</p>
            @endif
        </div>
    </section>

    <article class="mx-auto max-w-4xl px-4 py-12 sm:px-6 lg:px-8">
        @if ($page->image)
            <img src="{{ media_url($page->image) }}" alt="{{ $page->title }}" class="mb-10 aspect-[16/8] w-full rounded-3xl object-cover shadow-xl">
        @endif

        <div class="article-content">
            {!! $page->content !!}
        </div>
    </article>
@endsection
