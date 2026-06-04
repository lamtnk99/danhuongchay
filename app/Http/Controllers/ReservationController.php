<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Mail\ReservationAdminNotificationMail;
use App\Mail\ReservationCustomerConfirmationMail;
use App\Models\Branch;
use App\Models\Reservation;
use App\Services\SeoService;
use App\Support\OpeningHours;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

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
            __('site.seo_fallback.reservation_title'),
            __('site.seo_fallback.reservation_description'),
            __('site.seo_fallback.reservation_keywords'),
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
        $reservation = Reservation::create($data + ['status' => 'pending']);
        $reservation->load('branch');

        $this->sendReservationEmails($reservation);

        return redirect()
            ->to(localized_route('reservations.create'))
            ->with('success', __('site.reservation.success'));
    }

    private function sendReservationEmails(Reservation $reservation): void
    {
        $adminRecipient = $this->reservationNotificationRecipient($reservation);

        if ($adminRecipient) {
            $this->sendMailSafely(
                fn () => Mail::to($adminRecipient)->queue(new ReservationAdminNotificationMail($reservation)),
                $reservation,
                'admin_notification'
            );
        }

        if (filled($reservation->email)) {
            $this->sendMailSafely(
                fn () => Mail::to($reservation->email)
                    ->locale(current_locale())
                    ->queue(new ReservationCustomerConfirmationMail($reservation)),
                $reservation,
                'customer_confirmation'
            );
        }
    }

    private function reservationNotificationRecipient(Reservation $reservation): ?string
    {
        return collect([
            $reservation->branch?->reservation_notification_email,
            $reservation->branch?->email,
            setting('email'),
        ])
            ->map(fn ($email): string => trim((string) $email))
            ->first(fn (string $email): bool => filter_var($email, FILTER_VALIDATE_EMAIL) !== false);
    }

    private function sendMailSafely(callable $callback, Reservation $reservation, string $type): void
    {
        try {
            $callback();
        } catch (Throwable $exception) {
            Log::warning('Reservation mail could not be sent.', [
                'reservation_id' => $reservation->id,
                'mail_type' => $type,
                'error' => $exception->getMessage(),
            ]);
        }
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
