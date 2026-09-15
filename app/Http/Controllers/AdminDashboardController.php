<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Booking;
use App\Models\City;
use App\Models\Promo;
use App\Models\Review;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $pendingPropertiesCount = Property::where('status', 'pending_review')->count();
        $activePropertiesCount = Property::where('status', 'active')->count();
        $totalBookingsCount = Booking::count();
        $totalGmv = Booking::whereIn('status', ['paid', 'active_lease', 'completed'])->sum('grand_total');

        $pendingQueue = Property::with(['mitra', 'city', 'units'])
            ->where('status', 'pending_review')
            ->latest()
            ->take(5)
            ->get();

        $recentTransactions = Booking::with(['property', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.admin.index', compact(
            'pendingPropertiesCount',
            'activePropertiesCount',
            'totalBookingsCount',
            'totalGmv',
            'pendingQueue',
            'recentTransactions'
        ));
    }

    public function verificationQueue()
    {
        $properties = Property::with(['mitra', 'city', 'units'])
            ->where('status', 'pending_review')
            ->latest()
            ->paginate(10);

        return view('dashboard.admin.verification_queue', compact('properties'));
    }

    public function approveProperty(Request $request, Property $property)
    {
        $property->update([
            'status' => 'active',
            'verified_official' => $request->has('verified_official') ? true : true,
            'rejection_reason' => null,
        ]);

        AuditLog::log('PROPERTY_VERIFICATION_APPROVED', 'Property', $property->id, [
            'property_title' => $property->title,
            'verified_official' => $property->verified_official,
            'admin_id' => auth()->id(),
        ]);

        return back()->with('success', "Properti \"{$property->title}\" BERHASIL diverifikasi dan tayang dengan lencana Official Stayease!");
    }

    public function rejectProperty(Request $request, Property $property)
    {
        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        $property->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        AuditLog::log('PROPERTY_VERIFICATION_REJECTED', 'Property', $property->id, [
            'property_title' => $property->title,
            'reason' => $request->reason,
            'admin_id' => auth()->id(),
        ]);

        return back()->with('success', "Properti \"{$property->title}\" ditolak dengan catatan evaluasi.");
    }

    public function bookings()
    {
        $bookings = Booking::with(['property', 'user', 'unit'])
            ->latest()
            ->paginate(15);

        return view('dashboard.admin.bookings', compact('bookings'));
    }

    public function updatePaymentStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_status' => 'required|in:unpaid,verified,refunded,disputed',
        ]);

        $newStatus = $booking->status;
        if ($request->payment_status === 'refunded') {
            // A refund means the booking itself is effectively cancelled.
            $newStatus = 'cancelled';
        }

        if ($request->payment_status === 'verified') {
            $booking->markPaymentVerified();
        } else {
            $booking->update([
                'payment_status' => $request->payment_status,
                'status' => $newStatus,
            ]);
        }

        AuditLog::log('BOOKING_PAYMENT_STATUS_UPDATED', 'Booking', $booking->id, [
            'payment_status' => $request->payment_status,
            'booking_code' => $booking->booking_code,
        ]);

        return back()->with('success', "Status pembayaran {$booking->booking_code} diubah menjadi " . strtoupper($request->payment_status));
    }

    public function cities()
    {
        $cities = City::withCount('properties')->latest()->paginate(10);
        return view('dashboard.admin.cities', compact('cities'));
    }

    public function promos()
    {
        $promos = Promo::latest()->paginate(10);
        return view('dashboard.admin.promos', compact('promos'));
    }

    public function storePromo(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:promos,code|max:30',
            'title' => 'required|string|max:100',
            'discount_type' => 'required|in:percentage,fixed',
            'discount_value' => 'required|numeric|min:1',
            'valid_until' => 'required|date|after:today',
        ]);

        Promo::create([
            'code' => strtoupper($request->code),
            'title' => $request->title,
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'max_discount' => $request->max_discount,
            'min_transaction' => $request->min_transaction ?? 0,
            'badge' => $request->badge ?? 'PROMO',
            'description' => $request->description,
            'valid_until' => $request->valid_until,
            'is_active' => true,
        ]);

        return back()->with('success', "Voucher promo {$request->code} berhasil dibuat.");
    }

    public function reviews()
    {
        $reviews = Review::with(['property', 'user'])->latest()->paginate(15);
        return view('dashboard.admin.reviews', compact('reviews'));
    }

    public function toggleReviewStatus(Review $review)
    {
        $newStatus = $review->status === 'published' ? 'hidden' : 'published';
        $review->update(['status' => $newStatus]);

        return back()->with('success', "Status ulasan diubah menjadi: {$newStatus}");
    }

    /**
     * New feature: only Mitra could reply to reviews before (the reply
     * route lived under `dashboard/mitra/*`, which Admin's role can't
     * reach even though the underlying model already supported it).
     * Admin can reply to any review platform-wide, not just ones on
     * properties they personally own.
     */
    public function replyReview(Request $request, Review $review)
    {
        $request->validate([
            'reply' => 'required|string|max:500',
        ]);

        $review->update([
            'owner_reply' => strip_tags($request->reply),
            'owner_replied_at' => now(),
        ]);

        return back()->with('success', 'Tanggapan Admin berhasil disimpan.');
    }
}
