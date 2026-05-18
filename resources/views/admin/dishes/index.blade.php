@extends('admin.layouts.app')

@section('title', 'Món ăn / Sản phẩm')

@section('content')
    <div class="admin-page-head">
        <form class="admin-filter" method="GET">
            <input name="q" value="{{ request('q') }}" placeholder="Tìm món..." class="admin-input">
            <select name="category_id" class="admin-input">
                <option value="">Tất cả danh mục</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            <select name="status" class="admin-input">
                <option value="">Tất cả trạng thái</option>
                <option value="active" @selected(request('status') === 'active')>Active</option>
                <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
            </select>
            <button class="admin-btn-secondary">Lọc</button>
        </form>
        <a href="{{ route('admin.dishes.create') }}" class="admin-btn-primary">Thêm món</a>
    </div>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Ảnh</th>
                    <th>Tên món</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Nổi bật</th>
                    <th>Trạng thái</th>
                    <th class="text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dishes as $dish)
                    <tr>
                        <td>
                            @if ($dish->image)
                                <img src="{{ media_url($dish->image) }}" alt="{{ $dish->name }}" class="h-16 w-20 rounded-xl object-cover">
                            @endif
                        </td>
                        <td>
                            <p class="font-semibold">{{ $dish->name }}</p>
                            <p class="text-xs text-slate-500">{{ $dish->slug }}</p>
                        </td>
                        <td>{{ $dish->category?->name }}</td>
                        <td>
                            @if ($dish->sale_price)
                                <span class="font-semibold text-emerald-800">{{ number_format($dish->sale_price, 0, ',', '.') }}đ</span>
                                <span class="block text-xs text-slate-400 line-through">{{ number_format($dish->price, 0, ',', '.') }}đ</span>
                            @else
                                {{ number_format($dish->price, 0, ',', '.') }}đ
                            @endif
                        </td>
                        <td>{{ $dish->is_featured ? 'Có' : 'Không' }}</td>
                        <td><span class="status-badge {{ $dish->is_active ? 'status-active' : 'status-inactive' }}">{{ $dish->is_active ? 'active' : 'inactive' }}</span></td>
                        <td>
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('menu.show', $dish) }}" target="_blank" class="admin-btn-mini">Preview</a>
                                <a href="{{ route('admin.dishes.edit', $dish) }}" class="admin-btn-mini">Sửa</a>
                                <form method="POST" action="{{ route('admin.dishes.destroy', $dish) }}" data-confirm="Xóa món này?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="admin-btn-danger">Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-slate-500">Chưa có món ăn.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $dishes->links() }}</div>
@endsection
