@extends('layouts.app')

@section('content')
    <section class="reservation-luxury">
        <div class="reservation-luxury-media">
            <img
                src="{{ media_variant_url(setting('default_background'), 'hero', 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1800&q=85') }}"
                alt="{{ is_english() ? 'Dan Huong Chay reservation space' : 'Không gian đặt bàn Đàn Hương Chay' }}"
                fetchpriority="high"
            >
        </div>
        <div class="reservation-luxury-shade"></div>

        <div class="reservation-luxury-inner">
            <div class="reservation-luxury-copy">
                <p>{{ is_english() ? 'Reservation' : 'Đặt bàn' }}</p>
                <h1>{{ is_english() ? 'Reserve a calm vegetarian table' : 'Giữ chỗ cho một bữa chay an yên' }}</h1>
                <span>{{ is_english() ? 'Choose the branch and time. Dan Huong Chay will confirm by hotline.' : 'Chọn cơ sở và khung giờ phù hợp. Đàn Hương Chay sẽ gọi xác nhận trước khi giữ bàn.' }}</span>
            </div>

            <div class="reservation-luxury-card">
                @if (session('success'))
                    <span class="sr-only" data-track-view="reservation_success" data-track-category="reservation" data-track-label="Reservation form success" data-facebook-event="SubmitReservation"></span>
                    <div class="mb-5 rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-900">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ localized_route('reservations.store') }}" method="POST" data-submit-loading data-track-submit="submit_reservation" data-track-category="reservation" data-track-label="Reservation form" data-facebook-event="SubmitReservation">
                    @csrf

                    <div class="reservation-form-head">
                        <p>{{ is_english() ? 'Booking details' : 'Thông tin giữ bàn' }}</p>
                        <h2>{{ is_english() ? 'Tell us your preferred experience' : 'Cho quán biết thời gian bạn muốn ghé' }}</h2>
                    </div>

                    <div class="mt-6 grid gap-4">
                        <div>
                            <label for="branch_id" class="form-label">{{ is_english() ? 'Branch' : 'Cơ sở' }}</label>
                            <select id="branch_id" name="branch_id" class="form-input" required>
                                @foreach ($branches as $branch)
                                    @php($branchHours = \App\Support\OpeningHours::fromBranch($branch))
                                    <option
                                        value="{{ $branch->id }}"
                                        data-hint="{{ $branchHours->message() }}"
                                        data-opens="{{ $branchHours->opensAt }}"
                                        data-closes="{{ $branchHours->closesAt }}"
                                        data-slots='@json($branchHours->bookableSlots())'
                                        @selected((string) old('branch_id', $selectedBranch?->id) === (string) $branch->id)
                                    >
                                        {{ $branch->name }}{{ $branch->city ? ' - '.$branch->city : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="reservation-time-hint" data-branch-time-hint>{{ $openingHours->message() }}</p>
                            @error('branch_id') <p class="form-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="name" class="form-label">{{ is_english() ? 'Full name' : 'Họ tên' }}</label>
                                <input id="name" name="name" value="{{ old('name') }}" class="form-input" autocomplete="name" required>
                                @error('name') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="phone" class="form-label">{{ is_english() ? 'Phone' : 'Số điện thoại' }}</label>
                                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" class="form-input" inputmode="tel" autocomplete="tel" required>
                                @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            <div>
                                <label for="reservation_date" class="form-label">{{ is_english() ? 'Date' : 'Ngày đặt' }}</label>
                                <input id="reservation_date" type="date" name="reservation_date" value="{{ old('reservation_date') }}" min="{{ now()->toDateString() }}" class="form-input" autocomplete="off" required>
                                @error('reservation_date') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="reservation_time" class="form-label">{{ is_english() ? 'Time' : 'Giờ đặt' }}</label>
                                <input id="reservation_time" type="time" list="reservation-time-options" name="reservation_time" value="{{ old('reservation_time') }}" min="{{ $openingHours->opensAt }}" max="{{ $openingHours->closesAt }}" step="900" class="form-input" required>
                                <datalist id="reservation-time-options"></datalist>
                                @error('reservation_time') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="guests" class="form-label">{{ is_english() ? 'Guests' : 'Số người' }}</label>
                                <input id="guests" type="number" name="guests" value="{{ old('guests', 2) }}" min="1" max="40" class="form-input" inputmode="numeric" required>
                                @error('guests') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="note" class="form-label">{{ is_english() ? 'Note' : 'Ghi chú' }}</label>
                            <textarea id="note" name="note" rows="4" class="form-input" placeholder="{{ is_english() ? 'Catering, birthday, family dinner...' : 'Đặt tiệc, mâm cúng, sinh nhật, bàn gia đình...' }}">{{ old('note') }}</textarea>
                            @error('note') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="reservation-actions">
                        <button type="submit" class="btn-primary" data-loading-text="{{ is_english() ? 'Sending...' : 'Đang gửi yêu cầu...' }}">{{ is_english() ? 'Send request' : 'Gửi yêu cầu giữ bàn' }}</button>
                        <a href="{{ localized_route('local.vegetarian-catering') }}" class="btn-secondary">{{ is_english() ? 'Catering options' : 'Đặt tiệc/mâm cúng' }}</a>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const branchSelect = document.querySelector('[data-branch-time-hint]') ? document.getElementById('branch_id') : null;
            const hint = document.querySelector('[data-branch-time-hint]');
            const timeInput = document.getElementById('reservation_time');
            const datalist = document.getElementById('reservation-time-options');
            if (!branchSelect || !hint || !timeInput || !datalist) return;

            const addMinutes = (time, minutes) => {
                const [hours, mins] = time.split(':').map(Number);
                const date = new Date(2000, 0, 1, hours, mins + minutes);
                return `${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
            };

            const refreshTimes = () => {
                const selected = branchSelect.selectedOptions[0];
                hint.textContent = selected?.dataset.hint || hint.textContent;
                timeInput.min = selected?.dataset.opens || timeInput.min;
                timeInput.max = selected?.dataset.closes || timeInput.max;
                datalist.innerHTML = '';

                let slots = [];
                try {
                    slots = JSON.parse(selected?.dataset.slots || '[]');
                } catch (error) {
                    slots = [];
                }

                slots.forEach((slot) => {
                    for (let cursor = slot.start; cursor <= slot.end; cursor = addMinutes(cursor, 15)) {
                        const option = document.createElement('option');
                        option.value = cursor;
                        datalist.appendChild(option);
                        if (cursor === slot.end) break;
                    }
                });
            };

            branchSelect.addEventListener('change', refreshTimes);
            refreshTimes();
        });
    </script>
@endsection
