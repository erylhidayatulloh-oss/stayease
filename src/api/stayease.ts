import { apiGet, apiPost } from './client';
import {
  ApiCity,
  ApiPaginated,
  ApiProperty,
  ApiPromo,
  mapCity,
  mapProperty,
  mapPromo,
} from './mappers';
import { CityHub, PaymentMethod, Property, PromoVoucher, PropertyType } from '../types';

interface ApiEnvelope<T> {
  status: 'success' | 'error';
  data: T;
}

export async function fetchAllProperties(): Promise<Property[]> {
  const res = await apiGet<ApiEnvelope<ApiPaginated<ApiProperty>>>('/properties?per_page=48');
  return res.data.data.map(mapProperty);
}

export async function fetchPropertyBySlug(slug: string): Promise<Property> {
  const res = await apiGet<ApiEnvelope<ApiProperty>>(`/properties/${slug}`);
  return mapProperty(res.data);
}

export async function fetchAllCities(): Promise<CityHub[]> {
  const res = await apiGet<ApiEnvelope<ApiCity[]>>('/cities');
  return res.data.map(mapCity);
}

export async function fetchAllPromos(): Promise<PromoVoucher[]> {
  const res = await apiGet<ApiEnvelope<ApiPromo[]>>('/promos');
  return res.data.map(mapPromo);
}

export interface CreateBookingPayload {
  propertyId: string;
  roomUnitId: string;
  checkInDate: string;
  durationMonths: number;
  tenantName: string;
  tenantPhone: string;
  tenantEmail: string;
  tenantNik: string;
  emergencyName?: string;
  emergencyPhone?: string;
  paymentMethod: PaymentMethod;
  addOns?: {
    cleaningWeekly: boolean;
    parkingCar: boolean;
    laundryPartner: boolean;
  };
  promoCode?: string | null;
}

export interface BookingRecord {
  id: number;
  booking_code: string;
  status: string;
  payment_status: string;
  grand_total: number;
  base_rent_total: number;
  deposit_total: number;
  service_fee: number;
}

export async function createBooking(payload: CreateBookingPayload): Promise<BookingRecord> {
  const res = await apiPost<ApiEnvelope<BookingRecord>>('/bookings', {
    property_id: Number(payload.propertyId),
    room_unit_id: Number(payload.roomUnitId),
    check_in_date: payload.checkInDate,
    duration_months: payload.durationMonths,
    tenant_name: payload.tenantName,
    tenant_phone: payload.tenantPhone,
    tenant_email: payload.tenantEmail,
    tenant_nik: payload.tenantNik,
    emergency_name: payload.emergencyName,
    emergency_phone: payload.emergencyPhone,
    payment_method: payload.paymentMethod,
    addons: payload.addOns
      ? {
          cleaning_weekly: payload.addOns.cleaningWeekly,
          parking_car: payload.addOns.parkingCar,
          laundry_partner: payload.addOns.laundryPartner,
        }
      : undefined,
    promo_code: payload.promoCode || undefined,
  });
  return res.data;
}

export async function confirmBookingPayment(bookingId: number): Promise<BookingRecord> {
  const res = await apiPost<ApiEnvelope<BookingRecord>>(`/bookings/${bookingId}/confirm-payment`, {});
  return res.data;
}

// ---------------------------------------------------------------------------
// Wishlist, chat, and property-listing submission.
//
// The storefront has no full login system, so these are identified by the
// visitor's own email (captured once via booking/chat/listing forms and
// reused from then on — see src/utils/guestIdentity.ts). This mirrors how
// booking already worked and replaces what used to be localStorage-only /
// entirely-fake client-side behavior with real backend persistence.
// ---------------------------------------------------------------------------

export interface GuestIdentity {
  name: string;
  email: string;
  phone?: string;
}

export async function fetchWishlist(email: string): Promise<number[]> {
  const res = await apiGet<ApiEnvelope<number[]>>(`/wishlist?email=${encodeURIComponent(email)}`);
  return res.data;
}

