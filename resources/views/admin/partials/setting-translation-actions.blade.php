<div class="mb-5 rounded-2xl border border-[#667132]/20 bg-[#667132]/8 p-4 text-sm text-[#3d4226]">
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="font-semibold">Bản dịch English</p>
            <p class="mt-1 text-[#667132]/70">Có thể dịch tự động từ nội dung tiếng Việt rồi chỉnh lại trước khi lưu.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button type="button" class="admin-btn-mini" data-deepl-translate data-deepl-url="{{ route('admin.translations.translate') }}">Dịch tự động</button>
            <button type="button" class="admin-btn-mini" data-copy-translation>Copy từ tiếng Việt</button>
        </div>
    </div>
    <div class="mt-3 hidden rounded-xl border bg-white p-3 text-sm" data-deepl-inline-status></div>
</div>
