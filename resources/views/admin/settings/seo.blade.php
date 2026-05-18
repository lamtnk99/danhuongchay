@extends('admin.layouts.app')

@section('title', $title)

@section('content')
    <form method="POST" action="{{ $action }}" enctype="multipart/form-data" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        @method('PUT')

        <div class="grid gap-5 lg:grid-cols-2">
            @foreach ($keys as $key => $label)
                <div @class(['lg:col-span-2' => in_array($key, ['default_meta_description', 'default_meta_keywords', 'google_analytics_code', 'google_search_console', 'facebook_pixel_code', 'robots_txt_content'])])>
                    <label for="{{ $key }}" class="admin-label">{{ $label }}</label>
                    @if (in_array($key, ['default_meta_description', 'default_meta_keywords', 'google_analytics_code', 'google_search_console', 'facebook_pixel_code', 'robots_txt_content']))
                        <textarea id="{{ $key }}" name="{{ $key }}" rows="4" class="admin-input">{{ old($key, setting($key)) }}</textarea>
                    @else
                        <input id="{{ $key }}" name="{{ $key }}" value="{{ old($key, setting($key)) }}" class="admin-input">
                    @endif
                    @error($key) <p class="form-error">{{ $message }}</p> @enderror
                </div>
            @endforeach

            @foreach ($imageKeys as $key => $label)
                <div class="lg:col-span-2">
                    <label for="{{ $key }}" class="admin-label">{{ $label }}</label>
                    @if (setting($key))
                        <img src="{{ media_url(setting($key)) }}" alt="{{ $label }}" class="mb-3 h-36 w-full rounded-xl object-cover">
                    @endif
                    <input id="{{ $key }}" type="file" name="{{ $key }}" accept=".jpg,.jpeg,.png,.webp,.svg,image/jpeg,image/png,image/webp,image/svg+xml" class="admin-input">
                    <p class="mt-2 text-sm text-slate-500">Chấp nhận JPG, PNG, WEBP, SVG. Dung lượng tối đa 10MB.</p>
                    @error($key) <p class="form-error">{{ $message }}</p> @enderror
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <button type="submit" class="admin-btn-primary">Lưu SEO</button>
            <a href="{{ route('admin.dashboard') }}" class="admin-btn-secondary">Quay lại</a>
        </div>
    </form>
@endsection
