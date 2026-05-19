@extends('layouts.app')

@section('content')
    <section class="hero-section relative isolate overflow-hidden">
        @php
            $hero = $banners->first();
        @endphp
        <img
            src="{{ media_url($hero?->image, media_url(setting('default_background'), 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1600&q=85')) }}"
            alt="{{ $hero?->title ?: 'Mâm món chay nhiều rau củ tại Đàn Hương Chay' }}"
            class="absolute inset-0 -z-20 h-full w-full object-cover"
            fetchpriority="high"
        >
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-emerald-950/82 via-emerald-950/58 to-amber-950/18"></div>
        <div class="mx-auto flex min-h-[74vh] max-w-7xl items-center px-4 py-20 sm:px-6 lg:px-8">
            <div class="max-w-3xl text-white">
                <p class="text-sm font-semibold uppercase tracking-[0.32em] text-amber-100">{{ $hero?->subtitle ?: setting('restaurant_name', 'Đàn Hương Chay') }}</p>
                <h1 class="mt-5 max-w-2xl text-4xl font-semibold leading-tight sm:text-5xl lg:text-6xl">
                    {{ $hero?->title ?: setting('slogan', 'Ẩm thực chay thanh lành - Nuôi dưỡng thân tâm') }}
                </h1>
                <p class="mt-6 max-w-xl text-lg leading-8 text-emerald-50/88">
                    {{ $hero?->description ?: setting('short_description', 'Món chay Việt hiện đại, nguyên liệu tươi sạch, hương vị nhẹ nhàng và không gian yên tĩnh cho những bữa ăn an nhiên.') }}
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ $hero?->button_link ?: route('menu.index') }}" class="btn-primary">{{ $hero?->button_text ?: 'Xem thực đơn' }}</a>
                    <a href="{{ route('reservations.create') }}" class="btn-secondary">Đặt bàn</a>
                    <a href="{{ route('local.vegetarian-catering-hai-phong') }}" class="btn-ghost" data-track-event="click_catering_cta" data-track-label="Hero đặt mâm cỗ">Đặt mâm cỗ</a>
                </div>
            </div>
        </div>
    </section>

    @if ($homePromotions->isNotEmpty())
        <section class="promo-showcase">
            <div class="promo-shell">
                <div class="promo-head">
                    <div>
                        <p class="promo-kicker">Ưu đãi hôm nay</p>
                        <h2>Món ngon đang được gợi ý</h2>
                        <p>Các chương trình nổi bật, combo theo mùa và thông báo đặc biệt từ Đàn Hương Chay.</p>
                    </div>
                    <a href="{{ route('menu.index') }}" class="promo-head-link">Xem thực đơn</a>
                </div>

                <div @class([
                    'promo-grid',
                    'promo-grid-single' => $homePromotions->count() === 1,
                    'promo-grid-two' => $homePromotions->count() === 2,
                    'promo-grid-three' => $homePromotions->count() === 3,
                    'promo-grid-four' => $homePromotions->count() >= 4,
                ])>
                    @foreach ($homePromotions as $promotion)
                        <article @class(['promo-card', 'is-featured' => $loop->first && $homePromotions->count() > 2]) style="--promo-accent: {{ $promotion->accent_color ?: '#047857' }}">
                            <img
                                src="{{ media_url($promotion->image, 'https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&w=900&q=85') }}"
                                alt="{{ $promotion->title }}"
                                loading="lazy"
                            >
                            <div class="promo-card-copy">
                                @if ($promotion->badge)
                                    <span class="promo-badge">{{ $promotion->badge }}</span>
                                @endif
                                @if ($promotion->subtitle)
                                    <p class="promo-subtitle">{{ $promotion->subtitle }}</p>
                                @endif
                                <h3>{{ $promotion->title }}</h3>
                                @if ($promotion->description)
                                    <p>{{ $promotion->description }}</p>
                                @endif
                                @if ($promotion->button_text && $promotion->button_link)
                                    <a href="{{ $promotion->button_link }}" class="promo-link">{{ $promotion->button_text }}</a>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="section-block">
        <div class="section-heading">
            <p class="eyebrow">Món nổi bật</p>
            <h2>Những món chay được yêu thích</h2>
            <p>Thực đơn được xây dựng từ rau củ theo mùa, nấm tươi, đậu hũ và gia vị nhẹ để giữ trọn vị tự nhiên.</p>
        </div>
        <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($featuredDishes as $dish)
                @include('partials.dish-card', ['dish' => $dish])
            @endforeach
        </div>
    </section>

    <section class="reason-section-visual">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-16 sm:px-6 lg:grid-cols-[0.9fr_1.1fr] lg:px-8">
            <div>
                <p class="eyebrow">Vì sao chọn quán</p>
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">Một bữa chay tử tế bắt đầu từ nguyên liệu và sự chú tâm</h2>
                <p class="mt-5 leading-8 text-stone-700">
                    Đàn Hương Chay ưu tiên món ăn vừa ngon miệng, vừa nhẹ nhàng cho cơ thể. Mỗi món được nấu để thực khách cảm nhận được vị ngọt của rau củ và sự cân bằng trong từng phần ăn.
                </p>
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
                    <p>Form đặt bàn đơn giản, lưu thông tin và phản hồi xác nhận rõ ràng.</p>
                </div>
            </div>
        </div>
    </section>

    @if ($homeGalleryImages->isNotEmpty())
        <section class="gallery-preview-section">
            <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
                <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                    <div class="max-w-2xl">
                        <p class="eyebrow">Không gian</p>
                        <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">Góc nhỏ để bữa chay chậm lại</h2>
                        <p class="mt-4 leading-8 text-stone-700">Một vài góc quán được chọn lọc từ bộ ảnh không gian, đủ ấm để đi cùng gia đình và đủ yên cho những cuộc hẹn nhẹ.</p>
                    </div>
                    <a href="{{ route('gallery.index') }}" class="inline-flex font-semibold text-emerald-800 hover:text-emerald-950">Xem không gian</a>
                </div>

                <div class="home-gallery-grid mt-10">
                    @foreach ($homeGalleryImages as $image)
                        <article @class(['home-gallery-card', 'is-large' => $loop->first])>
                            <img src="{{ media_url($image->image) }}" alt="{{ $image->alt_text ?: $image->title }}" loading="lazy">
                            <div>
                                <h3>{{ $image->title }}</h3>
                                @if ($image->description)
                                    <p>{{ $image->description }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
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
                                        <img src="{{ media_url($testimonial->avatar) }}" alt="{{ $testimonial->name }}" loading="lazy">
                                    @else
                                        <span class="testimonial-avatar">{{ mb_substr($testimonial->name, 0, 1) }}</span>
                                    @endif
                                    <figcaption>
                                        <strong>{{ $testimonial->name }}</strong>
                                        @if ($testimonial->role)
                                            <span>{{ $testimonial->role }}</span>
                                        @endif
                                    </figcaption>
                                </div>
                                <div class="testimonial-stars" aria-label="{{ $testimonial->rating }} trên 5 sao">
                                    @for ($star = 1; $star <= 5; $star++)
                                        <span @class(['is-muted' => $star > $testimonial->rating])>★</span>
                                    @endfor
                                </div>
                                <blockquote>“{{ $testimonial->content }}”</blockquote>
                            </figure>
                        @endforeach
                    </div>
                </div>

                <div class="testimonial-controls">
                    <button type="button" data-testimonial-prev aria-label="Review trước">‹</button>
                    <div class="testimonial-dots" data-testimonial-dots>
                        @foreach ($testimonials as $testimonial)
                            <button
                                type="button"
                                data-testimonial-dot
                                data-index="{{ $loop->index }}"
                                aria-label="Xem review {{ $loop->iteration }}"
                                @class(['is-active' => $loop->first])
                            ></button>
                        @endforeach
                    </div>
                    <button type="button" data-testimonial-next aria-label="Review tiếp theo">›</button>
                </div>
            </div>
        @else
            <p class="mt-8 text-center text-stone-600">Chưa có review hiển thị.</p>
        @endif
    </section>

    <section class="section-block pt-0">
        <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
            <div class="max-w-2xl">
                <p class="eyebrow">Blog mới nhất</p>
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">Ăn chay, sống xanh và chăm sóc sức khỏe</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="inline-flex font-semibold text-emerald-800 hover:text-emerald-950">Xem tất cả bài viết</a>
        </div>
        <div class="mt-10 grid gap-6 md:grid-cols-3">
            @foreach ($latestPosts as $post)
                @include('partials.post-card', ['post' => $post])
            @endforeach
        </div>
    </section>

    <section class="section-block pt-0">
        <div class="rounded-[2rem] border border-emerald-900/10 bg-white p-6 shadow-sm sm:p-8 lg:flex lg:items-center lg:justify-between lg:gap-10">
            <div class="max-w-3xl">
                <p class="eyebrow">Quán chay Hải Phòng</p>
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">Tìm một bữa chay ngon, sạch và dễ đặt bàn tại Hải Phòng?</h2>
                <p class="mt-4 leading-8 text-stone-700">
                    Xem trang tổng hợp dành riêng cho khách đang tìm quán chay Hải Phòng, có thông tin địa chỉ, món nổi bật, FAQ, đặt bàn, tiệc chay và mâm cúng chay.
                </p>
            </div>
            <div class="mt-6 flex shrink-0 flex-wrap gap-3 lg:mt-0">
                <a href="{{ route('local.vegetarian-restaurant-hai-phong') }}" class="btn-primary justify-center">Xem gợi ý quán chay</a>
                <a href="{{ route('local.vegetarian-catering-hai-phong') }}" class="btn-secondary justify-center" data-track-event="click_catering_cta" data-track-category="catering" data-track-label="Home local SEO block" data-facebook-event="ClickCatering">Đặt tiệc/mâm chay</a>
            </div>
        </div>
    </section>
@endsection
