import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        // Generar source maps para mejor depuración
        sourcemap: true,
        // Optimizaciones de compilación
        minify: 'terser',
        // Incluir archivos de fuentes en la compilación
        assetsInclude: ['**/*.woff', '**/*.woff2', '**/*.ttf', '**/*.eot'],
    },
    // Configuración del servidor de desarrollo
    server: {
        // Forzar recarga en caliente
        hmr: {
            host: 'localhost',
        },
        // Configuración de cabeceras para permitir fuentes locales
        headers: {
            'Cache-Control': 'public, max-age=0',
        }
    }
});
