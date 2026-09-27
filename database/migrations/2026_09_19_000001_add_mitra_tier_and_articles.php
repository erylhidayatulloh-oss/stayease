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
        // Revisi: Mitra Biasa vs Mitra Pro. Mitra Pro is a service tier where
        // Admin uploads/manages the property listing and handles payment
        // verification on the mitra's behalf; Mitra Pro still sees their own
        // dashboard, properties, and financial reports read-only.
        Schema::table('users', function (Blueprint $table) {
            $table->enum('mitra_tier', ['reguler', 'pro'])->default('reguler')->after('role');
        });

        // Revisi: Halaman Artikel — content pages managed by Admin/Superadmin
        // and published publicly on the storefront.
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('cover_image')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->enum('status', ['draft', 'published'])->default('draft')->index();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('mitra_tier');
        });
    }
};
