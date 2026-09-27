<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'province',
        'image',
        'description',
        'avg_kost_price',
        'avg_apt_price',
        'is_active',
        'popular_districts',
        'top_universities',
        'faqs',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'popular_districts' => 'array',
        'top_universities' => 'array',
        'faqs' => 'array',
    ];

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
