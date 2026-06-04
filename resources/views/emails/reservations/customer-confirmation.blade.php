@php
    $branch = $reservation->branch;
    $scheduledTime = $reservation->reservation_date->format('d/m/Y').' '.__('site.email.customer_reservation.time_connector').' '.substr((string) $reservation->reservation_time, 0, 5);
    $contactPhones = collect([
        'Hotline' => $branch?->hotline ?: setting('hotline'),
        __('site.email.customer_reservation.phone') => $branch?->phone ?: setting('phone'),
    ])
        ->map(fn ($phone) => trim((string) $phone))
        ->filter()
        ->unique()
        ->all();
@endphp

<!doctype html>
<html lang="{{ current_locale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('site.email.customer_reservation.subject') }}</title>
</head>
<body style="margin:0;background:#f7fbf8;color:#123026;font-family:Arial,Helvetica,sans-serif;">
    <div style="padding:32px 16px;">
        <div style="margin:0 auto;max-width:640px;border-radius:24px;background:#ffffff;padding:30px;border:1px solid #dbeadf;">
            <p style="margin:0 0 10px;color:#047857;font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;">{{ __('site.email.customer_reservation.brand') }}</p>
            <h1 style="margin:0 0 16px;font-size:25px;line-height:1.35;color:#064e3b;">{{ __('site.email.customer_reservation.title') }}</h1>
            <p style="margin:0 0 22px;color:#475569;font-size:15px;line-height:1.8;">
                {{ __('site.email.customer_reservation.intro') }}
            </p>

            <div style="border-radius:18px;background:#ecfdf5;padding:20px;margin-bottom:22px;">
                <p style="margin:0 0 10px;color:#065f46;font-size:15px;"><strong>{{ __('site.email.customer_reservation.name') }}:</strong> {{ $reservation->name }}</p>
                <p style="margin:0 0 10px;color:#065f46;font-size:15px;"><strong>{{ __('site.email.customer_reservation.branch') }}:</strong> {{ $branch?->name ?: __('site.email.customer_reservation.default_branch') }}</p>
                <p style="margin:0 0 10px;color:#065f46;font-size:15px;"><strong>{{ __('site.email.customer_reservation.time') }}:</strong> {{ $scheduledTime }}</p>
                <p style="margin:0;color:#065f46;font-size:15px;"><strong>{{ __('site.email.customer_reservation.guests') }}:</strong> {{ $reservation->guests }}</p>
            </div>

            @if ($branch?->address)
                <p style="margin:0 0 12px;color:#334155;font-size:14px;line-height:1.7;"><strong>{{ __('site.email.customer_reservation.address') }}:</strong> {{ $branch->address }}</p>
            @endif

            @if ($contactPhones)
                <p style="margin:0 0 22px;color:#334155;font-size:14px;line-height:1.7;">
                    {{ __('site.email.customer_reservation.contact_intro') }}
                    @foreach ($contactPhones as $label => $phone)
                        <br>{{ $label }}: <a href="tel:{{ preg_replace('/\D+/', '', $phone) }}" style="color:#047857;font-weight:700;">{{ $phone }}</a>
                    @endforeach
                </p>
            @endif

            <p style="margin:0;color:#64748b;font-size:13px;line-height:1.7;">{{ __('site.email.customer_reservation.footer') }}</p>
        </div>
    </div>
</body>
</html>
