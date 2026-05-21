@extends('layouts.app')

@section('content')
    @php
        $hero = $banners->first();
        $heroImage = $hero?->image ?: setting('default_background');
        $signatureDishes = $featuredDishes->take(2);
        $signatureDish = $signatureDishes->first();
        $menuList = $featuredDishes->skip(2)->take(5);
    @endphp

    <section class="editorial-hero">
        <img
            src="{{ media_variant_url($heroImage, 'hero', 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1800&q=86') }}"
            @if (media_srcset($heroImage, ['card', 'large', 'hero'])) srcset="{{ media_srcset($heroImage, ['card', 'large', 'hero']) }}" @endif
            alt="{{ $hero?->localized('title') ?: localized_setting('restaurant_name', 'Đàn Hương Chay') }}"
            class="editorial-hero-bg"
            fetchpriority="high"
            sizes="100vw"
        >
        <div class="editorial-hero-overlay"></div>

        <div class="editorial-hero-inner">
            <div class="editorial-hero-copy">
                <p class="editorial-kicker">{{ $hero?->localized('subtitle') ?: 'Đàn Hương Chay' }}</p>
                <h1>{{ $hero?->localized('title') ?: 'Ẩm thực chay fusion trong không gian an yên' }}</h1>
                <p>{{ $hero?->localized('description') ?: 'Món chay Việt hiện đại, nguyên liệu tươi sạch và trải nghiệm dùng bữa nhẹ nhàng cho gia đình, bạn bè, tiệc chay và mâm cúng.' }}</p>
                <div class="editorial-hero-actions">
                    <a href="{{ localized_route('reservations.create') }}" class="btn-primary" data-track-event="click_reservation_cta" data-track-category="reservation" data-track-label="Home editorial hero">
                        Đặt bàn
                    </a>
                    <a href="{{ localized_route('menu.index') }}" class="btn-secondary">Xem thực đơn</a>
                    <a href="{{ localized_route('local.vegetarian-catering') }}" class="btn-ghost" data-track-event="click_catering_cta" data-track-category="catering" data-track-label="Home editorial hero">
                        Đặt tiệc / mâm chay
                    </a>
                </div>
            </div>

            <aside class="editorial-hero-card" aria-label="Thông tin đặt bàn nhanh">
                <p>Giữ chỗ hôm nay</p>
                <h2>Chọn cơ sở đặt bàn</h2>
                <div class="hero-branch-list">
                    @forelse ($branches->take(2) as $branch)
                        <a href="{{ localized_route('reservations.create') }}?branch={{ $branch->id }}" data-track-event="click_reservation_cta" data-track-category="reservation" data-track-label="Home hero {{ $branch->name }}">
                            <span>
                                <strong>{{ $branch->city ?: $branch->name }}</strong>
                                <small>{{ $branch->hotline ?: $branch->phone ?: setting('hotline', '0947 361 515') }}</small>
                            </span>
                            <b>Đặt bàn</b>
                        </a>
                    @empty
                        <a href="{{ localized_route('reservations.create') }}">
                            <span>
                                <strong>{{ localized_setting('restaurant_name', 'Đàn Hương Chay') }}</strong>
                                <small>{{ setting('hotline', '0947 361 515') }}</small>
                            </span>
                            <b>Đặt bàn</b>
                        </a>
                    @endforelse
                </div>
                <div class="hero-opening-note">
                    <span>Giờ mở cửa</span>
                    <strong>{{ setting('opening_hours', '09:00 - 21:00') }}</strong>
                </div>
            </aside>
        </div>
    </section>

    @if ($signatureDish || $featuredDishes->isNotEmpty())
        <section class="editorial-menu-section">
            <div class="editorial-section-head">
                <p class="editorial-kicker">Thực đơn đặc biệt</p>
                <h2>Món chay được yêu thích tại Đàn Hương</h2>
                <p>Những món signature được chọn để khách dễ gọi khi đến lần đầu: vị thanh, trình bày đẹp và phù hợp cho cả dùng riêng lẫn chia sẻ theo bàn.</p>
            </div>

            <div class="editorial-menu-layout">
                @if ($signatureDishes->isNotEmpty())
                    <div class="signature-dish-column">
                        @foreach ($signatureDishes as $dish)
                            <article class="signature-dish-card">
                                <a href="{{ localized_route('menu.show', ['slug' => $dish->localizedSlug()]) }}" data-track-event="click_dish_card" data-track-category="dish" data-track-label="{{ $dish->localized('name') }}">
                                    <img
                                        src="{{ media_variant_url($dish->image, 'card') }}"
                                        @if (media_srcset($dish->image, ['thumb', 'card', 'large'])) srcset="{{ media_srcset($dish->image, ['thumb', 'card', 'large']) }}" @endif
                                        alt="{{ $dish->localized('name') }}"
                                        loading="lazy"
                                        sizes="(max-width: 1024px) 100vw, 40vw"
                                    >
                                </a>
                                <div>
                                    <p>{{ $dish->category?->localized('name') ?: 'Signature food' }}</p>
                                    <h3>{{ $dish->localized('name') }}</h3>
                                    <span>{{ \Illuminate\Support\Str::limit($dish->localized('description'), 110) }}</span>
                                    <div class="signature-dish-actions">
                                        @if (show_dish_prices())
                                            <strong>{{ number_format($dish->sale_price ?: $dish->price, 0, ',', '.') }}đ</strong>
                                        @endif
                                        <a href="{{ localized_route('menu.show', ['slug' => $dish->localizedSlug()]) }}">Xem món</a>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif

                <div class="editorial-menu-list">
                    @foreach ($menuList as $dish)
                        <article>
                            <a href="{{ localized_route('menu.show', ['slug' => $dish->localizedSlug()]) }}" data-track-event="click_dish_card" data-track-category="dish" data-track-label="{{ $dish->localized('name') }}">
                                <span>
                                    <small>{{ $dish->category?->localized('name') }}</small>
                                    <strong>{{ $dish->localized('name') }}</strong>
                                    <em>{{ \Illuminate\Support\Str::limit($dish->localized('description'), 96) }}</em>
                                </span>
                                @if (show_dish_prices())
                                    <b>{{ number_format($dish->sale_price ?: $dish->price, 0, ',', '.') }}đ</b>
                                @endif
                            </a>
                        </article>
                    @endforeach
                    <a href="{{ localized_route('menu.index') }}" class="editorial-menu-more">Xem toàn bộ thực đơn</a>
                </div>
            </div>
        </section>
    @endif

    @if ($homePromotions->isNotEmpty())
        <section class="editorial-promo-section">
            <div class="editorial-section-head">
                <p class="editorial-kicker">Ưu đãi hôm nay</p>
                <h2>Gợi ý cho bữa chay trọn vị hơn</h2>
            </div>

            <div class="editorial-promo-grid">
                @foreach ($homePromotions as $promotion)
                    <article @class(['editorial-promo-card', 'is-large' => $loop->first]) style="--promo-accent: {{ $promotion->accent_color ?: '#047857' }}">
                        <img
                            src="{{ media_variant_url($promotion->image, $loop->first ? 'large' : 'card', 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=900&q=85') }}"
                            @if (media_srcset($promotion->image, ['thumb', 'card', 'large'])) srcset="{{ media_srcset($promotion->image, ['thumb', 'card', 'large']) }}" @endif
                            alt="{{ $promotion->localized('title') }}"
                            loading="lazy"
                            sizes="{{ $loop->first ? '(max-width: 1024px) 100vw, 52vw' : '(max-width: 1024px) 100vw, 28vw' }}"
                        >
                        <div>
                            @if ($promotion->localized('badge'))
                                <span>{{ $promotion->localized('badge') }}</span>
                            @endif
                            <h3>{{ $promotion->localized('title') }}</h3>
                            @if ($promotion->localized('description'))
                                <p>{{ $promotion->localized('description') }}</p>
                            @endif
                            @if ($promotion->localized('button_text') && $promotion->button_link)
                                <a href="{{ $promotion->button_link }}">{{ $promotion->localized('button_text') }}</a>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    @endif

    <section class="reason-section-visual">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
            <div>
                <p class="eyebrow">Vì sao chọn quán</p>
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">Một bữa chay tử tế bắt đầu từ nguyên liệu và sự chú tâm</h2>
                <p class="mt-5 leading-8 text-stone-700">Đàn Hương Chay ưu tiên món ăn vừa ngon miệng, vừa nhẹ nhàng cho cơ thể. Mỗi món được nấu để thực khách cảm nhận vị ngọt của rau củ và sự cân bằng trong từng phần ăn.</p>
                <a href="{{ localized_route('about') }}" class="mt-7 inline-flex font-semibold text-emerald-800 hover:text-emerald-950">Tìm hiểu câu chuyện quán</a>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="reason-card has-card-bg" style="--card-bg: url('https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=900&q=80')">
                    <h3>Nguyên liệu sạch</h3>
                    <p>Rau củ, nấm và hạt được chọn lọc mỗi ngày từ nhà cung cấp đáng tin cậy.</p>
                </div>
                <div class="reason-card has-card-bg" style="--card-bg: url('https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=900&q=80')">
                    <h3>Nêm nếm tinh tế</h3>
                    <p>Giảm dầu, giảm vị gắt, giữ hậu vị thanh và hương thơm tự nhiên.</p>
                </div>
                <div class="reason-card has-card-bg" style="--card-bg: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=900&q=80')">
                    <h3>Không gian yên</h3>
                    <p>Ánh sáng ấm, chất liệu gỗ và mảng xanh tạo cảm giác thư giãn.</p>
                </div>
                <div class="reason-card has-card-bg" style="--card-bg: url('https://images.unsplash.com/photo-1556745757-8d76bdb6984b?auto=format&fit=crop&w=900&q=80')">
                    <h3>Đặt bàn nhanh</h3>
                    <p>Form đặt bàn đơn giản, chọn cơ sở rõ ràng và phản hồi xác nhận dễ theo dõi.</p>
                </div>
            </div>
        </div>
    </section>

    @if ($homeGalleryImages->isNotEmpty())
        <section class="editorial-gallery-section">
            <div class="editorial-section-head">
                <p class="editorial-kicker">Không gian</p>
                <h2>Những góc yên cho một bữa chay chậm lại</h2>
                <p>Ảnh không gian được chọn lọc theo từng cơ sở, giúp khách cảm nhận rõ hơn trước khi đặt bàn.</p>
            </div>
            <div class="home-gallery-grid mt-10">
                @foreach ($homeGalleryImages as $image)
                    <article @class(['home-gallery-card', 'is-large' => $loop->first])>
                        <img
                            src="{{ media_variant_url($image->image, $loop->first ? 'large' : 'card') }}"
                            @if (media_srcset($image->image, ['thumb', 'card', 'large'])) srcset="{{ media_srcset($image->image, ['thumb', 'card', 'large']) }}" @endif
                            alt="{{ $image->localized('alt_text', $image->localized('title')) }}"
                            loading="lazy"
                            sizes="{{ $loop->first ? '(max-width: 1024px) 100vw, 50vw' : '(max-width: 1024px) 100vw, 25vw' }}"
                        >
                        <div>
                            <h3>{{ $image->localized('title') }}</h3>
                            @if ($image->localized('description'))
                                <p>{{ $image->localized('description') }}</p>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-8 text-center">
                <a href="{{ localized_route('gallery.index') }}" class="btn-primary">Xem không gian quán</a>
            </div>
        </section>
    @endif

    <section class="section-block">
        <div class="section-heading">
            <p class="eyebrow">Cảm nhận</p>
            <h2>Khách hàng nói gì</h2>
        </div>
        @if ($testimonials->isNotEmpty())
            <div class="testimonial-slider mt-10" data-testimonial-slider>
                <div class="testimonial-viewport">
                    <div class="testimonial-track" data-testimonial-track>
                        @foreach ($testimonials as $testimonial)
                            <figure class="testimonial-card" data-testimonial-slide>
                                <div class="flex items-center gap-3">
                                    @if ($testimonial->avatar)
                                        <img src="{{ media_variant_url($testimonial->avatar, 'thumb') }}" alt="{{ $testimonial->name }}" loading="lazy">
                                    @else
                                        <span class="testimonial-avatar">{{ mb_substr($testimonial->name, 0, 1) }}</span>
                                    @endif
                                    <figcaption>
                                        <strong>{{ $testimonial->name }}</strong>
                                        @if ($testimonial->localized('role'))
                                            <span>{{ $testimonial->localized('role') }}</span>
                                        @endif
                                    </figcaption>
                                </div>
                                <div class="testimonial-stars" aria-label="{{ $testimonial->rating }} / 5">
                                    @for ($star = 1; $star <= 5; $star++)
                                        <span @class(['is-muted' => $star > $testimonial->rating])>★</span>
                                    @endfor
                                </div>
                                <blockquote>“{{ $testimonial->localized('content') }}”</blockquote>
                            </figure>
                        @endforeach
                    </div>
                </div>

                <div class="testimonial-controls">
                    <button type="button" data-testimonial-prev aria-label="Review trước">‹</button>
                    <div class="testimonial-dots" data-testimonial-dots>
                        @foreach ($testimonials as $testimonial)
                            <button type="button" data-testimonial-dot data-index="{{ $loop->index }}" aria-label="Review {{ $loop->iteration }}" @class(['is-active' => $loop->first])></button>
                        @endforeach
                    </div>
                    <button type="button" data-testimonial-next aria-label="Review sau">›</button>
                </div>
            </div>
        @endif
    </section>

    <section class="section-block pt-0">
        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
            <div class="max-w-2xl">
                <p class="eyebrow">Blog mới nhất</p>
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">Ăn chay, sống xanh và chăm sóc sức khỏe</h2>
            </div>
            <a href="{{ localized_route('blog.index') }}" class="inline-flex font-semibold text-emerald-800 hover:text-emerald-950">Xem tất cả bài viết</a>
        </div>
        <div class="mt-10 grid gap-6 md:grid-cols-3">
            @foreach ($latestPosts as $post)
                @include('partials.post-card', ['post' => $post])
            @endforeach
        </div>
    </section>
@endsection
