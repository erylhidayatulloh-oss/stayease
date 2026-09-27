import { CityHub } from '../types';

export const CITIES: CityHub[] = [
  {
    id: 'jakarta-selatan',
    name: 'Jakarta Selatan',
    slug: 'jakarta-selatan',
    province: 'DKI Jakarta',
    image: 'https://images.unsplash.com/photo-1555899434-94d1368aa7af?auto=format&fit=crop&w=800&q=80',
    description: 'Pusat bisnis, kafe hits, dan hunian eksklusif favorit profesional muda dan ekspatriat.',
    avgKostPrice: 2400000,
    avgApartmentPrice: 6500000,
    popularDistricts: ['Tebet', 'Kuningan', 'Setiabudi', 'SCBD', 'Kemang', 'Pancoran', 'Cilandak'],
    topUniversities: ['Universitas Bakrie', 'Universitas Prasetiya Mulya', 'Universitas Pancasila', 'UPN Veteran'],
    totalProperties: 1420,
    coords: { lat: -6.2615, lng: 106.8106 },
    faqs: [
      {
        q: 'Berapa rata-rata harga sewa kost di Jakarta Selatan?',
        a: 'Rata-rata sewa kost di Jakarta Selatan berkisar antara Rp 1.800.000 hingga Rp 4.500.000 per bulan tergantung fasilitas seperti AC, kamar mandi dalam, dan akses dekat stasiun MRT/KRL.'
      },
      {
        q: 'Daerah mana di Jakarta Selatan yang paling strategis untuk pekerja kantor?',
        a: 'Kuningan, Setiabudi, dan Tebet adalah area favorit karena memiliki akses langsung ke Sudirman-Thamrin, halte Transjakarta koridor 9, dan stasiun MRT.'
      },
      {
        q: 'Apakah sewa kost di Stayease sudah termasuk listrik?',
        a: 'Mayoritas kost eksklusif sudah include listrik, sedangkan beberapa kost menggunakan token mandiri agar penyewa dapat mengatur pemakaian sesuai kebutuhan.'
      }
    ]
  },
  {
    id: 'yogyakarta',
    name: 'Yogyakarta (Jogja)',
    slug: 'yogyakarta',
    province: 'DI Yogyakarta',
    image: 'https://images.unsplash.com/photo-1596402184320-417e7178b2cd?auto=format&fit=crop&w=800&q=80',
    description: 'Kota pelajar dengan suasana asri, biaya hidup terjangkau, dan pilihan kost mahasiswa terlengkap.',
    avgKostPrice: 1200000,
    avgApartmentPrice: 3800000,
    popularDistricts: ['Sleman (UGM)', 'Pogung', 'Seturan', 'Gejayan', 'Jakal (Kaliurang)', 'Kotabaru'],
    topUniversities: ['Universitas Gadjah Mada (UGM)', 'UNY', 'UPN Yogyakarta', 'Universitas Islam Indonesia (UII)', 'Atma Jaya'],
    totalProperties: 980,
    coords: { lat: -7.7956, lng: 110.3695 },
    faqs: [
      {
        q: 'Bagaimana mencari kost murah dekat kampus UGM?',
        a: 'Gunakan filter area "Pogung", "Karangmalang", atau "Sekip" di Stayease untuk menemukan kost dengan jarak jalan kaki kurang dari 500 meter ke kampus UGM.'
      },
      {
        q: 'Apakah ada kost putri dengan sistem keamanan 24 jam di Jogja?',
        a: 'Ya, semua properti bertanda "Official Stayease" di Sleman dan Jogja memiliki sistem keamanan CCTV 24 jam dan kartu akses digital.'
      }
    ]
  },
  {
    id: 'bali',
    name: 'Bali (Badung & Denpasar)',
    slug: 'bali',
    province: 'Bali',
    image: 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=800&q=80',
    description: 'Surga Work from Bali (WFB), villa tropis berkolam renang, dan co-living modern dengan Wi-Fi super kencang.',
    avgKostPrice: 2800000,
    avgApartmentPrice: 12500000,
    popularDistricts: ['Canggu', 'Seminyak', 'Pererenan', 'Uluwatu', 'Sanur', 'Kerobokan', 'Ubud'],
    topUniversities: ['Universitas Udayana (UNUD)', 'Warmadewa', 'ISI Denpasar'],
    totalProperties: 750,
    coords: { lat: -8.6500, lng: 115.1350 },
    faqs: [
      {
        q: 'Apakah villa di Bali bisa disewa bulanan via Stayease?',
        a: 'Tentu! Stayease menyediakan fitur sewa bulanan dan tahunan untuk villa dan guest house di Canggu, Seminyak, dan Sanur dengan diskon sewa jangka panjang hingga 25%.'
      },
      {
        q: 'Berapa kecepatan Wi-Fi di properti co-living Bali?',
        a: 'Semua listing co-living Stayease di Bali telah terverifikasi memiliki kecepatan internet minimal 100 Mbps dengan backup genset.'
      }
    ]
  },
  {
    id: 'bandung',
    name: 'Bandung',
    slug: 'bandung',
    province: 'Jawa Barat',
    image: 'https://images.unsplash.com/photo-1549488344-1f9b8d2bd1f3?auto=format&fit=crop&w=800&q=80',
    description: 'Kota kembang berudara sejuk, surganya mahasiswa ITB/Unpad dan pekerja kreatif.',
    avgKostPrice: 1500000,
    avgApartmentPrice: 4200000,
    popularDistricts: ['Dago', 'Dipatiukur', 'Ciumbuleuit (Unpar)', 'Buah Batu (Telkom)', 'Tubagus Ismail'],
    topUniversities: ['Institut Teknologi Bandung (ITB)', 'UNPAD', 'UNPAR', 'Telkom University', 'Maranatha'],
    totalProperties: 860,
    coords: { lat: -6.9175, lng: 107.6191 },
    faqs: [
      {
        q: 'Dimana lokasi kost paling dekat dengan ITB Ganesha?',
        a: 'Area Dago Bawah, Tubagus Ismail, dan Jalan Gelap Nyawang merupakan titik paling dekat dan favorit bagi mahasiswa ITB.'
      }
    ]
  },
  {
    id: 'surabaya',
    name: 'Surabaya',
    slug: 'surabaya',
    province: 'Jawa Timur',
    image: 'https://images.unsplash.com/photo-1588668214407-6ea9a6d8c272?auto=format&fit=crop&w=800&q=80',
    description: 'Kota metropolitan terbesar kedua di Indonesia dengan pusat bisnis timur dan kampus ternama.',
    avgKostPrice: 1600000,
    avgApartmentPrice: 4500000,
    popularDistricts: ['Gubeng', 'Surabaya Barat (Pakuwon)', 'Mulyorejo (UNAIR)', 'Sukolilo (ITS)', 'Wonokromo'],
    topUniversities: ['Universitas Airlangga (UNAIR)', 'ITS', 'Universitas Surabaya (UBAYA)', 'Petra Christian University'],
    totalProperties: 690,
    coords: { lat: -7.2575, lng: 112.7521 },
    faqs: [
      {
        q: 'Apakah ada apartemen dekat kampus ITS dan UNAIR?',
        a: 'Banyak pilihan apartemen seperti Grand Dharmahusada Lagoon dan Educity yang terhubung langsung dengan akses kampus dan pusat kuliner.'
      }
    ]
  }
];
