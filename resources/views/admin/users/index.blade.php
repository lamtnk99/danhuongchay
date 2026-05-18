@extends('admin.layouts.app')

@section('title', 'Tài khoản admin')

@section('content')
    <div class="admin-page-head">
        <form class="admin-filter" method="GET">
            <input name="q" value="{{ request('q') }}" placeholder="Tìm tên hoặc email..." class="admin-input">
            <button class="admin-btn-secondary">Tìm</button>
        </form>
        <a href="{{ route('admin.users.create') }}" class="admin-btn-primary">Thêm tài khoản</a>
    </div>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Avatar</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th class="text-right">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            @if ($user->avatar)
                                <img src="{{ media_url($user->avatar) }}" alt="{{ $user->name }}" class="h-12 w-12 rounded-full object-cover">
                            @else
                                <div class="grid h-12 w-12 place-items-center rounded-full bg-emerald-100 font-bold text-emerald-800">{{ mb_substr($user->name, 0, 1) }}</div>
                            @endif
                        </td>
                        <td class="font-semibold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="status-badge {{ $user->role === 'admin' ? 'status-active' : 'status-inactive' }}">{{ $user->role }}</span></td>
                        <td>
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="admin-btn-mini">Sửa</a>
                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" data-confirm="Xóa tài khoản này?">
                                    @csrf
                                    @method('DELETE')
                                    <button class="admin-btn-danger" @disabled(auth()->id() === $user->id)>Xóa</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-slate-500">Chưa có tài khoản.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-5">{{ $users->links() }}</div>
@endsection
