<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;

Route::prefix('v1')->group(function () {
    Route::get('/properties', [ApiController::class, 'getProperties']);
    Route::get('/properties/{slug}', [ApiController::class, 'getPropertyDetail']);
    Route::get('/cities', [ApiController::class, 'getCities']);
    Route::get('/promos', [ApiController::class, 'getPromos']);
    Route::post('/bookings', [ApiController::class, 'createBooking']);
    Route::post('/bookings/{booking}/confirm-payment', [ApiController::class, 'confirmBookingPayment']);

    // Wishlist (storefront, guest-identified by email — see User::findOrCreateGuest)
    Route::get('/wishlist', [ApiController::class, 'getWishlist']);
    Route::post('/wishlist/toggle', [ApiController::class, 'toggleWishlist']);

    // Direct chat with a property's Mitra
    Route::get('/messages/thread', [ApiController::class, 'getMessageThread']);
    Route::post('/messages', [ApiController::class, 'sendMessage']);

    // "Pasang Iklan" — public property listing submission
    Route::post('/listings', [ApiController::class, 'submitListing']);

    // Reviews submitted directly from the property page (guest-identified
    // by email, gated to visitors with a real paid booking — see
    // ApiController::submitReview).
    Route::get('/properties/{property}/review-eligibility', [ApiController::class, 'checkReviewEligibility']);
    Route::post('/properties/{property}/reviews', [ApiController::class, 'submitReview']);
});
