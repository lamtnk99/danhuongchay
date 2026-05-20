<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\Branch;
use App\Models\Reservation;
use App\Services\SeoService;
use App\Support\OpeningHours;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function create(Request $request): View
    {
        $branches = Branch::query()
            ->active()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $selectedBranch = $this->selectedBranch($request, $branches);
        $openingHours = OpeningHours::fromBranch($selectedBranch);

        $seo = SeoService::page(
            is_english() ? 'Book a table | Dan Huong Chay' : 'Đặt bàn quán chay | Đàn Hương Chay',
            is_english() ? 'Book a table at Dan Huong Chay to enjoy vegetarian fusion cuisine in a peaceful restaurant space.' : 'Đặt bàn trước tại Đàn Hương Chay để thưởng thức món chay ngon, không gian an yên và khung giờ phục vụ linh hoạt.',
            is_english() ? 'book vegetarian restaurant, Dan Huong Chay reservation, vegetarian catering' : 'đặt bàn quán chay, nhà hàng chay, tiệc chay, mâm cúng chay',
            localized_route('reservations.create')
        );

        $schemas = [
            SeoService::restaurantSchema(),
        ];

        return view('reservations.create', compact('seo', 'schemas', 'openingHours', 'branches', 'selectedBranch'));
    }

    public function store(StoreReservationRequest $request): RedirectResponse
    {
        $data = $request->validated();
        Reservation::create($data + ['status' => 'pending']);

        return redirect()
            ->to(localized_route('reservations.create'))
            ->with('success', is_english() ? 'Your reservation request has been sent. Dan Huong Chay will contact you to confirm.' : 'Đặt bàn thành công. Đàn Hương Chay sẽ gọi xác nhận thông tin của bạn.');
    }

    private function selectedBranch(Request $request, $branches): ?Branch
    {
        $branchParam = $request->query('branch');

        if ($branchParam) {
            $branch = $branches->first(fn (Branch $branch): bool => (string) $branch->id === (string) $branchParam || $branch->slug === $branchParam);
            if ($branch) {
                return $branch;
            }
        }

        return $branches->first();
    }
}
