@extends('admin.layouts.app')

@section('title', 'Chi tiết đặt bàn')

@section('content')
    <div class="grid gap-6 lg:grid-cols-[0.9fr_1.1fr]">
        <section class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <h2 class="text-xl font-bold text-slate-950">{{ $reservation->name }}</h2>
            <dl class="mt-5 space-y-4 text-sm">
                <div><dt class="font-bold text-slate-500">Số điện thoại</dt><dd>{{ $reservation->phone }}</dd></div>
                <div><dt class="font-bold text-slate-500">Cơ sở</dt><dd>{{ $reservation->branch?->name ?: 'Chưa chọn' }}</dd></div>
                <div><dt class="font-bold text-slate-500">Email</dt><dd>{{ $reservation->email ?: 'Không có' }}</dd></div>
                <div><dt class="font-bold text-slate-500">Ngày đặt</dt><dd>{{ $reservation->reservation_date->format('d/m/Y') }}</dd></div>
                <div><dt class="font-bold text-slate-500">Giờ đặt</dt><dd>{{ substr($reservation->reservation_time, 0, 5) }}</dd></div>
                <div><dt class="font-bold text-slate-500">Số người</dt><dd>{{ $reservation->guests }}</dd></div>
                <div><dt class="font-bold text-slate-500">Ghi chú khách</dt><dd class="whitespace-pre-line">{{ $reservation->note ?: 'Không có' }}</dd></div>
            </dl>
        </section>

        <form method="POST" action="{{ route('admin.reservations.update', $reservation) }}" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            @csrf
            @method('PUT')
            <div>
                <label for="status" class="admin-label">Trạng thái</label>
                <select id="status" name="status" class="admin-input" required>
                    @foreach (['pending', 'confirmed', 'cancelled', 'completed'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $reservation->status) === $status)>{{ $status }}</option>
                    @endforeach
                </select>
                @error('status') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="mt-5">
                <label for="admin_note" class="admin-label">Ghi chú nội bộ</label>
                <textarea id="admin_note" name="admin_note" rows="8" class="admin-input">{{ old('admin_note', $reservation->admin_note) }}</textarea>
                @error('admin_note') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="mt-6 flex flex-wrap gap-3">
                <button class="admin-btn-primary">Cập nhật</button>
                <a href="{{ route('admin.reservations.index') }}" class="admin-btn-secondary">Quay lại</a>
            </div>
        </form>
    </div>
@endsection
