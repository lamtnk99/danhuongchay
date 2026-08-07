import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';
import Link from '@tiptap/extension-link';

const i18n = window.danHuongI18n || {};
const i18nMessage = (key, replacements = {}, fallback = '') => {
    let message = i18n[key] || fallback;

    Object.entries(replacements).forEach(([name, value]) => {
        message = message.replaceAll(`:${name}`, value);
    });

    return message;
};

const isGalleryPage = document.body.classList.contains('page-gallery');

if (isGalleryPage) {
    const loader = document.querySelector('#page-loader');
    if (loader) {
        loader.remove();
    }
}

const toggle = document.querySelector('.mobile-nav-toggle');
const mobileNav = document.querySelector('#mobile-nav');

if (toggle && mobileNav) {
    const closeMobileNav = () => {
        toggle.setAttribute('aria-expanded', 'false');
        toggle.setAttribute('aria-label', i18nMessage('openMenu', {}, 'Open menu'));
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
            toggle.setAttribute('aria-label', i18nMessage('closeMenu', {}, 'Close menu'));
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

document.querySelectorAll('[data-admin-tabs]').forEach((tabs) => {
    const buttons = Array.from(tabs.querySelectorAll('[data-admin-tab]'));
    const panels = Array.from(tabs.querySelectorAll('[data-admin-tab-panel]'));

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            const target = button.dataset.adminTab;

            buttons.forEach((item) => item.classList.toggle('is-active', item === button));
            panels.forEach((panel) => {
                const isActive = panel.dataset.adminTabPanel === target;
                panel.hidden = !isActive;
                panel.classList.toggle('is-active', isActive);
            });
        });
    });

    tabs.querySelectorAll('[data-copy-translation]').forEach((copyButton) => {
        copyButton.addEventListener('click', () => {
            tabs.querySelectorAll('[data-copy-field]').forEach((target) => {
                const source = tabs.querySelector(`[name="${target.dataset.copyField}"]`);

                if (source && !target.value) {
                    target.value = source.value || source.textContent || '';
                    target.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });
        });
    });
});

const countEditorWords = (html) => {
    const temporary = document.createElement('div');
    temporary.innerHTML = html || '';
    return (temporary.textContent || '').trim().split(/\s+/u).filter(Boolean).length;
};

