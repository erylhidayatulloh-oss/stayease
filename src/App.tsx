import React, { useState, useEffect, useMemo } from 'react';
import { 
  ArrowLeft, 
  ArrowRight, 
  CheckCircle2, 
  ChevronRight, 
  Heart, 
  Layers, 
  MapPin, 
  Phone, 
  Share2, 
  ShieldCheck, 
  Sparkles, 
  Star, 
  Train, 
  X, 
  Zap 
} from 'lucide-react';
import { Navbar } from './components/layout/Navbar';
import { Footer } from './components/layout/Footer';
import { SeoHead } from './components/layout/SeoHead';
import { SeoInspectorModal } from './components/seo/SeoInspectorModal';
import { HeroSearch } from './components/home/HeroSearch';
import { CategoryBanners } from './components/home/CategoryBanners';
import { PromoCarousel } from './components/home/PromoCarousel';
import { CityGrid } from './components/home/CityGrid';
import { FeaturedSection } from './components/home/FeaturedSection';
import { Testimonials } from './components/home/Testimonials';
import { LandlordCta } from './components/home/LandlordCta';
import { FilterBar } from './components/explore/FilterBar';
import { PropertyCard } from './components/explore/PropertyCard';
import { InteractiveMap } from './components/explore/InteractiveMap';
import { CompareDrawer } from './components/explore/CompareDrawer';
import { PropertyGallery } from './components/detail/PropertyGallery';
import { UnitSelector } from './components/detail/UnitSelector';
import { FacilityIcons } from './components/detail/FacilityIcons';
import { TransitMatrix } from './components/detail/TransitMatrix';
import { ReviewsSection } from './components/detail/ReviewsSection';
import { LandlordCard } from './components/detail/LandlordCard';
import { BookingStickyBar } from './components/detail/BookingStickyBar';
import { BookingModal } from './components/booking/BookingModal';
import { ListPropertyWizard } from './components/owner/ListPropertyWizard';
import { WishlistModal } from './components/common/WishlistModal';
import { ChatModal } from './components/common/ChatModal';
import { CityLandingContent } from './components/seo/CityLandingContent';

import { PROPERTIES } from './data/properties';
import { CITIES } from './data/cities';
import { PROMO_VOUCHERS } from './data/promos';
import { fetchAllCities, fetchAllProperties, fetchAllPromos, fetchWishlist, toggleWishlist as apiToggleWishlist } from './api/stayease';
import { getGuestIdentity } from './utils/guestIdentity';
import { FilterState, Property, PromoVoucher, PropertyType, RentalPeriod, RoomUnit } from './types';
import { generateBreadcrumbSchema, generateCityFaqSchema, generatePropertySchema } from './utils/seoGenerator';
import { calculateDiscountPrice, formatRupiah } from './utils/currency';

export const App: React.FC = () => {
  const [isDataReady, setIsDataReady] = useState(false);
  const [loadError, setLoadError] = useState<string | null>(null);

  useEffect(() => {
    let cancelled = false;

    (async () => {
      try {
        const [properties, cities, promos] = await Promise.all([
          fetchAllProperties(),
          fetchAllCities(),
          fetchAllPromos(),
        ]);
        if (cancelled) return;

        // Replace the module-level mock arrays in place so every component that
        // already imports PROPERTIES / CITIES / PROMO_VOUCHERS directly (without
        // needing a big prop-drilling refactor) sees the real backend data on
        // first render, since <AppContent/> only mounts after this resolves.
        if (properties.length) {
          PROPERTIES.length = 0;
          PROPERTIES.push(...properties);
        }
        if (cities.length) {
          CITIES.length = 0;
          CITIES.push(...cities);
        }
        if (promos.length) {
          PROMO_VOUCHERS.length = 0;
          PROMO_VOUCHERS.push(...promos);
        }
      } catch (err: any) {
        if (!cancelled) {
          setLoadError(err?.message || 'Gagal memuat data dari server Stayease.');
        }
      } finally {
        if (!cancelled) setIsDataReady(true);
      }
    })();

    return () => {
      cancelled = true;
    };
  }, []);

  if (!isDataReady) {
    return (
      <div className="min-h-screen flex flex-col items-center justify-center bg-slate-50 gap-3">
        <div className="w-10 h-10 border-4 border-emerald-600 border-t-transparent rounded-full animate-spin" />
        <p className="text-sm font-bold text-slate-500">Memuat hunian terbaru dari Stayease...</p>
      </div>
    );
  }

  if (loadError && PROPERTIES.length === 0) {
    return (
      <div className="min-h-screen flex flex-col items-center justify-center bg-slate-50 gap-3 px-6 text-center">
        <p className="text-sm font-bold text-rose-600">{loadError}</p>
        <p className="text-xs text-slate-500 max-w-sm">
          Tidak dapat terhubung ke server Stayease. Pastikan backend Laravel sedang berjalan, lalu muat ulang halaman ini.
        </p>
      </div>
    );
  }

  return <AppContent />;
};

