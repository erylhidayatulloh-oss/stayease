/**
 * Indonesian Rupiah Formatting Utilities
 */

export function formatRupiah(amount: number): string {
  if (isNaN(amount) || amount === null || amount === undefined) {
    return 'Rp 0';
  }
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0,
  }).format(amount).replace('IDR', 'Rp');
}

export function formatCompactRupiah(amount: number): string {
  if (amount >= 1_000_000_000) {
    return `Rp ${(amount / 1_000_000_000).toFixed(1).replace('.0', '')} M`;
  }
  if (amount >= 1_000_000) {
    return `Rp ${(amount / 1_000_000).toFixed(1).replace('.0', '')} Jt`;
  }
  if (amount >= 1_000) {
    return `Rp ${(amount / 1_000).toFixed(0)} Rb`;
  }
  return formatRupiah(amount);
}

export function calculateDiscountPrice(original: number, discountPercent?: number): number {
  if (!discountPercent || discountPercent <= 0) return original;
  return Math.round(original * (1 - discountPercent / 100));
}

export function calculateBookingTotal(
  monthlyPrice: number,
  durationMonths: number,
  depositAmount: number,
  serviceFee: number,
  promoDiscount: number = 0,
  addOnsTotal: number = 0
) {
  const baseRent = monthlyPrice * durationMonths;
  const subtotal = baseRent + depositAmount + serviceFee + (addOnsTotal * durationMonths);
  const finalTotal = Math.max(0, subtotal - promoDiscount);
  
  return {
    baseRent,
    depositAmount,
    serviceFee,
    addOnsTotal: addOnsTotal * durationMonths,
    promoDiscount,
    finalTotal
  };
}
