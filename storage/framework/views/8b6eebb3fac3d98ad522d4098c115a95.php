<?php $__env->startSection('title', 'Surat Perjanjian Sewa Digital'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-4xl mx-auto space-y-6">
    
    <div class="flex items-center justify-between">
        <a href="<?php echo e(route('user.bookings')); ?>" class="text-xs font-bold text-slate-500 hover:text-slate-900 flex items-center gap-1">
            &larr; Kembali ke Daftar Booking
        </a>

        <button onclick="window.print()" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow transition flex items-center gap-1.5">
            <span>🖨️ Cetak / Simpan PDF</span>
        </button>
    </div>

    <!-- Official Contract Document Card -->
    <div class="bg-white rounded-3xl p-8 sm:p-12 border border-slate-200 shadow-card space-y-6 text-slate-800">
        
        <!-- Header Document -->
        <div class="text-center pb-6 border-b-2 border-slate-900 space-y-1">
            <div class="text-xs font-bold uppercase tracking-widest text-emerald-700">STAYEASE INDONESIA • OFFICIAL DIGITAL CONTRACT</div>
            <h1 class="text-xl sm:text-2xl font-black uppercase">SURAT PERJANJIAN SEWA MENYEWA HUNIAN</h1>
            <p class="text-xs text-slate-500 font-mono">Nomor Registrasi Kontrak: STY/LEG/<?php echo e($booking->booking_code); ?>/2026</p>
        </div>

        <!-- Agreement Terms -->
        <div class="space-y-4 text-xs sm:text-sm leading-relaxed">
            <p>
                Pada hari ini, telah disepakati Perjanjian Sewa Menyewa Hunian yang mengikat secara hukum antara pihak-pihak di bawah ini:
            </p>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-2 text-xs">
                <div>
                    <strong>PIHAK PERTAMA (Pengelola / Pemilik):</strong>
                    <div class="text-slate-600"><?php echo e($booking->property->mitra->name); ?> (Pemilik Sah Properti <?php echo e($booking->property->title); ?>)</div>
                </div>
                <div>
                    <strong>PIHAK KEDUA (Penyewa):</strong>
                    <div class="text-slate-600"><?php echo e($booking->tenant_name); ?> (NIK: <?php echo e($booking->tenant_nik); ?>, Telp: <?php echo e($booking->tenant_phone); ?>)</div>
                </div>
            </div>

            <div class="space-y-3 pt-2">
                <h4 class="font-bold text-xs uppercase text-slate-900">Pasal 1 — Objek Sewa & Masa Tinggal</h4>
                <p>
                    Pihak Pertama menyewakan kepada Pihak Kedua unit kamar <strong><?php echo e($booking->unit->name); ?></strong> yang berlokasi di <strong><?php echo e($booking->property->address); ?>, <?php echo e($booking->property->sub_district); ?>, <?php echo e($booking->property->city->name ?? 'Indonesia'); ?></strong> selama <strong><?php echo e($booking->duration_months); ?> Bulan</strong>, terhitung mulai tanggal <strong><?php echo e($booking->check_in_date->format('d F Y')); ?></strong> sampai dengan <strong><?php echo e($booking->check_in_date->copy()->addMonths($booking->duration_months)->format('d F Y')); ?></strong>.
                </p>

                <h4 class="font-bold text-xs uppercase text-slate-900">Pasal 2 — Biaya Sewa & Uang Jaminan (Deposit)</h4>
                <p>
                    Total biaya sewa yang telah dibayarkan lunas sebesar <strong>Rp <?php echo e(number_format($booking->grand_total, 0, ',', '.')); ?></strong> via metode pembayaran resmi <strong><?php echo e(strtoupper($booking->payment_method)); ?></strong>. Uang jaminan (deposit) sebesar <strong>Rp <?php echo e(number_format($booking->deposit_total, 0, ',', '.')); ?></strong> akan dikembalikan penuh kepada Pihak Kedua setelah masa sewa berakhir dan unit dikembalikan dalam keadaan baik.
                </p>

                <h4 class="font-bold text-xs uppercase text-slate-900">Pasal 3 — Tata Tertib & Hak Kewajiban</h4>
                <p>
                    Penyewa wajib mematuhi seluruh tata tertib lingkungan properti, menjaga fasilitas yang disediakan, tidak melakukan perbuatan melanggar hukum, dan menjaga ketertiban umum.
                </p>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="pt-8 border-t border-slate-200 grid grid-cols-2 gap-8 text-center text-xs">
            <div class="space-y-4">
                <span class="text-slate-500 block">Pihak Pertama (Pengelola),</span>
                <div class="h-16 flex items-center justify-center font-bold text-emerald-800 text-sm font-mono border border-dashed border-emerald-300 rounded-xl bg-emerald-50/50">
                    [E-VERIFIED BY STAYEASE]
                </div>
                <strong><?php echo e($booking->property->mitra->name); ?></strong>
            </div>

            <div class="space-y-4">
                <span class="text-slate-500 block">Pihak Kedua (Penyewa),</span>
                <div class="h-16 flex items-center justify-center font-bold text-emerald-800 text-sm font-mono border border-dashed border-emerald-300 rounded-xl bg-emerald-50/50">
                    [DIGITALLY SIGNED & E-KYC]
                </div>
                <strong><?php echo e($booking->tenant_name); ?></strong>
            </div>
        </div>

    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookair/SevenInc/StayEase-Revisi/resources/views/dashboard/user/contract.blade.php ENDPATH**/ ?>