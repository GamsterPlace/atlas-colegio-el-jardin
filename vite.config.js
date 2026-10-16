
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/atlas.css',
                'resources/css/login.css',
                'resources/css/inicio.css',
                'resources/css/nosotros.css',
                'resources/css/oferta-academica.css',
                'resources/css/vida-estudiantil.css',
                'resources/css/noticias.css',
                'resources/css/contacto.css',
            ],
            refresh: true,
        }),
        vue(),
    ],
});
