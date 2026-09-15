<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Wishlist;
use App\Models\Message;
use App\Models\Review;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class UserDashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        $activeBookings = Booking::with(['property.city', 'unit'])
            ->where('user_id', $user->id)
            ->whereIn('status', ['active_lease', 'paid', 'pending_payment'])
            ->latest()
            ->get();

        $completedBookingsCount = Booking::where('user_id', $user->id)
            ->where('status', 'completed')
            ->count();

        $wishlists = Wishlist::with(['property.city', 'property.units'])
            ->where('user_id', $user->id)
            ->latest()
            ->take(4)
            ->get();

        $unreadMessagesCount = Message::where('recipient_id', $user->id)
            ->where('is_read', false)
            ->count();

        return view('dashboard.user.index', compact(
            'user',
            'activeBookings',
            'completedBookingsCount',
            'wishlists',
            'unreadMessagesCount'
        ));
    }

    public function bookings()
    {
        $user = auth()->user();
        $bookings = Booking::with(['property.city', 'property.mitra', 'unit', 'review'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('dashboard.user.bookings', compact('user', 'bookings'));
    }

    public function contract(Booking $booking)
    {
        if ($booking->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak.');
        }

        $booking->load(['property.mitra', 'unit']);
        return view('dashboard.user.contract', compact('booking'));
    }

    public function wishlist()
    {
        $user = auth()->user();
        $wishlists = Wishlist::with(['property.city', 'property.units'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(12);

        return view('dashboard.user.wishlist', compact('user', 'wishlists'));
    }

    public function removeWishlist(Wishlist $wishlist)
    {
        if ($wishlist->user_id === auth()->id()) {
            $wishlist->delete();
            return back()->with('success', 'Properti dihapus dari daftar favorit.');
        }
        return back()->with('error', 'Aksi tidak diizinkan.');
    }

    public function messages(Request $request)
    {
        $user = auth()->user();

        // Bug fix: the "recipient" used to be hardcoded to user id 3
        // (mitra@stayease.id) in the view, so every tenant's messages went
        // to the same single account no matter which property they were
        // actually asking about. This instead lists every Mitra the tenant
        // has an existing conversation or booking with, as separate
        // threads, and lets them pick which one to view/reply to.
        $messagePartnerIds = Message::where('sender_id', $user->id)
            ->orWhere('recipient_id', $user->id)
            ->get(['sender_id', 'recipient_id'])
            ->flatMap(fn ($m) => [$m->sender_id, $m->recipient_id])
            ->unique()
            ->reject(fn ($id) => $id === $user->id);

        $bookingPartnerIds = Booking::with('property')
            ->where('user_id', $user->id)
            ->get()
            ->pluck('property.mitra_id')
            ->filter();

        $partnerIds = $messagePartnerIds->merge($bookingPartnerIds)->unique()->values();
        $partners = User::whereIn('id', $partnerIds)->get(['id', 'name']);

        $activePartnerId = (int) ($request->query('with') ?: $partnerIds->first());

        $messages = $activePartnerId
            ? Message::where(function ($q) use ($user, $activePartnerId) {
                $q->where('sender_id', $user->id)->where('recipient_id', $activePartnerId);
            })->orWhere(function ($q) use ($user, $activePartnerId) {
                $q->where('sender_id', $activePartnerId)->where('recipient_id', $user->id);
            })
            ->oldest() // chat should read oldest -> newest (newest at the bottom), not newest first.
            ->get()
            : collect();

        return view('dashboard.user.messages', compact('user', 'messages', 'partners', 'activePartnerId'));
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

        return back()->with('success', 'Pesan berhasil dikirim ke pemilik hunian.');
    }

    public function profile()
    {
        $user = auth()->user();
        return view('dashboard.user.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'ktp_number' => 'nullable|string|min:16|max:16',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'ktp_number' => $request->ktp_number,
        ]);

        AuditLog::log('USER_PROFILE_UPDATED', 'User', $user->id, [
            'name' => $user->name,
            'phone' => $user->phone,
        ]);

        return back()->with('success', 'Data profil penyewa berhasil diperbarui.');
    }

    public function storeReview(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'cleanliness_rating' => 'required|integer|min:1|max:5',
            'security_rating' => 'required|integer|min:1|max:5',
            'location_rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000',
        ]);

        Review::create([
            'booking_id' => $booking->id,
            'user_id' => auth()->id(),
            'property_id' => $booking->property_id,
            'rating' => $request->rating,
            'cleanliness_rating' => $request->cleanliness_rating,
            'security_rating' => $request->security_rating,
            'location_rating' => $request->location_rating,
            'comment' => strip_tags($request->comment),
            'status' => 'published',
        ]);

        return back()->with('success', 'Ulasan Anda berhasil dipublikasikan!');
    }
}
