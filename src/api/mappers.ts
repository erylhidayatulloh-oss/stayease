import {
  CityHub,
  Landlord,
  Property,
  PromoVoucher,
  Review,
  RoomUnit,
  TransitPoint,
} from '../types';

// --- Raw shapes coming back from Laravel (snake_case Eloquent JSON) ---

export interface ApiRoomUnit {
  id: number;
  name: string;
  size_m2: number;
  bed_type: string;
  price_monthly: number;
  price_yearly: number | null;
  price_daily: number | null;
  promo_price_monthly: number | null;
  is_promo: boolean;
  available_count: number;
  total_count: number;
  photos: string[] | null;
  features: string[] | null;
}

export interface ApiReview {
  id: number;
  rating: number;
  cleanliness_rating: number;
  security_rating: number;
  location_rating: number;
  comment: string;
  owner_reply: string | null;
  owner_replied_at: string | null;
  created_at: string;
  status: string;
  user?: { id: number; name: string; avatar: string | null } | null;
}

export interface ApiMitra {
  id: number;
  name: string;
  avatar: string | null;
  phone: string | null;
  ktp_verified_at: string | null;
  created_at: string;
}

export interface ApiCity {
  id: number;
  name: string;
  slug: string;
  province: string;
  image: string;
  description: string;
  avg_kost_price: number;
  avg_apt_price: number;
  popular_districts: string[] | null;
  top_universities: string[] | null;
  faqs: { q: string; a: string }[] | null;
  properties_count?: number;
}

export interface ApiProperty {
  id: number;
  slug: string;
  title: string;
  property_type: string;
  gender_restriction: string | null;
  address: string;
  sub_district: string;
  coords_lat: number;
  coords_lng: number;
  verified_official: boolean;
  has_virtual_tour: boolean;
  status: string;
  base_price_monthly: number;
  base_price_daily: number | null;
  base_price_yearly: number | null;
  discount_percent: number;
  deposit_amount: number;
  electricity_policy: string;
  service_fee: number;
  images: string[] | null;
  virtual_tour_url: string | null;
  facilities: string[] | null;
  rules: string[] | null;
  transit_points: Partial<TransitPoint>[] | null;
  description: string;
  rating_avg: number;
  reviews_count: number;
  city?: ApiCity | null;
  units?: ApiRoomUnit[];
  mitra?: ApiMitra | null;
  reviews?: ApiReview[];
}

export interface ApiPromo {
  code: string;
  title: string;
  discount_type: 'percentage' | 'fixed';
  discount_value: number;
  max_discount: number | null;
  min_transaction: number;
  badge: string;
  description: string;
  valid_until: string;
}

export interface ApiPaginated<T> {
  data: T[];
  current_page: number;
  last_page: number;
  total: number;
}

// --- Mappers ---

export function mapRoomUnit(u: ApiRoomUnit): RoomUnit {
  return {
    id: String(u.id),
    name: u.name,
    sizeM2: u.size_m2,
    bedType: u.bed_type,
    priceMonthly: u.price_monthly,
    priceYearly: u.price_yearly ?? undefined,
    priceDaily: u.price_daily ?? undefined,
    availableCount: u.available_count,
    isPromo: u.is_promo,
    promoPriceMonthly: u.promo_price_monthly ?? undefined,
    photos: u.photos ?? [],
    features: u.features ?? [],
  };
}

export function mapReview(r: ApiReview): Review {
  return {
    id: String(r.id),
    userName: r.user?.name ?? 'Penyewa Stayease',
    userAvatar: r.user?.avatar ?? 'https://images.unsplash.com/photo-1633332755192-727a05c4013d?auto=format&fit=crop&w=150&q=80',
    userRole: 'Penyewa Terverifikasi',
    rating: r.rating,
    cleanliness: r.cleanliness_rating,
    location: r.location_rating,
    facilities: r.security_rating,
    date: r.created_at,
    comment: r.comment,
    helpfulCount: 0,
    ownerReply: r.owner_reply
      ? { date: r.owner_replied_at ?? r.created_at, text: r.owner_reply }
      : undefined,
  };
}

export function mapLandlord(m: ApiMitra | null | undefined, propertyCount = 1): Landlord {
  if (!m) {
    return {
      id: 'stayease-official',
      name: 'Stayease Official',
      avatar: 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=150&q=80',
      badge: 'Official Stayease',
      responseTime: '< 15 Menit',
      responseRate: 98,
      joinedYear: 2023,
      phoneWhatsapp: '628119876543',
      totalProperties: propertyCount,
    };
  }
  return {
    id: String(m.id),
    name: m.name,
    avatar: m.avatar ?? 'https://images.unsplash.com/photo-1560250097-0b93528c311a?auto=format&fit=crop&w=150&q=80',
    badge: m.ktp_verified_at ? 'Mitra Terverifikasi' : 'Superhost',
    responseTime: '< 30 Menit',
    responseRate: 96,
    joinedYear: m.created_at ? new Date(m.created_at).getFullYear() : 2024,
    phoneWhatsapp: (m.phone ?? '628119876543').replace(/^0/, '62'),
    totalProperties: propertyCount,
  };
}

