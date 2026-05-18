<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'reservation_date',
        'reservation_time',
        'guests',
        'note',
        'status',
        'admin_note',
    ];

    protected function casts(): array
    {
        return [
            'reservation_date' => 'date',
        ];
    }
}
