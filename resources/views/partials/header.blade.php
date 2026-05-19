@php
    if (is_english()) {
        $navItems = collect([
            (object) ['title' => __('site.nav.home'), 'url' => localized_route('home'), 'open_new_tab' => false],
            (object) ['title' => __('site.nav.about'), 'url' => localized_route('about'), 'open_new_tab' => false],
            (object) ['title' => __('site.nav.gallery'), 'url' => localized_route('gallery.index'), 'open_new_tab' => false],
            (object) ['title' => __('site.nav.menu'), 'url' => localized_route('menu.index'), 'open_new_tab' => false],
            (object) ['title' => __('site.nav.blog'), 'url' => localized_route('blog.index'), 'open_new_tab' => false],
            (object) ['title' => __('site.nav.catering'), 'url' => localized_route('local.vegetarian-catering-hai-phong'), 'open_new_tab' => false],
            (object) ['title' => __('site.nav.reservation'), 'url' => localized_route('reservations.create'), 'open_new_tab' => false],
            (object) ['title' => __('site.nav.contact'), 'url' => localized_route('contact'), 'open_new_tab' => false],
        ]);
    } else {
        $navItems = \App\Models\NavigationMenu::active()
            ->location('header')
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();

        if ($navItems->isEmpty()) {
            $navItems = collect([
                (object) ['title' => __('site.nav.home'), 'url' => route('home'), 'open_new_tab' => false],
                (object) ['title' => __('site.nav.about'), 'url' => route('about'), 'open_new_tab' => false],
                (object) ['title' => __('site.nav.menu'), 'url' => route('menu.index'), 'open_new_tab' => false],
                (object) ['title' => __('site.nav.blog'), 'url' => route('blog.index'), 'open_new_tab' => false],
                (object) ['title' => __('site.nav.catering'), 'url' => route('local.vegetarian-catering-hai-phong'), 'open_new_tab' => false],
                (object) ['title' => __('site.nav.reservation'), 'url' => route('reservations.create'), 'open_new_tab' => false],
                (object) ['title' => __('site.nav.contact'), 'url' => route('contact'), 'open_new_tab' => false],
            ]);
        }
    }

    $languageLinks = [
        'vi' => route('home'),
        'en' => route('localized.home'),
    ];
@endphp

<header class="site-header sticky top-0 z-50 border-b border-emerald-900/10 bg-[#fbfaf5]/92 shadow-sm backdrop-blur">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-4 py-3 sm:px-6 lg:px-8" aria-label="{{ __('site.nav.home') }}">
        <a href="{{ localized_route('home') }}" class="flex items-center gap-3" aria-label="{{ localized_setting('restaurant_name', 'Đàn Hương Chay') }}">
            @if (setting('logo_header'))
                <img src="{{ media_url(setting('logo_header')) }}" alt="{{ localized_setting('restaurant_name', 'Đàn Hương Chay') }}" class="h-11 w-11 rounded-full object-contain bg-white shadow-sm">
            @else
                <span class="grid h-11 w-11 place-items-center rounded-full bg-emerald-800 text-lg font-semibold text-white shadow-sm">ĐH</span>
            @endif
            <span>
                <span class="block text-lg font-semibold tracking-wide text-emerald-950">{{ localized_setting('restaurant_name', 'Đàn Hương Chay') }}</span>
                <span class="block text-xs uppercase tracking-[0.24em] text-amber-800">{{ __('site.brand_suffix') }}</span>
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

            <div class="ml-2 flex rounded-full border border-emerald-900/10 bg-white p-1 text-xs font-bold text-stone-600">
                @foreach ($languageLinks as $locale => $url)
                    <a href="{{ $url }}" @class([
                        'rounded-full px-2.5 py-1 transition',
                        'bg-emerald-900 text-white' => current_locale() === $locale,
                        'hover:bg-emerald-50 hover:text-emerald-900' => current_locale() !== $locale,
                    ])>{{ strtoupper($locale) }}</a>
                @endforeach
            </div>
        </div>

        <button
            type="button"
            class="mobile-nav-toggle inline-flex h-11 w-11 items-center justify-center rounded-full border border-emerald-900/20 bg-white text-emerald-950 shadow-sm lg:hidden"
            aria-controls="mobile-nav"
            aria-expanded="false"
            aria-label="{{ __('site.nav.menu') }}"
        >
            <span class="sr-only">{{ __('site.nav.menu') }}</span>
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

            <div class="grid grid-cols-2 gap-2 pt-2 text-center text-sm font-bold">
                <a href="{{ $languageLinks['vi'] }}" @class(['rounded-xl px-4 py-3', 'bg-emerald-900 text-white' => current_locale() === 'vi', 'bg-white text-stone-700 shadow-sm' => current_locale() !== 'vi'])>VI</a>
                <a href="{{ $languageLinks['en'] }}" @class(['rounded-xl px-4 py-3', 'bg-emerald-900 text-white' => current_locale() === 'en', 'bg-white text-stone-700 shadow-sm' => current_locale() !== 'en'])>EN</a>
            </div>
        </div>
    </div>
</header>
