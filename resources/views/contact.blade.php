@extends('layouts.app')

@section('content')
    @php
        $mapBranches = $branches->filter(fn ($branch) => filled($branch->google_map_iframe))->values();
    @endphp

    <section class="subpage-hero contact-hero">
        <div class="mx-auto max-w-4xl px-4 py-16 text-center sm:px-6 lg:px-8">
            <p class="eyebrow">{{ __('site.contact.eyebrow') }}</p>
            <h1 class="mt-4 text-4xl font-semibold text-emerald-950 sm:text-5xl">{{ __('site.contact.title') }}</h1>
            <p class="mt-6 text-lg leading-8 text-stone-700">{{ __('site.contact.description') }}</p>
        </div>
    </section>

    <section class="contact-editorial-section">
        <div class="section-block">
        <div class="contact-editorial-grid">
            <div class="space-y-6">
                <div class="contact-panel p-6">
                    <h2 class="text-2xl font-semibold text-emerald-950">{{ is_english() ? 'Branches' : 'Các cơ sở Đàn Hương Chay' }}</h2>
                    <div class="mt-5 grid gap-4">
                        @forelse ($branches as $branch)
                            <article class="contact-branch-card">
                                <h3 class="text-lg font-semibold text-emerald-950">{{ $branch->name }}</h3>
                                @if ($branch->address)
                                    <p class="mt-2 text-sm leading-6 text-stone-700">{{ $branch->address }}</p>
                                @endif
                                <dl class="mt-4 grid gap-2 text-sm text-stone-700">
                                    @if ($branch->hotline)
                                        <div><dt class="inline font-semibold text-emerald-900">Hotline:</dt> <dd class="inline">{{ $branch->hotline }}</dd></div>
                                    @endif
                                    @if ($branch->phone && $branch->phone !== $branch->hotline)
                                        <div><dt class="inline font-semibold text-emerald-900">{{ __('site.contact.phone') }}:</dt> <dd class="inline">{{ $branch->phone }}</dd></div>
                                    @endif
                                    @if ($branch->email)
                                        <div><dt class="inline font-semibold text-emerald-900">Email:</dt> <dd class="inline">{{ $branch->email }}</dd></div>
                                    @endif
                                    @if ($branch->opening_hours)
                                        <div><dt class="inline font-semibold text-emerald-900">{{ __('site.contact.opening_hours') }}:</dt> <dd class="inline">{{ $branch->opening_hours }}</dd></div>
                                    @endif
                                </dl>
                                @include('partials.branch-social-actions', ['branch' => $branch])
                                <div class="mt-4 flex flex-wrap gap-3">
                                    <a href="{{ localized_route('reservations.create', ['branch' => $branch->slug]) }}" class="btn-primary py-3 text-sm">{{ is_english() ? 'Book this branch' : 'Đặt bàn cơ sở này' }}</a>
                                    @if ($branch->hotline || $branch->phone)
                                        <a href="tel:{{ preg_replace('/\D+/', '', $branch->hotline ?: $branch->phone) }}" class="btn-ghost py-3 text-sm">{{ is_english() ? 'Call' : 'Gọi' }}</a>
                                    @endif
                                </div>
                            </article>
                        @empty
                            <p class="text-stone-600">{{ is_english() ? 'Branch information is being updated.' : 'Thông tin cơ sở đang được cập nhật.' }}</p>
                        @endforelse
                    </div>
                </div>

                @if ($mapBranches->isNotEmpty())
                    <div class="about-map-feature contact-map-feature" data-map-slider>
                        <div class="about-map-copy">
                            <p>{{ is_english() ? 'Map' : 'Bản đồ' }}</p>
                            <h3 data-map-title>{{ $mapBranches->first()->name }}</h3>
                            @if ($mapBranches->count() > 1)
                                <div class="about-map-tabs">
                                    @foreach ($mapBranches as $branch)
                                        <button type="button" @class(['is-active' => $loop->first]) data-map-tab="{{ $loop->index }}">{{ $branch->city ?: $branch->name }}</button>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="about-map-slides">
                            @foreach ($mapBranches as $branch)
                                <div @class(['about-map-slide', 'is-active' => $loop->first]) data-map-slide="{{ $loop->index }}" data-title="{{ $branch->name }}">
                                    <div class="map-frame">
                                        {!! $branch->google_map_iframe !!}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="contact-panel p-6 sm:p-8">
                @if (session('success'))
                    <span class="sr-only" data-track-view="contact_success" data-track-category="lead" data-track-label="Contact form success" data-facebook-event="Lead"></span>
                    <div class="mb-6 rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">{{ session('success') }}</div>
                @endif

                <form action="{{ localized_route('contact.store') }}" method="POST" class="grid gap-5" data-submit-loading data-track-submit="submit_contact" data-track-category="lead" data-track-label="Contact form" data-facebook-event="Lead">
                    @csrf
                    <div>
                        <label for="branch_id" class="form-label">{{ is_english() ? 'Branch' : 'Cơ sở cần liên hệ' }}</label>
                        <select id="branch_id" name="branch_id" class="form-input">
                            <option value="">{{ is_english() ? 'General contact' : 'Liên hệ chung' }}</option>
                            @foreach ($branches as $branch)
                                <option value="{{ $branch->id }}" @selected((string) old('branch_id', $selectedBranch?->id) === (string) $branch->id)>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                        @error('branch_id') <p class="form-error">{{ $message }}</p> @enderror
                    </div>
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
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('[data-map-slider]').forEach((slider) => {
                const tabs = [...slider.querySelectorAll('[data-map-tab]')];
                const slides = [...slider.querySelectorAll('[data-map-slide]')];
                const title = slider.querySelector('[data-map-title]');
                if (!tabs.length || slides.length < 2) return;

                const show = (index) => {
                    tabs.forEach((tab, tabIndex) => tab.classList.toggle('is-active', tabIndex === index));
                    slides.forEach((slide, slideIndex) => slide.classList.toggle('is-active', slideIndex === index));
                    if (title) title.textContent = slides[index]?.dataset.title || title.textContent;
                };

                tabs.forEach((tab, index) => tab.addEventListener('click', () => show(index)));
            });
        });
    </script>
@endsection
