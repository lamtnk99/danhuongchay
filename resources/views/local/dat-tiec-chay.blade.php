@extends('layouts.app')

@section('content')
    <section class="catering-hub-hero">
        <img
            src="{{ media_variant_url(setting('default_background'), 'hero', 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=1800&q=85') }}"
            alt="{{ is_english() ? 'Vegetarian catering at Dan Huong Chay' : 'Đặt tiệc chay Đàn Hương Chay' }}"
            class="catering-hub-bg"
            fetchpriority="high"
        >
        <div class="catering-hub-overlay"></div>
        <div class="mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
            <div class="max-w-3xl text-white">
                <p class="eyebrow text-amber-100">{{ is_english() ? 'Vegetarian catering' : 'Đặt tiệc & mâm cúng chay' }}</p>
                <h1>{{ is_english() ? 'Vegetarian trays for intimate, meaningful gatherings' : 'Mâm chay chỉn chu cho những dịp cần sự an yên' }}</h1>
                <p>{{ is_english() ? 'Dan Huong Chay prepares vegetarian meals for family gatherings, ceremonies and small private tables at each branch.' : 'Đàn Hương Chay nhận tư vấn mâm cúng chay, bàn tiệc gia đình và những bữa chay cần chuẩn bị kỹ tại từng cơ sở.' }}</p>
            </div>
        </div>
    </section>

    <section class="section-block">
        <div class="catering-branch-grid">
            @foreach ($branches as $branch)
                @php
                    $isBmt = $branch->slug === 'buon-ma-thuot';
                    $detailUrl = $isBmt ? localized_route('local.vegetarian-catering-buon-ma-thuot') : localized_route('local.vegetarian-catering-hai-phong');
                @endphp
                <article class="catering-branch-card">
                    <div class="catering-branch-image">
                        <img
                            src="{{ media_variant_url($branch->image, 'large', $isBmt ? 'https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?auto=format&fit=crop&w=1000&q=82' : 'https://images.unsplash.com/photo-1551218808-94e220e084d2?auto=format&fit=crop&w=1000&q=82') }}"
                            alt="{{ $branch->name }}"
                            loading="lazy"
                        >
                    </div>
                    <div class="catering-branch-copy">
                        <p>{{ $branch->city ?: ($isBmt ? 'Buôn Ma Thuột' : 'Hải Phòng') }}</p>
                        <h2>{{ $branch->name }}</h2>
                        <span>{{ $branch->address }}</span>
                        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ $detailUrl }}" class="btn-primary">{{ is_english() ? 'View branch service' : 'Xem dịch vụ cơ sở' }}</a>
                            <a href="{{ localized_route('reservations.create', ['branch' => $branch->slug]) }}" class="btn-secondary">{{ is_english() ? 'Send request' : 'Gửi yêu cầu' }}</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@endsection
