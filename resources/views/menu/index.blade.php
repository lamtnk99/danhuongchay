@extends('layouts.app')

@section('content')
    <section class="subpage-hero">
        <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
            <p class="eyebrow">{{ __('site.menu.eyebrow') }}</p>
            <h1 class="mt-4 text-4xl font-semibold text-emerald-950 sm:text-5xl">{{ __('site.menu.title') }}</h1>
            <p class="mt-6 text-lg leading-8 text-stone-700">{{ __('site.menu.description') }}</p>
        </div>
    </section>

    <section class="section-block">
        <form action="{{ localized_route('menu.index') }}" method="GET" class="menu-search-form mb-8 grid gap-4 rounded-2xl border border-emerald-900/10 bg-white p-4 shadow-sm md:grid-cols-[1fr_auto]">
            <label class="sr-only" for="q">{{ __('site.menu.search') }}</label>
            <input
                id="q"
                type="search"
                name="q"
                value="{{ $search }}"
                placeholder="{{ __('site.menu.search_placeholder') }}"
                class="form-input"
                inputmode="search"
                autocomplete="off"
            >
            @if ($selectedCategory)
                <input type="hidden" name="category" value="{{ $selectedCategory }}">
            @endif
            <button type="submit" class="btn-primary justify-center">{{ __('site.menu.search') }}</button>
        </form>

        <div class="category-scroll mb-8" aria-label="{{ __('site.menu.eyebrow') }}">
            <a
                href="{{ localized_route('menu.index', request()->filled('q') ? ['q' => $search] : []) }}"
                @class(['filter-chip', 'is-active' => blank($selectedCategory)])
            >
                {{ __('site.menu.all') }}
            </a>
            @foreach ($categories as $category)
                @php $categorySlug = $category->localizedSlug(); @endphp
                <a
                    href="{{ localized_route('menu.index', array_filter(['category' => $categorySlug, 'q' => $search])) }}"
                    @class(['filter-chip', 'is-active' => $selectedCategory === $categorySlug || $selectedCategory === $category->slug])
                >
                    {{ $category->localized('name') }}
                </a>
            @endforeach
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($dishes as $dish)
                @include('partials.dish-card', ['dish' => $dish])
            @empty
                <div class="rounded-2xl border border-emerald-900/10 bg-white p-8 text-center text-stone-600 sm:col-span-2 lg:col-span-3">
                    {{ __('site.menu.empty') }}
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $dishes->links() }}
        </div>
    </section>
@endsection
