@extends('admin.layouts.app')

@section('title', 'Sổ đặt bàn')

@section('content')
    <div class="reservation-shift-head">
        <div>
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-emerald-700">Bảng trực ca</p>
            <h2 class="mt-2 text-2xl font-black text-slate-950">Theo dõi đặt bàn trong ngày</h2>
            <p class="mt-2 max-w-3xl text-sm leading-6 text-slate-600">
                Mặc định hiển thị đặt bàn hôm nay ở trạng thái chờ gọi và đã giữ bàn. Các đơn cần gọi ngay hoặc đã qua giờ sẽ được đẩy lên trước.
            </p>
        </div>
        <a href="{{ route('admin.reservations.index', ['date' => now()->toDateString(), 'status' => 'active']) }}" class="admin-btn-primary">Ca hôm nay</a>
    </div>

    <div class="reservation-shift-stats">
        @foreach ([
            ['label' => 'Tổng bàn hôm nay', 'value' => $todayStats['total'], 'tone' => 'slate'],
            ['label' => 'Chờ gọi', 'value' => $todayStats['pending'], 'tone' => 'amber'],
            ['label' => 'Cần gọi ngay', 'value' => $todayStats['urgent'], 'tone' => 'red'],
            ['label' => 'Sắp đến giờ', 'value' => $todayStats['due_soon'], 'tone' => 'sky'],
            ['label' => 'Qua giờ chưa chốt', 'value' => $todayStats['past'], 'tone' => 'rose'],
        ] as $stat)
            <div class="reservation-shift-stat reservation-shift-stat-{{ $stat['tone'] }}">
                <span>{{ $stat['label'] }}</span>
                <strong>{{ $stat['value'] }}</strong>
            </div>
        @endforeach
    </div>

    <form class="admin-filter reservation-filter" method="GET">
        <input name="q" value="{{ request('q') }}" placeholder="Tìm tên hoặc SĐT..." class="admin-input">
        <input type="date" name="date" value="{{ $selectedDate }}" class="admin-input">
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
        <a href="{{ route('admin.reservations.index', ['status' => 'active']) }}" class="admin-btn-secondary">Hôm nay</a>
        <a href="{{ route('admin.reservations.index', ['date' => now()->addDay()->toDateString(), 'status' => 'active']) }}" class="admin-btn-secondary">Ngày mai</a>
    </form>

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
