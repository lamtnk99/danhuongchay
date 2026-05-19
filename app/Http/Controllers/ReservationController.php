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
            'Đặt bàn quán chay Hải Phòng | Đàn Hương Chay',
            'Đặt bàn trước tại Đàn Hương Chay để thưởng thức món chay ngon, không gian an yên và khung giờ phục vụ linh hoạt tại Hải Phòng.',
            'đặt bàn quán chay, đặt bàn Hải Phòng, nhà hàng chay Hải Phòng, tiệc chay, mâm cúng chay',
            route('reservations.create')
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
            ->route('reservations.create')
            ->with('success', 'Đặt bàn thành công. Đàn Hương Chay sẽ gọi xác nhận thông tin của bạn.');
    }
}
