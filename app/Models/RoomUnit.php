<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'name',
        'size_m2',
        'bed_type',
        'price_monthly',
        'price_yearly',
        'price_daily',
        'promo_price_monthly',
        'is_promo',
        'available_count',
        'total_count',
        'photos',
        'features',
    ];

    protected $casts = [
        'is_promo' => 'boolean',
        'photos' => 'array',
        'features' => 'array',
    ];

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
