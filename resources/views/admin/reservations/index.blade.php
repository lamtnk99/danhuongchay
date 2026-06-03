@extends('admin.layouts.app')

@section('title', 'Sổ đặt bàn')

@section('content')
    <div class="reservation-shift-head">
        <div>
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-[#667132]">Bảng trực ca</p>
            <h2 class="mt-2 text-2xl font-black text-slate-950">Theo dõi đặt bàn 7 ngày tới</h2>
            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                Mặc định hiển thị đặt bàn từ hôm nay đến 7 ngày tới ở trạng thái chờ gọi và đã giữ bàn. Dùng bộ lọc ngày để xem từng ca hoặc khoảng thời gian xa hơn.
            </p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.reservations.export', request()->query()) }}" class="admin-btn-secondary">Xuất Excel</a>
            <a href="{{ route('admin.reservations.index', ['date' => now()->toDateString(), 'status' => 'active']) }}" class="admin-btn-primary">Ca hôm nay</a>
        </div>
    </div>

    <div class="reservation-shift-stats">
        @foreach ([
            ['label' => 'Tổng trong khoảng', 'value' => $rangeStats['total'], 'tone' => 'slate'],
            ['label' => 'Đang xử lý', 'value' => $rangeStats['active'], 'tone' => 'amber'],
            ['label' => 'Chờ gọi', 'value' => $rangeStats['pending'], 'tone' => 'red'],
            ['label' => 'Đã giữ bàn', 'value' => $rangeStats['confirmed'], 'tone' => 'sky'],
            ['label' => 'Ca hôm nay', 'value' => $todayStats['total'], 'tone' => 'rose'],
        ] as $stat)
            <div class="reservation-shift-stat reservation-shift-stat-{{ $stat['tone'] }}">
                <span>{{ $stat['label'] }}</span>
                <strong>{{ $stat['value'] }}</strong>
            </div>
        @endforeach
    </div>

    <div class="reservation-shift-stats">
        @foreach ([
            ['label' => 'Hôm nay chờ gọi', 'value' => $todayStats['pending'], 'tone' => 'amber'],
            ['label' => 'Hôm nay cần gọi ngay', 'value' => $todayStats['urgent'], 'tone' => 'red'],
            ['label' => 'Hôm nay sắp đến giờ', 'value' => $todayStats['due_soon'], 'tone' => 'sky'],
            ['label' => 'Hôm nay qua giờ', 'value' => $todayStats['past'], 'tone' => 'rose'],
        ] as $stat)
            <div class="reservation-shift-stat reservation-shift-stat-{{ $stat['tone'] }}">
                <span>{{ $stat['label'] }}</span>
                <strong>{{ $stat['value'] }}</strong>
            </div>
        @endforeach
    </div>

    <form class="admin-filter reservation-filter" method="GET">
        <input name="q" value="{{ request('q') }}" placeholder="Tìm tên hoặc SĐT..." class="admin-input">
        <label class="sr-only" for="reservation_from">Từ ngày</label>
        <input id="reservation_from" type="date" name="from" value="{{ $selectedDate ? null : $fromDate }}" class="admin-input">
        <label class="sr-only" for="reservation_to">Đến ngày</label>
        <input id="reservation_to" type="date" name="to" value="{{ $selectedDate ? null : $toDate }}" class="admin-input">
        <select name="branch_id" class="admin-input">
            <option value="">Tất cả cơ sở</option>
            @foreach ($branches as $branch)
                <option value="{{ $branch->id }}" @selected((string) request('branch_id') === (string) $branch->id)>{{ $branch->name }}</option>
            @endforeach
        </select>
        <select name="status" class="admin-input">
            <option value="active" @selected($status === 'active' || blank($status))>Đang xử lý</option>
            <option value="pending" @selected($status === 'pending')>Chờ gọi xác nhận</option>
            <option value="confirmed" @selected($status === 'confirmed')>Đã giữ bàn</option>
            <option value="completed" @selected($status === 'completed')>Khách đã đến</option>
            <option value="cancelled" @selected($status === 'cancelled')>Đã hủy</option>
        </select>
        <button class="admin-btn-secondary">Lọc</button>
        <a href="{{ route('admin.reservations.index', ['date' => now()->toDateString(), 'status' => 'active']) }}" @class(['admin-btn-secondary', 'is-active' => $quickFilter === 'today'])>Ca hôm nay</a>
        <a href="{{ route('admin.reservations.index', ['date' => now()->addDay()->toDateString(), 'status' => 'active']) }}" @class(['admin-btn-secondary', 'is-active' => $quickFilter === 'tomorrow'])>Ngày mai</a>
        <a href="{{ route('admin.reservations.index', ['from' => now()->toDateString(), 'to' => now()->addDays(7)->toDateString(), 'status' => 'active']) }}" @class(['admin-btn-secondary', 'is-active' => $quickFilter === 'week'])>7 ngày tới</a>
    </form>

    @if ($selectedDate)
        <p class="mt-3 text-sm font-semibold text-slate-500">Đang xem ngày {{ \Carbon\Carbon::parse($selectedDate)->format('d/m/Y') }}.</p>
    @elseif ($fromDate && $toDate)
        <p class="mt-3 text-sm font-semibold text-slate-500">Đang xem từ {{ \Carbon\Carbon::parse($fromDate)->format('d/m/Y') }} đến {{ \Carbon\Carbon::parse($toDate)->format('d/m/Y') }}.</p>
    @elseif (request('q'))
        <p class="mt-3 text-sm font-semibold text-slate-500">Đang tìm toàn bộ lịch sử theo từ khóa.</p>
    @endif

    <div class="reservation-board">
        @foreach ($sections as $section)
            @continue($section['items']->isEmpty() && $section['key'] === 'closed' && ! in_array($status, ['completed', 'cancelled'], true))
            <section class="reservation-lane reservation-lane-{{ $section['tone'] }}">
                <div class="reservation-lane-head">
                    <div>
                        <h3>{{ $section['title'] }}</h3>
                        <p>{{ $section['hint'] }}</p>
                    </div>
                    <strong>{{ $section['items']->count() }}</strong>
                </div>

                <div class="reservation-card-list">
                    @forelse ($section['items'] as $reservation)
                        @include('admin.reservations.partials.card', ['reservation' => $reservation])
                    @empty
                        <p class="reservation-empty">Không có đơn trong nhóm này.</p>
                    @endforelse
                </div>
            </section>
        @endforeach
    </div>

    @if ($reservations->count() >= 200)
        <p class="mt-4 rounded-2xl bg-amber-50 p-4 text-sm font-semibold text-amber-900">
            Đang hiển thị 200 đơn đầu tiên theo bộ lọc. Hãy lọc theo ngày, cơ sở hoặc trạng thái để xem chính xác hơn.
        </p>
    @endif
@endsection
