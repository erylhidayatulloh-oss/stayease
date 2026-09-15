import React from 'react';
import { 
  Car, 
  Check, 
  Coffee, 
  DoorClosed, 
  Flame, 
  Lock, 
  Refrigerator, 
  ShieldCheck, 
  ShowerHead, 
  Sparkles, 
  Tv, 
  Utensils, 
  Wifi, 
  Wind, 
  Zap 
} from 'lucide-react';

interface FacilityIconsProps {
  facilities: string[];
  rules: string[];
}

export const FacilityIcons: React.FC<FacilityIconsProps> = ({ facilities, rules }) => {
  const getIconForFacility = (name: string) => {
    const lower = name.toLowerCase();
    if (lower.includes('ac')) return Wind;
    if (lower.includes('wi-fi') || lower.includes('wifi') || lower.includes('internet')) return Wifi;
    if (lower.includes('kamar mandi') || lower.includes('water heater') || lower.includes('shower') || lower.includes('bathtub')) return ShowerHead;
    if (lower.includes('tv')) return Tv;
    if (lower.includes('parkir') || lower.includes('carport')) return Car;
    if (lower.includes('dapur') || lower.includes('kitchen')) return Utensils;
    if (lower.includes('kulkas')) return Refrigerator;
    if (lower.includes('cctv') || lower.includes('smart lock') || lower.includes('keamanan')) return Lock;
    if (lower.includes('listrik')) return Zap;
    return Sparkles;
  };

  return (
    <div className="space-y-8">
      
      {/* Facilities Grid */}
      <section className="space-y-4">
        <h3 className="text-lg font-extrabold text-slate-900 tracking-tight">
          Fasilitas Lengkap Properti
        </h3>
        
        <div className="grid grid-cols-2 sm:grid-cols-3 gap-3">
          {facilities.map((facility, idx) => {
            const Icon = getIconForFacility(facility);
            return (
              <div
                key={idx}
                className="p-3 bg-slate-50 border border-slate-200/80 rounded-2xl flex items-center gap-3"
              >
                <div className="p-2 bg-emerald-100/70 text-emerald-700 rounded-xl shrink-0">
                  <Icon className="w-4 h-4" />
                </div>
                <span className="text-xs font-bold text-slate-800 leading-snug">{facility}</span>
              </div>
            );
          })}
        </div>
      </section>

      {/* Rules & Policies */}
      <section className="space-y-3 p-5 bg-amber-50/50 rounded-2xl border border-amber-200/60">
        <h4 className="text-xs font-bold text-amber-900 uppercase tracking-wider">
          Peraturan & Tata Tertib Hunian
        </h4>
        <ul className="space-y-2">
          {rules.map((rule, idx) => (
            <li key={idx} className="flex items-start gap-2 text-xs text-slate-700">
              <span className="w-1.5 h-1.5 rounded-full bg-amber-600 mt-1.5 shrink-0" />
              <span>{rule}</span>
            </li>
          ))}
        </ul>
      </section>

    </div>
  );
};
