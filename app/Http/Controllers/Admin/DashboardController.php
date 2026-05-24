<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\Contact;
use App\Models\Dish;
use App\Models\Post;
use App\Models\Reservation;
use App\Support\BranchAccess;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        [$startDate, $endDate] = $this->dateRange($request);
        $between = [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()];
        $todayReservations = BranchAccess::apply(Reservation::with('branch'), $request->user())
            ->whereDate('reservation_date', now()->toDateString())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('reservation_time')
            ->get();

        return view('admin.dashboard', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'dishCount' => Dish::whereBetween('created_at', $between)->count(),
            'postCount' => Post::whereBetween('created_at', $between)->count(),
            'reservationCount' => BranchAccess::apply(Reservation::query(), $request->user())->whereBetween('created_at', $between)->count(),
            'contactCount' => BranchAccess::apply(Contact::query(), $request->user())->whereBetween('created_at', $between)->count(),
            'chatCount' => BranchAccess::apply(ChatSession::query(), $request->user())->whereBetween('created_at', $between)->count(),
            'pendingReservationCount' => BranchAccess::apply(Reservation::query(), $request->user())->where('status', 'pending')->count(),
            'todayReservationCount' => $todayReservations->count(),
            'todayPendingReservationCount' => $todayReservations->where('status', 'pending')->count(),
            'todayDueSoonReservationCount' => $todayReservations->filter(fn (Reservation $reservation): bool => $reservation->isDueSoon())->count(),
            'todayPastReservationCount' => $todayReservations->filter(fn (Reservation $reservation): bool => $reservation->isPastServiceTime())->count(),
            'nextReservations' => $todayReservations->take(6),
            'newContactCount' => BranchAccess::apply(Contact::query(), $request->user())->where('status', 'new')->count(),
            'unreadChatCount' => BranchAccess::applyViaRelation(ChatMessage::query(), $request->user(), 'chatSession')->where('sender', 'visitor')->where('is_read', false)->count(),
            'latestReservations' => BranchAccess::apply(Reservation::with('branch'), $request->user())->whereBetween('created_at', $between)->latest()->limit(5)->get(),
            'latestContacts' => BranchAccess::apply(Contact::with('branch'), $request->user())->whereBetween('created_at', $between)->latest()->limit(5)->get(),
            'featuredDishes' => Dish::with('category')->featured()->latest()->limit(5)->get(),
            'latestPosts' => Post::with('category')->latest('published_at')->limit(5)->get(),
            'chartData' => $this->chartData($between[0], $between[1], $request->user()),
        ]);
    }

    private function dateRange(Request $request): array
    {
        $startDate = $request->date('start_date')
            ? Carbon::parse($request->date('start_date'))
            : now()->subDays(29);

        $endDate = $request->date('end_date')
            ? Carbon::parse($request->date('end_date'))
            : now();

        if ($startDate->gt($endDate)) {
            [$startDate, $endDate] = [$endDate, $startDate];
        }

        return [$startDate, $endDate];
    }

    private function chartData(Carbon $startDate, Carbon $endDate, $user): array
    {
        $days = collect(CarbonPeriod::create($startDate->copy()->startOfDay(), $endDate->copy()->startOfDay()))
            ->map(fn (Carbon $date): string => $date->toDateString())
            ->values();

        $reservations = $this->dailyCounts(Reservation::class, $startDate, $endDate, $user);
        $contacts = $this->dailyCounts(Contact::class, $startDate, $endDate, $user);
        $chats = $this->dailyCounts(ChatSession::class, $startDate, $endDate, $user);
        $reservationSeries = $days->map(fn (string $day): int => $reservations[$day] ?? 0);
        $contactSeries = $days->map(fn (string $day): int => $contacts[$day] ?? 0);
        $chatSeries = $days->map(fn (string $day): int => $chats[$day] ?? 0);
        $displayDays = $days->slice(max(0, $days->count() - 14))->values();
        $displayReservations = $displayDays->map(fn (string $day): int => $reservations[$day] ?? 0);
        $displayContacts = $displayDays->map(fn (string $day): int => $contacts[$day] ?? 0);
        $displayChats = $displayDays->map(fn (string $day): int => $chats[$day] ?? 0);
        $displayMax = max(1, $displayReservations->max() ?? 0, $displayContacts->max() ?? 0, $displayChats->max() ?? 0);
        $displayTotalMax = max(1, $displayDays
            ->map(fn (string $day): int => ($reservations[$day] ?? 0) + ($contacts[$day] ?? 0) + ($chats[$day] ?? 0))
            ->max() ?? 0);

        return [
            'days' => $days,
            'reservations' => $reservationSeries,
            'contacts' => $contactSeries,
            'chats' => $chatSeries,
            'totals' => [
                'reservations' => $reservationSeries->sum(),
                'contacts' => $contactSeries->sum(),
                'chats' => $chatSeries->sum(),
            ],
            'display_days' => $displayDays,
            'display_reservations' => $displayReservations,
            'display_contacts' => $displayContacts,
            'display_chats' => $displayChats,
            'display_max' => $displayMax,
            'display_total_max' => $displayTotalMax,
            'total_max' => max(1, $reservationSeries->sum(), $contactSeries->sum(), $chatSeries->sum()),
        ];
    }

    private function dailyCounts(string $modelClass, Carbon $startDate, Carbon $endDate, $user)
    {
        return BranchAccess::apply($modelClass::query(), $user)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get(['created_at'])
            ->groupBy(fn ($item): string => $item->created_at->toDateString())
            ->map->count();
    }
}
