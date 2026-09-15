<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\City;
use App\Models\Property;
use App\Models\RoomUnit;
use App\Models\Booking;
use App\Models\Promo;
use App\Models\Review;
use App\Models\Payout;
use App\Models\AuditLog;
use App\Models\Message;
use App\Models\Wishlist;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create 4 Main Role Users
        $superadmin = User::create([
            'name' => 'Budi Wisesa (Superadmin)',
            'email' => 'superadmin@stayease.id',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'status' => 'active',
            'phone' => '081100000001',
            'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
            'ktp_number' => '3174000000000001',
            'ktp_verified_at' => now(),
            'bank_name' => 'BCA',
            'bank_account_number' => '8899001122',
            'bank_account_holder' => 'PT STAYEASE DIGITAL INDONESIA',
        ]);

        $admin = User::create([
            'name' => 'Siti Nurhaliza (Admin Operasional)',
            'email' => 'admin@stayease.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
            'phone' => '081100000002',
            'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&q=80',
            'ktp_number' => '3174000000000002',
            'ktp_verified_at' => now(),
        ]);

        $mitra = User::create([
            'name' => 'Ir. Hendra Gunawan (Mitra Pemilik)',
            'email' => 'mitra@stayease.id',
            'password' => Hash::make('password'),
            'role' => 'mitra',
            'status' => 'active',
            'phone' => '081298765432',
            'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=150&q=80',
            'ktp_number' => '3174051203800009',
            'ktp_verified_at' => now(),
            'bank_name' => 'Bank Central Asia (BCA)',
            'bank_account_number' => '5210987654',
            'bank_account_holder' => 'Ir. Hendra Gunawan',
        ]);

        $user = User::create([
            'name' => 'Rian Pratama (Penyewa)',
            'email' => 'user@stayease.id',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
            'phone' => '081311223344',
            'avatar' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80',
            'ktp_number' => '3174051203980004',
            'ktp_verified_at' => now(),
        ]);

        // Additional tenant user
        $user2 = User::create([
            'name' => 'Dimas Anggara',
            'email' => 'dimas@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'status' => 'active',
            'phone' => '081299334455',
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',
            'ktp_number' => '3174051203950002',
            'ktp_verified_at' => now(),
        ]);

        // 2. Create Cities
        $jkt = City::create([
            'name' => 'Jakarta Selatan',
            'slug' => 'jakarta-selatan',
            'province' => 'DKI Jakarta',
            'image' => 'https://images.unsplash.com/photo-1555899434-94d1368aa7af?auto=format&fit=crop&w=800&q=80',
            'description' => 'Pusat bisnis, kafe hits, dan hunian eksklusif favorit profesional muda dan ekspatriat.',
            'avg_kost_price' => 2400000,
            'avg_apt_price' => 6500000,
            'is_active' => true,
            'popular_districts' => ['Tebet', 'Kuningan', 'Setiabudi', 'SCBD', 'Kemang', 'Pancoran', 'Cilandak'],
            'top_universities' => ['Universitas Bakrie', 'Universitas Prasetiya Mulya', 'Universitas Pancasila'],
            'faqs' => [
                ['q' => 'Berapa rata-rata harga sewa kost di Jakarta Selatan?', 'a' => 'Berkisar antara Rp 1.800.000 - Rp 4.500.000/bln.'],
                ['q' => 'Apakah sewa di Stayease bergaransi?', 'a' => 'Ya, 100% garansi foto asli atau uang kembali.']
            ],
            'meta_title' => 'Sewa Kost & Apartemen di Jakarta Selatan | Stayease Indonesia',
            'meta_description' => 'Cari kost eksklusif dan apartemen di Tebet, Kuningan, SCBD Jakarta Selatan.'
        ]);

        $jog = City::create([
            'name' => 'Yogyakarta (Jogja)',
            'slug' => 'yogyakarta',
            'province' => 'DI Yogyakarta',
            'image' => 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&w=800&q=80',
            'description' => 'Kota pelajar dengan suasana asri, biaya hidup terjangkau, dan pilihan kost mahasiswa terlengkap.',
            'avg_kost_price' => 1200000,
            'avg_apt_price' => 3800000,
            'is_active' => true,
            'popular_districts' => ['Sleman (UGM)', 'Pogung', 'Seturan', 'Gejayan', 'Jakal (Kaliurang)'],
            'top_universities' => ['Universitas Gadjah Mada (UGM)', 'UNY', 'UPN Yogyakarta', 'UII'],
            'faqs' => [
                ['q' => 'Bagaimana mencari kost murah dekat kampus UGM?', 'a' => 'Gunakan filter area Pogung atau Sekip di Stayease.']
            ],
            'meta_title' => 'Sewa Kost Murah Dekat UGM Yogyakarta | Stayease Indonesia',
            'meta_description' => 'Kost putri dan putra dekat kampus UGM Pogung, Seturan Jogja.'
        ]);

        $bali = City::create([
            'name' => 'Bali (Badung & Denpasar)',
            'slug' => 'bali',
            'province' => 'Bali',
            'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=800&q=80',
            'description' => 'Surga Work from Bali (WFB), villa tropis berkolam renang, dan co-living modern.',
            'avg_kost_price' => 2800000,
            'avg_apt_price' => 12500000,
            'is_active' => true,
            'popular_districts' => ['Canggu', 'Seminyak', 'Pererenan', 'Uluwatu', 'Sanur'],
            'top_universities' => ['Universitas Udayana (UNUD)', 'Warmadewa'],
            'faqs' => [
                ['q' => 'Apakah villa bisa disewa bulanan?', 'a' => 'Bisa, dengan potongan harga sewa jangka panjang hingga 25%.']
            ],
            'meta_title' => 'Sewa Villa & Co-Living Canggu Bali Bulanan | Stayease',
            'meta_description' => 'Sewa villa tropis bulanan di Batu Bolong Canggu Bali dengan high speed fiber Wi-Fi.'
        ]);

        $bdg = City::create([
            'name' => 'Bandung',
            'slug' => 'bandung',
            'province' => 'Jawa Barat',
            'image' => 'https://images.unsplash.com/photo-1549488344-1f9b8d2bd1f3?auto=format&fit=crop&w=800&q=80',
            'description' => 'Kota kembang berudara sejuk, surganya mahasiswa ITB/Unpad dan pekerja kreatif.',
            'avg_kost_price' => 1500000,
            'avg_apt_price' => 4200000,
            'is_active' => true,
            'popular_districts' => ['Dago', 'Dipatiukur', 'Ciumbuleuit', 'Buah Batu'],
            'top_universities' => ['Institut Teknologi Bandung (ITB)', 'UNPAD', 'Telkom University'],
            'faqs' => [
                ['q' => 'Lokasi kost paling dekat ITB Ganesha?', 'a' => 'Dago Bawah dan Tubagus Ismail.']
            ],
            'meta_title' => 'Sewa Kost Dekat ITB Bandung | Stayease Indonesia',
            'meta_description' => 'Kost putra putri di Dago, Dipatiukur Bandung dekat ITB & Unpad.'
        ]);

        $sby = City::create([
            'name' => 'Surabaya',
            'slug' => 'surabaya',
            'province' => 'Jawa Timur',
            'image' => 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=800&q=80',
            'description' => 'Kota metropolitan terbesar kedua di Indonesia dengan pusat bisnis dan kampus ternama.',
            'avg_kost_price' => 1600000,
            'avg_apt_price' => 4500000,
            'is_active' => true,
            'popular_districts' => ['Gubeng', 'Surabaya Barat (Pakuwon)', 'Mulyorejo', 'Sukolilo'],
            'top_universities' => ['Universitas Airlangga (UNAIR)', 'ITS', 'Petra Christian University'],
            'faqs' => [
                ['q' => 'Apakah ada apartemen connect mall di Surabaya?', 'a' => 'Ada di Pakuwon Mall Tower Orchid.']
            ],
            'meta_title' => 'Sewa Apartemen & Kost Surabaya | Stayease Indonesia',
            'meta_description' => 'Sewa apartemen Pakuwon Mall dan kost Gubeng dekat UNAIR Surabaya.'
        ]);

        // 3. Create Properties
        $prop1 = Property::create([
            'mitra_id' => $mitra->id,
            'city_id' => $jkt->id,
            'title' => 'Stayease Living Tebet Exclusive Suites',
            'slug' => 'kost-tebet-exclusive-jakarta-selatan',
            'property_type' => 'kost_campur',
            'gender_restriction' => 'Campur (Pria/Wanita)',
            'address' => 'Jl. Tebet Barat Dalam Raya No. 42, RT.05/RW.02',
            'sub_district' => 'Tebet',
            'coords_lat' => -6.2345,
            'coords_lng' => 106.8532,
            'base_price_monthly' => 2350000,
            'base_price_yearly' => 26000000,
            'discount_percent' => 12,
            'deposit_amount' => 500000,
            'electricity_policy' => 'token_mandiri',
            'service_fee' => 50000,
            'verified_official' => true,
            'has_virtual_tour' => true,
            'status' => 'active',
            'images' => [
                'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1000&q=80',
                'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=1000&q=80',
                'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=1000&q=80'
            ],
            'virtual_tour_url' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1400&q=80',
            'facilities' => ['AC Daikin 1 PK', 'Wi-Fi 100 Mbps', 'Kamar Mandi Dalam', 'Water Heater', 'Smart TV 32 Inch', 'CCTV 24 Jam', 'Parkir Mobil & Motor'],
            'rules' => ['Akses 24 Jam dengan Smart Card', 'Tamu lawan jenis dilarang menginap', 'Dilarang merokok di kamar'],
            'transit_points' => [
                ['name' => 'Stasiun KRL Tebet', 'type' => 'KRL', 'distanceMeters' => 450, 'walkMinutes' => 6],
                ['name' => 'Halte Transjakarta Tebet Eco Park', 'type' => 'Transjakarta', 'distanceMeters' => 300, 'walkMinutes' => 4]
            ],
            'description' => 'Hunian modern berstandar hotel di jantung Jakarta Selatan. Berjarak 450 meter dari Stasiun Tebet dengan Wi-Fi fiber optik dan rooftop lounge.',
            'rating_avg' => 4.92,
            'reviews_count' => 38,
        ]);

        $unit1 = RoomUnit::create([
            'property_id' => $prop1->id,
            'name' => 'Deluxe Room (Jendela Luar)',
            'size_m2' => 18,
            'bed_type' => 'Queen Bed (160x200)',
            'price_monthly' => 2350000,
            'price_yearly' => 26000000,
            'promo_price_monthly' => 2068000,
            'is_promo' => true,
            'available_count' => 2,
            'total_count' => 10,
            'photos' => ['https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80'],
            'features' => ['Smart TV 32"', 'Water Heater', 'Jendela Luar']
        ]);

        $prop2 = Property::create([
            'mitra_id' => $mitra->id,
            'city_id' => $jog->id,
            'title' => 'Kost Putri Griya Pogung Asri UGM',
            'slug' => 'kost-putri-pogung-ugm-yogyakarta',
            'property_type' => 'kost_putri',
            'gender_restriction' => 'Khusus Putri',
            'address' => 'Jl. Pogung Kidul No. 18, Sinduadi, Mlati',
            'sub_district' => 'Sleman (UGM)',
            'coords_lat' => -7.7682,
            'coords_lng' => 110.3752,
            'base_price_monthly' => 1350000,
            'base_price_yearly' => 15000000,
            'discount_percent' => 15,
            'deposit_amount' => 300000,
            'electricity_policy' => 'include',
            'service_fee' => 30000,
            'verified_official' => true,
            'has_virtual_tour' => true,
            'status' => 'active',
            'images' => [
                'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=1000&q=80',
                'https://images.unsplash.com/photo-1540518614846-7ede433c4ef7?auto=format&fit=crop&w=1000&q=80'
            ],
            'facilities' => ['Listrik Termasuk (Include Listrik)', 'AC', 'Wi-Fi 100 Mbps', 'Kamar Mandi Dalam', 'Dapur Bersama + Dispenser Gratis', 'Penjaga Kost 24 Jam'],
            'rules' => ['Khusus mahasiswi / karyawati wanita', 'Gerbang tutup jam 23.00 (ada akses darurat)', 'Tamu pria di ruang tamu depan'],
            'transit_points' => [
                ['name' => 'Kampus FK & FMIPA UGM', 'type' => 'Kampus', 'distanceMeters' => 350, 'walkMinutes' => 4],
                ['name' => 'RSUP Dr. Sardjito', 'type' => 'Mall', 'distanceMeters' => 600, 'walkMinutes' => 8]
            ],
            'description' => 'Pilihan utama kost putri dekat kampus UGM Yogyakarta. Suasana tenang dengan sirkulasi udara sejuk di kawasan Pogung Kidul.',
            'rating_avg' => 4.95,
            'reviews_count' => 52,
        ]);

        $unit2 = RoomUnit::create([
            'property_id' => $prop2->id,
            'name' => 'Single Deluxe (Include Listrik)',
            'size_m2' => 15,
            'bed_type' => 'Single Bed (120x200)',
            'price_monthly' => 1350000,
            'price_yearly' => 15000000,
            'promo_price_monthly' => 1147500,
            'is_promo' => true,
            'available_count' => 3,
            'total_count' => 16,
            'photos' => ['https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=800&q=80'],
            'features' => ['Include Listrik', 'AC', 'Kamar Mandi Dalam']
        ]);

        $prop3 = Property::create([
            'mitra_id' => $mitra->id,
            'city_id' => $bali->id,
            'title' => 'The Bohemia Haven Tropical Villa & Co-Living',
            'slug' => 'villa-bohemia-canggu-oasis-bali',
            'property_type' => 'villa',
            'gender_restriction' => 'Campur (Pria/Wanita)',
            'address' => 'Jl. Pantai Batu Bolong No. 88, Canggu',
            'sub_district' => 'Canggu',
            'coords_lat' => -8.6481,
            'coords_lng' => 115.1328,
            'base_price_monthly' => 7800000,
            'base_price_daily' => 650000,
            'base_price_yearly' => 85000000,
            'discount_percent' => 10,
            'deposit_amount' => 1000000,
            'electricity_policy' => 'include',
            'service_fee' => 100000,
            'verified_official' => true,
            'has_virtual_tour' => true,
            'status' => 'active',
            'images' => [
                'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1000&q=80',
                'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1000&q=80'
            ],
            'facilities' => ['Private Swimming Pool', 'Wi-Fi 150 Mbps (Starlink Backup)', 'En-suite Bathtub', 'Kitchenette with Espresso Machine', 'Pet Friendly'],
            'rules' => ['Pet friendly dengan konfirmasi awal', 'Dilarang pesta musik di atas jam 23.00'],
            'transit_points' => [
                ['name' => 'Pantai Batu Bolong', 'type' => 'Bandara', 'distanceMeters' => 900, 'walkMinutes' => 10]
            ],
            'description' => 'Pengalaman Work from Bali (WFB) terbaik di Canggu dengan kolam renang privat dan atmosfer komunitas digital nomad.',
            'rating_avg' => 4.98,
            'reviews_count' => 46,
        ]);

        $unit3 = RoomUnit::create([
            'property_id' => $prop3->id,
            'name' => 'Poolside Master Suite',
            'size_m2' => 35,
            'bed_type' => 'King Bed (180x200)',
            'price_monthly' => 7800000,
            'price_daily' => 650000,
            'price_yearly' => 85000000,
            'promo_price_monthly' => 7020000,
            'is_promo' => true,
            'available_count' => 1,
            'total_count' => 8,
            'photos' => ['https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=800&q=80'],
            'features' => ['Akses Kolam Renang', 'Bathtub Mewah', 'Espresso Maker']
        ]);

        // A pending review property for Admin queue demo
        $propPending = Property::create([
            'mitra_id' => $mitra->id,
            'city_id' => $bdg->id,
            'title' => 'Kost Dago Garden Residence (Menunggu Verifikasi)',
            'slug' => 'kost-dago-garden-bandung-pending',
            'property_type' => 'kost_campur',
            'gender_restriction' => 'Campur (Pria/Wanita)',
            'address' => 'Jl. Dago Asri No. 55, Coblong',
            'sub_district' => 'Dago',
            'coords_lat' => -6.8850,
            'coords_lng' => 107.6180,
            'base_price_monthly' => 1950000,
            'deposit_amount' => 500000,
            'electricity_policy' => 'include',
            'service_fee' => 40000,
            'verified_official' => false,
            'has_virtual_tour' => false,
            'status' => 'pending_review',
            'images' => [
                'https://images.unsplash.com/photo-1554995207-c18c203602cb?auto=format&fit=crop&w=1000&q=80'
            ],
            'facilities' => ['AC', 'Wi-Fi 100Mbps', 'Kamar Mandi Dalam', 'Water Heater'],
            'rules' => ['Menjaga ketenangan malam hari'],
            'transit_points' => [
                ['name' => 'Kampus ITB Ganesha', 'type' => 'Kampus', 'distanceMeters' => 700, 'walkMinutes' => 9]
            ],
            'description' => 'Listing baru yang diajukan oleh Mitra Hendra. Memerlukan audit kelayakan foto fisik oleh tim Admin.',
            'rating_avg' => 5.00,
            'reviews_count' => 0,
        ]);

        RoomUnit::create([
            'property_id' => $propPending->id,
            'name' => 'Standard Room Garden View',
            'size_m2' => 16,
            'bed_type' => 'Single Bed (120x200)',
            'price_monthly' => 1950000,
            'available_count' => 4,
            'total_count' => 10,
            'photos' => ['https://images.unsplash.com/photo-1554995207-c18c203602cb?auto=format&fit=crop&w=800&q=80'],
            'features' => ['AC', 'Water Heater', 'Garden View']
        ]);

        // 4. Create Promos
        Promo::create([
            'code' => 'STAYEASEHEMAT',
            'title' => 'Diskon Pengguna Baru Stayease',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'max_discount' => 350000,
            'min_transaction' => 1000000,
            'badge' => '10% OFF',
            'description' => 'Potongan 10% s.d. Rp 350.000 untuk transaksi booking pertama di seluruh Indonesia.',
            'usage_limit' => 500,
            'used_count' => 42,
            'is_active' => true,
            'valid_until' => now()->addMonths(6),
        ]);

        Promo::create([
            'code' => 'MABA2026',
            'title' => 'Spesial Mahasiswa Baru',
            'discount_type' => 'fixed',
            'discount_value' => 250000,
            'min_transaction' => 1200000,
            'badge' => 'HEMAT 250RB',
            'description' => 'Cashback langsung Rp 250.000 untuk kost dekat kampus UGM, UI, ITB, UNAIR.',
            'usage_limit' => 300,
            'used_count' => 88,
            'is_active' => true,
            'valid_until' => now()->addMonths(3),
        ]);

        // 5. Create Bookings
        $booking1 = Booking::create([
            'booking_code' => 'STY-JKT-882910',
            'user_id' => $user->id,
            'property_id' => $prop1->id,
            'room_unit_id' => $unit1->id,
            'check_in_date' => now()->subDays(10),
            'duration_months' => 3,
            'status' => 'active_lease',
            'base_rent_total' => 6204000,
            'deposit_total' => 500000,
            'service_fee' => 50000,
            'addons_total' => 450000,
            'discount_total' => 350000,
            'grand_total' => 6854000,
            'addons_json' => ['cleaning_weekly' => true],
            'payment_method' => 'qris',
            'payment_status' => 'verified',
            'payment_ref' => 'QRIS-NMID-992102',
            'paid_at' => now()->subDays(11),
            'contract_signed_at' => now()->subDays(11),
            'signature_data' => 'data:image/png;base64,mockSignature...',
            'tenant_name' => 'Rian Pratama',
            'tenant_phone' => '081311223344',
            'tenant_email' => 'user@stayease.id',
            'tenant_nik' => '3174051203980004',
            'emergency_name' => 'Agus Santoso (Ayah)',
            'emergency_phone' => '081398765432',
        ]);

        $booking2 = Booking::create([
            'booking_code' => 'STY-DPS-441209',
            'user_id' => $user2->id,
            'property_id' => $prop3->id,
            'room_unit_id' => $unit3->id,
            'check_in_date' => now()->addDays(5),
            'duration_months' => 1,
            'status' => 'paid',
            'base_rent_total' => 7020000,
            'deposit_total' => 1000000,
            'service_fee' => 100000,
            'grand_total' => 8120000,
            'payment_method' => 'bca_va',
            'payment_status' => 'verified',
            'payment_ref' => 'VA-BCA-88920812',
            'paid_at' => now()->subHours(2),
            'contract_signed_at' => now()->subHours(2),
            'tenant_name' => 'Dimas Anggara',
            'tenant_phone' => '081299334455',
            'tenant_email' => 'dimas@gmail.com',
            'tenant_nik' => '3174051203950002',
        ]);

        // 6. Create Reviews
        Review::create([
            'booking_id' => $booking1->id,
            'user_id' => $user->id,
            'property_id' => $prop1->id,
            'rating' => 5,
            'cleanliness_rating' => 5,
            'security_rating' => 5,
            'location_rating' => 5,
            'comment' => 'Kamar sangat nyaman dan bersih, Wi-Fi 100 Mbps sangat lancar buat kerja WFH. Pak Hendra sangat responsif.',
            'owner_reply' => 'Terima kasih Mas Rian! Senang bisa memberikan kenyamanan hunian di Stayease Tebet.',
            'owner_replied_at' => now()->subDays(2),
            'status' => 'published',
        ]);

        // 7. Create Payouts
        Payout::create([
            'mitra_id' => $mitra->id,
            'amount' => 6500000,
            'bank_name' => 'Bank Central Asia (BCA)',
            'account_number' => '5210987654',
            'account_holder' => 'Ir. Hendra Gunawan',
            'status' => 'approved',
            'notes' => 'Pencairan dana sewa booking STY-JKT-882910 setelah dipotong biaya platform 5%.',
            'processed_by' => $superadmin->id,
            'processed_at' => now()->subDays(5),
        ]);

        // A pending payout for Superadmin demo
        Payout::create([
            'mitra_id' => $mitra->id,
            'amount' => 7714000,
            'bank_name' => 'Bank Central Asia (BCA)',
            'account_number' => '5210987654',
            'account_holder' => 'Ir. Hendra Gunawan',
            'status' => 'pending',
            'notes' => 'Permintaan pencairan dana sewa booking STY-DPS-441209.',
        ]);

        // 8. Create Wishlists
        Wishlist::create([
            'user_id' => $user->id,
            'property_id' => $prop2->id,
        ]);
        Wishlist::create([
            'user_id' => $user->id,
            'property_id' => $prop3->id,
        ]);

        // 9. Create Messages
        Message::create([
            'sender_id' => $user->id,
            'recipient_id' => $mitra->id,
            'property_id' => $prop1->id,
            'content' => 'Selamat siang Pak Hendra, apakah boleh titip kurir paket di meja security?',
            'is_read' => true,
            'created_at' => now()->subHours(5),
        ]);

        Message::create([
            'sender_id' => $mitra->id,
            'recipient_id' => $user->id,
            'property_id' => $prop1->id,
            'content' => 'Boleh sekali Mas Rian, security kami siap menerima dan mencatat di logbook ya.',
            'is_read' => true,
            'created_at' => now()->subHours(4),
        ]);

        // 10. Create Audit Logs
        AuditLog::log('USER_LOGIN', 'User', $superadmin->id, ['email' => $superadmin->email], $superadmin->id);
        AuditLog::log('PROPERTY_VERIFIED', 'Property', $prop1->id, ['status' => 'active', 'badge' => 'official'], $admin->id);
        AuditLog::log('PAYOUT_DISBURSED', 'Payout', 1, ['amount' => 6500000, 'bank' => 'BCA'], $superadmin->id);
        AuditLog::log('BOOKING_CONFIRMED', 'Booking', $booking1->id, ['code' => 'STY-JKT-882910', 'payment' => 'qris'], $user->id);
    }
}
