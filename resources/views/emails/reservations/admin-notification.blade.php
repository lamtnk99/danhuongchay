@php
    $branch = $reservation->branch;
    $scheduledTime = $reservation->reservation_date->format('d/m/Y').' lúc '.substr((string) $reservation->reservation_time, 0, 5);
@endphp

<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đặt bàn mới</title>
</head>
<body style="margin:0;background:#f8fafc;color:#0f172a;font-family:Arial,Helvetica,sans-serif;">
    <div style="padding:28px 16px;">
        <div style="margin:0 auto;max-width:640px;border-radius:20px;background:#ffffff;padding:28px;border:1px solid #e2e8f0;">
            <p style="margin:0 0 8px;color:#047857;font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;">Đặt bàn mới</p>
            <h1 style="margin:0 0 16px;font-size:24px;line-height:1.3;color:#064e3b;">{{ $reservation->name }} đặt bàn {{ $reservation->guests }} khách</h1>
            <p style="margin:0 0 24px;color:#475569;font-size:15px;line-height:1.7;">Khách vừa gửi yêu cầu đặt bàn. Vui lòng gọi xác nhận trước khi giữ bàn.</p>

            <div style="border-radius:16px;background:#ecfdf5;padding:18px;margin-bottom:20px;">
                <p style="margin:0 0 8px;font-size:14px;color:#065f46;"><strong>Cơ sở:</strong> {{ $branch?->name ?: 'Chưa chọn cơ sở' }}</p>
                <p style="margin:0 0 8px;font-size:14px;color:#065f46;"><strong>Thời gian:</strong> {{ $scheduledTime }}</p>
                <p style="margin:0;font-size:14px;color:#065f46;"><strong>Số khách:</strong> {{ $reservation->guests }}</p>
            </div>

            <table role="presentation" cellpadding="0" cellspacing="0" style="width:100%;border-collapse:collapse;margin-bottom:20px;">
                <tr>
                    <td style="padding:10px 0;color:#64748b;width:140px;font-size:14px;">Khách hàng</td>
                    <td style="padding:10px 0;color:#0f172a;font-size:14px;font-weight:700;">{{ $reservation->name }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;color:#64748b;font-size:14px;">Điện thoại</td>
                    <td style="padding:10px 0;color:#0f172a;font-size:14px;font-weight:700;"><a href="tel:{{ preg_replace('/\D+/', '', $reservation->phone) }}" style="color:#047857;">{{ $reservation->phone }}</a></td>
                </tr>
                <tr>
                    <td style="padding:10px 0;color:#64748b;font-size:14px;">Email</td>
                    <td style="padding:10px 0;color:#0f172a;font-size:14px;">{{ $reservation->email ?: 'Không có' }}</td>
                </tr>
                <tr>
                    <td style="padding:10px 0;color:#64748b;font-size:14px;">Ghi chú</td>
                    <td style="padding:10px 0;color:#0f172a;font-size:14px;">{{ $reservation->note ?: 'Không có' }}</td>
                </tr>
            </table>

            <a href="{{ route('admin.reservations.show', $reservation) }}" style="display:inline-block;border-radius:999px;background:#047857;color:#ffffff;text-decoration:none;padding:12px 18px;font-size:14px;font-weight:700;">Mở chi tiết trong admin</a>
        </div>
    </div>
</body>
</html>
