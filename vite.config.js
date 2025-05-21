import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/modules/jodit.js',
                'resources/js/modules/chart.js',
                'resources/js/modules/leaflet.js',
                'resources/js/dhtt/drawRoute.js',
                'resources/js/dhtt/statistics.js'
            ],
            refresh: true,
        }),
        tailwindcss()
    ]
});
