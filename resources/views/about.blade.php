@extends('layouts.app')

@section('content')
    <section class="subpage-hero">
        <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
            <p class="eyebrow">Giới thiệu</p>
            <h1 class="mt-4 text-4xl font-semibold text-emerald-950 sm:text-5xl">{{ $page?->title ?: 'Câu chuyện Đàn Hương Chay' }}</h1>
            <p class="mt-6 text-lg leading-8 text-stone-700">
                {{ $page?->meta_description ?: 'Đàn Hương Chay được tạo nên từ mong muốn có một nơi dùng bữa nhẹ nhàng, nơi món chay không chỉ là lựa chọn ăn uống mà còn là cách trở về với sự cân bằng.' }}
            </p>
        </div>
    </section>

    <section class="section-block">
        <div class="grid gap-10 lg:grid-cols-2 lg:items-center">
            <div>
                <img
                    src="{{ media_url($page?->image, 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1200&q=80') }}"
                    alt="Không gian nhà hàng ấm cúng với bàn gỗ và ánh sáng dịu"
                    class="rounded-3xl object-cover shadow-2xl"
                    loading="lazy"
                >
            </div>
            <div>
                <p class="eyebrow">Triết lý</p>
                <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">Nấu chay bằng sự tiết chế và tôn trọng nguyên liệu</h2>
                @if ($page)
                    <div class="article-content mt-5">{!! $page->content !!}</div>
                @else
                    <p class="mt-5 leading-8 text-stone-700">
                        Chúng tôi tin món chay ngon không cần cầu kỳ quá mức. Rau củ đúng mùa, nấm tươi, đậu hũ và hạt được xử lý cẩn thận có thể tạo nên một bữa ăn đầy đủ, thơm lành và sâu vị.
                    </p>
                    <p class="mt-4 leading-8 text-stone-700">
                        Mỗi món tại Đàn Hương Chay được nêm vừa phải, hạn chế dầu nặng, ưu tiên vị ngọt tự nhiên và sự cân bằng giữa màu sắc, kết cấu, hương thơm.
                    </p>
                @endif
            </div>
        </div>
    </section>

    <section class="commitment-section-visual">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="grid gap-6 md:grid-cols-3">
                <div class="commitment-card has-card-bg" style="--card-bg: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=900&q=80')">
                    <h2>Không gian thanh tịnh</h2>
                    <p>Thiết kế nhẹ với gỗ, cây xanh và ánh sáng tự nhiên, phù hợp cho bữa ăn gia đình, bạn bè hoặc những buổi gặp gỡ yên tĩnh.</p>
                </div>
                <div class="commitment-card has-card-bg" style="--card-bg: url('https://images.unsplash.com/photo-1506806732259-39c2d0268443?auto=format&fit=crop&w=900&q=80')">
                    <h2>Nguyên liệu rõ nguồn</h2>
                    <p>Ưu tiên rau củ tươi, nấm, hạt và đạm thực vật từ nhà cung cấp ổn định, hạn chế phụ gia không cần thiết.</p>
                </div>
                <div class="commitment-card has-card-bg" style="--card-bg: url('https://images.unsplash.com/photo-1512621776951-a57141f2eefd?auto=format&fit=crop&w=900&q=80')">
                    <h2>Món ăn lành mạnh</h2>
                    <p>Thực đơn được cân nhắc để có đủ chất, dễ ăn và phù hợp với nhiều thói quen ăn chay khác nhau.</p>
                </div>
            </div>
        </div>
    </section>

    @if ($galleryImages->isNotEmpty())
        <section class="section-block">
            <div class="section-heading">
                <p class="eyebrow">Không gian quán</p>
                <h2>Một vài góc yên của Đàn Hương Chay</h2>
            </div>
            <div class="about-gallery-strip mt-10">
                @foreach ($galleryImages as $image)
                    <a href="{{ route('gallery.index') }}" class="about-gallery-card">
                        <img src="{{ media_url($image->image) }}" alt="{{ $image->alt_text ?: $image->title }}" loading="lazy">
                        <span>{{ $image->title }}</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <section class="about-map-section">
        <div class="mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8">
            <div class="grid gap-8 lg:grid-cols-[0.8fr_1.2fr] lg:items-center">
                <div>
                    <p class="eyebrow">Địa chỉ</p>
                    <h2 class="mt-3 text-3xl font-semibold text-emerald-950 sm:text-4xl">Ghé quán khi bạn muốn một bữa chay thật nhẹ</h2>
                    <p class="mt-5 leading-8 text-stone-700">{{ setting('address', 'Địa chỉ mẫu, Thành phố Hồ Chí Minh') }}</p>
                    <p class="mt-2 leading-8 text-stone-700">Giờ mở cửa: {{ setting('opening_hours', '09:00 - 21:30 hằng ngày') }}</p>
                    <a href="{{ route('reservations.create') }}" class="btn-primary mt-6">Đặt bàn</a>
                </div>
                <div class="map-frame">
                    @if (setting('google_map_iframe'))
                        {!! setting('google_map_iframe') !!}
                    @else
                        <iframe
                            title="Bản đồ Đàn Hương Chay"
                            src="https://www.google.com/maps?q=Ho%20Chi%20Minh%20City&output=embed"
                            class="h-80 w-full"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
