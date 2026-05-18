@php
    $seo = $seo ?? [];
    $title = $seo['title'] ?? 'Đàn Hương Chay | Ẩm thực chay thanh lành';
    $description = $seo['description'] ?? 'Đàn Hương Chay phục vụ món chay Việt hiện đại, nguyên liệu sạch và không gian thanh tịnh.';
    $keywords = $seo['keywords'] ?? 'Đàn Hương Chay, quán chay, nhà hàng chay, món chay ngon';
    $canonical = $seo['canonical'] ?? url()->current();
    $image = $seo['image'] ?? \App\Services\SeoService::defaultImage();
    $type = $seo['type'] ?? 'website';
@endphp

<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
<link rel="canonical" href="{{ $canonical }}">

<meta property="og:locale" content="vi_VN">
<meta property="og:type" content="{{ $type }}">
<meta property="og:site_name" content="Đàn Hương Chay">
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
