<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Branch;
use App\Models\Contact;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(Request $request): View
    {
        $branches = Branch::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $selectedBranch = $branches->first(fn (Branch $branch): bool => $branch->slug === $request->query('branch') || (string) $branch->id === (string) $request->query('branch'));

        $seo = SeoService::page(
            is_english() ? 'Contact Dan Huong Chay | Vegetarian restaurant' : 'Liên hệ Đàn Hương Chay',
            is_english() ? 'Find Dan Huong Chay branches, opening hours, hotline and contact form for vegetarian dining.' : 'Thông tin liên hệ, địa chỉ các cơ sở, giờ mở cửa và form gửi tin nhắn cho Đàn Hương Chay.',
            is_english() ? 'contact vegetarian restaurant, Dan Huong Chay address, vegetarian restaurant phone' : 'liên hệ quán chay, địa chỉ quán chay, số điện thoại quán chay, nhà hàng chay',
            localized_route('contact')
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        return view('contact', compact('seo', 'schemas', 'branches', 'selectedBranch'));
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        Contact::create($request->validated());

        return redirect()
            ->to(localized_route('contact'))
            ->with('success', is_english() ? 'Thank you for contacting us. Dan Huong Chay will respond as soon as possible.' : 'Cảm ơn bạn đã liên hệ. Đàn Hương Chay sẽ phản hồi trong thời gian sớm nhất.');
    }
}
