import React, { useState, useMemo } from 'react';
import { 
  ArrowRight, 
  CheckCircle2, 
  Filter, 
  MapPin, 
  Search, 
  ShieldCheck, 
  SlidersHorizontal, 
  Sparkles, 
  TrendingUp, 
  Zap 
} from 'lucide-react';
import { PROPERTIES } from '../../data/properties';
import { Property, PropertyType } from '../../types';
import { PropertyCard } from '../explore/PropertyCard';
import { calculateDiscountPrice } from '../../utils/currency';

interface FeaturedSectionProps {
  onSelectProperty: (property: Property) => void;
  onQuickBook?: (property: Property) => void;
  favorites: string[];
  onToggleFavorite: (property: Property) => void;
  comparedProperties: Property[];
  onToggleCompare: (property: Property) => void;
  onExploreAll: () => void;
}

export const FeaturedSection: React.FC<FeaturedSectionProps> = ({
  onSelectProperty,
  onQuickBook,
  favorites,
  onToggleFavorite,
  comparedProperties,
  onToggleCompare,
  onExploreAll
}) => {
  // Category tab filter
  const [selectedCategory, setSelectedCategory] = useState<string>('all');
  
  // City quick pill filter
  const [selectedCity, setSelectedCity] = useState<string>('all');

  // Search input within home catalog
  const [searchQuery, setSearchQuery] = useState<string>('');

  // Sort order
  const [sortBy, setSortBy] = useState<'recommended' | 'price_low' | 'price_high' | 'rating'>('recommended');

  const categories = [
    { id: 'all', label: '✨ Semua Hunian', count: PROPERTIES.length },
    { id: 'kost_campur', label: '👫 Kost Campur', count: PROPERTIES.filter(p => p.propertyType === 'kost_campur').length },
    { id: 'kost_putri', label: '👩 Kost Putri', count: PROPERTIES.filter(p => p.propertyType === 'kost_putri').length },
    { id: 'kost_putra', label: '👨 Kost Putra', count: PROPERTIES.filter(p => p.propertyType === 'kost_putra').length },
    { id: 'apartemen', label: '🏢 Apartemen', count: PROPERTIES.filter(p => p.propertyType === 'apartemen').length },
    { id: 'villa', label: '🌴 Villa & Co-Living', count: PROPERTIES.filter(p => p.propertyType === 'villa').length },
    { id: 'kontrakan', label: '🏡 Kontrakan', count: PROPERTIES.filter(p => p.propertyType === 'kontrakan').length },
  ];

  const cities = [
    { id: 'all', label: 'Semua Kota' },
    { id: 'Jakarta Selatan', label: '📍 Jakarta Selatan' },
    { id: 'Yogyakarta', label: '📍 Yogyakarta (UGM)' },
    { id: 'Bali', label: '📍 Bali (Canggu/Ubud)' },
    { id: 'Bandung', label: '📍 Bandung (ITB)' },
    { id: 'Surabaya', label: '📍 Surabaya' },
  ];

  // Filter and sort computation showing ALL matching properties
  const displayedProperties = useMemo(() => {
    return PROPERTIES.filter(p => {
      // Category filter
      if (selectedCategory !== 'all' && p.propertyType !== selectedCategory) {
        return false;
      }

      // City filter
      if (selectedCity !== 'all' && p.city !== selectedCity) {
        return false;
      }

      // Search query
      if (searchQuery.trim()) {
        const q = searchQuery.toLowerCase();
        const matchTitle = p.title.toLowerCase().includes(q);
        const matchSub = p.subDistrict.toLowerCase().includes(q);
        const matchCity = p.city.toLowerCase().includes(q);
        const matchTransit = p.transitPoints.some(t => t.name.toLowerCase().includes(q));
        if (!matchTitle && !matchSub && !matchCity && !matchTransit) return false;
      }

      return true;
    }).sort((a, b) => {
      const priceA = calculateDiscountPrice(a.basePriceMonthly, a.discountPercent);
      const priceB = calculateDiscountPrice(b.basePriceMonthly, b.discountPercent);

      if (sortBy === 'price_low') return priceA - priceB;
      if (sortBy === 'price_high') return priceB - priceA;
      if (sortBy === 'rating') return b.rating - a.rating;
      return 0; // default recommended
    });
  }, [selectedCategory, selectedCity, searchQuery, sortBy]);

  return (
    <section className="py-14 bg-slate-50 border-t border-slate-200/80">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        {/* Section Header */}
        <div className="flex flex-col lg:flex-row lg:items-end justify-between gap-6">
          <div className="space-y-2">
            <div className="inline-flex items-center gap-2 px-3 py-1 bg-emerald-100 text-emerald-900 rounded-full text-xs font-black tracking-wide">
              <Sparkles className="w-3.5 h-3.5 text-emerald-600" />
              <span>KATALOG RESMI LENGKAP • 100% FOTO ASLI</span>
            </div>
            
            <h2 className="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
              Semua Pilihan Hunian Tersedia Siap Huni
            </h2>
            
            <p className="text-xs sm:text-sm text-slate-600 max-w-2xl leading-relaxed">
              Jelajahi seluruh direktori kost mahasiswa, apartemen modern, villa tropis, dan rumah kontrakan di kota-kota strategis Indonesia dengan jaminan kepastian kamar & verifikasi resmi.
            </p>
          </div>

          <div className="flex items-center gap-3">
            <div className="px-4 py-2 bg-white rounded-2xl border border-slate-200 shadow-sm text-xs text-slate-600 font-bold flex items-center gap-2">
              <span className="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
              <span><strong>{displayedProperties.length}</strong> Properti Tersedia</span>
            </div>

            <button
              onClick={onExploreAll}
              className="px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl text-xs font-bold shadow transition flex items-center gap-1.5"
            >
              <span>Mode Peta Lengkap</span>
              <ArrowRight className="w-4 h-4" />
            </button>
          </div>
        </div>

        {/* Interactive Filter Toolbar in Home Catalog */}
        <div className="bg-white rounded-3xl p-5 sm:p-6 border border-slate-200 shadow-sm space-y-5">
          
          {/* Category Tabs */}
          <div className="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
            {categories.map((cat) => (
              <button
                key={cat.id}
                type="button"
                onClick={() => setSelectedCategory(cat.id)}
                className={`px-4 py-2.5 rounded-2xl text-xs font-extrabold transition whitespace-nowrap flex items-center gap-1.5 ${
                  selectedCategory === cat.id
                    ? 'bg-emerald-600 text-white shadow-md shadow-emerald-600/20'
                    : 'bg-slate-100 hover:bg-slate-200 text-slate-700'
                }`}
              >
                <span>{cat.label}</span>
                <span className={`text-[10px] px-1.5 py-0.2 rounded-full ${
                  selectedCategory === cat.id ? 'bg-emerald-800 text-emerald-100' : 'bg-slate-200 text-slate-600'
                }`}>
                  {cat.count}
                </span>
              </button>
            ))}
          </div>

          {/* City Pills & Search Bar & Sort Dropdown */}
          <div className="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 pt-3 border-t border-slate-100 text-xs">
            
            {/* City Pills */}
            <div className="flex items-center gap-1.5 overflow-x-auto pb-1">
              <span className="text-slate-400 font-bold text-[11px] mr-1 hidden sm:inline">Wilayah:</span>
              {cities.map((city) => (
                <button
                  key={city.id}
                  type="button"
                  onClick={() => setSelectedCity(city.id)}
                  className={`px-3 py-1.5 rounded-xl font-bold transition whitespace-nowrap ${
                    selectedCity === city.id
                      ? 'bg-slate-900 text-white'
                      : 'bg-slate-50 hover:bg-slate-100 text-slate-600 border border-slate-200/80'
                  }`}
                >
                  {city.label}
                </button>
              ))}
            </div>

            {/* Search & Sort Controls */}
            <div className="flex items-center gap-2">
              <div className="relative flex-1 sm:w-60">
                <Search className="w-3.5 h-3.5 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                <input
                  type="text"
                  placeholder="Cari area, kampus, stasiun..."
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  className="w-full pl-8 pr-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-emerald-500 font-medium"
                />
              </div>

              <select
                value={sortBy}
                onChange={(e) => setSortBy(e.target.value as any)}
                className="px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-700 focus:outline-none focus:border-emerald-500 cursor-pointer"
              >
                <option value="recommended">⭐ Terpopuler</option>
                <option value="price_low">💵 Harga Terendah</option>
                <option value="price_high">💎 Harga Tertinggi</option>
                <option value="rating">🌟 Rating Tertinggi</option>
              </select>
            </div>

          </div>

        </div>

        {/* All Available Properties Grid */}
        {displayedProperties.length === 0 ? (
          <div className="bg-white rounded-3xl p-12 text-center border border-slate-200 space-y-3">
            <div className="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-slate-400">
              <MapPin className="w-6 h-6" />
            </div>
            <h4 className="font-extrabold text-slate-800 text-base">Tidak ada hunian yang cocok dengan filter</h4>
            <p className="text-xs text-slate-500 max-w-sm mx-auto">
              Coba ganti kategori atau pilih "Semua Kota" untuk melihat hunian lainnya.
            </p>
            <button
              onClick={() => {
                setSelectedCategory('all');
                setSelectedCity('all');
                setSearchQuery('');
              }}
              className="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow transition"
            >
              Reset Semua Filter
            </button>
          </div>
        ) : (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {displayedProperties.map((property) => (
              <PropertyCard
                key={property.id}
                property={property}
                onSelect={onSelectProperty}
                onQuickBook={onQuickBook}
                isFavorite={favorites.includes(property.id)}
                onToggleFavorite={onToggleFavorite}
                isCompared={comparedProperties.some(p => p.id === property.id)}
                onToggleCompare={onToggleCompare}
              />
            ))}
          </div>
        )}

      </div>
    </section>
  );
};
