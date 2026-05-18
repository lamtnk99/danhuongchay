@extends('admin.layouts.app')

@section('title', $title)

@section('content')
    <form method="POST" action="{{ $action }}" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
        @csrf
        @method('PUT')

        <div class="grid gap-5 lg:grid-cols-2">
            @foreach ($keys as $key => $label)
                <div @class(['lg:col-span-2' => in_array($key, ['short_description', 'address', 'google_map_iframe', 'footer_description'])])>
                    <label for="{{ $key }}" class="admin-label">{{ $label }}</label>
                    @if (in_array($key, ['short_description', 'address', 'google_map_iframe', 'footer_description']))
                        <textarea id="{{ $key }}" name="{{ $key }}" rows="4" class="admin-input">{{ old($key, setting($key)) }}</textarea>
                    @else
                        <input id="{{ $key }}" name="{{ $key }}" value="{{ old($key, setting($key)) }}" class="admin-input">
                    @endif
                    @error($key) <p class="form-error">{{ $message }}</p> @enderror
                </div>
            @endforeach
        </div>

        <div class="mt-6 flex flex-wrap gap-3">
            <button type="submit" class="admin-btn-primary">Lưu cài đặt</button>
            <a href="{{ route('admin.dashboard') }}" class="admin-btn-secondary">Quay lại</a>
        </div>
    </form>
@endsection
