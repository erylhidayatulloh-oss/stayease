// Base client for talking to the real Laravel backend (routes/api.php).
// The frontend is built and served from the same Laravel app (see vite.config.ts
// `base`/`build.outDir` and routes/web.php), so relative paths work with no CORS setup needed.

const API_BASE = '/api/v1';

export class ApiError extends Error {
  status: number;
  errors?: Record<string, string[]>;

  constructor(message: string, status: number, errors?: Record<string, string[]>) {
    super(message);
    this.status = status;
    this.errors = errors;
  }
}

async function request<T>(path: string, options: RequestInit = {}): Promise<T> {
  const res = await fetch(`${API_BASE}${path}`, {
    ...options,
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      ...(options.headers || {}),
    },
  });

  let body: any = null;
  try {
    body = await res.json();
  } catch {
    // Non-JSON response (e.g. a raw 500 HTML error page) — keep body as null.
  }

  if (!res.ok) {
    throw new ApiError(
      body?.message || `Permintaan gagal (HTTP ${res.status}).`,
      res.status,
      body?.errors
    );
  }

  return body as T;
}

export const apiGet = <T,>(path: string) => request<T>(path, { method: 'GET' });

export const apiPost = <T,>(path: string, payload: unknown) =>
  request<T>(path, { method: 'POST', body: JSON.stringify(payload) });
