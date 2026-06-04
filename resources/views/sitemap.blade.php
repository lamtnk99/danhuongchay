<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach ($urls as $url)
    <url>
        <loc>{{ $url['loc'] }}</loc>
        <lastmod>{{ $url['lastmod']->toAtomString() }}</lastmod>
        <changefreq>{{ $url['changefreq'] }}</changefreq>
        <priority>{{ $url['priority'] }}</priority>
@foreach ($url['alternates'] as $locale => $alternateUrl)
        <xhtml:link rel="alternate" hreflang="{{ $locale }}" href="{{ $alternateUrl }}" />
@endforeach
@if (isset($url['alternates']['vi']))
        <xhtml:link rel="alternate" hreflang="x-default" href="{{ $url['alternates']['vi'] }}" />
@endif
    </url>
@endforeach
</urlset>
