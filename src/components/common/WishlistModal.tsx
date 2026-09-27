import React from 'react';
import { Heart, MapPin, Trash2, X } from 'lucide-react';
import { Property } from '../../types';
import { calculateDiscountPrice, formatRupiah } from '../../utils/currency';

interface WishlistModalProps {
  isOpen: boolean;
  onClose: () => void;
  favorites: Property[];
  onRemoveFavorite: (propertyId: string) => void;
  onSelectProperty: (property: Property) => void;
  onClearAll: () => void;
}

export const WishlistModal: React.FC<WishlistModalProps> = ({
  isOpen,
  onClose,
  favorites,
  onRemoveFavorite,
  onSelectProperty,
  onClearAll
}) => {
  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm animate-fadeIn">
      <div className="bg-white w-full max-w-2xl rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[85vh]">
        
        {/* Header */}
        <div className="p-5 bg-slate-900 text-white flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="p-2 bg-rose-500/20 text-rose-400 rounded-xl">
              <Heart className="w-5 h-5 fill-rose-500 text-rose-500" />
            </div>
            <div>
              <h3 className="text-base font-extrabold">Daftar Properti Favorit Saya</h3>
              <p className="text-xs text-slate-400">Tersimpan {favorites.length} hunian impian Anda.</p>
            </div>
          </div>

          <div className="flex items-center gap-2">
            {favorites.length > 0 && (
              <button
                onClick={onClearAll}
                className="px-3 py-1.5 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white rounded-xl text-xs font-semibold transition"
              >
                Hapus Semua
              </button>
            )}
            <button
              onClick={onClose}
              className="p-2 text-slate-400 hover:text-white rounded-xl hover:bg-slate-800 transition"
            >
              <X className="w-5 h-5" />
            </button>
          </div>
        </div>

        {/* List */}
        <div className="p-5 overflow-y-auto flex-1 space-y-3">
          {favorites.length === 0 ? (
            <div className="text-center py-12 space-y-3">
              <Heart className="w-12 h-12 text-slate-300 mx-auto" />
              <h4 className="font-extrabold text-slate-800">Belum ada properti favorit</h4>
              <p className="text-xs text-slate-500 max-w-xs mx-auto">
                Klik ikon hati pada properti yang Anda sukai untuk menyimpannya di sini.
              </p>
            </div>
          ) : (
            favorites.map((prop) => {
              const finalPrice = calculateDiscountPrice(prop.basePriceMonthly, prop.discountPercent);
              return (
                <div
                  key={prop.id}
                  className="p-3.5 bg-slate-50 hover:bg-slate-100/80 rounded-2xl border border-slate-200 flex items-center justify-between gap-4 transition group"
                >
                  <div 
                    onClick={() => {
                      onSelectProperty(prop);
                      onClose();
                    }}
                    className="flex items-center gap-3 cursor-pointer flex-1 min-w-0"
                  >
                    <img
                      src={prop.images[0]}
                      alt={prop.title}
                      className="w-16 h-16 rounded-xl object-cover shrink-0 group-hover:scale-105 transition"
                    />
                    <div className="min-w-0">
                      <h4 className="font-bold text-xs text-slate-900 truncate group-hover:text-emerald-700">
                        {prop.title}
                      </h4>
                      <div className="flex items-center gap-1 text-[11px] text-slate-500 mt-0.5">
                        <MapPin className="w-3 h-3 text-emerald-600 shrink-0" />
                        <span className="truncate">{prop.subDistrict}, {prop.city}</span>
                      </div>
                      <div className="text-xs font-black text-emerald-700 mt-1">
                        {formatRupiah(finalPrice)}<span className="text-[10px] text-slate-400 font-normal">/bln</span>
                      </div>
                    </div>
                  </div>

                  <div className="flex items-center gap-2 shrink-0">
                    <button
                      onClick={() => {
                        onSelectProperty(prop);
                        onClose();
                      }}
                      className="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition"
                    >
                      Lihat
                    </button>
                    <button
                      onClick={() => onRemoveFavorite(prop.id)}
                      className="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition"
                    >
                      <Trash2 className="w-4 h-4" />
                    </button>
                  </div>
                </div>
              );
            })
          )}
        </div>

      </div>
    </div>
  );
};
