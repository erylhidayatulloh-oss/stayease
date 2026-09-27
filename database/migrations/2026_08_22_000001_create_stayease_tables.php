<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Cities
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('province');
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('avg_kost_price')->default(1500000);
            $table->unsignedBigInteger('avg_apt_price')->default(4500000);
            $table->boolean('is_active')->default(true)->index();
            $table->json('popular_districts')->nullable();
            $table->json('top_universities')->nullable();
            $table->json('faqs')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });

        // 2. Properties
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mitra_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('city_id')->nullable()->constrained('cities')->onDelete('set null');
            $table->string('title');
            $table->string('slug')->unique();
            $table->enum('property_type', ['kost_campur', 'kost_putri', 'kost_putra', 'apartemen', 'villa', 'kontrakan'])->default('kost_campur')->index();
            $table->string('gender_restriction')->nullable();
            $table->text('address');
            $table->string('sub_district')->index();
            $table->decimal('coords_lat', 10, 7)->nullable();
            $table->decimal('coords_lng', 10, 7)->nullable();
            $table->unsignedBigInteger('base_price_monthly');
            $table->unsignedBigInteger('base_price_yearly')->nullable();
            $table->unsignedBigInteger('base_price_daily')->nullable();
            $table->unsignedTinyInteger('discount_percent')->default(0);
            $table->unsignedBigInteger('deposit_amount')->default(0);
            $table->enum('electricity_policy', ['include', 'token_mandiri'])->default('token_mandiri');
            $table->unsignedBigInteger('service_fee')->default(50000);
            $table->boolean('verified_official')->default(false)->index();
            $table->boolean('has_virtual_tour')->default(false);
            $table->enum('status', ['draft', 'pending_review', 'active', 'rejected'])->default('pending_review')->index();
            $table->text('rejection_reason')->nullable();
            $table->json('images')->nullable();
            $table->text('virtual_tour_url')->nullable();
            $table->json('facilities')->nullable();
            $table->json('rules')->nullable();
            $table->json('transit_points')->nullable();
            $table->text('description')->nullable();
            $table->decimal('rating_avg', 3, 2)->default(5.00);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->timestamps();
        });

        // 3. Room Units
        Schema::create('room_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('name');
            $table->unsignedSmallInteger('size_m2')->default(16);
            $table->string('bed_type')->default('Single Bed (120x200)');
            $table->unsignedBigInteger('price_monthly');
            $table->unsignedBigInteger('price_yearly')->nullable();
            $table->unsignedBigInteger('price_daily')->nullable();
            $table->unsignedBigInteger('promo_price_monthly')->nullable();
            $table->boolean('is_promo')->default(false);
            $table->unsignedSmallInteger('available_count')->default(1);
            $table->unsignedSmallInteger('total_count')->default(1);
            $table->json('photos')->nullable();
            $table->json('features')->nullable();
            $table->timestamps();
        });

        // 4. Bookings
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->foreignId('room_unit_id')->constrained('room_units')->onDelete('cascade');
            $table->date('check_in_date');
            $table->unsignedSmallInteger('duration_months')->default(1);
            $table->enum('status', ['pending_payment', 'paid', 'active_lease', 'completed', 'cancelled', 'rejected'])->default('pending_payment')->index();
            $table->unsignedBigInteger('base_rent_total');
            $table->unsignedBigInteger('deposit_total')->default(0);
            $table->unsignedBigInteger('service_fee')->default(50000);
            $table->unsignedBigInteger('addons_total')->default(0);
            $table->unsignedBigInteger('discount_total')->default(0);
            $table->unsignedBigInteger('grand_total');
            $table->json('addons_json')->nullable();
            $table->enum('payment_method', ['qris', 'bca_va', 'mandiri_va', 'bni_va', 'bri_va', 'gopay', 'ovo', 'shopeepay', 'alfamart'])->default('qris');
            $table->enum('payment_status', ['unpaid', 'verified', 'refunded', 'disputed'])->default('unpaid')->index();
            $table->string('payment_ref')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('contract_signed_at')->nullable();
            $table->text('signature_data')->nullable();
            $table->string('tenant_name');
            $table->string('tenant_phone');
            $table->string('tenant_email');
            $table->string('tenant_nik');
            $table->string('emergency_name')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->timestamps();
        });

        // 5. Promos
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('title');
            $table->enum('discount_type', ['percentage', 'fixed'])->default('percentage');
            $table->unsignedBigInteger('discount_value');
            $table->unsignedBigInteger('max_discount')->nullable();
            $table->unsignedBigInteger('min_transaction')->default(0);
            $table->string('badge')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('usage_limit')->default(1000);
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('is_active')->default(true)->index();
            $table->date('valid_until');
            $table->timestamps();
        });

        // 6. Reviews
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->nullable()->constrained('bookings')->onDelete('set null');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->unsignedTinyInteger('rating')->default(5);
            $table->unsignedTinyInteger('cleanliness_rating')->default(5);
            $table->unsignedTinyInteger('security_rating')->default(5);
            $table->unsignedTinyInteger('location_rating')->default(5);
            $table->text('comment');
            $table->text('owner_reply')->nullable();
            $table->timestamp('owner_replied_at')->nullable();
            $table->enum('status', ['published', 'flagged', 'hidden'])->default('published')->index();
            $table->timestamps();
        });

        // 7. Payouts (Mitra withdrawal to Indonesian Banks)
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mitra_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('amount');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_holder');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
            $table->text('notes')->nullable();
            $table->string('proof_file')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        // 8. Audit Logs (High Security Event Tracking)
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('action')->index();
            $table->string('entity_type')->index();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('created_at')->useCurrent()->index();
        });

        // 9. Direct Messages
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('property_id')->nullable()->constrained('properties')->onDelete('set null');
            $table->text('content');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        // 10. Wishlists
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['user_id', 'property_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishlists');
        Schema::dropIfExists('messages');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('payouts');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('promos');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('room_units');
        Schema::dropIfExists('properties');
        Schema::dropIfExists('cities');
    }
};
