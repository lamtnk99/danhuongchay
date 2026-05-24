<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Reservation;
use App\Models\ReservationActivity;
use App\Support\BranchAccess;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReservationController extends Controller
{
    public function index(Request $request): View
    {
        $selectedDate = $request->filled('date')
            ? $request->date('date')->toDateString()
            : ($request->filled('q') ? null : now()->toDateString());

        $status = $request->input('status');
        $reservations = $this->filteredReservations($request, $selectedDate, $status)
            ->limit(200)
            ->get();

        $sections = $this->sections($reservations);
        $todayStats = $this->todayStats($request);

        $branches = Branch::query()
            ->active()
            ->when($request->user()?->branch_id, fn ($query) => $query->where('id', $request->user()->branch_id))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.reservations.index', compact('reservations', 'sections', 'branches', 'selectedDate', 'status', 'todayStats'));
    }

    private function filteredReservations(Request $request, ?string $selectedDate, ?string $status): Builder
    {
        return BranchAccess::apply(Reservation::query()->with('branch'), $request->user())
            ->when($request->filled('q'), function ($query) use ($request): void {
                $query->where(function ($query) use ($request): void {
                    $query->where('name', 'like', '%'.$request->q.'%')
                        ->orWhere('phone', 'like', '%'.$request->q.'%');
                });
            })
            ->when($status && $status !== 'active', fn ($query) => $query->where('status', $status))
            ->when(! $status && ! $request->filled('q'), fn ($query) => $query->whereIn('status', ['pending', 'confirmed']))
            ->when($status === 'active', fn ($query) => $query->whereIn('status', ['pending', 'confirmed']))
            ->when($request->filled('branch_id'), fn ($query) => $query->where('branch_id', $request->branch_id))
            ->when($selectedDate, fn ($query) => $query->whereDate('reservation_date', $selectedDate))
            ->orderBy('reservation_date')
            ->orderBy('reservation_time')
            ->orderBy('created_at');
    }

    private function sections(Collection $reservations): array
    {
        $urgentIds = $reservations
            ->filter(fn (Reservation $reservation): bool => $reservation->needsUrgentCall())
            ->pluck('id');
        $dueSoonIds = $reservations
            ->filter(fn (Reservation $reservation): bool => $reservation->isDueSoon())
            ->pluck('id');
        $pastIds = $reservations
            ->filter(fn (Reservation $reservation): bool => $reservation->isPastServiceTime())
            ->pluck('id');

        return [
            [
                'key' => 'urgent',
                'title' => 'Cần gọi ngay',
                'hint' => 'Đơn chờ quá 30 phút hoặc gần đến giờ dùng bữa.',
                'tone' => 'danger',
                'items' => $reservations->whereIn('id', $urgentIds),
            ],
            [
                'key' => 'pending',
                'title' => 'Chờ gọi xác nhận',
                'hint' => 'Đơn mới cần liên hệ khách trước khi giữ bàn.',
                'tone' => 'warning',
                'items' => $reservations
                    ->where('status', 'pending')
                    ->whereNotIn('id', $urgentIds),
            ],
            [
                'key' => 'soon',
                'title' => 'Sắp đến giờ',
                'hint' => 'Bàn đã giữ trong 90 phút tới.',
                'tone' => 'info',
                'items' => $reservations->whereIn('id', $dueSoonIds),
            ],
            [
                'key' => 'confirmed',
                'title' => 'Đã giữ bàn',
                'hint' => 'Theo dõi khách đến và chuyển hoàn tất sau khi phục vụ.',
                'tone' => 'success',
                'items' => $reservations
                    ->where('status', 'confirmed')
                    ->whereNotIn('id', $dueSoonIds)
                    ->whereNotIn('id', $pastIds),
            ],
            [
                'key' => 'past',
                'title' => 'Đã qua giờ chưa chốt',
                'hint' => 'Cần đánh dấu khách đã đến hoặc hủy để không sót việc.',
                'tone' => 'danger',
                'items' => $reservations->whereIn('id', $pastIds),
            ],
            [
                'key' => 'closed',
                'title' => 'Đã kết thúc',
                'hint' => 'Các đơn đã hoàn tất hoặc hủy trong bộ lọc hiện tại.',
                'tone' => 'muted',
                'items' => $reservations->whereIn('status', ['completed', 'cancelled']),
            ],
        ];
    }

    private function todayStats(Request $request): array
    {
        $todayReservations = BranchAccess::apply(Reservation::query(), $request->user())
            ->whereDate('reservation_date', now()->toDateString())
            ->get();

        return [
            'total' => $todayReservations->count(),
            'pending' => $todayReservations->where('status', 'pending')->count(),
            'confirmed' => $todayReservations->where('status', 'confirmed')->count(),
            'urgent' => $todayReservations->filter(fn (Reservation $reservation): bool => $reservation->needsUrgentCall())->count(),
            'due_soon' => $todayReservations->filter(fn (Reservation $reservation): bool => $reservation->isDueSoon())->count(),
            'past' => $todayReservations->filter(fn (Reservation $reservation): bool => $reservation->isPastServiceTime())->count(),
        ];
    }

    public function show(Reservation $reservation): View
    {
        BranchAccess::authorize(auth()->user(), $reservation->branch_id);
        $reservation->load(['branch', 'activities.user']);

        return view('admin.reservations.show', compact('reservation'));
    }

    public function update(Request $request, Reservation $reservation): RedirectResponse
    {
        BranchAccess::authorize($request->user(), $reservation->branch_id);

        $data = $request->validate([
            'workflow_action' => ['nullable', Rule::in(['contact_attempt', 'confirmed', 'cancelled', 'completed'])],
            'status' => ['required_without:workflow_action', Rule::in(Reservation::STATUSES)],
            'admin_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $fromStatus = $reservation->status;

        if (! empty($data['workflow_action'])) {
            $this->applyWorkflowAction($reservation, $request, $data['workflow_action'], $data['admin_note'] ?? null);
        } else {
            $this->applyManualUpdate($reservation, $request, $data, $fromStatus);
        }

        return back()->with('success', 'Đã cập nhật đặt bàn.');
    }

    private function applyWorkflowAction(Reservation $reservation, Request $request, string $action, ?string $note): void
    {
        $fromStatus = $reservation->status;
        $updates = [];

        if ($note !== null) {
            $updates['admin_note'] = $note;
        }

        $updates = array_merge($updates, match ($action) {
            'contact_attempt' => [
                'last_contacted_at' => now(),
                'contact_attempts' => min(255, (int) $reservation->contact_attempts + 1),
            ],
            'confirmed' => [
                'status' => 'confirmed',
                'confirmed_at' => $reservation->confirmed_at ?: now(),
                'last_contacted_at' => $reservation->last_contacted_at ?: now(),
                'contact_attempts' => max(1, (int) $reservation->contact_attempts),
            ],
            'cancelled' => [
                'status' => 'cancelled',
                'cancelled_at' => now(),
            ],
            'completed' => [
                'status' => 'completed',
                'completed_at' => now(),
            ],
        });

        $reservation->update($updates);

        $this->logActivity($reservation, $request, $action, $fromStatus, $reservation->status, $note);
    }

    private function applyManualUpdate(Reservation $reservation, Request $request, array $data, string $fromStatus): void
    {
        $updates = [
            'status' => $data['status'],
            'admin_note' => $data['admin_note'] ?? null,
        ];

        if ($fromStatus !== $data['status']) {
            $updates = array_merge($updates, match ($data['status']) {
                'confirmed' => ['confirmed_at' => $reservation->confirmed_at ?: now()],
                'completed' => ['completed_at' => now()],
                'cancelled' => ['cancelled_at' => now()],
                default => [],
            });
        }

        $reservation->update($updates);

        $this->logActivity($reservation, $request, 'updated', $fromStatus, $reservation->status, $data['admin_note'] ?? null);
    }

    private function logActivity(Reservation $reservation, Request $request, string $action, ?string $fromStatus, ?string $toStatus, ?string $note): void
    {
        ReservationActivity::create([
            'reservation_id' => $reservation->id,
            'user_id' => $request->user()?->id,
            'action' => $action,
            'from_status' => $fromStatus,
            'to_status' => $toStatus,
            'note' => $note,
            'created_at' => now(),
        ]);
    }

    public function destroy(Reservation $reservation): RedirectResponse
    {
        BranchAccess::authorize(auth()->user(), $reservation->branch_id);

        $reservation->delete();

        return redirect()->route('admin.reservations.index')->with('success', 'Đã xóa đặt bàn.');
    }
}
