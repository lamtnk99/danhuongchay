<footer class="border-t border-[#667132]/10 bg-[#faf7f2] text-[#3d4226]">
    @php
        $footerMenus = \App\Models\NavigationMenu::active()
            ->location('footer')
            ->whereNull('parent_id')
            ->with('translations')
            ->orderBy('sort_order')
            ->get();

        if ($footerMenus->isEmpty()) {
            $footerMenus = collect([
                (object) ['title' => __('site.nav.about'), 'url' => localized_route('about'), 'open_new_tab' => false],
                (object) ['title' => __('site.auto.auto_68bdb9147851'), 'url' => localized_route('local.vegetarian-restaurant-hai-phong'), 'open_new_tab' => false],
                (object) ['title' => __('site.auto.auto_b82881a47f27'), 'url' => localized_route('local.vegetarian-restaurant-buon-ma-thuot'), 'open_new_tab' => false],
                (object) ['title' => __('site.nav.catering'), 'url' => localized_route('local.vegetarian-catering-hai-phong'), 'open_new_tab' => false],
                (object) ['title' => __('site.nav.gallery'), 'url' => localized_route('gallery.index'), 'open_new_tab' => false],
                (object) ['title' => __('site.nav.menu'), 'url' => localized_route('menu.index'), 'open_new_tab' => false],
                (object) ['title' => __('site.nav.contact'), 'url' => localized_route('contact'), 'open_new_tab' => false],
            ]);
        }

        $footerMenuPaths = $footerMenus
            ->map(fn ($menu) => method_exists($menu, 'localized') ? $menu->localized('url', $menu->url) : $menu->url)
            ->map(fn ($url) => parse_url(str_starts_with($url, 'http') ? $url : url($url), PHP_URL_PATH))
            ->filter()
            ->values();

        $requiredFooterLinks = collect([
            (object) [
                'title' => __('site.auto.auto_b82881a47f27'),
                'url' => localized_route('local.vegetarian-restaurant-buon-ma-thuot'),
                'open_new_tab' => false,
            ],
        ])->reject(fn ($link) => $footerMenuPaths->contains(parse_url($link->url, PHP_URL_PATH)));

        $footerBranches = \App\Models\Branch::active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->limit(3)
            ->get();
    @endphp

    <div class="mx-auto grid max-w-7xl gap-12 px-4 py-14 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:px-8">
        <div>
            @if (setting('logo_footer'))
                <img src="{{ media_url(setting('logo_footer')) }}" alt="{{ localized_setting('restaurant_name', 'Đàn Hương Chay') }}" class="mb-5 h-14 w-14 rounded-full object-contain bg-white shadow-sm">
            @endif
            <p class="text-xl font-bold text-[#3d4226]">{{ localized_setting('restaurant_name', 'Đàn Hương Chay') }}</p>
            <p class="mt-4 text-sm leading-6 text-stone-500">
                {{ localized_setting('footer_description', 'Ẩm thực chay thanh lành, nuôi dưỡng thân tâm bằng nguyên liệu sạch và cách nấu tinh tế.') }}
            </p>
            <div class="mt-6 flex gap-3">
                @if (setting('facebook_url')) <a href="{{ setting('facebook_url') }}" class="social-link" aria-label="Facebook Đàn Hương Chay">@include('partials.social-icon', ['name' => 'facebook'])</a> @endif
                @if (setting('zalo_url')) <a href="{{ setting('zalo_url') }}" class="social-link" aria-label="Zalo Đàn Hương Chay">@include('partials.social-icon', ['name' => 'zalo'])</a> @endif
                @if (setting('instagram_url')) <a href="{{ setting('instagram_url') }}" class="social-link" aria-label="Instagram Đàn Hương Chay">@include('partials.social-icon', ['name' => 'instagram'])</a> @endif
                @if (setting('tiktok_url')) <a href="{{ setting('tiktok_url') }}" class="social-link" aria-label="TikTok Đàn Hương Chay">@include('partials.social-icon', ['name' => 'tiktok'])</a> @endif
                @if (setting('youtube_url')) <a href="{{ setting('youtube_url') }}" class="social-link" aria-label="YouTube Đàn Hương Chay">@include('partials.social-icon', ['name' => 'youtube'])</a> @endif
            </div>
        </div>

        <div>
            <p class="text-sm font-bold uppercase tracking-wider text-[#667132]">{{ __('site.footer.info') }}</p>
            <ul class="mt-4 space-y-4 text-sm text-stone-500">
                @forelse ($footerBranches as $branch)
                    <li>
                        <span class="block font-semibold text-[#3d4226]">{{ $branch->name }}</span>
                        @if ($branch->address)<span class="block mt-0.5">{{ $branch->address }}</span>@endif
                        @if ($branch->hotline)<span class="block mt-0.5">Hotline: <a href="tel:{{ preg_replace('/\D+/', '', $branch->hotline) }}" class="text-[#667132] hover:underline">{{ $branch->hotline }}</a></span>@endif
                        @if ($branch->phone && $branch->phone !== $branch->hotline)<span class="block mt-0.5">{{ __('site.footer.phone') }}: {{ $branch->phone }}</span>@endif
                    </li>
                @empty
                    <li>{{ localized_setting('address', 'Villa 01-B4 Hoàng Mậu - Gia Viên, TP. Hải Phòng') }}</li>
                    @if (setting('hotline'))<li>Hotline: <a href="tel:{{ preg_replace('/\D+/', '', setting('hotline')) }}" class="text-[#667132] hover:underline">{{ setting('hotline') }}</a></li>@endif
                    @if (setting('phone') && setting('phone') !== setting('hotline'))<li>{{ __('site.footer.phone') }}: {{ setting('phone') }}</li>@endif
                @endforelse
                <li><a href="mailto:{{ setting('email', 'info@danhuongchay.com') }}" class="text-[#667132] hover:underline">{{ setting('email', 'info@danhuongchay.com') }}</a></li>
                <li>{{ localized_setting('opening_hours', '09:00 - 14:00 | 16:00 - 21:00 hằng ngày') }}</li>
            </ul>
        </div>

        <div>
            <p class="text-sm font-bold uppercase tracking-wider text-[#667132]">{{ __('site.footer.quick_links') }}</p>
            <ul class="mt-4 space-y-2.5 text-sm text-stone-500">
                @foreach ($footerMenus as $menu)
                    @php
                        $menuUrl = method_exists($menu, 'localized') ? $menu->localized('url', $menu->url) : $menu->url;
                        $menuTitle = method_exists($menu, 'localized') ? $menu->localized('title', $menu->title) : $menu->title;
                    @endphp
                    <li>
                        <a class="hover:text-[#667132] transition-colors" href="{{ str_starts_with($menuUrl, 'http') ? $menuUrl : url($menuUrl) }}" @if ($menu->open_new_tab) target="_blank" rel="noopener" @endif>
                            {{ $menuTitle }}
                        </a>
                    </li>
                @endforeach
                @foreach ($requiredFooterLinks as $menu)
                    <li>
                        <a class="hover:text-[#667132] transition-colors" href="{{ $menu->url }}">
                            {{ $menu->title }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <p class="text-sm font-bold uppercase tracking-wider text-[#667132]">{{ __('site.auto.auto_c8c784a2a41c') }}</p>
            <p class="mt-4 text-sm leading-relaxed text-stone-500">
                {{ __('site.auto.auto_d5edfb90e89d') }}
            </p>
            <a href="{{ localized_route('reservations.create') }}" class="mt-5 inline-flex min-h-[2.75rem] items-center justify-center rounded-full bg-[#667132] px-6 text-sm font-bold text-white shadow-sm transition hover:-translate-y-0.5 hover:bg-[#788240] hover:shadow-md">
                {{ __('site.auto.auto_64a2210d034c') }}
            </a>
        </div>
    </div>

    <div class="border-t border-[#667132]/10 px-4 py-5 text-center text-xs text-stone-400">
        {{ localized_setting('copyright', '© '.date('Y').' Đàn Hương Chay. All rights reserved.') }}
    </div>
</footer>
