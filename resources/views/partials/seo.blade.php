@php
    $seo = $seo ?? [];
    $title = $seo['title'] ?? localized_setting('default_meta_title', __('site.auto.auto_9823c88f5889'));
    $description = $seo['description'] ?? localized_setting('default_meta_description', __('site.auto.auto_70a13ac60acc'));
    $keywords = $seo['keywords'] ?? localized_setting('default_meta_keywords', __('site.auto.auto_d811f86c0bc3'));
    $canonical = $seo['canonical'] ?? url()->current();
    $image = $seo['image'] ?? \App\Services\SeoService::defaultImage();
    $imageAlt = $seo['image_alt'] ?? $title;
    $type = $seo['type'] ?? 'website';
    $robots = $seo['robots'] ?? null;
    $ogLocale = config('locales.supported.'.current_locale().'.og_locale', 'vi_VN');
    $alternates = $seo['alternates'] ?? \App\Services\SeoService::currentRouteAlternates();
    $analyticsCode = setting('google_analytics_code');
    $analyticsId = config('services.google.analytics_id');
    $searchConsole = setting('google_search_console');
    $searchConsoleVerification = config('services.google.site_verification');
@endphp

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
@if ($robots)
    <meta name="robots" content="{{ $robots }}">
@endif
<link rel="canonical" href="{{ $canonical }}">
@if (filled($analyticsCode) || filled($analyticsId))
    <link rel="dns-prefetch" href="//www.googletagmanager.com">
    <link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
@endif

@foreach ($alternates as $locale => $url)
    <link rel="alternate" hreflang="{{ $locale }}" href="{{ $url }}">
@endforeach
@if (isset($alternates['vi']))
    <link rel="alternate" hreflang="x-default" href="{{ $alternates['vi'] }}">
@endif

<meta property="og:locale" content="{{ $ogLocale }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:site_name" content="{{ localized_setting('restaurant_name', 'Đàn Hương Chay') }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:image:alt" content="{{ $imageAlt }}">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">
<meta name="twitter:image:alt" content="{{ $imageAlt }}">

@if (setting('favicon'))
    <link rel="icon" href="{{ media_url(setting('favicon')) }}">
@endif

@if (filled($analyticsCode))
    {!! $analyticsCode !!}
@elseif (filled($analyticsId))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $analyticsId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $analyticsId }}');
    </script>
@endif

@if (filled($searchConsole))
    {!! $searchConsole !!}
@elseif (filled($searchConsoleVerification))
    <meta name="google-site-verification" content="{{ $searchConsoleVerification }}">
@endif

{!! setting('facebook_pixel_code') !!}
