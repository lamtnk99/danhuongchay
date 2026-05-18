@extends('layouts.app')

@section('content')
    <section class="subpage-hero gallery-hero">
        <div class="mx-auto max-w-5xl px-4 py-16 text-center sm:px-6 lg:px-8">
            <p class="eyebrow">Không gian</p>
            <h1 class="mt-4 text-4xl font-semibold text-emerald-950 sm:text-5xl">Những góc yên tại Đàn Hương Chay</h1>
            <p class="mx-auto mt-6 max-w-3xl text-lg leading-8 text-stone-700">
                Một vài lát cắt về không gian quán: bàn gỗ, ánh sáng dịu, cây xanh và những góc ngồi đủ riêng tư cho bữa chay chậm rãi.
            </p>
        </div>
    </section>

    <section class="section-block">
        <div class="gallery-masonry">
            @forelse ($galleryImages as $image)
                <article class="gallery-tile">
                    <img src="{{ media_url($image->image) }}" alt="{{ $image->alt_text ?: $image->title }}" loading="lazy">
                    <div>
                        <h2>{{ $image->title }}</h2>
                        @if ($image->description)
                            <p>{{ $image->description }}</p>
                        @endif
                    </div>
                </article>
            @empty
                <p class="text-center text-stone-600">Chưa có ảnh không gian hiển thị.</p>
            @endforelse
        </div>

        <div class="mt-8">{{ $galleryImages->links() }}</div>
    </section>
@endsection