document.querySelectorAll('textarea[data-rich-text]').forEach((textarea) => {
    const shell = document.createElement('div');
    shell.className = 'rich-text-editor';
    shell.innerHTML = `
        <div class="rich-text-toolbar" role="toolbar" aria-label="Công cụ soạn thảo">
            <button type="button" data-editor-action="paragraph" title="Đoạn văn">P</button>
            <button type="button" data-editor-action="h2" title="Đầu mục lớn">H2</button>
            <button type="button" data-editor-action="h3" title="Đầu mục nhỏ">H3</button>
            <span aria-hidden="true"></span>
            <button type="button" data-editor-action="bold" title="In đậm"><strong>B</strong></button>
            <button type="button" data-editor-action="italic" title="In nghiêng"><em>I</em></button>
            <button type="button" data-editor-action="bullet" title="Danh sách chấm">• List</button>
            <button type="button" data-editor-action="ordered" title="Danh sách số">1. List</button>
            <button type="button" data-editor-action="quote" title="Trích dẫn">“ ”</button>
            <button type="button" data-editor-action="link" title="Chèn liên kết">Link</button>
            <button type="button" data-editor-action="unlink" title="Xóa liên kết">Unlink</button>
            <span aria-hidden="true"></span>
            <button type="button" data-editor-action="undo" title="Hoàn tác">↶</button>
            <button type="button" data-editor-action="redo" title="Làm lại">↷</button>
        </div>
        <div class="rich-text-surface"></div>
        <div class="rich-text-metrics" aria-live="polite"></div>
        <details class="rich-text-source">
            <summary>Chỉnh sửa HTML nâng cao</summary>
            <textarea rows="10" spellcheck="false" aria-label="Mã HTML nâng cao"></textarea>
        </details>
    `;

    textarea.insertAdjacentElement('beforebegin', shell);
    textarea.classList.add('rich-text-source-field');
    const wasRequired = textarea.required;
    textarea.required = false;
    const surface = shell.querySelector('.rich-text-surface');
    const source = shell.querySelector('.rich-text-source textarea');
    const metrics = shell.querySelector('.rich-text-metrics');
    let syncing = false;

    const editor = new Editor({
        element: surface,
        extensions: [
            StarterKit.configure({ heading: { levels: [2, 3] }, link: false }),
            Link.configure({ openOnClick: false, autolink: true, defaultProtocol: 'https' }),
        ],
        content: textarea.value || '',
        editorProps: {
            attributes: {
                class: 'rich-text-prosemirror',
                'aria-label': textarea.dataset.editorLocale === 'en' ? 'English article content' : 'Nội dung bài viết',
            },
        },
        onUpdate: ({ editor: currentEditor }) => {
            if (syncing) return;
            const html = currentEditor.getHTML();
            textarea.value = html === '<p></p>' ? '' : html;
            source.value = textarea.value;
            textarea.dispatchEvent(new Event('input', { bubbles: true }));
            refreshMetrics();
        },
        onSelectionUpdate: refreshToolbar,
        onTransaction: refreshToolbar,
    });

    const refreshMetrics = () => {
        const words = countEditorWords(textarea.value);
        const headings = (textarea.value.match(/<h[23]\b/gi) || []).length;
        const speed = textarea.dataset.editorLocale === 'en' ? 200 : 220;
        metrics.textContent = `${words.toLocaleString('vi-VN')} từ · ${headings} đầu mục · ${Math.max(1, Math.ceil(words / speed))} phút đọc`;
    };

    function refreshToolbar() {
        shell.querySelectorAll('[data-editor-action]').forEach((button) => {
            const action = button.dataset.editorAction;
            const active = (action === 'paragraph' && editor.isActive('paragraph'))
                || (action === 'h2' && editor.isActive('heading', { level: 2 }))
                || (action === 'h3' && editor.isActive('heading', { level: 3 }))
                || (action === 'bold' && editor.isActive('bold'))
                || (action === 'italic' && editor.isActive('italic'))
                || (action === 'bullet' && editor.isActive('bulletList'))
                || (action === 'ordered' && editor.isActive('orderedList'))
                || (action === 'quote' && editor.isActive('blockquote'))
                || (action === 'link' && editor.isActive('link'));
            button.classList.toggle('is-active', active);
        });
    }

    const actions = {
        paragraph: () => editor.chain().focus().setParagraph().run(),
        h2: () => editor.chain().focus().toggleHeading({ level: 2 }).run(),
        h3: () => editor.chain().focus().toggleHeading({ level: 3 }).run(),
        bold: () => editor.chain().focus().toggleBold().run(),
        italic: () => editor.chain().focus().toggleItalic().run(),
        bullet: () => editor.chain().focus().toggleBulletList().run(),
        ordered: () => editor.chain().focus().toggleOrderedList().run(),
        quote: () => editor.chain().focus().toggleBlockquote().run(),
        unlink: () => editor.chain().focus().unsetLink().run(),
        undo: () => editor.chain().focus().undo().run(),
        redo: () => editor.chain().focus().redo().run(),
        link: () => {
            const previousUrl = editor.getAttributes('link').href || '';
            const url = window.prompt('Nhập đường dẫn liên kết:', previousUrl);
            if (url === null) return;
            if (!url.trim()) {
                editor.chain().focus().unsetLink().run();
                return;
            }
            editor.chain().focus().extendMarkRange('link').setLink({ href: url.trim() }).run();
        },
    };

    shell.querySelector('.rich-text-toolbar').addEventListener('click', (event) => {
        const button = event.target.closest('[data-editor-action]');
        if (button) actions[button.dataset.editorAction]?.();
    });

    source.value = textarea.value;
    source.addEventListener('input', () => {
        syncing = true;
        textarea.value = source.value;
        editor.commands.setContent(source.value || '', { emitUpdate: false });
        syncing = false;
        textarea.dispatchEvent(new Event('input', { bubbles: true }));
        refreshMetrics();
    });

    textarea.addEventListener('input', () => {
        if (syncing || editor.getHTML() === textarea.value) return;
        syncing = true;
        editor.commands.setContent(textarea.value || '', { emitUpdate: false });
        source.value = textarea.value;
        syncing = false;
        refreshMetrics();
    });

    textarea.closest('form')?.addEventListener('submit', (event) => {
        if (wasRequired && !textarea.value.trim()) {
            event.preventDefault();
            shell.scrollIntoView({ behavior: 'smooth', block: 'center' });
            editor.commands.focus();
            shell.classList.add('has-error');
        }
    });

    refreshMetrics();
    refreshToolbar();
});

