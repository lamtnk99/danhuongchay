<?php

namespace App\Support;

use Carbon\Carbon;

class OpeningHours
{
    /**
     * @param array<int, array{start: string, end: string}> $slots
     */
    public function __construct(
        public readonly array $slots,
        public readonly string $opensAt,
        public readonly string $closesAt,
        public readonly ?string $lastBookingTime,
        public readonly int $bufferMinutes,
        public readonly string $label,
    ) {}

    public static function fromSetting(?string $value = null): self
    {
        $openingLabel = trim((string) ($value ?: setting('opening_hours', '09:00 - 21:30 hằng ngày')));
        $slotSetting = trim((string) setting('reservation_time_slots', ''));
        $lastBookingTime = self::extractTime((string) setting('reservation_last_booking_time', ''));
        $bufferMinutes = max(0, (int) setting('reservation_last_order_buffer_minutes', 30));

        $slots = self::parseSlots($slotSetting);
        if ($slots === []) {
            $slots = self::parseSlotsFromFreeText($openingLabel);
        }
        if ($slots === []) {
            $slots = [['start' => '09:00', 'end' => '21:30']];
        }

        $opensAt = $slots[0]['start'];
        $closesAt = $slots[array_key_last($slots)]['end'];

        return new self(
            slots: $slots,
            opensAt: $opensAt,
            closesAt: $closesAt,
            lastBookingTime: $lastBookingTime,
            bufferMinutes: $bufferMinutes,
            label: self::buildLabel($slots, $lastBookingTime, $bufferMinutes),
        );
    }

    public function isWithin(string $time): bool
    {
        $normalizedTime = self::normalizeTime($time);

        foreach ($this->slots as $slot) {
            $latestInSlot = $this->latestBookableInSlot($slot['start'], $slot['end']);
            if ($latestInSlot < $slot['start']) {
                continue;
            }

            if ($normalizedTime >= $slot['start'] && $normalizedTime <= $latestInSlot) {
                return true;
            }
        }

        return false;
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
        return "Quán nhận đặt bàn theo khung giờ: {$this->label}.";
    }

    /**
     * @return array<int, array{start: string, end: string}>
     */
    public function bookableSlots(): array
    {
        $slots = [];

        foreach ($this->slots as $slot) {
            $bookableEnd = $this->latestBookableInSlot($slot['start'], $slot['end']);
            if ($bookableEnd >= $slot['start']) {
                $slots[] = ['start' => $slot['start'], 'end' => $bookableEnd];
            }
        }

        return $slots;
    }

    public function firstBookableTime(): ?string
    {
        return $this->bookableSlots()[0]['start'] ?? null;
    }

    public function lastBookableTime(): ?string
    {
        $slots = $this->bookableSlots();

        return $slots === [] ? null : $slots[array_key_last($slots)]['end'];
    }

    private static function normalizeTime(string $time): string
    {
        return Carbon::createFromFormat('H:i', str_pad($time, 5, '0', STR_PAD_LEFT))->format('H:i');
    }

    /**
     * @return array<int, array{start: string, end: string}>
     */
    private static function parseSlots(string $value): array
    {
        if ($value === '') {
            return [];
        }

        $items = preg_split('/[\r\n,;|]+/', $value) ?: [];
        $slots = [];

        foreach ($items as $item) {
            if (! preg_match('/([01]?\d|2[0-3]):([0-5]\d)\s*[-–]\s*([01]?\d|2[0-3]):([0-5]\d)/', $item, $matches)) {
                continue;
            }

            $start = self::normalizeTime($matches[1].':'.$matches[2]);
            $end = self::normalizeTime($matches[3].':'.$matches[4]);
            if ($start >= $end) {
                continue;
            }

            $slots[] = compact('start', 'end');
        }

        usort($slots, fn (array $a, array $b): int => strcmp($a['start'], $b['start']));

        return $slots;
    }

    /**
     * @return array<int, array{start: string, end: string}>
     */
    private static function parseSlotsFromFreeText(string $text): array
    {
        preg_match_all('/([01]?\d|2[0-3]):([0-5]\d)\s*[-–]\s*([01]?\d|2[0-3]):([0-5]\d)/', $text, $matches, PREG_SET_ORDER);

        $slots = [];
        foreach ($matches as $match) {
            $start = self::normalizeTime($match[1].':'.$match[2]);
            $end = self::normalizeTime($match[3].':'.$match[4]);
            if ($start < $end) {
                $slots[] = compact('start', 'end');
            }
        }

        if ($slots !== []) {
            usort($slots, fn (array $a, array $b): int => strcmp($a['start'], $b['start']));
        }

        return $slots;
    }

    private static function extractTime(string $value): ?string
    {
        if (! preg_match('/([01]?\d|2[0-3]):([0-5]\d)/', $value, $matches)) {
            return null;
        }

        return self::normalizeTime($matches[1].':'.$matches[2]);
    }

    private function latestBookableInSlot(string $slotStart, string $slotEnd): string
    {
        $slotClose = Carbon::createFromFormat('H:i', $slotEnd);
        $bufferEnd = $slotClose->copy()->subMinutes($this->bufferMinutes)->format('H:i');

        $latest = $bufferEnd;
        if ($this->lastBookingTime) {
            $latest = min($latest, $this->lastBookingTime);
        }

        return max($slotStart, $latest);
    }

    /**
     * @param array<int, array{start: string, end: string}> $slots
     */
    private static function buildLabel(array $slots, ?string $lastBookingTime, int $bufferMinutes): string
    {
        $base = collect($slots)->map(fn (array $slot): string => "{$slot['start']} - {$slot['end']}")->implode(', ');
        $parts = [$base];

        if ($lastBookingTime) {
            $parts[] = "nhận đặt bàn đến {$lastBookingTime}";
        } elseif ($bufferMinutes > 0) {
            $parts[] = "ngừng nhận trước giờ đóng bếp {$bufferMinutes} phút";
        }

        return implode(' | ', $parts);
    }
}

