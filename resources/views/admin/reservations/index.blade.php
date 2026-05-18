@extends('admin.layouts.app')

@section('title', 'Đặt bàn')

@section('content')
    <div class="admin-page-head">
        <form class="admin-filter" method="GET">
            <input name="q" value="{{ request('q') }}" placeholder="Tìm tên hoặc SĐT..." class="admin-input">
            <input type="date" name="date" value="{{ request('date') }}" class="admin-input">
            <select name="status" class="admin-input">
                <option value="">Tất cả trạng thái</option>
                @foreach (['pending', 'confirmed', 'cancelled', 'completed'] as $status)
                    <option value="{{ $status }}" @selected(request('status') === $status)>{{ $status }}</option>
                @endforeach
            </select>
            <button class="admin-btn-secondary">Lọc</button>
        </form>
    </div>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Khách</th>
                    <th>Liên hệ</th>
                    <th>Ngày giờ</th>
                    <th>Số người</th>
                    <th>Trạng thái</th>
                    <th class="text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reservations as $reservation)
                    <tr>
                        <td class="font-semibold">{{ $reservation->name }}</td>
                        <td>
                            <p>{{ $reservation->phone }}</p>
                            <p class="text-xs text-slate-500">{{ $reservation->email }}</p>
                        </td>
                        <td>{{ $reservation->reservation_date->format('d/m/Y') }} {{ substr($reservation->reservation_time, 0, 5) }}</td>
                        <td>{{ $reservation->guests }}</td>
                        <td><span class="status-badge status-{{ $reservation->status }}">{{ $reservation->status }}</span></td>
                        <td>
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.reservations.show', $reservation) }}" class="admin-btn-mini">Chi tiết</a>
                                <form method="POST" action="{{ route('admin.reservations.destroy', $reservation) }}" data-confirm="Xóa đặt bàn này?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="admin-btn-danger">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-slate-500">Chưa có đặt bàn.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $reservations->links() }}</div>
@endsection
