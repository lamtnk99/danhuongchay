@extends('admin.layouts.app')

@section('title', 'Dịch tự động')

@section('content')
    @php
        $count = $usage['character_count'] ?? null;
        $limit = $usage['character_limit'] ?? null;
        $percent = $limit ? min(100, round(($count / $limit) * 100, 1)) : null;
        $warningLimit = (int) (setting('translation_monthly_limit_warning') ?: 450000);
    @endphp

    <div class="mb-6 rounded-2xl bg-emerald-950 p-6 text-white shadow-sm">
        <p class="text-sm font-bold uppercase tracking-[0.25em] text-emerald-200">DeepL API Free</p>
        <h1 class="mt-2 text-2xl font-bold">Cài đặt dịch tự động</h1>
        <p class="mt-2 max-w-3xl text-sm leading-7 text-emerald-50/80">
            Dịch tự động chỉ chạy khi admin bấm nút trong tab English. Kết quả sẽ đổ vào form để kiểm tra trước khi lưu, không tự ghi đè nội dung.
        </p>
    </div>

    <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_22rem]">
        <form method="POST" action="{{ route('admin.translations.settings.update') }}" class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            @csrf
            @method('PUT')

            <div class="grid gap-5 lg:grid-cols-2">
                <label class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 font-semibold text-slate-700 lg:col-span-2">
                    <input type="checkbox" name="translation_enabled" value="1" @checked(old('translation_enabled', setting('translation_enabled')) === '1')>
                    Bật dịch tự động bằng DeepL
                </label>

                <div>
                    <label for="deepl_api_plan" class="admin-label">Gói API</label>
                    <select id="deepl_api_plan" name="deepl_api_plan" class="admin-input">
                        <option value="free" @selected(old('deepl_api_plan', setting('deepl_api_plan') ?: 'free') === 'free')>DeepL API Free</option>
                        <option value="pro" @selected(old('deepl_api_plan', setting('deepl_api_plan')) === 'pro')>DeepL API Pro</option>
                    </select>
                    @error('deepl_api_plan') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="deepl_target_lang" class="admin-label">English đích</label>
                    <select id="deepl_target_lang" name="deepl_target_lang" class="admin-input">
                        <option value="EN-US" @selected(old('deepl_target_lang', setting('deepl_target_lang') ?: 'EN-US') === 'EN-US')>English US</option>
                        <option value="EN-GB" @selected(old('deepl_target_lang', setting('deepl_target_lang')) === 'EN-GB')>English UK</option>
                    </select>
                    @error('deepl_target_lang') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="deepl_source_lang" class="admin-label">Ngôn ngữ nguồn</label>
                    <select id="deepl_source_lang" name="deepl_source_lang" class="admin-input">
                        <option value="VI" @selected(old('deepl_source_lang', setting('deepl_source_lang') ?: 'VI') === 'VI')>Tiếng Việt</option>
                    </select>
                    @error('deepl_source_lang') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="translation_monthly_limit_warning" class="admin-label">Cảnh báo khi gần mốc ký tự</label>
                    <input id="translation_monthly_limit_warning" type="number" min="1000" name="translation_monthly_limit_warning" value="{{ old('translation_monthly_limit_warning', $warningLimit) }}" class="admin-input">
                    @error('translation_monthly_limit_warning') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="deepl_api_key" class="admin-label">DeepL API key</label>
                    <input id="deepl_api_key" type="password" name="deepl_api_key" class="admin-input" autocomplete="new-password" placeholder="{{ $hasDeepLKey ? 'Đã lưu API key. Nhập key mới nếu muốn thay đổi.' : 'Dán DeepL API key tại đây' }}">
                    <p class="mt-2 text-sm text-slate-500">
                        Key được lưu mã hóa trong database. DeepL API Free key thường có đuôi <code>:fx</code>.
                    </p>
                    @error('deepl_api_key') <p class="form-error">{{ $message }}</p> @enderror
                </div>

                @if ($hasDeepLKey)
                    <label class="flex items-center gap-2 font-semibold text-red-700 lg:col-span-2">
                        <input type="checkbox" name="clear_deepl_api_key" value="1">
                        Xóa API key hiện tại
                    </label>
                @endif
            </div>

            <div class="mt-6 flex flex-wrap gap-3">
                <button type="submit" class="admin-btn-primary">Lưu cài đặt</button>
                <button type="button" class="admin-btn-secondary" data-deepl-test-url="{{ route('admin.translations.deepl.test') }}">Kiểm tra kết nối</button>
                <button type="button" class="admin-btn-secondary" data-deepl-usage-url="{{ route('admin.translations.deepl.usage') }}">Kiểm tra quota</button>
            </div>

            <div class="mt-4 hidden rounded-2xl border p-4 text-sm" data-deepl-status></div>
        </form>

        <aside class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
            <p class="text-sm font-bold uppercase tracking-[0.18em] text-emerald-700">Quota tháng này</p>
            @if ($usage && $limit)
                <p class="mt-3 text-3xl font-black text-emerald-950">{{ number_format($count) }}</p>
                <p class="text-sm text-slate-500">/ {{ number_format($limit) }} ký tự</p>
                <div class="mt-4 h-3 overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full bg-emerald-600" style="width: {{ $percent }}%"></div>
                </div>
                @if ($count >= $warningLimit)
                    <p class="mt-4 rounded-xl bg-amber-50 p-3 text-sm font-semibold text-amber-800">Quota đang gần mốc cảnh báo, nên ưu tiên dịch nội dung quan trọng.</p>
                @endif
            @else
                <p class="mt-3 text-sm leading-7 text-slate-600">Chưa đọc được quota. Hãy lưu API key rồi bấm “Kiểm tra quota”.</p>
            @endif
        </aside>
    </div>
@endsection
