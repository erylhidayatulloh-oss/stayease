import { Property } from '../types';

export const PROPERTIES: Property[] = [
  {
    id: 'kost-tebet-exclusive',
    slug: 'kost-tebet-exclusive-jakarta-selatan',
    title: 'Stayease Living Tebet Exclusive Suites',
    propertyType: 'kost_campur',
    genderRestriction: 'Campur (Pria/Wanita)',
    rentalPeriods: ['bulanan', 'tahunan'],
    address: 'Jl. Tebet Barat Dalam Raya No. 42, RT.05/RW.02',
    subDistrict: 'Tebet',
    city: 'Jakarta Selatan',
    province: 'DKI Jakarta',
    coords: { lat: -6.2345, lng: 106.8532 },
    verifiedOfficial: true,
    hasVirtualTour: true,
    isAvailable: true,
    basePriceMonthly: 2350000,
    basePriceYearly: 26000000,
    discountPercent: 12,
    depositAmount: 500000,
    electricityPolicy: 'token_mandiri',
    serviceFee: 50000,
    images: [
      'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1000&q=80',
      'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=1000&q=80',
      'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=1000&q=80',
      'https://images.unsplash.com/photo-1507652313519-d4e9174996dd?auto=format&fit=crop&w=1000&q=80'
    ],
    virtualTour360: 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?auto=format&fit=crop&w=1400&q=80',
    rating: 4.92,
    reviewCount: 38,
    roomSizeM2: 18,
    totalRooms: 20,
    availableRooms: 3,
    facilities: [
      'AC Daikin 1 PK',
      'Wi-Fi 100 Mbps',
      'Kamar Mandi Dalam',
      'Water Heater',
      'Smart TV 32 Inch',
      'Kasur Springbed Queen Size',
      'Meja Kerja & Kursi Ergonomis',
      'Dapur Bersama + Kulkas',
      'CCTV & Akses Pintu Smart Lock 24 Jam',
      'Parkir Mobil & Motor Gratis',
      'Rooftop Lounge Bersama'
    ],
    transitPoints: [
      { name: 'Stasiun KRL Tebet', type: 'KRL', distanceMeters: 450, walkMinutes: 6 },
      { name: 'Halte Transjakarta Tebet Eco Park', type: 'Transjakarta', distanceMeters: 300, walkMinutes: 4 },
      { name: 'MRT Dukuh Atas Hub', type: 'MRT', distanceMeters: 3800, walkMinutes: 18 },
      { name: 'Kota Kasablanka Mall', type: 'Mall', distanceMeters: 1200, walkMinutes: 15 }
    ],
    units: [
      {
        id: 'tebet-deluxe-a',
        name: 'Deluxe Room (Jendela Luar)',
        sizeM2: 18,
        bedType: 'Queen Bed (160x200)',
        priceMonthly: 2350000,
        priceYearly: 26000000,
        availableCount: 2,
        isPromo: true,
        promoPriceMonthly: 2068000,
        photos: [
          'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80',
          'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&w=800&q=80'
        ],
        features: ['Smart TV 32"', 'Jendela Hadap Luar', 'Water Heater', 'Kulkas Mini']
      },
      {
        id: 'tebet-executive-b',
        name: 'Executive Studio + Balkon Pribadi',
        sizeM2: 24,
        bedType: 'King Bed (180x200)',
        priceMonthly: 2950000,
        priceYearly: 32000000,
        availableCount: 1,
        isPromo: false,
        photos: [
          'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=800&q=80'
        ],
        features: ['Balkon Privat', 'Sofa Santai', 'Microwave Pribadi', 'Smart Door Lock']
      }
    ],
    reviews: [
      {
        id: 'rev-101',
        userName: 'Dimas Anggara',
        userAvatar: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80',
        userRole: 'Software Engineer di Kuningan',
        rating: 5,
        cleanliness: 5,
        location: 5,
        facilities: 5,
        date: '14 Agustus 2026',
        comment: 'Sangat recommended untuk yang kerja di SCBD atau Mega Kuningan. Ke stasiun KRL Tebet tinggal jalan kaki 5 menit. Wi-Fi super stabil buat WFH dan ada rooftop seru.',
        helpfulCount: 14,
        ownerReply: {
          date: '15 Agustus 2026',
          text: 'Terima kasih Mas Dimas! Semoga selalu nyaman tinggal di Stayease Tebet.'
        }
      }
    ],
    rules: [
      'Akses gerbang 24 jam dengan Smart Card',
      'Tamu lawan jenis dilarang menginap di dalam kamar',
      'Dilarang merokok di dalam kamar ber-AC',
      'Menjaga ketenangan di atas pukul 23:00 WIB'
    ],
    landlord: {
      id: 'landlord-hendra',
      name: 'Ir. Hendra Gunawan',
      avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=150&q=80',
      badge: 'Official Stayease',
      responseTime: '< 10 Menit',
      responseRate: 99,
      joinedYear: 2022,
      phoneWhatsapp: '+6281298765432',
      totalProperties: 3
    },
    description: 'Hunian modern berstandar hotel di jantung Jakarta Selatan. Berjarak 450 meter dari Stasiun Tebet dengan Wi-Fi fiber optik dan rooftop lounge.',
    seo: {
      metaTitle: 'Sewa Kost Tebet Exclusive Jakarta Selatan Dekat Stasiun KRL | Stayease',
      metaDescription: 'Sewa kost campur eksklusif di Tebet Jakarta Selatan. Fasilitas AC, kamar mandi dalam, Wi-Fi kencang, dekat Stasiun KRL & Kasablanka.',
      keywords: ['kost tebet', 'kost jakarta selatan', 'kost dekat stasiun tebet', 'kost eksklusif tebet', 'sewa kost tebet'],
      schemaType: 'Accommodation'
    }
  },
  {
    id: 'kost-putri-pogung-ugm',
    slug: 'kost-putri-pogung-ugm-yogyakarta',
    title: 'Kost Putri Griya Pogung Asri UGM',
    propertyType: 'kost_putri',
    genderRestriction: 'Khusus Putri',
    rentalPeriods: ['bulanan', 'tahunan'],
    address: 'Jl. Pogung Kidul No. 18, Sinduadi, Mlati',
    subDistrict: 'Sleman (UGM)',
    city: 'Yogyakarta',
    province: 'DI Yogyakarta',
    coords: { lat: -7.7682, lng: 110.3752 },
    verifiedOfficial: true,
    hasVirtualTour: true,
    isAvailable: true,
    basePriceMonthly: 1350000,
    basePriceYearly: 15000000,
    discountPercent: 15,
    depositAmount: 300000,
    electricityPolicy: 'include',
    serviceFee: 30000,
    images: [
      'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=1000&q=80',
      'https://images.unsplash.com/photo-1540518614846-7ede433c4ef7?auto=format&fit=crop&w=1000&q=80',
      'https://images.unsplash.com/photo-1513694203232-719a280e022f?auto=format&fit=crop&w=1000&q=80'
    ],
    virtualTour360: 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=1400&q=80',
    rating: 4.95,
    reviewCount: 52,
    roomSizeM2: 15,
    totalRooms: 16,
    availableRooms: 2,
    facilities: [
      'Listrik Termasuk (Free Listrik)',
      'AC Daikin',
      'Wi-Fi 100 Mbps Indihome',
      'Kamar Mandi Dalam + Shower',
      'Dapur Bersama + Gas & Kulkas Gratis',
      'Dispenser Air Minum RO Tanpa Batas',
      'Penjaga Kost Siaga 24 Jam',
      'Parkir Motor Luas & Beratap'
    ],
    transitPoints: [
      { name: 'Kampus FK & FMIPA UGM', type: 'Kampus', distanceMeters: 350, walkMinutes: 4 },
      { name: 'RSUP Dr. Sardjito', type: 'Mall', distanceMeters: 600, walkMinutes: 8 },
      { name: 'Halte Trans Jogja Pogung', type: 'Transjakarta', distanceMeters: 200, walkMinutes: 3 }
    ],
    units: [
      {
        id: 'pogung-single-deluxe',
        name: 'Single Deluxe (Include Listrik)',
        sizeM2: 15,
        bedType: 'Single Bed (120x200)',
        priceMonthly: 1350000,
        priceYearly: 15000000,
        availableCount: 2,
        isPromo: true,
        promoPriceMonthly: 1147500,
        photos: [
          'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=800&q=80'
        ],
        features: ['Free Listrik', 'AC', 'Kamar Mandi Dalam', 'Meja Belajar']
      }
    ],
    reviews: [
      {
        id: 'rev-201',
        userName: 'Nadia Salsabila',
        userAvatar: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=150&q=80',
        userRole: 'Mahasiswi Kedokteran UGM',
        rating: 5,
        cleanliness: 5,
        location: 5,
        facilities: 4.9,
        date: '10 Agustus 2026',
        comment: 'Sangat aman dan nyaman untuk mahasiswi UGM. Jalan kaki ke Fakultas Kedokteran cuma 4 menit.',
        helpfulCount: 21
      }
    ],
    rules: [
      'Khusus mahasiswi / karyawati wanita',
      'Gerbang ditutup pukul 23:00 WIB demi keamanan',
      'Tamu pria diterima di ruang tamu depan'
    ],
    landlord: {
      id: 'landlord-retno',
      name: 'Ibu Hj. Retno Wulandari',
      avatar: 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80',
      badge: 'Superhost',
      responseTime: '< 5 Menit',
      responseRate: 100,
      joinedYear: 2021,
      phoneWhatsapp: '+6281311223344',
      totalProperties: 2
    },
    description: 'Pilihan utama kost putri dekat kampus UGM Yogyakarta. Suasana tenang dengan sirkulasi udara sejuk di kawasan Pogung Kidul.',
    seo: {
      metaTitle: 'Sewa Kost Putri UGM Pogung Murah Dekat Kampus FK | Stayease',
      metaDescription: 'Kost putri UGM Pogung Kidul Sleman Jogja. Fasilitas AC, kamar mandi dalam, free listrik, Wi-Fi kencang, 4 menit jalan ke kampus.',
      keywords: ['kost putri ugm', 'kost pogung ugm', 'kost dekat ugm yogyakarta', 'kost putri murah jogja'],
      schemaType: 'Accommodation'
    }
  },
  {
    id: 'villa-bohemia-canggu',
    slug: 'villa-bohemia-canggu-oasis-bali',
    title: 'The Bohemia Haven Tropical Villa & Co-Living',
    propertyType: 'villa',
    genderRestriction: 'Campur (Pria/Wanita)',
    rentalPeriods: ['harian', 'bulanan', 'tahunan'],
    address: 'Jl. Pantai Batu Bolong No. 88, Canggu',
    subDistrict: 'Canggu',
    city: 'Bali',
    province: 'Bali',
    coords: { lat: -8.6481, lng: 115.1328 },
    verifiedOfficial: true,
    hasVirtualTour: true,
    isAvailable: true,
    basePriceMonthly: 7800000,
    basePriceYearly: 85000000,
    discountPercent: 10,
    depositAmount: 1000000,
    electricityPolicy: 'include',
    serviceFee: 100000,
    images: [
      'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1000&q=80',
      'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1000&q=80',
      'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1000&q=80'
    ],
    virtualTour360: 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=1400&q=80',
    rating: 4.98,
    reviewCount: 46,
    roomSizeM2: 35,
    totalRooms: 8,
    availableRooms: 1,
    facilities: [
      'Private & Shared Swimming Pool',
      'Wi-Fi 150 Mbps Fiber Optic (Starlink Backup)',
      'En-suite Bathroom with Luxury Bathtub',
      'Modern Kitchenette with Espresso Machine',
      'Air Conditioner Inverter Daikin',
      'Co-Working Area with Herman Miller Chairs',
      'Daily Housekeeping & Linen Change',
      'Pet Friendly (Kucing & Anjing Kecil)'
    ],
    transitPoints: [
      { name: 'Pantai Batu Bolong', type: 'Bandara', distanceMeters: 900, walkMinutes: 10 },
      { name: 'Canggu Love Anchor Market', type: 'Mall', distanceMeters: 500, walkMinutes: 6 },
      { name: 'Bandara Internasional Ngurah Rai (DPS)', type: 'Bandara', distanceMeters: 18000, walkMinutes: 45 }
    ],
    units: [
      {
        id: 'bohemia-master-suite',
        name: 'Poolside Master Suite + Bathtub',
        sizeM2: 35,
        bedType: 'King Bed (180x200)',
        priceMonthly: 7800000,
        priceYearly: 85000000,
        availableCount: 1,
        isPromo: true,
        promoPriceMonthly: 7020000,
        photos: [
          'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?auto=format&fit=crop&w=800&q=80'
        ],
        features: ['Akses Kolam Renang Langsung', 'Bathtub Mewah', 'Espresso Maker', 'Balkon Tropis']
      }
    ],
    reviews: [
      {
        id: 'rev-301',
        userName: 'Alexander Wright',
        userAvatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
        userRole: 'Digital Nomad & Founder',
        rating: 5,
        cleanliness: 5,
        location: 5,
        facilities: 5,
        date: '02 Agustus 2026',
        comment: 'Best WFB (Work from Bali) villa in Batu Bolong! Starlink internet is blazing fast with 150 Mbps.',
        helpfulCount: 33
      }
    ],
    rules: [
      'Pet friendly dengan konfirmasi awal',
      'Dilarang memutar musik keras setelah pukul 23:00 WITA',
      'Dilarang merokok di dalam kamar'
    ],
    landlord: {
      id: 'landlord-wayan',
      name: 'Bli Wayan Sudarma',
      avatar: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=150&q=80',
      badge: 'Official Stayease',
      responseTime: '< 5 Menit',
      responseRate: 100,
      joinedYear: 2020,
      phoneWhatsapp: '+6281999887766',
      totalProperties: 4
    },
    description: 'Pengalaman Work from Bali (WFB) terbaik di Canggu dengan kolam renang privat dan atmosfer komunitas digital nomad.',
    seo: {
      metaTitle: 'Sewa Villa Bulanan di Canggu Bali Dekat Pantai Batu Bolong | Stayease',
      metaDescription: 'Sewa villa tropis bulanan di Batu Bolong Canggu Bali. Dilengkapi kolam renang, bathtub, Wi-Fi 150Mbps Starlink, pet friendly.',
      keywords: ['sewa villa canggu bulanan', 'villa batu bolong bali', 'coliving bali canggu', 'monthly rent villa bali'],
      schemaType: 'Accommodation'
    }
  },
  {
    id: 'apt-pakuwon-surabaya',
    slug: 'apartemen-pakuwon-mall-tower-orchid-surabaya',
    title: 'Apartemen Pakuwon Mall Tower Orchid Surabaya Barat',
    propertyType: 'apartemen',
    genderRestriction: 'Campur (Pria/Wanita)',
    rentalPeriods: ['bulanan', 'tahunan'],
    address: 'Jl. Mayjend Jonosewojo No. 2, Babatan, Wiyung',
    subDistrict: 'Surabaya Barat',
    city: 'Surabaya',
    province: 'Jawa Timur',
    coords: { lat: -7.2995, lng: 112.6756 },
    verifiedOfficial: true,
    hasVirtualTour: true,
    isAvailable: true,
    basePriceMonthly: 3850000,
    basePriceYearly: 42000000,
    discountPercent: 8,
    depositAmount: 1500000,
    electricityPolicy: 'token_mandiri',
    serviceFee: 65000,
    images: [
      'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80',
      'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1000&q=80',
      'https://images.unsplash.com/photo-1512918728675-ed5a9ecdebfd?auto=format&fit=crop&w=1000&q=80'
    ],
    virtualTour360: 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1400&q=80',
    rating: 4.89,
    reviewCount: 31,
    roomSizeM2: 26,
    totalRooms: 12,
    availableRooms: 3,
    facilities: [
      'Direct Access / Connect Langsung ke Pakuwon Mall',
      'Infinity Pool Sky Deck Lantai 33',
      'Fitness Center & Gym Mewah',
      'Full Furnished Interior Modern',
      'Smart Door Lock Access Card',
      'AC Daikin 1 PK + Smart TV 43"',
      'Kitchen Set + Kulkas 2 Pintu',
      'Water Heater & Mesin Cuci'
    ],
    transitPoints: [
      { name: 'Pakuwon Mall Superblock', type: 'Mall', distanceMeters: 50, walkMinutes: 1 },
      { name: 'Universitas Ciputra (UC)', type: 'Kampus', distanceMeters: 4500, walkMinutes: 12 },
      { name: 'Universitas Surabaya (UBAYA)', type: 'Kampus', distanceMeters: 8500, walkMinutes: 20 },
      { name: 'Gerbang Tol Kota Satelit', type: 'KRL', distanceMeters: 3200, walkMinutes: 10 }
    ],
    units: [
      {
        id: 'pakuwon-studio-deluxe',
        name: 'Studio Deluxe City View (High Floor)',
        sizeM2: 26,
        bedType: 'Queen Bed (160x200)',
        priceMonthly: 3850000,
        priceYearly: 42000000,
        availableCount: 3,
        isPromo: true,
        promoPriceMonthly: 3542000,
        photos: [
          'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=800&q=80'
        ],
        features: ['Connect Pakuwon Mall', 'City View Surabaya Barat', 'Mesin Cuci', 'Smart Lock']
      }
    ],
    reviews: [
      {
        id: 'rev-401',
        userName: 'Jessica Tanuwijaya',
        userAvatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
        userRole: 'Entrepreneur / Resident',
        rating: 5,
        cleanliness: 5,
        location: 5,
        facilities: 4.8,
        date: '05 Agustus 2026',
        comment: 'Tinggal turun lift langsung tembus ke Pakuwon Mall. Sangat praktis buat belanja kebutuhan harian dan cari makan.',
        helpfulCount: 19
      }
    ],
    rules: [
      'Penyewa wajib mendaftarkan kartu akses resident',
      'Dilarang merokok di area lorong gedung',
      'Tidak diperkenankan membawa hewan peliharaan besar'
    ],
    landlord: {
      id: 'landlord-stanley',
      name: 'Stanley Gunawan',
      avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',
      badge: 'Mitra Terverifikasi',
      responseTime: '< 15 Menit',
      responseRate: 98,
      joinedYear: 2022,
      phoneWhatsapp: '+6281777889900',
      totalProperties: 2
    },
    description: 'Sewa apartemen studio mewah di atas mall terbesar di Indonesia. Akses eksklusif ke pusat perbelanjaan, gym, dan sky pool.',
    seo: {
      metaTitle: 'Sewa Apartemen Pakuwon Mall Surabaya Barat Bulanan Full Furnished | Stayease',
      metaDescription: 'Sewa apartemen Pakuwon Mall Surabaya Tower Orchid. Direct connect ke mall, full furnished, infinity pool, fitness center. Booking aman.',
      keywords: ['sewa apartemen pakuwon mall', 'apartemen surabaya barat', 'apartemen orchid pakuwon', 'sewa studio surabaya'],
      schemaType: 'Apartment'
    }
  },
  {
    id: 'kost-putra-dago-itb',
    slug: 'kost-putra-dago-asri-itb-bandung',
    title: 'Kost Putra Dago Asri ITB Bandung',
    propertyType: 'kost_putra',
    genderRestriction: 'Khusus Putra',
    rentalPeriods: ['bulanan', 'tahunan'],
    address: 'Jl. Dago Asri Blok B No. 12, Coblong',
    subDistrict: 'Dago',
    city: 'Bandung',
    province: 'Jawa Barat',
    coords: { lat: -6.8821, lng: 107.6174 },
    verifiedOfficial: true,
    hasVirtualTour: true,
    isAvailable: true,
    basePriceMonthly: 1650000,
    basePriceYearly: 18000000,
    discountPercent: 10,
    depositAmount: 400000,
    electricityPolicy: 'include',
    serviceFee: 35000,
    images: [
      'https://images.unsplash.com/photo-1554995207-c18c203602cb?auto=format&fit=crop&w=1000&q=80',
      'https://images.unsplash.com/photo-1583847268964-b28dc8f51f92?auto=format&fit=crop&w=1000&q=80'
    ],
    virtualTour360: 'https://images.unsplash.com/photo-1554995207-c18c203602cb?auto=format&fit=crop&w=1400&q=80',
    rating: 4.88,
    reviewCount: 29,
    roomSizeM2: 16,
    totalRooms: 14,
    availableRooms: 2,
    facilities: [
      'Udara Sejuk Alami Dago Atas',
      'Kamar Mandi Dalam + Water Heater Gas',
      'Wi-Fi 100 Mbps Biznet',
      'Kasur Springbed Single 120x200',
      'Meja Belajar Kayu Solid + Kursi',
      'Dapur Bersama Lengkap',
      'Parkir Mobil & Motor Berkanopi',
      'Penjaga Kost 24 Jam'
    ],
    transitPoints: [
      { name: 'Kampus ITB Ganesha', type: 'Kampus', distanceMeters: 650, walkMinutes: 8 },
      { name: 'Universitas Padjadjaran (UNPAD Dipatiukur)', type: 'Kampus', distanceMeters: 1100, walkMinutes: 14 },
      { name: 'Simpang Dago & McD', type: 'Mall', distanceMeters: 400, walkMinutes: 5 }
    ],
    units: [
      {
        id: 'dago-standard-room',
        name: 'Standard Room Garden View',
        sizeM2: 16,
        bedType: 'Single Bed (120x200)',
        priceMonthly: 1650000,
        priceYearly: 18000000,
        availableCount: 2,
        isPromo: true,
        promoPriceMonthly: 1485000,
        photos: [
          'https://images.unsplash.com/photo-1554995207-c18c203602cb?auto=format&fit=crop&w=800&q=80'
        ],
        features: ['Include Listrik', 'Water Heater', 'Wi-Fi 100Mbps', 'Meja Belajar']
      }
    ],
    reviews: [
      {
        id: 'rev-501',
        userName: 'Fajar Nugraha',
        userAvatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80',
        userRole: 'Mahasiswa Teknik Elektro ITB',
        rating: 5,
        cleanliness: 4.9,
        location: 5,
        facilities: 4.8,
        date: '01 Agustus 2026',
        comment: 'Lokasi super strategis ke kampus ITB Ganesha. Udara Dago sejuk banget jadi nggak perlu AC, hemat listrik.',
        helpfulCount: 16
      }
    ],
    rules: [
      'Khusus mahasiswa / karyawan pria',
      'Gerbang tutup jam 24.00 malam (ada kunci cadangan)',
      'Menjaga ketenangan di lingkungan perumahan Dago Asri'
    ],
    landlord: {
      id: 'landlord-cecep',
      name: 'Pak Cecep Suryana',
      avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',
      badge: 'Mitra Terverifikasi',
      responseTime: '< 15 Menit',
      responseRate: 97,
      joinedYear: 2023,
      phoneWhatsapp: '+6281222334455',
      totalProperties: 1
    },
    description: 'Kost putra asri dan tenang di kawasan elit Dago Asri Bandung. Berjarak jalan kaki santai ke kampus ITB Ganesha dan pusat kuliner Simpang Dago.',
    seo: {
      metaTitle: 'Sewa Kost Putra Dago Dekat ITB Bandung Bulanan | Stayease',
      metaDescription: 'Kost putra Dago Asri dekat kampus ITB Bandung. Fasilitas water heater, Wi-Fi 100Mbps, include listrik, parkir mobil. Booking online mudah.',
      keywords: ['kost putra itb', 'kost dago asri bandung', 'kost dekat kampus itb ganesha', 'kost murah dago'],
      schemaType: 'Accommodation'
    }
  },
  {
    id: 'kontrakan-bsd-modern',
    slug: 'sewa-kontrakan-rumah-minimalis-bsd-serpong',
    title: 'Rumah Kontrakan Modern Cluster Vanya Park BSD',
    propertyType: 'kontrakan',
    genderRestriction: 'Campur (Pria/Wanita)',
    rentalPeriods: ['bulanan', 'tahunan'],
    address: 'Cluster Vanya Park Blok C8 No. 15, Pagedangan',
    subDistrict: 'BSD Serpong',
    city: 'Jakarta Selatan',
    province: 'Banten / Jabodetabek',
    coords: { lat: -6.3021, lng: 106.6342 },
    verifiedOfficial: true,
    hasVirtualTour: true,
    isAvailable: true,
    basePriceMonthly: 4900000,
    basePriceYearly: 55000000,
    discountPercent: 5,
    depositAmount: 2000000,
    electricityPolicy: 'token_mandiri',
    serviceFee: 80000,
    images: [
      'https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?auto=format&fit=crop&w=1000&q=80',
      'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1000&q=80',
      'https://images.unsplash.com/photo-1600565193348-f74bd3c7ccdf?auto=format&fit=crop&w=1000&q=80'
    ],
    virtualTour360: 'https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?auto=format&fit=crop&w=1400&q=80',
    rating: 4.91,
    reviewCount: 22,
    roomSizeM2: 72,
    totalRooms: 3,
    availableRooms: 1,
    facilities: [
      '2 Lantai (2 Kamar Tidur + 2 Kamar Mandi)',
      'Semi Furnished (AC 2 Unit, Kitchen Set, Sofa)',
      'Carport 1 Mobil + Kanopi Kokoh',
      'Taman Belakang Asri',
      'Clubhouse Cluster dengan Kolam Renang & Danau Alami',
      'Keamanan 24 Jam One-Gate System',
      'Dekat Akses Tol Serpong - Balaraja'
    ],
    transitPoints: [
      { name: 'AEON Mall BSD City', type: 'Mall', distanceMeters: 2500, walkMinutes: 20 },
      { name: 'ICE BSD (Indonesia Convention Exhibition)', type: 'Mall', distanceMeters: 1800, walkMinutes: 15 },
      { name: 'Stasiun KRL Cisauk (Intermoda)', type: 'KRL', distanceMeters: 4200, walkMinutes: 12 },
      { name: 'Prasetiya Mulya University BSD', type: 'Kampus', distanceMeters: 2100, walkMinutes: 18 }
    ],
    units: [
      {
        id: 'bsd-full-house',
        name: '1 Unit Rumah 2 Lantai (2KT + 2KM)',
        sizeM2: 72,
        bedType: '1 King + 1 Single Bed',
        priceMonthly: 4900000,
        priceYearly: 55000000,
        availableCount: 1,
        isPromo: true,
        promoPriceMonthly: 4655000,
        photos: [
          'https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?auto=format&fit=crop&w=800&q=80'
        ],
        features: ['2 Lantai', 'Carport Berkanopi', 'Clubhouse Kolam Renang', 'One Gate System']
      }
    ],
    reviews: [
      {
        id: 'rev-601',
        userName: 'Bagus Pratama',
        userAvatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=150&q=80',
        userRole: 'Keluarga Muda / WFH',
        rating: 5,
        cleanliness: 5,
        location: 4.8,
        facilities: 5,
        date: '28 Juli 2026',
        comment: 'Lingkungan cluster sangat asri dan aman untuk anak-anak bermain sepeda sore hari. Dekat banget ke ICE dan AEON Mall BSD.',
        helpfulCount: 11
      }
    ],
    rules: [
      'Cocok untuk keluarga atau sharing profesional',
      'Wajib lapor RT/Security cluster setempat',
      'Iuran pemeliharaan lingkungan (IPL) ditanggung penyewa'
    ],
    landlord: {
      id: 'landlord-hendra',
      name: 'Hendra Gunawan',
      avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=150&q=80',
      badge: 'Official Stayease',
      responseTime: '< 8 Menit',
      responseRate: 99,
      joinedYear: 2023,
      phoneWhatsapp: '+6281288990011',
      totalProperties: 2
    },
    description: 'Sewa rumah kontrakan minimalis modern di kawasan terfavorit BSD City. Menawarkan privasi, udara segar tepi danau alami Vanya Park, dan fasilitas olahraga clubhouse.',
    seo: {
      metaTitle: 'Sewa Rumah Kontrakan Cluster BSD City Serpong Bulanan/Tahunan | Stayease',
      metaDescription: 'Kontrakan 2 lantai di Vanya Park BSD Serpong. 2KT, 2KM, carport, semi furnished, security 24 jam. Dekat ICE & AEON Mall.',
      keywords: ['kontrakan bsd city', 'sewa rumah serpong tangerang', 'kontrakan murah bsd', 'sewa rumah 2 lantai bsd'],
      schemaType: 'SingleFamilyResidence'
    }
  },
  {
    id: 'kost-scbd-kuningan-signature',
    slug: 'kost-eksklusif-kuningan-scbd-jakarta-selatan',
    title: 'Kuningan Signature Executive Co-Living & Kost',
    propertyType: 'kost_campur',
    genderRestriction: 'Campur (Pria/Wanita)',
    rentalPeriods: ['bulanan', 'tahunan'],
    address: 'Jl. Karet Pedurenan No. 77, Setiabudi',
    subDistrict: 'Kuningan',
    city: 'Jakarta Selatan',
    province: 'DKI Jakarta',
    coords: { lat: -6.2215, lng: 106.8291 },
    verifiedOfficial: true,
    hasVirtualTour: true,
    isAvailable: true,
    basePriceMonthly: 3100000,
    basePriceYearly: 35000000,
    discountPercent: 10,
    depositAmount: 600000,
    electricityPolicy: 'token_mandiri',
    serviceFee: 50000,
    images: [
      'https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=1000&q=80',
      'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?auto=format&fit=crop&w=1000&q=80'
    ],
    virtualTour360: 'https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=1400&q=80',
    rating: 4.96,
    reviewCount: 41,
    roomSizeM2: 20,
    totalRooms: 18,
    availableRooms: 2,
    facilities: [
      'AC Daikin 1 PK',
      'Wi-Fi 150 Mbps',
      'Smart TV 40" 4K',
      'Kamar Mandi Dalam + Bathtub Mini',
      'Gym Mini & Co-Working Space',
      'Fingerprint Door Lock',
      'Parkir Mobil Luas'
    ],
    transitPoints: [
      { name: 'Halte LRT Rasuna Said', type: 'LRT', distanceMeters: 400, walkMinutes: 5 },
      { name: 'Lotte Shopping Avenue', type: 'Mall', distanceMeters: 800, walkMinutes: 10 }
    ],
    units: [
      {
        id: 'kuningan-suite-a',
        name: 'Executive King Suite (City View)',
        sizeM2: 20,
        bedType: 'King Bed (180x200)',
        priceMonthly: 3100000,
        availableCount: 2,
        isPromo: true,
        promoPriceMonthly: 2790000,
        photos: ['https://images.unsplash.com/photo-1505691938895-1758d7feb511?auto=format&fit=crop&w=800&q=80'],
        features: ['Smart TV 40"', 'Bathtub Mini', 'City View SCBD']
      }
    ],
    reviews: [
      {
        id: 'rev-701',
        userName: 'Reza Rahadian',
        userAvatar: 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?auto=format&fit=crop&w=150&q=80',
        userRole: 'Management Consultant di SCBD',
        rating: 5,
        cleanliness: 5,
        location: 5,
        facilities: 5,
        date: '18 Agustus 2026',
        comment: 'Akses ke SCBD dan Kuningan sangat dekat tanpa kena macet ganjil genap. Fasilitas standar hotel bintang 4.',
        helpfulCount: 28
      }
    ],
    rules: ['Akses 24 Jam dengan Smart Card', 'Dilarang merokok di dalam kamar'],
    landlord: {
      id: 'landlord-hendra',
      name: 'Ir. Hendra Gunawan',
      avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=150&q=80',
      badge: 'Official Stayease',
      responseTime: '< 8 Menit',
      responseRate: 99,
      joinedYear: 2022,
      phoneWhatsapp: '+6281298765432',
      totalProperties: 3
    },
    description: 'Co-living mewah untuk profesional muda di jantung segitiga emas Kuningan & SCBD Jakarta Selatan.',
    seo: {
      metaTitle: 'Sewa Kost Eksklusif Kuningan SCBD Jakarta Selatan | Stayease',
      metaDescription: 'Kost eksklusif Kuningan Setiabudi dekat SCBD. Fasilitas smart TV 4K, bathtub, gym mini, Wi-Fi 150 Mbps.',
      keywords: ['kost kuningan', 'kost scbd', 'kost eksklusif setiabudi', 'coliving jakarta selatan'],
      schemaType: 'Accommodation'
    }
  },
  {
    id: 'villa-ubud-sanctuary',
    slug: 'villa-ubud-sanctuary-jungle-retreat-bali',
    title: 'The Ubud Sanctuary Bamboo Eco Villa',
    propertyType: 'villa',
    genderRestriction: 'Campur (Pria/Wanita)',
    rentalPeriods: ['harian', 'bulanan', 'tahunan'],
    address: 'Jl. Raya Sayan No. 108, Ubud',
    subDistrict: 'Ubud',
    city: 'Bali',
    province: 'Bali',
    coords: { lat: -8.5069, lng: 115.2625 },
    verifiedOfficial: true,
    hasVirtualTour: true,
    isAvailable: true,
    basePriceMonthly: 6500000,
    basePriceYearly: 72000000,
    discountPercent: 15,
    depositAmount: 1000000,
    electricityPolicy: 'include',
    serviceFee: 80000,
    images: [
      'https://images.unsplash.com/photo-1540541338287-41700207dee6?auto=format&fit=crop&w=1000&q=80',
      'https://images.unsplash.com/photo-1582719478250-c89cae4dc85b?auto=format&fit=crop&w=1000&q=80'
    ],
    virtualTour360: 'https://images.unsplash.com/photo-1540541338287-41700207dee6?auto=format&fit=crop&w=1400&q=80',
    rating: 4.97,
    reviewCount: 35,
    roomSizeM2: 45,
    totalRooms: 6,
    availableRooms: 1,
    facilities: [
      'Private Jungle Infinity Pool',
      'Wi-Fi 150 Mbps Fiber Optic',
      'Semi-Outdoor Rain Shower & Bathtub',
      'Yoga Shala & Meditation Deck',
      'Dapur Lengkap + Blender Smoothie',
      'Daily Organic Breakfast Option'
    ],
    transitPoints: [
      { name: 'Ubud Art Market & Palace', type: 'Mall', distanceMeters: 2800, walkMinutes: 25 },
      { name: 'Campuhan Ridge Walk', type: 'Mall', distanceMeters: 2100, walkMinutes: 18 }
    ],
    units: [
      {
        id: 'ubud-jungle-suite',
        name: 'Jungle View Master Villa',
        sizeM2: 45,
        bedType: 'King Bed (180x200)',
        priceMonthly: 6500000,
        availableCount: 1,
        isPromo: true,
        promoPriceMonthly: 5525000,
        photos: ['https://images.unsplash.com/photo-1540541338287-41700207dee6?auto=format&fit=crop&w=800&q=80'],
        features: ['Kolam Renang Lembah', 'Yoga Deck', 'Bathtub Outdoor']
      }
    ],
    reviews: [
      {
        id: 'rev-801',
        userName: 'Clara Oswald',
        userAvatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=150&q=80',
        userRole: 'Remote Writer & Yoga Instructor',
        rating: 5,
        cleanliness: 5,
        location: 4.9,
        facilities: 5,
        date: '20 Agustus 2026',
        comment: 'Peaceful sanctuary with beautiful jungle views and sounds of Ayung river. Wi-Fi is surprisingly fast.',
        helpfulCount: 24
      }
    ],
    rules: ['Menjaga ketenangan alam Ubud', 'Dilarang merokok di dalam kamar bambu'],
    landlord: {
      id: 'landlord-wayan',
      name: 'Bli Wayan Sudarma',
      avatar: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=150&q=80',
      badge: 'Official Stayease',
      responseTime: '< 5 Menit',
      responseRate: 100,
      joinedYear: 2020,
      phoneWhatsapp: '+6281999887766',
      totalProperties: 4
    },
    description: 'Eco villa bambu alami dengan pemandangan lembah tropis Ubud Bali. Sangat cocok untuk retret ketenangan dan kerja jarak jauh.',
    seo: {
      metaTitle: 'Sewa Villa Bulanan Ubud Bali Kolam Renang Privat | Stayease',
      metaDescription: 'Sewa eco villa bulanan di Sayan Ubud Bali. Jungle view, private infinity pool, bathtub outdoor, Wi-Fi kencang.',
      keywords: ['villa ubud bulanan', 'sewa villa ubud bali', 'jungle villa ubud', 'eco villa bali'],
      schemaType: 'Accommodation'
    }
  }
];
