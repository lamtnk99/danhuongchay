<footer class="border-t border-emerald-900/10 bg-emerald-950 text-emerald-50">
    @php
        $footerMenus = \App\Models\NavigationMenu::active()
            ->location('footer')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->get();
    @endphp
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:px-8">
        <div>
            @if (setting('logo_footer'))
                <img src="{{ media_url(setting('logo_footer')) }}" alt="{{ setting('restaurant_name', 'Đàn Hương Chay') }}" class="mb-4 h-14 w-14 rounded-full object-contain bg-white">
            @endif
            <p class="text-xl font-semibold">{{ setting('restaurant_name', 'Đàn Hương Chay') }}</p>
            <p class="mt-4 text-sm leading-6 text-emerald-50/75">
                {{ setting('footer_description', 'Ẩm thực chay thanh lành, nuôi dưỡng thân tâm bằng nguyên liệu sạch và cách nấu tinh tế.') }}
            </p>
        </div>

        <div>
            <p class="font-semibold">Thông tin quán</p>
            <ul class="mt-4 space-y-2 text-sm text-emerald-50/75">
                <li>{{ setting('address', '123 Đường An Nhiên, Quận 3, TP. Hồ Chí Minh') }}</li>
                <li>{{ setting('phone', '0912 345 678') }}</li>
                <li>{{ setting('email', 'hello@danhuongchay.vn') }}</li>
                <li>{{ setting('opening_hours', '09:00 - 21:30 hằng ngày') }}</li>
            </ul>
        </div>

        <div>
            <p class="font-semibold">Link nhanh</p>
            <ul class="mt-4 space-y-2 text-sm text-emerald-50/75">
                @forelse ($footerMenus as $menu)
                    <li>
                        <a class="hover:text-white" href="{{ str_starts_with($menu->url, 'http') ? $menu->url : url($menu->url) }}" @if ($menu->open_new_tab) target="_blank" rel="noopener" @endif>
                            {{ $menu->title }}
                        </a>
                    </li>
                @empty
                    <li><a class="hover:text-white" href="{{ route('about') }}">Giới thiệu</a></li>
                    <li><a class="hover:text-white" href="{{ route('menu.index') }}">Thực đơn</a></li>
                    <li><a class="hover:text-white" href="{{ route('blog.index') }}">Blog</a></li>
                    <li><a class="hover:text-white" href="{{ route('reservations.create') }}">Đặt bàn</a></li>
                    <li><a class="hover:text-white" href="{{ route('contact') }}">Liên hệ</a></li>
                @endforelse
            </ul>
        </div>

        <div>
            <p class="font-semibold">Theo dõi</p>
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
        {{ setting('copyright', '© '.date('Y').' Đàn Hương Chay. All rights reserved.') }}
    </div>
</footer>
