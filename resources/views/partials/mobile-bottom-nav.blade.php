@php
    $items = [
        ['label' => __('site.nav.home'), 'url' => localized_route('home'), 'active' => request()->routeIs('home', 'localized.home'), 'icon' => 'home'],
        ['label' => __('site.nav.menu'), 'url' => localized_route('menu.index'), 'active' => request()->routeIs('menu.*', 'localized.menu.*'), 'icon' => 'menu'],
        ['label' => __('site.nav.catering'), 'url' => localized_route('local.vegetarian-catering'), 'active' => request()->routeIs('local.vegetarian-catering', 'localized.local.vegetarian-catering', 'local.vegetarian-catering-hai-phong', 'localized.local.vegetarian-catering-hai-phong', 'local.vegetarian-catering-buon-ma-thuot', 'localized.local.vegetarian-catering-buon-ma-thuot'), 'icon' => 'catering'],
        ['label' => __('site.nav.reservation'), 'url' => localized_route('reservations.create'), 'active' => request()->routeIs('reservations.*', 'localized.reservations.*'), 'icon' => 'calendar'],
        ['label' => __('site.nav.contact'), 'url' => localized_route('contact'), 'active' => request()->routeIs('contact', 'localized.contact'), 'icon' => 'phone'],
    ];
@endphp

<nav class="mobile-bottom-nav lg:hidden" aria-label="{{ __('site.nav.menu') }}">
    @foreach ($items as $item)
        <a href="{{ $item['url'] }}" @class(['mobile-bottom-link', 'is-active' => $item['active']])>
            @switch($item['icon'])
                @case('home')
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 10.4 12 3l9 7.4v9.1a1.5 1.5 0 0 1-1.5 1.5h-4.2v-6.1H8.7V21H4.5A1.5 1.5 0 0 1 3 19.5v-9.1Z" /></svg>
                    @break
                @case('menu')
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 5.5h14a1 1 0 1 1 0 2H5a1 1 0 0 1 0-2Zm0 5.5h14a1 1 0 1 1 0 2H5a1 1 0 1 1 0-2Zm0 5.5h14a1 1 0 1 1 0 2H5a1 1 0 1 1 0-2Z" /></svg>
                    @break
                @case('calendar')
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1.5A2.5 2.5 0 0 1 22 6.5v12A2.5 2.5 0 0 1 19.5 21h-15A2.5 2.5 0 0 1 2 18.5v-12A2.5 2.5 0 0 1 4.5 4H6V3a1 1 0 0 1 1-1Zm13 8H4v8.5c0 .28.22.5.5.5h15a.5.5 0 0 0 .5-.5V10Z" /></svg>
                    @break
                @case('catering')
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3.5c3.9 0 7.2 2.8 7.9 6.5h.6a1 1 0 1 1 0 2h-17a1 1 0 1 1 0-2h.6C4.8 6.3 8.1 3.5 12 3.5Zm0 2c-2.8 0-5.1 1.9-5.8 4.5h11.6C17.1 7.4 14.8 5.5 12 5.5ZM5 14h14a1 1 0 0 1 .9 1.4l-1.6 3.2A2.5 2.5 0 0 1 16.1 20H7.9a2.5 2.5 0 0 1-2.2-1.4l-1.6-3.2A1 1 0 0 1 5 14Z" /></svg>
                    @break
                @default
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6.6 10.8c1.4 2.7 3.6 4.9 6.3 6.3l2.1-2.1c.3-.3.8-.4 1.2-.3 1 .3 2.1.5 3.2.5.7 0 1.2.5 1.2 1.2v3.2c0 .7-.5 1.2-1.2 1.2C10.3 20.8 3.2 13.7 3.2 4.6c0-.7.5-1.2 1.2-1.2h3.2c.7 0 1.2.5 1.2 1.2 0 1.1.2 2.2.5 3.2.1.4 0 .9-.3 1.2l-2.4 1.8Z" /></svg>
            @endswitch
            <span>{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>
