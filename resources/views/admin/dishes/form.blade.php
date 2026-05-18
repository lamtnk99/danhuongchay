<div class="grid gap-5 lg:grid-cols-2">
    <div>
        <label class="admin-label" for="name">Tên món</label>
        <input id="name" name="name" value="{{ old('name', $dish->name) }}" class="admin-input" required>
        @error('name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="slug">Slug</label>
        <input id="slug" name="slug" value="{{ old('slug', $dish->slug) }}" class="admin-input" placeholder="Để trống để tự sinh">
        @error('slug') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="category_id">Danh mục</label>
        <select id="category_id" name="category_id" class="admin-input" required>
            <option value="">Chọn danh mục</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id', $dish->category_id) === (string) $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="sort_order">Thứ tự</label>
        <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $dish->sort_order ?? 0) }}" class="admin-input">
        @error('sort_order') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="lg:col-span-2">
        <label class="admin-label" for="description">Mô tả ngắn</label>
        <textarea id="description" name="description" rows="4" class="admin-input" required>{{ old('description', $dish->description) }}</textarea>
        @error('description') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="lg:col-span-2">
        <label class="admin-label" for="content">Nội dung chi tiết</label>
        <textarea id="content" name="content" rows="8" class="admin-input">{{ old('content', $dish->content) }}</textarea>
        @error('content') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="lg:col-span-2">
        <label class="admin-label" for="ingredients">Thành phần</label>
        <textarea id="ingredients" name="ingredients" rows="4" class="admin-input">{{ old('ingredients', $dish->ingredients) }}</textarea>
        @error('ingredients') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="price">Giá</label>
        <input id="price" type="number" min="0" name="price" value="{{ old('price', $dish->price) }}" class="admin-input" required>
        @error('price') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="sale_price">Giá khuyến mãi</label>
        <input id="sale_price" type="number" min="0" name="sale_price" value="{{ old('sale_price', $dish->sale_price) }}" class="admin-input">
        @error('sale_price') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="lg:col-span-2">
        <label class="admin-label" for="image">Ảnh đại diện</label>
        @if ($dish->image)
            <img src="{{ media_url($dish->image) }}" alt="{{ $dish->name }}" class="mb-3 h-44 w-full rounded-xl object-cover">
        @endif
        <input id="image" type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.svg,image/jpeg,image/png,image/webp,image/svg+xml" class="admin-input">
        <p class="mt-2 text-sm text-slate-500">Chấp nhận JPG, PNG, WEBP, SVG. Dung lượng tối đa 10MB.</p>
        @error('image') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="lg:col-span-2">
        <label class="admin-label" for="gallery">Gallery nhiều ảnh</label>
        @if ($dish->gallery)
            <div class="mb-3 grid gap-3 sm:grid-cols-3">
                @foreach ($dish->gallery as $image)
                    <label class="rounded-xl border border-slate-200 p-2">
                        <img src="{{ media_url($image) }}" alt="Gallery {{ $dish->name }}" class="h-24 w-full rounded-lg object-cover">
                        <span class="mt-2 flex items-center gap-2 text-xs">
                            <input type="checkbox" name="remove_gallery[]" value="{{ $image }}">
                            Xóa ảnh này
                        </span>
                    </label>
                @endforeach
            </div>
        @endif
        <input id="gallery" type="file" name="gallery[]" multiple accept=".jpg,.jpeg,.png,.webp,.svg,image/jpeg,image/png,image/webp,image/svg+xml" class="admin-input">
        <p class="mt-2 text-sm text-slate-500">Có thể chọn nhiều ảnh. Mỗi ảnh tối đa 10MB.</p>
        @error('gallery.*') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="meta_title">Meta title</label>
        <input id="meta_title" name="meta_title" value="{{ old('meta_title', $dish->meta_title) }}" class="admin-input">
        @error('meta_title') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="meta_keywords">Meta keywords</label>
        <input id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $dish->meta_keywords) }}" class="admin-input">
        @error('meta_keywords') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="lg:col-span-2">
        <label class="admin-label" for="meta_description">Meta description</label>
        <textarea id="meta_description" name="meta_description" rows="3" class="admin-input">{{ old('meta_description', $dish->meta_description) }}</textarea>
        @error('meta_description') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <label class="flex items-center gap-2 font-semibold text-slate-700">
        <input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $dish->is_featured))>
        Món nổi bật
    </label>
    <label class="flex items-center gap-2 font-semibold text-slate-700">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $dish->is_active ?? true))>
        Hiển thị
    </label>
</div>

<div class="mt-6 flex flex-wrap gap-3">
    <button type="submit" class="admin-btn-primary">{{ $dish->exists ? 'Cập nhật' : 'Lưu' }}</button>
    <a href="{{ route('admin.dishes.index') }}" class="admin-btn-secondary">Quay lại</a>
    @if ($dish->exists)
        <a href="{{ route('menu.show', $dish) }}" target="_blank" class="admin-btn-secondary">Preview</a>
    @endif
</div>
