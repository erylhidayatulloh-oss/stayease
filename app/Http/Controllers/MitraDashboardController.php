<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\RoomUnit;
use App\Models\Booking;
use App\Models\City;
use App\Models\Payout;
use App\Models\Review;
use App\Models\Message;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class MitraDashboardController extends Controller
{
    public function index()
    {
        $mitra = auth()->user();

        $properties = Property::with(['city', 'units', 'bookings'])
            ->where('mitra_id', $mitra->id)
            ->get();

        $totalProperties = $properties->count();
        
        $totalRooms = $properties->flatMap->units->sum('total_count');
        $availableRooms = $properties->flatMap->units->sum('available_count');
        $occupiedRooms = max(0, $totalRooms - $availableRooms);
        $occupancyRate = $totalRooms > 0 ? round(($occupiedRooms / $totalRooms) * 100) : 0;

        $bookings = Booking::whereIn('property_id', $properties->pluck('id'))->get();
        $activeTenantsCount = $bookings->where('status', 'active_lease')->count();
        $totalGrossRevenue = $bookings->whereIn('status', ['paid', 'active_lease', 'completed'])->sum('grand_total');

        $payouts = Payout::where('mitra_id', $mitra->id)->latest()->get();
        $totalDisbursed = $payouts->where('status', 'approved')->sum('amount');
        $feePercent = $mitra->platformFeePercent();
        $availableBalance = max(0, ($totalGrossRevenue * (1 - $feePercent)) - $totalDisbursed);

        $recentBookings = Booking::with(['property', 'unit'])
            ->whereIn('property_id', $properties->pluck('id'))
            ->latest()
            ->take(5)
            ->get();

        $isMitraPro = $mitra->isMitraPro();

        return view('dashboard.mitra.index', compact(
            'mitra',
            'totalProperties',
            'totalRooms',
            'occupiedRooms',
            'occupancyRate',
            'activeTenantsCount',
            'totalGrossRevenue',
            'availableBalance',
            'recentBookings',
            'isMitraPro'
        ));
    }

    public function properties()
    {
        $mitra = auth()->user();
        $properties = Property::with(['city', 'units', 'reviews'])
            ->where('mitra_id', $mitra->id)
            ->latest()
            ->paginate(10);

        $isMitraPro = $mitra->isMitraPro();

        return view('dashboard.mitra.properties', compact('mitra', 'properties', 'isMitraPro'));
    }

    public function createProperty()
    {
        // Mitra Pro: listings are uploaded by Admin on their behalf.
        if (auth()->user()->isMitraPro()) {
            return redirect()->route('mitra.properties')
                ->with('error', 'Akun Mitra Pro tidak menambah listing sendiri — properti Anda diunggah & dikelola langsung oleh tim Admin Stayease.');
        }

        $cities = City::where('is_active', true)->get();
        return view('dashboard.mitra.create_property', compact('cities'));
    }

    public function storeProperty(Request $request)
    {
        // Defense in depth: even if the form is reached directly, Mitra Pro
        // accounts can't self-upload properties (Admin does it for them).
        if (auth()->user()->isMitraPro()) {
            return redirect()->route('mitra.properties')
                ->with('error', 'Akun Mitra Pro tidak menambah listing sendiri — properti Anda diunggah & dikelola langsung oleh tim Admin Stayease.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'city_id' => 'required|exists:cities,id',
            'sub_district' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'property_type' => 'required|in:kost_campur,kost_putri,kost_putra,apartemen,villa,kontrakan',
            'gender_restriction' => 'nullable|string',
            'base_price_monthly' => 'required|numeric|min:100000',
            'deposit_amount' => 'required|numeric|min:0',
            'electricity_policy' => 'required|in:include,token_mandiri',
            'description' => 'required|string|min:20',
            'unit_name' => 'required|string|max:100',
            'unit_size' => 'required|numeric|min:6',
            'unit_total' => 'required|integer|min:1',
        ]);

        $slug = Str::slug($request->title) . '-' . Str::lower(Str::random(6));

        $property = Property::create([
            'mitra_id' => auth()->id(),
            'city_id' => $request->city_id,
            'title' => $request->title,
            'slug' => $slug,
            'property_type' => $request->property_type,
            'gender_restriction' => $request->gender_restriction ?? 'Campur (Pria/Wanita)',
            'address' => $request->address,
            'sub_district' => $request->sub_district,
            'base_price_monthly' => $request->base_price_monthly,
            'deposit_amount' => $request->deposit_amount,
            'electricity_policy' => $request->electricity_policy,
            'service_fee' => 50000,
            'verified_official' => false,
            'has_virtual_tour' => !empty($request->virtual_tour_url),
            'virtual_tour_url' => $request->virtual_tour_url,
            'status' => 'pending_review', // Queued for admin verification
            'facilities' => $request->facilities ?? ['AC', 'Wi-Fi 100Mbps', 'Kamar Mandi Dalam'],
            'rules' => ['Menjaga ketertiban dan kebersihan bersama'],
            'images' => [
                'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1000&q=80',
                'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=1000&q=80'
            ],
            'description' => $request->description,
        ]);

        RoomUnit::create([
            'property_id' => $property->id,
            'name' => $request->unit_name,
            'size_m2' => $request->unit_size,
            'bed_type' => 'Queen Bed (160x200)',
            'price_monthly' => $request->base_price_monthly,
            'available_count' => $request->unit_total,
            'total_count' => $request->unit_total,
            'photos' => ['https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80'],
            'features' => ['AC', 'Kamar Mandi Dalam']
        ]);

        AuditLog::log('PROPERTY_CREATED_BY_MITRA', 'Property', $property->id, [
            'title' => $property->title,
            'status' => 'pending_review',
        ]);

        return redirect()->route('mitra.properties')->with('success', 'Listing properti berhasil didaftarkan! Status saat ini: Menunggu Verifikasi Admin.');
    }

    public function destroyProperty(Property $property)
    {
        if ($property->mitra_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        // Mitra Pro properties are Admin-managed; only Admin/Superadmin may remove them.
        if (auth()->user()->isMitraPro() && !auth()->user()->isAdmin()) {
            return back()->with('error', 'Properti Mitra Pro hanya bisa dihapus oleh tim Admin Stayease.');
        }

        $property->delete();
        AuditLog::log('PROPERTY_DELETED_BY_MITRA', 'Property', $property->id, ['title' => $property->title]);

        return back()->with('success', 'Properti berhasil dihapus.');
    }

    public function bookings()
    {
        $mitra = auth()->user();
        $propertyIds = Property::where('mitra_id', $mitra->id)->pluck('id');

        $bookings = Booking::with(['property', 'unit', 'user'])
            ->whereIn('property_id', $propertyIds)
            ->latest()
            ->paginate(12);

        return view('dashboard.mitra.bookings', compact('mitra', 'bookings'));
    }

    public function updateBookingStatus(Request $request, Booking $booking)
    {
        if ($booking->property->mitra_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:active_lease,completed,rejected,cancelled',
        ]);

        if (in_array($request->status, ['active_lease', 'completed']) && $booking->payment_status !== 'verified') {
            return back()->with('error', "Booking {$booking->booking_code} belum bisa diaktifkan karena pembayaran belum diverifikasi oleh Admin.");
        }

        $oldStatus = $booking->status;
        $booking->update([
            'status' => $request->status,
        ]);

        AuditLog::log('BOOKING_STATUS_UPDATED_BY_MITRA', 'Booking', $booking->id, [
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'booking_code' => $booking->booking_code,
        ]);

        return back()->with('success', "Status booking {$booking->booking_code} berhasil diubah menjadi: " . strtoupper($request->status));
    }

    public function finance()
    {
        $mitra = auth()->user();
        $properties = Property::where('mitra_id', $mitra->id)->get();
        $propertyIds = $properties->pluck('id');

        $bookings = Booking::whereIn('property_id', $propertyIds)->whereIn('status', ['paid', 'active_lease', 'completed'])->get();
        $totalGrossRevenue = $bookings->sum('grand_total');
        $feePercent = $mitra->platformFeePercent();
        $platformFeeTotal = $totalGrossRevenue * $feePercent;
        $netEarnings = $totalGrossRevenue - $platformFeeTotal;

        $payouts = Payout::where('mitra_id', $mitra->id)->latest()->get();
        $totalDisbursed = $payouts->where('status', 'approved')->sum('amount');
        $availableBalance = max(0, $netEarnings - $totalDisbursed);

        return view('dashboard.mitra.finance', compact(
            'mitra',
            'feePercent',
            'totalGrossRevenue',
            'platformFeeTotal',
            'netEarnings',
            'totalDisbursed',
            'availableBalance',
            'payouts'
        ));
    }

    public function requestPayout(Request $request)
    {
        $mitra = auth()->user();
        
        $request->validate([
            'amount' => 'required|numeric|min:100000',
            'bank_name' => 'required|string|max:50',
            'account_number' => 'required|string|max:30',
            'account_holder' => 'required|string|max:100',
        ]);

        $payout = Payout::create([
            'mitra_id' => $mitra->id,
            'amount' => $request->amount,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'account_holder' => $request->account_holder,
            'status' => 'pending',
            'notes' => 'Permintaan pencairan dana sewa via Mitra Dashboard.',
        ]);

        AuditLog::log('PAYOUT_REQUESTED', 'Payout', $payout->id, [
            'amount' => $request->amount,
            'bank' => $request->bank_name,
            'account' => $request->account_number,
        ]);

        return back()->with('success', 'Permintaan pencairan dana sebesar Rp ' . number_format($request->amount, 0, ',', '.') . ' berhasil diajukan ke tim Keuangan.');
    }

    public function reviews()
    {
        $mitra = auth()->user();
        $propertyIds = Property::where('mitra_id', $mitra->id)->pluck('id');

        $reviews = Review::with(['property', 'user', 'booking'])
            ->whereIn('property_id', $propertyIds)
            ->latest()
            ->paginate(10);

        return view('dashboard.mitra.reviews', compact('mitra', 'reviews'));
    }

    public function replyReview(Request $request, Review $review)
    {
        if ($review->property->mitra_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'reply' => 'required|string|max:500',
        ]);

        $review->update([
            'owner_reply' => strip_tags($request->reply),
            'owner_replied_at' => now(),
        ]);

        return back()->with('success', 'Tanggapan ulasan berhasil disimpan.');
    }

    /**
     * New feature: Mitra previously had no inbox at all — the "Pesan"
     * feature only existed on the tenant dashboard, so messages tenants
     * sent (from the storefront chat or their own dashboard) had nowhere
     * to be read or replied to on the property-owner side. This mirrors
     * the tenant-side "Pesan" page, grouped into one thread per tenant.
     */
    public function messages(Request $request)
    {
        $mitra = auth()->user();

        $partnerIds = Message::where('sender_id', $mitra->id)
            ->orWhere('recipient_id', $mitra->id)
            ->get(['sender_id', 'recipient_id'])
            ->flatMap(fn ($m) => [$m->sender_id, $m->recipient_id])
            ->unique()
            ->reject(fn ($id) => $id === $mitra->id)
            ->values();

        $partners = User::whereIn('id', $partnerIds)->get(['id', 'name']);

        $activePartnerId = (int) ($request->query('with') ?: $partnerIds->first());

        $messages = $activePartnerId
            ? Message::with('property')
                ->where(function ($q) use ($mitra, $activePartnerId) {
                    $q->where('sender_id', $mitra->id)->where('recipient_id', $activePartnerId);
                })->orWhere(function ($q) use ($mitra, $activePartnerId) {
                    $q->where('sender_id', $activePartnerId)->where('recipient_id', $mitra->id);
                })
                ->oldest()
                ->get()
            : collect();

        if ($activePartnerId) {
            Message::where('sender_id', $activePartnerId)
                ->where('recipient_id', $mitra->id)
                ->update(['is_read' => true]);
        }

        $unreadCounts = Message::where('recipient_id', $mitra->id)
            ->where('is_read', false)
            ->get()
            ->countBy('sender_id');

        return view('dashboard.mitra.messages', compact('mitra', 'partners', 'activePartnerId', 'messages', 'unreadCounts'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'content' => 'required|string|max:1000',
            'property_id' => 'nullable|exists:properties,id',
        ]);

        Message::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $request->recipient_id,
            'property_id' => $request->property_id,
            'content' => strip_tags($request->content),
            'is_read' => false,
        ]);

        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    /**
     * Revisi: Ubah detail akun & verifikasi KTP (upload foto) untuk Mitra —
     * sama seperti fitur profil di dashboard User. Foto & nomor KTP yang
     * diunggah masuk ke database dan muncul di panel Superadmin untuk
     * ditinjau/diverifikasi.
     */
    public function profile()
    {
        $mitra = auth()->user();
        return view('dashboard.mitra.profile', compact('mitra'));
    }

    public function updateProfile(Request $request)
    {
        $mitra = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $mitra->id,
            'ktp_number' => 'nullable|string|min:16|max:16',
            'ktp_name' => 'nullable|string|max:255',
            'ktp_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|string|min:6|confirmed',
            'bank_name' => 'nullable|string|max:100',
            'bank_account_number' => 'nullable|string|max:50',
            'bank_account_holder' => 'nullable|string|max:255',
        ], [
            'email.unique' => 'Email ini sudah dipakai akun lain.',
            'ktp_photo.image' => 'File foto KTP harus berupa gambar (JPG/PNG).',
            'ktp_photo.max' => 'Ukuran foto KTP maksimal 4MB.',
            'avatar.image' => 'Foto profil harus berupa gambar (JPG/PNG).',
            'avatar.max' => 'Ukuran foto profil maksimal 2MB.',
        ]);

        $data = [
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'bank_name' => $request->bank_name,
            'bank_account_number' => $request->bank_account_number,
            'bank_account_holder' => $request->bank_account_holder,
        ];

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        // Any change to the KTP-relevant fields (number, legal name, photo)
        // means the previous verification/rejection no longer applies to
        // the new data — Admin needs to review it fresh.
        $ktpDataChanged = false;

        if ($request->filled('ktp_number') && $request->ktp_number !== $mitra->ktp_number) {
            $data['ktp_number'] = $request->ktp_number;
            $ktpDataChanged = true;
        } elseif ($request->filled('ktp_number')) {
            $data['ktp_number'] = $request->ktp_number;
        }

        if ($request->filled('ktp_name') && $request->ktp_name !== $mitra->ktp_name) {
            $data['ktp_name'] = $request->ktp_name;
            $ktpDataChanged = true;
        } elseif ($request->filled('ktp_name')) {
            $data['ktp_name'] = $request->ktp_name;
        }

        if ($request->hasFile('ktp_photo')) {
            $data['ktp_photo'] = $request->file('ktp_photo')->store('ktp-photos', 'public');
            $ktpDataChanged = true;
        }

        if ($ktpDataChanged) {
            $data['ktp_verified_at'] = null;
            $data['ktp_rejection_reason'] = null;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $mitra->update($data);

        AuditLog::log('MITRA_PROFILE_UPDATED', 'User', $mitra->id, [
            'name' => $mitra->name,
            'ktp_photo_updated' => $request->hasFile('ktp_photo'),
        ]);

        return back()->with('success', 'Data profil berhasil diperbarui.' . ($ktpDataChanged ? ' Data KTP baru Anda akan ditinjau ulang oleh Admin.' : ''));
    }
}
