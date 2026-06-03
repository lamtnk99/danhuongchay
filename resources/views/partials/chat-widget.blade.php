@php
    $chatBranches = \App\Models\Branch::query()
        ->active()
        ->orderBy('sort_order')
        ->orderBy('name')
        ->get();

    $chatSelectedBranch = $selectedBranch ?? null;

    if (! $chatSelectedBranch && isset($branch) && $branch instanceof \App\Models\Branch) {
        $chatSelectedBranch = $branch;
    }

    if (! $chatSelectedBranch && request()->filled('branch')) {
        $branchParam = request('branch');
        $chatSelectedBranch = $chatBranches->first(fn ($branch) => (string) $branch->id === (string) $branchParam || $branch->slug === $branchParam);
    }

@endphp

<div
    class="chat-widget"
    data-chat-widget
    data-start-url="{{ route('chat.start') }}"
    data-branch-id="{{ $chatSelectedBranch?->id }}"
    data-csrf="{{ csrf_token() }}"
>
    <button type="button" class="chat-toggle" data-chat-toggle aria-expanded="false">
        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
            <path d="M12 3C6.48 3 2 6.92 2 11.75c0 2.76 1.46 5.22 3.75 6.82V22l3.43-1.72c.9.22 1.85.34 2.82.34 5.52 0 10-3.92 10-8.87S17.52 3 12 3Zm-3.2 9.8a1.3 1.3 0 1 1 0-2.6 1.3 1.3 0 0 1 0 2.6Zm3.2 0a1.3 1.3 0 1 1 0-2.6 1.3 1.3 0 0 1 0 2.6Zm3.2 0a1.3 1.3 0 1 1 0-2.6 1.3 1.3 0 0 1 0 2.6Z" />
        </svg>
    </button>

    <nav class="chat-actions hidden" data-chat-actions aria-label="Hành động nhanh">
        <a href="{{ localized_route('reservations.create') }}" class="chat-action-btn chat-action-mobile-hide" aria-label="Đặt bàn">
            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                <path d="M19 4h-1V2h-2v2H8V2H6v2H5c-1.11 0-2 .9-2 2v14a2 2 0 0 0 2 2h14c1.1 0 2-.9 2-2V6a2 2 0 0 0-2-2zm0 16H5V9h14v11zM7 11h5v5H7z"/>
            </svg>
        </a>
        <a href="{{ localized_route('contact') }}" class="chat-action-btn chat-action-mobile-hide" aria-label="Liên hệ">
            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4-8 5-8-5V6l8 5 8-5v2z"/>
            </svg>
        </a>
        <a href="{{ localized_route('menu.index') }}" class="chat-action-btn chat-action-mobile-hide" aria-label="Thực đơn">
            <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
                <path d="M3 4h18v2H3zm0 7h18v2H3zm0 7h18v2H3z"/>
            </svg>
        </a>
    </nav>

    <section class="chat-panel hidden" data-chat-panel aria-label="Chat online với tư vấn viên">
        <header class="chat-panel-head">
            <div>
                <p class="text-sm font-bold">{{ setting('restaurant_name', 'Đàn Hương Chay') }}</p>
                <p class="text-xs text-[#f5f0e0]/80">Tư vấn viên thường phản hồi trong vài phút</p>
            </div>
            <button type="button" data-chat-close class="chat-close" aria-label="Đóng chat">×</button>
        </header>

        <div class="chat-messages" data-chat-messages>
            <div class="chat-bubble admin">
                Xin chào, Đàn Hương Chay có thể hỗ trợ bạn đặt bàn hoặc tư vấn món nào hôm nay?
            </div>
        </div>

        <form class="chat-start-form" data-chat-start-form>
            <div class="grid gap-2">
                <input type="text" name="website" tabindex="-1" autocomplete="off" class="hidden" aria-hidden="true">
                @if ($chatBranches->isNotEmpty())
                    <select name="branch_id" class="chat-input" required aria-label="Cơ sở cần tư vấn">
                        <option value="" @selected(! $chatSelectedBranch)>Chọn cơ sở cần tư vấn</option>
                        @foreach ($chatBranches as $branch)
                            <option value="{{ $branch->id }}" @selected((string) $chatSelectedBranch?->id === (string) $branch->id)>{{ $branch->name }}</option>
                        @endforeach
                    </select>
                @endif
                <input name="visitor_name" class="chat-input" placeholder="Tên của bạn" autocomplete="name">
                <input type="tel" name="phone" class="chat-input" placeholder="Số điện thoại" inputmode="tel" autocomplete="tel">
                <textarea name="message" rows="3" class="chat-input" placeholder="Bạn cần tư vấn gì?" required></textarea>
            </div>
            <button type="submit" class="chat-submit">Bắt đầu chat</button>
            <p class="chat-error hidden" data-chat-error></p>
        </form>

        <form class="chat-send-form hidden" data-chat-send-form>
            <input name="message" class="chat-input" placeholder="Nhập tin nhắn..." autocomplete="off" required>
            <button type="submit" class="chat-send-button">Gửi</button>
        </form>
    </section>
</div>
