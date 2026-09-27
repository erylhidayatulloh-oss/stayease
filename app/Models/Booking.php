<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'property_id',
        'room_unit_id',
        'check_in_date',
        'duration_months',
        'status',
        'base_rent_total',
        'deposit_total',
        'service_fee',
        'addons_total',
        'discount_total',
        'grand_total',
        'addons_json',
        'payment_method',
        'payment_status',
        'payment_ref',
        'paid_at',
        'contract_signed_at',
        'signature_data',
        'tenant_name',
        'tenant_phone',
        'tenant_email',
        'tenant_nik',
        'emergency_name',
        'emergency_phone',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'paid_at' => 'datetime',
        'contract_signed_at' => 'datetime',
        'addons_json' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(RoomUnit::class, 'room_unit_id');
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Mark this booking's payment as verified and advance its lifecycle status
     * accordingly. Used both by Admin's manual verification screen and by the
     * public booking API (simulating an automated payment gateway callback).
     */
    public function markPaymentVerified(): void
    {
        if ($this->status === 'pending_payment') {
            $this->status = 'paid';
        }
        $this->payment_status = 'verified';
        $this->paid_at = $this->paid_at ?? now();
        $this->save();
    }
}
