<article class="dish-card group overflow-hidden rounded-2xl border border-emerald-900/10 bg-white shadow-sm">
    <a href="{{ localized_route('menu.show', ['slug' => $dish->localizedSlug()]) }}" class="block" data-track-event="click_dish_card" data-track-category="dish" data-track-label="{{ $dish->localized('name') }}" data-facebook-event="ClickDish">
        <div class="aspect-[4/3] overflow-hidden bg-emerald-50">
            <img
                src="{{ media_variant_url($dish->image, 'card') }}"
                @if (media_srcset($dish->image, ['thumb', 'card', 'large'])) srcset="{{ media_srcset($dish->image, ['thumb', 'card', 'large']) }}" @endif
                alt="{{ $dish->localized('name') }}"
                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                loading="lazy"
                sizes="(max-width: 640px) 100vw, (max-width: 1024px) 50vw, 33vw"
            >
        </div>
    </a>
    <div class="p-5">
        <div class="flex items-start justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-amber-700">{{ $dish->category->localized('name') }}</p>
                <h3 class="mt-2 text-lg font-semibold text-emerald-950">
                    <a href="{{ localized_route('menu.show', ['slug' => $dish->localizedSlug()]) }}" class="hover:text-emerald-700" data-track-event="click_dish_card" data-track-category="dish" data-track-label="{{ $dish->localized('name') }}" data-facebook-event="ClickDish">{{ $dish->localized('name') }}</a>
                </h3>
            </div>
            @if (show_dish_prices())
                <div class="dish-price-pill shrink-0">
                    <span>{{ number_format($dish->sale_price ?: $dish->price, 0, ',', '.') }}đ</span>
                    @if ($dish->sale_price)
                        <del>{{ number_format($dish->price, 0, ',', '.') }}đ</del>
                    @endif
                </div>
            @endif
        </div>
        <p class="mt-3 line-clamp-2 text-sm leading-6 text-stone-600">{{ $dish->localized('description') }}</p>
    </div>
</article>