export async function toggleWishlist(propertyId: number, identity: GuestIdentity): Promise<boolean> {
  const res = await apiPost<ApiEnvelope<{ property_id: number; in_wishlist: boolean }>>('/wishlist/toggle', {
    property_id: propertyId,
    email: identity.email,
    name: identity.name,
    phone: identity.phone,
  });
  return res.data.in_wishlist;
}

export interface ChatMessageRecord {
  id: number;
  from_visitor: boolean;
  content: string;
  time: string;
}

export async function fetchMessageThread(propertyId: string | number, email: string): Promise<ChatMessageRecord[]> {
  const res = await apiGet<ApiEnvelope<ChatMessageRecord[]>>(
    `/messages/thread?property_id=${propertyId}&email=${encodeURIComponent(email)}`
  );
  return res.data;
}

export async function sendChatMessage(
  propertyId: string | number,
  identity: GuestIdentity,
  content: string
): Promise<ChatMessageRecord> {
  const res = await apiPost<ApiEnvelope<ChatMessageRecord>>('/messages', {
    property_id: propertyId,
    email: identity.email,
    name: identity.name,
    phone: identity.phone,
    content,
  });
  return res.data;
}

export interface SubmitListingPayload {
  title: string;
  propertyType: PropertyType;
  city: string;
  subDistrict: string;
  address: string;
  roomCount: number;
  monthlyPrice: number;
  electricityPolicy: 'include' | 'token_mandiri';
  facilities: string[];
  ownerName: string;
  ownerWhatsapp: string;
  ownerEmail: string;
}

export async function submitPropertyListing(payload: SubmitListingPayload): Promise<{ id: number; title: string }> {
  const res = await apiPost<ApiEnvelope<{ id: number; title: string }>>('/listings', {
    title: payload.title,
    property_type: payload.propertyType,
    city: payload.city,
    sub_district: payload.subDistrict,
    address: payload.address,
    room_count: payload.roomCount,
    monthly_price: payload.monthlyPrice,
    electricity_policy: payload.electricityPolicy,
    facilities: payload.facilities,
    owner_name: payload.ownerName,
    owner_whatsapp: payload.ownerWhatsapp,
    owner_email: payload.ownerEmail,
  });
  return res.data;
}

// ---------------------------------------------------------------------------
// Reviews submitted directly from the property page.
//
// New feature: the property page only ever displayed reviews — writing one
// only existed inside the tenant dashboard, gated to one specific booking.
// This still enforces the same rule the page already states ("hanya
// penyewa yang telah menyelesaikan masa tinggal"): the backend checks for
// a real, paid booking under the visitor's email before accepting it.
// ---------------------------------------------------------------------------

export interface ReviewEligibility {
  eligible: boolean;
  reason: 'no_booking' | 'already_reviewed' | null;
}

export async function checkReviewEligibility(propertyId: string | number, email: string): Promise<ReviewEligibility> {
  const res = await apiGet<ApiEnvelope<ReviewEligibility>>(
    `/properties/${propertyId}/review-eligibility?email=${encodeURIComponent(email)}`
  );
  return res.data;
}

export interface SubmitReviewPayload {
  rating: number;
  cleanlinessRating: number;
  securityRating: number;
  locationRating: number;
  comment: string;
}

export interface SubmittedReviewRecord {
  id: number;
  rating: number;
  cleanliness_rating: number;
  security_rating: number;
  location_rating: number;
  comment: string;
  created_at: string;
  user: { id: number; name: string; avatar: string | null } | null;
}

export async function submitPropertyReview(
  propertyId: string | number,
  identity: GuestIdentity,
  payload: SubmitReviewPayload
): Promise<SubmittedReviewRecord> {
  const res = await apiPost<ApiEnvelope<SubmittedReviewRecord>>(`/properties/${propertyId}/reviews`, {
    email: identity.email,
    name: identity.name,
    rating: payload.rating,
    cleanliness_rating: payload.cleanlinessRating,
    security_rating: payload.securityRating,
    location_rating: payload.locationRating,
    comment: payload.comment,
  });
  return res.data;
}
