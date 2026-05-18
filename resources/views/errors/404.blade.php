@extends('layouts.app')

@section('content')
    <section class="mx-auto grid min-h-[60vh] max-w-4xl place-items-center px-4 py-20 text-center sm:px-6 lg:px-8">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.32em] text-amber-700">404</p>
            <h1 class="mt-4 text-4xl font-semibold text-emerald-950 sm:text-5xl">Trang bạn tìm không tồn tại</h1>
            <p class="mt-5 text-lg leading-8 text-stone-700">
                Đường dẫn có thể đã thay đổi hoặc nội dung không còn được hiển thị.
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <a href="{{ route('home') }}" class="btn-primary">Về trang chủ</a>
                <a href="{{ route('menu.index') }}" class="btn-secondary">Xem thực đơn</a>
            </div>
        </div>
    </section>
@endsection
