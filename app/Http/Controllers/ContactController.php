<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        $seo = SeoService::page(
            'Liên hệ Đàn Hương Chay | Đặt câu hỏi và tìm đường',
            'Thông tin liên hệ, địa chỉ, giờ mở cửa và form gửi tin nhắn cho Đàn Hương Chay.',
            'liên hệ Đàn Hương Chay, địa chỉ quán chay, số điện thoại quán chay',
            route('contact')
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        return view('contact', compact('seo', 'schemas'));
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        Contact::create($request->validated());

        return redirect()
            ->route('contact')
            ->with('success', 'Cảm ơn bạn đã liên hệ. Đàn Hương Chay sẽ phản hồi trong thời gian sớm nhất.');
    }
}
