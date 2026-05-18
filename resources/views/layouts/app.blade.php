<!doctype html>
<html lang="vi" data-upload-max-kb="{{ config('uploads.max_image_kb', 10240) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @include('partials.seo')

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @isset($schemas)
        @foreach ($schemas as $schema)
            <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
        @endforeach
    @endisset
</head>
<body class="public-site min-h-screen bg-[#fbfaf5] font-sans text-stone-800 antialiased">
    <a href="#main-content" class="skip-link">Bỏ qua menu</a>

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
