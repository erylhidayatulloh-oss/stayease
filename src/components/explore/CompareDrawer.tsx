import React from 'react';
import { 
  Check, 
  Layers, 
  MapPin, 
  ShieldCheck, 
  Star, 
  Trash2, 
  X, 
  Zap 
} from 'lucide-react';
import { Property } from '../../types';
import { calculateDiscountPrice, formatRupiah } from '../../utils/currency';

interface CompareDrawerProps {
  isOpen: boolean;
  onClose: () => void;
  comparedProperties: Property[];
  onRemoveProperty: (propertyId: string) => void;
  onSelectProperty: (property: Property) => void;
  onClearAll: () => void;
}

export const CompareDrawer: React.FC<CompareDrawerProps> = ({
  isOpen,
  onClose,
  comparedProperties,
  onRemoveProperty,
  onSelectProperty,
  onClearAll
}) => {
  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm animate-fadeIn">
      <div className="bg-white w-full max-w-5xl rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[92vh]">
        
        {/* Header */}
        <div className="p-5 bg-slate-900 text-white flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="p-2 bg-amber-500/20 text-amber-400 rounded-xl">
              <Layers className="w-5 h-5" />
            </div>
            <div>
              <h3 className="text-base font-extrabold">Bandingkan Properti Kost & Apartemen</h3>
              <p className="text-xs text-slate-400">
                Membandingkan {comparedProperties.length} dari maks. 3 properti pilihan Anda.
              </p>
            </div>
          </div>
          
          <div className="flex items-center gap-2">
            {comparedProperties.length > 0 && (
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

        {/* Comparison Table / Grid */}
        <div className="p-6 overflow-y-auto flex-1">
          {comparedProperties.length === 0 ? (
            <div className="text-center py-16 space-y-3">
              <Layers className="w-12 h-12 text-slate-300 mx-auto" />
              <h4 className="font-extrabold text-slate-800">Belum ada properti yang dibandingkan</h4>
              <p className="text-xs text-slate-500 max-w-sm mx-auto">
                Klik ikon layer <Layers className="w-3.5 h-3.5 inline text-amber-500" /> pada kartu kost/apartemen untuk menambahkan properti ke tabel perbandingan.
              </p>
            </div>
          ) : (
            <div className="overflow-x-auto">
              <table className="w-full text-left border-collapse">
                <thead>
                  <tr className="border-b border-slate-200">
                    <th className="p-3 text-xs font-bold text-slate-400 uppercase w-48">Parameter</th>
                    {comparedProperties.map((prop) => {
                      const finalPrice = calculateDiscountPrice(prop.basePriceMonthly, prop.discountPercent);
                      return (
                        <th key={prop.id} className="p-3 w-64 min-w-[240px] align-top">
                          <div className="relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 p-3 space-y-2">
                            <button
                              onClick={() => onRemoveProperty(prop.id)}
                              className="absolute top-2 right-2 p-1.5 bg-slate-900/70 hover:bg-rose-600 text-white rounded-full transition"
                              title="Hapus dari perbandingan"
                            >
                              <X className="w-3.5 h-3.5" />
                            </button>

                            <img
                              src={prop.images[0]}
                              alt={prop.title}
                              className="w-full h-28 object-cover rounded-xl"
                            />

                            <h4 className="font-extrabold text-xs text-slate-900 line-clamp-1">{prop.title}</h4>
                            
                            <div className="text-sm font-black text-emerald-700">
                              {formatRupiah(finalPrice)}<span className="text-[10px] text-slate-400 font-normal">/bln</span>
                            </div>

                            <button
                              onClick={() => {
                                onSelectProperty(prop);
                                onClose();
                              }}
                              className="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold transition"
                            >
                              Lihat Detail
                            </button>
                          </div>
                        </th>
                      );
                    })}
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 text-xs">
                  <tr>
                    <td className="p-3 font-bold text-slate-500">Tipe & Jenis</td>
                    {comparedProperties.map((p) => (
                      <td key={p.id} className="p-3 font-semibold text-slate-800">
                        {p.genderRestriction || p.propertyType.toUpperCase()}
                      </td>
                    ))}
                  </tr>

                  <tr>
                    <td className="p-3 font-bold text-slate-500">Luas Kamar</td>
                    {comparedProperties.map((p) => (
                      <td key={p.id} className="p-3 font-semibold text-slate-800">
                        {p.roomSizeM2} m²
                      </td>
                    ))}
                  </tr>

                  <tr>
                    <td className="p-3 font-bold text-slate-500">Kebijakan Listrik</td>
                    {comparedProperties.map((p) => (
                      <td key={p.id} className="p-3">
                        <span className={`px-2 py-0.5 rounded text-[11px] font-bold ${
                          p.electricityPolicy === 'include'
                            ? 'bg-emerald-100 text-emerald-800'
                            : 'bg-amber-100 text-amber-800'
                        }`}>
                          {p.electricityPolicy === 'include' ? 'Include Listrik' : 'Token Mandiri'}
                        </span>
                      </td>
                    ))}
                  </tr>

                  <tr>
                    <td className="p-3 font-bold text-slate-500">Rating & Review</td>
                    {comparedProperties.map((p) => (
                      <td key={p.id} className="p-3 font-bold text-slate-800 flex items-center gap-1">
                        <Star className="w-3.5 h-3.5 fill-amber-400 text-amber-500" />
                        <span>{p.rating} ({p.reviewCount} ulasan)</span>
                      </td>
                    ))}
                  </tr>

                  <tr>
                    <td className="p-3 font-bold text-slate-500">Akses Transportasi</td>
                    {comparedProperties.map((p) => (
                      <td key={p.id} className="p-3 text-slate-700">
                        {p.transitPoints[0] 
                          ? `${p.transitPoints[0].walkMinutes} min ke ${p.transitPoints[0].name}`
                          : '-'}
                      </td>
                    ))}
                  </tr>

                  <tr>
                    <td className="p-3 font-bold text-slate-500">Fasilitas Utama</td>
                    {comparedProperties.map((p) => (
                      <td key={p.id} className="p-3 text-slate-600">
                        <ul className="space-y-1">
                          {p.facilities.slice(0, 5).map((f, i) => (
                            <li key={i} className="flex items-center gap-1.5 text-[11px]">
                              <Check className="w-3 h-3 text-emerald-600 shrink-0" />
                              <span>{f}</span>
                            </li>
                          ))}
                        </ul>
                      </td>
                    ))}
                  </tr>

                  <tr>
                    <td className="p-3 font-bold text-slate-500">Aturan Properti</td>
                    {comparedProperties.map((p) => (
                      <td key={p.id} className="p-3 text-slate-600 text-[11px]">
                        {p.rules[0] || 'Standar ketertiban hunian'}
                      </td>
                    ))}
                  </tr>
                </tbody>
              </table>
            </div>
          )}
        </div>

      </div>
    </div>
  );
};
