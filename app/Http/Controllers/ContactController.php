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
            is_english() ? 'Contact Dan Huong Chay | Vegetarian restaurant in Hai Phong' : 'Liên hệ quán chay Hải Phòng | Đàn Hương Chay',
            is_english() ? 'Find Dan Huong Chay address, opening hours, hotline and contact form for vegetarian dining in Hai Phong.' : 'Thông tin liên hệ, địa chỉ, giờ mở cửa và form gửi tin nhắn cho Đàn Hương Chay tại Hải Phòng.',
            is_english() ? 'contact vegetarian restaurant Hai Phong, Dan Huong Chay address, vegetarian restaurant phone' : 'liên hệ quán chay Hải Phòng, địa chỉ quán chay, số điện thoại quán chay, nhà hàng chay Hải Phòng',
            localized_route('contact')
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
            ->to(localized_route('contact'))
            ->with('success', is_english() ? 'Thank you for contacting us. Dan Huong Chay will respond as soon as possible.' : 'Cảm ơn bạn đã liên hệ. Đàn Hương Chay sẽ phản hồi trong thời gian sớm nhất.');
    }
}
