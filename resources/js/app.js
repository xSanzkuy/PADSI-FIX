// Menggunakan require untuk memuat JavaScript dan CSS
require('./bootstrap');
require('../sass/app.scss');
const Toastify = require('toastify-js');
require("toastify-js/src/toastify.css");

const axios = require('axios');
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Contoh: Tambahkan event listener untuk interaksi sederhana
window.onload = function() {
    console.log('Aplikasi siap digunakan!');
};
