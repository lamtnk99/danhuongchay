@extends('layouts.app')

@section('content')
    <section class="subpage-hero">
        <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
            <p class="eyebrow">Thực đơn</p>
            <h1 class="mt-4 text-4xl font-semibold text-emerald-950 sm:text-5xl">Món chay thanh lành cho mọi bữa ăn</h1>
            <p class="mt-6 text-lg leading-8 text-stone-700">
                Tìm món theo danh mục hoặc tên món. Mỗi món đều có mô tả, giá, nguyên liệu và trang SEO riêng.
            </p>
        </div>
    </section>

    <section class="section-block">
        <form action="{{ route('menu.index') }}" method="GET" class="menu-search-form mb-8 grid gap-4 rounded-2xl border border-emerald-900/10 bg-white p-4 shadow-sm md:grid-cols-[1fr_auto]">
            <label class="sr-only" for="q">Tìm món ăn</label>
            <input
                id="q"
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="Tìm món chay, nguyên liệu..."
                class="form-input"
                inputmode="search"
                autocomplete="off"
            >
            @if ($selectedCategory)
                <input type="hidden" name="category" value="{{ $selectedCategory }}">
            @endif
            <button type="submit" class="btn-primary justify-center">Tìm kiếm</button>
        </form>

        <div class="category-scroll mb-8" aria-label="Lọc món ăn theo danh mục">
            <a
                href="{{ route('menu.index', request()->filled('q') ? ['q' => $search] : []) }}"
                @class(['filter-chip', 'is-active' => blank($selectedCategory)])
            >
                Tất cả
            </a>
            @foreach ($categories as $category)
                <a
                    href="{{ route('menu.index', array_filter(['category' => $category->slug, 'q' => $search])) }}"
                    @class(['filter-chip', 'is-active' => $selectedCategory === $category->slug])
                >
                    {{ $category->name }}
                </a>
            @endforeach
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($dishes as $dish)
                @include('partials.dish-card', ['dish' => $dish])
            @empty
                <div class="rounded-2xl border border-emerald-900/10 bg-white p-8 text-center text-stone-600 sm:col-span-2 lg:col-span-3">
                    Không tìm thấy món phù hợp. Hãy thử từ khóa hoặc danh mục khác.
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $dishes->links() }}
        </div>
    </section>
@endsection
