import React, { useEffect, useState } from 'react';
import {
  AlertCircle,
  CheckCircle2,
  CornerDownRight,
  Loader2,
  MessageSquare,
  PenLine,
  Star,
  ThumbsUp
} from 'lucide-react';
import { Review } from '../../types';
import { ApiError, ReviewEligibility, checkReviewEligibility, submitPropertyReview } from '../../api';
import { getGuestIdentity } from '../../utils/guestIdentity';

interface ReviewsSectionProps {
  reviews: Review[];
  rating: number;
  reviewCount: number;
  propertyId: string;
}

const StarPicker: React.FC<{ label: string; value: number; onChange: (v: number) => void }> = ({
  label,
  value,
  onChange
}) => (
  <div className="flex items-center justify-between">
    <span className="text-slate-600 font-semibold">{label}</span>
    <div className="flex items-center gap-0.5">
      {[1, 2, 3, 4, 5].map((n) => (
        <button
          key={n}
          type="button"
          onClick={() => onChange(n)}
          className="p-0.5"
          aria-label={`${label}: ${n} dari 5`}
        >
          <Star className={`w-4 h-4 ${n <= value ? 'fill-amber-400 text-amber-500' : 'text-slate-300'}`} />
        </button>
      ))}
    </div>
  </div>
);

