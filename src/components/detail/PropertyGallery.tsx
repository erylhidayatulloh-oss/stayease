import React, { useState } from 'react';
import { 
  ChevronLeft, 
  ChevronRight, 
  Compass, 
  Eye, 
  Grid, 
  RotateCw, 
  Share2, 
  ShieldCheck, 
  Sparkles, 
  X 
} from 'lucide-react';
import { Property } from '../../types';

interface PropertyGalleryProps {
  property: Property;
}

export const PropertyGallery: React.FC<PropertyGalleryProps> = ({ property }) => {
  const [isLightboxOpen, setIsLightboxOpen] = useState(false);
  const [lightboxIndex, setLightboxIndex] = useState(0);
  const [is360Active, setIs360Active] = useState(false);
  const [rotationAngle, setRotationAngle] = useState(0);

  const handleNext = () => {
    setLightboxIndex((prev) => (prev === property.images.length - 1 ? 0 : prev + 1));
  };

  const handlePrev = () => {
    setLightboxIndex((prev) => (prev === 0 ? property.images.length - 1 : prev - 1));
  };

  return (
    <div className="space-y-3">
      
      {/* 360° Interactive Simulator Switch Banner */}
      {property.hasVirtualTour && (
        <div className="bg-gradient-to-r from-slate-950 via-slate-900 to-emerald-950 text-white p-3.5 sm:p-4 rounded-2xl flex items-center justify-between shadow-md">
          <div className="flex items-center gap-3">
            <div className="p-2 bg-emerald-500/20 text-emerald-400 rounded-xl">
              <Compass className="w-5 h-5 animate-spin" style={{ animationDuration: '8s' }} />
            </div>
            <div>
              <div className="flex items-center gap-2">
                <h4 className="text-xs sm:text-sm font-extrabold">Virtual Tour 360° Tersedia</h4>
                <span className="px-2 py-0.5 text-[10px] bg-emerald-500 text-slate-950 font-black rounded-full">
                  Survey Online
                </span>
              </div>
              <p className="text-[11px] text-slate-300">Lihat kondisi kamar dan fasilitas secara 360 derajat sebelum booking.</p>
            </div>
          </div>

          <button
            onClick={() => setIs360Active(!is360Active)}
            className="px-3.5 py-2 bg-emerald-500 hover:bg-emerald-400 text-slate-950 font-extrabold text-xs rounded-xl transition flex items-center gap-1.5 shrink-0"
          >
            <RotateCw className="w-3.5 h-3.5" />
            <span>{is360Active ? 'Tutup 360°' : 'Buka Tour 360°'}</span>
          </button>
        </div>
      )}

      {/* 360 Interactive Simulation Canvas */}
      {is360Active && property.virtualTour360 && (
        <div className="relative aspect-video rounded-3xl overflow-hidden bg-slate-950 border-2 border-emerald-500 shadow-2xl animate-fadeIn">
          <img
            src={property.virtualTour360}
            alt="360 Tour Simulator"
            style={{ transform: `rotate(${rotationAngle}deg) scale(1.1)` }}
            className="w-full h-full object-cover transition-transform duration-300"
          />
          <div className="absolute inset-0 bg-black/20 pointer-events-none" />

          {/* 360 On-screen Controls */}
          <div className="absolute bottom-4 left-1/2 -translate-x-1/2 bg-slate-900/90 backdrop-blur-md px-4 py-2 rounded-2xl border border-slate-700 flex items-center gap-3 text-white">
            <button
              onClick={() => setRotationAngle(prev => prev - 15)}
              className="p-1.5 hover:bg-slate-800 rounded-lg text-xs font-bold flex items-center gap-1"
            >
              <RotateCw className="w-4 h-4 -scale-x-100" />
              <span>Putar Kiri</span>
            </button>
            <span className="text-xs font-mono text-emerald-400 font-bold">{rotationAngle}° Sudut Pandang</span>
            <button
              onClick={() => setRotationAngle(prev => prev + 15)}
              className="p-1.5 hover:bg-slate-800 rounded-lg text-xs font-bold flex items-center gap-1"
            >
              <RotateCw className="w-4 h-4" />
              <span>Putar Kanan</span>
            </button>
          </div>
        </div>
      )}

      {/* Standard Photo Grid Gallery */}
      {!is360Active && (
        <div className="grid grid-cols-1 md:grid-cols-4 gap-2.5 rounded-3xl overflow-hidden aspect-[16/9] sm:aspect-[21/9] max-h-[480px]">
          
          {/* Main Large Hero Image */}
          <div 
            onClick={() => { setLightboxIndex(0); setIsLightboxOpen(true); }}
            className="md:col-span-2 relative group cursor-pointer overflow-hidden bg-slate-100 h-full"
          >
            <img
              src={property.images[0]}
              alt={property.title}
              className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            />
            <div className="absolute top-4 left-4">
              <span className="px-3 py-1 bg-slate-900/80 backdrop-blur-md text-emerald-300 text-xs font-extrabold rounded-full flex items-center gap-1.5 shadow">
                <ShieldCheck className="w-4 h-4 text-emerald-400" />
                <span>Foto 100% Asli Terverifikasi</span>
              </span>
            </div>
          </div>

          {/* Secondary 2 Images */}
          <div className="hidden md:grid grid-rows-2 gap-2.5 h-full">
            {property.images.slice(1, 3).map((img, idx) => (
              <div
                key={idx}
                onClick={() => { setLightboxIndex(idx + 1); setIsLightboxOpen(true); }}
                className="relative group cursor-pointer overflow-hidden bg-slate-100 h-full"
              >
                <img
                  src={img}
                  alt={`${property.title} - foto ${idx + 2}`}
                  className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                />
              </div>
            ))}
          </div>

          {/* Third Column with View All Photos Button */}
          <div className="hidden md:block relative group cursor-pointer overflow-hidden bg-slate-100 h-full">
            <img
              src={property.images[3] || property.images[0]}
              alt={`${property.title} - foto 4`}
              className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            />
            <button
              onClick={() => { setLightboxIndex(0); setIsLightboxOpen(true); }}
              className="absolute inset-0 bg-slate-950/40 group-hover:bg-slate-950/60 backdrop-blur-[2px] transition flex flex-col items-center justify-center text-white gap-1.5"
            >
              <Grid className="w-6 h-6 text-emerald-400" />
              <span className="text-xs font-extrabold">Lihat Semua Foto ({property.images.length})</span>
            </button>
          </div>

        </div>
      )}

      {/* Lightbox Modal */}
      {isLightboxOpen && (
        <div className="fixed inset-0 z-50 bg-black/95 flex flex-col items-center justify-center p-4">
          <div className="w-full max-w-5xl flex items-center justify-between text-white pb-4">
            <span className="text-sm font-bold">
              Foto {lightboxIndex + 1} dari {property.images.length}
            </span>
            <button
              onClick={() => setIsLightboxOpen(false)}
              className="p-2 bg-white/10 hover:bg-white/20 rounded-full"
            >
              <X className="w-6 h-6" />
            </button>
          </div>

          <div className="relative max-w-4xl w-full max-h-[75vh] flex items-center justify-center">
            <img
              src={property.images[lightboxIndex]}
              alt="Foto Detail"
              className="max-w-full max-h-[75vh] object-contain rounded-2xl"
            />

            <button
              onClick={handlePrev}
              className="absolute left-2 p-3 bg-black/60 hover:bg-black/90 text-white rounded-full"
            >
              <ChevronLeft className="w-6 h-6" />
            </button>
            <button
              onClick={handleNext}
              className="absolute right-2 p-3 bg-black/60 hover:bg-black/90 text-white rounded-full"
            >
              <ChevronRight className="w-6 h-6" />
            </button>
          </div>
        </div>
      )}

    </div>
  );
};
