import React, { useState } from 'react';
import { 
  ArrowRight, 
  Calendar, 
  Check, 
  CreditCard, 
  Percent, 
  ShieldCheck, 
  Sparkles, 
  Tag, 
  Zap 
} from 'lucide-react';
import { PROMO_VOUCHERS } from '../../data/promos';
import { Property, PromoVoucher, RoomUnit } from '../../types';
import { calculateBookingTotal, calculateDiscountPrice, formatRupiah } from '../../utils/currency';

interface BookingStickyBarProps {
  property: Property;
  selectedUnit: RoomUnit;
  onStartBooking: (params: {
    durationMonths: number;
    checkInDate: string;
    appliedPromo: PromoVoucher | null;
  }) => void;
}

export const BookingStickyBar: React.FC<BookingStickyBarProps> = ({
  property,
  selectedUnit,
  onStartBooking
}) => {
  const [durationMonths, setDurationMonths] = useState<number>(1);
  const [checkInDate, setCheckInDate] = useState<string>('2026-09-01');
  const [promoCodeInput, setPromoCodeInput] = useState<string>('STAYEASEHEMAT');
  const [appliedPromo, setAppliedPromo] = useState<PromoVoucher | null>(PROMO_VOUCHERS[0]);
  const [promoError, setPromoError] = useState<string | null>(null);

  const activeMonthlyPrice = selectedUnit.isPromo && selectedUnit.promoPriceMonthly 
    ? selectedUnit.promoPriceMonthly 
    : calculateDiscountPrice(property.basePriceMonthly, property.discountPercent);

  const promoDiscountAmount = appliedPromo 
    ? (appliedPromo.discountType === 'percentage' 
        ? Math.min(appliedPromo.maxDiscount || 500000, Math.round(activeMonthlyPrice * durationMonths * (appliedPromo.discountValue / 100)))
        : appliedPromo.discountValue)
    : 0;

  const totals = calculateBookingTotal(
    activeMonthlyPrice,
    durationMonths,
    property.depositAmount,
    property.serviceFee,
    promoDiscountAmount
  );

  const handleApplyPromo = (e: React.FormEvent) => {
    e.preventDefault();
    setPromoError(null);
    const found = PROMO_VOUCHERS.find(p => p.code.toUpperCase() === promoCodeInput.trim().toUpperCase());
    if (found) {
      setAppliedPromo(found);
    } else {
      setPromoError('Kode voucher tidak valid');
      setAppliedPromo(null);
    }
  };

  return (
    <div className="bg-white rounded-3xl border border-slate-200/80 shadow-elevated p-5 sm:p-6 space-y-5 sticky top-24">
      
      {/* Pricing Header */}
      <div className="pb-4 border-b border-slate-100 flex items-end justify-between">
        <div>
          <span className="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">
            Harga Sewa Terpilih
          </span>
          <div className="flex items-baseline gap-1">
            <span className="text-2xl font-black text-slate-900">
              {formatRupiah(activeMonthlyPrice)}
            </span>
            <span className="text-xs text-slate-400 font-semibold">/bulan</span>
          </div>
        </div>

        <div className="text-right">
          <span className="px-2.5 py-1 bg-emerald-50 text-emerald-700 font-bold text-xs rounded-full border border-emerald-200">
            {selectedUnit.name.split(' ')[0]} Room
          </span>
        </div>
      </div>

      {/* Inputs: Check-in Date & Duration */}
      <div className="space-y-3">
        {/* Check-in Date */}
        <div className="p-3 bg-slate-50 rounded-2xl border border-slate-200/80">
          <label className="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">
            Tanggal Masuk (Check-in)
          </label>
          <div className="flex items-center gap-2">
            <Calendar className="w-4 h-4 text-emerald-600 shrink-0" />
            <input
              type="date"
              value={checkInDate}
              onChange={(e) => setCheckInDate(e.target.value)}
              className="w-full bg-transparent text-xs font-bold text-slate-800 focus:outline-none cursor-pointer"
            />
          </div>
        </div>

        {/* Duration Selection Buttons */}
        <div>
          <label className="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5">
            Durasi Waktu Sewa
          </label>
          <div className="grid grid-cols-4 gap-1.5">
            {[
              { months: 1, label: '1 Bln' },
              { months: 3, label: '3 Bln' },
              { months: 6, label: '6 Bln' },
              { months: 12, label: '1 Thn' }
            ].map((d) => (
              <button
                key={d.months}
                type="button"
                onClick={() => setDurationMonths(d.months)}
                className={`py-2 px-1 rounded-xl text-xs font-bold transition text-center ${
                  durationMonths === d.months
                    ? 'bg-slate-900 text-white shadow-sm'
                    : 'bg-slate-100 hover:bg-slate-200 text-slate-700'
                }`}
              >
                {d.label}
              </button>
            ))}
          </div>
        </div>

        {/* Promo Voucher Input */}
        <div className="pt-1">
          <form onSubmit={handleApplyPromo} className="flex gap-2">
            <div className="relative flex-1">
              <Tag className="w-3.5 h-3.5 text-emerald-600 absolute left-3 top-1/2 -translate-y-1/2" />
              <input
                type="text"
                placeholder="Kode Promo (STAYEASEHEMAT)"
                value={promoCodeInput}
                onChange={(e) => setPromoCodeInput(e.target.value)}
                className="w-full pl-8 pr-3 py-2 bg-slate-50 text-xs font-bold rounded-xl border border-slate-200 uppercase focus:outline-none focus:border-emerald-500"
              />
            </div>
            <button
              type="submit"
              className="px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold rounded-xl transition"
            >
              Gunakan
            </button>
          </form>

          {appliedPromo && (
            <div className="mt-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-800 rounded-lg text-[11px] font-semibold flex items-center justify-between border border-emerald-200">
              <span className="flex items-center gap-1">
                <Sparkles className="w-3 h-3 text-emerald-600" />
                <span>Kupon <strong>{appliedPromo.code}</strong> aktif</span>
              </span>
              <span className="font-bold">-{formatRupiah(promoDiscountAmount)}</span>
            </div>
          )}

          {promoError && (
            <span className="text-[11px] text-rose-600 font-semibold block mt-1">
              {promoError}
            </span>
          )}
        </div>

      </div>

      {/* Detailed Price Calculation Breakdown */}
      <div className="p-4 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-2 text-xs">
        <div className="flex justify-between text-slate-600">
          <span>Sewa ({durationMonths} Bulan):</span>
          <span className="font-semibold text-slate-900">{formatRupiah(totals.baseRent)}</span>
        </div>

        <div className="flex justify-between text-slate-600">
          <span>Deposit Jaminan (Dikembalikan):</span>
          <span className="font-semibold text-slate-900">{formatRupiah(totals.depositAmount)}</span>
        </div>

        <div className="flex justify-between text-slate-600">
          <span>Biaya Layanan & Kontrak Digital:</span>
          <span className="font-semibold text-slate-900">{formatRupiah(totals.serviceFee)}</span>
        </div>

        {totals.promoDiscount > 0 && (
          <div className="flex justify-between text-emerald-700 font-semibold">
            <span>Diskon Promo Stayease:</span>
            <span>-{formatRupiah(totals.promoDiscount)}</span>
          </div>
        )}

        <div className="pt-2 border-t border-slate-200 flex justify-between items-baseline font-black text-slate-900 text-sm">
          <span>Total Pembayaran Awal:</span>
          <span className="text-emerald-700 text-base">{formatRupiah(totals.finalTotal)}</span>
        </div>
      </div>

      {/* Action Button */}
      <button
        onClick={() => onStartBooking({ durationMonths, checkInDate, appliedPromo })}
        className="w-full py-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 transition hover:scale-[1.02] active:scale-[0.98]"
      >
        <Sparkles className="w-4 h-4" />
        <span>Ajukan Sewa / Booking Instan</span>
        <ArrowRight className="w-4 h-4" />
      </button>

      <div className="flex items-center justify-center gap-2 text-[11px] text-slate-400 font-medium text-center">
        <ShieldCheck className="w-3.5 h-3.5 text-emerald-600" />
        <span>Garansi Uang Kembali 100% jika kamar tidak sesuai</span>
      </div>

    </div>
  );
};
