@php
    $translationFields = [
        ['name' => 'title', 'label' => 'Post title'],
        ['name' => 'slug', 'label' => 'English slug'],
        ['name' => 'excerpt', 'label' => 'Excerpt', 'type' => 'textarea', 'rows' => 4, 'wide' => true],
        ['name' => 'content', 'label' => 'Content', 'type' => 'textarea', 'rows' => 12, 'wide' => true],
        ['name' => 'meta_title', 'label' => 'Meta title'],
        ['name' => 'meta_keywords', 'label' => 'Meta keywords'],
        ['name' => 'meta_description', 'label' => 'Meta description', 'type' => 'textarea', 'rows' => 3, 'wide' => true],
    ];
@endphp

<div class="admin-form-tabs" data-admin-tabs>
    <div class="admin-tab-nav" role="tablist" aria-label="Ngôn ngữ bài viết">
        <button type="button" class="admin-tab-button is-active" data-admin-tab="vi">Tiếng Việt</button>
        <button type="button" class="admin-tab-button" data-admin-tab="en">English</button>
    </div>

    <div class="admin-tab-panel is-active" data-admin-tab-panel="vi">
        <div class="grid gap-5 lg:grid-cols-2">
            <div>
                <label class="admin-label" for="title">Tiêu đề</label>
                <input id="title" name="title" value="{{ old('title', $post->title) }}" class="admin-input" required>
                @error('title') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="admin-label" for="slug">Slug</label>
                <input id="slug" name="slug" value="{{ old('slug', $post->slug) }}" class="admin-input" placeholder="Để trống để tự sinh">
                @error('slug') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="admin-label" for="category_id">Danh mục</label>
                <select id="category_id" name="category_id" class="admin-input" required>
                    <option value="">Chọn danh mục</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) old('category_id', $post->category_id) === (string) $category->id)>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="admin-label" for="published_at">Ngày đăng / hẹn ngày</label>
                <input id="published_at" type="datetime-local" name="published_at" value="{{ old('published_at', optional($post->published_at)->format('Y-m-d\\TH:i')) }}" class="admin-input">
                @error('published_at') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="lg:col-span-2">
                <label class="admin-label" for="excerpt">Mô tả ngắn</label>
                <textarea id="excerpt" name="excerpt" rows="4" class="admin-input" required>{{ old('excerpt', $post->excerpt) }}</textarea>
                @error('excerpt') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="lg:col-span-2">
                <label class="admin-label" for="content">Nội dung</label>
                <textarea id="content" name="content" rows="12" class="admin-input" required>{{ old('content', $post->content) }}</textarea>
                @error('content') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="lg:col-span-2">
                <label class="admin-label" for="thumbnail">Ảnh thumbnail</label>
                @if ($post->thumbnail)
                    <img src="{{ media_url($post->thumbnail) }}" alt="{{ $post->title }}" class="mb-3 h-44 w-full rounded-xl object-cover">
                @endif
                <input id="thumbnail" type="file" name="thumbnail" accept=".jpg,.jpeg,.png,.webp,.svg,image/jpeg,image/png,image/webp,image/svg+xml" class="admin-input">
                <p class="mt-2 text-sm text-slate-500">Chấp nhận JPG, PNG, WEBP, SVG. Ảnh lớn sẽ được resize/tối ưu khi upload.</p>
                @error('thumbnail') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="admin-label" for="meta_title">Meta title</label>
                <input id="meta_title" name="meta_title" value="{{ old('meta_title', $post->meta_title) }}" class="admin-input">
                @error('meta_title') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="admin-label" for="meta_keywords">Meta keywords</label>
                <input id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $post->meta_keywords) }}" class="admin-input">
                @error('meta_keywords') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="lg:col-span-2">
                <label class="admin-label" for="meta_description">Meta description</label>
                <textarea id="meta_description" name="meta_description" rows="3" class="admin-input">{{ old('meta_description', $post->meta_description) }}</textarea>
                @error('meta_description') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <div class="lg:col-span-2">
                @php
                    $linkablePostsById = $linkablePosts->keyBy(fn ($item) => (string) $item->id);
                    $selectedRelatedPostIds = collect(old(
                        'related_post_ids',
                        $post->exists ? $post->internalLinks->pluck('id')->all() : []
                    ))->map(fn ($id) => (string) $id)
                        ->filter(fn ($id) => $linkablePostsById->has($id))
                        ->take(6)
                        ->values();
                @endphp
                <div data-related-post-picker data-max-items="6">
                    <label class="admin-label" for="related_post_search">Liên kết nội bộ đến bài viết</label>
                    <div class="relative">
                        <input
                            id="related_post_search"
                            type="search"
                            class="admin-input pr-12"
                            placeholder="Tìm theo tiêu đề hoặc danh mục bài viết..."
                            autocomplete="off"
                            data-related-post-search
                        >
                        <span class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-slate-400" aria-hidden="true">⌕</span>
                    </div>

                    <div class="mt-3 max-h-64 overflow-y-auto rounded-xl border border-slate-200 bg-white" data-related-post-options>
                        @forelse ($linkablePosts as $linkablePost)
                            <button
                                type="button"
                                class="flex w-full items-center justify-between gap-4 border-b border-slate-100 px-4 py-3 text-left transition last:border-b-0 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-45"
                                data-related-post-option
                                data-post-id="{{ $linkablePost->id }}"
                                data-post-title="{{ $linkablePost->title }}"
                                data-post-category="{{ $linkablePost->category?->name }}"
                            >
                                <span class="min-w-0">
                                    <span class="block font-semibold text-slate-800">{{ $linkablePost->title }}</span>
                                    @if ($linkablePost->category)
                                        <span class="mt-1 block text-xs font-medium text-slate-500">{{ $linkablePost->category->name }}</span>
                                    @endif
                                </span>
                                <span class="shrink-0 rounded-lg bg-[#667132]/10 px-3 py-1.5 text-xs font-bold text-[#667132]">Chọn</span>
                            </button>
                        @empty
                            <p class="px-4 py-5 text-sm text-slate-500">Chưa có bài viết khác để liên kết.</p>
                        @endforelse
                        <p class="hidden px-4 py-5 text-sm text-slate-500" data-related-post-no-results>Không tìm thấy bài viết phù hợp.</p>
                    </div>

                    <div class="mt-5 rounded-xl border border-[#667132]/15 bg-[#667132]/5 p-4">
                        <div class="flex items-center justify-between gap-4">
                            <p class="font-bold text-slate-800">Bài viết đã chọn</p>
                            <span class="rounded-full bg-white px-2.5 py-1 text-xs font-bold text-[#667132]" data-related-post-count>{{ $selectedRelatedPostIds->count() }}/6</span>
                        </div>
                        <div class="mt-3 space-y-2" data-related-post-selected>
                            @foreach ($selectedRelatedPostIds as $selectedId)
                                @if ($selectedPost = $linkablePostsById->get($selectedId))
                                    <div class="flex items-center gap-3 rounded-lg border border-[#667132]/15 bg-white px-3 py-3" data-selected-related-post data-post-id="{{ $selectedPost->id }}">
                                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-[#667132]/10 text-xs font-bold text-[#667132]" data-related-post-position>{{ $loop->iteration }}</span>
                                        <span class="min-w-0 flex-1">
                                            <span class="block truncate text-sm font-semibold text-slate-800">{{ $selectedPost->title }}</span>
                                            @if ($selectedPost->category)
                                                <span class="block text-xs text-slate-500">{{ $selectedPost->category->name }}</span>
                                            @endif
                                        </span>
                                        <input type="hidden" name="related_post_ids[]" value="{{ $selectedPost->id }}">
                                        <button type="button" class="shrink-0 rounded-lg px-2.5 py-1.5 text-xs font-bold text-red-600 transition hover:bg-red-50" data-remove-related-post>Xóa</button>
                                    </div>
                                @endif
                            @endforeach
                            <p @class(['text-sm text-slate-500', 'hidden' => $selectedRelatedPostIds->isNotEmpty()]) data-related-post-empty>Chưa chọn bài viết nào.</p>
                        </div>
                    </div>
                    <p class="sr-only" aria-live="polite" data-related-post-status></p>
                </div>
                <p class="mt-2 text-sm text-slate-500">Chọn tối đa 6 bài thật sự liên quan. Thứ tự chọn cũng là thứ tự ưu tiên hiển thị; hệ thống tự bổ sung bài cùng chủ đề nếu còn thiếu.</p>
                @error('related_post_ids') <p class="form-error">{{ $message }}</p> @enderror
                @error('related_post_ids.*') <p class="form-error">{{ $message }}</p> @enderror
            </div>
            <label class="flex items-center gap-2 font-semibold text-slate-700">
                <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $post->is_featured))>
                Bài nổi bật
            </label>
            <label class="flex items-center gap-2 font-semibold text-slate-700">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $post->is_active ?? true))>
                Hiển thị
            </label>
        </div>
    </div>

    <div class="admin-tab-panel" data-admin-tab-panel="en" hidden>
        @include('admin.partials.translation-fields', ['model' => $post, 'locale' => 'en', 'fields' => $translationFields])
    </div>
</div>

<div class="mt-6 flex flex-wrap gap-3">
    <button type="submit" class="admin-btn-primary">{{ $post->exists ? 'Cập nhật' : 'Lưu' }}</button>
    <a href="{{ route('admin.posts.index') }}" class="admin-btn-secondary">Quay lại</a>
    @if ($post->exists)
        <a href="{{ route('blog.show', $post) }}" target="_blank" class="admin-btn-secondary">Preview</a>
    @endif
</div>
