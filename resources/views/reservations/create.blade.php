@extends('layouts.app')

@section('content')
    <section class="subpage-hero">
        <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
            <p class="eyebrow">Đặt bàn</p>
            <h1 class="mt-4 text-4xl font-semibold text-emerald-950 sm:text-5xl">Giữ chỗ cho bữa chay an nhiên</h1>
            <p class="mt-6 text-lg leading-8 text-stone-700">
                Điền thông tin đặt bàn, hệ thống sẽ lưu yêu cầu và đội ngũ Đàn Hương Chay sẽ gọi xác nhận.
            </p>
        </div>
    </section>

    <section class="section-block">
        <div class="mx-auto max-w-3xl rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm sm:p-8">
            @if (session('success'))
                <span class="sr-only" data-track-view="reservation_success" data-track-category="reservation" data-track-label="Reservation form success" data-facebook-event="SubmitReservation"></span>
                <div class="mb-6 rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ localized_route('reservations.store') }}" method="POST" class="grid gap-5" data-submit-loading data-track-submit="submit_reservation" data-track-category="reservation" data-track-label="Reservation form" data-facebook-event="SubmitReservation">
                @csrf
                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="name" class="form-label">Họ tên</label>
                        <input id="name" name="name" value="{{ old('name') }}" class="form-input" autocomplete="name" required>
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="phone" class="form-label">Số điện thoại</label>
                        <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" class="form-input" inputmode="tel" autocomplete="tel" required>
                        @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="email" class="form-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" inputmode="email" autocomplete="email">
                    @error('email') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-5 md:grid-cols-3">
                    <div>
                        <label for="reservation_date" class="form-label">Ngày đặt</label>
                        <input id="reservation_date" type="date" name="reservation_date" value="{{ old('reservation_date') }}" min="{{ now()->toDateString() }}" class="form-input" autocomplete="off" required>
                        @error('reservation_date') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="reservation_time" class="form-label">Giờ đặt</label>
                        <input
                            id="reservation_time"
                            type="time"
                            list="reservation-time-options"
                            name="reservation_time"
                            value="{{ old('reservation_time') }}"
                            min="{{ $openingHours->opensAt }}"
                            max="{{ $openingHours->closesAt }}"
                            step="900"
                            class="form-input"
                            required
                        >
                        <datalist id="reservation-time-options">
                            @foreach ($openingHours->bookableSlots() as $slot)
                                @php
                                    $cursor = \Carbon\Carbon::createFromFormat('H:i', $slot['start']);
                                    $end = \Carbon\Carbon::createFromFormat('H:i', $slot['end']);
                                @endphp
                                @while ($cursor->lte($end))
                                    <option value="{{ $cursor->format('H:i') }}"></option>
                                    @php($cursor->addMinutes(15))
                                @endwhile
                            @endforeach
                        </datalist>
                        <p class="mt-2 text-sm font-medium text-stone-600" data-reservation-time-hint>
                            {{ $openingHours->message() }}
                        </p>
                        @error('reservation_time') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="guests" class="form-label">Số người</label>
                        <input id="guests" type="number" name="guests" value="{{ old('guests', 2) }}" min="1" max="40" class="form-input" inputmode="numeric" required>
                        @error('guests') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label for="note" class="form-label">Ghi chú</label>
                    <textarea id="note" name="note" rows="5" class="form-input">{{ old('note') }}</textarea>
                    @error('note') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn-primary justify-center" data-loading-text="Đang gửi yêu cầu...">Gửi yêu cầu đặt bàn</button>
            </form>
        </div>
    </section>
@endsection
