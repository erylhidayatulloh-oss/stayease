import { PromoVoucher } from '../types';

export const PROMO_VOUCHERS: PromoVoucher[] = [
  {
    code: 'STAYEASEHEMAT',
    title: 'Diskon Pengguna Baru Stayease',
    discountType: 'percentage',
    discountValue: 10,
    maxDiscount: 350000,
    minTransaction: 1000000,
    badge: '10% OFF',
    description: 'Potongan 10% s.d. Rp 350.000 untuk transaksi booking pertama di seluruh Indonesia.',
    validUntil: '31 Des 2026'
  },
  {
    code: 'MABA2026',
    title: 'Spesial Mahasiswa Baru',
    discountType: 'fixed',
    discountValue: 250000,
    minTransaction: 1200000,
    badge: 'HEMAT 250RB',
    description: 'Cashback langsung Rp 250.000 untuk kost dekat kampus UGM, UI, ITB, UNAIR, dan Telkom.',
    validUntil: '30 Nov 2026'
  },
  {
    code: 'QRISBERKAH',
    title: 'Ekstra Diskon QRIS / VA',
    discountType: 'fixed',
    discountValue: 100000,
    minTransaction: 1500000,
    badge: 'QRIS 100RB',
    description: 'Potongan Rp 100.000 otomatis saat membayar menggunakan QRIS atau Virtual Account Bank.',
    validUntil: '31 Des 2026'
  },
  {
    code: 'WFAINBALI',
    title: 'Long Stay Villa Bali Diskon',
    discountType: 'percentage',
    discountValue: 15,
    maxDiscount: 1500000,
    minTransaction: 5000000,
    badge: 'BALI 15%',
    description: 'Diskon sewa bulanan villa di Canggu, Seminyak, dan Sanur untuk remote worker.',
    validUntil: '31 Des 2026'
  }
];
