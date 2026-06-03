@if (session('success'))
    <div class="mb-5 rounded-2xl border border-[#667132]/20 bg-[#667132]/8 px-4 py-3 text-sm font-semibold text-[#3d4226]">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="mb-5 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800">
        {{ session('error') }}
    </div>
@endif
