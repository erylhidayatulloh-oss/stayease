<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'discount_type',
        'discount_value',
        'max_discount',
        'min_transaction',
        'badge',
        'description',
        'usage_limit',
        'used_count',
        'is_active',
        'valid_until',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'valid_until' => 'date',
    ];
}
