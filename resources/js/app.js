const toggle = document.querySelector('.mobile-nav-toggle');
const mobileNav = document.querySelector('#mobile-nav');

if (toggle && mobileNav) {
    const closeMobileNav = () => {
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', 'Mở menu');
        mobileNav.classList.remove('is-open');
        document.body.classList.remove('mobile-nav-open');

        window.setTimeout(() => {
            if (toggle.getAttribute('aria-expanded') === 'false') {
                mobileNav.hidden = true;
            }
        }, 220);
    };

    toggle.addEventListener('click', () => {
        const expanded = toggle.getAttribute('aria-expanded') === 'true';

        if (expanded) {
            closeMobileNav();
            return;
        }

        mobileNav.hidden = false;
        window.requestAnimationFrame(() => {
            toggle.setAttribute('aria-expanded', 'true');
            toggle.setAttribute('aria-label', 'Đóng menu');
            mobileNav.classList.add('is-open');
            document.body.classList.add('mobile-nav-open');
        });
    });

    mobileNav.querySelectorAll('a').forEach((link) => link.addEventListener('click', closeMobileNav));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
            closeMobileNav();
        }
    });
}

const stickyHeader = document.querySelector('header.sticky');

if (stickyHeader) {
    const updateHeaderShadow = () => stickyHeader.classList.toggle('is-scrolled', window.scrollY > 12);
    updateHeaderShadow();
    window.addEventListener('scroll', updateHeaderShadow, { passive: true });
}

document.querySelectorAll('form[data-submit-loading]').forEach((form) => {
    form.addEventListener('submit', () => {
        if (!form.checkValidity()) {
            return;
        }

        const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');

        if (!submitButton) {
            return;
        }

        submitButton.disabled = true;
        submitButton.classList.add('is-loading');

        if (submitButton.tagName === 'BUTTON') {
            submitButton.dataset.originalText = submitButton.textContent.trim();
            submitButton.textContent = submitButton.dataset.loadingText || 'Đang gửi...';
        }
    });
});

const revealTargets = document.querySelectorAll('.section-block, .dish-card, .promo-card, .reason-card, .commitment-card, .home-gallery-card, .gallery-tile, .about-gallery-card, article.group');

if ('IntersectionObserver' in window) {
    revealTargets.forEach((target) => target.classList.add('reveal-ready'));

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.14 });

    revealTargets.forEach((target) => revealObserver.observe(target));
}

document.querySelectorAll('[data-testimonial-slider]').forEach((slider) => {
    const track = slider.querySelector('[data-testimonial-track]');
    const slides = Array.from(slider.querySelectorAll('[data-testimonial-slide]'));
    const dots = Array.from(slider.querySelectorAll('[data-testimonial-dot]'));
    const prevButton = slider.querySelector('[data-testimonial-prev]');
    const nextButton = slider.querySelector('[data-testimonial-next]');
    let current = 0;
    let autoplay = null;

    if (!track || slides.length < 1) {
        return;
    }

    const getStep = () => {
        if (slides.length > 1) {
            return Math.max(1, slides[1].offsetLeft - slides[0].offsetLeft);
        }

        return Math.max(1, slides[0].getBoundingClientRect().width);
    };

    const getMaxIndex = () => {
        const viewportWidth = slider.querySelector('.testimonial-viewport')?.clientWidth || track.clientWidth;
        const visibleCount = Math.max(1, Math.floor(viewportWidth / getStep()));

        return Math.max(0, slides.length - visibleCount);
    };

    const goTo = (index) => {
        const maxIndex = getMaxIndex();
        current = Math.min(Math.max(index, 0), maxIndex);
        track.style.transform = `translateX(-${slides[current].offsetLeft}px)`;

        dots.forEach((dot, dotIndex) => {
            dot.classList.toggle('is-active', dotIndex === current);
            dot.hidden = dotIndex > maxIndex;
        });
    };

    const stop = () => {
        if (autoplay) {
            window.clearInterval(autoplay);
            autoplay = null;
        }
    };

    const start = () => {
        if (autoplay || slides.length < 2 || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            return;
        }

        autoplay = window.setInterval(() => {
            const next = current >= getMaxIndex() ? 0 : current + 1;
            goTo(next);
        }, 4500);
    };

    prevButton?.addEventListener('click', () => goTo(current <= 0 ? getMaxIndex() : current - 1));
    nextButton?.addEventListener('click', () => goTo(current >= getMaxIndex() ? 0 : current + 1));
    dots.forEach((dot) => dot.addEventListener('click', () => goTo(Number(dot.dataset.index || 0))));
    slider.addEventListener('mouseenter', stop);
    slider.addEventListener('mouseleave', start);
    slider.addEventListener('focusin', stop);
    slider.addEventListener('focusout', start);
    window.addEventListener('resize', () => goTo(current), { passive: true });

    goTo(0);
    start();
});

