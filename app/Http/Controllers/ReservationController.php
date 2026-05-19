<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Reservation;
use App\Services\SeoService;
use App\Support\OpeningHours;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function create(): View
    {
        $seo = SeoService::page(
            is_english() ? 'Book a table | Dan Huong Chay Hai Phong' : 'Đặt bàn quán chay Hải Phòng | Đàn Hương Chay',
            is_english() ? 'Book a table at Dan Huong Chay to enjoy vegetarian fusion cuisine in a peaceful Hai Phong restaurant space.' : 'Đặt bàn trước tại Đàn Hương Chay để thưởng thức món chay ngon, không gian an yên và khung giờ phục vụ linh hoạt tại Hải Phòng.',
            is_english() ? 'book vegetarian restaurant Hai Phong, Dan Huong Chay reservation, vegetarian catering' : 'đặt bàn quán chay, đặt bàn Hải Phòng, nhà hàng chay Hải Phòng, tiệc chay, mâm cúng chay',
            localized_route('reservations.create')
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        $openingHours = OpeningHours::fromSetting();

        return view('reservations.create', compact('seo', 'schemas', 'openingHours'));
    }

    public function store(StoreReservationRequest $request): RedirectResponse
    {
        Reservation::create($request->validated() + ['status' => 'pending']);

        return redirect()
            ->to(localized_route('reservations.create'))
            ->with('success', is_english() ? 'Your reservation request has been sent. Dan Huong Chay will contact you to confirm.' : 'Đặt bàn thành công. Đàn Hương Chay sẽ gọi xác nhận thông tin của bạn.');
    }
}