document.querySelectorAll('[data-related-post-picker]').forEach((picker) => {
    const search = picker.querySelector('[data-related-post-search]');
    const options = Array.from(picker.querySelectorAll('[data-related-post-option]'));
    const selectedList = picker.querySelector('[data-related-post-selected]');
    const emptyState = picker.querySelector('[data-related-post-empty]');
    const noResults = picker.querySelector('[data-related-post-no-results]');
    const count = picker.querySelector('[data-related-post-count]');
    const status = picker.querySelector('[data-related-post-status]');
    const maxItems = Number(picker.dataset.maxItems || 6);

    const normalize = (value) => String(value || '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLocaleLowerCase('vi')
        .replaceAll('đ', 'd')
        .trim();

    const selectedItems = () => Array.from(selectedList.querySelectorAll('[data-selected-related-post]'));
    const selectedIds = () => new Set(selectedItems().map((item) => item.dataset.postId));

    const refresh = () => {
        const selected = selectedItems();
        const ids = selectedIds();
        const query = normalize(search.value);
        const reachedLimit = selected.length >= maxItems;
        let visibleOptions = 0;

        selected.forEach((item, index) => {
            const position = item.querySelector('[data-related-post-position]');
            if (position) {
                position.textContent = String(index + 1);
            }
        });

        options.forEach((option) => {
            const isSelected = ids.has(option.dataset.postId);
            const haystack = normalize(`${option.dataset.postTitle} ${option.dataset.postCategory}`);
            const matches = !query || haystack.includes(query);

            option.hidden = isSelected || !matches;
            option.disabled = !isSelected && reachedLimit;
            if (!option.hidden) {
                visibleOptions += 1;
            }
        });

        emptyState.classList.toggle('hidden', selected.length > 0);
        noResults.classList.toggle('hidden', visibleOptions > 0 || options.length === 0);
        count.textContent = `${selected.length}/${maxItems}`;
    };

    const createSelectedItem = (option) => {
        const item = document.createElement('div');
        item.className = 'flex items-center gap-3 rounded-lg border border-[#667132]/15 bg-white px-3 py-3';
        item.dataset.selectedRelatedPost = '';
        item.dataset.postId = option.dataset.postId;

        const position = document.createElement('span');
        position.className = 'flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#667132]/10 text-xs font-bold text-[#667132]';
        position.dataset.relatedPostPosition = '';

        const text = document.createElement('span');
        text.className = 'min-w-0 flex-1';

        const title = document.createElement('span');
        title.className = 'block truncate text-sm font-semibold text-slate-800';
        title.textContent = option.dataset.postTitle;
        text.appendChild(title);

        if (option.dataset.postCategory) {
            const category = document.createElement('span');
            category.className = 'block text-xs text-slate-500';
            category.textContent = option.dataset.postCategory;
            text.appendChild(category);
        }

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'related_post_ids[]';
        input.value = option.dataset.postId;

        const remove = document.createElement('button');
        remove.type = 'button';
        remove.className = 'shrink-0 rounded-lg px-2.5 py-1.5 text-xs font-bold text-red-600 transition hover:bg-red-50';
        remove.dataset.removeRelatedPost = '';
        remove.textContent = 'Xóa';

        item.append(position, text, input, remove);

        return item;
    };

    options.forEach((option) => {
        option.addEventListener('click', () => {
            if (selectedItems().length >= maxItems || selectedIds().has(option.dataset.postId)) {
                return;
            }

            selectedList.appendChild(createSelectedItem(option));
            status.textContent = `Đã chọn bài viết: ${option.dataset.postTitle}`;
            refresh();
            search.focus();
        });
    });

    selectedList.addEventListener('click', (event) => {
        const remove = event.target.closest('[data-remove-related-post]');
        if (!remove) {
            return;
        }

        const item = remove.closest('[data-selected-related-post]');
        const title = item.querySelector('.font-semibold')?.textContent || '';
        item.remove();
        status.textContent = `Đã bỏ bài viết: ${title}`;
        refresh();
        search.focus();
    });

    search.addEventListener('input', refresh);
    refresh();
});

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

const setDeepLStatus = (element, message, type = 'info') => {
    if (!element) {
        return;
    }

    element.hidden = false;
    element.classList.remove('hidden', 'border-red-200', 'bg-red-50', 'text-red-800', 'border-[#667132]/20', 'bg-[#667132]/8', 'text-[#667132]', 'border-slate-200', 'bg-slate-50', 'text-slate-700');

    const classes = {
        error: ['border-red-200', 'bg-red-50', 'text-red-800'],
        success: ['border-[#667132]/20', 'bg-[#667132]/8', 'text-[#667132]'],
        info: ['border-slate-200', 'bg-slate-50', 'text-slate-700'],
    };

    element.classList.add(...(classes[type] || classes.info));
    element.textContent = message;
};

const postJson = async (url, payload = {}) => {
    const response = await fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify(payload),
    });

    const data = await response.json().catch(() => ({}));

    if (!response.ok || data.ok === false) {
        throw new Error(data.message || 'Không xử lý được yêu cầu.');
    }

    return data;
};

