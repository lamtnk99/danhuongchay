@php
    $authUser = auth()->user();
    $initials = collect(explode(' ', trim($authUser?->name ?? 'Dan Huong')))
        ->filter()
        ->take(2)
        ->map(fn ($word) => mb_strtoupper(mb_substr($word, 0, 1)))
        ->implode('');

    $sidebarCounts = [
        'reservations' => \App\Models\Reservation::where('status', 'pending')->count(),
        'contacts' => \App\Models\Contact::where('status', 'new')->count(),
        'chat' => \App\Models\ChatMessage::where('sender', 'visitor')->where('is_read', false)->count(),
    ];

    $items = [
        ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'active' => 'admin.dashboard'],
        ['label' => 'Cài đặt website', 'route' => 'admin.settings.edit', 'active' => 'admin.settings.*'],
        ['label' => 'Logo & nhận diện', 'route' => 'admin.identity.edit', 'active' => 'admin.identity.*'],
        ['label' => 'Banner trang chủ', 'route' => 'admin.banners.index', 'active' => 'admin.banners.*'],
        ['label' => 'Danh mục', 'route' => 'admin.categories.index', 'active' => 'admin.categories.*'],
        ['label' => 'Món ăn / Sản phẩm', 'route' => 'admin.dishes.index', 'active' => 'admin.dishes.*'],
        ['label' => 'Bài viết', 'route' => 'admin.posts.index', 'active' => 'admin.posts.*'],
        ['label' => 'Review khách hàng', 'route' => 'admin.testimonials.index', 'active' => 'admin.testimonials.*'],
        ['label' => 'Khuyến mãi / Popup', 'route' => 'admin.promotions.index', 'active' => 'admin.promotions.*'],
        ['label' => 'Không gian quán', 'route' => 'admin.gallery.index', 'active' => 'admin.gallery.*'],
        ['label' => 'Trang tĩnh', 'route' => 'admin.pages.index', 'active' => 'admin.pages.*'],
        ['label' => 'Menu điều hướng', 'route' => 'admin.menus.index', 'active' => 'admin.menus.*'],
        ['label' => 'Đặt bàn', 'route' => 'admin.reservations.index', 'active' => 'admin.reservations.*', 'badge' => 'reservations'],
        ['label' => 'Liên hệ', 'route' => 'admin.contacts.index', 'active' => 'admin.contacts.*', 'badge' => 'contacts'],
        ['label' => 'Chat online', 'route' => 'admin.chats.index', 'active' => 'admin.chats.*', 'badge' => 'chat'],
        ['label' => 'SEO', 'route' => 'admin.seo.edit', 'active' => 'admin.seo.*'],
        ['label' => 'Tài khoản admin', 'route' => 'admin.users.index', 'active' => 'admin.users.*'],
    ];
@endphp

<aside data-admin-sidebar class="hidden w-full shrink-0 border-r border-slate-200 bg-white lg:block lg:min-h-screen lg:w-72">
    <div class="flex h-16 items-center gap-3 border-b border-slate-200 px-5">
        @if ($authUser?->avatar)
            <img src="{{ media_url($authUser->avatar) }}" alt="{{ $authUser->name }}" class="h-10 w-10 rounded-xl object-cover">
        @else
            <div class="grid h-10 w-10 place-items-center rounded-xl bg-emerald-700 font-bold text-white">{{ $initials ?: 'ĐH' }}</div>
        @endif
        <div>
            <p class="font-bold text-emerald-950">Đàn Hương Chay</p>
            <p class="text-xs text-slate-500">Admin Panel</p>
        </div>
    </div>

    <nav class="space-y-1 p-4">
        @foreach ($items as $item)
            @php
                $count = !empty($item['badge']) ? ($sidebarCounts[$item['badge']] ?? 0) : 0;
            @endphp
            <a
                href="{{ route($item['route']) }}"
                @class([
                    'flex items-center justify-between gap-3 rounded-xl px-4 py-2.5 text-sm font-semibold transition',
                    'bg-emerald-700 text-white shadow-sm' => request()->routeIs($item['active']),
                    'text-slate-600 hover:bg-emerald-50 hover:text-emerald-800' => ! request()->routeIs($item['active']),
                ])
            >
                <span>{{ $item['label'] }}</span>
                @if (!empty($item['badge']))
                    <span
                        data-admin-badge="{{ $item['badge'] }}"
                        @class(['admin-sidebar-badge', 'hidden' => $count < 1])
                    >
                        {{ $count }}
                    </span>
                @endif
            </a>
        @endforeach
    </nav>
</aside>
