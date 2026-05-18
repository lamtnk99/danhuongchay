@if ($promotion)
    <div
        class="promo-popup hidden"
        data-promo-popup
        data-promo-id="{{ $promotion->id }}"
        data-show-once="{{ $promotion->show_once ? '1' : '0' }}"
        style="--promo-accent: {{ $promotion->accent_color ?: '#047857' }}"
        role="dialog"
        aria-modal="true"
        aria-labelledby="promo-popup-title"
    >
        <div class="promo-popup-backdrop" data-promo-close></div>
        <div @class(['promo-popup-card', 'promo-popup-'.$promotion->template])>
            <button type="button" class="promo-popup-close" data-promo-close aria-label="Đóng thông báo">×</button>

            @if ($promotion->image && $promotion->template !== 'minimal')
                <img
                    src="{{ media_url($promotion->image) }}"
                    alt="{{ $promotion->title }}"
                    class="promo-popup-image"
                    loading="lazy"
                >
            @endif

            <div class="promo-popup-copy">
                @if ($promotion->badge)
                    <span class="promo-badge">{{ $promotion->badge }}</span>
                @endif
                @if ($promotion->subtitle)
                    <p class="promo-subtitle">{{ $promotion->subtitle }}</p>
                @endif
                <h2 id="promo-popup-title">{{ $promotion->title }}</h2>
                @if ($promotion->description)
                    <p>{{ $promotion->description }}</p>
                @endif
                @if ($promotion->button_text && $promotion->button_link)
                    <a href="{{ $promotion->button_link }}" class="promo-popup-cta" data-promo-action>
                        {{ $promotion->button_text }}
                    </a>
                @endif
            </div>
        </div>
    </div>
@endif
