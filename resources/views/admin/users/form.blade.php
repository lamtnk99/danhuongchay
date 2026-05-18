<div class="grid gap-5 lg:grid-cols-2">
    <div>
        <label class="admin-label" for="name">Họ tên</label>
        <input id="name" name="name" value="{{ old('name', $user->name) }}" class="admin-input" required>
        @error('name') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="admin-input" required>
        @error('email') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="password">Mật khẩu</label>
        <input id="password" type="password" name="password" class="admin-input" @required(! $user->exists)>
        @error('password') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="password_confirmation">Nhập lại mật khẩu</label>
        <input id="password_confirmation" type="password" name="password_confirmation" class="admin-input" @required(! $user->exists)>
    </div>
    <div>
        <label class="admin-label" for="role">Role</label>
        <select id="role" name="role" class="admin-input" required>
            <option value="admin" @selected(old('role', $user->role ?: 'admin') === 'admin')>admin</option>
            <option value="user" @selected(old('role', $user->role) === 'user')>user</option>
        </select>
        @error('role') <p class="form-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="admin-label" for="avatar">Avatar</label>
        @if ($user->avatar)
            <img src="{{ media_url($user->avatar) }}" alt="{{ $user->name }}" class="mb-3 h-20 w-20 rounded-full object-cover">
        @endif
        <input id="avatar" type="file" name="avatar" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp" class="admin-input">
        <p class="mt-2 text-sm text-slate-500">Chấp nhận JPG, PNG, WEBP. Dung lượng tối đa 10MB.</p>
        @error('avatar') <p class="form-error">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-6 flex flex-wrap gap-3">
    <button type="submit" class="admin-btn-primary">{{ $user->exists ? 'Cập nhật' : 'Lưu' }}</button>
    <a href="{{ route('admin.users.index') }}" class="admin-btn-secondary">Quay lại</a>
</div>
