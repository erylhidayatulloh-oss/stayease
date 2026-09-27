import React, { useState } from 'react';
import { 
  Building, 
  ChevronDown, 
  Heart, 
  Layers, 
  MapPin, 
  Menu, 
  PlusCircle, 
  Search, 
  ShieldCheck, 
  Sparkles, 
  User, 
  X 
} from 'lucide-react';
import { CityHub } from '../../types';

interface NavbarProps {
  currentView: string;
  onNavigate: (view: string, payload?: any) => void;
  favoritesCount: number;
  onOpenFavorites: () => void;
  compareCount: number;
  onOpenCompare: () => void;
  onOpenSeoInspector: () => void;
  selectedCity: string;
  onSelectCity: (city: string) => void;
  cities: CityHub[];
}

export const Navbar: React.FC<NavbarProps> = ({
  currentView,
  onNavigate,
  favoritesCount,
  onOpenFavorites,
  compareCount,
  onOpenCompare,
  onOpenSeoInspector,
  selectedCity,
  onSelectCity,
  cities
}) => {
  const [isCityDropdownOpen, setIsCityDropdownOpen] = useState(false);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);

  return (
    <header className="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-20 gap-4">
          
          {/* Brand Logo & Tagline */}
          <div className="flex items-center gap-6">
            <button 
              onClick={() => onNavigate('home')}
              className="flex items-center gap-2.5 text-left group"
            >
              <div className="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 flex items-center justify-center text-white shadow-md shadow-emerald-500/20 group-hover:scale-105 transition">
                <Building className="w-5 h-5" />
              </div>
              <div>
                <div className="flex items-center gap-1.5">
                  <span className="text-xl font-extrabold tracking-tight text-slate-900">
                    Stay<span className="text-emerald-600">ease</span>
                  </span>
                  <span className="px-1.5 py-0.5 text-[10px] font-bold bg-emerald-100 text-emerald-800 rounded border border-emerald-300">
                    ID
                  </span>
                </div>
                <div className="flex items-center gap-1 text-[11px] font-semibold text-slate-500">
                  <ShieldCheck className="w-3 h-3 text-emerald-600" />
                  <span>Official Rent Platform</span>
                </div>
              </div>
            </button>

            {/* City Selector Dropdown (Desktop) */}
            <div className="hidden lg:relative lg:block">
              <button
                onClick={() => setIsCityDropdownOpen(!isCityDropdownOpen)}
                className="flex items-center gap-2 px-3.5 py-2 bg-slate-100/80 hover:bg-slate-200/80 rounded-xl text-xs font-semibold text-slate-700 transition"
              >
                <MapPin className="w-3.5 h-3.5 text-emerald-600" />
                <span>{selectedCity || 'Semua Kota'}</span>
                <ChevronDown className="w-3.5 h-3.5 text-slate-400" />
              </button>

              {isCityDropdownOpen && (
                <div className="absolute top-full left-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-200 py-2 z-50 animate-fadeIn">
                  <div className="px-3 py-1.5 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                    Pilih Kota Populer
                  </div>
                  <button
                    onClick={() => {
                      onSelectCity('');
                      setIsCityDropdownOpen(false);
                      onNavigate('explore');
                    }}
                    className={`w-full text-left px-3.5 py-2 text-xs font-medium hover:bg-emerald-50 hover:text-emerald-700 flex items-center justify-between ${
                      !selectedCity ? 'text-emerald-700 font-bold bg-emerald-50/50' : 'text-slate-700'
                    }`}
                  >
                    <span>Seluruh Indonesia</span>
                    <span className="text-[10px] text-slate-400">4,700+ Listing</span>
                  </button>
                  {cities.map((c) => (
                    <button
                      key={c.id}
                      onClick={() => {
                        onSelectCity(c.name);
                        setIsCityDropdownOpen(false);
                        onNavigate('city-hub', c.slug);
                      }}
                      className={`w-full text-left px-3.5 py-2 text-xs font-medium hover:bg-emerald-50 hover:text-emerald-700 flex items-center justify-between ${
                        selectedCity === c.name ? 'text-emerald-700 font-bold bg-emerald-50/50' : 'text-slate-700'
                      }`}
                    >
                      <div className="flex items-center gap-2">
                        <img src={c.image} alt={c.name} className="w-5 h-5 rounded-full object-cover" />
                        <span>{c.name}</span>
                      </div>
                      <span className="text-[10px] text-slate-400">{c.totalProperties} Kost & Apt</span>
                    </button>
                  ))}
                </div>
              )}
            </div>

          </div>

          {/* Center Navigation Links (Desktop) */}
          <nav className="hidden md:flex items-center gap-1 bg-slate-100/70 p-1.5 rounded-2xl">
            <button
              onClick={() => onNavigate('home')}
              className={`px-4 py-2 text-xs font-bold rounded-xl transition ${
                currentView === 'home' 
                  ? 'bg-white text-emerald-700 shadow-sm' 
                  : 'text-slate-600 hover:text-slate-900'
              }`}
            >
              Beranda
            </button>
            <button
              onClick={() => onNavigate('explore')}
              className={`px-4 py-2 text-xs font-bold rounded-xl transition ${
                currentView === 'explore' 
                  ? 'bg-white text-emerald-700 shadow-sm' 
                  : 'text-slate-600 hover:text-slate-900'
              }`}
            >
              Cari Kost & Apartemen
            </button>
            <button
              onClick={() => onNavigate('owner-wizard')}
              className={`px-4 py-2 text-xs font-bold rounded-xl transition ${
                currentView === 'owner-wizard' 
                  ? 'bg-white text-emerald-700 shadow-sm' 
                  : 'text-slate-600 hover:text-slate-900'
              }`}
            >
              Mitra Pemilik
            </button>
          </nav>

          {/* Right Action Icons & Controls */}
          <div className="flex items-center gap-2 sm:gap-3">
            
            {/* Live SEO Inspector Trigger Button */}
            <button
              onClick={onOpenSeoInspector}
              title="Inspect SEO & Schema.org JSON-LD"
              className="p-2 sm:px-3 sm:py-2 bg-gradient-to-r from-slate-900 to-slate-800 text-emerald-400 hover:text-emerald-300 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-sm border border-slate-700 transition hover:scale-105"
            >
              <Sparkles className="w-4 h-4 text-emerald-400" />
              <span className="hidden sm:inline">SEO Inspector</span>
            </button>

            {/* Compare Button */}
            <button
              onClick={onOpenCompare}
              className="relative p-2 text-slate-600 hover:text-emerald-600 hover:bg-slate-100 rounded-xl transition"
              title="Bandingkan Properti"
            >
              <Layers className="w-5 h-5" />
              {compareCount > 0 && (
                <span className="absolute -top-1 -right-1 w-5 h-5 bg-amber-500 text-white text-[10px] font-black rounded-full flex items-center justify-center animate-bounce">
                  {compareCount}
                </span>
              )}
            </button>

            {/* Wishlist Button */}
            <button
              onClick={onOpenFavorites}
              className="relative p-2 text-slate-600 hover:text-rose-600 hover:bg-slate-100 rounded-xl transition"
              title="Favorit Saya"
            >
              <Heart className="w-5 h-5" />
              {favoritesCount > 0 && (
                <span className="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 text-white text-[10px] font-black rounded-full flex items-center justify-center">
                  {favoritesCount}
                </span>
              )}
            </button>

            {/* Owner CTA Button */}
            <button
              onClick={() => onNavigate('owner-wizard')}
              className="hidden lg:flex items-center gap-1.5 px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-xl text-xs font-bold transition"
            >
              <PlusCircle className="w-4 h-4 text-emerald-600" />
              <span>Pasang Iklan</span>
            </button>

            {/* Masuk / Dashboard (links to the real Laravel login page).
                "/login" auto-redirects to the right dashboard if the visitor
                is already authenticated, so this one link correctly covers
                both "please log in" and "go to my dashboard". */}
            <a
              href="/login"
              className="flex items-center gap-2 p-1.5 sm:px-3 sm:py-2 bg-slate-100 hover:bg-slate-200 rounded-xl text-xs font-semibold text-slate-700 transition"
            >
              <User className="w-4 h-4 text-slate-500" />
              <span className="hidden sm:inline">Masuk / Dashboard</span>
            </a>

            {/* Mobile Menu Button */}
            <button
              onClick={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
              className="md:hidden p-2 text-slate-700 hover:bg-slate-100 rounded-xl"
            >
              {isMobileMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
            </button>

          </div>

        </div>
      </div>

      {/* Mobile Drawer Menu */}
      {isMobileMenuOpen && (
        <div className="md:hidden bg-white border-b border-slate-200 p-4 space-y-3 animate-fadeIn">
          <div className="grid grid-cols-2 gap-2">
            <button
              onClick={() => { onNavigate('home'); setIsMobileMenuOpen(false); }}
              className="p-3 text-center text-xs font-bold bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 rounded-xl border border-slate-100"
            >
              Beranda
            </button>
            <button
              onClick={() => { onNavigate('explore'); setIsMobileMenuOpen(false); }}
              className="p-3 text-center text-xs font-bold bg-slate-50 hover:bg-emerald-50 hover:text-emerald-700 rounded-xl border border-slate-100"
            >
              Cari Kost & Apt
            </button>
            <button
              onClick={() => { onNavigate('owner-wizard'); setIsMobileMenuOpen(false); }}
              className="p-3 text-center text-xs font-bold bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-200 col-span-2 flex items-center justify-center gap-1.5"
            >
              <PlusCircle className="w-4 h-4" />
              Pasang Iklan Kost / Apartemen
            </button>
          </div>

          <div className="pt-2 border-t border-slate-100">
            <div className="text-xs font-bold text-slate-400 mb-2">Pilih Kota:</div>
            <div className="flex flex-wrap gap-1.5">
              {cities.map(c => (
                <button
                  key={c.id}
                  onClick={() => {
                    onSelectCity(c.name);
                    onNavigate('city-hub', c.slug);
                    setIsMobileMenuOpen(false);
                  }}
                  className="px-2.5 py-1 text-xs bg-slate-100 hover:bg-emerald-100 hover:text-emerald-800 rounded-lg"
                >
                  {c.name}
                </button>
              ))}
            </div>
          </div>
        </div>
      )}
    </header>
  );
};
