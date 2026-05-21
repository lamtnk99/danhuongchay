@extends('layouts.app')

@section('content')
    <section class="subpage-hero blog-hero">
        <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
            <p class="eyebrow">{{ __('site.nav.blog') }}</p>
            <h1 class="mt-4 text-4xl font-semibold text-emerald-950 sm:text-5xl">{{ __('site.blog.title') }}</h1>
            <p class="mt-6 text-lg leading-8 text-stone-700">{{ __('site.blog.description') }}</p>
        </div>
    </section>

    <section class="section-block">
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($posts as $post)
                @include('partials.post-card', ['post' => $post])
            @endforeach
        </div>

        <div class="mt-10">
            {{ $posts->links() }}
        </div>
    </section>
@endsection
