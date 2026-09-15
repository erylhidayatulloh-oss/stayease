<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\City;
use App\Models\Promo;
use App\Models\Booking;
use App\Models\User;
use App\Models\Wishlist;
use App\Models\Message;
use App\Models\RoomUnit;
use App\Models\AuditLog;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function getProperties(Request $request): JsonResponse
    {
        $query = Property::with(['city', 'units', 'mitra'])->where('status', 'active');

        if ($request->has('city') && !empty($request->city)) {
            $query->whereHas('city', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->city}%")
                  ->orWhere('slug', $request->city);
            });
        }

        if ($request->has('type') && !empty($request->type) && $request->type !== 'all') {
            $query->where('property_type', $request->type);
        }

        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('base_price_monthly', '<=', $request->max_price);
        }

        if ($request->has('search') && !empty($request->search)) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('sub_district', 'like', "%{$s}%")
                  ->orWhere('address', 'like', "%{$s}%");
            });
        }

        $properties = $query->latest()->paginate($request->input('per_page', 12));

        return response()->json([
            'status' => 'success',
            'data' => $properties,
        ]);
    }

    public function getPropertyDetail(string $slug): JsonResponse
    {
        // Bug fix: reviews were loaded with no status filter, so a review
        // Admin had moderated to "hidden" still showed up on the public
        // property page — moderation had no real effect. Only "hidden"
        // reviews are excluded here (same rule already used by
        // Review::recalculatePropertyRating()); "flagged" ones still show,
        // matching how the admin queue treats them as "needs a look", not
        // "already known bad".
        $property = Property::with([
            'city', 'units', 'mitra',
            'reviews' => fn ($q) => $q->where('status', '!=', 'hidden')->with('user')->latest(),
        ])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'status' => 'success',
            'data' => $property,
        ]);
    }

    public function getCities(): JsonResponse
    {
        $cities = City::withCount('properties')->where('is_active', true)->get();

        return response()->json([
            'status' => 'success',
            'data' => $cities,
        ]);
    }

    public function getPromos(): JsonResponse
    {
        $promos = Promo::where('is_active', true)->where('valid_until', '>=', now())->get();

        return response()->json([
            'status' => 'success',
            'data' => $promos,
        ]);
    }

    public function createBooking(Request $request): JsonResponse
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'room_unit_id' => 'required|exists:room_units,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'duration_months' => 'required|integer|min:1|max:24',
            'tenant_name' => 'required|string|max:255',
            'tenant_phone' => 'required|string|max:20',
            'tenant_email' => 'required|email',
            'tenant_nik' => 'required|string|min:16|max:16',
            'payment_method' => 'required|in:qris,bca_va,mandiri_va,bni_va,bri_va,gopay,ovo,shopeepay,alfamart',
            'emergency_name' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            // Bug fix: these were previously accepted by nothing at all — the
            // add-ons and promo code the tenant picks in the UI never reached
            // the backend, so the amount actually billed/verified (grand_total)
            // silently ignored them while the UI kept showing a different
            // (correct) total to the tenant during payment.
            'addons' => 'nullable|array',
            'addons.cleaning_weekly' => 'nullable|boolean',
            'addons.parking_car' => 'nullable|boolean',
            'addons.laundry_partner' => 'nullable|boolean',
            'promo_code' => 'nullable|string|max:30',
        ]);

        $property = Property::findOrFail($request->property_id);
        $unit = $property->units()->findOrFail($request->room_unit_id);

        // Bug fix: the endpoint used to accept bookings for units with zero
        // stock left, letting tenants pay for a room that was already fully
        // booked. Block it here instead.
        if ($unit->available_count < 1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Maaf, unit kamar ini baru saja habis dipesan penyewa lain. Silakan pilih unit lain.',
            ], 422);
        }

        $durationMonths = (int) $request->duration_months;
        $baseRent = $unit->price_monthly * $durationMonths;

        // Recompute add-ons total server-side from fixed price list, in the
        // exact same way the frontend prices them (Rp/month, multiplied by
        // duration) — never trust a client-supplied total.
        $addonsInput = $request->input('addons', []);
        $addonPrices = [
            'cleaning_weekly' => 150000,
            'parking_car' => 250000,
            'laundry_partner' => 200000,
        ];
        $selectedAddons = [];
        $addonsMonthly = 0;
        foreach ($addonPrices as $key => $price) {
            if (!empty($addonsInput[$key])) {
                $selectedAddons[$key] = true;
                $addonsMonthly += $price;
            }
        }
        $addonsTotal = $addonsMonthly * $durationMonths;

        // Recompute the promo discount server-side against the real Promo
        // record (never trust a client-supplied discount amount).
        $discountTotal = 0;
        $appliedPromoCode = null;
        if ($request->filled('promo_code')) {
            $promo = Promo::where('code', strtoupper($request->promo_code))
                ->where('is_active', true)
                ->where('valid_until', '>=', now())
                ->first();

            if ($promo && $promo->used_count < $promo->usage_limit) {
                $subtotalForPromo = $baseRent + $addonsTotal;
                if ($subtotalForPromo >= $promo->min_transaction) {
                    $discountTotal = $promo->discount_type === 'percentage'
                        ? min($promo->max_discount ?? PHP_INT_MAX, (int) round($subtotalForPromo * ($promo->discount_value / 100)))
                        : (int) $promo->discount_value;
                    $appliedPromoCode = $promo->code;
                }
            }
        }

        $grandTotal = $baseRent + $property->deposit_amount + $property->service_fee + $addonsTotal - $discountTotal;

        // Bug fix: guest bookings used to be hardcoded to user_id 4 (the
        // demo tenant account) no matter who actually filled in the form,
        // so every guest's bookings/history got mixed into one account.
        // Now each tenant is matched (or created) by their own email.
        $tenantUser = auth()->user() ?? User::findOrCreateGuest(
            $request->tenant_email,
            $request->tenant_name,
            $request->tenant_phone
        );

        $booking = Booking::create([
            'booking_code' => 'STY-' . strtoupper(Str::random(8)),
            'user_id' => $tenantUser->id,
            'property_id' => $property->id,
            'room_unit_id' => $unit->id,
            'check_in_date' => $request->check_in_date,
            'duration_months' => $durationMonths,
            'status' => 'pending_payment',
            'base_rent_total' => $baseRent,
            'deposit_total' => $property->deposit_amount,
            'service_fee' => $property->service_fee,
            'addons_total' => $addonsTotal,
            'discount_total' => $discountTotal,
            'grand_total' => $grandTotal,
            'addons_json' => array_merge($selectedAddons, $appliedPromoCode ? ['promo_code' => $appliedPromoCode] : []),
            'payment_method' => $request->payment_method,
            'payment_status' => 'unpaid',
            'tenant_name' => $request->tenant_name,
            'tenant_phone' => $request->tenant_phone,
            'tenant_email' => $request->tenant_email,
            'tenant_nik' => $request->tenant_nik,
            'emergency_name' => $request->emergency_name,
            'emergency_phone' => $request->emergency_phone,
        ]);

        if ($appliedPromoCode) {
            Promo::where('code', $appliedPromoCode)->increment('used_count');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Pemesanan hunian berhasil dibuat.',
            'data' => $booking,
        ], 201);
    }

    /**
     * Simulates an automated payment-gateway webhook (QRIS/VA/e-wallet instant
     * confirmation) marking a booking's payment as verified. In a real
     * integration this would instead be called by the payment provider's
     * server-to-server callback after validating a signature.
     */
    public function confirmBookingPayment(Booking $booking): JsonResponse
    {
        if ($booking->payment_status === 'verified') {
            return response()->json([
                'status' => 'success',
                'message' => 'Pembayaran sudah terverifikasi sebelumnya.',
                'data' => $booking,
            ]);
        }

        $booking->markPaymentVerified();

        return response()->json([
            'status' => 'success',
            'message' => 'Pembayaran berhasil dikonfirmasi. Booking Anda kini berstatus LUNAS.',
            'data' => $booking,
        ]);
    }

    /**
     * Bug fix: the storefront's Wishlist (heart icon) used to be
     * localStorage-only, so favorites never reached the real `wishlists`
     * table and were invisible to the tenant/mitra/admin dashboards. This
     * looks up the visitor's real wishlist by email once they're known
     * (localStorage is still used for instant UI feedback before that).
     */
    public function getWishlist(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        $propertyIds = $user
            ? Wishlist::where('user_id', $user->id)->pluck('property_id')
            : collect();

        return response()->json([
            'status' => 'success',
            'data' => $propertyIds,
        ]);
    }

    public function toggleWishlist(Request $request): JsonResponse
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::findOrCreateGuest($request->email, $request->name, $request->phone);

        $existing = Wishlist::where('user_id', $user->id)
            ->where('property_id', $request->property_id)
            ->first();

        if ($existing) {
            $existing->delete();
            $inWishlist = false;
        } else {
            Wishlist::create([
                'user_id' => $user->id,
                'property_id' => $request->property_id,
            ]);
            $inWishlist = true;
        }

        return response()->json([
            'status' => 'success',
            'data' => ['property_id' => (int) $request->property_id, 'in_wishlist' => $inWishlist],
        ]);
    }

    /**
     * Bug fix: the storefront's "Chat dengan Pemilik" modal used to be a
     * fully client-side fake — it echoed back random canned replies and
     * never touched the `messages` table, so the property owner never
     * actually received anything. This wires it to the real Message model
     * (same table the dashboards' "Pesan" pages use).
     */
    public function getMessageThread(Request $request): JsonResponse
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'email' => 'required|email',
        ]);

        $property = Property::findOrFail($request->property_id);
        $user = User::where('email', $request->email)->first();

        // Bug fix: filtering by property_id here caused replies sent from
        // the Mitra/Tenant dashboards (which don't scope by property) to
        // silently disappear from the storefront chat widget. Thread is
        // matched purely by the sender/recipient pair, same as the
        // dashboards, so both surfaces show the exact same conversation.
        $messages = $user
            ? Message::where(function ($q) use ($user, $property) {
                $q->where('sender_id', $user->id)->where('recipient_id', $property->mitra_id);
            })->orWhere(function ($q) use ($user, $property) {
                $q->where('sender_id', $property->mitra_id)->where('recipient_id', $user->id);
            })
            ->oldest()
            ->get()
            : collect();

        return response()->json([
            'status' => 'success',
            'data' => $messages->map(fn ($m) => [
                'id' => $m->id,
                'from_visitor' => $m->sender_id === $user?->id,
                'content' => $m->content,
                'time' => $m->created_at->format('H:i'),
            ])->values(),
        ]);
    }

    public function sendMessage(Request $request): JsonResponse
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'content' => 'required|string|max:1000',
        ]);

        $property = Property::findOrFail($request->property_id);
        $user = User::findOrCreateGuest($request->email, $request->name, $request->phone);

        $message = Message::create([
            'sender_id' => $user->id,
            'recipient_id' => $property->mitra_id,
            'property_id' => $property->id,
            'content' => strip_tags($request->content),
            'is_read' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $message->id,
                'from_visitor' => true,
                'content' => $message->content,
                'time' => $message->created_at->format('H:i'),
            ],
        ], 201);
    }

    /**
     * Bug fix: the "Pasang Iklan" (Pusat Pendaftaran Mitra) wizard used to
     * only show a fake confetti success screen — "Publikasikan Iklan Sewa"
     * never called the backend, so no Property row was ever created and
     * the listing never reached Admin's verification queue. This now
     * creates a real (pending_review) Property + a default RoomUnit, tied
     * to a real (found-or-created) Mitra account, exactly like the
     * existing Mitra Dashboard "Tambah Properti" flow does.
     */
    public function submitListing(Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'property_type' => 'required|in:kost_campur,kost_putri,kost_putra,apartemen,villa,kontrakan',
            'city' => 'required|string|max:100',
            'sub_district' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'room_count' => 'required|integer|min:1',
            'monthly_price' => 'required|numeric|min:100000',
            'electricity_policy' => 'required|in:include,token_mandiri',
            'facilities' => 'nullable|array',
            'owner_name' => 'required|string|max:255',
            'owner_whatsapp' => 'required|string|max:20',
            'owner_email' => 'required|email',
        ]);

        $owner = User::where('email', $request->owner_email)->first();
        if (!$owner) {
            $owner = User::create([
                'name' => $request->owner_name,
                'email' => $request->owner_email,
                'password' => Hash::make(Str::random(32)),
                'role' => 'mitra',
                'status' => 'active',
                'phone' => $request->owner_whatsapp,
            ]);
        } else {
            // A visitor who already has a plain "user" account becomes a
            // Mitra too the moment they list a property; accounts that are
            // already mitra/admin/superadmin keep their existing role.
            if ($owner->role === 'user') {
                $owner->update(['role' => 'mitra']);
            }
            if (!$owner->phone) {
                $owner->update(['phone' => $request->owner_whatsapp]);
            }
        }

        $city = City::where('name', 'like', $request->city)->first();

        $property = Property::create([
            'mitra_id' => $owner->id,
            'city_id' => $city?->id,
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . Str::lower(Str::random(6)),
            'property_type' => $request->property_type,
            'address' => $request->address,
            'sub_district' => $request->sub_district,
            'base_price_monthly' => $request->monthly_price,
            'deposit_amount' => 0,
            'electricity_policy' => $request->electricity_policy,
            'service_fee' => 50000,
            'verified_official' => false,
            'status' => 'pending_review',
            'facilities' => $request->facilities ?? [],
            'rules' => ['Menjaga ketertiban dan kebersihan bersama'],
            // No real file-upload pipeline exists yet for owner-submitted
            // photos (Step 3 "Pilih File Foto" is still a UI-only stub),
            // so new listings start with the same placeholder photos the
            // Mitra Dashboard's own "Tambah Properti" form uses.
            'images' => [
                'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1000&q=80',
                'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=1000&q=80',
            ],
            'description' => "Listing baru diajukan oleh {$request->owner_name} melalui formulir Pasang Iklan di website. Menunggu verifikasi tim Stayease.",
        ]);

        RoomUnit::create([
            'property_id' => $property->id,
            'name' => 'Unit Standar',
            'size_m2' => 16,
            'price_monthly' => $request->monthly_price,
            'available_count' => $request->room_count,
            'total_count' => $request->room_count,
            'photos' => [],
            'features' => $request->facilities ?? [],
        ]);

        AuditLog::log('PROPERTY_LISTING_SUBMITTED_PUBLIC', 'Property', $property->id, [
            'title' => $property->title,
            'owner_email' => $owner->email,
            'source' => 'storefront_owner_wizard',
        ], $owner->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Listing properti berhasil diajukan dan akan direview tim Stayease dalam 1x24 jam.',
            'data' => $property,
        ], 201);
    }

    /**
     * New feature: the storefront property page only ever displayed
     * reviews — there was no way to write one there (only the dashboard
     * could, gated to one specific booking). This checks upfront whether
     * a guest is allowed to review, using the exact rule the page already
     * states ("hanya penyewa yang telah menyelesaikan masa tinggal"): a
     * real, paid booking for this exact property.
     */
    public function checkReviewEligibility(Request $request, Property $property): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['status' => 'success', 'data' => ['eligible' => false, 'reason' => 'no_booking']]);
        }

        if (Review::where('property_id', $property->id)->where('user_id', $user->id)->exists()) {
            return response()->json(['status' => 'success', 'data' => ['eligible' => false, 'reason' => 'already_reviewed']]);
        }

        $qualifyingBooking = Booking::where('property_id', $property->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['paid', 'active_lease', 'completed'])
            ->exists();

        return response()->json([
            'status' => 'success',
            'data' => ['eligible' => $qualifyingBooking, 'reason' => $qualifyingBooking ? null : 'no_booking'],
        ]);
    }

    public function submitReview(Request $request, Property $property): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'cleanliness_rating' => 'required|integer|min:1|max:5',
            'security_rating' => 'required|integer|min:1|max:5',
            'location_rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kami tidak menemukan riwayat sewa untuk email ini. Hanya penyewa yang pernah booking properti ini yang bisa memberi ulasan.',
            ], 422);
        }

        if (Review::where('property_id', $property->id)->where('user_id', $user->id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah pernah memberi ulasan untuk properti ini.',
            ], 422);
        }

        $booking = Booking::where('property_id', $property->id)
            ->where('user_id', $user->id)
            ->whereIn('status', ['paid', 'active_lease', 'completed'])
            ->latest()
            ->first();

        if (!$booking) {
            return response()->json([
                'status' => 'error',
                'message' => 'Ulasan hanya dapat diberikan oleh penyewa yang bookingnya di properti ini sudah dibayar.',
            ], 422);
        }

        $review = Review::create([
            'booking_id' => $booking->id,
            'user_id' => $user->id,
            'property_id' => $property->id,
            'rating' => $request->rating,
            'cleanliness_rating' => $request->cleanliness_rating,
            'security_rating' => $request->security_rating,
            'location_rating' => $request->location_rating,
            'comment' => strip_tags($request->comment),
            'status' => 'published',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ulasan Anda berhasil dipublikasikan. Terima kasih!',
            'data' => $review->load('user'),
        ], 201);
    }
}
