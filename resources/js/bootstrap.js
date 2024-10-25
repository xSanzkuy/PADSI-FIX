/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

// Menggunakan require alih-alih import
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// Gunakan require untuk Echo dan Pusher
// Jika Anda membutuhkan Pusher dan Laravel Echo, pastikan kode ini digunakan.
const Echo = require('laravel-echo');
const Pusher = require('pusher-js');

window.Pusher = Pusher;

// Set up konfigurasi untuk Echo, disesuaikan dengan aplikasi Laravel Anda
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER || 'mt1',
    wsHost: process.env.MIX_PUSHER_HOST || `ws-${process.env.MIX_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: process.env.MIX_PUSHER_PORT || 80,
    wssPort: process.env.MIX_PUSHER_PORT || 443,
    forceTLS: (process.env.MIX_PUSHER_SCHEME || 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