const currentTranslationProviderLabel = () => {
    const select = document.querySelector('[data-translation-provider]');

    if (!select) {
        return 'provider dịch';
    }

    return select.value === 'microsoft' ? 'Microsoft Translator' : 'DeepL';
};

document.querySelectorAll('[data-deepl-translate]').forEach((button) => {
    button.addEventListener('click', async () => {
        const tabs = button.closest('[data-admin-tabs]');
        const status = tabs?.querySelector('[data-deepl-inline-status]');
        const url = button.dataset.deeplUrl;
        const fields = {};

        tabs?.querySelectorAll('[data-copy-field]').forEach((target) => {
            const fieldName = target.dataset.translationField || target.name.match(/\[([^\]]+)\]$/)?.[1];

            if (!fieldName || fieldName === 'slug') {
                return;
            }

            const source = tabs.querySelector(`[name="${target.dataset.copyField}"]`);
            const value = source?.value || source?.textContent || '';

            if (value.trim()) {
                fields[fieldName] = value;
            }
        });

        if (!url || Object.keys(fields).length === 0) {
            setDeepLStatus(status, 'Không có nội dung tiếng Việt để dịch.', 'error');
            return;
        }

        button.disabled = true;
        setDeepLStatus(status, `Đang gửi nội dung sang ${currentTranslationProviderLabel()}...`, 'info');

        try {
            const data = await postJson(url, { fields });

            Object.entries(data.translations || {}).forEach(([fieldName, value]) => {
                const target = tabs.querySelector(`[data-translation-field="${fieldName}"][data-copy-field], [name$="[${fieldName}]"][data-copy-field]`);

                if (target) {
                    target.value = value || '';
                    target.dispatchEvent(new Event('input', { bubbles: true }));
                }
            });

            if (data.source_hash) {
                const form = tabs?.closest('form');
                const hashInput = form?.querySelector('[data-translation-source-hash]');
                const providerInput = form?.querySelector('[data-translation-provider-value]');
                const state = tabs?.querySelector('[data-translation-state]');
                if (hashInput) hashInput.value = data.source_hash;
                if (providerInput) providerInput.value = data.provider || '';
                if (state) {
                    state.textContent = 'Bản dịch mới, chưa lưu';
                    state.className = 'translation-state translation-state-pending';
                    state.dataset.translationStateValue = 'pending';
                }
            }

            const usageText = data.usage?.character_limit
                ? ` Đã dùng ${Number(data.usage.character_count).toLocaleString('vi-VN')} / ${Number(data.usage.character_limit).toLocaleString('vi-VN')} ký tự.`
                : '';

            const saveReminder = data.source_hash ? ' Bản dịch mới chỉ nằm trong form; bấm Lưu/Cập nhật để ghi nhận.' : '';
            setDeepLStatus(status, `Đã dịch xong và điền vào form English.${saveReminder}${usageText}`, 'success');
        } catch (error) {
            setDeepLStatus(status, error.message, 'error');
        } finally {
            button.disabled = false;
        }
    });
});

document.querySelectorAll('[data-deepl-test-url], [data-deepl-usage-url]').forEach((button) => {
    button.addEventListener('click', async () => {
        const status = document.querySelector('[data-deepl-status]');
        const isUsage = Boolean(button.dataset.deeplUsageUrl);
        const url = button.dataset.deeplUsageUrl || button.dataset.deeplTestUrl;

        const providerLabel = currentTranslationProviderLabel();

        button.disabled = true;
        setDeepLStatus(status, isUsage ? `Đang kiểm tra quota ${providerLabel}...` : `Đang kiểm tra kết nối ${providerLabel}...`, 'info');

        try {
            const data = isUsage
                ? await fetch(url, { headers: { Accept: 'application/json' } }).then(async (response) => {
                    const json = await response.json().catch(() => ({}));
                    if (!response.ok || json.ok === false) throw new Error(json.message || 'Không đọc được quota.');
                    return json;
                })
                : await postJson(url);

            const usage = data.usage;
            const usageText = usage?.character_limit
                ? ` Đã dùng ${Number(usage.character_count).toLocaleString('vi-VN')} / ${Number(usage.character_limit).toLocaleString('vi-VN')} ký tự.`
                : '';
            const sampleText = data.sample ? ` Mẫu: ${data.sample}` : '';

            setDeepLStatus(status, `${data.message || `Kiểm tra ${providerLabel} thành công.`}${usageText}${sampleText}`, 'success');
        } catch (error) {
            setDeepLStatus(status, error.message, 'error');
        } finally {
            button.disabled = false;
        }
    });
});

