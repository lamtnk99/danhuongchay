<div id="page-loader" class="page-loader" aria-hidden="true">
    <div class="page-loader__inner">
        <div class="page-loader__brand-ring">
            <div class="page-loader__ring-track"></div>
            <div class="page-loader__ring-fill"></div>
            <div class="page-loader__brand-mark">
                @if (setting('logo_header'))
                    <img src="{{ media_url(setting('logo_header')) }}" alt="" class="page-loader__logo-img">
                @else
                    <span class="page-loader__logo-text">ĐH</span>
                @endif
            </div>
        </div>
        <p class="page-loader__brand-name">Đàn Hương Chay</p>
        <p class="page-loader__status">{{ __('site.loading') }}</p>
    </div>
</div>
