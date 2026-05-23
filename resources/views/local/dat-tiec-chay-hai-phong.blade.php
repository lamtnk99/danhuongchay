@extends('layouts.app')

@section('content')
    @php
        $fitItems = is_english()
            ? ['Family ceremonial trays', 'Vegetarian birthday or anniversary meals', 'Guest meals', 'Group dish orders', 'Weekend vegetarian sets']
            : ['Mâm cúng chay gia đình', 'Tiệc chay sinh nhật, kỷ niệm', 'Bữa chay tiếp khách', 'Đặt món theo nhóm', 'Set món chay cuối tuần'];
    @endphp

    <section class="relative isolate overflow-hidden bg-stone-950 text-white">
        <img
            src="{{ media_variant_url(setting('default_background'), 'hero', 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1600&q=85') }}"
            @if (media_srcset(setting('default_background'), ['card', 'large', 'hero'])) srcset="{{ media_srcset(setting('default_background'), ['card', 'large', 'hero']) }}" @endif
            alt="{{ is_english() ? 'Vegetarian catering table at Dan Huong Chay Hai Phong' : 'Bàn tiệc chay trang trọng tại Đàn Hương Chay Hải Phòng' }}"
            class="absolute inset-0 -z-20 h-full w-full object-cover"
            fetchpriority="high"
            sizes="100vw"
        >
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-emerald-950/92 via-emerald-950/72 to-amber-900/35"></div>
        <div class="mx-auto grid min-h-[70vh] max-w-7xl gap-10 px-4 py-20 sm:px-6 lg:grid-cols-[1fr_0.9fr] lg:items-center lg:px-8">
            <div>
                <p class="eyebrow text-amber-100">{{ is_english() ? 'Vegetarian catering in Hai Phong' : 'Đặt tiệc chay Hải Phòng' }}</p>
                <h1 class="mt-5 max-w-3xl text-4xl font-semibold leading-tight sm:text-5xl lg:text-6xl">
                    {{ is_english() ? 'Thoughtful vegetarian catering and feast trays with gentle flavors' : 'Đặt tiệc chay và mâm cúng chay chỉn chu, nhẹ vị, trang trọng' }}
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-emerald-50/90">
                    {{ is_english() ? 'Dan Huong Chay suggests menus by group size, taste and occasion: family gatherings, guest meals, birthdays, ceremonies or vegetarian tables that need extra care.' : 'Đàn Hương Chay tư vấn thực đơn theo số người, khẩu vị và mục đích sử dụng: tiệc gia đình, tiếp khách, sinh nhật, lễ cúng hoặc những bữa chay cần chuẩn bị kỹ hơn.' }}
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ localized_route('reservations.create') }}" class="btn-primary" data-track-event="click_catering_reservation" data-track-category="catering" data-track-label="Catering hero reservation" data-facebook-event="ClickCatering">{{ is_english() ? 'Send catering request' : 'Gửi yêu cầu đặt tiệc' }}</a>
                    <a href="tel:{{ preg_replace('/\D+/', '', setting('hotline', '0947361515')) }}" class="btn-ghost" data-track-event="click_call" data-track-category="contact" data-track-label="Catering hero call" data-facebook-event="ClickCall">{{ is_english() ? 'Call for advice' : 'Gọi tư vấn' }}</a>
                </div>
            </div>

            <div class="rounded-[2rem] border border-white/15 bg-white/12 p-5 shadow-2xl backdrop-blur">
                <p class="text-sm font-semibold uppercase tracking-[0.22em] text-amber-100">{{ is_english() ? 'Best for' : 'Phù hợp cho' }}</p>
                <div class="mt-5 grid gap-3">
                    @foreach ($fitItems as $item)
                        <div class="rounded-2xl bg-white/12 px-4 py-3 text-sm font-semibold text-emerald-50">{{ $item }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <div class="section-block pb-0">
        @include('partials.contextual-cta', [
            'context' => 'catering',
            'kicker' => is_english() ? 'Advice by occasion' : 'Tư vấn theo dịp',
            'title' => is_english() ? 'Need a vegetarian feast tray or catering today?' : 'Cần mâm cúng chay hay tiệc chay trong hôm nay?',
            'text' => is_english() ? 'Send your request first so the restaurant can suggest dish count, flavor, preparation time and plating style for your family or group.' : 'Gửi yêu cầu trước để quán tư vấn số món, khẩu vị, thời gian chuẩn bị và cách bày món phù hợp với gia đình hoặc nhóm khách.',
            'primaryLabel' => is_english() ? 'Send catering request' : 'Gửi yêu cầu đặt tiệc',
            'secondaryLabel' => is_english() ? 'View suggested dishes' : 'Xem món gợi ý',
            'secondaryUrl' => '#mon-goi-y',
            'trackLabel' => 'Catering landing smart CTA',
        ])
    </div>

    <section class="section-block">
        <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
            <div>
                <p class="eyebrow">{{ is_english() ? 'How we advise' : 'Cách quán tư vấn' }}</p>
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">{{ is_english() ? 'It is not about ordering more dishes, but the right dishes' : 'Không cần gọi thật nhiều món, cần gọi đúng món' }}</h2>
                <p class="mt-5 leading-8 text-stone-700">
                    {{ is_english() ? 'A beautiful vegetarian tray or catering table is about balance: main dishes, soup or hotpot, rolls or starters, vegetables and mushrooms, plus light drinks or dessert. We suggest by guest count and mealtime to avoid too much, too little or repeated flavors.' : 'Một mâm chay hoặc tiệc chay đẹp nằm ở sự cân đối: có món chính, món canh/lẩu, món cuốn hoặc khai vị, món rau/nấm và phần đồ uống hoặc tráng miệng nhẹ. Quán sẽ tư vấn theo số khách và thời gian dùng bữa để tránh thừa, thiếu hoặc món bị trùng vị.' }}
                </p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <article class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-emerald-950">{{ is_english() ? 'Neat and respectful' : 'Gọn và trang trọng' }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ is_english() ? 'Clear dish layout and balanced colors, suitable for ceremonial trays or family tables.' : 'Bố cục món rõ ràng, màu sắc hài hòa, phù hợp mâm cúng hoặc bàn tiệc gia đình.' }}</p>
                </article>
                <article class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-emerald-950">{{ is_english() ? 'Easy to enjoy' : 'Dễ ăn cho nhiều người' }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ is_english() ? 'Gentle seasoning, not too oily, suitable even for guests new to vegetarian food.' : 'Ưu tiên vị thanh, không quá nặng dầu, phù hợp cả người mới ăn chay.' }}</p>
                </article>
                <article class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-emerald-950">{{ is_english() ? 'Sized for your group' : 'Tư vấn theo số khách' }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ is_english() ? 'Dish quantities can be suggested for 2-4 guests, 5-8 guests or larger groups.' : 'Gợi ý số lượng món theo 2-4 người, 5-8 người hoặc nhóm đông hơn.' }}</p>
                </article>
                <article class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-emerald-950">{{ is_english() ? 'Book ahead' : 'Đặt trước để chuẩn bị tốt' }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ is_english() ? 'Catering and ceremonial trays should be requested early so ingredients and kitchen timing can be arranged well.' : 'Tiệc và mâm cúng nên báo trước để quán chủ động nguyên liệu và thời gian bếp.' }}</p>
                </article>
            </div>
        </div>
    </section>

    @if ($suggestedDishes->isNotEmpty())
        <section id="mon-goi-y" class="section-block pt-0">
            <div class="section-heading">
                <p class="eyebrow">{{ is_english() ? 'Suggested dishes' : 'Gợi ý món' }}</p>
                <h2>{{ is_english() ? 'Dishes that work well for groups and vegetarian trays' : 'Một số món hợp cho bàn nhóm và mâm chay' }}</h2>
                <p>{{ is_english() ? 'This is a helpful starting point when discussing vegetarian catering or ceremonial trays at the Hai Phong branch.' : 'Danh sách này là điểm bắt đầu tốt để bạn trao đổi với quán khi cần đặt tiệc chay hoặc mâm cúng chay tại Hải Phòng.' }}</p>
            </div>
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($suggestedDishes as $dish)
                    @include('partials.dish-card', ['dish' => $dish])
                @endforeach
            </div>
        </section>
    @endif

    <section class="bg-emerald-950 text-white">
        <div class="mx-auto grid max-w-7xl gap-8 px-4 py-16 sm:px-6 lg:grid-cols-[0.8fr_1.2fr] lg:items-center lg:px-8">
            <div>
                <p class="eyebrow text-amber-100">{{ is_english() ? 'Send a request' : 'Gửi yêu cầu' }}</p>
                <h2 class="mt-3 text-3xl font-semibold sm:text-4xl">{{ is_english() ? 'Tell us your group size and occasion' : 'Cho quán biết số người và dịp dùng bữa' }}</h2>
                <p class="mt-5 leading-8 text-emerald-50/80">
                    {{ is_english() ? 'You can book a table and note “vegetarian catering” or “vegetarian feast tray”. The restaurant will contact you to confirm the details.' : 'Bạn có thể đặt bàn trước rồi ghi chú “đặt tiệc chay” hoặc “mâm cúng chay”. Quán sẽ liên hệ lại để xác nhận chi tiết.' }}
                </p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ localized_route('reservations.create') }}" class="btn-primary" data-track-event="click_catering_reservation" data-track-category="catering" data-track-label="Catering middle reservation" data-facebook-event="ClickCatering">{{ is_english() ? 'Request via form' : 'Đặt tiệc qua form' }}</a>
                    <a href="{{ setting('zalo_url') }}" class="btn-ghost" target="_blank" rel="noopener" data-track-event="click_zalo" data-track-category="contact" data-track-label="Catering Zalo" data-facebook-event="ClickZalo">{{ is_english() ? 'Message Zalo' : 'Nhắn Zalo' }}</a>
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
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">{{ is_english() ? 'Questions about vegetarian catering and feast trays' : 'Câu hỏi về đặt tiệc và mâm cúng chay' }}</h2>
            </div>
            <div class="space-y-4">
                @foreach ($faqs as $faq)
                    <details class="group rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm" @if ($loop->first) open @endif>
                        <summary class="cursor-pointer list-none text-lg font-semibold text-emerald-950">{{ $faq['question'] }}</summary>
                        <p class="mt-4 leading-8 text-stone-700">{{ $faq['answer'] }}</p>
                    </details>
                @endforeach
            </div>
        </div>
    </section>
@endsection