const translationProviderSelect = document.querySelector('[data-translation-provider]');

if (translationProviderSelect) {
    const updateProviderPanels = () => {
        document.querySelectorAll('[data-provider-panel]').forEach((panel) => {
            panel.classList.toggle('hidden', panel.dataset.providerPanel !== translationProviderSelect.value);
        });
    };

    translationProviderSelect.addEventListener('change', updateProviderPanels);
    updateProviderPanels();
}

document.querySelectorAll('[data-seo-panel]').forEach((panel) => {
    const form = panel.closest('form');
    if (!form) return;

    const field = (name) => form.querySelector(`[name="${name}"]`);
    const title = field('title');
    const focusKeyword = field('focus_keyword');
    const error = panel.querySelector('[data-seo-error]');
    let focusWasEdited = Boolean(focusKeyword?.value);
    let debounceTimer = null;
    let controller = null;

    focusKeyword?.addEventListener('input', (event) => {
        if (event.isTrusted) focusWasEdited = true;
    });

    const suggestFocusKeyword = () => {
        if (!focusKeyword || focusWasEdited) return;
        const suggestion = title.value
            .split(/[|–—:]/u)[0]
            .trim()
            .split(/\s+/u)
            .filter(Boolean)
            .slice(0, 7)
            .join(' ');
        focusKeyword.value = suggestion;
        focusKeyword.dispatchEvent(new Event('input', { bubbles: true }));
    };

    title?.addEventListener('input', suggestFocusKeyword);
    suggestFocusKeyword();

    const renderItems = (container, items, emptyMessage, warning = false) => {
        container.replaceChildren();
        if (!items?.length) {
            const empty = document.createElement('p');
            empty.className = 'text-sm text-slate-500';
            empty.textContent = emptyMessage;
            container.appendChild(empty);
            return;
        }
        items.forEach((item) => {
            const row = document.createElement('div');
            row.className = `seo-check ${warning ? 'is-warning' : (item.state === 'pass' ? 'is-pass' : 'is-improve')}`;
            const mark = document.createElement('span');
            mark.textContent = warning ? '!' : (item.state === 'pass' ? '✓' : '•');
            const text = document.createElement('span');
            const label = document.createElement('strong');
            label.textContent = item.label || item.message;
            text.appendChild(label);
            if (item.label && item.message && item.message !== 'Đạt') {
                const message = document.createElement('small');
                message.textContent = item.message;
                text.appendChild(message);
            }
            row.append(mark, text);
            container.appendChild(row);
        });
    };

    const analyze = async () => {
        controller?.abort();
        controller = new AbortController();
        const relatedIds = Array.from(form.querySelectorAll('[name="related_post_ids[]"]')).map((input) => input.value);
        const thumbnail = field('thumbnail');
        const payload = {
            post_id: panel.dataset.postId || null,
            title: title?.value || '',
            slug: field('slug')?.value || '',
            excerpt: field('excerpt')?.value || '',
            content: field('content')?.value || '',
            meta_title: field('meta_title')?.value || '',
            meta_description: field('meta_description')?.value || '',
            focus_keyword: focusKeyword?.value || '',
            thumbnail_present: panel.dataset.thumbnailPresent === '1' || Boolean(thumbnail?.files?.length),
            related_post_ids: relatedIds,
        };

        panel.querySelector('[data-seo-preview-title]').textContent = payload.meta_title || payload.title || 'Tiêu đề bài viết';
        panel.querySelector('[data-seo-preview-description]').textContent = payload.meta_description || payload.excerpt || 'Mô tả bài viết sẽ hiển thị tại đây.';
        panel.querySelector('[data-seo-preview-url]').textContent = `${window.location.origin}/blog/${payload.slug || 'duong-dan-bai-viet'}`;

        try {
            const response = await fetch(panel.dataset.seoUrl, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify(payload),
                signal: controller.signal,
            });
            const data = await response.json().catch(() => ({}));
            if (!response.ok || data.ok === false) throw new Error(data.message || 'Không phân tích được SEO.');

            const score = panel.querySelector('[data-seo-score]');
            score.textContent = data.score;
            score.className = `seo-score is-${data.status}`;
            panel.querySelector('[data-seo-word-count]').textContent = data.metrics.word_count;
            panel.querySelector('[data-seo-read-time]').textContent = data.metrics.reading_minutes;
            panel.querySelector('[data-seo-title-length]').textContent = data.metrics.title_length;
            panel.querySelector('[data-seo-description-length]').textContent = data.metrics.description_length;
            renderItems(panel.querySelector('[data-seo-checks]'), data.checks, 'Chưa có tiêu chí để phân tích.');
            renderItems(panel.querySelector('[data-seo-warnings]'), data.warnings, 'Không có cảnh báo thêm.', true);
            error.classList.add('hidden');
        } catch (requestError) {
            if (requestError.name === 'AbortError') return;
            error.textContent = requestError.message;
            error.classList.remove('hidden');
        }
    };

    const scheduleAnalysis = () => {
        window.clearTimeout(debounceTimer);
        debounceTimer = window.setTimeout(analyze, 700);
    };

    form.addEventListener('input', scheduleAnalysis);
    form.addEventListener('change', scheduleAnalysis);
    form.addEventListener('click', (event) => {
        if (event.target.closest('[data-related-post-option], [data-remove-related-post]')) {
            window.setTimeout(scheduleAnalysis, 0);
        }
    });

    const translationHash = form.querySelector('[data-translation-source-hash]');
    ['title', 'excerpt', 'content', 'meta_title', 'meta_description', 'focus_keyword'].forEach((name) => {
        field(name)?.addEventListener('input', () => {
            const state = form.querySelector('[data-translation-state]');
            if (!translationHash?.value && state?.dataset.translationStateValue !== 'synced' && state?.dataset.translationStateValue !== 'pending') return;
            if (translationHash) translationHash.value = '';
            if (state) {
                state.textContent = 'Bản Anh cần cập nhật';
                state.className = 'translation-state translation-state-stale';
                state.dataset.translationStateValue = 'stale';
            }
        });
    });

    scheduleAnalysis();
});

