import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';
import { writeFileSync, unlinkSync, mkdirSync } from 'fs';

export default defineConfig({
  plugins: [
    tailwindcss(),
    {
      name: 'wordpress-hmr',
      /**
       * Write a `hot` file while the dev server is running so the
       * PHP enqueue helper can detect dev mode and point to the
       * Vite dev server instead of the built manifest.
       */
      configureServer(server) {
        server.httpServer?.once('listening', () => {
          const address = server.httpServer.address();
          const port = typeof address === 'string' ? 5173 : (address?.port ?? 5173);

          try {
            mkdirSync('assets/build', { recursive: true });
          } catch {}

          writeFileSync('assets/build/hot', `http://localhost:${port}`);
        });
      },
      /** Remove the `hot` file on build start / server close. */
      buildStart() {
        try {
          unlinkSync('assets/build/hot');
        } catch {}
      },
    },
  ],

  build: {
    outDir: 'assets/build',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: {
        main: 'resources/js/main.js',
      },
    },
  },

  server: {
    cors: true,
    port: 5173,
    strictPort: true,
  },
});
