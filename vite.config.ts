import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react()],
  base: '/storefront/',
  build: {
    outDir: 'public/storefront',
    emptyOutDir: true,
  },
  publicDir: false,
  server: {
    port: 3000,
    open: false,
    host: true,
    proxy: {
      // So `npm run dev` also talks to the real Laravel backend instead of
      // needing a separate CORS setup. Run `php artisan serve` on :8000 first.
      '/api': 'http://127.0.0.1:8000',
    },
  },
});
