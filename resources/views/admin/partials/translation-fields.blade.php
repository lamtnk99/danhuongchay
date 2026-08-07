@php
    $translation = $model->translation($locale ?? 'en');
    $inputPrefix = "translations[{$locale}]";
    $errorPrefix = "translations.{$locale}";
    $isBlogTranslation = isset($translationUrl);
    $state = $translationState ?? 'unknown';
    $stateLabels = [
        'missing' => ['Chưa có bản Anh', 'Tạo bản tiếng Anh'],
        'stale' => ['Bản Anh cần cập nhật', 'Cập nhật bản tiếng Anh'],
        'synced' => ['Bản Anh đã đồng bộ', 'Dịch lại bản tiếng Anh'],
        'unknown' => ['Chưa xác định lần dịch', 'Dịch lại bản tiếng Anh'],
    ];
    [$stateLabel, $translateLabel] = $stateLabels[$state] ?? $stateLabels['unknown'];
@endphp

<div class="rounded-2xl border border-[#667132]/20 bg-[#667132]/8 p-4 text-sm text-[#3d4226]">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <div class="flex flex-wrap items-center gap-2">
                <p class="font-semibold">Bản dịch English</p>
                @if ($isBlogTranslation)
                    <span class="translation-state translation-state-{{ $state }}" data-translation-state data-translation-state-value="{{ $state }}">{{ $stateLabel }}</span>
                @endif
            </div>
            <p class="mt-1 text-[#667132]/70">{{ $isBlogTranslation ? 'Chỉ dùng nút dịch khi viết blog tiếng Anh. Bạn có thể sửa lại bản dịch trước khi bấm Lưu.' : 'Điền nội dung tiếng Anh để frontend `/en` hiển thị đúng ngôn ngữ.' }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button type="button" class="admin-btn-mini" data-deepl-translate data-deepl-url="{{ $translationUrl ?? route('admin.translations.translate') }}">{{ $isBlogTranslation ? $translateLabel : 'Dịch tự động' }}</button>
            <button type="button" class="admin-btn-mini" data-copy-translation>Copy từ tiếng Việt</button>
        </div>
    </div>
    <div class="mt-3 hidden rounded-xl border bg-white p-3 text-sm" data-deepl-inline-status></div>

    @if ($isBlogTranslation && ! empty($englishChecks))
        <div class="mt-3 grid gap-2 sm:grid-cols-2" data-english-checks>
            @foreach ([
                'title' => 'Có tiêu đề', 'slug' => 'Có slug', 'excerpt' => 'Có mô tả ngắn',
                'content' => 'Có nội dung', 'meta_title' => 'Có SEO title', 'meta_description' => 'Có meta description',
                'different_content' => 'Đã dịch khỏi tiếng Việt', 'matching_structure' => 'Giữ đầu mục và liên kết',
            ] as $key => $label)
                <span @class(['english-check', 'is-pass' => $englishChecks[$key] ?? false])>{{ ($englishChecks[$key] ?? false) ? '✓' : '!' }} {{ $label }}</span>
            @endforeach
        </div>
    @endif
</div>

<div class="mt-5 grid gap-5 lg:grid-cols-2">
    @foreach ($fields as $field)
        @php
            $name = $field['name'];
            $type = $field['type'] ?? 'text';
            $rows = $field['rows'] ?? 3;
            $copyFrom = $field['copy_from'] ?? $name;
            $value = old("translations.{$locale}.{$name}", $translation?->{$name});
        @endphp

        <div class="{{ ($field['wide'] ?? false) ? 'lg:col-span-2' : '' }}">
            <label class="admin-label" for="{{ $locale }}_{{ $name }}">{{ $field['label'] }}</label>

            @if ($type === 'textarea')
                <textarea
                    id="{{ $locale }}_{{ $name }}"
                    name="{{ $inputPrefix }}[{{ $name }}]"
                    rows="{{ $rows }}"
                    class="admin-input"
                    data-copy-field="{{ $copyFrom }}"
                    data-translation-field="{{ $name }}"
                    @if ($field['editor'] ?? false) data-rich-text data-editor-locale="{{ $locale }}" @endif
                >{{ $value }}</textarea>
            @else
                <input
                    id="{{ $locale }}_{{ $name }}"
                    name="{{ $inputPrefix }}[{{ $name }}]"
                    value="{{ $value }}"
                    class="admin-input"
                    data-copy-field="{{ $copyFrom }}"
                    data-translation-field="{{ $name }}"
                >
            @endif

            @error("{$errorPrefix}.{$name}") <p class="form-error">{{ $message }}</p> @enderror
        </div>
    @endforeach
</div>
