<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use App\Models\ChatSession;
use App\Models\Contact;
use App\Models\Reservation;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $chatUnread = ChatMessage::query()
            ->where('sender', 'visitor')
            ->where('is_read', false)
            ->count();

        $reservationPending = Reservation::where('status', 'pending')->count();
        $contactNew = Contact::where('status', 'new')->count();

        $latestChats = ChatSession::query()
            ->latest('last_message_at')
            ->limit(5)
            ->get()
            ->map(function (ChatSession $chat): array {
                $latestMessage = $chat->messages()->latest()->first();

                return [
                    'type' => 'chat',
                    'title' => $chat->visitor_name ?: 'Khách ghé thăm',
                    'body' => $latestMessage?->message ?: 'Hội thoại mới',
                    'url' => route('admin.chats.show', $chat),
                    'time' => optional($chat->last_message_at)->format('H:i'),
                ];
            });

        $latestReservations = Reservation::query()
            ->where('status', 'pending')
            ->latest()
            ->limit(3)
            ->get()
            ->map(fn (Reservation $reservation): array => [
                'type' => 'reservation',
                'title' => $reservation->name,
                'body' => 'Đặt bàn '.$reservation->reservation_date->format('d/m/Y').' lúc '.substr($reservation->reservation_time, 0, 5),
                'url' => route('admin.reservations.show', $reservation),
                'time' => $reservation->created_at->format('H:i'),
            ]);

        $latestContacts = Contact::query()
            ->where('status', 'new')
            ->latest()
            ->limit(3)
            ->get()
            ->map(fn (Contact $contact): array => [
                'type' => 'contact',
                'title' => $contact->name,
                'body' => str($contact->message)->limit(80)->toString(),
                'url' => route('admin.contacts.show', $contact),
                'time' => $contact->created_at->format('H:i'),
            ]);

        return response()->json([
            'counts' => [
                'chat' => $chatUnread,
                'reservations' => $reservationPending,
                'contacts' => $contactNew,
                'total' => $chatUnread + $reservationPending + $contactNew,
            ],
            'items' => $latestChats
                ->concat($latestReservations)
                ->concat($latestContacts)
                ->take(10)
                ->values(),
        ]);
    }
}
