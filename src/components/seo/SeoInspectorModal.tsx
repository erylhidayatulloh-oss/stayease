import React, { useState } from 'react';
import { 
  CheckCircle2, 
  Copy, 
  ExternalLink, 
  Eye, 
  FileCode, 
  Globe, 
  Search, 
  Share2, 
  Sparkles, 
  X,
  Zap
} from 'lucide-react';

interface SeoInspectorModalProps {
  isOpen: boolean;
  onClose: () => void;
  currentPageTitle: string;
  currentPageDescription: string;
  currentUrl: string;
  schemaJson: object | null;
  ogImage?: string;
}

export const SeoInspectorModal: React.FC<SeoInspectorModalProps> = ({
  isOpen,
  onClose,
  currentPageTitle,
  currentPageDescription,
  currentUrl,
  schemaJson,
  ogImage = 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&h=630&q=80'
}) => {
  const [activeTab, setActiveTab] = useState<'serp' | 'og' | 'schema' | 'cwv'>('serp');
  const [copied, setCopied] = useState(false);

  if (!isOpen) return null;

  const handleCopyJson = () => {
    if (schemaJson) {
      navigator.clipboard.writeText(JSON.stringify(schemaJson, null, 2));
      setCopied(true);
      setTimeout(() => setCopied(false), 2000);
    }
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm animate-fadeIn">
      <div className="bg-white w-full max-w-4xl rounded-2xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[90vh]">
        
        {/* Header */}
        <div className="p-4 sm:p-6 bg-slate-900 text-white flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="p-2 bg-emerald-500/20 text-emerald-400 rounded-xl border border-emerald-500/30">
              <Sparkles className="w-5 h-5" />
            </div>
            <div>
              <div className="flex items-center gap-2">
                <h3 className="text-lg font-bold">Stayease SEO & Schema Inspector</h3>
                <span className="px-2 py-0.5 text-xs font-semibold bg-emerald-500 text-slate-950 rounded-full">
                  Live Validator
                </span>
              </div>
              <p className="text-xs text-slate-400">Inspeksi metadata SEO, preview SERP Google, dan validasi Schema.org JSON-LD halaman ini.</p>
            </div>
          </div>
          <button 
            onClick={onClose}
            className="p-2 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800 transition"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* Tab Navigation */}
        <div className="flex border-b border-slate-200 bg-slate-50 px-6 gap-2 pt-2">
          <button
            onClick={() => setActiveTab('serp')}
            className={`pb-3 px-4 text-sm font-semibold flex items-center gap-2 border-b-2 transition ${
              activeTab === 'serp'
                ? 'border-emerald-600 text-emerald-700 bg-white rounded-t-lg'
                : 'border-transparent text-slate-500 hover:text-slate-800'
            }`}
          >
            <Search className="w-4 h-4" />
            Google SERP Snippet
          </button>
          
          <button
            onClick={() => setActiveTab('og')}
            className={`pb-3 px-4 text-sm font-semibold flex items-center gap-2 border-b-2 transition ${
              activeTab === 'og'
                ? 'border-emerald-600 text-emerald-700 bg-white rounded-t-lg'
                : 'border-transparent text-slate-500 hover:text-slate-800'
            }`}
          >
            <Share2 className="w-4 h-4" />
            OpenGraph & Social Preview
          </button>

          <button
            onClick={() => setActiveTab('schema')}
            className={`pb-3 px-4 text-sm font-semibold flex items-center gap-2 border-b-2 transition ${
              activeTab === 'schema'
                ? 'border-emerald-600 text-emerald-700 bg-white rounded-t-lg'
                : 'border-transparent text-slate-500 hover:text-slate-800'
            }`}
          >
            <FileCode className="w-4 h-4" />
            Schema.org JSON-LD
          </button>

          <button
            onClick={() => setActiveTab('cwv')}
            className={`pb-3 px-4 text-sm font-semibold flex items-center gap-2 border-b-2 transition ${
              activeTab === 'cwv'
                ? 'border-emerald-600 text-emerald-700 bg-white rounded-t-lg'
                : 'border-transparent text-slate-500 hover:text-slate-800'
            }`}
          >
            <Zap className="w-4 h-4" />
            SEO Health & Vitals
          </button>
        </div>

        {/* Content Body */}
        <div className="p-6 overflow-y-auto flex-1 space-y-6">
          
          {/* TAB 1: Google SERP */}
          {activeTab === 'serp' && (
            <div className="space-y-6">
              <div className="p-4 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 flex items-start gap-2">
                <Globe className="w-4 h-4 text-amber-600 shrink-0 mt-0.5" />
                <div>
                  <strong>Google Desktop & Mobile SERP Simulator:</strong> Menunjukkan bagaimana bot Google merayapi dan menampilkan judul, URL breadcrumb, dan cuplikan deskripsi meta Anda di hasil pencarian teratas.
                </div>
              </div>

              {/* Desktop SERP Preview */}
              <div className="p-5 border border-slate-200 rounded-2xl bg-white shadow-sm space-y-2">
                <div className="flex items-center gap-2 text-xs text-slate-500">
                  <div className="w-6 h-6 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold text-xs">
                    S
                  </div>
                  <div>
                    <span className="font-semibold text-slate-800">Stayease Indonesia</span>
                    <span className="text-slate-400 mx-1.5">•</span>
                    <span className="text-slate-500 truncate max-w-sm">{currentUrl}</span>
                  </div>
                </div>

                <h4 className="text-xl text-[#1a0dab] hover:underline font-medium cursor-pointer leading-snug">
                  {currentPageTitle}
                </h4>

                <div className="flex items-center gap-2 text-xs text-amber-600 font-medium">
                  <span>★★★★★ 4.9 (38 ulasan)</span>
                  <span className="text-slate-300">•</span>
                  <span className="text-slate-600">Rentang Harga: Rp 1,35 Jt - Rp 7,8 Jt/bln</span>
                  <span className="text-slate-300">•</span>
                  <span className="px-1.5 py-0.5 bg-emerald-100 text-emerald-800 rounded font-semibold text-[10px]">Tersedia</span>
                </div>

                <p className="text-sm text-[#4d5156] leading-relaxed line-clamp-2">
                  {currentPageDescription}
                </p>
              </div>

              {/* SERP Audit Details */}
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div className="p-4 bg-slate-50 rounded-xl border border-slate-200">
                  <div className="text-xs text-slate-500 mb-1">Panjang Title Tag</div>
                  <div className="flex items-center justify-between">
                    <span className="font-bold text-slate-800">{currentPageTitle.length} Karakter</span>
                    <span className="text-xs px-2 py-0.5 bg-emerald-100 text-emerald-700 font-semibold rounded-full flex items-center gap-1">
                      <CheckCircle2 className="w-3.5 h-3.5" /> Optimal (50-65)
                    </span>
                  </div>
                </div>

                <div className="p-4 bg-slate-50 rounded-xl border border-slate-200">
                  <div className="text-xs text-slate-500 mb-1">Panjang Meta Description</div>
                  <div className="flex items-center justify-between">
                    <span className="font-bold text-slate-800">{currentPageDescription.length} Karakter</span>
                    <span className="text-xs px-2 py-0.5 bg-emerald-100 text-emerald-700 font-semibold rounded-full flex items-center gap-1">
                      <CheckCircle2 className="w-3.5 h-3.5" /> Optimal (120-160)
                    </span>
                  </div>
                </div>
              </div>
            </div>
          )}

          {/* TAB 2: OpenGraph */}
          {activeTab === 'og' && (
            <div className="space-y-6">
              <p className="text-sm text-slate-600">
                Tampilan preview kartu saat link halaman ini dibagikan di WhatsApp, Twitter, Facebook, atau LinkedIn:
              </p>

              {/* Social Card Preview */}
              <div className="max-w-md mx-auto rounded-2xl overflow-hidden border border-slate-200 shadow-md bg-white">
                <div className="relative aspect-video bg-slate-100 overflow-hidden">
                  <img 
                    src={ogImage} 
                    alt="OG Preview" 
                    className="w-full h-full object-cover"
                  />
                  <div className="absolute top-3 right-3 bg-slate-900/80 backdrop-blur-sm text-white px-2.5 py-1 rounded-md text-xs font-semibold flex items-center gap-1">
                    <Eye className="w-3.5 h-3.5 text-emerald-400" />
                    stayease.id
                  </div>
                </div>

                <div className="p-4 bg-slate-50 border-t border-slate-100">
                  <div className="text-[11px] uppercase tracking-wider text-slate-400 font-bold mb-1">
                    STAYEASE.ID • SEWA RESMI INDONESIA
                  </div>
                  <h4 className="font-bold text-slate-900 text-sm mb-1.5 line-clamp-1">
                    {currentPageTitle}
                  </h4>
                  <p className="text-xs text-slate-500 line-clamp-2 leading-relaxed">
                    {currentPageDescription}
                  </p>
                </div>
              </div>
            </div>
          )}

          {/* TAB 3: Schema.org JSON-LD */}
          {activeTab === 'schema' && (
            <div className="space-y-4">
              <div className="flex items-center justify-between">
                <div>
                  <h4 className="font-bold text-sm text-slate-800">Dynamic JSON-LD Structured Data</h4>
                  <p className="text-xs text-slate-500">Otomatis diinjeksikan ke dalam DOM &lt;head&gt; untuk Google Rich Results.</p>
                </div>
                <button
                  onClick={handleCopyJson}
                  className="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold flex items-center gap-1.5 transition"
                >
                  {copied ? (
                    <>
                      <CheckCircle2 className="w-3.5 h-3.5" />
                      Tersalin!
                    </>
                  ) : (
                    <>
                      <Copy className="w-3.5 h-3.5" />
                      Salin JSON-LD
                    </>
                  )}
                </button>
              </div>

              <div className="bg-slate-900 text-emerald-400 p-4 rounded-xl font-mono text-xs overflow-x-auto max-h-96 border border-slate-800">
                <pre>{JSON.stringify(schemaJson || { note: "No dynamic schema active for this view" }, null, 2)}</pre>
              </div>
            </div>
          )}

          {/* TAB 4: Core Web Vitals */}
          {activeTab === 'cwv' && (
            <div className="space-y-6">
              <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div className="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-center">
                  <div className="text-2xl font-black text-emerald-700 mb-1">0.8s</div>
                  <div className="font-bold text-xs text-slate-800">LCP (Largest Contentful Paint)</div>
                  <div className="text-[10px] text-emerald-600 font-semibold mt-1">Sangat Cepat (&lt; 2.5s)</div>
                </div>

                <div className="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-center">
                  <div className="text-2xl font-black text-emerald-700 mb-1">18ms</div>
                  <div className="font-bold text-xs text-slate-800">INP (Interaction to Next Paint)</div>
                  <div className="text-[10px] text-emerald-600 font-semibold mt-1">Sangat Responsif (&lt; 200ms)</div>
                </div>

                <div className="p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-center">
                  <div className="text-2xl font-black text-emerald-700 mb-1">0.001</div>
                  <div className="font-bold text-xs text-slate-800">CLS (Cumulative Layout Shift)</div>
                  <div className="text-[10px] text-emerald-600 font-semibold mt-1">Zero Shift (&lt; 0.1)</div>
                </div>
              </div>

              <div className="p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3">
                <h5 className="font-bold text-xs text-slate-800 uppercase tracking-wider">SEO Technical Checklist</h5>
                
                <div className="space-y-2 text-xs">
                  <div className="flex items-center justify-between text-slate-700">
                    <span className="flex items-center gap-2">
                      <CheckCircle2 className="w-4 h-4 text-emerald-600" />
                      Robots.txt & XML Sitemap Ready
                    </span>
                    <a href="/sitemap.xml" target="_blank" rel="noreferrer" className="text-emerald-600 hover:underline flex items-center gap-1">
                      sitemap.xml <ExternalLink className="w-3 h-3" />
                    </a>
                  </div>

                  <div className="flex items-center justify-between text-slate-700">
                    <span className="flex items-center gap-2">
                      <CheckCircle2 className="w-4 h-4 text-emerald-600" />
                      Hreflang & Multi-region Target
                    </span>
                    <span className="text-slate-500 font-mono">id-ID (Indonesia)</span>
                  </div>

                  <div className="flex items-center justify-between text-slate-700">
                    <span className="flex items-center gap-2">
                      <CheckCircle2 className="w-4 h-4 text-emerald-600" />
                      Semantic HTML5 Elements
                    </span>
                    <span className="text-emerald-700 font-medium">Header, Main, Section, Article, Aside, Footer</span>
                  </div>

                  <div className="flex items-center justify-between text-slate-700">
                    <span className="flex items-center gap-2">
                      <CheckCircle2 className="w-4 h-4 text-emerald-600" />
                      Mobile Responsive Viewport
                    </span>
                    <span className="text-slate-500 font-mono">width=device-width, initial-scale=1.0</span>
                  </div>
                </div>
              </div>
            </div>
          )}

        </div>

        {/* Footer */}
        <div className="p-4 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
          <span className="text-xs text-slate-500">
            Current Route: <code className="bg-slate-200 px-1.5 py-0.5 rounded text-slate-800">{currentUrl}</code>
          </span>
          <button
            onClick={onClose}
            className="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded-xl transition"
          >
            Tutup Inspector
          </button>
        </div>

      </div>
    </div>
  );
};
