import React, { useState } from 'react';
import { 
  CheckCircle2, 
  Clock, 
  MessageCircle, 
  MessageSquare, 
  Phone, 
  ShieldCheck, 
  Sparkles, 
  UserCheck 
} from 'lucide-react';
import { Landlord, Property } from '../../types';

interface LandlordCardProps {
  landlord: Landlord;
  property: Property;
  onOpenDirectChat: () => void;
}

export const LandlordCard: React.FC<LandlordCardProps> = ({
  landlord,
  property,
  onOpenDirectChat
}) => {
  const [waClicked, setWaClicked] = useState(false);

  const handleWhatsAppRedirect = () => {
    setWaClicked(true);
    const message = encodeURIComponent(
      `Halo ${landlord.name}, saya melihat listing "${property.title}" di Stayease Indonesia (https://stayease.id/p/${property.slug}). Apakah unit masih tersedia untuk sewa? Terima kasih.`
    );
    window.open(`https://wa.me/${landlord.phoneWhatsapp.replace('+', '')}?text=${message}`, '_blank');
  };

  return (
    <div className="p-5 bg-gradient-to-br from-emerald-50/60 to-slate-50 rounded-3xl border border-emerald-200/80 shadow-subtle space-y-4">
      
      <div className="flex items-center justify-between">
        <div className="flex items-center gap-3">
          <div className="relative">
            <img
              src={landlord.avatar}
              alt={landlord.name}
              className="w-12 h-12 rounded-2xl object-cover border-2 border-emerald-500 shadow-sm"
            />
            <span className="absolute -bottom-1 -right-1 p-0.5 bg-emerald-600 rounded-full text-white">
              <ShieldCheck className="w-3.5 h-3.5" />
            </span>
          </div>

          <div>
            <div className="flex items-center gap-1.5">
              <h4 className="font-extrabold text-slate-900 text-sm">{landlord.name}</h4>
            </div>
            <span className="inline-block px-2 py-0.5 bg-emerald-100 text-emerald-800 text-[10px] font-black rounded-full mt-0.5">
              {landlord.badge}
            </span>
          </div>
        </div>

        <div className="text-right text-xs">
          <span className="text-slate-400 block text-[10px]">Waktu Balas</span>
          <span className="font-bold text-emerald-700 flex items-center gap-1 justify-end">
            <Clock className="w-3 h-3" /> {landlord.responseTime}
          </span>
        </div>
      </div>

      {/* Metrics */}
      <div className="grid grid-cols-3 gap-2 p-3 bg-white rounded-2xl border border-slate-200/70 text-center text-xs">
        <div>
          <span className="text-slate-400 text-[10px] block">Tingkat Balas</span>
          <span className="font-extrabold text-slate-800">{landlord.responseRate}%</span>
        </div>
        <div>
          <span className="text-slate-400 text-[10px] block">Bergabung</span>
          <span className="font-extrabold text-slate-800">{landlord.joinedYear}</span>
        </div>
        <div>
          <span className="text-slate-400 text-[10px] block">Total Listing</span>
          <span className="font-extrabold text-slate-800">{landlord.totalProperties} Properti</span>
        </div>
      </div>

      {/* Action CTA Buttons */}
      <div className="grid grid-cols-1 sm:grid-cols-2 gap-2 pt-1">
        <button
          onClick={handleWhatsAppRedirect}
          className="py-2.5 px-4 bg-[#25D366] hover:bg-[#1EBE5D] text-white font-extrabold text-xs rounded-xl shadow-sm flex items-center justify-center gap-1.5 transition hover:scale-[1.02]"
        >
          <Phone className="w-4 h-4" />
          <span>Chat WhatsApp Pemilik</span>
        </button>

        <button
          onClick={onOpenDirectChat}
          className="py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl flex items-center justify-center gap-1.5 transition"
        >
          <MessageCircle className="w-4 h-4 text-emerald-400" />
          <span>Kirim Pesan Stayease</span>
        </button>
      </div>

    </div>
  );
};
