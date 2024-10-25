require('./bootstrap'); // Menggunakan CommonJS untuk memuat bootstrap.js
require('../sass/app.scss'); // Menggunakan require untuk SCSS

const axios = require('axios');
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
