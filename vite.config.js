import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
				'resources/css/app.css',
				'resources/css/calendar.css',
				'resources/css/chat.css',
				'resources/css/gallery.css',
				'resources/css/guest.css',
				'resources/css/guestreservation.css',
				'resources/css/guestcalendar.css',

				'resources/js/app.js',
				'resources/js/bootstrap.js',
				'resources/js/calendar.js',
				'resources/js/calendarreservation.js',
				'resources/js/chat.js',
				'resources/js/gallery.js',
				'resources/js/guestcalendar.js',
				'resources/js/guestcalendarreservations.js',
				'resources/js/guestreservation.js',
				'resources/js/logout.js',
				'resources/js/modal.js',
				'resources/js/priceinput.js',
				'resources/js/togglesidebar.js',
				'resources/js/usersnapshot.js',
			],
            refresh: true,
        }),
    ],
});
