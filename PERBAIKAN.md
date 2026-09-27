# Ringkasan Perbaikan & Integrasi StayEase

Dokumen ini menjelaskan apa saja yang diperbaiki, bagaimana frontend & backend sekarang jadi **satu sistem utuh**, dan cara menjalankannya di komputer kamu.

## Sekarang ini SATU sistem
Sebelumnya project ini punya 2 bagian yang berdiri sendiri-sendiri (backend Laravel dengan data asli, dan frontend React dengan data contoh/mock, tidak saling kenal). **Sekarang keduanya sudah disatukan:**

- **`/`** → Storefront React (halaman cari & booking untuk customer) — sekarang mengambil properti, kota, dan promo **langsung dari database Laravel** lewat API, bukan data contoh lagi.
- **`/dashboard`** → Area akun (otomatis diarahkan ke dashboard sesuai role aktif: Penyewa/Mitra/Admin/Superadmin).
- Booking yang dibuat customer di storefront React **benar-benar tersimpan di database** dan langsung muncul di dashboard Admin (untuk verifikasi pembayaran) dan Mitra (untuk approve check-in) — sudah saya uji end-to-end dan berhasil.
- Satu aplikasi, satu port, satu `php artisan serve` — tidak perlu menjalankan 2 server terpisah untuk mode production.

## Bagaimana integrasinya bekerja
1. Frontend React di-build (`npm run build`) menjadi file statis di `public/storefront/`.
2. Laravel menyajikan file itu di route `/`.
3. React memanggil API asli (`/api/v1/properties`, `/api/v1/cities`, `/api/v1/promos`) saat pertama kali dimuat, lalu menampilkan data sungguhan.
4. Saat customer melakukan booking di modal booking React, sistem memanggil `POST /api/v1/bookings` (data BENAR-BENAR masuk ke tabel `bookings`), lalu saat customer klik "Simulasi Pembayaran Sukses", sistem memanggil endpoint baru `POST /api/v1/bookings/{id}/confirm-payment` yang mensimulasikan callback otomatis dari payment gateway (QRIS/VA) — persis seperti yang sungguhan akan dilakukan Midtrans/Xendit dkk lewat webhook.
5. Booking itu otomatis langsung terlihat oleh Admin & Mitra di dashboard mereka, dengan status yang benar (`paid` → bisa langsung di-approve Mitra).

## Bug yang saya temukan & perbaiki (kumulatif, termasuk sesi sebelumnya)

1. **`public/index.php` hilang total** — file front controller wajib Laravel. Dibuat ulang.
2. **`routes/api.php` tidak terdaftar** di `bootstrap/app.php` — semua endpoint booking mati. Didaftarkan.
3. **Bug inti alur pembayaran & booking**: verifikasi pembayaran Admin tidak pernah memindahkan status booking ke `paid`. Diperbaiki + disatukan logikanya jadi satu method `Booking::markPaymentVerified()` yang dipakai baik oleh Admin (verifikasi manual) maupun API publik (simulasi webhook otomatis).
4. **Mitra bisa approve check-in sebelum pembayaran diverifikasi** — sekarang diblokir.
5. **Suspend/Ban akun tidak pernah diberlakukan** — sekarang `RoleMiddleware` benar-benar mem-blokir user yang di-suspend/banned.
6. **Rating properti tidak ter-update** saat ada review baru — sekarang dihitung ulang otomatis.
7. **Frontend gagal build** (dependency native rollup hilang) — diperbaiki.
8. **`composer.lock` salah resolve ke Symfony 8** (butuh PHP 8.4, padahal cukup 8.3) — diturunkan ke Symfony 7.4 yang kompatibel.
9. **Bug tersembunyi di konfigurasi TypeScript**: `tsconfig.node.json` tidak punya pengaturan `noEmit`, sehingga setiap kali `tsc` dijalankan, dia diam-diam membuat file `vite.config.js` basi yang **menimpa/mengalahkan** `vite.config.ts` asli (Vite selalu memilih `.js` di atas `.ts` kalau dua-duanya ada) — ini kemungkinan alasan konfigurasi build frontend sebelumnya tidak pernah benar-benar berpengaruh. Sudah diperbaiki, dan file basi tersebut sudah dihapus.
10. Payment method `alfamart` yang sudah ada di UI frontend tapi ditolak backend — sekarang diterima.
11. Folder duplikat `stayease-backend`/`stayease-indonesia` (identik 100% dengan root) — dihapus.

## ⚠️ Catatan penting: belum ada sistem login sungguhan
Aplikasi ini masih pakai mode **demo**: tidak ada halaman register/login email+password. Tombol "Dashboard Saya" & Role Switcher otomatis login sebagai user demo per role, supaya semua fitur bisa langsung dicoba tanpa daftar akun. Kalau mau dipakai produksi sungguhan dengan akun rahasia customer/mitra asli, perlu ditambah sistem autentikasi asli (`laravel/fortify` atau `laravel/breeze`) — beri tahu saya kalau mau saya bantu buatkan.

## Sudah diuji langsung end-to-end (bukan cuma baca kode)
- ✅ `/` menyajikan storefront React, asset JS/CSS ke-load dengan benar
- ✅ React memuat properti/kota/promo asli dari API Laravel (dicek field-by-field, semua cocok)
- ✅ Booking dari storefront React → tersimpan di DB → **muncul otomatis** di dashboard Admin & Mitra
- ✅ Konfirmasi pembayaran (simulasi webhook) → status booking otomatis `paid`
- ✅ Mitra approve check-in setelah pembayaran verified → status `active_lease`
- ✅ Ke-4 dashboard role bisa diakses, role switcher & suspend/ban berfungsi
- ✅ Mitra request payout → Superadmin approve payout

## Cara menjalankan di komputer kamu

### Setup awal (sekali saja)
```bash
# Backend
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed

# Frontend — build storefront ke public/storefront/
npm install
npm run build
```

### Menjalankan (mode production/demo, SATU server saja)
```bash
php artisan serve
```
Buka `http://127.0.0.1:8000` → langsung tampil storefront customer dengan data asli. Klik "Dashboard Saya" di navbar untuk masuk ke area akun.

> **Kalau `composer install` gagal / minta PHP 8.4**: vendor/ di zip ini sudah diperbaiki untuk PHP 8.3. Jangan jalankan `composer install` ulang kalau PHP kalian masih 8.3. Kalau PHP kalian 8.4+, aman jalankan seperti biasa.

### Mode development (frontend & backend jalan terpisah, hot-reload)
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```
Buka `http://localhost:3000` — perubahan kode React langsung terlihat tanpa build ulang (proxy otomatis meneruskan panggilan `/api/*` ke Laravel di port 8000, sudah dikonfigurasi di `vite.config.ts`).

**Setiap kali selesai mengubah kode di `src/`, jalankan `npm run build` lagi** supaya versi production di `/` (yang dilayani Laravel) ikut ter-update.
