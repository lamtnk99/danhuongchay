@php
    $branch = $reservation->branch;
    $scheduledTime = $reservation->reservation_date->format('d/m/Y').' lÃºc '.substr((string) $reservation->reservation_time, 0, 5);
    $contactPhones = collect([
        'Hotline' => $branch?->hotline ?: setting('hotline'),
        'Sá»‘ Ä‘iá»‡n thoáº¡i' => $branch?->phone ?: setting('phone'),
    ])
        ->map(fn ($phone) => trim((string) $phone))
        ->filter()
        ->unique()
        ->all();
@endphp

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ÄÃ n HÆ°Æ¡ng Chay Ä‘Ã£ nháº­n yÃªu cáº§u Ä‘áº·t bÃ n</title>
</head>
<body style="margin:0;background:#f7fbf8;color:#123026;font-family:Arial,Helvetica,sans-serif;">
    <div style="padding:32px 16px;">
        <div style="margin:0 auto;max-width:640px;border-radius:24px;background:#ffffff;padding:30px;border:1px solid #dbeadf;">
            <p style="margin:0 0 10px;color:#047857;font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;">ÄÃ n HÆ°Æ¡ng Chay</p>
            <h1 style="margin:0 0 16px;font-size:25px;line-height:1.35;color:#064e3b;">Cáº£m Æ¡n báº¡n Ä‘Ã£ gá»­i yÃªu cáº§u Ä‘áº·t bÃ n</h1>
            <p style="margin:0 0 22px;color:#475569;font-size:15px;line-height:1.8;">
                ÄÃ n HÆ°Æ¡ng Chay Ä‘Ã£ nháº­n thÃ´ng tin cá»§a báº¡n. NhÃ  hÃ ng sáº½ liÃªn há»‡ xÃ¡c nháº­n trÆ°á»›c khi giá»¯ bÃ n, Ä‘á»ƒ bá»¯a Äƒn Ä‘Æ°á»£c chuáº©n bá»‹ chu Ä‘Ã¡o vÃ  nháº¹ nhÃ ng nháº¥t.
            </p>

            <div style="border-radius:18px;background:#ecfdf5;padding:20px;margin-bottom:22px;">
                <p style="margin:0 0 10px;color:#065f46;font-size:15px;"><strong>Há» tÃªn:</strong> {{ $reservation->name }}</p>
                <p style="margin:0 0 10px;color:#065f46;font-size:15px;"><strong>CÆ¡ sá»Ÿ:</strong> {{ $branch?->name ?: 'ÄÃ n HÆ°Æ¡ng Chay' }}</p>
                <p style="margin:0 0 10px;color:#065f46;font-size:15px;"><strong>Thá»i gian:</strong> {{ $scheduledTime }}</p>
                <p style="margin:0;color:#065f46;font-size:15px;"><strong>Sá»‘ khÃ¡ch:</strong> {{ $reservation->guests }}</p>
            </div>

            @if ($branch?->address)
                <p style="margin:0 0 12px;color:#334155;font-size:14px;line-height:1.7;"><strong>Äá»‹a chá»‰:</strong> {{ $branch->address }}</p>
            @endif

            @if ($contactPhones)
                <p style="margin:0 0 22px;color:#334155;font-size:14px;line-height:1.7;">
                    Cần điều chỉnh thông tin đặt bàn, bạn có thể liên hệ:
                    @foreach ($contactPhones as $label => $phone)
                        <br>{{ $label }}: <a href="tel:{{ preg_replace('/\D+/', '', $phone) }}" style="color:#047857;font-weight:700;">{{ $phone }}</a>
                    @endforeach
                </p>
            @endif

            <p style="margin:0;color:#64748b;font-size:13px;line-height:1.7;">Email nÃ y xÃ¡c nháº­n há»‡ thá»‘ng Ä‘Ã£ nháº­n yÃªu cáº§u Ä‘áº·t bÃ n. BÃ n chá»‰ Ä‘Æ°á»£c giá»¯ sau khi nhÃ  hÃ ng liÃªn há»‡ xÃ¡c nháº­n.</p>
        </div>
    </div>
</body>
</html>
