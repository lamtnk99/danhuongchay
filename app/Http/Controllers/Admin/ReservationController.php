<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Reservation;
use App\Support\BranchAccess;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(Request $request): View
    {
        $reservations = Reservation::query()
            ->with('branch')
            ->tap(fn ($query) => BranchAccess::apply($query, $request->user()))
            ->when($request->filled('q'), function ($query) use ($request): void {
                $query->where(function ($query) use ($request): void {
                    $query->where('name', 'like', '%'.$request->q.'%')
                        ->orWhere('phone', 'like', '%'.$request->q.'%');
                });
            })
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->status))
            ->when($request->filled('branch_id'), fn ($query) => $query->where('branch_id', $request->branch_id))
            ->when($request->filled('date'), fn ($query) => $query->whereDate('reservation_date', $request->date))
            ->latest('reservation_date')
            ->paginate(10)
            ->withQueryString();

        $branches = Branch::query()
            ->active()
            ->when($request->user()?->branch_id, fn ($query) => $query->where('id', $request->user()->branch_id))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.reservations.index', compact('reservations', 'branches'));
    }

    public function show(Reservation $reservation): View
    {
        $reservation->load('branch');
        BranchAccess::authorize(auth()->user(), $reservation->branch_id);

        return view('admin.reservations.show', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        BranchAccess::authorize($request->user(), $reservation->branch_id);

        $data = $request->validate([
            'status' => ['required', Rule::in(['pending', 'confirmed', 'cancelled', 'completed'])],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $reservation->update($data);

        return redirect()->route('admin.reservations.show', $reservation)->with('success', 'Đã cập nhật đặt bàn.');
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        BranchAccess::authorize(auth()->user(), $reservation->branch_id);

        $reservation->delete();

        return redirect()->route('admin.reservations.index')->with('success', 'Đã xóa đặt bàn.');
    }
}
