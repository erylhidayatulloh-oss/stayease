import React, { useState } from 'react';
import { 
  ArrowRight, 
  Banknote, 
  Calculator, 
  CheckCircle2, 
  PlusCircle, 
  ShieldCheck, 
  Sparkles, 
  TrendingUp 
} from 'lucide-react';
import { formatCompactRupiah, formatRupiah } from '../../utils/currency';

interface LandlordCtaProps {
  onOpenOwnerWizard: () => void;
}

export const LandlordCta: React.FC<LandlordCtaProps> = ({ onOpenOwnerWizard }) => {
  const [roomCount, setRoomCount] = useState<number>(10);
  const [roomPrice, setRoomPrice] = useState<number>(2000000);
  const [occupancyRate, setOccupancyRate] = useState<number>(90);

  const estimatedMonthlyRevenue = (roomCount * roomPrice * (occupancyRate / 100));
  const estimatedYearlyRevenue = estimatedMonthlyRevenue * 12;

  return (
    <section className="py-16 bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950 text-white relative overflow-hidden">
      
      {/* Background Glow */}
      <div className="absolute top-1/2 right-0 -mr-24 w-96 h-96 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none" />

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div className="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
          
          {/* Left Column: Value Proposition */}
          <div className="lg:col-span-6 space-y-6">
            <div className="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 rounded-full text-xs font-bold">
              <Sparkles className="w-3.5 h-3.5" />
              <span>Mitra Pemilik Kost & Apartemen Stayease</span>
            </div>

            <h2 className="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight leading-tight">
              Maksimalkan Okupansi Kost & Properti Anda Hingga <span className="text-emerald-400">98%</span>
            </h2>

            <p className="text-sm sm:text-base text-slate-300 leading-relaxed">
              Pasang iklan gratis di platform sewa resmi dengan jangkauan 2 juta+ pencari hunian aktif. Nikmati sistem manajemen tagihan otomatis, kontrak digital, dan pembayaran terjamin.
            </p>

            <div className="space-y-3 text-xs sm:text-sm text-slate-300">
              <div className="flex items-center gap-2.5">
                <CheckCircle2 className="w-5 h-5 text-emerald-400 shrink-0" />
                <span>Gratis biaya pasang iklan properti & foto 360° profesional</span>
              </div>
              <div className="flex items-center gap-2.5">
                <CheckCircle2 className="w-5 h-5 text-emerald-400 shrink-0" />
                <span>Pencairan dana sewa instan langsung ke rekening bank Anda</span>
              </div>
              <div className="flex items-center gap-2.5">
                <CheckCircle2 className="w-5 h-5 text-emerald-400 shrink-0" />
                <span>Verifikasi identitas KTP penyewa otomatis sebelum check-in</span>
              </div>
            </div>

            <div className="pt-2">
              <button
                onClick={onOpenOwnerWizard}
                className="px-6 py-3.5 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black text-sm rounded-2xl shadow-xl shadow-emerald-500/20 flex items-center gap-2 transition hover:scale-105"
              >
                <PlusCircle className="w-4 h-4" />
                <span>Mulai Pasang Iklan Gratis</span>
                <ArrowRight className="w-4 h-4" />
              </button>
            </div>
          </div>

          {/* Right Column: Interactive Revenue Estimator Calculator */}
          <div className="lg:col-span-6 bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-6 sm:p-8 shadow-2xl space-y-6">
            
            <div className="flex items-center justify-between pb-4 border-b border-slate-800">
              <div className="flex items-center gap-2.5">
                <div className="p-2 bg-emerald-500/20 text-emerald-400 rounded-xl">
                  <Calculator className="w-5 h-5" />
                </div>
                <div>
                  <h3 className="font-extrabold text-white text-base">Kalkulator Potensi Penghasilan</h3>
                  <p className="text-xs text-slate-400">Simulasikan omzet bulanan properti sewa Anda.</p>
                </div>
              </div>
              <span className="text-[10px] font-bold px-2 py-0.5 bg-emerald-500/20 text-emerald-300 rounded-full">
                Live Simulator
              </span>
            </div>

            {/* Controls */}
            <div className="space-y-4">
              {/* Slider 1: Room count */}
              <div className="space-y-1.5">
                <div className="flex justify-between text-xs font-semibold">
                  <span className="text-slate-400">Jumlah Kamar / Unit:</span>
                  <span className="text-emerald-400 font-bold">{roomCount} Kamar</span>
                </div>
                <input
                  type="range"
                  min="2"
                  max="50"
                  value={roomCount}
                  onChange={(e) => setRoomCount(Number(e.target.value))}
                  className="w-full accent-emerald-500 cursor-pointer"
                />
              </div>

              {/* Slider 2: Price per room */}
              <div className="space-y-1.5">
                <div className="flex justify-between text-xs font-semibold">
                  <span className="text-slate-400">Harga Sewa Rata-rata per Bulan:</span>
                  <span className="text-emerald-400 font-bold">{formatRupiah(roomPrice)}</span>
                </div>
                <input
                  type="range"
                  min="800000"
                  max="10000000"
                  step="200000"
                  value={roomPrice}
                  onChange={(e) => setRoomPrice(Number(e.target.value))}
                  className="w-full accent-emerald-500 cursor-pointer"
                />
              </div>

              {/* Slider 3: Occupancy */}
              <div className="space-y-1.5">
                <div className="flex justify-between text-xs font-semibold">
                  <span className="text-slate-400">Target Tingkat Okupansi:</span>
                  <span className="text-emerald-400 font-bold">{occupancyRate}%</span>
                </div>
                <input
                  type="range"
                  min="50"
                  max="100"
                  step="5"
                  value={occupancyRate}
                  onChange={(e) => setOccupancyRate(Number(e.target.value))}
                  className="w-full accent-emerald-500 cursor-pointer"
                />
              </div>
            </div>

            {/* Results Display Box */}
            <div className="p-4 rounded-2xl bg-gradient-to-r from-emerald-950/60 to-teal-950/60 border border-emerald-500/30 space-y-3">
              <div>
                <span className="text-[11px] font-bold text-emerald-300 uppercase tracking-wider">
                  Estimasi Omzet Bulanan:
                </span>
                <div className="text-2xl sm:text-3xl font-black text-emerald-400">
                  {formatRupiah(estimatedMonthlyRevenue)}
                </div>
              </div>

              <div className="pt-2 border-t border-emerald-500/20 flex items-center justify-between text-xs text-slate-300">
                <span>Potensi Omzet Tahunan:</span>
                <span className="font-extrabold text-white text-sm">
                  {formatCompactRupiah(estimatedYearlyRevenue)}/tahun
                </span>
              </div>
            </div>

            <button
              onClick={onOpenOwnerWizard}
              className="w-full py-3 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-xl border border-slate-700 transition text-center"
            >
              Daftarkan Properti Saya Sekarang
            </button>

          </div>

        </div>
      </div>
    </section>
  );
};
