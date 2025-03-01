import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        // Ensure Bootstrap is properly bundled
        commonjsOptions: {
            include: [/node_modules/],
        },
        rollupOptions: {
            external: [],
        },
        // Ensure the build output is in the correct location
        outDir: 'public/build',
        emptyOutDir: true,
        manifest: true,
    },
});
