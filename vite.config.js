import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',        // agar bisa diakses dari perangkat lain
        port: 5173,             // port default Vite
        hmr: {
            host: '192.168.18.40', // ganti pake IP lokal laptop
        },
    },
    plugins: [
        laravel([
            'resources/css/app.css',
            'resources/js/app.js',
        ]),
    ],
});