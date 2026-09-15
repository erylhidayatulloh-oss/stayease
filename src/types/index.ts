export type PropertyType = 
  | 'kost_putra' 
  | 'kost_putri' 
  | 'kost_campur' 
  | 'apartemen' 
  | 'villa' 
  | 'kontrakan';

export type RentalPeriod = 'harian' | 'bulanan' | 'tahunan';

export type PaymentMethod = 
  | 'qris' 
  | 'bca_va' 
  | 'mandiri_va' 
  | 'bni_va' 
  | 'bri_va' 
  | 'gopay' 
  | 'ovo' 
  | 'shopeepay' 
  | 'alfamart';

export interface TransitPoint {
  name: string;
  type: 'MRT' | 'LRT' | 'KRL' | 'Transjakarta' | 'Kampus' | 'Mall' | 'Bandara';
  distanceMeters: number;
  walkMinutes: number;
}

export interface RoomUnit {
  id: string;
  name: string;
  sizeM2: number;
  bedType: string;
  priceMonthly: number;
  priceYearly?: number;
  priceDaily?: number;
  availableCount: number;
  isPromo?: boolean;
  promoPriceMonthly?: number;
  photos: string[];
  features: string[];
}

export interface Review {
  id: string;
  userName: string;
  userAvatar: string;
  userRole: string; // e.g. 'Mahasiswa UGM', 'Software Engineer di SCBD'
  rating: number;
  cleanliness: number;
  location: number;
  facilities: number;
  date: string;
  comment: string;
  helpfulCount: number;
  ownerReply?: {
    date: string;
    text: string;
  };
}

export interface Landlord {
  id: string;
  name: string;
  avatar: string;
  badge: 'Official Stayease' | 'Superhost' | 'Mitra Terverifikasi';
  responseTime: string; // e.g., '< 10 Menit'
  responseRate: number; // e.g., 99
  joinedYear: number;
  phoneWhatsapp: string;
  totalProperties: number;
}

export interface Property {
  id: string;
  slug: string;
  title: string;
  propertyType: PropertyType;
  genderRestriction?: 'Khusus Putra' | 'Khusus Putri' | 'Campur (Pria/Wanita)';
  rentalPeriods: RentalPeriod[];
  address: string;
  subDistrict: string; // e.g., 'Tebet', 'Setiabudi', 'Canggu', 'Gubeng', 'Dago'
  city: string; // e.g., 'Jakarta Selatan', 'Bandung', 'Bali', 'Surabaya', 'Yogyakarta'
  province: string;
  coords: {
    lat: number;
    lng: number;
  };
  verifiedOfficial: boolean;
  hasVirtualTour: boolean;
  isAvailable: boolean;
  
  // Pricing
  basePriceMonthly: number;
  basePriceDaily?: number;
  basePriceYearly?: number;
  discountPercent?: number;
  depositAmount: number;
  electricityPolicy: 'include' | 'token_mandiri';
  serviceFee: number;
  
  images: string[];
  virtualTour360?: string;
  
  // Specs & Ratings
  rating: number;
  reviewCount: number;
  roomSizeM2: number;
  totalRooms: number;
  availableRooms: number;
  
  // Amenities & Transit
  facilities: string[];
  transitPoints: TransitPoint[];
  units: RoomUnit[];
  reviews: Review[];
  rules: string[];
  
  landlord: Landlord;
  description: string;
  
  seo: {
    metaTitle: string;
    metaDescription: string;
    keywords: string[];
    schemaType: 'SingleFamilyResidence' | 'Apartment' | 'Accommodation' | 'RealEstateListing';
  };
}

export interface CityHub {
  id: string;
  name: string;
  slug: string;
  province: string;
  image: string;
  description: string;
  avgKostPrice: number;
  avgApartmentPrice: number;
  popularDistricts: string[];
  topUniversities: string[];
  totalProperties: number;
  coords: {
    lat: number;
    lng: number;
  };
  faqs: { q: string; a: string }[];
}

export interface PromoVoucher {
  code: string;
  title: string;
  discountType: 'percentage' | 'fixed';
  discountValue: number; // e.g. 15 for 15% or 300000 for Rp 300rb
  maxDiscount?: number;
  minTransaction: number;
  badge: string;
  description: string;
  validUntil: string;
}

export interface FilterState {
  searchQuery: string;
  city: string;
  propertyType: PropertyType | 'all';
  rentalPeriod: RentalPeriod;
  minPrice: number;
  maxPrice: number;
  gender: 'all' | 'putra' | 'putri' | 'campur';
  facilities: string[];
  verifiedOnly: boolean;
  virtualTourOnly: boolean;
  sortBy: 'recommended' | 'price_low' | 'price_high' | 'rating' | 'newest';
}

export interface BookingState {
  property: Property;
  unit: RoomUnit;
  checkInDate: string;
  durationMonths: number;
  tenantName: string;
  tenantPhone: string;
  tenantEmail: string;
  tenantNikKtp: string;
  gender: 'Pria' | 'Wanita';
  emergencyContactName: string;
  emergencyContactPhone: string;
  appliedPromo?: PromoVoucher | null;
  paymentMethod: PaymentMethod;
  addOns: {
    cleaningWeekly: boolean; // Rp 150.000 / bln
    parkingCar: boolean;     // Rp 250.000 / bln
    laundryPartner: boolean; // Rp 200.000 / bln
  };
  signatureDataUrl?: string;
  isAgreedTerms: boolean;
}