const articleBody = document.querySelector('[data-article-body]');
const readingProgress = document.querySelector('[data-reading-progress] span');

if (articleBody && readingProgress) {
    const updateReadingProgress = () => {
        const top = articleBody.getBoundingClientRect().top + window.scrollY;
        const available = Math.max(1, articleBody.offsetHeight - window.innerHeight * 0.55);
        const progress = Math.min(1, Math.max(0, (window.scrollY - top + window.innerHeight * 0.25) / available));
        readingProgress.style.transform = `scaleX(${progress})`;
    };
    updateReadingProgress();
    window.addEventListener('scroll', updateReadingProgress, { passive: true });
    window.addEventListener('resize', updateReadingProgress);
}

const tocLinks = Array.from(document.querySelectorAll('[data-toc-link]'));

document.querySelectorAll('[data-toc-toggle]').forEach((toggleButton) => {
    const popup = document.querySelector('[data-toc-popup]');
    const overlay = document.querySelector('[data-toc-overlay]');
    const closeButton = popup?.querySelector('[data-toc-close]');

    if (!popup || !overlay || !closeButton) return;

    const openToc = () => {
        popup.hidden = false;
        overlay.hidden = false;
        toggleButton.setAttribute('aria-expanded', 'true');
        closeButton.focus();
    };

    const closeToc = (restoreFocus = true) => {
        popup.hidden = true;
        overlay.hidden = true;
        toggleButton.setAttribute('aria-expanded', 'false');
        if (restoreFocus) toggleButton.focus({ preventScroll: true });
    };

    toggleButton.addEventListener('click', () => {
        if (popup.hidden) openToc();
        else closeToc();
    });
    closeButton.addEventListener('click', () => closeToc());
    overlay.addEventListener('click', () => closeToc());
    popup.querySelectorAll('[data-toc-link]').forEach((link) => link.addEventListener('click', () => closeToc(false)));
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !popup.hidden) closeToc();
    });
});

if (tocLinks.length && 'IntersectionObserver' in window) {
    const headings = tocLinks
        .map((link) => document.getElementById(decodeURIComponent(link.hash.slice(1))))
        .filter(Boolean);
    const activateHeading = (id) => {
        let activeLink = null;
        tocLinks.forEach((link) => {
            const active = decodeURIComponent(link.hash.slice(1)) === id;
            link.classList.toggle('is-active', active);
            if (active) {
                link.setAttribute('aria-current', 'location');
                activeLink = link;
            }
            else link.removeAttribute('aria-current');
        });
        if (activeLink && !activeLink.closest('[data-toc-popup]')?.hidden) {
            activeLink.scrollIntoView({ block: 'nearest' });
        }
    };
    const visible = new Map();
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => visible.set(entry.target.id, entry));
        const active = headings.find((heading) => visible.get(heading.id)?.isIntersecting)
            || [...headings].reverse().find((heading) => heading.getBoundingClientRect().top < 160)
            || headings[0];
        if (active) activateHeading(active.id);
    }, { rootMargin: '-18% 0px -68% 0px' });
    headings.forEach((heading) => observer.observe(heading));
}

