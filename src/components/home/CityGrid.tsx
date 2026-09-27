import React from 'react';
import { ArrowRight, Building, MapPin, Sparkles } from 'lucide-react';
import { CITIES } from '../../data/cities';
import { formatCompactRupiah } from '../../utils/currency';

interface CityGridProps {
  onSelectCityHub: (slug: string) => void;
}

export const CityGrid: React.FC<CityGridProps> = ({ onSelectCityHub }) => {
  return (
    <section className="py-14 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div className="flex flex-col sm:flex-row sm:items-end justify-between mb-8 gap-4">
          <div>
            <div className="flex items-center gap-1.5 text-xs font-extrabold text-emerald-600 uppercase tracking-wider mb-1">
              <MapPin className="w-4 h-4" />
              <span>Eksplorasi Wilayah Populer</span>
            </div>
            <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
              Kota Paling Dicari di Indonesia
            </h2>
            <p className="text-xs sm:text-sm text-slate-500 mt-1">
              Pusat perkantoran, kampus bergengsi, dan spot gaya hidup terfavorit.
            </p>
          </div>

          <span className="text-xs text-slate-500 font-semibold">
            Menampilkan 5 kota metropolitan teratas
          </span>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          {CITIES.map((city, idx) => (
            <div
              key={city.id}
              onClick={() => onSelectCityHub(city.slug)}
              className="group cursor-pointer rounded-3xl overflow-hidden bg-slate-900 relative aspect-[4/3] shadow-card hover:shadow-elevated transition-all duration-300 transform hover:-translate-y-1.5"
            >
              {/* Background Image with Dark Gradient Overlay */}
              <img
                src={city.image}
                alt={`Sewa Kost dan Apartemen di ${city.name}`}
                className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-80"
              />
              <div className="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent" />

              {/* Top Badge */}
              <div className="absolute top-4 left-4 right-4 flex items-center justify-between">
                <span className="px-3 py-1 bg-white/90 backdrop-blur-md text-slate-900 font-bold text-xs rounded-full shadow-sm">
                  {city.totalProperties}+ Properti
                </span>
                <span className="p-2 bg-black/40 backdrop-blur-md text-white rounded-full group-hover:bg-emerald-600 transition">
                  <ArrowRight className="w-4 h-4 group-hover:translate-x-0.5 transition" />
                </span>
              </div>

              {/* Bottom Details */}
              <div className="absolute bottom-4 left-4 right-4 text-white space-y-2">
                <div>
                  <span className="text-[11px] font-semibold text-emerald-400 uppercase tracking-wider">
                    {city.province}
                  </span>
                  <h3 className="text-xl font-black tracking-tight">{city.name}</h3>
                </div>

                <p className="text-xs text-slate-300 line-clamp-1">
                  {city.description}
                </p>

                {/* Pricing Info Badges */}
                <div className="pt-2 flex items-center gap-2 text-[11px] font-semibold">
                  <span className="px-2 py-0.5 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-lg">
                    Kost mulai {formatCompactRupiah(city.avgKostPrice)}/bln
                  </span>
                  <span className="px-2 py-0.5 bg-white/10 text-slate-200 rounded-lg">
                    Apt {formatCompactRupiah(city.avgApartmentPrice)}/bln
                  </span>
                </div>
              </div>
            </div>
          ))}
        </div>

      </div>
    </section>
  );
};
