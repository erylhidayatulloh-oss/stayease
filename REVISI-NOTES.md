# Revisi StayEase — Catatan Perubahan

## 1. Mitra Biasa & Mitra Pro
- Kolom baru `mitra_tier` (`reguler` / `pro`) di tabel `users`.
- Superadmin → **Manajemen Pengguna**: dropdown untuk mengubah tingkatan Mitra (Biasa/Pro) pada setiap akun Mitra.
- Mitra Pro **tidak bisa** menambah/menghapus properti sendiri (tombol disembunyikan di dashboard Mitra, dan diblok juga di level controller sebagai jaga-jaga). Mitra Pro tetap melihat dashboard, daftar properti, dan laporan keuangan seperti biasa — hanya read-only untuk bagian listing.
- Admin → menu baru **Properti Mitra Pro**: Admin mengunggah properti atas nama Mitra Pro yang dipilih. Properti ini langsung *tayang & terverifikasi* (tanpa antre moderasi) karena Admin yang bertanggung jawab penuh atas listing & pembayarannya.
- File terkait: `database/migrations/2026_09_19_000001_add_mitra_tier_and_articles.php`, `app/Models/User.php`, `app/Http/Controllers/{MitraDashboardController,SuperadminDashboardController,AdminDashboardController}.php`, view-view di `resources/views/dashboard/{mitra,superadmin,admin}/`.

## 2. Halaman Artikel
- Model & tabel baru `articles` (judul, slug, gambar sampul, ringkasan, isi, status draft/terbit).
- Akses tulis/kelola artikel **hanya untuk Admin & Superadmin** (menu baru "Kelola Artikel" di sidebar Admin).
- Halaman publik: `/artikel` (daftar) dan `/artikel/{slug}` (detail), tampil untuk semua pengunjung tanpa login.
- File terkait: `app/Models/Article.php`, `app/Http/Controllers/ArticleController.php`, tambahan method di `AdminDashboardController.php`, view di `resources/views/articles/` dan `resources/views/dashboard/admin/`.

## 3. Perbaikan Halaman Login
- Pilihan role (Penyewa/Mitra/Admin/Superadmin) di halaman login **dihapus** — sekarang cukup email & kata sandi, sistem otomatis mengarahkan ke dashboard sesuai role akun yang login.
- Daftar kredensial demo (email + password yang terlihat publik) **dihapus** dari halaman login.
- `AuthController` disederhanakan: validasi hanya email + password, redirect berdasarkan `role` milik user itu sendiri di database.
- File terkait: `resources/views/auth/login.blade.php`, `app/Http/Controllers/AuthController.php`, `app/Http/Middleware/RoleMiddleware.php`.

## 4. Perbaikan Fitur Booking Kilat (⚡ "Sewa" Instan)
**Bug yang ditemukan:** tombol "⚡ Sewa" (Pesan Langsung & Bayar Instan) di kartu properti selalu mengambil tipe kamar pertama (`units[0]`) tanpa mengecek stok — sehingga penyewa bisa saja diarahkan langsung ke halaman pembayaran untuk tipe kamar yang sebenarnya **sudah penuh/habis**.

**Perbaikan:**
- Sistem sekarang memilih tipe kamar pertama yang **masih tersedia stoknya**.
- Jika seluruh tipe kamar di properti tersebut penuh, tombol "⚡ Sewa" otomatis berubah jadi label "Penuh" (non-aktif), dan sistem mengarahkan ke halaman detail properti agar penyewa bisa melihat opsi lain.
- File terkait: `src/App.tsx` (fungsi `getFirstAvailableUnit`, `hasAnyAvailableUnit`, `handleQuickBook`), `src/components/explore/PropertyCard.tsx`.

## Catatan Teknis
- Jangan lupa jalankan `php artisan migrate` setelah deploy untuk menerapkan migration `mitra_tier` & tabel `articles`.
- Build ulang frontend dengan `npm install && npm run build` di server/lokal masing-masing (sandbox tempat revisi ini dikerjakan tidak memiliki akses internet untuk reinstall dependency native `rollup`, tapi pengecekan tipe TypeScript — `tsc -b` — sudah lolos tanpa error).
