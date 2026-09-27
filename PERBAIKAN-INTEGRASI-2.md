# Ringkasan Integrasi Tahap 2 — StayEase

Lanjutan dari `PERBAIKAN.md`. Semua fungsi di bawah ini sudah diuji langsung
(server Laravel dijalankan di sandbox, endpoint dipanggil lewat curl,
alurnya dites end-to-end) — bukan cuma dibaca kodenya.

## Cara pasang
Timpa 16 file di paket ini ke folder proyek Anda pada path yang sama persis
(struktur foldernya mengikuti root proyek), lalu:

```
npm run build      # storefront React perlu di-build ulang
php artisan route:clear
php artisan config:clear
```

## Bug yang diperbaiki

1. **Booking tamu selalu tercatat sebagai user id 4.**
   `ApiController::createBooking` dulu memakai `auth()->id() ?? 4` — jadi
   SEMUA booking dari tamu yang tidak login (termasuk lewat isian nama/
   email/telepon di form booking) tercatat sebagai satu akun demo yang
   sama. Sekarang setiap tamu dicarikan/dibuatkan akun sendiri berdasarkan
   email mereka (`User::findOrCreateGuest`).

2. **Halaman "Pesan" di Dashboard Penyewa: `recipient_id` di-hardcode ke
   user id 3.** Semua penyewa yang membuka halaman itu chat-nya selalu
   terkirim ke satu akun mitra yang sama, bukan pemilik properti yang
   sebenarnya relevan. Sekarang dikelompokkan jadi thread per lawan bicara
   yang bisa dipilih.

3. **Filter `property_id` di endpoint chat storefront membuat balasan dari
   dashboard "hilang".** Ditemukan saat pengujian end-to-end: balasan yang
   dikirim mitra dari dashboard tidak menyertakan `property_id`, jadi tidak
   pernah cocok dengan filter di endpoint chat storefront. Sudah
   diseragamkan (thread dicocokkan berdasarkan pasangan pengirim-penerima,
   sama seperti dashboard).

## Fitur yang sebelumnya cuma tampilan (prototype), sekarang terhubung ke backend sungguhan

4. **Wishlist storefront** — dulu hanya `localStorage`, tidak pernah masuk
   tabel `wishlists`. Sekarang ada endpoint `GET/POST /api/v1/wishlist*`,
   dan `App.tsx` mensinkronkannya begitu identitas pengunjung diketahui.

5. **"Chat dengan Pemilik" di halaman properti** — dulu 100% simulasi
   (balasan otomatis acak lewat `setTimeout`, tidak pernah tersimpan).
   Sekarang pesan sungguhan tersimpan di tabel `messages` lewat
   `GET/POST /api/v1/messages*`, dan muncul di dashboard Mitra.

6. **"Pasang Iklan" (wizard pendaftaran Mitra)** — dulu tombol
   "Publikasikan Iklan Sewa" cuma menampilkan animasi confetti + toast,
   tidak pernah mengirim apa pun ke server. Sekarang endpoint baru
   `POST /api/v1/listings` betul-betul membuat Property (status
   `pending_review`) + 1 Room Unit + akun Mitra (dibuat otomatis dari
   email pemilik), dan otomatis muncul di antrian verifikasi Admin yang
   sudah ada. Sudah diuji sampai: submit → masuk antrian → Admin approve →
   tampil di listing publik.

## Fitur baru

7. **Inbox Pesan di Dashboard Mitra** — sebelumnya sama sekali tidak ada
   halaman untuk mitra melihat/membalas pesan (route-nya belum dibuat).
   Sekarang ada di menu "Pesan Penyewa", dengan badge jumlah pesan belum
   dibaca.

## Catatan / keterbatasan yang sengaja belum disentuh

- **Upload foto properti** masih berupa penjelasan teks di wizard "Pasang
  Iklan" (bukan input file sungguhan), karena belum ada sistem penyimpanan
  file. Ini sekarang jujur di teksnya ("tim fotografer Stayease akan
  datang..."), tidak lagi berpura-pura ada tombol upload yang berfungsi.
- **Reset password / lupa email** untuk akun yang dibuat otomatis dari
  booking/wishlist/chat/listing belum ada alurnya — akun-akun tersebut
  dibuatkan password acak yang tidak diberitahukan ke pemiliknya. Kalau
  ingin pengguna publik bisa login ke akun itu nanti, perlu ditambahkan
  fitur "atur password" (mis. lewat link email) di iterasi berikutnya.
- **`vendor/composer/platform_check.php`** di proyek Anda mensyaratkan PHP
  >= 8.4.1 walau `composer.json` menyatakan `^8.3` — ini bukan bagian dari
  permintaan integrasi kali ini, tapi patut dicek karena bisa membuat
  `php artisan` gagal total di server yang cuma punya PHP 8.3.
