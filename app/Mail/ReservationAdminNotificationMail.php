<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationAdminNotificationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public readonly Reservation $reservation) {}

    public function envelope(): Envelope
    {
        $branchName = $this->reservation->branch?->name ?: 'Chưa chọn cơ sở';
        $time = $this->reservation->reservation_date->format('d/m').' '.substr((string) $this->reservation->reservation_time, 0, 5);

        return new Envelope(
            subject: "[Đặt bàn mới] {$branchName} - {$time} - {$this->reservation->guests} khách",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reservations.admin-notification',
        );
    }
}
