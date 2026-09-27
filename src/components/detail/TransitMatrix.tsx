import React from 'react';
import { 
  Building2, 
  GraduationCap, 
  MapPin, 
  Navigation, 
  Plane, 
  ShoppingBag, 
  Train 
} from 'lucide-react';
import { TransitPoint } from '../../types';

interface TransitMatrixProps {
  transitPoints: TransitPoint[];
  address: string;
  subDistrict: string;
  city: string;
}

export const TransitMatrix: React.FC<TransitMatrixProps> = ({
  transitPoints,
  address,
  subDistrict,
  city
}) => {
  const getTransitIcon = (type: TransitPoint['type']) => {
    switch (type) {
      case 'MRT':
      case 'LRT':
      case 'KRL':
      case 'Transjakarta':
        return Train;
      case 'Kampus':
        return GraduationCap;
      case 'Mall':
        return ShoppingBag;
      case 'Bandara':
        return Plane;
      default:
        return Navigation;
    }
  };

  return (
    <section className="space-y-4">
      <div className="flex items-center justify-between">
        <div>
          <h3 className="text-lg font-extrabold text-slate-900 tracking-tight">
            Lokasi & Akses Transportasi
          </h3>
          <p className="text-xs text-slate-500 mt-0.5">
            {address}, {subDistrict}, {city}
          </p>
        </div>
      </div>

      <div className="grid grid-cols-1 sm:grid-cols-2 gap-3">
        {transitPoints.map((point, idx) => {
          const Icon = getTransitIcon(point.type);
          return (
            <div
              key={idx}
              className="p-3.5 bg-slate-50 rounded-2xl border border-slate-200/80 flex items-center justify-between"
            >
              <div className="flex items-center gap-3">
                <div className="p-2 bg-teal-100 text-teal-700 rounded-xl shrink-0">
                  <Icon className="w-4 h-4" />
                </div>
                <div>
                  <span className="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">
                    {point.type}
                  </span>
                  <h4 className="text-xs font-bold text-slate-900">{point.name}</h4>
                </div>
              </div>

              <div className="text-right shrink-0">
                <span className="text-xs font-black text-emerald-700 block">
                  {point.walkMinutes} Menit
                </span>
                <span className="text-[10px] text-slate-400">
                  {point.distanceMeters >= 1000 
                    ? `${(point.distanceMeters / 1000).toFixed(1)} km` 
                    : `${point.distanceMeters} meter`}
                </span>
              </div>
            </div>
          );
        })}
      </div>
    </section>
  );
};
