import React, { useState } from 'react';
import { 
  Building, 
  ChevronDown, 
  GraduationCap, 
  HelpCircle, 
  MapPin, 
  Navigation, 
  ShieldCheck, 
  Sparkles 
} from 'lucide-react';
import { CityHub, Property } from '../../types';
import { formatCompactRupiah, formatRupiah } from '../../utils/currency';
import { PropertyCard } from '../explore/PropertyCard';

interface CityLandingContentProps {
  city: CityHub;
  properties: Property[];
  onSelectProperty: (property: Property) => void;
  favorites: string[];
  onToggleFavorite: (property: Property) => void;
  comparedProperties: Property[];
  onToggleCompare: (property: Property) => void;
  onOpenSeoInspector: () => void;
}

export const CityLandingContent: React.FC<CityLandingContentProps> = ({
  city,
  properties,
  onSelectProperty,
  favorites,
  onToggleFavorite,
  comparedProperties,
  onToggleCompare,
  onOpenSeoInspector
}) => {
  const [openFaqIndex, setOpenFaqIndex] = useState<number | null>(0);

  const filteredCityProps = properties.filter(
    p => p.city.toLowerCase().includes(city.name.toLowerCase()) || 
         city.popularDistricts.some(d => p.subDistrict.toLowerCase().includes(d.toLowerCase()))
  );

  return (
    <div className="space-y-12 pb-16 animate-fadeIn">
      
      {/* City Hero Banner with Breadcrumbs */}
      <section className="relative rounded-3xl overflow-hidden bg-slate-900 text-white p-6 sm:p-12 shadow-elevated">
        <img
          src={city.image}
          alt={`Sewa Kost dan Apartemen di ${city.name}`}
          className="absolute inset-0 w-full h-full object-cover opacity-35"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/60 to-transparent" />

        <div className="relative max-w-3xl space-y-4">
          
          {/* Breadcrumb Navigation */}
          <nav className="flex items-center gap-2 text-xs text-slate-300">
            <span>Beranda</span>
            <span>/</span>
            <span>Sewa Properti</span>
            <span>/</span>
            <span className="text-emerald-400 font-bold">{city.name}</span>
          </nav>

          <div className="inline-flex items-center gap-2 px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-full text-xs font-bold">
            <Sparkles className="w-3.5 h-3.5" />
            <span>SEO Landing Hub Resmi</span>
          </div>

          <h1 className="text-3xl sm:text-5xl font-black tracking-tight leading-tight">
            Sewa Kost & Apartemen di <span className="text-emerald-400">{city.name}</span>
          </h1>

          <p className="text-sm sm:text-base text-slate-300 leading-relaxed">
            {city.description} Temukan {city.totalProperties}+ pilihan hunian kost mahasiswa, pekerja profesional, kontrakan keluarga, dan villa dengan jaminan harga termurah dan foto terverifikasi resmi.
          </p>

          {/* Quick Statistics Bar */}
          <div className="grid grid-cols-2 sm:grid-cols-3 gap-3 pt-4">
            <div className="p-3.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10">
              <span className="text-[10px] text-slate-300 block uppercase font-bold">Rata-rata Kost</span>
              <span className="text-base font-black text-emerald-400">{formatCompactRupiah(city.avgKostPrice)}/bln</span>
            </div>
            <div className="p-3.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10">
              <span className="text-[10px] text-slate-300 block uppercase font-bold">Rata-rata Apartemen</span>
              <span className="text-base font-black text-white">{formatCompactRupiah(city.avgApartmentPrice)}/bln</span>
            </div>
            <div className="p-3.5 bg-white/10 backdrop-blur-md rounded-2xl border border-white/10 col-span-2 sm:col-span-1">
              <span className="text-[10px] text-slate-300 block uppercase font-bold">Properti Terdaftar</span>
              <span className="text-base font-black text-emerald-300">{city.totalProperties}+ Unit</span>
            </div>
          </div>

        </div>
      </section>

      {/* Popular Districts & Universities SEO Links Matrix */}
      <section className="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        {/* Popular Districts */}
        <div className="p-6 bg-white rounded-3xl border border-slate-200 shadow-subtle space-y-4">
          <div className="flex items-center gap-2">
            <MapPin className="w-5 h-5 text-emerald-600" />
            <h3 className="font-extrabold text-base text-slate-900">Area & Kecamatan Terpopuler di {city.name}</h3>
          </div>
          <p className="text-xs text-slate-500">Cari kost dan hunian spesifik berdasarkan radius lokasi lingkungan.</p>
          <div className="flex flex-wrap gap-2">
            {city.popularDistricts.map((dist, idx) => (
              <span
                key={idx}
                className="px-3 py-1.5 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-800 text-slate-700 text-xs font-bold rounded-xl border border-slate-200 transition cursor-pointer"
              >
                Kost di {dist}
              </span>
            ))}
          </div>
        </div>

        {/* Top Universities */}
        <div className="p-6 bg-white rounded-3xl border border-slate-200 shadow-subtle space-y-4">
          <div className="flex items-center gap-2">
            <GraduationCap className="w-5 h-5 text-teal-600" />
            <h3 className="font-extrabold text-base text-slate-900">Kost Dekat Kampus & Universitas</h3>
          </div>
          <p className="text-xs text-slate-500">Jarak jalan kaki kurang dari 10 menit ke gerbang fakultas.</p>
          <div className="flex flex-wrap gap-2">
            {city.topUniversities.map((univ, idx) => (
              <span
                key={idx}
                className="px-3 py-1.5 bg-teal-50 text-teal-900 text-xs font-bold rounded-xl border border-teal-200 transition cursor-pointer"
              >
                Kost Dekat {univ}
              </span>
            ))}
          </div>
        </div>

      </section>

      {/* Available Properties Grid in this City */}
      <section className="space-y-6">
        <div className="flex items-center justify-between">
          <div>
            <h2 className="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
              Pilihan Hunian di {city.name}
            </h2>
            <p className="text-xs text-slate-500 mt-0.5">
              Menampilkan properti terverifikasi resmi Stayease di wilayah {city.name}.
            </p>
          </div>

          <button
            onClick={onOpenSeoInspector}
            className="px-3 py-1.5 bg-slate-900 text-emerald-400 hover:text-emerald-300 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-sm"
          >
            <Sparkles className="w-3.5 h-3.5" />
            <span>Lihat Schema JSON-LD Kota</span>
          </button>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          {filteredCityProps.length > 0 ? (
            filteredCityProps.map((prop) => (
              <PropertyCard
                key={prop.id}
                property={prop}
                onSelect={onSelectProperty}
                isFavorite={favorites.includes(prop.id)}
                onToggleFavorite={onToggleFavorite}
                isCompared={comparedProperties.some(p => p.id === prop.id)}
                onToggleCompare={onToggleCompare}
              />
            ))
          ) : (
            properties.slice(0, 3).map((prop) => (
              <PropertyCard
                key={prop.id}
                property={prop}
                onSelect={onSelectProperty}
                isFavorite={favorites.includes(prop.id)}
                onToggleFavorite={onToggleFavorite}
                isCompared={comparedProperties.some(p => p.id === prop.id)}
                onToggleCompare={onToggleCompare}
              />
            ))
          )}
        </div>
      </section>

      {/* SEO FAQ Accordion Schema */}
      <section className="p-6 sm:p-8 bg-slate-50 rounded-3xl border border-slate-200/80 space-y-6">
        <div className="flex items-center gap-2">
          <HelpCircle className="w-5 h-5 text-emerald-600" />
          <div>
            <h3 className="text-lg font-extrabold text-slate-900">Pertanyaan Umum (FAQ) Sewa Hunian di {city.name}</h3>
            <p className="text-xs text-slate-500">Pertanyaan yang sering diajukan calon penyewa dan mahasiswa.</p>
          </div>
        </div>

        <div className="space-y-3">
          {city.faqs.map((faq, idx) => {
            const isOpen = openFaqIndex === idx;
            return (
              <div
                key={idx}
                className="bg-white rounded-2xl border border-slate-200/80 overflow-hidden shadow-subtle"
              >
                <button
                  onClick={() => setOpenFaqIndex(isOpen ? null : idx)}
                  className="w-full p-4 text-left font-extrabold text-xs sm:text-sm text-slate-900 flex items-center justify-between gap-3 hover:text-emerald-700 transition"
                >
                  <span>{faq.q}</span>
                  <ChevronDown className={`w-4 h-4 text-slate-400 shrink-0 transition-transform ${isOpen ? 'rotate-180 text-emerald-600' : ''}`} />
                </button>
                {isOpen && (
                  <div className="px-4 pb-4 pt-1 text-xs text-slate-600 leading-relaxed border-t border-slate-100 animate-fadeIn">
                    {faq.a}
                  </div>
                )}
              </div>
            );
          })}
        </div>
      </section>

    </div>
  );
};
