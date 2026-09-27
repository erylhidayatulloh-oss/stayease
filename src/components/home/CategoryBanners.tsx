import React from 'react';
import { 
  Building2, 
  Home, 
  Palmtree, 
  Sparkles, 
  UserCheck, 
  Users, 
  Venus 
} from 'lucide-react';
import { PropertyType } from '../../types';

interface CategoryBannersProps {
  onSelectCategory: (type: PropertyType) => void;
}

export const CategoryBanners: React.FC<CategoryBannersProps> = ({ onSelectCategory }) => {
  const categories = [
    {
      type: 'kost_putri' as PropertyType,
      title: 'Kost Khusus Putri',
      subtitle: 'Aman, CCTV & Gerbang Berakses',
      count: '1,450+ Unit',
      icon: Venus,
      color: 'from-pink-500/10 to-rose-500/10 text-rose-600 border-rose-200 hover:border-rose-400',
      badge: 'Khusus Wanita'
    },
    {
      type: 'kost_campur' as PropertyType,
      title: 'Kost Campur',
      subtitle: 'Akses 24 Jam & Fasilitas Lengkap',
      count: '1,820+ Unit',
      icon: Users,
      color: 'from-emerald-500/10 to-teal-500/10 text-emerald-600 border-emerald-200 hover:border-emerald-400',
      badge: 'Bebas Jam Malam'
    },
    {
      type: 'kost_putra' as PropertyType,
      title: 'Kost Khusus Putra',
      subtitle: 'Dekat Kampus & Kantor',
      count: '920+ Unit',
      icon: UserCheck,
      color: 'from-blue-500/10 to-indigo-500/10 text-blue-600 border-blue-200 hover:border-blue-400',
      badge: 'Parkir Luas'
    },
    {
      type: 'apartemen' as PropertyType,
      title: 'Apartemen Sewa',
      subtitle: 'Studio & 2BR Full Furnished',
      count: '640+ Unit',
      icon: Building2,
      color: 'from-purple-500/10 to-violet-500/10 text-purple-600 border-purple-200 hover:border-purple-400',
      badge: 'Fasilitas Gym & Pool'
    },
    {
      type: 'villa' as PropertyType,
      title: 'Villa & Co-Living',
      subtitle: 'Bali, Jogja & Puncak WFA',
      count: '310+ Unit',
      icon: Palmtree,
      color: 'from-amber-500/10 to-orange-500/10 text-amber-600 border-amber-200 hover:border-amber-400',
      badge: 'Private Pool'
    },
    {
      type: 'kontrakan' as PropertyType,
      title: 'Rumah Kontrakan',
      subtitle: 'Hunian Keluarga & Profesional',
      count: '480+ Unit',
      icon: Home,
      color: 'from-cyan-500/10 to-sky-500/10 text-cyan-600 border-cyan-200 hover:border-cyan-400',
      badge: 'Cluster One-Gate'
    }
  ];

  return (
    <section className="py-12 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between mb-8">
          <div>
            <h2 className="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
              Pilihan Kategori Hunian Terpopuler
            </h2>
            <p className="text-xs sm:text-sm text-slate-500 mt-1">
              Sesuaikan dengan kebutuhan privasi, fasilitas, dan tipe rental impian Anda.
            </p>
          </div>
        </div>

        <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 sm:gap-4">
          {categories.map((cat, idx) => {
            const Icon = cat.icon;
            return (
              <button
                key={idx}
                onClick={() => onSelectCategory(cat.type)}
                className={`p-4 rounded-2xl border bg-gradient-to-b ${cat.color} transition-all duration-200 hover:-translate-y-1 hover:shadow-card text-left flex flex-col justify-between group`}
              >
                <div>
                  <div className="flex items-center justify-between mb-3">
                    <div className="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center group-hover:scale-110 transition">
                      <Icon className="w-5 h-5" />
                    </div>
                    <span className="text-[10px] font-bold px-1.5 py-0.5 bg-white/80 rounded-full">
                      {cat.count}
                    </span>
                  </div>
                  <h3 className="font-bold text-slate-900 text-sm mb-1 leading-snug">
                    {cat.title}
                  </h3>
                  <p className="text-[11px] text-slate-600 line-clamp-1">
                    {cat.subtitle}
                  </p>
                </div>

                <div className="mt-3 pt-2 border-t border-slate-900/5">
                  <span className="text-[10px] font-extrabold uppercase tracking-wider text-slate-700">
                    {cat.badge}
                  </span>
                </div>
              </button>
            );
          })}
        </div>
      </div>
    </section>
  );
};
