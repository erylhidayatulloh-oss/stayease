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
        // Revisi: Account Settings — menangani kasus nama akun tidak sesuai
        // KTP. `ktp_name` menyimpan nama persis seperti tertulis di KTP
        // (terpisah dari `name` / nama akun), supaya Admin bisa
        // membandingkan keduanya saat verifikasi. `ktp_rejection_reason`
        // menyimpan alasan saat Admin menolak verifikasi (mis. nama tidak
        // sesuai) agar user tahu apa yang perlu diperbaiki.
        Schema::table('users', function (Blueprint $table) {
            $table->string('ktp_name')->nullable()->after('ktp_number');
            $table->string('ktp_rejection_reason')->nullable()->after('ktp_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ktp_name', 'ktp_rejection_reason']);
        });
    }
};
