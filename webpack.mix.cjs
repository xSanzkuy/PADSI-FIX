const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/cartAnimation.js', 'public/js') // Jika Anda memerlukan file ini
   .sass('resources/sass/app.scss', 'public/css')
   .copy('node_modules/chart.js/dist/chart.min.js', 'public/js/chart.min.js')
   .copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/css/bootstrap.min.css')
   .setResourceRoot('/')
   .browserSync({
       proxy: '127.0.0.1:8000', // Ganti dengan URL lokal Anda jika berbeda
       files: [
           'app/**/*',
           'resources/views/**/*',
           'public/**/*',
           'resources/js/**/*',
           'resources/sass/**/*'
       ],
       open: false,
       notify: false
   })
   .options({
       processCssUrls: false
   })
   .version();
