@extends('layouts.app')

@section('content')
    <section class="subpage-hero">
        <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
            <p class="eyebrow">{{ __('site.contact.eyebrow') }}</p>
            <h1 class="mt-4 text-4xl font-semibold text-emerald-950 sm:text-5xl">{{ __('site.contact.title') }}</h1>
            <p class="mt-6 text-lg leading-8 text-stone-700">{{ __('site.contact.description') }}</p>
        </div>
    </section>

    <section class="section-block">
        <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr]">
            <div class="space-y-6">
                <div class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm">
                    <h2 class="text-2xl font-semibold text-emerald-950">{{ __('site.contact.info_title') }}</h2>
                    <dl class="mt-5 space-y-4 text-stone-700">
                        <div>
                            <dt class="font-semibold text-emerald-900">{{ __('site.contact.name') }}</dt>
                            <dd>{{ localized_setting('restaurant_name', 'Đàn Hương Chay') }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-emerald-900">{{ __('site.contact.address') }}</dt>
                            <dd>{{ localized_setting('address', 'Villa 01-B4 Hoàng Mậu - Gia Viên, TP. Hải Phòng') }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-emerald-900">{{ __('site.contact.hotline') }}</dt>
                            <dd>{{ setting('hotline', setting('phone', '0912 345 678')) }}</dd>
                        </div>
                        @if (setting('phone') && setting('phone') !== setting('hotline'))
                            <div>
                                <dt class="font-semibold text-emerald-900">{{ __('site.contact.phone') }}</dt>
                                <dd>{{ setting('phone') }}</dd>
                            </div>
                        @endif
                        <div>
                            <dt class="font-semibold text-emerald-900">Email</dt>
                            <dd>{{ setting('email', 'hello@danhuongchay.vn') }}</dd>
                        </div>
                        <div>
                            <dt class="font-semibold text-emerald-900">{{ __('site.contact.opening_hours') }}</dt>
                            <dd>{{ localized_setting('opening_hours', '09:00 - 21:30') }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="overflow-hidden rounded-3xl border border-emerald-900/10 bg-white shadow-sm">
                    @if (setting('google_map_iframe'))
                        {!! setting('google_map_iframe') !!}
                    @else
                        <iframe title="{{ __('site.contact.map_title') }}" src="https://www.google.com/maps?q=Hai%20Phong&output=embed" class="h-80 w-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    @endif
                </div>
            </div>

            <div class="rounded-3xl border border-emerald-900/10 bg-white p-6 shadow-sm sm:p-8">
                @if (session('success'))
                    <span class="sr-only" data-track-view="contact_success" data-track-category="lead" data-track-label="Contact form success" data-facebook-event="Lead"></span>
                    <div class="mb-6 rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">{{ session('success') }}</div>
                @endif

                <form action="{{ localized_route('contact.store') }}" method="POST" class="grid gap-5" data-submit-loading data-track-submit="submit_contact" data-track-category="lead" data-track-label="Contact form" data-facebook-event="Lead">
                    @csrf
                    <div>
                        <label for="name" class="form-label">{{ __('site.contact.field_name') }}</label>
                        <input id="name" name="name" value="{{ old('name') }}" class="form-input" autocomplete="name" required>
                        @error('name') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <div class="grid gap-5 md:grid-cols-2">
                        <div>
                            <label for="phone" class="form-label">{{ __('site.contact.field_phone') }}</label>
                            <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" class="form-input" inputmode="tel" autocomplete="tel">
                            @error('phone') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="email" class="form-label">{{ __('site.contact.field_email') }}</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-input" inputmode="email" autocomplete="email">
                            @error('email') <p class="form-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div>
                        <label for="message" class="form-label">{{ __('site.contact.field_message') }}</label>
                        <textarea id="message" name="message" rows="6" class="form-input" required>{{ old('message') }}</textarea>
                        @error('message') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
                    <button type="submit" class="btn-primary justify-center" data-loading-text="{{ __('site.contact.loading') }}">{{ __('site.contact.submit') }}</button>
                </form>
            </div>
        </div>
    </section>
@endsection
