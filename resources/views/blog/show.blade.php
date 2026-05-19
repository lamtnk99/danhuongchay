@extends('layouts.app')

@section('content')
    @include('partials.breadcrumb', ['items' => $breadcrumbs])

    <article class="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
        <div class="text-center">
            <p class="eyebrow">{{ $post->category->name }}</p>
            <h1 class="mt-4 text-4xl font-semibold leading-tight text-emerald-950 sm:text-5xl">{{ $post->title }}</h1>
            <time class="mt-5 block text-sm font-medium text-stone-500" datetime="{{ $post->published_at->toDateString() }}">
                {{ $post->published_at->format('d/m/Y') }}
            </time>
        </div>

        <img
            src="{{ media_url($post->thumbnail) }}"
            alt="{{ $post->title }}"
            class="mt-10 aspect-[16/9] w-full rounded-3xl object-cover shadow-xl"
            fetchpriority="high"
        >

        <div class="article-content mt-10">
            {!! $post->content !!}
        </div>

        <aside class="mt-10 rounded-3xl border border-emerald-900/10 bg-emerald-50/70 p-6">
            <p class="font-semibold text-emerald-950">Đang tìm quán chay Hải Phòng?</p>
            <p class="mt-2 leading-7 text-stone-700">
                Xem trang tổng hợp của Đàn Hương Chay để biết địa chỉ, món nổi bật, giờ mở cửa, đặt bàn, tiệc chay và mâm cúng chay.
            </p>
            <div class="mt-5 flex flex-wrap gap-3">
                <a href="{{ route('local.vegetarian-restaurant-hai-phong') }}" class="btn-primary">Xem quán chay Hải Phòng</a>
                <a href="{{ route('menu.index') }}" class="btn-secondary">Xem thực đơn</a>
            </div>
        </aside>
    </article>

    @if ($relatedPosts->isNotEmpty())
        <section class="section-block pt-0">
            <div class="section-heading">
                <p class="eyebrow">Bài viết liên quan</p>
                <h2>Đọc thêm</h2>
            </div>
            <div class="mt-10 grid gap-6 md:grid-cols-3">
                @foreach ($relatedPosts as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            </div>
        </section>
    @endif
@endsection
