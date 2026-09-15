import React, { useState } from 'react';
import confetti from 'canvas-confetti';
import { 
  AlertCircle,
  Building2, 
  Camera, 
  Check, 
  CheckCircle2, 
  FileText, 
  Home, 
  Loader2,
  MapPin, 
  Plus, 
  ShieldCheck, 
  Sparkles, 
  Upload, 
  Zap 
} from 'lucide-react';
import { CITIES } from '../../data/cities';
import { PropertyType } from '../../types';
import { submitPropertyListing, ApiError } from '../../api';
import { saveGuestIdentity } from '../../utils/guestIdentity';

interface ListPropertyWizardProps {
  onClose: () => void;
  onSuccessListed: (title: string) => void;
}

export const ListPropertyWizard: React.FC<ListPropertyWizardProps> = ({
  onClose,
  onSuccessListed
}) => {
  const [currentStep, setCurrentStep] = useState<1 | 2 | 3 | 4>(1);

  // Form State
  const [propertyTitle, setPropertyTitle] = useState('Kost Eksklusif Baru Cilandak');
  const [propertyType, setPropertyType] = useState<PropertyType>('kost_campur');
  const [city, setCity] = useState('Jakarta Selatan');
  const [subDistrict, setSubDistrict] = useState('Cilandak');
  const [address, setAddress] = useState('Jl. Cilandak Barat Raya No. 18');
  const [roomCount, setRoomCount] = useState<number>(12);
  const [monthlyPrice, setMonthlyPrice] = useState<number>(2500000);
  const [electricityPolicy, setElectricityPolicy] = useState<'include' | 'token_mandiri'>('token_mandiri');
  const [ownerName, setOwnerName] = useState('Haji Sulaeman');
  const [ownerWhatsapp, setOwnerWhatsapp] = useState('081299887766');
  const [ownerEmail, setOwnerEmail] = useState('');

  const [isSubmitting, setIsSubmitting] = useState(false);
  const [submitError, setSubmitError] = useState<string | null>(null);

  const [facilities, setFacilities] = useState<string[]>([
    'AC', 'Wi-Fi', 'Kamar Mandi Dalam', 'Water Heater', 'Parkir Motor', 'Dapur Bersama'
  ]);

  const toggleFacility = (name: string) => {
    setFacilities(prev => 
      prev.includes(name) ? prev.filter(f => f !== name) : [...prev, name]
    );
  };

  // Bug fix: this used to just switch to the "success" step and show
  // confetti no matter what — "Publikasikan Iklan Sewa" never actually
  // called the backend, so no Property row was ever created and the
  // listing never reached Admin's verification queue. It now really
  // submits the listing (POST /api/v1/listings) and only shows the
  // success screen once that succeeds.
  const handleFinishListing = async () => {
    if (!ownerEmail.trim()) {
      setSubmitError('Email pemilik wajib diisi supaya kami bisa membuatkan akun Mitra Anda.');
      return;
    }

    setIsSubmitting(true);
    setSubmitError(null);
    try {
      await submitPropertyListing({
        title: propertyTitle,
        propertyType,
        city,
        subDistrict,
        address,
        roomCount,
        monthlyPrice,
        electricityPolicy,
        facilities,
        ownerName,
        ownerWhatsapp,
        ownerEmail: ownerEmail.trim(),
      });

      saveGuestIdentity({ name: ownerName, email: ownerEmail.trim(), phone: ownerWhatsapp });

      setCurrentStep(4);
      try {
        confetti({ particleCount: 90, spread: 60 });
      } catch (e) {}
      onSuccessListed(propertyTitle);
    } catch (err) {
      setSubmitError(
        err instanceof ApiError
          ? err.message
          : 'Gagal mengirim listing. Periksa koneksi Anda dan coba lagi.'
      );
    } finally {
      setIsSubmitting(false);
    }
  };

  return (
    <div className="max-w-4xl mx-auto py-10 px-4 sm:px-6 animate-fadeIn">
      <div className="bg-white rounded-3xl border border-slate-200 shadow-card overflow-hidden">
        
        {/* Header */}
        <div className="p-6 bg-slate-900 text-white flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="p-2.5 bg-emerald-500/20 text-emerald-400 rounded-2xl">
              <Building2 className="w-6 h-6" />
            </div>
            <div>
              <h2 className="text-lg sm:text-xl font-black">Pusat Pendaftaran Mitra Pemilik Stayease</h2>
              <p className="text-xs text-slate-400">Daftarkan kost, apartemen, atau villa Anda dalam 3 langkah mudah.</p>
            </div>
          </div>

          <button
            onClick={onClose}
            className="px-3.5 py-1.5 bg-slate-800 hover:bg-slate-700 text-xs font-bold rounded-xl transition"
          >
            Tutup
          </button>
        </div>

        {/* Step Wizard Tracker */}
        {currentStep < 4 && (
          <div className="bg-slate-50 p-4 border-b border-slate-200 flex items-center justify-between text-xs font-bold">
            <span className={currentStep >= 1 ? 'text-emerald-700' : 'text-slate-400'}>1. Tipe & Lokasi Properti</span>
            <span className="text-slate-300">→</span>
            <span className={currentStep >= 2 ? 'text-emerald-700' : 'text-slate-400'}>2. Fasilitas & Harga Sewa</span>
            <span className="text-slate-300">→</span>
            <span className={currentStep >= 3 ? 'text-emerald-700' : 'text-slate-400'}>3. Foto & Kontak Pemilik</span>
          </div>
        )}

        <div className="p-6 sm:p-8">
          
          {/* STEP 1: Basic Info & Location */}
          {currentStep === 1 && (
            <div className="space-y-5 animate-fadeIn">
              <div className="space-y-1">
                <label className="block text-xs font-bold text-slate-700">Nama Listing / Properti *</label>
                <input
                  type="text"
                  value={propertyTitle}
                  onChange={(e) => setPropertyTitle(e.target.value)}
                  placeholder="Cth: Kost Griya Melati UGM / Apartemen Grand Pakuwon"
                  className="w-full p-3 bg-slate-50 text-xs font-bold rounded-2xl border border-slate-200 focus:outline-none focus:border-emerald-500"
                />
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div className="space-y-1">
                  <label className="block text-xs font-bold text-slate-700">Tipe Properti</label>
                  <select
                    value={propertyType}
                    onChange={(e) => setPropertyType(e.target.value as any)}
                    className="w-full p-3 bg-slate-50 text-xs font-bold rounded-2xl border border-slate-200 focus:outline-none focus:border-emerald-500"
                  >
                    <option value="kost_campur">Kost Campur</option>
                    <option value="kost_putri">Kost Khusus Putri</option>
                    <option value="kost_putra">Kost Khusus Putra</option>
                    <option value="apartemen">Apartemen Sewa</option>
                    <option value="villa">Villa & Co-Living</option>
                    <option value="kontrakan">Rumah Kontrakan</option>
                  </select>
                </div>

                <div className="space-y-1">
                  <label className="block text-xs font-bold text-slate-700">Kota / Wilayah</label>
                  <select
                    value={city}
                    onChange={(e) => setCity(e.target.value)}
                    className="w-full p-3 bg-slate-50 text-xs font-bold rounded-2xl border border-slate-200 focus:outline-none focus:border-emerald-500"
                  >
                    {CITIES.map(c => (
                      <option key={c.id} value={c.name}>{c.name}</option>
                    ))}
                  </select>
                </div>

                <div className="space-y-1">
                  <label className="block text-xs font-bold text-slate-700">Kecamatan / Area</label>
                  <input
                    type="text"
                    value={subDistrict}
                    onChange={(e) => setSubDistrict(e.target.value)}
                    placeholder="Cth: Tebet, Pogung, Canggu, Dago"
                    className="w-full p-3 bg-slate-50 text-xs font-bold rounded-2xl border border-slate-200 focus:outline-none focus:border-emerald-500"
                  />
                </div>

                <div className="space-y-1">
                  <label className="block text-xs font-bold text-slate-700">Jumlah Total Kamar / Unit</label>
                  <input
                    type="number"
                    value={roomCount}
                    onChange={(e) => setRoomCount(Number(e.target.value))}
                    className="w-full p-3 bg-slate-50 text-xs font-bold rounded-2xl border border-slate-200 focus:outline-none focus:border-emerald-500"
                  />
                </div>
              </div>

              <div className="space-y-1">
                <label className="block text-xs font-bold text-slate-700">Alamat Lengkap Properti *</label>
                <textarea
                  rows={2}
                  value={address}
                  onChange={(e) => setAddress(e.target.value)}
                  className="w-full p-3 bg-slate-50 text-xs font-medium rounded-2xl border border-slate-200 focus:outline-none focus:border-emerald-500"
                />
              </div>

              <div className="pt-4 flex justify-end">
                <button
                  type="button"
                  onClick={() => setCurrentStep(2)}
                  className="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-2xl shadow-md transition"
                >
                  Lanjut ke Langkah 2
                </button>
              </div>
            </div>
          )}

          {/* STEP 2: Pricing & Facilities */}
          {currentStep === 2 && (
            <div className="space-y-5 animate-fadeIn">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div className="space-y-1">
                  <label className="block text-xs font-bold text-slate-700">Harga Sewa Bulanan (Rp) *</label>
                  <input
                    type="number"
                    step="50000"
                    value={monthlyPrice}
                    onChange={(e) => setMonthlyPrice(Number(e.target.value))}
                    className="w-full p-3 bg-slate-50 text-xs font-bold rounded-2xl border border-slate-200 focus:outline-none focus:border-emerald-500 font-mono"
                  />
                </div>

                <div className="space-y-1">
                  <label className="block text-xs font-bold text-slate-700">Kebijakan Listrik</label>
                  <select
                    value={electricityPolicy}
                    onChange={(e) => setElectricityPolicy(e.target.value as any)}
                    className="w-full p-3 bg-slate-50 text-xs font-bold rounded-2xl border border-slate-200 focus:outline-none focus:border-emerald-500"
                  >
                    <option value="token_mandiri">Token Listrik Mandiri oleh Penyewa</option>
                    <option value="include">Include / Termasuk Biaya Listrik</option>
                  </select>
                </div>
              </div>

              <div className="space-y-2">
                <label className="block text-xs font-bold text-slate-700">Fasilitas yang Disediakan</label>
                <div className="flex flex-wrap gap-2">
                  {[
                    'AC', 'Wi-Fi 100Mbps', 'Kamar Mandi Dalam', 'Water Heater', 'Smart TV', 
                    'Kasur Springbed', 'Meja Belajar', 'Dapur Bersama', 'Kulkas Bersama', 
                    'Mesin Cuci', 'Parkir Mobil', 'Parkir Motor', 'Akses 24 Jam', 'CCTV Security'
                  ].map((fac) => {
                    const isSelected = facilities.includes(fac);
                    return (
                      <button
                        key={fac}
                        type="button"
                        onClick={() => toggleFacility(fac)}
                        className={`px-3 py-1.5 rounded-xl text-xs font-semibold border transition ${
                          isSelected
                            ? 'bg-emerald-600 text-white border-emerald-600'
                            : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-slate-300'
                        }`}
                      >
                        {fac}
                      </button>
                    );
                  })}
                </div>
              </div>

              <div className="pt-4 flex items-center justify-between">
                <button
                  type="button"
                  onClick={() => setCurrentStep(1)}
                  className="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-2xl"
                >
                  Kembali
                </button>
                <button
                  type="button"
                  onClick={() => setCurrentStep(3)}
                  className="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-2xl shadow-md transition"
                >
                  Lanjut ke Langkah 3
                </button>
              </div>
            </div>
          )}

          {/* STEP 3: Photos & Owner Contact */}
          {currentStep === 3 && (
            <div className="space-y-5 animate-fadeIn">
              <div className="p-6 border-2 border-dashed border-emerald-300 rounded-3xl bg-emerald-50/30 text-center space-y-2">
                <Camera className="w-10 h-10 text-emerald-600 mx-auto" />
                <h4 className="font-extrabold text-sm text-slate-900">Sesi Foto Profesional Gratis</h4>
                <p className="text-xs text-slate-500 max-w-sm mx-auto">
                  Tidak perlu upload foto sendiri — tim fotografer Stayease akan datang untuk sesi foto & Virtual Tour 360° gratis setelah listing Anda diverifikasi.
                </p>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div className="space-y-1">
                  <label className="block text-xs font-bold text-slate-700">Nama Pemilik / Pengelola *</label>
                  <input
                    type="text"
                    value={ownerName}
                    onChange={(e) => setOwnerName(e.target.value)}
                    className="w-full p-3 bg-slate-50 text-xs font-bold rounded-2xl border border-slate-200 focus:outline-none focus:border-emerald-500"
                  />
                </div>

                <div className="space-y-1">
                  <label className="block text-xs font-bold text-slate-700">Nomor WhatsApp Aktif *</label>
                  <input
                    type="tel"
                    value={ownerWhatsapp}
                    onChange={(e) => setOwnerWhatsapp(e.target.value)}
                    className="w-full p-3 bg-slate-50 text-xs font-bold rounded-2xl border border-slate-200 focus:outline-none focus:border-emerald-500"
                  />
                </div>

                <div className="space-y-1 sm:col-span-2">
                  <label className="block text-xs font-bold text-slate-700">Email Aktif *</label>
                  <input
                    type="email"
                    required
                    placeholder="nama@email.com"
                    value={ownerEmail}
                    onChange={(e) => setOwnerEmail(e.target.value)}
                    className="w-full p-3 bg-slate-50 text-xs font-bold rounded-2xl border border-slate-200 focus:outline-none focus:border-emerald-500"
                  />
                  <p className="text-[11px] text-slate-400">Dipakai untuk membuatkan akun Dashboard Mitra Anda (login pakai email ini).</p>
                </div>
              </div>

              {submitError && (
                <div className="flex items-center gap-2 p-3 bg-rose-50 text-rose-700 rounded-xl text-xs border border-rose-200">
                  <AlertCircle className="w-4 h-4 shrink-0" />
                  <span>{submitError}</span>
                </div>
              )}

              <div className="pt-4 flex items-center justify-between">
                <button
                  type="button"
                  onClick={() => setCurrentStep(2)}
                  disabled={isSubmitting}
                  className="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-bold rounded-2xl disabled:opacity-50"
                >
                  Kembali
                </button>
                <button
                  type="button"
                  onClick={handleFinishListing}
                  disabled={isSubmitting}
                  className="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 text-white text-xs font-black rounded-2xl shadow-lg shadow-emerald-600/30 transition flex items-center gap-2"
                >
                  {isSubmitting && <Loader2 className="w-4 h-4 animate-spin" />}
                  {isSubmitting ? 'Mengirim...' : 'Publikasikan Iklan Sewa'}
                </button>
              </div>
            </div>
          )}

          {/* STEP 4: Success Message */}
          {currentStep === 4 && (
            <div className="py-12 text-center space-y-5 animate-fadeIn">
              <div className="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto shadow-md">
                <CheckCircle2 className="w-10 h-10" />
              </div>

              <div>
                <span className="px-3 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-black">
                  PENDAFTARAN BERHASIL
                </span>
                <h3 className="text-2xl font-black text-slate-900 mt-2">
                  Listing Properti Anda Telah Diterima!
                </h3>
                <p className="text-xs sm:text-sm text-slate-500 max-w-md mx-auto mt-1">
                  Tim kurator Stayease akan menghubungi Anda via WhatsApp dalam 1x24 jam untuk verifikasi kelayakan dan jadwal sesi foto 360° gratis.
                </p>
              </div>

              <button
                onClick={onClose}
                className="px-6 py-3 bg-slate-900 text-white text-xs font-bold rounded-2xl hover:bg-slate-800 transition"
              >
                Kembali ke Beranda
              </button>
            </div>
          )}

        </div>

      </div>
    </div>
  );
};