function derivePropertyType(type: string): Property['propertyType'] {
  const allowed: Property['propertyType'][] = ['kost_putra', 'kost_putri', 'kost_campur', 'apartemen', 'villa', 'kontrakan'];
  return (allowed.includes(type as any) ? type : 'kost_campur') as Property['propertyType'];
}

export function mapProperty(p: ApiProperty): Property {
  const units = (p.units ?? []).map(mapRoomUnit);
  const totalRooms = (p.units ?? []).reduce((sum, u) => sum + (u.total_count || 0), 0);
  const availableRooms = (p.units ?? []).reduce((sum, u) => sum + (u.available_count || 0), 0);

  const rentalPeriods: Property['rentalPeriods'] = [];
  if (p.base_price_daily) rentalPeriods.push('harian');
  if (p.base_price_monthly) rentalPeriods.push('bulanan');
  if (p.base_price_yearly) rentalPeriods.push('tahunan');

  const transitPoints: TransitPoint[] = (p.transit_points ?? []).map((t) => ({
    name: t.name ?? '',
    type: (t.type as TransitPoint['type']) ?? 'Kampus',
    distanceMeters: t.distanceMeters ?? 0,
    walkMinutes: t.walkMinutes ?? 0,
  }));

  return {
    id: String(p.id),
    slug: p.slug,
    title: p.title,
    propertyType: derivePropertyType(p.property_type),
    genderRestriction: (p.gender_restriction as Property['genderRestriction']) ?? undefined,
    rentalPeriods: rentalPeriods.length ? rentalPeriods : ['bulanan'],
    address: p.address,
    subDistrict: p.sub_district,
    city: p.city?.name ?? '-',
    province: p.city?.province ?? '-',
    coords: { lat: p.coords_lat, lng: p.coords_lng },
    verifiedOfficial: p.verified_official,
    hasVirtualTour: p.has_virtual_tour,
    isAvailable: p.status === 'active' && availableRooms > 0,
    basePriceMonthly: p.base_price_monthly,
    basePriceDaily: p.base_price_daily ?? undefined,
    basePriceYearly: p.base_price_yearly ?? undefined,
    discountPercent: p.discount_percent,
    depositAmount: p.deposit_amount,
    electricityPolicy: p.electricity_policy as Property['electricityPolicy'],
    serviceFee: p.service_fee,
    images: p.images ?? [],
    virtualTour360: p.virtual_tour_url ?? undefined,
    rating: p.rating_avg,
    reviewCount: p.reviews_count,
    roomSizeM2: units[0]?.sizeM2 ?? 0,
    totalRooms: totalRooms || units.length,
    availableRooms,
    facilities: p.facilities ?? [],
    transitPoints,
    units,
    reviews: (p.reviews ?? []).map(mapReview),
    rules: p.rules ?? [],
    landlord: mapLandlord(p.mitra),
    description: p.description,
    seo: {
      metaTitle: `${p.title} - Sewa ${derivePropertyType(p.property_type)} di ${p.city?.name ?? 'Indonesia'} | Stayease`,
      metaDescription: p.description?.slice(0, 155) ?? '',
      keywords: [p.title, p.sub_district, p.city?.name ?? ''].filter(Boolean),
      schemaType: p.property_type === 'apartemen' ? 'Apartment' : 'SingleFamilyResidence',
    },
  };
}

export function mapCity(c: ApiCity): CityHub {
  return {
    id: String(c.id),
    name: c.name,
    slug: c.slug,
    province: c.province,
    image: c.image,
    description: c.description,
    avgKostPrice: c.avg_kost_price,
    avgApartmentPrice: c.avg_apt_price,
    popularDistricts: c.popular_districts ?? [],
    topUniversities: c.top_universities ?? [],
    totalProperties: c.properties_count ?? 0,
    coords: { lat: -6.2, lng: 106.8 },
    faqs: c.faqs ?? [],
  };
}

export function mapPromo(p: ApiPromo): PromoVoucher {
  return {
    code: p.code,
    title: p.title,
    discountType: p.discount_type,
    discountValue: p.discount_value,
    maxDiscount: p.max_discount ?? undefined,
    minTransaction: p.min_transaction,
    badge: p.badge,
    description: p.description,
    validUntil: p.valid_until,
  };
}
