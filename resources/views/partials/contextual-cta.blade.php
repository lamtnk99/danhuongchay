@php
    $context = $context ?? 'default';
    $title = $title ?? __('site.dish.reservation_title');
    $text = $text ?? __('site.dish.reservation_text');
    $primaryLabel = $primaryLabel ?? __('site.dish.book');
    $secondaryLabel = $secondaryLabel ?? __('site.dish.catering');
    $primaryUrl = $primaryUrl ?? localized_route('reservations.create');
    $secondaryUrl = $secondaryUrl ?? localized_route('local.vegetarian-catering-hai-phong');
    $phoneLabel = $phoneLabel ?? (is_english() ? 'Call hotline' : 'Gọi hotline');
    $trackLabel = $trackLabel ?? $title;
    $phoneHref = preg_replace('/\D+/', '', setting('hotline', setting('phone', '0947361515')));
@endphp

<section class="smart-cta" data-track-view="view_contextual_cta" data-track-category="{{ $context }}" data-track-label="{{ $trackLabel }}">
    <div>
        <p class="smart-cta-kicker">{{ $kicker ?? (is_english() ? 'Need a suggestion?' : 'Cần tư vấn nhanh?') }}</p>
        <h2>{{ $title }}</h2>
        <p>{{ $text }}</p>
    </div>
    <div class="smart-cta-actions">
        <a href="{{ $primaryUrl }}" class="btn-primary justify-center" data-track-event="click_contextual_reservation" data-track-category="{{ $context }}" data-track-label="{{ $trackLabel }}" data-facebook-event="ClickReservation">
            {{ $primaryLabel }}
        </a>
        <a href="{{ $secondaryUrl }}" class="btn-secondary justify-center" data-track-event="click_contextual_catering" data-track-category="{{ $context }}" data-track-label="{{ $trackLabel }}" data-facebook-event="ClickCatering">
            {{ $secondaryLabel }}
        </a>
        <a href="tel:{{ $phoneHref }}" class="btn-ghost justify-center" data-track-event="click_call" data-track-category="{{ $context }}" data-track-label="{{ $trackLabel }}" data-facebook-event="ClickCall">
            {{ $phoneLabel }}
        </a>
    </div>
</section>
