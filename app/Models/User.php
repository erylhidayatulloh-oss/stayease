<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'mitra_tier',
        'status',
        'phone',
        'avatar',
        'ktp_number',
        'ktp_name',
        'ktp_photo',
        'ktp_verified_at',
        'ktp_rejection_reason',
        'bank_name',
        'bank_account_number',
        'bank_account_holder',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'ktp_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }

    public function isMitra(): bool
    {
        return $this->role === 'mitra';
    }

    /**
     * Mitra Pro: Admin uploads/manages the property listing and handles
     * payment verification on this mitra's behalf. Mitra Pro still has
     * read-only access to their own dashboard, properties, and finance.
     */
    public function isMitraPro(): bool
    {
        return $this->role === 'mitra' && $this->mitra_tier === 'pro';
    }

    /**
     * Potongan platform: Mitra Biasa "numpang" di platform & urus listing +
     * pembayaran sendiri (potongan lebih kecil). Mitra Pro semua di-handle
     * tim Admin Stayease (upload listing & verifikasi pembayaran), sehingga
     * potongannya lebih besar sebagai kompensasi jasa pengelolaan penuh.
     */
    public function platformFeePercent(): float
    {
        return $this->isMitraPro() ? 0.15 : 0.05;
    }

    /**
     * Revisi: deteksi nama akun tidak sesuai nama di KTP (typo, nama
     * panggilan, dsb), supaya Admin bisa lihat peringatan saat verifikasi.
     */
    public function hasKtpNameMismatch(): bool
    {
        if (!$this->ktp_name) {
            return false;
        }

        return strtolower(trim($this->name)) !== strtolower(trim($this->ktp_name));
    }

    /**
     * Revisi: Ubah foto profil — `avatar` bisa berisi URL eksternal penuh
     * (data demo/seed lama) ATAU path relatif hasil upload ke storage
     * publik. Accessor ini menormalkan keduanya jadi satu URL yang bisa
     * langsung dipakai di <img src="...">.
     */
    public function getAvatarUrlAttribute(): string
    {
        if (!$this->avatar) {
            return 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=200&q=80';
        }

        if (str_starts_with($this->avatar, 'http://') || str_starts_with($this->avatar, 'https://')) {
            return $this->avatar;
        }

        return asset('storage/' . $this->avatar);
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Find an account by email, or create a lightweight guest account for it.
     *
     * The public storefront (booking, wishlist, chat, "Pasang Iklan") lets
     * visitors act without logging in first, but every one of those tables
     * still requires a real user_id. Bug fix: this used to be handled by
     * silently attributing everything to a single hardcoded demo account
     * (id 4), which mixed every guest's data together under one identity.
     * This instead ties each visitor's activity to their own persistent
     * account (matched by email), created on first contact.
     */
    public static function findOrCreateGuest(string $email, string $name, ?string $phone = null): self
    {
        $user = self::where('email', $email)->first();

        if ($user) {
            if ($phone && !$user->phone) {
                $user->update(['phone' => $phone]);
            }
            return $user;
        }

        return self::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make(Str::random(32)),
            'role' => 'user',
            'status' => 'active',
            'phone' => $phone,
        ]);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class, 'mitra_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class, 'mitra_id');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'user_id');
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'author_id');
    }
}
