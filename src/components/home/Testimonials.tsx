import React from 'react';
import { CheckCircle2, MessageSquare, Quote, Star } from 'lucide-react';

export const Testimonials: React.FC = () => {
  const testimonials = [
    {
      id: 1,
      name: 'Nadia Putri',
      role: 'Mahasiswi FK UGM Yogyakarta',
      avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=150&q=80',
      text: 'Fitur Virtual Tour 360° ngebantu banget pas masih di luar pulau. Pas sampai di Pogung Jogja, kamarnya 100% persis seperti di foto dan video! Ibu kostnya juga ramah banget.',
      rating: 5,
      propertyRented: 'Kost Griya Pogung UGM'
    },
    {
      id: 2,
      name: 'Reza Mahardika',
      role: 'Software Engineer di Sudirman SCBD',
      avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=150&q=80',
      text: 'Sewa apartemen bulanan via Stayease bebas ribet. Bayar pakai QRIS langsung aktif, ada kontrak sewa digital resmi yang jelas hak dan kewajibannya. Praktis banget!',
      rating: 5,
      propertyRented: 'Apartemen Pakuwon Tower Orchid'
    },
    {
      id: 3,
      name: 'Chloe Bennett',
      role: 'Product Designer (WFA Bali)',
      avatar: 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=150&q=80',
      text: 'Finding a reliable co-living space in Canggu with high-speed fiber internet and verified photos was effortless with Stayease. Customer support was super responsive via WhatsApp.',
      rating: 5,
      propertyRented: 'The Bohemia Haven Villa Canggu'
    }
  ];

  return (
    <section className="py-16 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div className="text-center max-w-2xl mx-auto mb-12 space-y-2">
          <div className="inline-flex items-center gap-1 text-xs font-extrabold text-emerald-600 uppercase tracking-wider">
            <MessageSquare className="w-4 h-4" />
            <span>Pengalaman Nyata Penyewa</span>
          </div>
          <h2 className="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
            Dipercaya Lebih dari 50.000+ Penyewa
          </h2>
          <p className="text-xs sm:text-sm text-slate-500">
            Dengarkan cerita mahasiswa dan profesional yang telah menemukan kenyamanan hunian bersama Stayease.
          </p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {testimonials.map((item) => (
            <div
              key={item.id}
              className="p-6 rounded-3xl bg-slate-50 border border-slate-200/80 shadow-subtle hover:shadow-card transition-all duration-300 flex flex-col justify-between"
            >
              <div>
                {/* Stars */}
                <div className="flex items-center gap-1 mb-4 text-amber-400">
                  {[...Array(item.rating)].map((_, i) => (
                    <Star key={i} className="w-4 h-4 fill-current" />
                  ))}
                </div>

                <p className="text-xs sm:text-sm text-slate-700 leading-relaxed italic mb-6">
                  "{item.text}"
                </p>
              </div>

              <div className="pt-4 border-t border-slate-200/60 flex items-center gap-3">
                <img
                  src={item.avatar}
                  alt={item.name}
                  className="w-10 h-10 rounded-full object-cover border-2 border-emerald-500"
                />
                <div>
                  <div className="flex items-center gap-1">
                    <h4 className="text-xs font-bold text-slate-900">{item.name}</h4>
                    <CheckCircle2 className="w-3.5 h-3.5 text-emerald-600" />
                  </div>
                  <p className="text-[11px] text-slate-500">{item.role}</p>
                  <p className="text-[10px] text-emerald-700 font-semibold mt-0.5">{item.propertyRented}</p>
                </div>
              </div>
            </div>
          ))}
        </div>

      </div>
    </section>
  );
};
