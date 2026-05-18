<div
    class="chat-widget"
    data-chat-widget
    data-start-url="{{ route('chat.start') }}"
    data-csrf="{{ csrf_token() }}"
>
    <button type="button" class="chat-toggle" data-chat-toggle aria-expanded="false">
        <span class="chat-pulse" aria-hidden="true"></span>
        <svg viewBox="0 0 24 24" class="h-5 w-5 fill-current" aria-hidden="true">
            <path d="M12 3C6.48 3 2 6.92 2 11.75c0 2.76 1.46 5.22 3.75 6.82V22l3.43-1.72c.9.22 1.85.34 2.82.34 5.52 0 10-3.92 10-8.87S17.52 3 12 3Zm-3.2 9.8a1.3 1.3 0 1 1 0-2.6 1.3 1.3 0 0 1 0 2.6Zm3.2 0a1.3 1.3 0 1 1 0-2.6 1.3 1.3 0 0 1 0 2.6Zm3.2 0a1.3 1.3 0 1 1 0-2.6 1.3 1.3 0 0 1 0 2.6Z" />
        </svg>
        <span>Tư vấn</span>
    </button>

    <section class="chat-panel hidden" data-chat-panel aria-label="Chat online với tư vấn viên">
        <header class="chat-panel-head">
            <div>
                <p class="text-sm font-bold">{{ setting('restaurant_name', 'Đàn Hương Chay') }}</p>
                <p class="text-xs text-emerald-50/80">Tư vấn viên thường phản hồi trong vài phút</p>
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
