@extends('layouts.app')

@section('content')
    <section class="reservation-luxury">
        <div class="reservation-luxury-media">
            <img
                src="{{ media_variant_url(setting('default_background'), 'hero', 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1800&q=85') }}"
                alt="{{ __('site.reservation.image_alt') }}"
                fetchpriority="high"
            >
        </div>
        <div class="reservation-luxury-shade"></div>

        <div class="reservation-luxury-inner">
            <div class="reservation-luxury-copy">
                <p>{{ __('site.reservation.eyebrow') }}</p>
                <h1>{{ __('site.reservation.title') }}</h1>
                <span>{{ __('site.reservation.description') }}</span>
            </div>

            <div class="reservation-luxury-card">
                @if (session('success'))
                    <span class="sr-only" data-track-view="reservation_success" data-track-category="reservation" data-track-label="Reservation form success" data-facebook-event="SubmitReservation"></span>
                    <div class="mb-5 rounded-2xl bg-[#667132]/8 px-4 py-3 text-sm font-semibold text-[#3d4226]">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ localized_route('reservations.store') }}" method="POST" data-submit-loading data-track-submit="submit_reservation" data-track-category="reservation" data-track-label="Reservation form" data-facebook-event="SubmitReservation">
                    @csrf

                    <div class="reservation-form-head">
                        <p>{{ __('site.reservation.form_eyebrow') }}</p>
                        <h2>{{ __('site.reservation.form_title') }}</h2>
                    </div>

                    <div class="mt-6 grid gap-4">
                        <div>
                            <label for="branch_id" class="form-label">{{ __('site.reservation.branch') }}</label>
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

                        <div class="grid gap-4 sm:grid-cols-3">
                            <div>
                                <label for="name" class="form-label">{{ __('site.reservation.full_name') }}</label>
                                <input id="name" name="name" value="{{ old('name') }}" class="form-input" autocomplete="name" required>
                                @error('name') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="phone" class="form-label">{{ __('site.reservation.phone') }}</label>
                                <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" class="form-input" inputmode="tel" autocomplete="tel" required>
                                @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="email" class="form-label">{{ __('site.reservation.email') }}</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" inputmode="email" autocomplete="email" placeholder="{{ __('site.reservation.email_placeholder') }}">
                                <p class="mt-1 text-xs text-stone-500">{{ __('site.reservation.email_hint') }}</p>
                                @error('email') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-3">
                            <div>
                                <label for="reservation_date" class="form-label">{{ __('site.reservation.date') }}</label>
                                <input id="reservation_date" type="date" name="reservation_date" value="{{ old('reservation_date') }}" min="{{ now()->toDateString() }}" class="form-input" autocomplete="off" required>
                                @error('reservation_date') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="reservation_time" class="form-label">{{ __('site.reservation.time') }}</label>
                                <input id="reservation_time" type="time" list="reservation-time-options" name="reservation_time" value="{{ old('reservation_time') }}" min="{{ $openingHours->opensAt }}" max="{{ $openingHours->closesAt }}" step="900" class="form-input" required>
                                <datalist id="reservation-time-options"></datalist>
                                @error('reservation_time') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="guests" class="form-label">{{ __('site.reservation.guests') }}</label>
                                <input id="guests" type="number" name="guests" value="{{ old('guests', 2) }}" min="1" max="40" class="form-input" inputmode="numeric" required>
                                @error('guests') <p class="form-error">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="note" class="form-label">{{ __('site.reservation.note') }}</label>
                            <textarea id="note" name="note" rows="4" class="form-input" placeholder="{{ __('site.reservation.note_placeholder') }}">{{ old('note') }}</textarea>
                            @error('note') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="reservation-actions">
                        <button type="submit" class="btn-primary" data-loading-text="{{ __('site.reservation.loading') }}">{{ __('site.reservation.submit') }}</button>
                        <a href="{{ localized_route('local.vegetarian-catering') }}" class="btn-secondary">{{ __('site.reservation.catering_options') }}</a>
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