const promoPopup = document.querySelector('[data-promo-popup]');

if (promoPopup) {
    const promoId = promoPopup.dataset.promoId;
    const storageKey = `danhuong_promo_seen_${promoId}`;
    const showOnce = promoPopup.dataset.showOnce === '1';
    const closeButtons = promoPopup.querySelectorAll('[data-promo-close], [data-promo-action]');

    const wasSeen = () => {
        try {
            return showOnce && window.localStorage.getItem(storageKey) === '1';
        } catch {
            return false;
        }
    };

    const markSeen = () => {
        try {
            window.localStorage.setItem(storageKey, '1');
        } catch {
            // Local storage can be unavailable in some privacy modes.
        }
    };

    const closePopup = () => {
        promoPopup.classList.add('hidden');
        markSeen();
    };

    if (!wasSeen()) {
        window.setTimeout(() => {
            promoPopup.classList.remove('hidden');
        }, 900);
    }

    closeButtons.forEach((button) => button.addEventListener('click', closePopup));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !promoPopup.classList.contains('hidden')) {
            closePopup();
        }
    });
}

const reservationDateInput = document.querySelector('[data-reservation-date]');
const reservationTimeInput = document.querySelector('[data-reservation-time]');

if (reservationDateInput && reservationTimeInput) {
    const hint = document.querySelector('[data-reservation-time-hint]');
    const opensAt = reservationTimeInput.dataset.opensAt;
    const closesAt = reservationTimeInput.dataset.closesAt;
    const today = reservationTimeInput.dataset.today;

    const currentTime = () => {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');

        return `${hours}:${minutes}`;
    };

    const updateReservationTimeLimits = () => {
        let minTime = opensAt;
        let message = `Quán nhận đặt bàn trong khung ${opensAt} - ${closesAt}.`;

        if (reservationDateInput.value === today) {
            minTime = [opensAt, currentTime()].sort().at(-1);
            message = `Hôm nay chỉ nhận giờ từ ${minTime} đến ${closesAt}.`;
        }

        reservationTimeInput.min = minTime;
        reservationTimeInput.max = closesAt;

        if (reservationTimeInput.value && (reservationTimeInput.value < minTime || reservationTimeInput.value > closesAt)) {
            reservationTimeInput.value = '';
        }

        if (hint) {
            hint.textContent = message;
        }
    };

    reservationDateInput.addEventListener('change', updateReservationTimeLimits);
    updateReservationTimeLimits();
}

document.querySelectorAll('input[type="file"]').forEach((input) => {
    input.addEventListener('change', () => {
        const maxKb = Number(document.documentElement.dataset.uploadMaxKb || 10240);
        const maxMb = Math.round(maxKb / 1024);
        const maxBytes = maxKb * 1024;
        const oversizedFiles = Array.from(input.files || []).filter((file) => file.size > maxBytes);
        const existingError = input.parentElement?.querySelector('[data-upload-client-error]');

        existingError?.remove();

        if (!oversizedFiles.length) {
            return;
        }

        input.value = '';

        const error = document.createElement('p');
        error.className = 'form-error';
        error.dataset.uploadClientError = 'true';
        error.textContent = `Ảnh vượt quá ${maxMb}MB nên chưa thể tải lên. Vui lòng chọn ảnh nhỏ hơn, hệ thống sẽ tự nén sau khi lưu.`;
        input.insertAdjacentElement('afterend', error);
    });
});

document.querySelectorAll('[data-admin-color-picker]').forEach((picker) => {
    const colorPicker = picker.querySelector('[data-color-picker]');
    const colorValue = picker.querySelector('[data-color-value]');
    const colorCode = picker.querySelector('[data-color-code]');
    const swatches = Array.from(picker.querySelectorAll('[data-color-swatch]'));

    const setColor = (color) => {
        if (!/^#[0-9a-fA-F]{6}$/.test(color)) {
            return;
        }

        const normalized = color.toUpperCase();
        colorPicker.value = normalized;
        colorValue.value = normalized;
        colorCode.textContent = normalized;

        swatches.forEach((swatch) => {
            swatch.classList.toggle('is-active', swatch.dataset.colorSwatch.toUpperCase() === normalized);
        });
    };

    colorPicker?.addEventListener('input', () => setColor(colorPicker.value));
    swatches.forEach((swatch) => {
        swatch.addEventListener('click', () => setColor(swatch.dataset.colorSwatch));
    });

    if (colorValue?.value) {
        setColor(colorValue.value);
    }
});

