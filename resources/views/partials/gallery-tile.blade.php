@php
    $galleryTitle = $image->localized('title');
    $galleryDescription = $image->localized('description');
@endphp

<article class="gallery-tile" tabindex="0">
    <img
        src="{{ media_variant_url($image->image, 'card') }}"
        @if (media_srcset($image->image, ['thumb', 'card', 'large'])) srcset="{{ media_srcset($image->image, ['thumb', 'card', 'large']) }}" @endif
        alt="{{ $image->localized('alt_text', $galleryTitle) }}"
        loading="lazy"
        sizes="(max-width: 768px) 100vw, 33vw"
    >
    <div class="gallery-tile__caption">
        <h3>{{ $galleryTitle }}</h3>
        @if ($galleryDescription)
            <p>{{ $galleryDescription }}</p>
        @endif
    </div>
</article>
