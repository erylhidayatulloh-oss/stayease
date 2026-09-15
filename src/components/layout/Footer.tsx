import React from 'react';
import { 
  Building, 
  CheckCircle, 
  CreditCard, 
  Globe, 
  Headphones, 
  Lock, 
  Mail, 
  MapPin, 
  Phone, 
  ShieldCheck, 
  Sparkles 
} from 'lucide-react';
import { CITIES } from '../../data/cities';

interface FooterProps {
  onNavigate: (view: string, payload?: any) => void;
}

export const Footer: React.FC<FooterProps> = ({ onNavigate }) => {
  return (
    <footer className="bg-slate-900 text-slate-300 pt-16 pb-12 border-t border-slate-800">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {/* Top Feature Highlights */}
        <div className="grid grid-cols-1 md:grid-cols-4 gap-6 pb-12 border-b border-slate-800">
          <div className="flex items-start gap-3.5">
            <div className="p-2.5 bg-emerald-500/10 text-emerald-400 rounded-xl border border-emerald-500/20 shrink-0">
              <ShieldCheck className="w-5 h-5" />
            </div>
            <div>
              <h4 className="text-sm font-bold text-white mb-1">100% Foto & Data Asli</h4>
              <p className="text-xs text-slate-400 leading-relaxed">Seluruh kamar telah disurvei tim Stayease dengan jaminan uang kembali bila tidak sesuai.</p>
            </div>
          </div>

          <div className="flex items-start gap-3.5">
            <div className="p-2.5 bg-emerald-500/10 text-emerald-400 rounded-xl border border-emerald-500/20 shrink-0">
              <CreditCard className="w-5 h-5" />
            </div>
            <div>
              <h4 className="text-sm font-bold text-white mb-1">Pembayaran Resmi & Aman</h4>
              <p className="text-xs text-slate-400 leading-relaxed">Didukung QRIS instan, Virtual Account BCA/Mandiri, dan escrow sistem otomatis.</p>
            </div>
          </div>

          <div className="flex items-start gap-3.5">
            <div className="p-2.5 bg-emerald-500/10 text-emerald-400 rounded-xl border border-emerald-500/20 shrink-0">
              <Lock className="w-5 h-5" />
            </div>
            <div>
              <h4 className="text-sm font-bold text-white mb-1">Kontrak Sewa Digital</h4>
              <p className="text-xs text-slate-400 leading-relaxed">Perjanjian sewa berkekuatan hukum dengan e-Signature resmi dan tanda tangan digital.</p>
            </div>
          </div>

          <div className="flex items-start gap-3.5">
            <div className="p-2.5 bg-emerald-500/10 text-emerald-400 rounded-xl border border-emerald-500/20 shrink-0">
              <Headphones className="w-5 h-5" />
            </div>
            <div>
              <h4 className="text-sm font-bold text-white mb-1">Bantuan 24/7 Siaga</h4>
              <p className="text-xs text-slate-400 leading-relaxed">Customer care via WhatsApp siap membantu proses check-in dan kendala hunian.</p>
            </div>
          </div>
        </div>

        {/* Main Links Hierarchy */}
        <div className="grid grid-cols-2 md:grid-cols-5 gap-8 py-12 border-b border-slate-800">
          
          {/* Brand Info */}
          <div className="col-span-2 space-y-4">
            <div className="flex items-center gap-2.5">
              <div className="w-9 h-9 rounded-xl bg-emerald-600 flex items-center justify-center text-white shadow-md">
                <Building className="w-5 h-5" />
              </div>
              <span className="text-xl font-extrabold text-white tracking-tight">
                Stay<span className="text-emerald-400">ease</span> Indonesia
              </span>
            </div>
            <p className="text-xs text-slate-400 leading-relaxed max-w-sm">
              Platform resmi penemuan & sewa hunian No. 1 di Indonesia. Menghubungkan jutaan pencari kost, penyewa apartemen, kontrakan, dan villa dengan pemilik properti terpercaya.
            </p>
            <div className="flex items-center gap-3 pt-2 text-xs text-slate-400">
              <div className="flex items-center gap-1">
                <CheckCircle className="w-3.5 h-3.5 text-emerald-400" />
                <span>Terdaftar Kominfo RI</span>
              </div>
              <span>•</span>
              <div className="flex items-center gap-1">
                <CheckCircle className="w-3.5 h-3.5 text-emerald-400" />
                <span>ISO 27001 Certified</span>
              </div>
            </div>
          </div>

          {/* Kota Populer (SEO internal links) */}
          <div className="space-y-3">
            <h5 className="text-xs font-bold uppercase tracking-wider text-white">Sewa Per Kota</h5>
            <ul className="space-y-2 text-xs text-slate-400">
              {CITIES.map(c => (
                <li key={c.id}>
                  <button 
                    onClick={() => onNavigate('city-hub', c.slug)}
                    className="hover:text-emerald-400 transition"
                  >
                    Kost & Apt {c.name}
                  </button>
                </li>
              ))}
            </ul>
          </div>

          {/* Kampus Populer */}
          <div className="space-y-3">
            <h5 className="text-xs font-bold uppercase tracking-wider text-white">Dekat Kampus</h5>
            <ul className="space-y-2 text-xs text-slate-400">
              <li><button onClick={() => onNavigate('explore', { search: 'UGM' })} className="hover:text-emerald-400 transition">Kost Dekat UGM Jogja</button></li>
              <li><button onClick={() => onNavigate('explore', { search: 'ITB' })} className="hover:text-emerald-400 transition">Kost Dekat ITB Bandung</button></li>
              <li><button onClick={() => onNavigate('explore', { search: 'UI Depok' })} className="hover:text-emerald-400 transition">Kost Dekat UI Depok</button></li>
              <li><button onClick={() => onNavigate('explore', { search: 'UNAIR' })} className="hover:text-emerald-400 transition">Kost Dekat UNAIR Surabaya</button></li>
              <li><button onClick={() => onNavigate('explore', { search: 'Telkom' })} className="hover:text-emerald-400 transition">Kost Dekat Telkom Univ</button></li>
            </ul>
          </div>

          {/* Layanan & Pemilik */}
          <div className="space-y-3">
            <h5 className="text-xs font-bold uppercase tracking-wider text-white">Mitra Pemilik & Bantuan</h5>
            <ul className="space-y-2 text-xs text-slate-400">
              <li><button onClick={() => onNavigate('owner-wizard')} className="text-emerald-400 font-semibold hover:underline">Pasang Iklan Sewa Gratis</button></li>
              <li><button onClick={() => onNavigate('owner-wizard')} className="hover:text-emerald-400 transition">Kalkulator Pendapatan Kost</button></li>
              <li><a href="#faq" className="hover:text-emerald-400 transition">Pusat Bantuan & FAQ</a></li>
              <li><a href="#syarat" className="hover:text-emerald-400 transition">Syarat & Ketentuan Sewa</a></li>
              <li><a href="#kebijakan" className="hover:text-emerald-400 transition">Kebijakan Privasi Data</a></li>
            </ul>
          </div>

        </div>

        {/* Payment Gateways & Copyright */}
        <div className="pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-xs text-slate-500">
          <div>
            © 2026 PT Stayease Digital Indonesia. Seluruh hak cipta dilindungi undang-undang.
          </div>

          <div className="flex items-center gap-3 text-[11px] text-slate-400 font-medium">
            <span>Metode Bayar Resmi:</span>
            <span className="px-2 py-0.5 bg-slate-800 rounded font-bold text-white">QRIS</span>
            <span className="px-2 py-0.5 bg-slate-800 rounded font-bold text-white">BCA VA</span>
            <span className="px-2 py-0.5 bg-slate-800 rounded font-bold text-white">Mandiri</span>
            <span className="px-2 py-0.5 bg-slate-800 rounded font-bold text-white">GoPay</span>
            <span className="px-2 py-0.5 bg-slate-800 rounded font-bold text-white">ShopeePay</span>
          </div>
        </div>

      </div>
    </footer>
  );
};
