// The storefront has no full login system for browsing — visitors book,
// chat, and save wishlists without creating an account up front. To still
// tie that activity to a real backend user (instead of a hardcoded/fake
// placeholder — see PERBAIKAN.md and the booking user_id bug fix), we
// remember the visitor's own name/email/phone locally once they've given it
// to us (e.g. via a booking or a chat message) and reuse it silently after
// that, the same way a browser remembers a saved shipping address.

import { GuestIdentity } from '../api/stayease';

const STORAGE_KEY = 'stayease_guest_identity';

export function getGuestIdentity(): GuestIdentity | null {
  try {
    const raw = localStorage.getItem(STORAGE_KEY);
    if (!raw) return null;
    const parsed = JSON.parse(raw);
    if (parsed && typeof parsed.email === 'string' && typeof parsed.name === 'string') {
      return parsed as GuestIdentity;
    }
    return null;
  } catch {
    return null;
  }
}

export function saveGuestIdentity(identity: GuestIdentity): void {
  try {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(identity));
  } catch {
    // Ignore storage errors (e.g. private browsing mode) — the visitor can
    // still use the site, they'll just be asked to identify themselves again.
  }
}
