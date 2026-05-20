<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'city',
        'address',
        'phone',
        'hotline',
        'email',
        'opening_hours',
        'reservation_time_slots',
        'reservation_last_booking_time',
        'reservation_last_order_buffer_minutes',
        'google_map_iframe',
        'description',
        'image',
        'facebook_url',
        'zalo_url',
        'is_active',
        'sort_order',
        'meta_title',
        'meta_description',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
            'reservation_last_order_buffer_minutes' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Branch $branch): void {
            if (blank($branch->slug)) {
                $branch->slug = Str::slug($branch->name);
            }
        });
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(GalleryImage::class);
    }
}
