import React, { useState } from 'react';
import { 
  Check, 
  Copy, 
  Gift, 
  Sparkles, 
  Tag, 
  Timer 
} from 'lucide-react';
import { PROMO_VOUCHERS } from '../../data/promos';
import { PromoVoucher } from '../../types';

interface PromoCarouselProps {
  onApplyPromo?: (voucher: PromoVoucher) => void;
}

export const PromoCarousel: React.FC<PromoCarouselProps> = ({ onApplyPromo }) => {
  const [copiedCode, setCopiedCode] = useState<string | null>(null);

  const handleCopy = (voucher: PromoVoucher) => {
    navigator.clipboard.writeText(voucher.code);
    setCopiedCode(voucher.code);
    if (onApplyPromo) onApplyPromo(voucher);
    setTimeout(() => setCopiedCode(null), 2500);
  };

  return (
    <section className="py-8 bg-slate-50 border-y border-slate-200/70">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div className="flex items-center justify-between mb-4">
          <div className="flex items-center gap-2">
            <div className="p-1.5 bg-rose-100 text-rose-600 rounded-lg">
              <Gift className="w-4 h-4" />
            </div>
            <div>
              <h3 className="text-base font-bold text-slate-900">Voucher & Promo Spesial Hari Ini</h3>
              <p className="text-xs text-slate-500">Klaim kode promo untuk potongan sewa langsung di Stayease.</p>
            </div>
          </div>
          <span className="hidden sm:flex items-center gap-1 text-xs font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-full border border-emerald-200">
            <Sparkles className="w-3 h-3" /> Cashback Otomatis
          </span>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
          {PROMO_VOUCHERS.map((v) => (
            <div
              key={v.code}
              className="bg-white rounded-2xl p-4 border border-slate-200 shadow-subtle hover:shadow-card transition flex flex-col justify-between relative overflow-hidden group"
            >
              {/* Top Accent Ribbon */}
              <div className="absolute top-0 right-0 bg-gradient-to-l from-emerald-600 to-teal-600 text-white text-[10px] font-black px-3 py-0.5 rounded-bl-xl shadow-sm">
                {v.badge}
              </div>

              <div>
                <div className="text-[11px] font-bold text-slate-400 mb-1 flex items-center gap-1">
                  <Tag className="w-3 h-3 text-emerald-600" />
                  <span>KODE PROMO</span>
                </div>

                <h4 className="font-extrabold text-slate-900 text-sm mb-1.5 leading-snug">
                  {v.title}
                </h4>

                <p className="text-xs text-slate-600 leading-relaxed mb-3">
                  {v.description}
                </p>
              </div>

              <div className="pt-3 border-t border-slate-100 flex items-center justify-between gap-2">
                <div className="flex items-center gap-1 text-[10px] text-slate-400">
                  <Timer className="w-3 h-3 text-slate-400" />
                  <span>s.d {v.validUntil}</span>
                </div>

                <button
                  onClick={() => handleCopy(v)}
                  className={`px-3 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1 transition ${
                    copiedCode === v.code
                      ? 'bg-emerald-600 text-white'
                      : 'bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700'
                  }`}
                >
                  {copiedCode === v.code ? (
                    <>
                      <Check className="w-3.5 h-3.5" />
                      Tersalin!
                    </>
                  ) : (
                    <>
                      <Copy className="w-3.5 h-3.5" />
                      <span className="font-mono">{v.code}</span>
                    </>
                  )}
                </button>
              </div>
            </div>
          ))}
        </div>

      </div>
    </section>
  );
};
