import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: ['resources/js/main.jsx', 'resources/js/index.css'],
      refresh: true,
    }),
    react(),
  ],
  build: {
    manifest: "manifest.json",
  }
});
