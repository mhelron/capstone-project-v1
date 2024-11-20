import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
				'resources/css/app.css',
				'resources/css/guest.css',
				'resources/css/guestreservation.css',
				'resources/css/calendar.css',
				'resources/css/gallery.css',

				'resources/js/app.js',
				'resources/js/bootstrap.js',
				'resources/js/app.js',
				'resources/js/logout.js',
				'resources/js/priceinput.js',
				'resources/js/togglesidebar.js',
				'resources/js/guestreservation.js',
				'resources/js/usersnapshot.js',
				'resources/js/modal.js',
				'resources/js/gallery.js',
			],
            refresh: true,
        }),
    ],
});