// New feature: this used to be read-only — there was no way to write a
// review from the property page at all (only the tenant dashboard could,
// gated to one specific completed booking). This adds that, while keeping
// the exact same rule the page already stated but never enforced here:
// only a tenant with a real, paid booking for this property can review it
// (checked server-side via /api/v1/properties/{id}/review-eligibility).
export const ReviewsSection: React.FC<ReviewsSectionProps> = ({
  reviews,
  rating,
  reviewCount,
  propertyId
}) => {
  const [helpfulCounts, setHelpfulCounts] = useState<{ [id: string]: number }>({});
  const [hasLiked, setHasLiked] = useState<{ [id: string]: boolean }>({});

  const [localReviews, setLocalReviews] = useState<Review[]>(reviews);
  useEffect(() => setLocalReviews(reviews), [reviews]);

  const [eligibility, setEligibility] = useState<ReviewEligibility | null>(null);
  const [isCheckingEligibility, setIsCheckingEligibility] = useState(false);
  const [showForm, setShowForm] = useState(false);
  const [justSubmitted, setJustSubmitted] = useState(false);

  const [formRating, setFormRating] = useState(5);
  const [formCleanliness, setFormCleanliness] = useState(5);
  const [formSecurity, setFormSecurity] = useState(5);
  const [formLocation, setFormLocation] = useState(5);
  const [formComment, setFormComment] = useState('');
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [submitError, setSubmitError] = useState<string | null>(null);

  const identity = getGuestIdentity();

  useEffect(() => {
    if (!identity) return;
    setIsCheckingEligibility(true);
    checkReviewEligibility(propertyId, identity.email)
      .then(setEligibility)
      .catch(() => setEligibility(null))
      .finally(() => setIsCheckingEligibility(false));
    // Only re-check when the property or the known identity's email changes.
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, [propertyId, identity?.email]);

  const handleLike = (id: string, initialCount: number) => {
    if (hasLiked[id]) return;
    setHelpfulCounts(prev => ({ ...prev, [id]: (prev[id] || initialCount) + 1 }));
    setHasLiked(prev => ({ ...prev, [id]: true }));
  };

  const handleSubmitReview = async (e: React.FormEvent) => {
    e.preventDefault();
    if (!identity || formComment.trim().length < 10) {
      setSubmitError('Ceritakan pengalaman Anda minimal 10 karakter.');
      return;
    }

    setIsSubmitting(true);
    setSubmitError(null);
    try {
      const created = await submitPropertyReview(propertyId, identity, {
        rating: formRating,
        cleanlinessRating: formCleanliness,
        securityRating: formSecurity,
        locationRating: formLocation,
        comment: formComment.trim(),
      });

      setLocalReviews(prev => [
        {
          id: String(created.id),
          userName: created.user?.name ?? identity.name,
          userAvatar: created.user?.avatar ?? 'https://images.unsplash.com/photo-1633332755192-727a05c4013d?auto=format&fit=crop&w=150&q=80',
          userRole: 'Penyewa Terverifikasi',
          rating: created.rating,
          cleanliness: created.cleanliness_rating,
          location: created.location_rating,
          facilities: created.security_rating,
          date: created.created_at,
          comment: created.comment,
          helpfulCount: 0,
        },
        ...prev,
      ]);

      setShowForm(false);
      setJustSubmitted(true);
      setFormComment('');
      setEligibility({ eligible: false, reason: 'already_reviewed' });
    } catch (err) {
      setSubmitError(err instanceof ApiError ? err.message : 'Gagal mengirim ulasan. Coba lagi.');
    } finally {
      setIsSubmitting(false);
    }
  };

  const avg = (key: 'cleanliness' | 'location' | 'facilities') =>
    localReviews.length > 0
      ? (localReviews.reduce((sum, r) => sum + r[key], 0) / localReviews.length).toFixed(1)
      : '5.0';

  return (
    <section className="space-y-6">
      
      {/* Header & Rating Breakdown */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between pb-4 border-b border-slate-200 gap-4">
        <div>
          <h3 className="text-lg font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
            <Star className="w-5 h-5 fill-amber-400 text-amber-500" />
            <span>{rating} dari 5.0</span>
            <span className="text-slate-400 text-sm font-normal">({reviewCount} Ulasan Terverifikasi)</span>
          </h3>
          <p className="text-xs text-slate-500 mt-0.5">Ulasan hanya dapat diberikan oleh penyewa yang telah menyelesaikan masa tinggal.</p>
        </div>

        {/* Rating Metrics — computed from actual review data, not fixed numbers */}
        <div className="flex items-center gap-4 text-xs font-semibold text-slate-700">
          <div className="text-center">
            <div className="text-emerald-700 font-extrabold text-sm">{avg('cleanliness')}</div>
            <div className="text-[10px] text-slate-400">Kebersihan</div>
          </div>
          <div className="text-center">
            <div className="text-emerald-700 font-extrabold text-sm">{avg('facilities')}</div>
            <div className="text-[10px] text-slate-400">Keamanan</div>
          </div>
          <div className="text-center">
            <div className="text-emerald-700 font-extrabold text-sm">{avg('location')}</div>
            <div className="text-[10px] text-slate-400">Lokasi</div>
          </div>
        </div>
      </div>

      {/* Write a Review */}
      <div className="p-5 bg-white rounded-2xl border border-slate-200/80">
        {justSubmitted ? (
          <div className="flex items-center gap-2 text-emerald-700 text-xs font-bold">
            <CheckCircle2 className="w-4 h-4" />
            <span>Ulasan Anda berhasil dipublikasikan. Terima kasih sudah berbagi pengalaman!</span>
          </div>
        ) : !identity ? (
          <div className="flex items-center gap-2 text-xs text-slate-500">
            <MessageSquare className="w-4 h-4 shrink-0" />
            <span>Sudah pernah menyewa di sini? Setelah booking selesai, Anda bisa memberi ulasan langsung dari halaman ini.</span>
          </div>
        ) : isCheckingEligibility ? (
          <div className="flex items-center gap-2 text-xs text-slate-400">
            <Loader2 className="w-4 h-4 animate-spin" />
            <span>Memeriksa kelayakan ulasan Anda...</span>
          </div>
        ) : eligibility?.reason === 'already_reviewed' ? (
          <div className="flex items-center gap-2 text-xs text-slate-500">
            <CheckCircle2 className="w-4 h-4 text-emerald-600 shrink-0" />
            <span>Anda sudah memberi ulasan untuk properti ini. Terima kasih!</span>
          </div>
        ) : !eligibility?.eligible ? (
          <div className="flex items-center gap-2 text-xs text-slate-500">
            <MessageSquare className="w-4 h-4 shrink-0" />
            <span>Ulasan hanya bisa diberikan oleh penyewa dengan booking yang sudah dibayar di properti ini.</span>
          </div>
        ) : !showForm ? (
          <button
            onClick={() => setShowForm(true)}
            className="flex items-center gap-2 text-xs font-bold text-emerald-700 hover:text-emerald-800"
          >
            <PenLine className="w-4 h-4" />
            <span>Tulis Ulasan Anda</span>
          </button>
        ) : (
          <form onSubmit={handleSubmitReview} className="space-y-3">
            <h4 className="text-xs font-black text-slate-900">Bagikan Pengalaman Anda</h4>
            <div className="space-y-2 text-xs max-w-xs">
              <StarPicker label="Rating Keseluruhan" value={formRating} onChange={setFormRating} />
              <StarPicker label="Kebersihan" value={formCleanliness} onChange={setFormCleanliness} />
              <StarPicker label="Keamanan" value={formSecurity} onChange={setFormSecurity} />
              <StarPicker label="Lokasi" value={formLocation} onChange={setFormLocation} />
            </div>
            <textarea
              value={formComment}
              onChange={(e) => setFormComment(e.target.value)}
              placeholder="Ceritakan pengalaman tinggal Anda di sini (min. 10 karakter)..."
              rows={3}
              className="w-full p-3 bg-slate-50 text-xs rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500"
            />

            {submitError && (
              <div className="flex items-center gap-2 p-2.5 bg-rose-50 text-rose-700 rounded-xl text-[11px] border border-rose-200">
                <AlertCircle className="w-4 h-4 shrink-0" />
                <span>{submitError}</span>
              </div>
            )}

            <div className="flex items-center gap-2">
              <button
                type="submit"
                disabled={isSubmitting}
                className="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 disabled:opacity-60 text-white text-xs font-black rounded-xl shadow-sm transition flex items-center gap-2"
              >
                {isSubmitting && <Loader2 className="w-4 h-4 animate-spin" />}
                {isSubmitting ? 'Mengirim...' : 'Kirim Ulasan'}
              </button>
              <button
                type="button"
                onClick={() => setShowForm(false)}
                disabled={isSubmitting}
                className="px-4 py-2 bg-slate-100 text-slate-600 text-xs font-bold rounded-xl"
              >
                Batal
              </button>
            </div>
          </form>
        )}
      </div>

      {/* Review List */}
      <div className="space-y-4">
        {localReviews.map((rev) => {
          const currentHelpful = helpfulCounts[rev.id] !== undefined ? helpfulCounts[rev.id] : rev.helpfulCount;
          const isLiked = hasLiked[rev.id];

          return (
            <div
              key={rev.id}
              className="p-5 bg-slate-50 rounded-2xl border border-slate-200/80 space-y-3"
            >
              {/* User Meta */}
              <div className="flex items-center justify-between">
                <div className="flex items-center gap-3">
                  <img
                    src={rev.userAvatar}
                    alt={rev.userName}
                    className="w-10 h-10 rounded-full object-cover border border-emerald-400"
                  />
                  <div>
                    <div className="flex items-center gap-1.5">
                      <h4 className="text-xs font-bold text-slate-900">{rev.userName}</h4>
                      <CheckCircle2 className="w-3.5 h-3.5 text-emerald-600" />
                    </div>
                    <p className="text-[11px] text-slate-500">{rev.userRole}</p>
                  </div>
                </div>

                <div className="text-right">
                  <div className="flex items-center gap-0.5 text-amber-400 justify-end">
                    {[...Array(Math.floor(rev.rating))].map((_, i) => (
                      <Star key={i} className="w-3.5 h-3.5 fill-current" />
                    ))}
                  </div>
                  <span className="text-[10px] text-slate-400">{rev.date}</span>
                </div>
              </div>

              {/* Comment */}
              <p className="text-xs text-slate-700 leading-relaxed">
                "{rev.comment}"
              </p>

              {/* Helpful Counter Button */}
              <div className="flex items-center justify-between pt-2 border-t border-slate-200/60 text-xs">
                <button
                  onClick={() => handleLike(rev.id, rev.helpfulCount)}
                  className={`flex items-center gap-1.5 text-xs font-semibold transition ${
                    isLiked ? 'text-emerald-700 font-bold' : 'text-slate-500 hover:text-slate-800'
                  }`}
                >
                  <ThumbsUp className={`w-3.5 h-3.5 ${isLiked ? 'fill-emerald-600 text-emerald-600' : ''}`} />
                  <span>Membantu ({currentHelpful})</span>
                </button>
              </div>

              {/* Owner Reply */}
              {rev.ownerReply && (
                <div className="p-3 bg-white rounded-xl border border-slate-200/80 text-xs space-y-1 mt-2">
                  <div className="flex items-center gap-1 text-[11px] font-bold text-emerald-800">
                    <CornerDownRight className="w-3.5 h-3.5 text-emerald-600" />
                    <span>Respon dari Pengelola / Pemilik • {rev.ownerReply.date}</span>
                  </div>
                  <p className="text-slate-600 pl-4">
                    {rev.ownerReply.text}
                  </p>
                </div>
              )}
            </div>
          );
        })}
      </div>

    </section>
  );
};
