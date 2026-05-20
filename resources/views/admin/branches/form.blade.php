<div class="grid gap-5 lg:grid-cols-2">
    <div>
        <label class="admin-label" for="name">Tên cơ sở</label>
        <input id="name" name="name" value="{{ old('name', $branch->name) }}" class="admin-input" required>
        @error('name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="slug">Slug</label>
        <input id="slug" name="slug" value="{{ old('slug', $branch->slug) }}" class="admin-input" placeholder="Tự sinh nếu bỏ trống">
        @error('slug') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="city">Tỉnh/thành</label>
        <input id="city" name="city" value="{{ old('city', $branch->city) }}" class="admin-input">
        @error('city') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="sort_order">Thứ tự</label>
        <input id="sort_order" type="number" min="0" name="sort_order" value="{{ old('sort_order', $branch->sort_order ?? 0) }}" class="admin-input">
        @error('sort_order') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="lg:col-span-2">
        <label class="admin-label" for="address">Địa chỉ</label>
        <textarea id="address" name="address" rows="3" class="admin-input">{{ old('address', $branch->address) }}</textarea>
        @error('address') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="hotline">Hotline ưu tiên</label>
        <input id="hotline" name="hotline" value="{{ old('hotline', $branch->hotline) }}" class="admin-input">
        @error('hotline') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="phone">Số điện thoại</label>
        <input id="phone" name="phone" value="{{ old('phone', $branch->phone) }}" class="admin-input">
        @error('phone') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $branch->email) }}" class="admin-input">
        @error('email') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="opening_hours">Giờ mở cửa hiển thị</label>
        <input id="opening_hours" name="opening_hours" value="{{ old('opening_hours', $branch->opening_hours) }}" class="admin-input" placeholder="09:00 - 14:00 | 16:00 - 21:00 hằng ngày">
        @error('opening_hours') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="reservation_time_slots">Khung giờ nhận đặt bàn</label>
        <input id="reservation_time_slots" name="reservation_time_slots" value="{{ old('reservation_time_slots', $branch->reservation_time_slots) }}" class="admin-input" placeholder="09:00-14:00,16:00-21:00">
        <p class="mt-1 text-xs text-slate-500">Có thể nhập nhiều khung, cách nhau bằng dấu phẩy.</p>
        @error('reservation_time_slots') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="reservation_last_booking_time">Giờ cuối nhận đặt</label>
        <input id="reservation_last_booking_time" type="time" name="reservation_last_booking_time" value="{{ old('reservation_last_booking_time', $branch->reservation_last_booking_time) }}" class="admin-input">
        @error('reservation_last_booking_time') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="reservation_last_order_buffer_minutes">Ngừng nhận trước giờ đóng bếp</label>
        <input id="reservation_last_order_buffer_minutes" type="number" min="0" max="240" name="reservation_last_order_buffer_minutes" value="{{ old('reservation_last_order_buffer_minutes', $branch->reservation_last_order_buffer_minutes) }}" class="admin-input">
        @error('reservation_last_order_buffer_minutes') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="lg:col-span-2">
        <label class="admin-label" for="description">Mô tả ngắn</label>
        <textarea id="description" name="description" rows="4" class="admin-input">{{ old('description', $branch->description) }}</textarea>
        @error('description') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="lg:col-span-2">
        <label class="admin-label" for="google_map_iframe">Google Map iframe</label>
        <textarea id="google_map_iframe" name="google_map_iframe" rows="5" class="admin-input">{{ old('google_map_iframe', $branch->google_map_iframe) }}</textarea>
        @error('google_map_iframe') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="facebook_url">Facebook URL</label>
        <input id="facebook_url" name="facebook_url" value="{{ old('facebook_url', $branch->facebook_url) }}" class="admin-input">
        @error('facebook_url') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="zalo_url">Zalo URL</label>
        <input id="zalo_url" name="zalo_url" value="{{ old('zalo_url', $branch->zalo_url) }}" class="admin-input">
        @error('zalo_url') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div class="lg:col-span-2">
        <label class="admin-label" for="image">Ảnh đại diện cơ sở</label>
        @if ($branch->image)
            <img src="{{ media_variant_url($branch->image, 'card') }}" alt="{{ $branch->name }}" class="mb-3 h-52 w-full rounded-xl object-cover">
        @endif
        <input id="image" type="file" name="image" accept=".jpg,.jpeg,.png,.webp,.svg,image/jpeg,image/png,image/webp,image/svg+xml" class="admin-input">
        @error('image') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="meta_title">Meta title</label>
        <input id="meta_title" name="meta_title" value="{{ old('meta_title', $branch->meta_title) }}" class="admin-input">
        @error('meta_title') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="meta_description">Meta description</label>
        <input id="meta_description" name="meta_description" value="{{ old('meta_description', $branch->meta_description) }}" class="admin-input">
        @error('meta_description') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <label class="flex items-center gap-2 font-semibold text-slate-700">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $branch->is_active ?? true))>
        Hiển thị cơ sở
    </label>
</div>

<div class="mt-6 flex flex-wrap gap-3">
    <button type="submit" class="admin-btn-primary">{{ $branch->exists ? 'Cập nhật' : 'Lưu' }}</button>
    <a href="{{ route('admin.branches.index') }}" class="admin-btn-secondary">Quay lại</a>
</div>