const AppContent: React.FC = () => {
  // Navigation & View State
  const [currentView, setCurrentView] = useState<'home' | 'explore' | 'detail' | 'city-hub' | 'owner-wizard'>('home');
  const [selectedProperty, setSelectedProperty] = useState<Property | null>(PROPERTIES[0]);
  const [selectedCitySlug, setSelectedCitySlug] = useState<string>('jakarta-selatan');
  const [activeUnit, setActiveUnit] = useState<RoomUnit>(PROPERTIES[0].units[0]);

  // Wishlist State (Local Storage persistent)
  const [favorites, setFavorites] = useState<string[]>(() => {
    try {
      const saved = localStorage.getItem('stayease_favorites');
      return saved ? JSON.parse(saved) : ['kost-tebet-exclusive', 'villa-bohemia-canggu'];
    } catch {
      return ['kost-tebet-exclusive', 'villa-bohemia-canggu'];
    }
  });

  // Compare State (Up to 3 properties)
  const [comparedProperties, setComparedProperties] = useState<Property[]>(
    PROPERTIES.slice(0, 2)
  );

  // Modals
  const [isFavoritesModalOpen, setIsFavoritesModalOpen] = useState(false);
  const [isCompareModalOpen, setIsCompareModalOpen] = useState(false);
  const [isSeoInspectorOpen, setIsSeoInspectorOpen] = useState(false);
  const [isBookingModalOpen, setIsBookingModalOpen] = useState(false);
  const [isChatModalOpen, setIsChatModalOpen] = useState(false);

  // Booking Parameters for modal
  const [bookingParams, setBookingParams] = useState({
    durationMonths: 1,
    checkInDate: '2026-09-01',
    appliedPromo: PROMO_VOUCHERS[0] as PromoVoucher | null
  });

  // Toast notification
  const [toastMessage, setToastMessage] = useState<string | null>(null);

  const showToast = (msg: string) => {
    setToastMessage(msg);
    setTimeout(() => setToastMessage(null), 3000);
  };

  // Filter State
  const [filters, setFilters] = useState<FilterState>({
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

  // View toggle in explore: 'split' | 'list' | 'map'
  const [exploreLayout, setExploreLayout] = useState<'split' | 'list' | 'map'>('split');

  // Sync favorites to local storage
  useEffect(() => {
    try {
      localStorage.setItem('stayease_favorites', JSON.stringify(favorites));
    } catch {}
  }, [favorites]);

  // Bug fix: Wishlist used to be localStorage-only and never reached the
  // real `wishlists` table, so it was invisible to the tenant/mitra/admin
  // dashboards and didn't follow the visitor to another device. Once we
  // know who the visitor is (see guestIdentity.ts — captured the first
  // time they book, chat, or list a property), reconcile localStorage
  // with their real backend wishlist: anything already saved online is
  // pulled in, and anything only saved locally so far is pushed up.
  useEffect(() => {
    const identity = getGuestIdentity();
    if (!identity) return;

    (async () => {
      try {
        const backendIds = new Set(await fetchWishlist(identity.email).then(ids => ids.map(String)));

        const localOnly = favorites.filter(id => !backendIds.has(id));
        for (const id of localOnly) {
          try {
            await apiToggleWishlist(Number(id), identity);
            backendIds.add(id);
          } catch {
            // Best-effort — it stays in local favorites either way.
          }
        }

        setFavorites(prev => Array.from(new Set([...prev, ...Array.from(backendIds)])));
      } catch {
        // Backend unreachable — keep going with local-only favorites.
      }
    })();
    // Reconcile once on mount only; toggleFavorite keeps things in sync from here on.
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const toggleFavorite = (property: Property) => {
    const identity = getGuestIdentity();
    if (identity) {
      apiToggleWishlist(Number(property.id), identity).catch(() => {
        // Best-effort background sync; localStorage below stays the source
        // of truth for this browser regardless of network hiccups.
      });
    }

    if (favorites.includes(property.id)) {
      setFavorites(prev => prev.filter(id => id !== property.id));
      showToast(`Dihapus dari favorit: ${property.title}`);
    } else {
      setFavorites(prev => [...prev, property.id]);
      showToast(`Ditambahkan ke favorit: ${property.title}`);
    }
  };

  const toggleCompare = (property: Property) => {
    const exists = comparedProperties.some(p => p.id === property.id);
    if (exists) {
      setComparedProperties(prev => prev.filter(p => p.id !== property.id));
      showToast(`Dihapus dari perbandingan: ${property.title}`);
    } else {
      if (comparedProperties.length >= 3) {
        showToast('Maksimal 3 properti untuk perbandingan.');
        return;
      }
      setComparedProperties(prev => [...prev, property]);
      showToast(`Ditambahkan ke perbandingan: ${property.title}`);
      setIsCompareModalOpen(true);
    }
  };

  // Filtered Properties Computation
  const filteredProperties = useMemo(() => {
    return PROPERTIES.filter(p => {
      // Query filter
      if (filters.searchQuery) {
        const q = filters.searchQuery.toLowerCase();
        const matchTitle = p.title.toLowerCase().includes(q);
        const matchSubDistrict = p.subDistrict.toLowerCase().includes(q);
        const matchCity = p.city.toLowerCase().includes(q);
        const matchTransit = p.transitPoints.some(t => t.name.toLowerCase().includes(q));
        if (!matchTitle && !matchSubDistrict && !matchCity && !matchTransit) return false;
      }

      // City filter
      if (filters.city && !p.city.toLowerCase().includes(filters.city.toLowerCase())) {
        return false;
      }

      // Type filter
      if (filters.propertyType !== 'all' && p.propertyType !== filters.propertyType) {
        return false;
      }

      // Gender filter
      if (filters.gender !== 'all') {
        if (filters.gender === 'putra' && p.propertyType !== 'kost_putra') return false;
        if (filters.gender === 'putri' && p.propertyType !== 'kost_putri') return false;
        if (filters.gender === 'campur' && p.propertyType !== 'kost_campur') return false;
      }

      // Max price filter
      const finalPrice = calculateDiscountPrice(p.basePriceMonthly, p.discountPercent);
      if (finalPrice > filters.maxPrice) {
        return false;
      }

      // Verified filter
      if (filters.verifiedOnly && !p.verifiedOfficial) {
        return false;
      }

      // Virtual tour filter
      if (filters.virtualTourOnly && !p.hasVirtualTour) {
        return false;
      }

      // Facilities filter
      if (filters.facilities.length > 0) {
        const hasAllFacilities = filters.facilities.every(reqFac => 
          p.facilities.some(f => f.toLowerCase().includes(reqFac.toLowerCase()))
        );
        if (!hasAllFacilities) return false;
      }

      return true;
    }).sort((a, b) => {
      if (filters.sortBy === 'price_low') {
        return a.basePriceMonthly - b.basePriceMonthly;
      }
      if (filters.sortBy === 'price_high') {
        return b.basePriceMonthly - a.basePriceMonthly;
      }
      if (filters.sortBy === 'rating') {
        return b.rating - a.rating;
      }
      return 0; // recommended
    });
  }, [filters]);

  // Navigation Handler
  const handleNavigate = (view: string, payload?: any) => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
    if (view === 'city-hub') {
      setSelectedCitySlug(payload || 'jakarta-selatan');
      setCurrentView('city-hub');
    } else if (view === 'detail' && payload) {
      setSelectedProperty(payload);
      setActiveUnit(payload.units[0]);
      setCurrentView('detail');
    } else {
      if (view === 'explore' && payload?.search) {
        setFilters(prev => ({ ...prev, searchQuery: payload.search }));
      }
      setCurrentView(view as any);
    }
  };

  // Active City Data for City Hub
  const currentCityHub = useMemo(() => {
    return CITIES.find(c => c.slug === selectedCitySlug) || CITIES[0];
  }, [selectedCitySlug]);

  // Dynamic SEO Computation
  const currentSeo = useMemo(() => {
    if (currentView === 'detail' && selectedProperty) {
      return {
        title: selectedProperty.seo.metaTitle,
        description: selectedProperty.seo.metaDescription,
        keywords: selectedProperty.seo.keywords,
        url: `https://stayease.id/p/${selectedProperty.slug}`,
        ogImage: selectedProperty.images[0],
        schemaJson: generatePropertySchema(selectedProperty)
      };
    }
    if (currentView === 'city-hub' && currentCityHub) {
      return {
        title: `Sewa Kost & Apartemen di ${currentCityHub.name} Murah & Terlengkap | Stayease`,
        description: `Cari sewa kost putra, putri, campur, dan apartemen bulanan di ${currentCityHub.name}. Garansi 100% foto asli, dekat kampus & kantor.`,
        keywords: [`kost ${currentCityHub.name}`, `sewa apartemen ${currentCityHub.name}`, `kost murah ${currentCityHub.name}`],
        url: `https://stayease.id/kota/${currentCityHub.slug}`,
        ogImage: currentCityHub.image,
        schemaJson: generateCityFaqSchema(currentCityHub)
      };
    }
    if (currentView === 'owner-wizard') {
      return {
        title: 'Pasang Iklan Sewa Kost & Apartemen Gratis | Mitra Resmi Stayease',
        description: 'Daftarkan properti kost dan apartemen Anda di platform sewa terbesar di Indonesia. Okupansi hingga 98%, gratis foto 360°, dan pencairan dana instan.',
        keywords: ['pasang iklan kost gratis', 'mitra pemilik kost', 'manajemen kost indonesia'],
        url: 'https://stayease.id/pasang-iklan',
        ogImage: 'https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?auto=format&fit=crop&w=1200&h=630&q=80',
        schemaJson: null
      };
    }
    return {
      title: 'Stayease Indonesia | Platform Sewa Kost, Apartemen & Villa Terverifikasi No. 1',
      description: 'Platform resmi sewa hunian No. 1 di Indonesia. Temukan kost putra/putri, apartemen, dan villa di Jakarta, Bali, Bandung, Surabaya, Jogja dengan survey 360° dan bayar aman pakai QRIS.',
      keywords: ['sewa kost jakarta', 'kost ugm jogja', 'villa bulanan bali', 'apartemen surabaya', 'stayease indonesia'],
      url: 'https://stayease.id/',
      ogImage: 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&h=630&q=80',
      schemaJson: generateBreadcrumbSchema([
        { name: 'Beranda', url: '/' },
        { name: 'Cari Kost & Apartemen', url: '/explore' }
      ])
    };
  }, [currentView, selectedProperty, currentCityHub]);

  return (
    <div className="min-h-screen flex flex-col bg-slate-50 font-sans">
      
      {/* Dynamic SEO Injector */}
      <SeoHead
        title={currentSeo.title}
        description={currentSeo.description}
        keywords={currentSeo.keywords}
        canonicalUrl={currentSeo.url}
        ogImage={currentSeo.ogImage}
        schemaJson={currentSeo.schemaJson}
      />

      {/* Global Navbar */}
      <Navbar
        currentView={currentView}
        onNavigate={handleNavigate}
        favoritesCount={favorites.length}
        onOpenFavorites={() => setIsFavoritesModalOpen(true)}
        compareCount={comparedProperties.length}
        onOpenCompare={() => setIsCompareModalOpen(true)}
        onOpenSeoInspector={() => setIsSeoInspectorOpen(true)}
        selectedCity={filters.city}
        onSelectCity={(city) => setFilters(prev => ({ ...prev, city }))}
        cities={CITIES}
      />

      {/* Main Content Area */}
      <main className="flex-1">
        
        {/* VIEW 1: HOME */}
        {currentView === 'home' && (
          <div className="space-y-4">
            <HeroSearch
              onSearch={({ city, propertyType, rentalPeriod, maxPrice, query }) => {
                setFilters({
                  ...filters,
                  city,
                  propertyType,
                  rentalPeriod,
                  maxPrice,
                  searchQuery: query
                });
                setCurrentView('explore');
              }}
            />

            <CategoryBanners
              onSelectCategory={(type) => {
                setFilters({ ...filters, propertyType: type });
                setCurrentView('explore');
              }}
            />

            <PromoCarousel
              onApplyPromo={(voucher) => {
                showToast(`Kode Promo ${voucher.code} berhasil disalin!`);
              }}
            />

            <CityGrid
              onSelectCityHub={(slug) => {
                handleNavigate('city-hub', slug);
              }}
            />

            <FeaturedSection
              onSelectProperty={(prop) => handleNavigate('detail', prop)}
              onQuickBook={(prop) => {
                setSelectedProperty(prop);
                setActiveUnit(prop.units[0]);
                setIsBookingModalOpen(true);
              }}
              favorites={favorites}
              onToggleFavorite={toggleFavorite}
              comparedProperties={comparedProperties}
              onToggleCompare={toggleCompare}
              onExploreAll={() => setCurrentView('explore')}
            />

            <LandlordCta
              onOpenOwnerWizard={() => setCurrentView('owner-wizard')}
            />

            <Testimonials />
          </div>
        )}

        {/* VIEW 2: EXPLORE / SEARCH */}
        {currentView === 'explore' && (
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6 animate-fadeIn">
            
            {/* Breadcrumbs */}
            <div className="flex items-center justify-between">
              <nav className="flex items-center gap-2 text-xs text-slate-500 font-semibold">
                <button onClick={() => setCurrentView('home')} className="hover:text-emerald-700">Beranda</button>
                <span>/</span>
                <span className="text-slate-900 font-bold">Cari Kost & Apartemen</span>
              </nav>

              {/* View Layout Switcher (Split / List / Map) */}
              <div className="hidden lg:flex items-center gap-1 bg-slate-200/80 p-1 rounded-2xl text-xs font-bold">
                <button
                  onClick={() => setExploreLayout('split')}
                  className={`px-3 py-1.5 rounded-xl transition ${
                    exploreLayout === 'split' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'
                  }`}
                >
                  Split List & Peta
                </button>
                <button
                  onClick={() => setExploreLayout('list')}
                  className={`px-3 py-1.5 rounded-xl transition ${
                    exploreLayout === 'list' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'
                  }`}
                >
                  Hanya Daftar
                </button>
                <button
                  onClick={() => setExploreLayout('map')}
                  className={`px-3 py-1.5 rounded-xl transition ${
                    exploreLayout === 'map' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'
                  }`}
                >
                  Hanya Peta Penuh
                </button>
              </div>
            </div>

            {/* Filter Bar */}
            <FilterBar
              filters={filters}
              onFilterChange={setFilters}
              totalResults={filteredProperties.length}
            />

            {/* Content Layouts */}
            {exploreLayout === 'split' && (
              <div className="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                {/* Left Side: Property Grid (7 cols) */}
                <div className="lg:col-span-7 space-y-4">
                  <div className="flex items-center justify-between text-xs text-slate-500 font-semibold px-1">
                    <span>Ditemukan <strong>{filteredProperties.length}</strong> hunian terverifikasi</span>
                    <span className="text-emerald-700">● 100% Foto Real & Kontrak Legal</span>
                  </div>

                  {filteredProperties.length === 0 ? (
                    <div className="bg-white rounded-3xl p-12 text-center border border-slate-200 space-y-3">
                      <div className="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-slate-400">
                        <MapPin className="w-6 h-6" />
                      </div>
                      <h4 className="font-extrabold text-slate-800">Tidak ada properti yang cocok</h4>
                      <p className="text-xs text-slate-500 max-w-sm mx-auto">
                        Coba kurangi filter atau perlebar batas anggaran pencarian Anda.
                      </p>
                    </div>
                  ) : (
                    <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                      {filteredProperties.map((prop) => (
                        <PropertyCard
                          key={prop.id}
                          property={prop}
                          onSelect={(p) => handleNavigate('detail', p)}
                          onQuickBook={(p) => {
                            setSelectedProperty(p);
                            setActiveUnit(p.units[0]);
                            setIsBookingModalOpen(true);
                          }}
                          isFavorite={favorites.includes(prop.id)}
                          onToggleFavorite={toggleFavorite}
                          isCompared={comparedProperties.some(p => p.id === prop.id)}
                          onToggleCompare={toggleCompare}
                        />
                      ))}
                    </div>
                  )}
                </div>

                {/* Right Side: Interactive Map (5 cols sticky) */}
                <div className="lg:col-span-5 lg:sticky lg:top-24 h-[650px]">
                  <InteractiveMap
                    properties={filteredProperties}
                    selectedProperty={selectedProperty}
                    onSelectProperty={(p) => handleNavigate('detail', p)}
                    cityCenter={filters.city || 'Indonesia'}
                  />
                </div>

              </div>
            )}

            {exploreLayout === 'list' && (
              <div className="space-y-4">
                <div className="text-xs text-slate-500 font-semibold px-1">
                  Menampilkan <strong>{filteredProperties.length}</strong> properti
                </div>
                <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                  {filteredProperties.map((prop) => (
                    <PropertyCard
                      key={prop.id}
                      property={prop}
                      onSelect={(p) => handleNavigate('detail', p)}
                      onQuickBook={(p) => {
                        setSelectedProperty(p);
                        setActiveUnit(p.units[0]);
                        setIsBookingModalOpen(true);
                      }}
                      isFavorite={favorites.includes(prop.id)}
                      onToggleFavorite={toggleFavorite}
                      isCompared={comparedProperties.some(p => p.id === prop.id)}
                      onToggleCompare={toggleCompare}
                    />
                  ))}
                </div>
              </div>
            )}

            {exploreLayout === 'map' && (
              <div className="h-[750px]">
                <InteractiveMap
                  properties={filteredProperties}
                  selectedProperty={selectedProperty}
                  onSelectProperty={(p) => handleNavigate('detail', p)}
                  cityCenter={filters.city || 'Indonesia'}
                />
              </div>
            )}

          </div>
        )}

        {/* VIEW 3: PROPERTY DETAIL */}
        {currentView === 'detail' && selectedProperty && (
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8 animate-fadeIn">
            
            {/* Top Navigation & Action Share Bar */}
            <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
              <button
                onClick={() => setCurrentView('explore')}
                className="inline-flex items-center gap-2 text-xs font-bold text-slate-600 hover:text-emerald-700 transition"
              >
                <ArrowLeft className="w-4 h-4" />
                <span>Kembali ke Pencarian</span>
              </button>

              <div className="flex items-center gap-2">
                <button
                  onClick={() => setIsSeoInspectorOpen(true)}
                  className="px-3 py-1.5 bg-slate-900 text-emerald-400 hover:text-emerald-300 text-xs font-bold rounded-xl flex items-center gap-1.5 shadow-sm"
                >
                  <Sparkles className="w-3.5 h-3.5" />
                  <span>Inspect JSON-LD Listing Ini</span>
                </button>

                <button
                  onClick={() => toggleCompare(selectedProperty)}
                  className={`px-3 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1.5 transition ${
                    comparedProperties.some(p => p.id === selectedProperty.id)
                      ? 'bg-amber-500 text-white'
                      : 'bg-slate-100 text-slate-700 hover:bg-slate-200'
                  }`}
                >
                  <Layers className="w-3.5 h-3.5" />
                  <span>Bandingkan</span>
                </button>

                <button
                  onClick={() => toggleFavorite(selectedProperty)}
                  className={`px-3 py-1.5 rounded-xl text-xs font-bold flex items-center gap-1.5 transition ${
                    favorites.includes(selectedProperty.id)
                      ? 'bg-rose-500 text-white'
                      : 'bg-slate-100 text-slate-700 hover:bg-slate-200'
                  }`}
                >
                  <Heart className={`w-3.5 h-3.5 ${favorites.includes(selectedProperty.id) ? 'fill-current' : ''}`} />
                  <span>Favorit</span>
                </button>
              </div>
            </div>

            {/* Title & Header Badges */}
            <div className="space-y-2">
              <div className="flex flex-wrap items-center gap-2">
                <span className="px-3 py-1 bg-emerald-100 text-emerald-800 text-xs font-extrabold rounded-full">
                  {selectedProperty.genderRestriction || selectedProperty.propertyType.toUpperCase()}
                </span>
                {selectedProperty.verifiedOfficial && (
                  <span className="px-3 py-1 bg-slate-900 text-emerald-400 text-xs font-extrabold rounded-full flex items-center gap-1">
                    <ShieldCheck className="w-3.5 h-3.5" />
                    <span>Official Stayease Terverifikasi</span>
                  </span>
                )}
              </div>

              <h1 className="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight">
                {selectedProperty.title}
              </h1>

              <div className="flex flex-wrap items-center gap-3 text-xs text-slate-600">
                <div className="flex items-center gap-1 text-slate-900 font-extrabold">
                  <Star className="w-4 h-4 fill-amber-400 text-amber-500" />
                  <span>{selectedProperty.rating}</span>
                  <span className="text-slate-400 font-normal">({selectedProperty.reviewCount} ulasan)</span>
                </div>
                <span>•</span>
                <div className="flex items-center gap-1">
                  <MapPin className="w-3.5 h-3.5 text-emerald-600" />
                  <span>{selectedProperty.address}, {selectedProperty.subDistrict}, {selectedProperty.city}</span>
                </div>
              </div>
            </div>

            {/* Photo Gallery & 360 Simulator */}
            <PropertyGallery property={selectedProperty} />

            {/* Split Content: Main Specs (Left) & Sticky Booking Widget (Right) */}
            <div className="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
              
              {/* Left Column (8 cols) */}
              <div className="lg:col-span-8 space-y-10">
                
                {/* Description */}
                <section className="space-y-3">
                  <h3 className="text-lg font-extrabold text-slate-900">Deskripsi Hunian</h3>
                  <p className="text-xs sm:text-sm text-slate-600 leading-relaxed">
                    {selectedProperty.description}
                  </p>
                </section>

                {/* Unit Selector */}
                <UnitSelector
                  units={selectedProperty.units}
                  selectedUnit={activeUnit}
                  onSelectUnit={setActiveUnit}
                />

                {/* Facility Icons & Policies */}
                <FacilityIcons
                  facilities={selectedProperty.facilities}
                  rules={selectedProperty.rules}
                />

                {/* Transit & Accessibility Matrix */}
                <TransitMatrix
                  transitPoints={selectedProperty.transitPoints}
                  address={selectedProperty.address}
                  subDistrict={selectedProperty.subDistrict}
                  city={selectedProperty.city}
                />

                {/* Landlord Profile & WhatsApp direct */}
                <LandlordCard
                  landlord={selectedProperty.landlord}
                  property={selectedProperty}
                  onOpenDirectChat={() => setIsChatModalOpen(true)}
                />

                {/* Reviews */}
                <ReviewsSection
                  reviews={selectedProperty.reviews}
                  rating={selectedProperty.rating}
                  reviewCount={selectedProperty.reviewCount}
                  propertyId={selectedProperty.id}
                />

              </div>

              {/* Right Column (4 cols sticky booking bar) */}
              <div className="lg:col-span-4">
                <BookingStickyBar
                  property={selectedProperty}
                  selectedUnit={activeUnit}
                  onStartBooking={({ durationMonths, checkInDate, appliedPromo }) => {
                    setBookingParams({ durationMonths, checkInDate, appliedPromo });
                    setIsBookingModalOpen(true);
                  }}
                />
              </div>

            </div>

          </div>
        )}

        {/* VIEW 4: CITY SEO LANDING HUB */}
        {currentView === 'city-hub' && (
          <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fadeIn">
            <CityLandingContent
              city={currentCityHub}
              properties={PROPERTIES}
              onSelectProperty={(prop) => handleNavigate('detail', prop)}
              favorites={favorites}
              onToggleFavorite={toggleFavorite}
              comparedProperties={comparedProperties}
              onToggleCompare={toggleCompare}
              onOpenSeoInspector={() => setIsSeoInspectorOpen(true)}
            />
          </div>
        )}

        {/* VIEW 5: OWNER LISTING WIZARD */}
        {currentView === 'owner-wizard' && (
          <ListPropertyWizard
            onClose={() => setCurrentView('home')}
            onSuccessListed={(title) => {
              showToast(`Iklan "${title}" berhasil diajukan! Tim kami akan segera menghubungi.`);
            }}
          />
        )}

      </main>

      {/* Global Footer */}
      <Footer onNavigate={handleNavigate} />

      {/* MODALS */}
      <SeoInspectorModal
        isOpen={isSeoInspectorOpen}
        onClose={() => setIsSeoInspectorOpen(false)}
        currentPageTitle={currentSeo.title}
        currentPageDescription={currentSeo.description}
        currentUrl={currentSeo.url}
        schemaJson={currentSeo.schemaJson}
        ogImage={currentSeo.ogImage}
      />

      <WishlistModal
        isOpen={isFavoritesModalOpen}
        onClose={() => setIsFavoritesModalOpen(false)}
        favorites={PROPERTIES.filter(p => favorites.includes(p.id))}
        onRemoveFavorite={(id) => toggleFavorite(PROPERTIES.find(p => p.id === id)!)}
        onSelectProperty={(p) => handleNavigate('detail', p)}
        onClearAll={() => {
          // Bug fix: this used to only clear local state, leaving the
          // entries still sitting in the real `wishlists` table — they'd
          // silently reappear next time favorites were reconciled with the
          // backend. Now each one is actually removed there too.
          const identity = getGuestIdentity();
          if (identity) {
            favorites.forEach((id) => {
              apiToggleWishlist(Number(id), identity).catch(() => {});
            });
          }
          setFavorites([]);
          showToast('Seluruh daftar favorit dihapus.');
        }}
      />

      <CompareDrawer
        isOpen={isCompareModalOpen}
        onClose={() => setIsCompareModalOpen(false)}
        comparedProperties={comparedProperties}
        onRemoveProperty={(id) => setComparedProperties(prev => prev.filter(p => p.id !== id))}
        onSelectProperty={(p) => handleNavigate('detail', p)}
        onClearAll={() => {
          setComparedProperties([]);
          showToast('Seluruh perbandingan dihapus.');
        }}
      />

      {selectedProperty && (
        <BookingModal
          isOpen={isBookingModalOpen}
          onClose={() => setIsBookingModalOpen(false)}
          property={selectedProperty}
          unit={activeUnit}
          initialDuration={bookingParams.durationMonths}
          initialCheckIn={bookingParams.checkInDate}
          initialPromo={bookingParams.appliedPromo}
        />
      )}

      {selectedProperty && (
        <ChatModal
          isOpen={isChatModalOpen}
          onClose={() => setIsChatModalOpen(false)}
          landlord={selectedProperty.landlord}
          property={selectedProperty}
        />
      )}

      {/* Toast Notification Alert */}
      {toastMessage && (
        <div className="fixed bottom-6 right-6 z-50 bg-slate-900/95 backdrop-blur-md text-white px-4 py-3 rounded-2xl shadow-2xl border border-slate-700 flex items-center gap-2.5 text-xs font-bold animate-fadeIn">
          <CheckCircle2 className="w-4 h-4 text-emerald-400 shrink-0" />
          <span>{toastMessage}</span>
        </div>
      )}

    </div>
  );
};
