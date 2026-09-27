import React, { useState } from 'react';
import { 
  Building, 
  Eye, 
  Layers, 
  MapPin, 
  Maximize2, 
  Navigation, 
  Plus, 
  ShieldCheck, 
  Star, 
  Train, 
  X, 
  Zap 
} from 'lucide-react';
import { Property } from '../../types';
import { calculateDiscountPrice, formatCompactRupiah, formatRupiah } from '../../utils/currency';

interface InteractiveMapProps {
  properties: Property[];
  selectedProperty: Property | null;
  onSelectProperty: (property: Property) => void;
  cityCenter?: string;
}

export const InteractiveMap: React.FC<InteractiveMapProps> = ({
  properties,
  selectedProperty,
  onSelectProperty,
  cityCenter
}) => {
  const [hoveredProperty, setHoveredProperty] = useState<Property | null>(null);
  const [activeLayer, setActiveLayer] = useState<'all' | 'transit' | 'campus'>('all');
  const [zoomLevel, setZoomLevel] = useState<number>(1);

  return (
    <div className="relative w-full h-[600px] lg:h-full min-h-[500px] bg-slate-900 rounded-3xl overflow-hidden border border-slate-200/80 shadow-card flex flex-col">
      
      {/* Map Header Overlay Controls */}
      <div className="absolute top-4 left-4 right-4 z-20 flex items-center justify-between pointer-events-none">
        
        {/* City & Counter Badge */}
        <div className="pointer-events-auto bg-slate-900/90 backdrop-blur-md text-white px-3.5 py-2 rounded-2xl border border-slate-700/80 shadow-md flex items-center gap-2">
          <MapPin className="w-4 h-4 text-emerald-400" />
          <div>
            <span className="text-xs font-black">{cityCenter || 'Peta Interaktif Indonesia'}</span>
            <span className="text-[10px] text-slate-400 ml-1.5">({properties.length} Pin Aktif)</span>
          </div>
        </div>

        {/* Transit Layer Switcher */}
        <div className="pointer-events-auto hidden sm:flex items-center gap-1 bg-slate-900/90 backdrop-blur-md p-1 rounded-2xl border border-slate-700/80 shadow-md text-xs">
          <button
            onClick={() => setActiveLayer('all')}
            className={`px-2.5 py-1 rounded-xl font-bold transition ${
              activeLayer === 'all' ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:text-white'
            }`}
          >
            Semua Pin
          </button>
          <button
            onClick={() => setActiveLayer('transit')}
            className={`px-2.5 py-1 rounded-xl font-bold flex items-center gap-1 transition ${
              activeLayer === 'transit' ? 'bg-emerald-600 text-white' : 'text-slate-400 hover:text-white'
            }`}
          >
            <Train className="w-3 h-3 text-teal-300" />
            <span>Stasiun Transit</span>
          </button>
        </div>

      </div>

      {/* Interactive Canvas / Visual SVG Map Matrix */}
      <div className="relative flex-1 w-full h-full bg-[#1b263b] overflow-hidden select-none">
        
        {/* Map Grid Pattern & Roads */}
        <svg className="w-full h-full opacity-30 absolute inset-0 pointer-events-none" xmlns="http://www.w3.org/2000/svg">
          <defs>
            <pattern id="map-grid" width="40" height="40" patternUnits="userSpaceOnUse">
              <path d="M 40 0 L 0 0 0 40" fill="none" stroke="#415a77" strokeWidth="0.75" />
            </pattern>
          </defs>
          <rect width="100%" height="100%" fill="url(#map-grid)" />
          
          {/* Stylized Transit Route Lines */}
          <path d="M 0 150 Q 300 250 800 180" fill="none" stroke="#10b981" strokeWidth="4" strokeDasharray="8 6" opacity="0.6" />
          <path d="M 200 0 Q 250 400 600 600" fill="none" stroke="#0ea5e9" strokeWidth="4" strokeDasharray="8 6" opacity="0.6" />
          <path d="M 50 450 Q 400 350 750 500" fill="none" stroke="#f59e0b" strokeWidth="3" opacity="0.5" />
        </svg>

        {/* Dynamic Pins Placed based on properties */}
        <div className="relative w-full h-full flex items-center justify-center p-8">
          <div className="relative w-full max-w-2xl h-96">
            
            {properties.map((prop, idx) => {
              const isSelected = selectedProperty?.id === prop.id;
              const isHovered = hoveredProperty?.id === prop.id;
              const finalPrice = calculateDiscountPrice(prop.basePriceMonthly, prop.discountPercent);

              // Pseudo-spatial layout coordinates
              const xPositions = [20, 65, 35, 75, 45, 80];
              const yPositions = [25, 30, 60, 65, 80, 20];
              const leftPercent = xPositions[idx % xPositions.length];
              const topPercent = yPositions[idx % yPositions.length];

              return (
                <div
                  key={prop.id}
                  style={{ left: `${leftPercent}%`, top: `${topPercent}%` }}
                  className="absolute -translate-x-1/2 -translate-y-1/2 z-10"
                >
                  {/* Pin Button */}
                  <button
                    onClick={() => onSelectProperty(prop)}
                    onMouseEnter={() => setHoveredProperty(prop)}
                    onMouseLeave={() => setHoveredProperty(null)}
                    className={`group relative px-3 py-1.5 rounded-full font-black text-xs shadow-xl transition-all duration-300 transform flex items-center gap-1.5 ${
                      isSelected || isHovered
                        ? 'bg-emerald-500 text-slate-950 scale-110 ring-4 ring-emerald-400/50 z-30'
                        : 'bg-white hover:bg-emerald-50 text-slate-900 hover:scale-105 z-10'
                    }`}
                  >
                    <span className="w-2 h-2 rounded-full bg-emerald-600 animate-pulse" />
                    <span>{formatCompactRupiah(finalPrice)}</span>
                  </button>

                  {/* Proximity Transit Pill */}
                  {prop.transitPoints[0] && activeLayer !== 'campus' && (
                    <div className="hidden sm:block absolute top-full left-1/2 -translate-x-1/2 mt-1 px-2 py-0.5 bg-slate-900/90 text-teal-300 rounded text-[9px] font-bold border border-slate-700 whitespace-nowrap shadow pointer-events-none">
                      {prop.transitPoints[0].walkMinutes}m ke {prop.transitPoints[0].name}
                    </div>
                  )}
                </div>
              );
            })}

          </div>
        </div>

        {/* Hovered / Selected Mini Preview Card (Bottom Floating) */}
        {(hoveredProperty || selectedProperty) && (
          <div className="absolute bottom-4 left-4 right-4 sm:left-auto sm:right-4 sm:w-80 bg-white rounded-2xl p-3 shadow-2xl border border-slate-200 z-30 animate-fadeIn">
            {(() => {
              const active = hoveredProperty || selectedProperty!;
              const finalPrice = calculateDiscountPrice(active.basePriceMonthly, active.discountPercent);
              return (
                <div 
                  onClick={() => onSelectProperty(active)}
                  className="flex items-center gap-3 cursor-pointer group"
                >
                  <img
                    src={active.images[0]}
                    alt={active.title}
                    className="w-16 h-16 rounded-xl object-cover shrink-0 group-hover:scale-105 transition"
                  />
                  <div className="flex-1 min-w-0">
                    <div className="flex items-center gap-1 text-[10px] font-bold text-emerald-700 mb-0.5">
                      <ShieldCheck className="w-3 h-3 text-emerald-600" />
                      <span>{active.subDistrict}, {active.city}</span>
                    </div>
                    <h4 className="font-extrabold text-xs text-slate-900 truncate group-hover:text-emerald-600">
                      {active.title}
                    </h4>
                    <div className="flex items-baseline gap-1 mt-1">
                      <span className="text-xs font-black text-slate-900">
                        {formatRupiah(finalPrice)}
                      </span>
                      <span className="text-[10px] text-slate-400">/bln</span>
                    </div>
                  </div>
                </div>
              );
            })()}
          </div>
        )}

      </div>

      {/* Map Footer Helper */}
      <div className="p-3 bg-slate-950 text-slate-400 text-xs flex items-center justify-between border-t border-slate-800">
        <div className="flex items-center gap-2">
          <span className="w-2.5 h-2.5 rounded-full bg-emerald-500" />
          <span className="text-[11px]">Klik pin harga untuk melihat detail unit & fasilitas.</span>
        </div>
        <span className="text-[10px] font-semibold text-slate-500">Stayease GeoMap ID</span>
      </div>

    </div>
  );
};
