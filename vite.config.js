import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
				'resources/css/app.css',
				'resources/css/guest.css',
				'resources/js/app.js',
				'resources/js/bootstrap.js',
				'resources/js/app.js',
				'resources/js/logout.js',
				'resources/js/priceinput.js',
				'resources/js/togglesidebar.js'
			],
            refresh: true,
        }),
    ],
});
