@extends('layouts.app')

@section('content')
    @php
        $fitItems = is_english()
            ? ['Family ceremonial trays', 'Intimate vegetarian gatherings', 'Guest meals', 'Small group orders', 'Weekend sets']
            : ['Mâm cúng chay gia đình', 'Tiệc chay thân mật', 'Bữa chay tiếp khách', 'Đặt món cho nhóm nhỏ', 'Set món cuối tuần'];
    @endphp

    <section class="relative isolate overflow-hidden bg-stone-950 text-white">
        <img
            src="{{ media_variant_url($branch?->image ?: setting('default_background'), 'hero', 'https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?auto=format&fit=crop&w=1600&q=85') }}"
            alt="{{ is_english() ? 'Vegetarian catering in Buon Ma Thuot' : 'Đặt tiệc chay Buôn Ma Thuột' }}"
            class="absolute inset-0 -z-20 h-full w-full object-cover"
            fetchpriority="high"
            sizes="100vw"
        >
        <div class="absolute inset-0 -z-10 bg-gradient-to-r from-emerald-950/92 via-emerald-950/72 to-amber-900/35"></div>
        <div class="mx-auto grid min-h-[70vh] max-w-7xl gap-10 px-4 py-20 sm:px-6 lg:grid-cols-[1fr_0.9fr] lg:items-center lg:px-8">
            <div>
                <p class="eyebrow text-amber-100">{{ is_english() ? 'Vegetarian catering in Buon Ma Thuot' : 'Đặt tiệc chay Buôn Ma Thuột' }}</p>
                <h1 class="mt-5 max-w-3xl text-4xl font-semibold leading-tight sm:text-5xl lg:text-6xl">
                    {{ is_english() ? 'Gentle vegetarian trays for gatherings, ceremonies and small tables' : 'Mâm chay thanh vị cho bữa sum họp, lễ cúng và bàn tiệc nhỏ' }}
                </h1>
                <p class="mt-6 max-w-2xl text-lg leading-8 text-emerald-50/90">
                    {{ is_english() ? 'The Buon Ma Thuot branch advises on dishes by guest count, mealtime and occasion. Dishes are chosen to feel neat, beautiful, easy to enjoy and gently seasoned.' : 'Cơ sở Buôn Ma Thuột nhận tư vấn món theo số người, thời gian dùng bữa và dịp chuẩn bị. Món được chọn theo hướng gọn, đẹp, dễ ăn và giữ vị thanh.' }}
                </p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ localized_route('reservations.create', ['branch' => 'buon-ma-thuot']) }}" class="btn-primary">{{ is_english() ? 'Send catering request' : 'Gửi yêu cầu đặt tiệc' }}</a>
                    <a href="tel:{{ preg_replace('/\D+/', '', $branch?->hotline ?: setting('hotline', '0947361515')) }}" class="btn-ghost">{{ is_english() ? 'Call for advice' : 'Gọi tư vấn' }}</a>
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
            'title' => is_english() ? 'Preparing a vegetarian tray in Buon Ma Thuot?' : 'Chuẩn bị mâm chay tại Buôn Ma Thuột?',
            'text' => is_english() ? 'Send your request first so the restaurant can suggest dish count, flavor and preparation time for your family or group.' : 'Gửi yêu cầu trước để quán tư vấn số món, khẩu vị và khung giờ chuẩn bị phù hợp cho gia đình hoặc nhóm khách.',
            'primaryLabel' => is_english() ? 'Send catering request' : 'Gửi yêu cầu đặt tiệc',
            'secondaryLabel' => is_english() ? 'View suggested dishes' : 'Xem món gợi ý',
            'secondaryUrl' => '#mon-goi-y',
            'trackLabel' => 'BMT catering landing smart CTA',
        ])
    </div>

    <section class="section-block">
        <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-start">
            <div>
                <p class="eyebrow">{{ is_english() ? 'How we prepare' : 'Cách quán chuẩn bị' }}</p>
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">{{ is_english() ? 'A beautiful vegetarian meal starts with enough dishes, chosen for the occasion' : 'Bữa chay đẹp bắt đầu từ món vừa đủ và đúng dịp' }}</h2>
                <p class="mt-5 leading-8 text-stone-700">
                    {{ is_english() ? 'For ceremonial trays or intimate tables, we balance main dishes, vegetables or mushrooms, soup or hotpot, rolls or starters and light drinks. The goal is a table that feels complete without becoming heavy.' : 'Với mâm cúng hoặc bàn tiệc thân mật, quán ưu tiên cách phối món có nhịp: món chính, món rau/nấm, món nước hoặc lẩu, món cuốn/khai vị và phần đồ uống nhẹ. Cách chọn món giúp bàn ăn đủ đầy mà không bị nặng vị.' }}
                </p>
            </div>
            <div class="grid gap-4 sm:grid-cols-2">
                <article class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-emerald-950">{{ is_english() ? 'Gentle flavors' : 'Thanh vị' }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ is_english() ? 'Lightly oiled, easy-to-enjoy vegetarian dishes for long-time vegetarian guests and newcomers alike.' : 'Món chay nhẹ dầu, dễ ăn, phù hợp cả người ăn chay lâu năm lẫn khách mới dùng thử.' }}</p>
                </article>
                <article class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-emerald-950">{{ is_english() ? 'Thoughtful' : 'Chỉn chu' }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ is_english() ? 'Dish quantities and serving style are suggested so the table feels neat, beautiful and not excessive.' : 'Tư vấn số lượng món và cách dùng bữa để bàn tiệc gọn, đẹp, không dư quá nhiều.' }}</p>
                </article>
                <article class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-emerald-950">{{ is_english() ? 'On schedule' : 'Theo giờ hẹn' }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ is_english() ? 'Please let us know in advance so ingredients and service timing can be prepared better.' : 'Khách nên báo trước để quán chuẩn bị nguyên liệu và giữ nhịp phục vụ tốt hơn.' }}</p>
                </article>
                <article class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h3 class="text-xl font-semibold text-emerald-950">{{ is_english() ? 'Menu advice' : 'Có tư vấn' }}</h3>
                    <p class="mt-3 leading-7 text-stone-700">{{ is_english() ? 'The restaurant can suggest dishes by guest count, flavor preference and dining purpose.' : 'Quán hỗ trợ gợi ý món theo số người, khẩu vị và mục đích dùng bữa.' }}</p>
                </article>
            </div>
        </div>
    </section>

    @if ($suggestedDishes->isNotEmpty())
        <section id="mon-goi-y" class="section-block pt-0">
            <div class="section-heading">
                <p class="eyebrow">{{ is_english() ? 'Suggested dishes' : 'Gợi ý món' }}</p>
                <h2>{{ is_english() ? 'Dishes that work well for groups and vegetarian trays' : 'Một số món hợp cho bàn nhóm và mâm chay' }}</h2>
                <p>{{ is_english() ? 'These featured dishes are a helpful starting point when discussing vegetarian feast trays or catering in Buon Ma Thuot.' : 'Những món nổi bật giúp bạn dễ bắt đầu khi cần trao đổi với quán về mâm cúng chay hoặc tiệc chay tại Buôn Ma Thuột.' }}</p>
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
                <p class="eyebrow text-amber-100">{{ is_english() ? 'Buon Ma Thuot branch' : 'Cơ sở Buôn Ma Thuột' }}</p>
                <h2 class="mt-3 text-3xl font-semibold sm:text-4xl">{{ $branch?->name ?: 'Đàn Hương Chay Buôn Ma Thuột' }}</h2>
                <p class="mt-5 leading-8 text-emerald-50/80">{{ $branch?->address }}</p>
                <div class="mt-7 flex flex-wrap gap-3">
                    <a href="{{ localized_route('reservations.create', ['branch' => 'buon-ma-thuot']) }}" class="btn-primary">{{ is_english() ? 'Book via form' : 'Đặt qua form' }}</a>
                    @if ($branch?->zalo_url ?: setting('zalo_url'))
                        <a href="{{ $branch?->zalo_url ?: setting('zalo_url') }}" class="btn-ghost" target="_blank" rel="noopener">{{ is_english() ? 'Message Zalo' : 'Nhắn Zalo' }}</a>
                    @endif
                </div>
            </div>
            <div class="overflow-hidden rounded-3xl border border-white/15 bg-white/10 shadow-2xl">
                @if ($branch?->google_map_iframe)
                    {!! $branch->google_map_iframe !!}
                @endif
            </div>
        </div>
    </section>

    <section class="section-block">
        <div class="grid gap-10 lg:grid-cols-[0.8fr_1.2fr]">
            <div>
                <p class="eyebrow">{{ is_english() ? 'Questions' : 'Câu hỏi thường gặp' }}</p>
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">{{ is_english() ? 'Questions about vegetarian catering in Buon Ma Thuot' : 'Câu hỏi về đặt tiệc chay Buôn Ma Thuột' }}</h2>
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
