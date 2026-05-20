@extends('layouts.app')

@section('content')
    <section class="relative isolate overflow-hidden bg-emerald-950 text-white">
        <img
            src="{{ media_variant_url(setting('default_background'), 'hero', 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1600&q=85') }}"
            @if (media_srcset(setting('default_background'), ['card', 'large', 'hero'])) srcset="{{ media_srcset(setting('default_background'), ['card', 'large', 'hero']) }}" @endif
            alt="Không gian và món chay tại Đàn Hương Chay Hải Phòng"
            class="absolute inset-0 -z-20 h-full w-full object-cover"
            fetchpriority="high"
            sizes="100vw"
        >
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-emerald-950/90 via-emerald-950/72 to-emerald-900/35"></div>
        <div class="mx-auto grid min-h-[72vh] max-w-7xl gap-10 px-4 py-20 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:items-center lg:px-8">
            <div>
                <p class="eyebrow text-amber-100">Quán chay Hải Phòng</p>
                <h1 class="mt-5 max-w-3xl text-4xl font-semibold leading-tight sm:text-5xl lg:text-6xl">
                    Đàn Hương Chay - quán chay ngon, sạch và an yên tại Hải Phòng
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-emerald-50/90">
                    Ẩm thực chay fusion với menu sáng tạo, món chay dễ ăn, không gian nhẹ nhàng và dịch vụ đặt bàn thuận tiện cho gia đình, bạn bè, tiệc chay hoặc mâm cúng chay.
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('reservations.create') }}" class="btn-primary" data-track-event="click_reservation_cta" data-track-category="reservation" data-track-label="Local SEO hero reservation" data-facebook-event="ClickReservation">Đặt bàn ngay</a>
                    <a href="{{ route('menu.index') }}" class="btn-secondary">Xem thực đơn</a>
                    <a href="{{ route('local.vegetarian-catering-hai-phong') }}" class="btn-ghost" data-track-event="click_catering_cta" data-track-category="catering" data-track-label="Local SEO hero catering" data-facebook-event="ClickCatering">Đặt tiệc/mâm chay</a>
                    <a href="tel:{{ preg_replace('/\D+/', '', setting('hotline', '0947361515')) }}" class="btn-ghost" data-track-event="click_call" data-track-category="contact" data-track-label="Local SEO hero call" data-facebook-event="ClickCall">Gọi hotline</a>
                </div>
            </div>

            <div class="rounded-[2rem] border border-white/15 bg-white/12 p-5 shadow-2xl backdrop-blur">
                <dl class="grid gap-4 text-sm text-emerald-50 sm:grid-cols-2">
                    <div class="rounded-2xl bg-white/12 p-4">
                        <dt class="font-semibold text-amber-100">Địa chỉ</dt>
                        <dd class="mt-2 leading-6">{{ setting('address') }}</dd>
                    </div>
                    <div class="rounded-2xl bg-white/12 p-4">
                        <dt class="font-semibold text-amber-100">Đặt bàn</dt>
                        <dd class="mt-2 leading-6">{{ setting('hotline', setting('phone')) }}</dd>
                    </div>
                    <div class="rounded-2xl bg-white/12 p-4">
                        <dt class="font-semibold text-amber-100">Giờ mở cửa</dt>
                        <dd class="mt-2 leading-6">{{ setting('opening_hours') }}</dd>
                    </div>
                    <div class="rounded-2xl bg-white/12 p-4">
                        <dt class="font-semibold text-amber-100">Phù hợp</dt>
                        <dd class="mt-2 leading-6">Ăn gia đình, tiếp khách, đặt tiệc, mâm cúng chay</dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    <section class="section-block">
        <div class="grid gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:items-start">
            <div>
                <p class="eyebrow">Vì sao dễ được tìm thấy</p>
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">Một điểm hẹn cho người đang tìm nhà hàng chay Hải Phòng</h2>
                <p class="mt-5 leading-8 text-stone-700">
                    Khi khách tìm “quán chay Hải Phòng”, điều họ cần không chỉ là một địa chỉ. Họ muốn biết món có ngon không, quán có dễ đặt bàn không, không gian có hợp đi cùng gia đình không và có hỗ trợ các nhu cầu như tiệc chay hay mâm cúng chay không.
                </p>
                <p class="mt-4 leading-8 text-stone-700">
                    Trang này gom các thông tin quan trọng nhất để khách ra quyết định nhanh hơn, đồng thời giúp Google hiểu rõ Đàn Hương Chay đang phục vụ nhóm nhu cầu local nào.
                </p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <article class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-emerald-950">Món chay fusion</h3>
                    <p class="mt-3 leading-7 text-stone-700">Menu sáng tạo, kết hợp rau củ, nấm, hạt và kỹ thuật nấu hiện đại để món chay không đơn điệu.</p>
                </article>
                <article class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-emerald-950">Không gian an yên</h3>
                    <p class="mt-3 leading-7 text-stone-700">Phù hợp cho bữa ăn gia đình, gặp gỡ bạn bè, tiếp khách nhẹ nhàng hoặc các dịp muốn ăn thanh hơn.</p>
                </article>
                <article class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-emerald-950">Đặt bàn rõ ràng</h3>
                    <p class="mt-3 leading-7 text-stone-700">Form đặt bàn có kiểm tra khung giờ hoạt động, giúp khách chủ động chọn ca phù hợp.</p>
                </article>
                <article class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-emerald-950">Tiệc và mâm chay</h3>
                    <p class="mt-3 leading-7 text-stone-700">Có thể tư vấn đặt món theo nhóm, đặt tiệc chay hoặc mâm cúng chay theo nhu cầu thực tế.</p>
                </article>
            </div>
        </div>
    </section>

    @if ($featuredDishes->isNotEmpty())
        <section class="section-block pt-0">
            <div class="section-heading">
                <p class="eyebrow">Món chay ngon Hải Phòng</p>
                <h2>Món nổi bật nên thử tại Đàn Hương Chay</h2>
                <p>Các món được chọn để khách mới dễ bắt đầu, khách quen có thêm gợi ý và Google hiểu rõ thực đơn chay của quán.</p>
            </div>
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($featuredDishes as $dish)
                    @include('partials.dish-card', ['dish' => $dish])
                @endforeach
            </div>
        </section>
    @endif

    <section class="bg-emerald-950 text-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-16 sm:px-6 lg:grid-cols-[0.85fr_1.15fr] lg:items-center lg:px-8">
            <div>
                <p class="eyebrow text-amber-100">Đặt bàn nhanh</p>
                <h2 class="mt-3 text-3xl font-semibold sm:text-4xl">Bạn đang tìm quán chay cho hôm nay?</h2>
                <p class="mt-5 leading-8 text-emerald-50/80">
                    Đặt bàn trước giúp quán chuẩn bị chỗ ngồi, tư vấn món phù hợp và tránh các khung giờ bếp đã ngừng nhận món.
                </p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ route('reservations.create') }}" class="btn-primary" data-track-event="click_reservation_cta" data-track-category="reservation" data-track-label="Local SEO middle reservation" data-facebook-event="ClickReservation">Đặt bàn</a>
                    <a href="{{ route('contact') }}" class="btn-ghost">Liên hệ tư vấn</a>
                </div>
            </div>
            <div class="overflow-hidden rounded-3xl border border-white/15 bg-white/10 shadow-2xl">
                @if (setting('google_map_iframe'))
                    {!! setting('google_map_iframe') !!}
                @endif
            </div>
        </div>
    </section>

    <section class="section-block">
        <div class="grid gap-10 lg:grid-cols-[0.8fr_1.2fr]">
            <div>
                <p class="eyebrow">FAQ</p>
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">Câu hỏi thường gặp về quán chay Hải Phòng</h2>
                <p class="mt-5 leading-8 text-stone-700">
                    Đây là nhóm câu hỏi khách thường cần trước khi gọi bàn. Phần này cũng được xuất thành FAQ schema để hỗ trợ SEO local.
                </p>
            </div>
            <div class="space-y-4">
                @foreach ($faqs as $faq)
                    <details class="group rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm" @if ($loop->first) open @endif>
                        <summary class="cursor-pointer list-none text-lg font-semibold text-emerald-950">
                            {{ $faq['question'] }}
                        </summary>
                        <p class="mt-4 leading-8 text-stone-700">{{ $faq['answer'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>

    @if ($latestPosts->isNotEmpty())
        <section class="section-block pt-0">
            <div class="flex flex-col justify-between gap-5 sm:flex-row sm:items-end">
                <div class="max-w-2xl">
                    <p class="eyebrow">Đọc thêm</p>
                    <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">Kiến thức ăn chay và gợi ý chọn quán</h2>
                </div>
                <a href="{{ route('blog.index') }}" class="inline-flex font-semibold text-emerald-800 hover:text-emerald-950">Xem blog</a>
            </div>
            <div class="mt-10 grid gap-6 md:grid-cols-3">
                @foreach ($latestPosts as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            </div>
        </section>
    @endif
@endsection
