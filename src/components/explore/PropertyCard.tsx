import React, { useState } from 'react';
import { 
  Check, 
  ChevronLeft, 
  ChevronRight, 
  Eye, 
  Heart, 
  Layers, 
  MapPin, 
  ShieldCheck, 
  Sparkles, 
  Star, 
  Train, 
  Zap 
} from 'lucide-react';
import { Property } from '../../types';
import { calculateDiscountPrice, formatRupiah } from '../../utils/currency';

interface PropertyCardProps {
  property: Property;
  onSelect: (property: Property) => void;
  onQuickBook?: (property: Property) => void;
  isFavorite: boolean;
  onToggleFavorite: (property: Property) => void;
  isCompared: boolean;
  onToggleCompare: (property: Property) => void;
}

export const PropertyCard: React.FC<PropertyCardProps> = ({
  property,
  onSelect,
  onQuickBook,
  isFavorite,
  onToggleFavorite,
  isCompared,
  onToggleCompare
}) => {
  const [activeImageIndex, setActiveImageIndex] = useState(0);

  const handlePrevImage = (e: React.MouseEvent) => {
    e.stopPropagation();
    setActiveImageIndex((prev) => (prev === 0 ? property.images.length - 1 : prev - 1));
  };

  const handleNextImage = (e: React.MouseEvent) => {
    e.stopPropagation();
    setActiveImageIndex((prev) => (prev === property.images.length - 1 ? 0 : prev + 1));
  };

  const finalPrice = calculateDiscountPrice(property.basePriceMonthly, property.discountPercent);
  const nearestTransit = property.transitPoints[0];

  return (
    <article
      onClick={() => onSelect(property)}
      className="group cursor-pointer bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-subtle hover:shadow-card hover:border-emerald-300 transition-all duration-300 flex flex-col justify-between"
    >
      <div>
        {/* Top Media & Image Gallery */}
        <div className="relative aspect-[4/3] w-full bg-slate-100 overflow-hidden">
          <img
            src={property.images[activeImageIndex] || property.images[0]}
            alt={property.title}
            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            loading="lazy"
          />

          {/* Top Floating Badges */}
          <div className="absolute top-3 left-3 flex flex-col gap-1.5 z-10">
            {property.verifiedOfficial && (
              <span className="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-600/95 backdrop-blur-md text-white text-[10px] font-extrabold rounded-full shadow-sm">
                <ShieldCheck className="w-3 h-3" />
                <span>OFFICIAL VERIFIED</span>
              </span>
            )}
            {property.hasVirtualTour && (
              <span className="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-900/80 backdrop-blur-md text-emerald-300 text-[10px] font-extrabold rounded-full shadow-sm">
                <Eye className="w-3 h-3" />
                <span>Tour 360°</span>
              </span>
            )}
          </div>

          {/* Action Buttons: Wishlist & Compare */}
          <div className="absolute top-3 right-3 flex items-center gap-1.5 z-10">
            {/* Compare Toggle */}
            <button
              onClick={(e) => {
                e.stopPropagation();
                onToggleCompare(property);
              }}
              title="Bandingkan Properti"
              className={`p-2 rounded-full backdrop-blur-md transition ${
                isCompared
                  ? 'bg-amber-500 text-white shadow-md'
                  : 'bg-white/80 hover:bg-white text-slate-700'
              }`}
            >
              <Layers className="w-4 h-4" />
            </button>

            {/* Favorite Wishlist Toggle */}
            <button
              onClick={(e) => {
                e.stopPropagation();
                onToggleFavorite(property);
              }}
              title="Simpan ke Favorit"
              className={`p-2 rounded-full backdrop-blur-md transition ${
                isFavorite
                  ? 'bg-rose-500 text-white shadow-md'
                  : 'bg-white/80 hover:bg-white text-slate-700'
              }`}
            >
              <Heart className={`w-4 h-4 ${isFavorite ? 'fill-current' : ''}`} />
            </button>
          </div>

          {/* Carousel Arrows (Visible on Hover / Desktop) */}
          {property.images.length > 1 && (
            <>
              <button
                onClick={handlePrevImage}
                className="absolute left-2 top-1/2 -translate-y-1/2 p-1.5 bg-black/40 hover:bg-black/70 text-white rounded-full opacity-0 group-hover:opacity-100 transition"
              >
                <ChevronLeft className="w-4 h-4" />
              </button>
              <button
                onClick={handleNextImage}
                className="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 bg-black/40 hover:bg-black/70 text-white rounded-full opacity-0 group-hover:opacity-100 transition"
              >
                <ChevronRight className="w-4 h-4" />
              </button>
            </>
          )}

          {/* Image Dots */}
          <div className="absolute bottom-2.5 left-0 right-0 flex items-center justify-center gap-1">
            {property.images.map((_, idx) => (
              <span
                key={idx}
                className={`w-1.5 h-1.5 rounded-full transition-all ${
                  activeImageIndex === idx ? 'w-4 bg-white' : 'bg-white/50'
                }`}
              />
            ))}
          </div>
        </div>

        {/* Content Body */}
        <div className="p-4 space-y-2.5">
          
          {/* Subheader: Category, Gender & Rating */}
          <div className="flex items-center justify-between text-xs">
            <span className="px-2 py-0.5 bg-slate-100 text-slate-700 font-bold rounded-md uppercase text-[10px]">
              {property.genderRestriction || property.propertyType.replace('_', ' ')}
            </span>

            <div className="flex items-center gap-1 text-slate-900 font-extrabold">
              <Star className="w-3.5 h-3.5 fill-amber-400 text-amber-500" />
              <span>{property.rating}</span>
              <span className="text-slate-400 font-normal">({property.reviewCount})</span>
            </div>
          </div>

          {/* Title */}
          <h3 className="font-extrabold text-slate-900 text-sm leading-snug line-clamp-1 group-hover:text-emerald-700 transition">
            {property.title}
          </h3>

          {/* Location & Nearest Transit */}
          <div className="space-y-1 text-xs text-slate-500">
            <div className="flex items-center gap-1">
              <MapPin className="w-3.5 h-3.5 text-emerald-600 shrink-0" />
              <span className="truncate">{property.subDistrict}, {property.city}</span>
            </div>

            {nearestTransit && (
              <div className="flex items-center gap-1 text-[11px] text-teal-700 font-medium bg-teal-50 px-2 py-0.5 rounded-md w-fit">
                <Train className="w-3 h-3" />
                <span>{nearestTransit.walkMinutes} min jln ke {nearestTransit.name}</span>
              </div>
            )}
          </div>

          {/* Quick Key Amenities */}
          <div className="flex flex-wrap gap-1 pt-1">
            {property.facilities.slice(0, 3).map((f, i) => (
              <span key={i} className="text-[10px] bg-slate-50 text-slate-600 px-1.5 py-0.5 rounded border border-slate-100">
                {f}
              </span>
            ))}
            {property.facilities.length > 3 && (
              <span className="text-[10px] text-slate-400 px-1 py-0.5 font-semibold">
                +{property.facilities.length - 3} lainnya
              </span>
            )}
          </div>

        </div>
      </div>

      {/* Footer Pricing & Fast Action Buttons */}
      <div className="p-4 pt-3 border-t border-slate-100 bg-slate-50/50 flex items-end justify-between gap-2">
        <div>
          {property.discountPercent && property.discountPercent > 0 ? (
            <div className="flex items-center gap-1.5 text-xs">
              <span className="line-through text-slate-400 text-[11px]">
                {formatRupiah(property.basePriceMonthly)}
              </span>
              <span className="text-[10px] font-black px-1.5 py-0.2 bg-rose-100 text-rose-700 rounded">
                -{property.discountPercent}%
              </span>
            </div>
          ) : null}

          <div className="flex items-baseline gap-1">
            <span className="text-base font-extrabold text-slate-900">
              {formatRupiah(finalPrice)}
            </span>
            <span className="text-[11px] text-slate-500 font-semibold">/bln</span>
          </div>

          <div className="text-[10px] text-slate-400 mt-0.5 flex items-center gap-1">
            <Zap className="w-3 h-3 text-amber-500" />
            <span>{property.electricityPolicy === 'include' ? 'Listrik Termasuk' : 'Token Mandiri'}</span>
          </div>
        </div>

        <div className="flex items-center gap-1.5">
          {onQuickBook && (
            <button 
              type="button"
              onClick={(e) => {
                e.stopPropagation();
                onQuickBook(property);
              }}
              title="Pesan Langsung & Bayar Instan"
              className="px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-xl text-xs font-black transition flex items-center gap-1"
            >
              <span>⚡ Sewa</span>
            </button>
          )}

          <button 
            type="button"
            onClick={(e) => {
              e.stopPropagation();
              onSelect(property);
            }}
            className="px-3 py-1.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold transition shadow-sm"
          >
            Detail
          </button>
        </div>
      </div>

    </article>
  );
};
