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
            'Đặt bàn tại Đàn Hương Chay',
            'Đặt bàn trước tại Đàn Hương Chay để thưởng thức bữa chay thanh lành trong không gian yên tĩnh.',
            'đặt bàn quán chay, đặt bàn Đàn Hương Chay, nhà hàng chay',
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
