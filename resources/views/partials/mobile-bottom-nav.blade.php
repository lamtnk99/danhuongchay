@php
    $items = [
        [
            'label' => 'Trang chủ',
            'url' => route('home'),
            'active' => request()->routeIs('home'),
            'icon' => 'home',
        ],
        [
            'label' => 'Menu',
            'url' => route('menu.index'),
            'active' => request()->routeIs('menu.*'),
            'icon' => 'menu',
        ],
        [
            'label' => 'Đặt bàn',
            'url' => route('reservations.create'),
            'active' => request()->routeIs('reservations.*'),
            'icon' => 'calendar',
        ],
        [
            'label' => 'Liên hệ',
            'url' => route('contact'),
            'active' => request()->routeIs('contact'),
            'icon' => 'phone',
        ],
    ];
@endphp

<nav class="mobile-bottom-nav lg:hidden" aria-label="Điều hướng nhanh trên mobile">
    @foreach ($items as $item)
        <a href="{{ $item['url'] }}" @class(['mobile-bottom-link', 'is-active' => $item['active']])>
            @switch($item['icon'])
                @case('home')
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M3 10.4 12 3l9 7.4v9.1a1.5 1.5 0 0 1-1.5 1.5h-4.2v-6.1H8.7V21H4.5A1.5 1.5 0 0 1 3 19.5v-9.1Z" />
                    </svg>
                    @break
                @case('menu')
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M5 5.5h14a1 1 0 1 1 0 2H5a1 1 0 0 1 0-2Zm0 5.5h14a1 1 0 1 1 0 2H5a1 1 0 1 1 0-2Zm0 5.5h14a1 1 0 1 1 0 2H5a1 1 0 1 1 0-2Z" />
                    </svg>
                    @break
                @case('calendar')
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M7 2a1 1 0 0 1 1 1v1h8V3a1 1 0 1 1 2 0v1h1.5A2.5 2.5 0 0 1 22 6.5v12A2.5 2.5 0 0 1 19.5 21h-15A2.5 2.5 0 0 1 2 18.5v-12A2.5 2.5 0 0 1 4.5 4H6V3a1 1 0 0 1 1-1Zm13 8H4v8.5c0 .28.22.5.5.5h15a.5.5 0 0 0 .5-.5V10Z" />
                    </svg>
                    @break
                @default
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M6.6 10.8c1.4 2.7 3.6 4.9 6.3 6.3l2.1-2.1c.3-.3.8-.4 1.2-.3 1 .3 2.1.5 3.2.5.7 0 1.2.5 1.2 1.2v3.2c0 .7-.5 1.2-1.2 1.2C10.3 20.8 3.2 13.7 3.2 4.6c0-.7.5-1.2 1.2-1.2h3.2c.7 0 1.2.5 1.2 1.2 0 1.1.2 2.2.5 3.2.1.4 0 .9-.3 1.2l-2.4 1.8Z" />
                    </svg>
            @endswitch
            <span>{{ $item['label'] }}</span>
        </a>
    @endforeach
</nav>
