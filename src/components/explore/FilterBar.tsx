import React, { useState } from 'react';
import { 
  Check, 
  ChevronDown, 
  DollarSign, 
  Filter, 
  Home, 
  MapPin, 
  RotateCcw, 
  Search, 
  ShieldCheck, 
  SlidersHorizontal, 
  Sparkles, 
  Users, 
  Video, 
  Wifi, 
  X, 
  Zap 
} from 'lucide-react';
import { CITIES } from '../../data/cities';
import { FilterState, PropertyType, RentalPeriod } from '../../types';
import { formatCompactRupiah } from '../../utils/currency';

interface FilterBarProps {
  filters: FilterState;
  onFilterChange: (newFilters: FilterState) => void;
  totalResults: number;
}

export const FilterBar: React.FC<FilterBarProps> = ({
  filters,
  onFilterChange,
  totalResults
}) => {
  const [isAdvancedOpen, setIsAdvancedOpen] = useState(false);

  const availableFacilities = [
    'AC',
    'Wi-Fi',
    'Kamar Mandi Dalam',
    'Water Heater',
    'Parkir Mobil',
    'Dapur Bersama',
    'Include Listrik',
    'Smart TV'
  ];

  const handleFacilityToggle = (facility: string) => {
    const exists = filters.facilities.includes(facility);
    const updated = exists
      ? filters.facilities.filter(f => f !== facility)
      : [...filters.facilities, facility];
    onFilterChange({ ...filters, facilities: updated });
  };

  const handleReset = () => {
    onFilterChange({
      searchQuery: '',
      city: '',
      propertyType: 'all',
      rentalPeriod: 'bulanan',
      minPrice: 0,
      maxPrice: 15000000,
      gender: 'all',
      facilities: [],
      verifiedOnly: false,
      virtualTourOnly: false,
      sortBy: 'recommended'
    });
  };

  const activeFiltersCount = 
    (filters.city ? 1 : 0) +
    (filters.propertyType !== 'all' ? 1 : 0) +
    (filters.gender !== 'all' ? 1 : 0) +
    filters.facilities.length +
    (filters.verifiedOnly ? 1 : 0) +
    (filters.virtualTourOnly ? 1 : 0) +
    (filters.searchQuery ? 1 : 0);

  return (
    <div className="bg-white rounded-3xl border border-slate-200/80 shadow-subtle p-4 sm:p-5 space-y-4">
      
      {/* Top Search & Primary Filters Row */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3 items-center">
        
        {/* Search Keyword */}
        <div className="lg:col-span-4 relative">
          <Search className="w-4 h-4 text-emerald-600 absolute left-3.5 top-1/2 -translate-y-1/2" />
          <input
            type="text"
            placeholder="Cari nama kost, jalan, kampus (UGM/ITB), stasiun..."
            value={filters.searchQuery}
            onChange={(e) => onFilterChange({ ...filters, searchQuery: e.target.value })}
            className="w-full pl-10 pr-4 py-2.5 bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-xs font-semibold rounded-2xl border border-slate-200 focus:border-emerald-500 focus:outline-none transition"
          />
        </div>

        {/* City Filter */}
        <div className="lg:col-span-3">
          <div className="relative">
            <MapPin className="w-4 h-4 text-emerald-600 absolute left-3.5 top-1/2 -translate-y-1/2" />
            <select
              value={filters.city}
              onChange={(e) => onFilterChange({ ...filters, city: e.target.value })}
              aria-label="Pilih Kota"
              className="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-xs font-semibold rounded-2xl border border-slate-200 focus:border-emerald-500 focus:outline-none appearance-none cursor-pointer"
            >
              <option value="">Semua Kota</option>
              {CITIES.map(c => (
                <option key={c.id} value={c.name}>{c.name}</option>
              ))}
            </select>
            <ChevronDown className="w-3.5 h-3.5 text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none" />
          </div>
        </div>

        {/* Property Type Filter */}
        <div className="lg:col-span-3">
          <div className="relative">
            <Home className="w-4 h-4 text-emerald-600 absolute left-3.5 top-1/2 -translate-y-1/2" />
            <select
              value={filters.propertyType}
              onChange={(e) => onFilterChange({ ...filters, propertyType: e.target.value as any })}
              aria-label="Pilih Tipe Properti"
              className="w-full pl-10 pr-8 py-2.5 bg-slate-50 hover:bg-slate-100/80 focus:bg-white text-xs font-semibold rounded-2xl border border-slate-200 focus:border-emerald-500 focus:outline-none appearance-none cursor-pointer"
            >
              <option value="all">Semua Tipe Properti</option>
              <option value="kost_campur">Kost Campur</option>
              <option value="kost_putri">Kost Khusus Putri</option>
              <option value="kost_putra">Kost Khusus Putra</option>
              <option value="apartemen">Apartemen</option>
              <option value="villa">Villa / Co-Living</option>
              <option value="kontrakan">Rumah Kontrakan</option>
            </select>
            <ChevronDown className="w-3.5 h-3.5 text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none" />
          </div>
        </div>

        {/* Toggle Advanced Button */}
        <div className="lg:col-span-2 flex items-center gap-2">
          <button
            onClick={() => setIsAdvancedOpen(!isAdvancedOpen)}
            className={`w-full py-2.5 px-3 rounded-2xl text-xs font-bold flex items-center justify-center gap-1.5 border transition ${
              isAdvancedOpen || activeFiltersCount > 0
                ? 'bg-emerald-50 text-emerald-800 border-emerald-300'
                : 'bg-slate-50 hover:bg-slate-100 text-slate-700 border-slate-200'
            }`}
          >
            <SlidersHorizontal className="w-3.5 h-3.5 text-emerald-600" />
            <span>Filter</span>
            {activeFiltersCount > 0 && (
              <span className="w-4 h-4 bg-emerald-600 text-white rounded-full text-[10px] font-black flex items-center justify-center">
                {activeFiltersCount}
              </span>
            )}
          </button>
        </div>

      </div>

      {/* Quick Pills Bar: Sort, Price & Results count */}
      <div className="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-slate-100 text-xs">
        
        {/* Gender Pills */}
        <div className="flex items-center gap-1.5 overflow-x-auto no-scrollbar">
          <span className="text-slate-400 font-bold text-[11px] mr-1">Tipe Kost:</span>
          {(['all', 'putri', 'putra', 'campur'] as const).map((g) => (
            <button
              key={g}
              onClick={() => onFilterChange({ ...filters, gender: g })}
              className={`px-3 py-1 rounded-xl text-xs font-semibold capitalize transition ${
                filters.gender === g
                  ? 'bg-slate-900 text-white shadow-sm'
                  : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
              }`}
            >
              {g === 'all' ? 'Semua' : `Khusus ${g}`}
            </button>
          ))}
        </div>

        {/* Sort Dropdown */}
        <div className="flex items-center gap-2">
          <span className="text-slate-400 font-semibold text-[11px]">Urutkan:</span>
          <select
            value={filters.sortBy}
            onChange={(e) => onFilterChange({ ...filters, sortBy: e.target.value as any })}
            aria-label="Urutkan Hasil Pencarian"
            className="bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold py-1 px-2.5 rounded-xl border-none focus:outline-none cursor-pointer"
          >
            <option value="recommended">Paling Sesuai</option>
            <option value="price_low">Harga Terendah</option>
            <option value="price_high">Harga Tertinggi</option>
            <option value="rating">Rating Tertinggi (4.8+)</option>
          </select>
        </div>

      </div>

      {/* Advanced Filter Drawer */}
      {isAdvancedOpen && (
        <div className="p-4 bg-slate-50 rounded-2xl border border-slate-200 space-y-4 animate-fadeIn">
          
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            
            {/* Price Range Slider */}
            <div className="space-y-2">
              <div className="flex justify-between text-xs font-bold text-slate-700">
                <span>Batas Harga Sewa:</span>
                <span className="text-emerald-700">{formatCompactRupiah(filters.maxPrice)}/bln</span>
              </div>
              <input
                type="range"
                min="500000"
                max="15000000"
                step="500000"
                value={filters.maxPrice}
                onChange={(e) => onFilterChange({ ...filters, maxPrice: Number(e.target.value) })}
                className="w-full accent-emerald-600 cursor-pointer"
              />
              <div className="flex justify-between text-[10px] text-slate-400 font-semibold">
                <span>Rp 500 Rb</span>
                <span>Rp 15 Jt+</span>
              </div>
            </div>

            {/* Special Feature Toggles */}
            <div className="space-y-2">
              <span className="block text-xs font-bold text-slate-700">Jaminan Kualitas:</span>
              <div className="space-y-1.5">
                <label className="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                  <input
                    type="checkbox"
                    checked={filters.verifiedOnly}
                    onChange={(e) => onFilterChange({ ...filters, verifiedOnly: e.target.checked })}
                    className="rounded accent-emerald-600"
                  />
                  <ShieldCheck className="w-3.5 h-3.5 text-emerald-600" />
                  <span>100% Official Stayease Verified</span>
                </label>

                <label className="flex items-center gap-2 text-xs font-semibold text-slate-700 cursor-pointer">
                  <input
                    type="checkbox"
                    checked={filters.virtualTourOnly}
                    onChange={(e) => onFilterChange({ ...filters, virtualTourOnly: e.target.checked })}
                    className="rounded accent-emerald-600"
                  />
                  <Video className="w-3.5 h-3.5 text-teal-600" />
                  <span>Tersedia Virtual Tour 360°</span>
                </label>
              </div>
            </div>

            {/* Facilities Checklist */}
            <div className="space-y-2">
              <span className="block text-xs font-bold text-slate-700">Fasilitas Utama:</span>
              <div className="flex flex-wrap gap-1.5">
                {availableFacilities.map((facility) => {
                  const isChecked = filters.facilities.includes(facility);
                  return (
                    <button
                      key={facility}
                      type="button"
                      onClick={() => handleFacilityToggle(facility)}
                      className={`px-2.5 py-1 rounded-lg text-xs font-medium border transition ${
                        isChecked
                          ? 'bg-emerald-600 text-white border-emerald-600'
                          : 'bg-white text-slate-700 border-slate-200 hover:border-slate-300'
                      }`}
                    >
                      {facility}
                    </button>
                  );
                })}
              </div>
            </div>

          </div>

          {/* Reset Filters & Close */}
          <div className="pt-2 border-t border-slate-200 flex items-center justify-between">
            <button
              onClick={handleReset}
              className="text-xs font-bold text-rose-600 hover:text-rose-700 flex items-center gap-1"
            >
              <RotateCcw className="w-3 h-3" />
              <span>Reset Semua Filter</span>
            </button>

            <button
              onClick={() => setIsAdvancedOpen(false)}
              className="px-4 py-1.5 bg-slate-900 text-white rounded-xl text-xs font-bold"
            >
              Terapkan Filter ({totalResults} Ditemukan)
            </button>
          </div>

        </div>
      )}

    </div>
  );
};
