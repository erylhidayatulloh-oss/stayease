import React, { useState, useRef, useEffect, useMemo } from 'react';
import confetti from 'canvas-confetti';
import { 
  AlertCircle, 
  ArrowLeft, 
  ArrowRight, 
  Calendar, 
  Check, 
  CheckCircle2, 
  Copy, 
  CreditCard, 
  Download, 
  FileText, 
  HelpCircle, 
  Home, 
  Lock, 
  PenTool, 
  Phone, 
  QrCode, 
  RotateCcw, 
  ShieldCheck, 
  Sparkles, 
  User, 
  X, 
  Zap 
} from 'lucide-react';
import { BookingState, PaymentMethod, Property, PromoVoucher, RoomUnit } from '../../types';
import { calculateBookingTotal, calculateDiscountPrice, formatRupiah } from '../../utils/currency';
import { createBooking, confirmBookingPayment, ApiError } from '../../api';
import { saveGuestIdentity } from '../../utils/guestIdentity';

interface BookingModalProps {
  isOpen: boolean;
  onClose: () => void;
  property: Property;
  unit: RoomUnit;
  initialDuration: number;
  initialCheckIn: string;
  initialPromo: PromoVoucher | null;
}

export const BookingModal: React.FC<BookingModalProps> = ({
  isOpen,
  onClose,
  property,
  unit,
  initialDuration,
  initialCheckIn,
  initialPromo
}) => {
  const [step, setStep] = useState<1 | 2 | 3 | 4 | 5>(1);

  // Form State
  const [tenantName, setTenantName] = useState('Rian Pratama');
  const [tenantPhone, setTenantPhone] = useState('081234567890');
  const [tenantEmail, setTenantEmail] = useState('rian.pratama@gmail.com');
  const [tenantNik, setTenantNik] = useState('3174051203980004');
  const [tenantGender, setTenantGender] = useState<'Pria' | 'Wanita'>('Pria');
  const [emergencyName, setEmergencyName] = useState('Agus Santoso (Ayah)');
  const [emergencyPhone, setEmergencyPhone] = useState('081398765432');

  // Booking details
  const [durationMonths, setDurationMonths] = useState(initialDuration || 1);
  // Bug fix: this used to be a hardcoded '2026-09-01', which silently
  // becomes an already-past date over time and makes the backend's
  // `after_or_equal:today` check on check_in_date fail no matter what —
  // especially for the "Jump straight to payment" shortcut below, which
  // skips this field entirely and relies purely on the default. Computed
  // a few days out from "now" instead so it's always valid.
  const getDefaultCheckInDate = () => {
    const d = new Date();
    d.setDate(d.getDate() + 3);
    return d.toISOString().split('T')[0];
  };
  const [checkInDate, setCheckInDate] = useState(initialCheckIn || getDefaultCheckInDate());

  // New feature: the periode a tenant selects (check-in date + duration)
  // never showed an explicit checkout date anywhere — only "Sewa X Bulan
  // terhitung mulai <checkInDate>", leaving the tenant to calculate the
  // end date themselves. Derived here so it always matches whatever
  // check-in date / duration is currently selected.
  const checkOutDate = useMemo(() => {
    const d = new Date(checkInDate + 'T00:00:00');
    if (Number.isNaN(d.getTime())) return '';
    d.setMonth(d.getMonth() + durationMonths);
    return d.toISOString().split('T')[0];
  }, [checkInDate, durationMonths]);

  const formatLongDate = (isoDate: string) => {
    if (!isoDate) return '-';
    const d = new Date(isoDate + 'T00:00:00');
    if (Number.isNaN(d.getTime())) return isoDate;
    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
  };

  // Add-ons
  const [cleaningWeekly, setCleaningWeekly] = useState(false);
  const [parkingCar, setParkingCar] = useState(false);
  const [laundryPartner, setLaundryPartner] = useState(false);

  // Payment State
  const [paymentMethod, setPaymentMethod] = useState<PaymentMethod>('qris');
  const [isCopiedVa, setIsCopiedVa] = useState(false);
  const [isCopiedAmount, setIsCopiedAmount] = useState(false);
  const [qrisTimer, setQrisTimer] = useState(895); // 14 mins 55s
  const [isSimulatingPayment, setIsSimulatingPayment] = useState(false);

  // Signature Canvas
  const canvasRef = useRef<HTMLCanvasElement | null>(null);
  const [isDrawing, setIsDrawing] = useState(false);
  const [hasSignature, setHasSignature] = useState(false);
  const [isAgreedTerms, setIsAgreedTerms] = useState(true);

  // Real backend booking record (created lazily the first time the tenant
  // actually tries to pay, or skips straight to the contract step).
  const [apiBooking, setApiBooking] = useState<import('../../api').BookingRecord | null>(null);
  const [bookingError, setBookingError] = useState<string | null>(null);

  const ensureBookingCreated = async () => {
    if (apiBooking) return apiBooking;
    const record = await createBooking({
      propertyId: property.id,
      roomUnitId: unit.id,
      checkInDate,
      durationMonths,
      tenantName,
      tenantPhone,
      tenantEmail,
      tenantNik,
      emergencyName,
      emergencyPhone,
      paymentMethod,
      addOns: {
        cleaningWeekly,
        parkingCar,
        laundryPartner,
      },
      promoCode: initialPromo?.code ?? null,
    });
    setApiBooking(record);
    // Now that we know who the tenant really is, remember it so Wishlist
    // and Chat (which also need a real identity — see guestIdentity.ts)
    // don't have to ask again on this device.
    saveGuestIdentity({ name: tenantName, email: tenantEmail, phone: tenantPhone });
    return record;
  };

  // Generated Mock Booking Code (fallback only, real code comes from apiBooking)
  const [bookingRefCode] = useState(() => 'STY-' + Math.random().toString(36).substring(2, 9).toUpperCase());

  // Totals
  const activeMonthlyPrice = unit.isPromo && unit.promoPriceMonthly 
    ? unit.promoPriceMonthly 
    : calculateDiscountPrice(property.basePriceMonthly, property.discountPercent);

  const addOnsMonthlyTotal = (cleaningWeekly ? 150000 : 0) + (parkingCar ? 250000 : 0) + (laundryPartner ? 200000 : 0);

  const promoDiscountAmount = initialPromo 
    ? (initialPromo.discountType === 'percentage' 
        ? Math.min(initialPromo.maxDiscount || 500000, Math.round(activeMonthlyPrice * durationMonths * (initialPromo.discountValue / 100)))
        : initialPromo.discountValue)
    : 0;

  const totals = calculateBookingTotal(
    activeMonthlyPrice,
    durationMonths,
    property.depositAmount,
    property.serviceFee,
    promoDiscountAmount,
    addOnsMonthlyTotal
  );

  // Countdown timer for QRIS
  useEffect(() => {
    if (step === 3 && paymentMethod === 'qris') {
      const interval = setInterval(() => {
        setQrisTimer(prev => (prev > 0 ? prev - 1 : 0));
      }, 1000);
      return () => clearInterval(interval);
    }
  }, [step, paymentMethod]);

  if (!isOpen) return null;

  // Copy helper
  const copyToClipboard = (text: string, type: 'va' | 'amount') => {
    navigator.clipboard.writeText(text);
    if (type === 'va') {
      setIsCopiedVa(true);
      setTimeout(() => setIsCopiedVa(false), 2000);
    } else {
      setIsCopiedAmount(true);
      setTimeout(() => setIsCopiedAmount(false), 2000);
    }
  };

  // Canvas drawing handlers
  const startDrawing = (e: React.MouseEvent<HTMLCanvasElement> | React.TouchEvent<HTMLCanvasElement>) => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;
    
    setIsDrawing(true);
    setHasSignature(true);
    const rect = canvas.getBoundingClientRect();
    const x = 'touches' in e ? e.touches[0].clientX - rect.left : e.clientX - rect.left;
    const y = 'touches' in e ? e.touches[0].clientY - rect.top : e.clientY - rect.top;
    ctx.beginPath();
    ctx.moveTo(x, y);
    ctx.lineWidth = 2.5;
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#047857';
  };

  const draw = (e: React.MouseEvent<HTMLCanvasElement> | React.TouchEvent<HTMLCanvasElement>) => {
    if (!isDrawing) return;
    const canvas = canvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;

    const rect = canvas.getBoundingClientRect();
    const x = 'touches' in e ? e.touches[0].clientX - rect.left : e.clientX - rect.left;
    const y = 'touches' in e ? e.touches[0].clientY - rect.top : e.clientY - rect.top;
    ctx.lineTo(x, y);
    ctx.stroke();
  };

  const stopDrawing = () => {
    setIsDrawing(false);
  };

  const clearSignature = () => {
    const canvas = canvasRef.current;
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    if (!ctx) return;
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    setHasSignature(false);
  };

  const handleInstantPay = async () => {
    setIsSimulatingPayment(true);
    setBookingError(null);
    try {
      const record = await ensureBookingCreated();
      const confirmed = await confirmBookingPayment(record.id);
      setApiBooking(confirmed);
      setStep(4); // Move to contract signature
    } catch (err) {
      setBookingError(err instanceof ApiError ? err.message : 'Gagal memproses pembayaran. Silakan coba lagi.');
    } finally {
      setIsSimulatingPayment(false);
    }
  };

  const handleFinishBooking = () => {
    setStep(5);
    try {
      confetti({
        particleCount: 120,
        spread: 80,
        origin: { y: 0.6 }
      });
    } catch (e) {}
  };

  const formatTimerMinutes = (seconds: number) => {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    return `${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
  };

  // Mock VA numbers based on bank
  const getVaNumber = () => {
    switch (paymentMethod) {
      case 'bca_va': return '88012' + tenantPhone.replace(/^0/, '');
      case 'mandiri_va': return '89508' + tenantPhone.replace(/^0/, '');
      case 'bni_va': return '98811' + tenantPhone.replace(/^0/, '');
      case 'bri_va': return '12800' + tenantPhone.replace(/^0/, '');
      default: return '88012' + tenantPhone.replace(/^0/, '');
    }
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-slate-950/75 backdrop-blur-md animate-fadeIn">
      <div className="bg-white w-full max-w-3xl rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[94vh]">
        
        {/* Modal Top Header */}
        <div className="p-4 sm:p-5 bg-slate-900 text-white flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="p-2 bg-emerald-500/20 text-emerald-400 rounded-xl">
              <Sparkles className="w-5 h-5" />
            </div>
            <div>
              <h3 className="text-base sm:text-lg font-black leading-tight">
                {step === 5 ? 'Sewa Berhasil Dikonfirmasi! 🎉' : 'Pemesanan & Pembayaran Cepat'}
              </h3>
              <p className="text-xs text-slate-400 truncate max-w-xs sm:max-w-md">
                {property.title} • {unit.name}
              </p>
            </div>
          </div>
          
          <button
            onClick={onClose}
            className="p-2 text-slate-400 hover:text-white rounded-xl hover:bg-slate-800 transition"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* Step Progress Bar (1 to 4) */}
        {step < 5 && (
          <div className="bg-slate-50 px-4 sm:px-6 py-3 border-b border-slate-200">
            <div className="flex items-center justify-between text-xs font-bold gap-2">
              <button 
                onClick={() => setStep(1)}
                className={`flex items-center gap-1.5 transition ${step === 1 ? 'text-emerald-800' : 'text-slate-500 hover:text-slate-800'}`}
              >
                <span className={`w-5 h-5 rounded-full flex items-center justify-center text-[10px] ${step >= 1 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600'}`}>1</span>
                <span className="hidden sm:inline">Data Penyewa</span>
              </button>
              
              <span className="text-slate-300">→</span>
              
              <button 
                onClick={() => setStep(2)}
                className={`flex items-center gap-1.5 transition ${step === 2 ? 'text-emerald-800' : 'text-slate-500 hover:text-slate-800'}`}
              >
                <span className={`w-5 h-5 rounded-full flex items-center justify-center text-[10px] ${step >= 2 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600'}`}>2</span>
                <span className="hidden sm:inline">Layanan</span>
              </button>
              
              <span className="text-slate-300">→</span>
              
              <button 
                onClick={() => setStep(3)}
                className={`flex items-center gap-1.5 transition ${step === 3 ? 'text-emerald-800' : 'text-slate-500 hover:text-slate-800'}`}
              >
                <span className={`w-5 h-5 rounded-full flex items-center justify-center text-[10px] ${step >= 3 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600'}`}>3</span>
                <span className="text-emerald-700 font-extrabold">Pembayaran (Mudah)</span>
              </button>
              
              <span className="text-slate-300">→</span>
              
              <button 
                onClick={() => setStep(4)}
                className={`flex items-center gap-1.5 transition ${step === 4 ? 'text-emerald-800' : 'text-slate-500 hover:text-slate-800'}`}
              >
                <span className={`w-5 h-5 rounded-full flex items-center justify-center text-[10px] ${step >= 4 ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-600'}`}>4</span>
                <span className="hidden sm:inline">Kontrak</span>
              </button>
            </div>
          </div>
        )}

        {/* Modal Body */}
        <div className="p-5 sm:p-6 overflow-y-auto flex-1 space-y-6">
          
          {/* STEP 1: Tenant Information */}
          {step === 1 && (
            <div className="space-y-5 animate-fadeIn">
              
              {/* Fast Track / Express Auto-Fill Banner */}
              <div className="p-4 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl border border-emerald-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
                <div className="flex items-center gap-2.5 text-emerald-900">
                  <ShieldCheck className="w-5 h-5 text-emerald-600 shrink-0" />
                  <div>
                    <span className="font-extrabold block">Profil Penyewa Terverifikasi Resmi</span>
                    <span className="text-[11px] text-emerald-700">Data telah diisi otomatis dari akun Stayease Anda.</span>
                  </div>
                </div>

                <button
                  type="button"
                  onClick={() => setStep(3)} // Jump straight to payment!
                  className="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-sm text-xs transition shrink-0 flex items-center gap-1"
                >
                  <span>⚡ Express: Langsung ke Pembayaran</span>
                  <ArrowRight className="w-3.5 h-3.5" />
                </button>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div className="space-y-1">
                  <label className="block font-bold text-slate-700">Nama Lengkap Sesuai KTP *</label>
                  <input
                    type="text"
                    value={tenantName}
                    onChange={(e) => setTenantName(e.target.value)}
                    className="w-full p-2.5 bg-slate-50 font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500"
                  />
                </div>

                <div className="space-y-1">
                  <label className="block font-bold text-slate-700">Nomor Induk Kependudukan (NIK KTP) *</label>
                  <input
                    type="text"
                    value={tenantNik}
                    onChange={(e) => setTenantNik(e.target.value)}
                    className="w-full p-2.5 bg-slate-50 font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 font-mono"
                  />
                </div>

                <div className="space-y-1">
                  <label className="block font-bold text-slate-700">Nomor WhatsApp Aktif *</label>
                  <input
                    type="tel"
                    value={tenantPhone}
                    onChange={(e) => setTenantPhone(e.target.value)}
                    className="w-full p-2.5 bg-slate-50 font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500"
                  />
                </div>

                <div className="space-y-1">
                  <label className="block font-bold text-slate-700">Alamat Email *</label>
                  <input
                    type="email"
                    value={tenantEmail}
                    onChange={(e) => setTenantEmail(e.target.value)}
                    className="w-full p-2.5 bg-slate-50 font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500"
                  />
                </div>

                <div className="space-y-1">
                  <label className="block font-bold text-slate-700">Tanggal Masuk (Check-in)</label>
                  <input
                    type="date"
                    value={checkInDate}
                    min={new Date().toISOString().split('T')[0]}
                    onChange={(e) => setCheckInDate(e.target.value)}
                    className="w-full p-2.5 bg-slate-50 font-bold rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500"
                  />
                </div>

                <div className="space-y-1">
                  <label className="block font-bold text-slate-700">Durasi Sewa</label>
                  <select
                    value={durationMonths}
                    onChange={(e) => setDurationMonths(Number(e.target.value))}
                    className="w-full p-2.5 bg-slate-50 font-bold rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 cursor-pointer"
                  >
                    <option value={1}>1 Bulan</option>
                    <option value={3}>3 Bulan (Hemat 5%)</option>
                    <option value={6}>6 Bulan (Hemat 10%)</option>
                    <option value={12}>12 Bulan (1 Tahun - Hemat 15%)</option>
                  </select>
                </div>
              </div>

              {/* New feature: explicit checkout date matching the selected
                  check-in date + duration, so the tenant doesn't have to
                  calculate it themselves. */}
              <div className="p-3 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center justify-between text-xs">
                <span className="text-emerald-800 font-semibold">Periode sewa Anda:</span>
                <span className="font-black text-emerald-900">
                  {formatLongDate(checkInDate)} &rarr; {formatLongDate(checkOutDate)}
                </span>
              </div>

              {/* Price Overview Card */}
              <div className="p-4 bg-slate-100 rounded-2xl flex items-center justify-between text-xs">
                <div>
                  <span className="text-slate-500 block">Total Estimasi Tagihan:</span>
                  <span className="text-base font-black text-emerald-800">{formatRupiah(totals.finalTotal)}</span>
                </div>
                <button
                  type="button"
                  onClick={() => setStep(2)}
                  className="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow transition flex items-center gap-1"
                >
                  <span>Lanjut ke Opsi Layanan</span>
                  <ArrowRight className="w-3.5 h-3.5" />
                </button>
              </div>

            </div>
          )}

          {/* STEP 2: Optional Add-ons */}
          {step === 2 && (
            <div className="space-y-4 animate-fadeIn">
              <div>
                <h4 className="text-sm font-extrabold text-slate-900">Pilih Layanan Tambahan (Opsional)</h4>
                <p className="text-xs text-slate-500">Nikmati kenyamanan ekstra dengan layanan pembersihan, parkir, dan laundry.</p>
              </div>

              <div className="space-y-3">
                <label className={`p-3.5 rounded-2xl border flex items-center justify-between cursor-pointer transition ${
                  cleaningWeekly ? 'bg-emerald-50 border-emerald-500' : 'bg-slate-50 border-slate-200 hover:border-slate-300'
                }`}>
                  <div className="flex items-center gap-3 text-xs">
                    <input
                      type="checkbox"
                      checked={cleaningWeekly}
                      onChange={(e) => setCleaningWeekly(e.target.checked)}
                      className="rounded accent-emerald-600 w-4 h-4"
                    />
                    <div>
                      <h5 className="font-bold text-slate-900">Pembersihan Kamar Mingguan (Weekly Cleaning)</h5>
                      <p className="text-[11px] text-slate-500">Pembersihan kamar mandi, sapu, pel, dan sprei 1x seminggu.</p>
                    </div>
                  </div>
                  <span className="text-xs font-extrabold text-slate-900 shrink-0">+Rp 150.000/bln</span>
                </label>

                <label className={`p-3.5 rounded-2xl border flex items-center justify-between cursor-pointer transition ${
                  parkingCar ? 'bg-emerald-50 border-emerald-500' : 'bg-slate-50 border-slate-200 hover:border-slate-300'
                }`}>
                  <div className="flex items-center gap-3 text-xs">
                    <input
                      type="checkbox"
                      checked={parkingCar}
                      onChange={(e) => setParkingCar(e.target.checked)}
                      className="rounded accent-emerald-600 w-4 h-4"
                    />
                    <div>
                      <h5 className="font-bold text-slate-900">Slot Parkir Mobil Berkanopi</h5>
                      <p className="text-[11px] text-slate-500">Reserved parking lot khusus mobil dengan kartu akses gate RFID.</p>
                    </div>
                  </div>
                  <span className="text-xs font-extrabold text-slate-900 shrink-0">+Rp 250.000/bln</span>
                </label>

                <label className={`p-3.5 rounded-2xl border flex items-center justify-between cursor-pointer transition ${
                  laundryPartner ? 'bg-emerald-50 border-emerald-500' : 'bg-slate-50 border-slate-200 hover:border-slate-300'
                }`}>
                  <div className="flex items-center gap-3 text-xs">
                    <input
                      type="checkbox"
                      checked={laundryPartner}
                      onChange={(e) => setLaundryPartner(e.target.checked)}
                      className="rounded accent-emerald-600 w-4 h-4"
                    />
                    <div>
                      <h5 className="font-bold text-slate-900">Langganan Laundry Kiloan (30 Kg/Bulan)</h5>
                      <p className="text-[11px] text-slate-500">Antar jemput laundry cuci setrika wangi langsung di depan kamar.</p>
                    </div>
                  </div>
                  <span className="text-xs font-extrabold text-slate-900 shrink-0">+Rp 200.000/bln</span>
                </label>
              </div>

              <div className="flex items-center justify-between pt-3">
                <button
                  type="button"
                  onClick={() => setStep(1)}
                  className="px-4 py-2 text-slate-600 font-bold text-xs hover:text-slate-900"
                >
                  &larr; Kembali
                </button>

                <button
                  type="button"
                  onClick={() => setStep(3)}
                  className="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow text-xs transition flex items-center gap-1.5"
                >
                  <span>Lanjut ke Pembayaran Mudah</span>
                  <ArrowRight className="w-3.5 h-3.5" />
                </button>
              </div>
            </div>
          )}

          {/* STEP 3: Seamless Indonesian Payment Gateway */}
          {step === 3 && (
            <div className="space-y-6 animate-fadeIn">
              
              {/* Grand Total Summary Banner */}
              <div className="p-4 bg-gradient-to-r from-slate-950 via-slate-900 to-emerald-950 text-white rounded-3xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-lg">
                <div>
                  <span className="text-[10px] text-emerald-400 font-bold uppercase tracking-wider block">Total Tagihan Final (Siap Bayar)</span>
                  <div className="text-2xl font-black text-white mt-0.5">
                    {formatRupiah(totals.finalTotal)}
                  </div>
                  <span className="text-[11px] text-slate-400">Sewa {durationMonths} Bulan • Bebas Biaya Admin Tambahan</span>
                </div>

                <button
                  type="button"
                  onClick={() => copyToClipboard(totals.finalTotal.toString(), 'amount')}
                  className="px-3 py-1.5 bg-white/10 hover:bg-white/20 text-emerald-300 rounded-xl text-xs font-bold transition flex items-center gap-1"
                >
                  <Copy className="w-3.5 h-3.5" />
                  <span>{isCopiedAmount ? 'Nominal Disalin!' : 'Salin Jumlah'}</span>
                </button>
              </div>

              {/* Payment Methods Selection Grid */}
              <div className="space-y-2">
                <label className="block text-xs font-bold text-slate-700">Pilih Jalur Pembayaran Resmi Stayease:</label>
                
                <div className="grid grid-cols-2 sm:grid-cols-4 gap-2">
                  {[
                    { id: 'qris' as PaymentMethod, label: 'QRIS Instan', badge: 'Tercepat ⚡', icon: '📱' },
                    { id: 'bca_va' as PaymentMethod, label: 'BCA Virtual Account', badge: 'Otomatis', icon: '🏦' },
                    { id: 'mandiri_va' as PaymentMethod, label: 'Mandiri VA', badge: 'Otomatis', icon: '🏦' },
                    { id: 'gopay' as PaymentMethod, label: 'GoPay / OVO', badge: 'e-Wallet', icon: '💳' },
                    { id: 'bni_va' as PaymentMethod, label: 'BNI VA', badge: 'Otomatis', icon: '🏦' },
                    { id: 'bri_va' as PaymentMethod, label: 'BRI VA', badge: 'Otomatis', icon: '🏦' },
                    { id: 'shopeepay' as PaymentMethod, label: 'ShopeePay', badge: 'Cashback', icon: '🛍️' },
                    { id: 'alfamart' as PaymentMethod, label: 'Alfamart / Indomaret', badge: 'Gerai', icon: '🏪' },
                  ].map((m) => (
                    <button
                      key={m.id}
                      type="button"
                      onClick={() => setPaymentMethod(m.id)}
                      className={`p-3 rounded-2xl border text-left transition flex flex-col justify-between ${
                        paymentMethod === m.id
                          ? 'border-emerald-600 bg-emerald-50 text-emerald-950 ring-2 ring-emerald-500/30'
                          : 'border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700'
                      }`}
                    >
                      <div className="flex items-center justify-between">
                        <span className="text-base">{m.icon}</span>
                        <span className="text-[9px] font-black px-1.5 py-0.2 bg-white rounded-md text-emerald-800 border border-emerald-200">
                          {m.badge}
                        </span>
                      </div>
                      <h5 className="font-bold text-xs mt-2 leading-tight">{m.label}</h5>
                    </button>
                  ))}
                </div>
              </div>

              {/* Payment UI Details */}
              {paymentMethod === 'qris' ? (
                /* QRIS INTERFACE */
                <div className="p-5 bg-slate-900 text-white rounded-3xl flex flex-col sm:flex-row items-center justify-between gap-6 shadow-md">
                  <div className="bg-white p-3 rounded-2xl shrink-0 text-center shadow-lg">
                    <img
                      src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=00020101021226590014ID.LINKAJA.WWW011893600911002233445502150000000000000000303UMI51440014ID.DOKU.WWW0215000000000000000520458125303360540723500005802ID5918STAYEASE+INDONESIA6007JAKARTA61051219062070703A0163048B5D"
                      alt="QRIS Stayease"
                      className="w-40 h-40 mx-auto rounded-xl"
                    />
                    <span className="text-[10px] font-extrabold text-slate-800 mt-1 block">NMID: ID102026883921</span>
                  </div>

                  <div className="space-y-3 flex-1 text-xs">
                    <div className="flex items-center justify-between pb-2 border-b border-slate-800">
                      <span className="text-emerald-400 font-bold flex items-center gap-1">
                        <QrCode className="w-4 h-4" />
                        <span>Scan Semua Bank & e-Wallet (BCA, GoPay, OVO, Dana)</span>
                      </span>
                      <span className="font-mono text-amber-400 font-black">
                        ⏳ {formatTimerMinutes(qrisTimer)}
                      </span>
                    </div>

                    <p className="text-slate-300 leading-relaxed text-[11px]">
                      Buka aplikasi m-Banking atau e-Wallet favorit Anda, pilih menu <strong>Bayar / QRIS</strong>, lalu arahkan kamera ke kode QR di samping.
                    </p>

                    {/* 1-Click Instant Success Simulation */}
                    <button
                      type="button"
                      disabled={isSimulatingPayment}
                      onClick={handleInstantPay}
                      className="w-full py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black rounded-xl text-xs shadow-lg transition flex items-center justify-center gap-2"
                    >
                      {isSimulatingPayment ? (
                        <span>Memverifikasi Pembayaran...</span>
                      ) : (
                        <>
                          <span>⚡ Simulasi Pembayaran QRIS Sukses (Instan Lunas)</span>
                          <CheckCircle2 className="w-4 h-4" />
                        </>
                      )}
                    </button>
                  </div>
                </div>
              ) : (
                /* VIRTUAL ACCOUNT & E-WALLET INTERFACE */
                <div className="p-5 bg-slate-900 text-white rounded-3xl space-y-4 shadow-md text-xs">
                  <div className="flex items-center justify-between pb-3 border-b border-slate-800">
                    <span className="text-emerald-400 font-bold uppercase">
                      Nomor Virtual Account {paymentMethod.replace('_', ' ').toUpperCase()}
                    </span>
                    <span className="px-2 py-0.5 bg-emerald-950 border border-emerald-500/40 text-emerald-300 rounded text-[10px] font-mono font-bold">
                      VERIFIKASI OTOMATIS
                    </span>
                  </div>

                  <div className="p-4 bg-slate-800 rounded-2xl flex items-center justify-between gap-4">
                    <div>
                      <span className="text-[10px] text-slate-400 block font-bold">Nomor Rekening Virtual:</span>
                      <div className="text-xl sm:text-2xl font-mono font-black text-emerald-400 tracking-wider">
                        {getVaNumber()}
                      </div>
                    </div>

                    <button
                      type="button"
                      onClick={() => copyToClipboard(getVaNumber(), 'va')}
                      className="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl font-black text-xs transition shadow flex items-center gap-1.5"
                    >
                      <Copy className="w-3.5 h-3.5" />
                      <span>{isCopiedVa ? 'Tersalin!' : 'Salin Nomor'}</span>
                    </button>
                  </div>

                  <div className="p-3 bg-slate-800/60 rounded-xl text-[11px] text-slate-300 space-y-1">
                    <div className="font-bold text-slate-200">Petunjuk Pembayaran:</div>
                    <p>1. Transfer tepat sejumlah <strong>{formatRupiah(totals.finalTotal)}</strong> ke nomor VA di atas.</p>
                    <p>2. Transaksi akan terkonfirmasi otomatis oleh sistem dalam 1-2 detik tanpa upload bukti transfer.</p>
                  </div>

                  {/* 1-Click Instant VA Verification Simulation */}
                  <button
                    type="button"
                    disabled={isSimulatingPayment}
                    onClick={handleInstantPay}
                    className="w-full py-3 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-black rounded-xl text-xs shadow-lg transition flex items-center justify-center gap-2"
                  >
                    {isSimulatingPayment ? (
                      <span>Memeriksa Mutasi Bank...</span>
                    ) : (
                      <>
                        <span>⚡ Konfirmasi Pembayaran VA (Simulasi Lunas)</span>
                        <CheckCircle2 className="w-4 h-4" />
                      </>
                    )}
                  </button>
                </div>
              )}

              <div className="flex items-center justify-between text-xs">
                <button
                  type="button"
                  // Bug fix: this used to send the user to Step 2 ("Layanan
                  // Tambahan" — add-ons only), which has no check-in date
                  // field at all, so "Ubah Data Sewa" (Change Rental Data)
                  // looked broken. Step 1 is where check-in date, duration,
                  // and tenant data actually live.
                  onClick={() => setStep(1)}
                  className="text-slate-500 hover:text-slate-900 font-bold"
                >
                  &larr; Ubah Data Sewa
                </button>

                <button
                  type="button"
                  onClick={async () => {
                    setBookingError(null);
                    try {
                      await ensureBookingCreated();
                      setStep(4);
                    } catch (err) {
                      setBookingError(err instanceof ApiError ? err.message : 'Gagal membuat pemesanan. Silakan coba lagi.');
                    }
                  }}
                  className="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold rounded-xl transition"
                >
                  Lewati ke Tanda Tangan Kontrak &rarr;
                </button>
              </div>

              {bookingError && (
                <div className="p-3 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700 font-semibold">
                  {bookingError}
                </div>
              )}

            </div>
          )}

          {/* STEP 4: Digital Rental Contract & Signature */}
          {step === 4 && (
            <div className="space-y-5 animate-fadeIn">
              
              <div className="p-4 bg-emerald-50 rounded-2xl border border-emerald-200 text-xs text-emerald-950 flex items-center justify-between">
                <div className="flex items-center gap-2">
                  <CheckCircle2 className="w-5 h-5 text-emerald-600 shrink-0" />
                  <div>
                    <strong className="block">Pembayaran Berhasil Diverifikasi!</strong>
                    <span className="text-[11px] text-emerald-700">Langkah terakhir: Tanda tangani Surat Perjanjian Sewa Digital yang sah secara hukum.</span>
                  </div>
                </div>
                <span className="px-2.5 py-1 bg-emerald-600 text-white font-black text-[10px] rounded-full">LUNAS</span>
              </div>

              {/* Digital Contract Preview Box */}
              <div className="p-4 bg-slate-50 rounded-2xl border border-slate-200 text-xs space-y-3 max-h-48 overflow-y-auto leading-relaxed text-slate-700 font-medium">
                <div className="font-bold text-center uppercase text-slate-900 pb-1 border-b border-slate-200">
                  SURAT PERJANJIAN SEWA MENYEWA HUNIAN (DIGITAL AGREEMENT)
                </div>
                <p>
                  Pada hari ini, disepakati perjanjian sewa antara <strong>{property.landlord.name}</strong> (Pihak Pertama / Pemilik) dan <strong>{tenantName}</strong> (Pihak Kedua / Penyewa) atas unit <strong>{unit.name}</strong> di <strong>{property.title}</strong>, {property.address} selama <strong>{durationMonths} Bulan</strong>, terhitung mulai <strong>{formatLongDate(checkInDate)}</strong> sampai dengan <strong>{formatLongDate(checkOutDate)}</strong>.
                </p>
                <p>
                  Biaya sewa total sebesar <strong>{formatRupiah(totals.finalTotal)}</strong> telah dibayarkan lunas melalui platform resmi Stayease. Uang jaminan deposit sebesar <strong>{formatRupiah(property.depositAmount)}</strong> akan dikembalikan penuh setelah masa sewa selesai.
                </p>
              </div>

              {/* HTML5 Canvas E-Signature Pad */}
              <div className="space-y-2">
                <div className="flex items-center justify-between">
                  <label className="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                    <PenTool className="w-3.5 h-3.5 text-emerald-600" />
                    <span>Goreskan Tanda Tangan Anda di Kotak Berikut:</span>
                  </label>
                  
                  <div className="flex items-center gap-2">
                    <button
                      type="button"
                      onClick={() => {
                        setHasSignature(true);
                        const canvas = canvasRef.current;
                        if (canvas) {
                          const ctx = canvas.getContext('2d');
                          if (ctx) {
                            ctx.font = 'bold 24px "Plus Jakarta Sans", sans-serif';
                            ctx.fillStyle = '#047857';
                            ctx.fillText(tenantName, 40, 65);
                          }
                        }
                      }}
                      className="text-[11px] font-bold text-emerald-700 hover:underline"
                    >
                      ⚡ Tanda Tangan Otomatis
                    </button>
                    
                    <button
                      type="button"
                      onClick={clearSignature}
                      className="text-[11px] font-bold text-rose-600 hover:underline flex items-center gap-1"
                    >
                      <RotateCcw className="w-3 h-3" />
                      <span>Hapus</span>
                    </button>
                  </div>
                </div>

                <div className="border-2 border-dashed border-slate-300 rounded-2xl bg-white p-1">
                  <canvas
                    ref={canvasRef}
                    width={600}
                    height={110}
                    onMouseDown={startDrawing}
                    onMouseMove={draw}
                    onMouseUp={stopDrawing}
                    onMouseLeave={stopDrawing}
                    onTouchStart={startDrawing}
                    onTouchMove={draw}
                    onTouchEnd={stopDrawing}
                    className="w-full h-28 cursor-crosshair rounded-xl touch-none"
                  />
                </div>
              </div>

              {/* Agreement Checkbox */}
              <label className="flex items-center gap-2 text-xs text-slate-700 cursor-pointer font-medium">
                <input
                  type="checkbox"
                  checked={isAgreedTerms}
                  onChange={(e) => setIsAgreedTerms(e.target.checked)}
                  className="rounded accent-emerald-600 w-4 h-4"
                />
                <span>Saya menyetujui seluruh syarat & ketentuan sewa serta tata tertib hunian.</span>
              </label>

              <button
                type="button"
                disabled={!isAgreedTerms}
                onClick={handleFinishBooking}
                className="w-full py-3 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 text-white font-extrabold rounded-xl text-xs shadow-lg transition flex items-center justify-center gap-2"
              >
                <span>Konfirmasi Sewa & Terbitkan Kontrak Resmi</span>
                <CheckCircle2 className="w-4 h-4" />
              </button>

            </div>
          )}

          {/* STEP 5: Success Receipt & Booking Confirmation */}
          {step === 5 && (
            <div className="space-y-6 text-center animate-fadeIn py-2">
              
              <div className="w-16 h-16 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mx-auto shadow-inner">
                <CheckCircle2 className="w-10 h-10" />
              </div>

              <div className="space-y-1">
                <span className="text-xs font-extrabold uppercase tracking-widest text-emerald-700">TRANSAKSI RESMI BERHASIL</span>
                <h3 className="text-2xl font-black text-slate-900">Selamat, Anda Resmi Menjadi Penghuni!</h3>
                <p className="text-xs text-slate-500 max-w-md mx-auto">
                  Kontrak digital legal telah ditandatangani dan notifikasi instan telah dikirimkan ke pemilik hunian.
                </p>
              </div>

              {/* Receipt Summary Card */}
              <div className="bg-slate-50 p-5 rounded-3xl border border-slate-200 text-left text-xs space-y-3 max-w-lg mx-auto">
                <div className="flex items-center justify-between pb-2 border-b border-slate-200">
                  <span className="text-slate-400 font-bold">Kode Booking Resmi:</span>
                  <span className="font-mono font-black text-emerald-800 text-sm">{apiBooking?.booking_code ?? bookingRefCode}</span>
                </div>

                <div className="flex items-center justify-between">
                  <span className="text-slate-500">Properti & Kamar:</span>
                  <strong className="text-slate-800 text-right">{property.title} ({unit.name})</strong>
                </div>

                <div className="flex items-center justify-between">
                  <span className="text-slate-500">Jadwal Check-in:</span>
                  <strong className="text-slate-800">{checkInDate} (Durasi {durationMonths} Bulan)</strong>
                </div>

                <div className="flex items-center justify-between">
                  <span className="text-slate-500">Jadwal Check-out:</span>
                  <strong className="text-slate-800">{checkOutDate}</strong>
                </div>

                <div className="flex items-center justify-between">
                  <span className="text-slate-500">Metode Pembayaran:</span>
                  <strong className="text-slate-800 uppercase">{paymentMethod} (LUNAS)</strong>
                </div>

                <div className="flex items-center justify-between pt-2 border-t border-slate-200">
                  <span className="font-bold text-slate-800">Total Dibayar:</span>
                  <span className="text-base font-black text-emerald-700">{formatRupiah(apiBooking?.grand_total ?? totals.finalTotal)}</span>
                </div>
              </div>

              {/* Actions: Download PDF, Print, Close */}
              <div className="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2 max-w-lg mx-auto">
                <button
                  type="button"
                  onClick={() => alert('Kwitansi & Kontrak Digital PDF berhasil diunduh.')}
                  className="w-full sm:w-auto px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs shadow transition flex items-center justify-center gap-1.5"
                >
                  <Download className="w-4 h-4" />
                  <span>Unduh Kwitansi & Kontrak PDF</span>
                </button>

                <button
                  type="button"
                  onClick={onClose}
                  className="w-full sm:w-auto px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl text-xs shadow transition"
                >
                  Selesai
                </button>
              </div>

            </div>
          )}

        </div>

      </div>
    </div>
  );
};
