import React, { useState } from 'react';
import { 
  Building, 
  Calendar, 
  ChevronRight, 
  Compass, 
  DollarSign, 
  Home, 
  MapPin, 
  Search, 
  ShieldCheck, 
  Sparkles, 
  Users 
} from 'lucide-react';
import { CITIES } from '../../data/cities';
import { PropertyType, RentalPeriod } from '../../types';
import { formatCompactRupiah, formatRupiah } from '../../utils/currency';

interface HeroSearchProps {
  onSearch: (filters: {
    city: string;
    propertyType: PropertyType | 'all';
    rentalPeriod: RentalPeriod;
    maxPrice: number;
    query: string;
  }) => void;
}

export const HeroSearch: React.FC<HeroSearchProps> = ({ onSearch }) => {
  const [selectedCity, setSelectedCity] = useState('');
  const [selectedType, setSelectedType] = useState<PropertyType | 'all'>('all');
  const [selectedPeriod, setSelectedPeriod] = useState<RentalPeriod>('bulanan');
  const [maxPrice, setMaxPrice] = useState<number>(6000000);
  const [searchQuery, setSearchQuery] = useState('');

  const quickSearches = [
    { label: '🔥 Kost Dekat UGM', query: 'UGM', city: 'Yogyakarta' },
    { label: '🚆 Kost Tebet Dekat KRL', query: 'Tebet', city: 'Jakarta Selatan' },
    { label: '🌴 Villa Canggu Bali', query: 'Canggu', city: 'Bali' },
    { label: '🏢 Apt Pakuwon Mall', query: 'Pakuwon', city: 'Surabaya' },
    { label: '🎓 Kost Dago ITB', query: 'ITB', city: 'Bandung' }
  ];

  const handleExecuteSearch = (e?: React.FormEvent) => {
    if (e) e.preventDefault();
    onSearch({
      city: selectedCity,
      propertyType: selectedType,
      rentalPeriod: selectedPeriod,
      maxPrice,
      query: searchQuery
    });
  };

  return (
    <section className="relative pt-8 pb-16 lg:pt-14 lg:pb-24 overflow-hidden bg-gradient-to-b from-emerald-50/70 via-white to-slate-50">
      
      {/* Background Graphic Accents */}
      <div className="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 rounded-full bg-emerald-200/40 blur-3xl pointer-events-none" />
      <div className="absolute top-1/2 left-0 -ml-24 w-80 h-80 rounded-full bg-teal-200/30 blur-3xl pointer-events-none" />

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        
        {/* Trust Badge & Headline */}
        <div className="text-center max-w-3xl mx-auto space-y-4 mb-8">
          <div className="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-100/80 border border-emerald-300/80 text-emerald-800 text-xs font-extrabold shadow-sm">
            <ShieldCheck className="w-4 h-4 text-emerald-600" />
            <span>Platform Sewa Resmi #1 di Indonesia</span>
            <span className="hidden sm:inline text-emerald-500">•</span>
            <span className="hidden sm:inline font-semibold">100% Terverifikasi & Foto Asli</span>
          </div>

          <h1 className="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight leading-[1.15]">
            Sewa Kost, Apartemen & Villa <span className="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-teal-500">Makin Nyaman & Aman</span>
          </h1>

          <p className="text-sm sm:text-base text-slate-600 leading-relaxed">
            Temukan ribuan hunian impian di Jakarta, Jogja, Bali, Bandung, Surabaya dengan survey 360°, kontrak digital legal, dan pembayaran resmi QRIS & Virtual Account.
          </p>
        </div>

        {/* Hero Interactive Search Container */}
        <div className="max-w-4xl mx-auto bg-white rounded-3xl shadow-elevated border border-slate-200/80 p-4 sm:p-6 backdrop-blur-md">
          
          {/* Duration Selector Tabs */}
          <div className="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100 overflow-x-auto no-scrollbar">
            <span className="text-xs font-bold text-slate-400 mr-2 shrink-0">Durasi Sewa:</span>
            <button
              type="button"
              onClick={() => setSelectedPeriod('bulanan')}
              className={`px-4 py-1.5 rounded-xl text-xs font-bold transition shrink-0 ${
                selectedPeriod === 'bulanan'
                  ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30'
                  : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
              }`}
            >
              Sewa Bulanan (Populer)
            </button>
            <button
              type="button"
              onClick={() => setSelectedPeriod('tahunan')}
              className={`px-4 py-1.5 rounded-xl text-xs font-bold transition shrink-0 ${
                selectedPeriod === 'tahunan'
                  ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30'
                  : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
              }`}
            >
              Sewa Tahunan (Hemat s.d 20%)
            </button>
            <button
              type="button"
              onClick={() => setSelectedPeriod('harian')}
              className={`px-4 py-1.5 rounded-xl text-xs font-bold transition shrink-0 ${
                selectedPeriod === 'harian'
                  ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-600/30'
                  : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
              }`}
            >
              Sewa Harian / Villa
            </button>
          </div>

          <form onSubmit={handleExecuteSearch} className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            
            {/* Input 1: Lokasi / Kota */}
            <div className="p-3 bg-slate-50 hover:bg-slate-100/80 rounded-2xl border border-slate-200 transition">
              <label className="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                Lokasi / Kota
              </label>
              <div className="flex items-center gap-2">
                <MapPin className="w-4 h-4 text-emerald-600 shrink-0" />
                <select
                  value={selectedCity}
                  onChange={(e) => setSelectedCity(e.target.value)}
                  aria-label="Pilih Kota"
                  className="w-full bg-transparent text-xs font-bold text-slate-800 focus:outline-none cursor-pointer"
                >
                  <option value="">Semua Kota di Indonesia</option>
                  {CITIES.map(c => (
                    <option key={c.id} value={c.name}>{c.name}</option>
                  ))}
                </select>
              </div>
            </div>

            {/* Input 2: Tipe Properti */}
            <div className="p-3 bg-slate-50 hover:bg-slate-100/80 rounded-2xl border border-slate-200 transition">
              <label className="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                Tipe Hunian
              </label>
              <div className="flex items-center gap-2">
                <Home className="w-4 h-4 text-emerald-600 shrink-0" />
                <select
                  value={selectedType}
                  onChange={(e) => setSelectedType(e.target.value as any)}
                  aria-label="Pilih Tipe Hunian"
                  className="w-full bg-transparent text-xs font-bold text-slate-800 focus:outline-none cursor-pointer"
                >
                  <option value="all">Semua Tipe Properti</option>
                  <option value="kost_campur">Kost Campur</option>
                  <option value="kost_putri">Kost Khusus Putri</option>
                  <option value="kost_putra">Kost Khusus Putra</option>
                  <option value="apartemen">Apartemen (Studio/2BR)</option>
                  <option value="villa">Villa & Co-Living</option>
                  <option value="kontrakan">Rumah Kontrakan</option>
                </select>
              </div>
            </div>

            {/* Input 3: Cari Nama / Kampus / Stasiun */}
            <div className="p-3 bg-slate-50 hover:bg-slate-100/80 rounded-2xl border border-slate-200 transition">
              <label className="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-1">
                Kampus / MRT / Landmark
              </label>
              <div className="flex items-center gap-2">
                <Search className="w-4 h-4 text-emerald-600 shrink-0" />
                <input
                  type="text"
                  placeholder="Cth: UGM, Tebet, Canggu..."
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  className="w-full bg-transparent text-xs font-bold text-slate-800 focus:outline-none placeholder:text-slate-400"
                />
              </div>
            </div>

            {/* Action Submit Button */}
            <div className="flex items-end">
              <button
                type="submit"
                className="w-full h-12 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-extrabold text-sm rounded-2xl shadow-lg shadow-emerald-600/30 flex items-center justify-center gap-2 transition hover:scale-[1.02] active:scale-[0.98]"
              >
                <Search className="w-4 h-4" />
                <span>Cari Hunian</span>
              </button>
            </div>

          </form>

          {/* Quick Filter Sliders & Badges */}
          <div className="mt-4 pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div className="flex items-center gap-3">
              <span className="text-xs font-bold text-slate-500">Maks. Anggaran:</span>
              <input
                type="range"
                min="1000000"
                max="12000000"
                step="500000"
                value={maxPrice}
                onChange={(e) => setMaxPrice(Number(e.target.value))}
                className="w-32 sm:w-44 accent-emerald-600 cursor-pointer"
              />
              <span className="text-xs font-extrabold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                {formatCompactRupiah(maxPrice)}/bln
              </span>
            </div>

            <div className="flex items-center gap-1.5 text-xs text-slate-400">
              <Sparkles className="w-3.5 h-3.5 text-amber-500" />
              <span>Garansi 100% Foto Real & Booking Instan</span>
            </div>
          </div>

        </div>

        {/* Popular Quick Search Tags */}
        <div className="mt-6 flex flex-wrap items-center justify-center gap-2">
          <span className="text-xs text-slate-500 font-semibold mr-1">Pencarian Populer:</span>
          {quickSearches.map((item, idx) => (
            <button
              key={idx}
              onClick={() => {
                setSelectedCity(item.city);
                setSearchQuery(item.query);
                onSearch({
                  city: item.city,
                  propertyType: 'all',
                  rentalPeriod: 'bulanan',
                  maxPrice: 10000000,
                  query: item.query
                });
              }}
              className="px-3 py-1.5 bg-white hover:bg-emerald-50 hover:border-emerald-300 text-slate-700 hover:text-emerald-800 text-xs font-semibold rounded-full border border-slate-200 shadow-subtle transition"
            >
              {item.label}
            </button>
          ))}
        </div>

      </div>
    </section>
  );
};
