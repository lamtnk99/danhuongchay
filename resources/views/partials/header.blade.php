@php
    $navItems = \App\Models\NavigationMenu::active()
        ->location('header')
        ->whereNull('parent_id')
        ->with('children')
        ->orderBy('sort_order')
        ->get();

    if ($navItems->isEmpty()) {
        $navItems = collect([
            (object) ['title' => 'Trang chủ', 'url' => route('home'), 'open_new_tab' => false],
            (object) ['title' => 'Giới thiệu', 'url' => route('about'), 'open_new_tab' => false],
            (object) ['title' => 'Thực đơn', 'url' => route('menu.index'), 'open_new_tab' => false],
            (object) ['title' => 'Blog', 'url' => route('blog.index'), 'open_new_tab' => false],
            (object) ['title' => 'Đặt tiệc', 'url' => route('local.vegetarian-catering-hai-phong'), 'open_new_tab' => false],
            (object) ['title' => 'Đặt bàn', 'url' => route('reservations.create'), 'open_new_tab' => false],
            (object) ['title' => 'Liên hệ', 'url' => route('contact'), 'open_new_tab' => false],
        ]);
    }
@endphp

<header class="site-header sticky top-0 z-50 border-b border-emerald-900/10 bg-[#fbfaf5]/92 shadow-sm backdrop-blur">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8" aria-label="Điều hướng chính">
        <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="Đàn Hương Chay">
            @if (setting('logo_header'))
                <img src="{{ media_url(setting('logo_header')) }}" alt="{{ setting('restaurant_name', 'Đàn Hương Chay') }}" class="h-11 w-11 rounded-full object-contain bg-white shadow-sm">
            @else
                <span class="grid h-11 w-11 place-items-center rounded-full bg-emerald-800 text-lg font-semibold text-white shadow-sm">ĐH</span>
            @endif
            <span>
                <span class="block text-lg font-semibold tracking-wide text-emerald-950">{{ setting('restaurant_name', 'Đàn Hương Chay') }}</span>
                <span class="block text-xs uppercase tracking-[0.24em] text-amber-800">Vegetarian Kitchen</span>
            </span>
        </a>

        <div class="hidden items-center gap-1 lg:flex">
            @foreach ($navItems as $item)
                @php
                    $href = str_starts_with($item->url, 'http') ? $item->url : url($item->url);
                    $isActive = url()->current() === rtrim($href, '/');
                @endphp
                <a
                    href="{{ $href }}"
                    @if ($item->open_new_tab) target="_blank" rel="noopener" @endif
                    @class([
                        'rounded-full px-4 py-2 text-sm font-medium transition',
                        'bg-emerald-900 text-white shadow-sm' => $isActive,
                        'text-stone-700 hover:bg-emerald-50 hover:text-emerald-900' => !$isActive,
                    ])
                >
                    {{ $item->title }}
                </a>
            @endforeach
        </div>

        <button
            type="button"
            class="mobile-nav-toggle inline-flex h-11 w-11 items-center justify-center rounded-full border border-emerald-900/20 bg-white text-emerald-950 shadow-sm lg:hidden"
            aria-controls="mobile-nav"
            aria-expanded="false"
            aria-label="Mở menu"
        >
            <span class="sr-only">Mở menu</span>
            <span class="hamburger" aria-hidden="true"></span>
        </button>
    </nav>

    <div id="mobile-nav" class="mobile-nav-panel border-t border-emerald-900/10 bg-[#fbfaf5] px-4 pb-4 lg:hidden" data-mobile-nav hidden>
        <div class="mx-auto grid max-w-7xl gap-2 pt-3">
            @foreach ($navItems as $item)
                @php
                    $href = str_starts_with($item->url, 'http') ? $item->url : url($item->url);
                    $isActive = url()->current() === rtrim($href, '/');
                @endphp
                <a
                    href="{{ $href }}"
                    @if ($item->open_new_tab) target="_blank" rel="noopener" @endif
                    @class([
                        'mobile-nav-link rounded-xl px-4 py-3 text-sm font-medium',
                        'bg-emerald-900 text-white' => $isActive,
                        'bg-white text-stone-700 shadow-sm' => !$isActive,
                    ])
                >
                    {{ $item->title }}
                </a>
            @endforeach
        </div>
    </div>
</header>
