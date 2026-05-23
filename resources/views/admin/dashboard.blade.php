@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <form method="GET" class="mb-6 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <div class="grid gap-4 md:grid-cols-[1fr_1fr_auto] md:items-end">
            <div>
                <label for="start_date" class="admin-label">Từ ngày</label>
                <input id="start_date" type="date" name="start_date" value="{{ $startDate->toDateString() }}" class="admin-input">
            </div>
            <div>
                <label for="end_date" class="admin-label">Đến ngày</label>
                <input id="end_date" type="date" name="end_date" value="{{ $endDate->toDateString() }}" class="admin-input">
            </div>
            <button class="admin-btn-primary">Lọc dữ liệu</button>
        </div>
    </form>

    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-5">
        @foreach ([
            ['label' => 'Món mới', 'value' => $dishCount, 'tone' => 'emerald'],
            ['label' => 'Bài viết mới', 'value' => $postCount, 'tone' => 'amber'],
            ['label' => 'Đặt bàn', 'value' => $reservationCount, 'tone' => 'sky'],
            ['label' => 'Liên hệ', 'value' => $contactCount, 'tone' => 'rose'],
            ['label' => 'Chat', 'value' => $chatCount, 'tone' => 'violet'],
        ] as $card)
            <div class="admin-stat-card admin-stat-{{ $card['tone'] }}">
                <p class="text-sm font-semibold text-slate-500">{{ $card['label'] }}</p>
                <p class="mt-3 text-3xl font-bold text-slate-950">{{ $card['value'] }}</p>
                <p class="mt-2 text-xs font-semibold text-slate-500">{{ $startDate->format('d/m') }} - {{ $endDate->format('d/m/Y') }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid gap-5 md:grid-cols-3">
        <a href="{{ route('admin.reservations.index', ['status' => 'pending']) }}" class="admin-alert-card">
            <span class="text-sm font-bold text-slate-500">Đặt bàn chờ xử lý</span>
            <strong>{{ $pendingReservationCount }}</strong>
        </a>
        <a href="{{ route('admin.contacts.index', ['status' => 'new']) }}" class="admin-alert-card">
            <span class="text-sm font-bold text-slate-500">Liên hệ mới</span>
            <strong>{{ $newContactCount }}</strong>
        </a>
        <a href="{{ route('admin.chats.index') }}" class="admin-alert-card">
            <span class="text-sm font-bold text-slate-500">Tin chat chưa đọc</span>
            <strong>{{ $unreadChatCount }}</strong>
        </a>
    </div>

    <section class="mt-6 rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
            <div>
                <h2 class="text-lg font-bold text-slate-950">Tổng quan tương tác</h2>
                <p class="text-sm text-slate-500">Tách rõ tổng số và xu hướng 14 ngày gần nhất trong khoảng lọc.</p>
            </div>
            <div class="flex flex-wrap gap-3 text-xs font-bold text-slate-600">
                <span class="chart-legend bg-sky-500"></span> Đặt bàn
                <span class="chart-legend bg-rose-500"></span> Liên hệ
                <span class="chart-legend bg-violet-500"></span> Chat
            </div>
        </div>

        <div class="mt-6 grid gap-6 xl:grid-cols-[0.78fr_1.22fr]">
            <div class="admin-chart-summary">
                @foreach ([
                    ['label' => 'Đặt bàn', 'value' => $chartData['totals']['reservations'], 'color' => 'bg-sky-500'],
                    ['label' => 'Liên hệ', 'value' => $chartData['totals']['contacts'], 'color' => 'bg-rose-500'],
                    ['label' => 'Tin chat', 'value' => $chartData['totals']['chats'], 'color' => 'bg-violet-500'],
                ] as $item)
                    @php
                        $width = ($item['value'] / $chartData['total_max']) * 100;
                    @endphp
                    <div class="admin-chart-summary-row">
                        <div class="flex items-center justify-between gap-4">
                            <span>{{ $item['label'] }}</span>
                            <strong>{{ $item['value'] }}</strong>
                        </div>
                        <div class="admin-summary-meter">
                            <span class="{{ $item['color'] }}" style="width: {{ max(6, $width) }}%"></span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="admin-trend-panel">
                <div class="mb-4 flex items-center justify-between gap-3">
                    <h3 class="font-bold text-slate-950">Xu hướng theo ngày</h3>
                    <span class="text-xs font-bold uppercase text-slate-400">14 ngày gần nhất</span>
                </div>
                <div class="admin-trend-chart">
                    @foreach ($chartData['display_days'] as $index => $day)
                        @php
                            $reservationTotal = $chartData['display_reservations'][$index] ?? 0;
                            $contactTotal = $chartData['display_contacts'][$index] ?? 0;
                            $chatTotal = $chartData['display_chats'][$index] ?? 0;
                            $dayTotal = $reservationTotal + $contactTotal + $chatTotal;
                            $height = ($dayTotal / $chartData['display_total_max']) * 100;
                        @endphp
                        <div class="admin-trend-day" title="{{ \Carbon\Carbon::parse($day)->format('d/m/Y') }} - {{ $dayTotal }} tương tác">
                            <strong>{{ $dayTotal }}</strong>
                            <div class="admin-trend-bar" style="height: {{ max(8, $height) }}%">
                                <span class="bg-violet-500" style="height: {{ $dayTotal ? ($chatTotal / $dayTotal) * 100 : 0 }}%"></span>
                                <span class="bg-rose-500" style="height: {{ $dayTotal ? ($contactTotal / $dayTotal) * 100 : 0 }}%"></span>
                                <span class="bg-sky-500" style="height: {{ $dayTotal ? ($reservationTotal / $dayTotal) * 100 : 0 }}%"></span>
                            </div>
                            <span>{{ \Carbon\Carbon::parse($day)->format('d/m') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <section class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-lg font-bold text-slate-950">Đặt bàn mới nhất</h2>
            <div class="mt-4 divide-y divide-slate-100">
                @forelse ($latestReservations as $reservation)
                    <a href="{{ route('admin.reservations.show', $reservation) }}" class="flex items-center justify-between gap-4 py-3">
                        <span>
                            <span class="block font-semibold">{{ $reservation->name }}</span>
                            <span class="text-sm text-slate-500">{{ $reservation->branch?->name ? $reservation->branch->name.' - ' : '' }}{{ $reservation->phone }} - {{ $reservation->reservation_date->format('d/m/Y') }} {{ substr($reservation->reservation_time, 0, 5) }}</span>
                        </span>
                        <span class="status-badge status-{{ $reservation->status }}">{{ $reservation->status }}</span>
                    </a>
                @empty
                    <p class="py-4 text-sm text-slate-500">Chưa có đặt bàn trong khoảng này.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-lg font-bold text-slate-950">Liên hệ mới nhất</h2>
            <div class="mt-4 divide-y divide-slate-100">
                @forelse ($latestContacts as $contact)
                    <a href="{{ route('admin.contacts.show', $contact) }}" class="flex items-center justify-between gap-4 py-3">
                        <span>
                            <span class="block font-semibold">{{ $contact->name }}</span>
                            <span class="text-sm text-slate-500">{{ $contact->branch?->name ? $contact->branch->name.' - ' : '' }}{{ $contact->email ?: $contact->phone }}</span>
                        </span>
                        <span class="status-badge status-{{ $contact->status }}">{{ $contact->status }}</span>
                    </a>
                @empty
                    <p class="py-4 text-sm text-slate-500">Chưa có liên hệ trong khoảng này.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-lg font-bold text-slate-950">Món ăn nổi bật</h2>
            <div class="mt-4 divide-y divide-slate-100">
                @forelse ($featuredDishes as $dish)
                    <a href="{{ route('admin.dishes.edit', $dish) }}" class="flex items-center gap-4 py-3">
                        <img src="{{ media_url($dish->image) }}" alt="{{ $dish->name }}" class="h-14 w-16 rounded-xl object-cover">
                        <span class="min-w-0 flex-1">
                            <span class="block truncate font-semibold">{{ $dish->name }}</span>
                            <span class="text-sm text-slate-500">{{ $dish->category?->name }}</span>
                        </span>
                        <span class="font-semibold text-emerald-800">{{ number_format($dish->price, 0, ',', '.') }}đ</span>
                    </a>
                @empty
                    <p class="py-4 text-sm text-slate-500">Chưa có món nổi bật.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-lg font-bold text-slate-950">Bài viết mới</h2>
            <div class="mt-4 divide-y divide-slate-100">
                @forelse ($latestPosts as $post)
                    <a href="{{ route('admin.posts.edit', $post) }}" class="flex items-center gap-4 py-3">
                        <img src="{{ media_url($post->thumbnail) }}" alt="{{ $post->title }}" class="h-14 w-16 rounded-xl object-cover">
                        <span>
                            <span class="block font-semibold">{{ $post->title }}</span>
                            <span class="text-sm text-slate-500">{{ optional($post->published_at)->format('d/m/Y') ?: 'Chưa hẹn ngày' }}</span>
                        </span>
                    </a>
                @empty
                    <p class="py-4 text-sm text-slate-500">Chưa có bài viết.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
