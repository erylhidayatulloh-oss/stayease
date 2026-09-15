import React, { useEffect, useState } from 'react';
import {
  AlertCircle,
  CheckCheck,
  Loader2,
  Send,
  ShieldCheck,
  X
} from 'lucide-react';
import { Landlord, Property } from '../../types';
import { ChatMessageRecord, GuestIdentity, fetchMessageThread, sendChatMessage } from '../../api';
import { getGuestIdentity, saveGuestIdentity } from '../../utils/guestIdentity';

interface ChatModalProps {
  isOpen: boolean;
  onClose: () => void;
  landlord: Landlord;
  property: Property;
}

// Bug fix: this modal used to be entirely fake — it echoed the visitor's
// own message back with a randomly-picked canned reply after a timeout,
// and never touched the backend at all, so the property owner never
// actually received anything. It now sends and loads real messages via
// /api/v1/messages (the same `messages` table the Mitra/Tenant dashboard
// "Pesan" pages use), identified by the visitor's own email — see
// src/utils/guestIdentity.ts for why.
export const ChatModal: React.FC<ChatModalProps> = ({
  isOpen,
  onClose,
  landlord,
  property
}) => {
  const [identity, setIdentity] = useState<GuestIdentity | null>(() => getGuestIdentity());

  // "Identify yourself" mini-form — only shown the first time a visitor
  // opens any chat on this browser, then remembered for next time.
  const [nameInput, setNameInput] = useState('');
  const [emailInput, setEmailInput] = useState('');
  const [phoneInput, setPhoneInput] = useState('');

  const [messages, setMessages] = useState<ChatMessageRecord[]>([]);
  const [isLoadingThread, setIsLoadingThread] = useState(false);
  const [loadError, setLoadError] = useState<string | null>(null);

  const [inputText, setInputText] = useState('');
  const [isSending, setIsSending] = useState(false);

  useEffect(() => {
    if (!isOpen || !identity) return;
    let cancelled = false;

    setIsLoadingThread(true);
    setLoadError(null);
    fetchMessageThread(property.id, identity.email)
      .then((thread) => {
        if (!cancelled) setMessages(thread);
      })
      .catch(() => {
        if (!cancelled) setLoadError('Gagal memuat riwayat chat. Periksa koneksi Anda.');
      })
      .finally(() => {
        if (!cancelled) setIsLoadingThread(false);
      });

    return () => {
      cancelled = true;
    };
  }, [isOpen, identity, property.id]);

  if (!isOpen) return null;

  const handleStartChat = (e: React.FormEvent) => {
    e.preventDefault();
    if (!nameInput.trim() || !emailInput.trim()) return;
    const newIdentity: GuestIdentity = {
      name: nameInput.trim(),
      email: emailInput.trim(),
      phone: phoneInput.trim() || undefined,
    };
    saveGuestIdentity(newIdentity);
    setIdentity(newIdentity);
  };

  const handleSendMessage = async (e: React.FormEvent) => {
    e.preventDefault();
    const content = inputText.trim();
    if (!content || !identity || isSending) return;

    setInputText('');
    setIsSending(true);
    setLoadError(null);
    try {
      const sent = await sendChatMessage(property.id, identity, content);
      setMessages(prev => [...prev, sent]);
    } catch {
      setLoadError('Pesan gagal terkirim. Silakan coba lagi.');
      setInputText(content);
    } finally {
      setIsSending(false);
    }
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm animate-fadeIn">
      <div className="bg-white w-full max-w-lg rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col h-[550px]">
        
        {/* Header */}
        <div className="p-4 bg-slate-900 text-white flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="relative">
              <img
                src={landlord.avatar}
                alt={landlord.name}
                className="w-10 h-10 rounded-full object-cover border-2 border-emerald-500"
              />
              <span className="w-3 h-3 bg-emerald-500 border-2 border-slate-900 rounded-full absolute bottom-0 right-0" />
            </div>
            <div>
              <div className="flex items-center gap-1.5">
                <h4 className="font-extrabold text-sm">{landlord.name}</h4>
                <ShieldCheck className="w-3.5 h-3.5 text-emerald-400" />
              </div>
              <p className="text-[10px] text-emerald-400 font-semibold">{landlord.badge}</p>
            </div>
          </div>

          <button
            onClick={onClose}
            className="p-2 text-slate-400 hover:text-white rounded-xl hover:bg-slate-800 transition"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        {!identity ? (
          /* Identify Yourself (once per browser) */
          <form onSubmit={handleStartChat} className="p-6 flex-1 flex flex-col justify-center gap-3 bg-slate-50">
            <p className="text-xs text-slate-600 mb-1">
              Isi data singkat ini supaya pesan Anda bisa dibalas langsung oleh pemilik hunian.
            </p>
            <input
              type="text"
              required
              placeholder="Nama lengkap"
              value={nameInput}
              onChange={(e) => setNameInput(e.target.value)}
              className="w-full p-3 bg-white text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500"
            />
            <input
              type="email"
              required
              placeholder="Email aktif"
              value={emailInput}
              onChange={(e) => setEmailInput(e.target.value)}
              className="w-full p-3 bg-white text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500"
            />
            <input
              type="tel"
              placeholder="No. WhatsApp (opsional)"
              value={phoneInput}
              onChange={(e) => setPhoneInput(e.target.value)}
              className="w-full p-3 bg-white text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500"
            />
            <button
              type="submit"
              className="mt-1 p-3 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black rounded-xl shadow-sm transition"
            >
              Mulai Chat
            </button>
          </form>
        ) : (
          <>
            {/* Message Area */}
            <div className="p-4 overflow-y-auto flex-1 space-y-3 bg-slate-50">
              <div className="p-2.5 bg-emerald-50 text-emerald-900 rounded-xl text-[11px] border border-emerald-200 flex items-center gap-2">
                <ShieldCheck className="w-4 h-4 text-emerald-600 shrink-0" />
                <span>Pesan terlindungi oleh enkripsi Stayease. Dilarang melakukan transaksi di luar platform resmi.</span>
              </div>

              {isLoadingThread && (
                <div className="flex items-center justify-center gap-2 text-slate-400 text-xs py-6">
                  <Loader2 className="w-4 h-4 animate-spin" />
                  <span>Memuat percakapan...</span>
                </div>
              )}

              {!isLoadingThread && messages.length === 0 && !loadError && (
                <div className="text-center py-10 text-slate-400 text-xs">
                  Belum ada percakapan. Kirim pesan pertama Anda ke {landlord.name}.
                </div>
              )}

              {messages.map((m) => (
                <div
                  key={m.id}
                  className={`flex flex-col ${m.from_visitor ? 'items-end' : 'items-start'}`}
                >
                  <div
                    className={`p-3 rounded-2xl max-w-[80%] text-xs leading-relaxed ${
                      m.from_visitor
                        ? 'bg-emerald-600 text-white rounded-br-none shadow-sm'
                        : 'bg-white text-slate-900 rounded-bl-none border border-slate-200 shadow-subtle'
                    }`}
                  >
                    {m.content}
                  </div>
                  <div className="flex items-center gap-1 text-[10px] text-slate-400 mt-1 px-1">
                    <span>{m.time}</span>
                    {m.from_visitor && <CheckCheck className="w-3 h-3 text-emerald-600" />}
                  </div>
                </div>
              ))}

              {loadError && (
                <div className="flex items-center gap-2 p-2.5 bg-rose-50 text-rose-700 rounded-xl text-[11px] border border-rose-200">
                  <AlertCircle className="w-4 h-4 shrink-0" />
                  <span>{loadError}</span>
                </div>
              )}
            </div>

            {/* Input Bar */}
            <form onSubmit={handleSendMessage} className="p-3 bg-white border-t border-slate-200 flex items-center gap-2">
              <input
                type="text"
                placeholder="Tulis pesan ke pemilik hunian..."
                value={inputText}
                onChange={(e) => setInputText(e.target.value)}
                disabled={isSending}
                className="flex-1 p-2.5 bg-slate-50 text-xs font-semibold rounded-xl border border-slate-200 focus:outline-none focus:border-emerald-500 disabled:opacity-60"
              />
              <button
                type="submit"
                disabled={!inputText.trim() || isSending}
                className="p-2.5 bg-emerald-600 hover:bg-emerald-700 disabled:bg-slate-200 text-white rounded-xl transition shadow-sm"
              >
                {isSending ? <Loader2 className="w-4 h-4 animate-spin" /> : <Send className="w-4 h-4" />}
              </button>
            </form>
          </>
        )}

      </div>
    </div>
  );
};
