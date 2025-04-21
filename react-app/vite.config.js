import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import { resolve } from 'path'

export default defineConfig({
  root: resolve(__dirname, 'src'),

  // Base para que funcione en producción en InfinityFree
  base: '/public/react/',

  plugins: [react()],

  build: {
    outDir: resolve(__dirname, '../public/react'), // Salida en public/react/
    emptyOutDir: true,
    rollupOptions: {
      input: {
        main: resolve(__dirname, 'src/main.jsx'),
      },
    },
  },

  server: {
    port: 5173,
    open: true,
    proxy: {
      '/api': {
        target: 'http://tiendavirtualelparaiso.infinityfreeapp.com',
        changeOrigin: true,
        secure: false,
      },
    },
  },
})
