require('./bootstrap'); // Memuat bootstrap.js
require('../sass/app.scss'); // Memuat SCSS ke dalam proyek

const axios = require('axios');
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Contoh: Tambahkan event listener untuk interaksi sederhana
window.onload = function() {
    console.log('Aplikasi siap digunakan!');
};