document.querySelectorAll('[data-share-article]').forEach((button) => {
    button.addEventListener('click', async () => {
        const status = button.parentElement.querySelector('[data-share-status]');
        try {
            if (navigator.share) {
                await navigator.share({ title: button.dataset.shareTitle, url: button.dataset.shareUrl });
                if (status) status.textContent = document.documentElement.lang === 'en' ? 'Shared' : 'Đã chia sẻ';
            } else {
                if (navigator.clipboard?.writeText) {
                    await navigator.clipboard.writeText(button.dataset.shareUrl);
                } else {
                    const copyField = document.createElement('textarea');
                    copyField.value = button.dataset.shareUrl;
                    copyField.style.position = 'fixed';
                    copyField.style.opacity = '0';
                    document.body.appendChild(copyField);
                    copyField.select();
                    document.execCommand('copy');
                    copyField.remove();
                }
                if (status) status.textContent = document.documentElement.lang === 'en' ? 'Link copied' : 'Đã sao chép link';
            }
            status?.classList.remove('hidden');
            window.setTimeout(() => status?.classList.add('hidden'), 3000);
        } catch (error) {
            if (error.name !== 'AbortError' && status) {
                status.textContent = 'Không thể chia sẻ lúc này';
                status.classList.remove('hidden');
            }
        }
    });
});

const trackConversion = (eventName, params = {}) => {
    const payload = {
        page_path: window.location.pathname,
        page_title: document.title,
        ...params,
    };

    if (typeof window.gtag === 'function') {
        window.gtag('event', eventName, payload);
    }

    if (typeof window.fbq === 'function') {
        const facebookEvent = payload.facebook_event || eventName;
        window.fbq('trackCustom', facebookEvent, payload);
    }

    window.dispatchEvent(new CustomEvent('danhuong:conversion', {
        detail: { eventName, payload },
    }));
};

document.querySelectorAll('[data-track-event]').forEach((element) => {
    element.addEventListener('click', () => {
        trackConversion(element.dataset.trackEvent, {
            event_category: element.dataset.trackCategory || 'engagement',
            event_label: element.dataset.trackLabel || element.textContent.trim(),
            link_url: element.href || null,
            facebook_event: element.dataset.facebookEvent || undefined,
        });
    });
});

document.querySelectorAll('[data-track-view]').forEach((element) => {
    trackConversion(element.dataset.trackView, {
        event_category: element.dataset.trackCategory || 'view',
        event_label: element.dataset.trackLabel || document.title,
        item_name: element.dataset.itemName || undefined,
        value: element.dataset.value ? Number(element.dataset.value) : undefined,
        currency: element.dataset.currency || undefined,
        facebook_event: element.dataset.facebookEvent || undefined,
    });
});

document.querySelectorAll('form[data-submit-loading]').forEach((form) => {
    form.addEventListener('submit', () => {
        if (!form.checkValidity()) {
            return;
        }

        if (form.dataset.trackSubmit) {
            trackConversion(form.dataset.trackSubmit, {
                event_category: form.dataset.trackCategory || 'form',
                event_label: form.dataset.trackLabel || form.getAttribute('action') || 'submit',
                facebook_event: form.dataset.facebookEvent || undefined,
            });
        }

        const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');

        if (!submitButton) {
            return;
        }

        submitButton.disabled = true;
        submitButton.classList.add('is-loading');

        if (submitButton.tagName === 'BUTTON') {
            submitButton.dataset.originalText = submitButton.textContent.trim();
            submitButton.textContent = submitButton.dataset.loadingText || i18nMessage('submitLoading', {}, 'Sending...');
        }
    });
});

