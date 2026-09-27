import React from 'react';
import { ArrowRight, Newspaper } from 'lucide-react';
import { Article } from '../../types';

interface ArticlesSectionProps {
  articles: Article[];
  onOpenArticle: (article: Article) => void;
  onViewAll: () => void;
}

const formatArticleDate = (iso: string | null) => {
  if (!iso) return '';
  const d = new Date(iso);
  if (Number.isNaN(d.getTime())) return '';
  return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
};

export const ArticlesSection: React.FC<ArticlesSectionProps> = ({ articles, onOpenArticle, onViewAll }) => {
  // Nothing published yet — skip the whole section rather than showing an
  // empty/broken-looking block on the homepage.
  if (!articles.length) return null;

  return (
    <section className="py-14 bg-white">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div className="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-8">
          <div className="space-y-2">
            <div className="inline-flex items-center gap-1.5 text-xs font-extrabold text-emerald-600 uppercase tracking-wider">
              <Newspaper className="w-3.5 h-3.5" />
              <span>Artikel Stayease</span>
            </div>
            <h2 className="text-2xl sm:text-3xl font-black text-slate-900">Tips & Info Seputar Sewa Hunian</h2>
            <p className="text-sm text-slate-500 max-w-xl">Panduan praktis memilih kost, apartemen, dan tips hidup ngekost dari tim Stayease.</p>
          </div>

          <button
            onClick={onViewAll}
            className="shrink-0 flex items-center gap-1.5 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs rounded-xl transition"
          >
            <span>Lihat Semua Artikel</span>
            <ArrowRight className="w-3.5 h-3.5" />
          </button>
        </div>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
          {articles.slice(0, 3).map((article) => (
            <button
              key={article.id}
              onClick={() => onOpenArticle(article)}
              className="text-left bg-white rounded-3xl overflow-hidden border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col"
            >
              <div className="h-40 bg-slate-100 overflow-hidden">
                <img
                  src={article.coverImage || 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80'}
                  alt={article.title}
                  className="w-full h-full object-cover"
                />
              </div>
              <div className="p-5 space-y-2 flex-1 flex flex-col">
                <span className="text-[10px] font-bold text-slate-400">{formatArticleDate(article.publishedAt)}</span>
                <h3 className="font-extrabold text-sm text-slate-900 line-clamp-2">{article.title}</h3>
                {article.excerpt && (
                  <p className="text-xs text-slate-500 line-clamp-3 flex-1">{article.excerpt}</p>
                )}
                <span className="text-[11px] font-bold text-emerald-700 mt-auto">Baca Selengkapnya &rarr;</span>
              </div>
            </button>
          ))}
        </div>
      </div>
    </section>
  );
};
