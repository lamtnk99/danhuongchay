<?php

namespace App\Support;

use Carbon\Carbon;

class OpeningHours
{
    public function __construct(
        public readonly string $opensAt,
        public readonly string $closesAt,
        public readonly string $label,
    ) {}

    public static function fromSetting(?string $value = null): self
    {
        $label = trim((string) ($value ?: setting('opening_hours', '09:00 - 21:30 hằng ngày')));

        if (preg_match_all('/\b([01]?\d|2[0-3]):([0-5]\d)\b/', $label, $matches) >= 2) {
            return new self(
                self::normalizeTime($matches[0][0]),
                self::normalizeTime($matches[0][1]),
                $label
            );
        }

        return new self('09:00', '21:30', '09:00 - 21:30 hằng ngày');
    }

    public function isWithin(string $time): bool
    {
        return $time >= $this->opensAt && $time <= $this->closesAt;
    }

    public function isPastToday(string $date, string $time): bool
    {
        if ($date !== today()->toDateString()) {
            return false;
        }

        return Carbon::createFromFormat('Y-m-d H:i', $date.' '.$time)->lte(now());
    }

    public function message(): string
    {
        return "Quán nhận đặt bàn trong khung {$this->opensAt} - {$this->closesAt}.";
    }

    private static function normalizeTime(string $time): string
    {
        return Carbon::createFromFormat('H:i', str_pad($time, 5, '0', STR_PAD_LEFT))->format('H:i');
    }
}