document.querySelectorAll('[data-admin-link-picker]').forEach((picker) => {
    const suggestion = picker.querySelector('[data-link-suggestion]');
    const input = picker.querySelector('input[name="button_link"]');

    suggestion?.addEventListener('change', () => {
        if (suggestion.value && input) {
            input.value = suggestion.value;
            input.focus();
        }
    });
});

const promotionPlacement = document.querySelector('select[name="placement"]');
const popupOnlyFields = document.querySelector('[data-popup-only]');

if (promotionPlacement && popupOnlyFields) {
    const popupCheckbox = popupOnlyFields.querySelector('input[name="show_once"]');
    const updatePopupFields = () => {
        const isPopup = promotionPlacement.value === 'popup';

        popupOnlyFields.classList.toggle('hidden', !isPopup);
        if (popupCheckbox) {
            popupCheckbox.disabled = !isPopup;
        }
    };

    promotionPlacement.addEventListener('change', updatePopupFields);
    updatePopupFields();
}

const chatWidget = document.querySelector('[data-chat-widget]');

if (chatWidget) {
    const toggleButton = chatWidget.querySelector('[data-chat-toggle]');
    const closeButton = chatWidget.querySelector('[data-chat-close]');
    const panel = chatWidget.querySelector('[data-chat-panel]');
    const startForm = chatWidget.querySelector('[data-chat-start-form]');
    const sendForm = chatWidget.querySelector('[data-chat-send-form]');
    const messagesBox = chatWidget.querySelector('[data-chat-messages]');
    const errorBox = chatWidget.querySelector('[data-chat-error]');
    const csrfToken = chatWidget.dataset.csrf;
    let chatSessionId = window.sessionStorage.getItem('danhuong_chat_session_id');
    let pollTimer = null;

    const request = async (url, options = {}) => {
        const response = await fetch(url, {
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                ...(options.body instanceof FormData ? {} : { 'Content-Type': 'application/json' }),
            },
            credentials: 'same-origin',
            ...options,
        });

        if (!response.ok) {
            throw new Error('Không gửi được tin nhắn. Bạn thử lại giúp mình nhé.');
        }

        return response.json();
    };

    const renderMessages = (messages) => {
        messagesBox.innerHTML = '';

        messages.forEach((item) => {
            const bubble = document.createElement('div');
            bubble.className = `chat-bubble ${item.sender === 'visitor' ? 'visitor' : 'admin'}`;
            bubble.textContent = item.message;
            messagesBox.appendChild(bubble);
        });

        messagesBox.scrollTop = messagesBox.scrollHeight;
    };

    const showError = (message) => {
        errorBox.textContent = message;
        errorBox.classList.remove('hidden');
    };

    const loadMessages = async () => {
        if (!chatSessionId) {
            return;
        }

        try {
            const data = await request(`/chat/${chatSessionId}/messages`);
            renderMessages(data.messages);
            startForm.classList.add('hidden');
            sendForm.classList.remove('hidden');
        } catch {
            window.sessionStorage.removeItem('danhuong_chat_session_id');
            chatSessionId = null;
            if (pollTimer) {
                clearInterval(pollTimer);
            }
        }
    };

    const startPolling = () => {
        if (pollTimer || !chatSessionId) {
            return;
        }

        pollTimer = window.setInterval(loadMessages, 6000);
    };

    toggleButton.addEventListener('click', () => {
        const expanded = toggleButton.getAttribute('aria-expanded') === 'true';
        toggleButton.setAttribute('aria-expanded', String(!expanded));
        panel.classList.toggle('hidden', expanded);

        if (!expanded && chatSessionId) {
            loadMessages();
            startPolling();
        }
    });

    closeButton.addEventListener('click', () => {
        toggleButton.setAttribute('aria-expanded', 'false');
        panel.classList.add('hidden');
    });

    startForm.addEventListener('submit', async (event) => {
        event.preventDefault();
        errorBox.classList.add('hidden');

        try {
            const formData = new FormData(startForm);
            const data = await request(chatWidget.dataset.startUrl, {
                method: 'POST',
                body: formData,
            });

            chatSessionId = data.session_id;
            window.sessionStorage.setItem('danhuong_chat_session_id', chatSessionId);
            renderMessages(data.messages);
            startForm.classList.add('hidden');
            sendForm.classList.remove('hidden');
            startPolling();
        } catch (error) {
            showError(error.message);
        }
    });

    sendForm.addEventListener('submit', async (event) => {
        event.preventDefault();

        if (!chatSessionId) {
            return;
        }

        const input = sendForm.querySelector('input[name="message"]');
        const message = input.value.trim();

        if (!message) {
            return;
        }

        input.value = '';

        try {
            const data = await request(`/chat/${chatSessionId}/messages`, {
                method: 'POST',
                body: JSON.stringify({ message }),
            });
            renderMessages(data.messages);
        } catch (error) {
            input.value = message;
            showError(error.message);
        }
    });

    if (chatSessionId) {
        startForm.classList.add('hidden');
        sendForm.classList.remove('hidden');
        loadMessages();
        startPolling();
    }
}

