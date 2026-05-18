@php
    $phone = setting('hotline', setting('phone'));
    $phoneHref = $phone ? preg_replace('/[^0-9+]/', '', $phone) : null;
    $zaloUrl = setting('zalo_url');
    $messengerUrl = setting('messenger_url', setting('facebook_url'));
@endphp

@if ($phoneHref || $zaloUrl || $messengerUrl)
    <div class="mobile-floating-actions lg:hidden" aria-label="Liên hệ nhanh">
        @if ($phoneHref)
            <a href="tel:{{ $phoneHref }}" class="mobile-floating-action is-call" aria-label="Gọi {{ setting('restaurant_name', 'Đàn Hương Chay') }}">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M6.6 10.8c1.4 2.7 3.6 4.9 6.3 6.3l2.1-2.1c.3-.3.8-.4 1.2-.3 1 .3 2.1.5 3.2.5.7 0 1.2.5 1.2 1.2v3.2c0 .7-.5 1.2-1.2 1.2C10.3 20.8 3.2 13.7 3.2 4.6c0-.7.5-1.2 1.2-1.2h3.2c.7 0 1.2.5 1.2 1.2 0 1.1.2 2.2.5 3.2.1.4 0 .9-.3 1.2l-2.4 1.8Z" />
                </svg>
            </a>
        @endif

        @if ($zaloUrl)
            <a href="{{ $zaloUrl }}" class="mobile-floating-action" aria-label="Nhắn Zalo" target="_blank" rel="noopener">
                @include('partials.social-icon', ['name' => 'zalo'])
            </a>
        @endif

        @if ($messengerUrl)
            <a href="{{ $messengerUrl }}" class="mobile-floating-action" aria-label="Nhắn Facebook" target="_blank" rel="noopener">
                @include('partials.social-icon', ['name' => 'facebook'])
            </a>
        @endif
    </div>
@endif
