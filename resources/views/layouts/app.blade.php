<!doctype html>
<html lang="{{ current_locale() }}" data-upload-max-kb="{{ config('uploads.max_image_kb', 10240) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('partials.seo')

    <script>
        window.danHuongI18n = {!! json_encode([
            'openMenu' => __('site.js.open_menu'),
            'closeMenu' => __('site.js.close_menu'),
            'submitLoading' => __('site.js.submit_loading'),
            'reservationWindow' => __('site.js.reservation_window'),
            'reservationTodayWindow' => __('site.js.reservation_today_window'),
            'uploadTooLarge' => __('site.js.upload_too_large'),
            'chatSendFailed' => __('site.chat.send_failed'),
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!};
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @isset($schemas)
        @foreach ($schemas as $schema)
            <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
        @endforeach
    @endisset
</head>
<body class="public-site min-h-screen bg-[#fbfaf5] font-sans text-stone-800 antialiased">
    <a href="#main-content" class="skip-link">{{ __('site.skip') }}</a>

    @include('partials.mobile-page-loader')
    @include('partials.header')

    <main id="main-content">
        @yield('content')
    </main>

    @include('partials.footer')
    @include('partials.mobile-floating-actions')
    @include('partials.chat-widget')
    @include('partials.mobile-bottom-nav')
    @include('partials.promo-popup', ['promotion' => $globalPopupPromotion ?? null])
</body>
</html>
