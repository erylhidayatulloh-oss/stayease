<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'mitra_id',
        'city_id',
        'title',
        'slug',
        'property_type',
        'gender_restriction',
        'address',
        'sub_district',
        'coords_lat',
        'coords_lng',
        'base_price_monthly',
        'base_price_yearly',
        'base_price_daily',
        'discount_percent',
        'deposit_amount',
        'electricity_policy',
        'service_fee',
        'verified_official',
        'has_virtual_tour',
        'status',
        'rejection_reason',
        'images',
        'virtual_tour_url',
        'facilities',
        'rules',
        'transit_points',
        'description',
        'rating_avg',
        'reviews_count',
    ];

    protected $casts = [
        'verified_official' => 'boolean',
        'has_virtual_tour' => 'boolean',
        'images' => 'array',
        'facilities' => 'array',
        'rules' => 'array',
        'transit_points' => 'array',
        'rating_avg' => 'float',
    ];

    public function mitra(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mitra_id');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(RoomUnit::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function getFinalPriceAttribute(): int
    {
        if ($this->discount_percent > 0) {
            return (int) round($this->base_price_monthly * (1 - $this->discount_percent / 100));
        }
        return (int) $this->base_price_monthly;
    }
}
