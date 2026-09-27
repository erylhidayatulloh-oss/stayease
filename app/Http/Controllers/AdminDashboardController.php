<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\RoomUnit;
use App\Models\Booking;
use App\Models\City;
use App\Models\Promo;
use App\Models\Review;
use App\Models\Article;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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

    /**
     * Revisi: Mitra Pro — Admin uploads & manages the property listing on
     * behalf of Mitra Pro accounts. Unlike the self-service Mitra flow,
     * these listings go live immediately (Admin verifies at creation time)
     * instead of sitting in the pending-review queue.
     */
    public function mitraProProperties()
    {
        $properties = Property::with(['city', 'units', 'mitra'])
            ->whereHas('mitra', function ($q) {
                $q->where('role', 'mitra')->where('mitra_tier', 'pro');
            })
            ->latest()
            ->paginate(10);

        return view('dashboard.admin.mitra_pro_properties', compact('properties'));
    }

    public function mitraProCreateProperty()
    {
        $cities = City::where('is_active', true)->get();
        $proMitras = User::where('role', 'mitra')->where('mitra_tier', 'pro')->orderBy('name')->get();

        return view('dashboard.admin.mitra_pro_create_property', compact('cities', 'proMitras'));
    }

    public function mitraProStoreProperty(Request $request)
    {
        $request->validate([
            'mitra_id' => 'required|exists:users,id',
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

        $mitra = User::where('id', $request->mitra_id)
            ->where('role', 'mitra')
            ->where('mitra_tier', 'pro')
            ->firstOrFail();

        $slug = Str::slug($request->title) . '-' . Str::lower(Str::random(6));

        $property = Property::create([
            'mitra_id' => $mitra->id,
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
            // Admin uploads & verifies Mitra Pro listings personally, so
            // these go straight to active + Official — no separate
            // verification-queue step like self-service Mitra listings.
            'verified_official' => true,
            'has_virtual_tour' => !empty($request->virtual_tour_url),
            'virtual_tour_url' => $request->virtual_tour_url,
            'status' => 'active',
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
            'features' => ['AC', 'Kamar Mandi Dalam'],
        ]);

        AuditLog::log('PROPERTY_CREATED_BY_ADMIN_FOR_MITRA_PRO', 'Property', $property->id, [
            'title' => $property->title,
            'mitra_id' => $mitra->id,
            'mitra_email' => $mitra->email,
        ]);

        return redirect()->route('admin.mitra_pro.properties')
            ->with('success', "Properti \"{$property->title}\" berhasil diunggah & langsung tayang untuk Mitra Pro {$mitra->name}.");
    }

    public function mitraProDestroyProperty(Property $property)
    {
        if (!$property->mitra || !$property->mitra->isMitraPro()) {
            abort(404);
        }

        $property->delete();

        AuditLog::log('PROPERTY_DELETED_BY_ADMIN_FOR_MITRA_PRO', 'Property', $property->id, [
            'title' => $property->title,
        ]);

        return back()->with('success', 'Properti Mitra Pro berhasil dihapus.');
    }

    /**
     * Revisi: Halaman Artikel — Admin/Superadmin-only article management.
     */
    public function articles()
    {
        $articles = Article::with('author')->latest()->paginate(10);
        return view('dashboard.admin.articles', compact('articles'));
    }

    public function createArticle()
    {
        return view('dashboard.admin.article_form', ['article' => null]);
    }

    public function storeArticle(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'nullable|url|max:1000',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string|min:20',
            'status' => 'required|in:draft,published',
        ]);

        $slug = Str::slug($data['title']) . '-' . Str::lower(Str::random(6));

        $article = Article::create([
            'author_id' => auth()->id(),
            'title' => $data['title'],
            'slug' => $slug,
            'cover_image' => $data['cover_image'] ?? null,
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'],
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published' ? now() : null,
        ]);

        AuditLog::log('ARTICLE_CREATED', 'Article', $article->id, [
            'title' => $article->title,
            'status' => $article->status,
        ]);

        return redirect()->route('admin.articles')->with('success', "Artikel \"{$article->title}\" berhasil disimpan.");
    }

    public function editArticle(Article $article)
    {
        return view('dashboard.admin.article_form', compact('article'));
    }

    public function updateArticle(Request $request, Article $article)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'nullable|url|max:1000',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string|min:20',
            'status' => 'required|in:draft,published',
        ]);

        $wasPublished = $article->status === 'published';

        $article->update([
            'title' => $data['title'],
            'cover_image' => $data['cover_image'] ?? null,
            'excerpt' => $data['excerpt'] ?? null,
            'content' => $data['content'],
            'status' => $data['status'],
            'published_at' => $data['status'] === 'published'
                ? ($wasPublished ? $article->published_at : now())
                : null,
        ]);

        AuditLog::log('ARTICLE_UPDATED', 'Article', $article->id, [
            'title' => $article->title,
            'status' => $article->status,
        ]);

        return redirect()->route('admin.articles')->with('success', "Artikel \"{$article->title}\" berhasil diperbarui.");
    }

    public function destroyArticle(Article $article)
    {
        $article->delete();

        AuditLog::log('ARTICLE_DELETED', 'Article', $article->id, ['title' => $article->title]);

        return back()->with('success', 'Artikel berhasil dihapus.');
    }
}
