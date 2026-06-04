@php
    $seo = $seo ?? [];
    $title = $seo['title'] ?? localized_setting('default_meta_title', __('site.auto.auto_9823c88f5889'));
    $description = $seo['description'] ?? localized_setting('default_meta_description', __('site.auto.auto_70a13ac60acc'));
    $keywords = $seo['keywords'] ?? localized_setting('default_meta_keywords', __('site.auto.auto_d811f86c0bc3'));
    $canonical = $seo['canonical'] ?? url()->current();
    $image = $seo['image'] ?? \App\Services\SeoService::defaultImage();
    $type = $seo['type'] ?? 'website';
    $ogLocale = config('locales.supported.'.current_locale().'.og_locale', 'vi_VN');
    $alternates = $seo['alternates'] ?? [
        'vi' => route('home'),
        'en' => route('localized.home'),
    ];
@endphp

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
<link rel="canonical" href="{{ $canonical }}">

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

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
<meta name="twitter:image" content="{{ $image }}">

@if (setting('favicon'))
    <link rel="icon" href="{{ media_url(setting('favicon')) }}">
@endif

{!! setting('google_analytics_code') !!}
{!! setting('google_search_console') !!}
{!! setting('facebook_pixel_code') !!}
