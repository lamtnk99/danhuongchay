<footer class="border-t border-emerald-900/10 bg-emerald-950 text-emerald-50">
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
                (object) ['title' => is_english() ? 'Vegetarian restaurant Hai Phong' : 'Quán chay Hải Phòng', 'url' => localized_route('local.vegetarian-restaurant-hai-phong'), 'open_new_tab' => false],
                (object) ['title' => __('site.nav.catering'), 'url' => localized_route('local.vegetarian-catering-hai-phong'), 'open_new_tab' => false],
                (object) ['title' => __('site.nav.gallery'), 'url' => localized_route('gallery.index'), 'open_new_tab' => false],
                (object) ['title' => __('site.nav.menu'), 'url' => localized_route('menu.index'), 'open_new_tab' => false],
                (object) ['title' => __('site.nav.contact'), 'url' => localized_route('contact'), 'open_new_tab' => false],
            ]);
        }
    @endphp
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:px-8">
        <div>
            @if (setting('logo_footer'))
                <img src="{{ media_url(setting('logo_footer')) }}" alt="{{ localized_setting('restaurant_name', 'Đàn Hương Chay') }}" class="mb-4 h-14 w-14 rounded-full object-contain bg-white">
            @endif
            <p class="text-xl font-semibold">{{ localized_setting('restaurant_name', 'Đàn Hương Chay') }}</p>
            <p class="mt-4 text-sm leading-6 text-emerald-50/75">
                {{ localized_setting('footer_description', 'Ẩm thực chay thanh lành, nuôi dưỡng thân tâm bằng nguyên liệu sạch và cách nấu tinh tế.') }}
            </p>
        </div>

        <div>
            <p class="font-semibold">{{ __('site.footer.info') }}</p>
            <ul class="mt-4 space-y-2 text-sm text-emerald-50/75">
                <li>{{ localized_setting('address', 'Villa 01-B4 Hoàng Mậu - Gia Viên, TP. Hải Phòng') }}</li>
                @if (setting('hotline'))
                    <li>Hotline: {{ setting('hotline') }}</li>
                @endif
                @if (setting('phone') && setting('phone') !== setting('hotline'))
                    <li>{{ __('site.footer.phone') }}: {{ setting('phone') }}</li>
                @endif
                <li>{{ setting('email', 'info@danhuongchay.com') }}</li>
                <li>{{ localized_setting('opening_hours', '09:00 - 14:00 | 16:00 - 21:00 hằng ngày') }}</li>
            </ul>
        </div>

        <div>
            <p class="font-semibold">{{ __('site.footer.quick_links') }}</p>
            <ul class="mt-4 space-y-2 text-sm text-emerald-50/75">
                @foreach ($footerMenus as $menu)
                    @php
                        $menuUrl = method_exists($menu, 'localized') ? $menu->localized('url', $menu->url) : $menu->url;
                        $menuTitle = method_exists($menu, 'localized') ? $menu->localized('title', $menu->title) : $menu->title;
                    @endphp
                    <li>
                        <a class="hover:text-white" href="{{ str_starts_with($menuUrl, 'http') ? $menuUrl : url($menuUrl) }}" @if ($menu->open_new_tab) target="_blank" rel="noopener" @endif>
                            {{ $menuTitle }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div>
            <p class="font-semibold">{{ __('site.footer.follow') }}</p>
            <div class="mt-4 flex gap-3">
                @if (setting('facebook_url')) <a href="{{ setting('facebook_url') }}" class="social-link" aria-label="Facebook Đàn Hương Chay">@include('partials.social-icon', ['name' => 'facebook'])</a> @endif
                @if (setting('zalo_url')) <a href="{{ setting('zalo_url') }}" class="social-link" aria-label="Zalo Đàn Hương Chay">@include('partials.social-icon', ['name' => 'zalo'])</a> @endif
                @if (setting('instagram_url')) <a href="{{ setting('instagram_url') }}" class="social-link" aria-label="Instagram Đàn Hương Chay">@include('partials.social-icon', ['name' => 'instagram'])</a> @endif
                @if (setting('tiktok_url')) <a href="{{ setting('tiktok_url') }}" class="social-link" aria-label="TikTok Đàn Hương Chay">@include('partials.social-icon', ['name' => 'tiktok'])</a> @endif
                @if (setting('youtube_url')) <a href="{{ setting('youtube_url') }}" class="social-link" aria-label="YouTube Đàn Hương Chay">@include('partials.social-icon', ['name' => 'youtube'])</a> @endif
            </div>
        </div>
    </div>
    <div class="border-t border-white/10 px-4 py-5 text-center text-xs text-emerald-50/60">
        {{ localized_setting('copyright', '© '.date('Y').' Đàn Hương Chay. All rights reserved.') }}
    </div>
</footer>