const revealTargets = document.querySelectorAll('.dish-card, .promo-card, .reason-card, .commitment-card, .home-gallery-card, .about-gallery-card, article.group');

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
        let message = i18nMessage('reservationWindow', { opens: opensAt, closes: closesAt }, `The restaurant accepts reservations from ${opensAt} to ${closesAt}.`);

        if (reservationDateInput.value === today) {
            minTime = [opensAt, currentTime()].sort().at(-1);
            message = i18nMessage('reservationTodayWindow', { min: minTime, closes: closesAt }, `For today, please choose a time from ${minTime} to ${closesAt}.`);
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
        error.textContent = i18nMessage('uploadTooLarge', { maxMb }, `The image is larger than ${maxMb}MB, so it cannot be uploaded. Please choose a smaller image; the system will compress it after saving.`);
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
    const currentBranchId = chatWidget.dataset.branchId || '';
    let chatSessionId = window.sessionStorage.getItem('danhuong_chat_session_id');
    let chatBranchId = window.sessionStorage.getItem('danhuong_chat_branch_id') || '';
    let pollTimer = null;

    if (chatSessionId && currentBranchId && chatBranchId && currentBranchId !== chatBranchId) {
        window.sessionStorage.removeItem('danhuong_chat_session_id');
        window.sessionStorage.removeItem('danhuong_chat_branch_id');
        chatSessionId = null;
        chatBranchId = '';
    }

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
            throw new Error(i18nMessage('chatSendFailed', {}, 'We could not send the message. Please try again.'));
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
            window.sessionStorage.removeItem('danhuong_chat_branch_id');
            chatSessionId = null;
            chatBranchId = '';
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

    const actionsMenu = chatWidget.querySelector('[data-chat-actions]');

    toggleButton.addEventListener('click', () => {
        const expanded = toggleButton.getAttribute('aria-expanded') === 'true';
        if (!expanded) {
            toggleButton.setAttribute('aria-expanded', 'true');
            actionsMenu.classList.remove('hidden');
            panel.classList.add('hidden');
        } else {
            toggleButton.setAttribute('aria-expanded', 'false');
            actionsMenu.classList.add('hidden');
            panel.classList.remove('hidden');
            trackConversion('open_chat', {
                event_category: 'chat',
                event_label: 'Open chat widget',
                facebook_event: 'OpenChat',
            });
            if (chatSessionId) {
                loadMessages();
                startPolling();
            }
        }
    });

    closeButton.addEventListener('click', () => {
        toggleButton.setAttribute('aria-expanded', 'false');
        actionsMenu.classList.add('hidden');
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
            chatBranchId = String(data.branch_id || formData.get('branch_id') || '');
            window.sessionStorage.setItem('danhuong_chat_session_id', chatSessionId);
            window.sessionStorage.setItem('danhuong_chat_branch_id', chatBranchId);
            trackConversion('start_chat', {
                event_category: 'chat',
                event_label: 'Visitor started chat',
                facebook_event: 'StartChat',
            });
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
    let previousReservationCount = null;

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

    const showAdminToast = (message) => {
        const toast = document.createElement('div');
        toast.className = 'admin-live-toast';
        toast.textContent = message;
        document.body.appendChild(toast);

        window.setTimeout(() => {
            toast.classList.add('is-hiding');
            window.setTimeout(() => toast.remove(), 220);
        }, 4200);
    };

    const renderNotifications = (data) => {
        const reservationCount = data.counts.reservations || 0;

        if (previousReservationCount !== null && reservationCount > previousReservationCount) {
            showAdminToast(`Có ${reservationCount - previousReservationCount} đơn đặt bàn mới cần xem`);
        }

        previousReservationCount = reservationCount;

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
            <a href="${item.url}" class="admin-notification-item ${item.urgent ? 'is-urgent' : ''}">
                <div class="flex items-center justify-between gap-3">
                    <p class="font-bold text-slate-900">${escapeNotificationHtml(item.title)}</p>
                    <span class="text-xs font-semibold text-slate-400">${escapeNotificationHtml(item.time)}</span>
                </div>
                <p class="mt-1 line-clamp-2 text-sm text-slate-500">${escapeNotificationHtml(item.body)}</p>
                <div class="mt-2 flex flex-wrap gap-2">
                    <span class="inline-flex rounded-full bg-[#667132]/10 px-2 py-1 text-xs font-bold uppercase text-[#667132]">${escapeNotificationHtml(item.type)}</span>
                    ${item.branch ? `<span class="inline-flex rounded-full bg-amber-50 px-2 py-1 text-xs font-bold text-amber-800">${escapeNotificationHtml(item.branch)}</span>` : ''}
                </div>
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
                <div class="${isAdmin ? 'ml-auto bg-[#667132] text-white' : 'bg-slate-100 text-slate-800'} max-w-[82%] rounded-2xl px-4 py-3 text-sm leading-6">
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

// ── Page transition loader ──
(function () {
    const loader = document.getElementById('page-loader');
    if (!loader) return;

    const showLoader = () => {
        loader.classList.add('is-active');
    };

    document.addEventListener('click', (e) => {
        const a = e.target.closest('a');
        if (!a) return;

        const href = a.getAttribute('href');
        if (!href) return;

        if (
            a.target === '_blank' ||
            href.startsWith('tel:') ||
            href.startsWith('mailto:') ||
            (href.startsWith('http') && !href.startsWith(window.location.origin)) ||
            href.startsWith('#') ||
            a.hasAttribute('download') ||
            a.dataset.noLoader
        ) return;

        const form = a.closest('form');
        if (form && form.dataset.submitLoading) return;

        showLoader();
    });

    window.addEventListener('pageshow', () => {
        loader.classList.remove('is-active');
    });
})();
