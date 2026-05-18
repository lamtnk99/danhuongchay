<div class="grid gap-5 lg:grid-cols-2">
    <div>
        <label class="admin-label" for="title">Tiêu đề</label>
        <input id="title" name="title" value="{{ old('title', $banner->title) }}" class="admin-input" required>
        @error('title') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="subtitle">Phụ đề</label>
        <input id="subtitle" name="subtitle" value="{{ old('subtitle', $banner->subtitle) }}" class="admin-input">
        @error('subtitle') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="lg:col-span-2">
        <label class="admin-label" for="description">Mô tả</label>
        <textarea id="description" name="description" rows="4" class="admin-input">{{ old('description', $banner->description) }}</textarea>
        @error('description') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="button_text">Text nút</label>
        <input id="button_text" name="button_text" value="{{ old('button_text', $banner->button_text) }}" class="admin-input">
        @error('button_text') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="button_link">Link nút</label>
        <input id="button_link" name="button_link" value="{{ old('button_link', $banner->button_link) }}" class="admin-input">
        @error('button_link') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="position">Vị trí</label>
        <input id="position" name="position" value="{{ old('position', $banner->position ?: 'home') }}" class="admin-input" required>
        @error('position') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="sort_order">Thứ tự</label>
        <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $banner->sort_order ?? 0) }}" class="admin-input">
        @error('sort_order') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="lg:col-span-2">
        <label class="admin-label" for="image">Ảnh banner</label>
        @if ($banner->image)
            <img src="{{ media_url($banner->image) }}" alt="{{ $banner->title }}" class="mb-3 h-48 w-full rounded-2xl object-cover">
        @endif
        <input id="image" type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.svg,image/jpeg,image/png,image/webp,image/svg+xml" class="admin-input" @required(! $banner->exists)>
        <p class="mt-2 text-sm text-slate-500">Chấp nhận JPG, PNG, WEBP, SVG. Dung lượng tối đa 10MB.</p>
        @error('image') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <label class="flex items-center gap-2 font-semibold text-slate-700">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $banner->is_active ?? true))>
        Hiển thị banner
    </label>
</div>

<div class="mt-6 flex flex-wrap gap-3">
    <button type="submit" class="admin-btn-primary">{{ $banner->exists ? 'Cập nhật' : 'Lưu' }}</button>
    <a href="{{ route('admin.banners.index') }}" class="admin-btn-secondary">Quay lại</a>
</div>
