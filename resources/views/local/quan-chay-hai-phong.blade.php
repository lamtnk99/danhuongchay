@extends('layouts.app')

@section('content')
    <section class="relative isolate overflow-hidden bg-emerald-950 text-white">
        <img
            src="{{ media_variant_url(setting('default_background'), 'hero', 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=1600&q=85') }}"
            @if (media_srcset(setting('default_background'), ['card', 'large', 'hero'])) srcset="{{ media_srcset(setting('default_background'), ['card', 'large', 'hero']) }}" @endif
            alt="{{ is_english() ? 'Vegetarian dishes and dining space at Dan Huong Chay Hai Phong' : 'Không gian và món chay tại Đàn Hương Chay Hải Phòng' }}"
            class="absolute inset-0 -z-20 h-full w-full object-cover"
            fetchpriority="high"
            sizes="100vw"
        >
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-emerald-950/90 via-emerald-950/72 to-emerald-900/35"></div>
        <div class="mx-auto grid min-h-[72vh] max-w-7xl gap-10 px-4 py-20 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:items-center lg:px-8">
            <div>
                <p class="eyebrow text-amber-100">{{ is_english() ? 'Vegetarian restaurant in Hai Phong' : 'Quán chay Hải Phòng' }}</p>
                <h1 class="mt-5 max-w-3xl text-4xl font-semibold leading-tight sm:text-5xl lg:text-6xl">
                    {{ is_english() ? 'Dan Huong Chay - gentle vegetarian dining in Hai Phong' : 'Đàn Hương Chay - quán chay ngon, sạch và an yên tại Hải Phòng' }}
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-emerald-50/90">
                    {{ is_english() ? 'Vegetarian fusion cuisine with approachable dishes, a calm dining space and convenient booking for families, friends, catering and ceremonial vegetarian trays.' : 'Ẩm thực chay fusion với menu sáng tạo, món chay dễ ăn, không gian nhẹ nhàng và dịch vụ đặt bàn thuận tiện cho gia đình, bạn bè, tiệc chay hoặc mâm cúng chay.' }}
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ localized_route('reservations.create') }}" class="btn-primary" data-track-event="click_reservation_cta" data-track-category="reservation" data-track-label="Local SEO hero reservation" data-facebook-event="ClickReservation">{{ is_english() ? 'Book now' : 'Đặt bàn ngay' }}</a>
                    <a href="{{ localized_route('menu.index') }}" class="btn-secondary">{{ is_english() ? 'View menu' : 'Xem thực đơn' }}</a>
                    <a href="{{ localized_route('local.vegetarian-catering-hai-phong') }}" class="btn-ghost" data-track-event="click_catering_cta" data-track-category="catering" data-track-label="Local SEO hero catering" data-facebook-event="ClickCatering">{{ is_english() ? 'Catering / feast trays' : 'Đặt tiệc/mâm chay' }}</a>
                    <a href="tel:{{ preg_replace('/\D+/', '', setting('hotline', '0947361515')) }}" class="btn-ghost" data-track-event="click_call" data-track-category="contact" data-track-label="Local SEO hero call" data-facebook-event="ClickCall">{{ is_english() ? 'Call hotline' : 'Gọi hotline' }}</a>
                </div>
            </div>

            <div class="rounded-[2rem] border border-white/15 bg-white/12 p-5 shadow-2xl backdrop-blur">
                <dl class="grid gap-4 text-sm text-emerald-50 sm:grid-cols-2">
                    <div class="rounded-2xl bg-white/12 p-4">
                        <dt class="font-semibold text-amber-100">{{ is_english() ? 'Address' : 'Địa chỉ' }}</dt>
                        <dd class="mt-2 leading-6">{{ setting('address') }}</dd>
                    </div>
                    <div class="rounded-2xl bg-white/12 p-4">
                        <dt class="font-semibold text-amber-100">{{ is_english() ? 'Reservation' : 'Đặt bàn' }}</dt>
                        <dd class="mt-2 leading-6">{{ setting('hotline', setting('phone')) }}</dd>
                    </div>
                    <div class="rounded-2xl bg-white/12 p-4">
                        <dt class="font-semibold text-amber-100">{{ is_english() ? 'Opening hours' : 'Giờ mở cửa' }}</dt>
                        <dd class="mt-2 leading-6">{{ setting('opening_hours') }}</dd>
                    </div>
                    <div class="rounded-2xl bg-white/12 p-4">
                        <dt class="font-semibold text-amber-100">{{ is_english() ? 'Best for' : 'Phù hợp' }}</dt>
                        <dd class="mt-2 leading-6">{{ is_english() ? 'Family meals, guest meals, catering, vegetarian feast trays' : 'Ăn gia đình, tiếp khách, đặt tiệc, mâm cúng chay' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </section>

    <section class="section-block">
        <div class="grid gap-10 lg:grid-cols-[0.85fr_1.15fr] lg:items-start">
            <div>
                <p class="eyebrow">{{ is_english() ? 'A peaceful dining stop' : 'Một điểm hẹn an yên' }}</p>
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">{{ is_english() ? 'For gentle vegetarian meals in Hai Phong' : 'Dành cho những bữa chay nhẹ nhàng tại Hải Phòng' }}</h2>
                <p class="mt-5 leading-8 text-stone-700">
                    {{ is_english() ? 'Choosing a vegetarian restaurant is not only about an address. A pleasant meal needs balanced flavors, a calm enough room, helpful service and clear booking for family meals, friends or special occasions.' : 'Khi chọn một quán chay, điều quan trọng không chỉ là địa chỉ. Một bữa ăn dễ chịu cần món vừa miệng, không gian đủ yên, nhân viên dễ trao đổi và cách đặt bàn rõ ràng cho gia đình, bạn bè hoặc những dịp cần chuẩn bị kỹ.' }}
                </p>
                <p class="mt-4 leading-8 text-stone-700">
                    {{ is_english() ? 'Dan Huong Chay keeps a gentle vegetarian cooking style, fresh ingredients and warm service so each visit feels a little lighter.' : 'Đàn Hương Chay giữ tinh thần nấu chay thanh vị, ưu tiên nguyên liệu tươi và cách phục vụ ấm áp để mỗi lần ghé quán đều nhẹ nhàng hơn một chút.' }}
                </p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <article class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-emerald-950">{{ is_english() ? 'Vegetarian fusion dishes' : 'Món chay fusion' }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ is_english() ? 'Creative dishes combining vegetables, mushrooms, grains and modern cooking so vegetarian food feels fresh and satisfying.' : 'Menu sáng tạo, kết hợp rau củ, nấm, hạt và kỹ thuật nấu hiện đại để món chay không đơn điệu.' }}</p>
                </article>
                <article class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-emerald-950">{{ is_english() ? 'Peaceful space' : 'Không gian an yên' }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ is_english() ? 'Suitable for family meals, meeting friends, hosting guests or days when you want something lighter.' : 'Phù hợp cho bữa ăn gia đình, gặp gỡ bạn bè, tiếp khách nhẹ nhàng hoặc các dịp muốn ăn thanh hơn.' }}</p>
                </article>
                <article class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-emerald-950">{{ is_english() ? 'Clear booking' : 'Đặt bàn rõ ràng' }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ is_english() ? 'Choose your preferred date and time, then our team confirms so your table can be prepared properly.' : 'Khách chọn trước ngày giờ ghé quán, đội ngũ sẽ xác nhận lại để bàn ăn được chuẩn bị gọn gàng.' }}</p>
                </article>
                <article class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-emerald-950">{{ is_english() ? 'Catering and feast trays' : 'Tiệc và mâm chay' }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ is_english() ? 'The restaurant can suggest dishes for groups, vegetarian catering or ceremonial trays based on your needs.' : 'Có thể tư vấn đặt món theo nhóm, đặt tiệc chay hoặc mâm cúng chay theo nhu cầu thực tế.' }}</p>
                </article>
            </div>
        </div>
    </section>

    @if ($featuredDishes->isNotEmpty())
        <section class="section-block pt-0">
            <div class="section-heading">
                <p class="eyebrow">{{ is_english() ? 'Vegetarian dishes in Hai Phong' : 'Món chay ngon Hải Phòng' }}</p>
                <h2>{{ is_english() ? 'Featured dishes to try at Dan Huong Chay' : 'Món nổi bật nên thử tại Đàn Hương Chay' }}</h2>
                <p>{{ is_english() ? 'A few approachable dishes for first-time guests, familiar enough to share with family around the table.' : 'Một vài món dễ gọi cho lần đầu ghé quán, cũng đủ quen để khách đi cùng gia đình có thêm lựa chọn trên bàn ăn.' }}</p>
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
                <p class="eyebrow text-amber-100">{{ is_english() ? 'Quick reservation' : 'Đặt bàn nhanh' }}</p>
                <h2 class="mt-3 text-3xl font-semibold sm:text-4xl">{{ is_english() ? 'Looking for a vegetarian table today?' : 'Bạn đang tìm quán chay cho hôm nay?' }}</h2>
                <p class="mt-5 leading-8 text-emerald-50/80">
                    {{ is_english() ? 'Booking ahead helps the restaurant prepare your seats, suggest suitable dishes and avoid times when the kitchen has stopped taking orders.' : 'Đặt bàn trước giúp quán chuẩn bị chỗ ngồi, tư vấn món phù hợp và tránh các khung giờ bếp đã ngừng nhận món.' }}
                </p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ localized_route('reservations.create') }}" class="btn-primary" data-track-event="click_reservation_cta" data-track-category="reservation" data-track-label="Local SEO middle reservation" data-facebook-event="ClickReservation">{{ is_english() ? 'Book a table' : 'Đặt bàn' }}</a>
                    <a href="{{ localized_route('contact') }}" class="btn-ghost">{{ is_english() ? 'Ask for advice' : 'Liên hệ tư vấn' }}</a>
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
                <p class="eyebrow">{{ is_english() ? 'Questions' : 'Câu hỏi thường gặp' }}</p>
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">{{ is_english() ? 'Common questions about our Hai Phong vegetarian restaurant' : 'Câu hỏi thường gặp về quán chay Hải Phòng' }}</h2>
                <p class="mt-5 leading-8 text-stone-700">
                    {{ is_english() ? 'A few things guests often ask before booking, ordering dishes or preparing vegetarian catering at the Hai Phong branch.' : 'Một vài điều khách thường hỏi trước khi đặt bàn, gọi món hoặc chuẩn bị tiệc chay tại cơ sở Hải Phòng.' }}
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
                    <p class="eyebrow">{{ is_english() ? 'Read more' : 'Đọc thêm' }}</p>
                    <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">{{ is_english() ? 'Vegetarian notes and dining suggestions' : 'Kiến thức ăn chay và gợi ý chọn quán' }}</h2>
                </div>
                <a href="{{ localized_route('blog.index') }}" class="inline-flex font-semibold text-emerald-800 hover:text-emerald-950">{{ is_english() ? 'View blog' : 'Xem blog' }}</a>
            </div>
            <div class="mt-10 grid gap-6 md:grid-cols-3">
                @foreach ($latestPosts as $post)
                    @include('partials.post-card', ['post' => $post])
                @endforeach
            </div>
        </section>
    @endif
@endsection
