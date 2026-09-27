import React from 'react';
import { Bed, Check, Sparkles, Tag, Users } from 'lucide-react';
import { RoomUnit } from '../../types';
import { formatRupiah } from '../../utils/currency';

interface UnitSelectorProps {
  units: RoomUnit[];
  selectedUnit: RoomUnit;
  onSelectUnit: (unit: RoomUnit) => void;
}

export const UnitSelector: React.FC<UnitSelectorProps> = ({
  units,
  selectedUnit,
  onSelectUnit
}) => {
  return (
    <section className="space-y-4">
      <div className="flex items-center justify-between">
        <h3 className="text-lg font-extrabold text-slate-900 tracking-tight">
          Pilihan Tipe Kamar & Unit
        </h3>
        <span className="text-xs text-slate-500 font-semibold">
          {units.length} Tipe Kamar Tersedia
        </span>
      </div>

      <div className="space-y-3">
        {units.map((unit) => {
          const isSelected = selectedUnit.id === unit.id;
          const displayPrice = unit.isPromo && unit.promoPriceMonthly 
            ? unit.promoPriceMonthly 
            : unit.priceMonthly;

          return (
            <div
              key={unit.id}
              onClick={() => onSelectUnit(unit)}
              className={`p-4 sm:p-5 rounded-2xl border transition-all duration-200 cursor-pointer flex flex-col sm:flex-row sm:items-center justify-between gap-4 ${
                isSelected
                  ? 'border-emerald-600 bg-emerald-50/40 ring-2 ring-emerald-500/20 shadow-sm'
                  : 'border-slate-200 bg-white hover:border-slate-300'
              }`}
            >
              {/* Unit Info */}
              <div className="flex items-start gap-4">
                <img
                  src={unit.photos[0]}
                  alt={unit.name}
                  className="w-20 h-20 rounded-xl object-cover shrink-0"
                />
                <div className="space-y-1">
                  <div className="flex items-center gap-2">
                    <h4 className="font-extrabold text-slate-900 text-sm">{unit.name}</h4>
                    {unit.isPromo && (
                      <span className="px-2 py-0.5 bg-rose-100 text-rose-700 font-black text-[10px] rounded-full">
                        PROMO
                      </span>
                    )}
                  </div>

                  <div className="flex items-center gap-3 text-xs text-slate-500">
                    <span className="flex items-center gap-1">
                      <Bed className="w-3.5 h-3.5 text-emerald-600" />
                      {unit.bedType}
                    </span>
                    <span>•</span>
                    <span>{unit.sizeM2} m²</span>
                  </div>

                  <div className="flex flex-wrap gap-1 pt-1">
                    {unit.features.map((feat, idx) => (
                      <span key={idx} className="text-[10px] bg-slate-100 text-slate-700 px-1.5 py-0.5 rounded font-medium">
                        {feat}
                      </span>
                    ))}
                  </div>
                </div>
              </div>

              {/* Price & Selection */}
              <div className="flex sm:flex-col items-center sm:items-end justify-between sm:justify-center border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100 shrink-0">
                <div className="text-right">
                  {unit.isPromo && unit.promoPriceMonthly && (
                    <span className="text-xs line-through text-slate-400 block">
                      {formatRupiah(unit.priceMonthly)}
                    </span>
                  )}
                  <div className="flex items-baseline gap-1">
                    <span className="text-base font-black text-emerald-700">
                      {formatRupiah(displayPrice)}
                    </span>
                    <span className="text-[11px] text-slate-400 font-medium">/bln</span>
                  </div>
                  <span className="text-[10px] text-emerald-600 font-bold block">
                    Tersisa {unit.availableCount} Kamar
                  </span>
                </div>

                <button
                  type="button"
                  className={`mt-2 px-4 py-1.5 rounded-xl text-xs font-extrabold transition flex items-center gap-1 ${
                    isSelected
                      ? 'bg-emerald-600 text-white shadow-sm'
                      : 'bg-slate-100 hover:bg-slate-200 text-slate-800'
                  }`}
                >
                  {isSelected ? (
                    <>
                      <Check className="w-3.5 h-3.5" />
                      <span>Terpilih</span>
                    </>
                  ) : (
                    <span>Pilih Kamar Ini</span>
                  )}
                </button>
              </div>
            </div>
          );
        })}
      </div>
    </section>
  );
};
