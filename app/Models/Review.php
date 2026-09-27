<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected static function booted(): void
    {
        static::created(fn (Review $review) => $review->recalculatePropertyRating());
        static::updated(fn (Review $review) => $review->wasChanged('status') && $review->recalculatePropertyRating());
        static::deleted(fn (Review $review) => $review->recalculatePropertyRating());
    }

    protected function recalculatePropertyRating(): void
    {
        $property = $this->property;
        if (!$property) {
            return;
        }

        $published = Review::where('property_id', $property->id)
            ->where('status', '!=', 'hidden')
            ->get();

        $property->update([
            'rating_avg' => $published->count() > 0 ? round($published->avg('rating'), 2) : 5.00,
            'reviews_count' => $published->count(),
        ]);
    }

    protected $fillable = [
        'booking_id',
        'user_id',
        'property_id',
        'rating',
        'cleanliness_rating',
        'security_rating',
        'location_rating',
        'comment',
        'owner_reply',
        'owner_replied_at',
        'status',
    ];

    protected $casts = [
        'owner_replied_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}
