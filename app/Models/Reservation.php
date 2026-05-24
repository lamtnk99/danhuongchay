<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Reservation extends Model
{
    use HasFactory;

    public const STATUSES = ['pending', 'confirmed', 'cancelled', 'completed'];

    public const STATUS_LABELS = [
        'pending' => 'Chờ gọi xác nhận',
        'confirmed' => 'Đã giữ bàn',
        'cancelled' => 'Đã hủy',
        'completed' => 'Khách đã đến',
    ];

    protected $fillable = [
        'name',
        'branch_id',
        'phone',
        'email',
        'reservation_date',
        'reservation_time',
        'guests',
        'note',
        'status',
        'admin_note',
        'last_contacted_at',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
        'contact_attempts',
    ];

    protected function casts(): array
    {
        return [
            'reservation_date' => 'date',
            'last_contacted_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'contact_attempts' => 'integer',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(ReservationActivity::class)->latest('created_at');
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }

    public function scheduledAt(): Carbon
    {
        return Carbon::parse($this->reservation_date->toDateString().' '.substr((string) $this->reservation_time, 0, 5));
    }

    public function waitingMinutes(): int
    {
        if ($this->status !== 'pending') {
            return 0;
        }

        return max(0, (int) $this->created_at->diffInMinutes(now()));
    }

    public function needsUrgentCall(): bool
    {
        return $this->status === 'pending' && ($this->waitingMinutes() >= 30 || $this->scheduledAt()->lte(now()->addMinutes(90)));
    }

    public function isDueSoon(): bool
    {
        $scheduledAt = $this->scheduledAt();

        return $this->status === 'confirmed'
            && $scheduledAt->gte(now())
            && $scheduledAt->lte(now()->addMinutes(90));
    }

    public function isPastServiceTime(): bool
    {
        return in_array($this->status, ['pending', 'confirmed'], true) && $this->scheduledAt()->lt(now());
    }

    public function statusTone(): string
    {
        return match ($this->status) {
            'confirmed' => 'confirmed',
            'completed' => 'completed',
            'cancelled' => 'cancelled',
            default => $this->needsUrgentCall() ? 'urgent' : 'pending',
        };
    }
}