const notificationHeader = document.querySelector('[data-admin-notifications-url]');

if (notificationHeader) {
    const url = notificationHeader.dataset.adminNotificationsUrl;
    const bell = document.querySelector('[data-admin-bell]');
    const panel = document.querySelector('[data-admin-notification-panel]');
    const countNode = document.querySelector('[data-admin-notification-count]');
    const listNode = document.querySelector('[data-admin-notification-list]');
    const summaryNode = document.querySelector('[data-admin-notification-summary]');
    const sidebarBadges = document.querySelectorAll('[data-admin-badge]');

    const updateBadge = (node, count) => {
        if (!node) {
            return;
        }

        node.textContent = count > 99 ? '99+' : String(count);
        node.classList.toggle('hidden', count < 1);
    };

    const escapeNotificationHtml = (value) => String(value ?? '')
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

    const renderNotifications = (data) => {
        updateBadge(countNode, data.counts.total || 0);
        summaryNode.textContent = `${data.counts.total || 0} mục cần xem`;

        sidebarBadges.forEach((badge) => {
            updateBadge(badge, data.counts[badge.dataset.adminBadge] || 0);
        });

        if (!data.items.length) {
            listNode.innerHTML = '<p class="px-4 py-5 text-sm text-slate-500">Không có thông báo mới.</p>';
            return;
        }

        listNode.innerHTML = data.items.map((item) => `
            <a href="${item.url}" class="admin-notification-item">
                <div class="flex items-center justify-between gap-3">
                    <p class="font-bold text-slate-900">${escapeNotificationHtml(item.title)}</p>
                    <span class="text-xs font-semibold text-slate-400">${escapeNotificationHtml(item.time)}</span>
                </div>
                <p class="mt-1 line-clamp-2 text-sm text-slate-500">${escapeNotificationHtml(item.body)}</p>
                <span class="mt-2 inline-flex rounded-full bg-emerald-50 px-2 py-1 text-xs font-bold uppercase text-emerald-700">${escapeNotificationHtml(item.type)}</span>
            </a>
        `).join('');
    };

    const loadNotifications = async () => {
        try {
            const response = await fetch(url, {
                headers: { Accept: 'application/json' },
                credentials: 'same-origin',
            });

            if (response.ok) {
                renderNotifications(await response.json());
            }
        } catch {
            // Polling should stay quiet; admin can keep working even if one request fails.
        }
    };

    bell?.addEventListener('click', () => {
        const expanded = bell.getAttribute('aria-expanded') === 'true';
        bell.setAttribute('aria-expanded', String(!expanded));
        panel?.classList.toggle('hidden', expanded);
        if (!expanded) {
            loadNotifications();
        }
    });

    document.addEventListener('click', (event) => {
        if (!panel || !bell || panel.classList.contains('hidden')) {
            return;
        }

        if (!panel.contains(event.target) && !bell.contains(event.target)) {
            bell.setAttribute('aria-expanded', 'false');
            panel.classList.add('hidden');
        }
    });

    loadNotifications();
    window.setInterval(loadNotifications, 8000);
}

const adminChatMessages = document.querySelector('[data-admin-chat-messages]');

if (adminChatMessages) {
    const url = adminChatMessages.dataset.adminChatUrl;
    let lastRendered = '';

    const escapeHtml = (value) => value
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;')
        .replaceAll("'", '&#039;');

    const renderAdminChat = (messages) => {
        const html = messages.map((item) => {
            const isAdmin = item.sender === 'admin';
            return `
                <div class="${isAdmin ? 'ml-auto bg-emerald-700 text-white' : 'bg-slate-100 text-slate-800'} max-w-[82%] rounded-2xl px-4 py-3 text-sm leading-6">
                    <p class="text-xs font-bold opacity-75">${escapeHtml(item.sender_name)} - ${item.created_at}</p>
                    <p class="mt-1 whitespace-pre-line">${escapeHtml(item.message)}</p>
                </div>
            `;
        }).join('');

        if (html !== lastRendered) {
            adminChatMessages.innerHTML = html;
            adminChatMessages.scrollTop = adminChatMessages.scrollHeight;
            lastRendered = html;
        }
    };

    const loadAdminChat = async () => {
        try {
            const response = await fetch(url, {
                headers: { Accept: 'application/json' },
                credentials: 'same-origin',
            });

            if (response.ok) {
                const data = await response.json();
                renderAdminChat(data.messages);
            }
        } catch {
            // Silent retry on next polling tick.
        }
    };

    loadAdminChat();
    window.setInterval(loadAdminChat, 5000);
}
