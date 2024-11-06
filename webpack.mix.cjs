const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .copy('node_modules/chart.js/dist/chart.min.js', 'public/js/chart.min.js')
   .copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/css/bootstrap.min.css')
   .setResourceRoot('/')
   .webpackConfig({
       module: {
           rules: [
               {
                   test: /\.js$/, // Mendukung file JS
                   exclude: /(node_modules|bower_components)/,
                   use: {
                       loader: 'babel-loader',
                       options: {
                           presets: ['@babel/preset-env'], // ES6 Compatibility
                       }
                   }
               }
           ]
       }
   })
   .browserSync({
       proxy: '127.0.0.1:8000', // Ganti dengan URL lokal Anda jika berbeda
       files: [
           'app/**/*',                 // Memantau perubahan di folder app
           'resources/views/**/*',     // Memantau perubahan di file Blade
           'public/**/*',              // Memantau perubahan di file public
           'resources/js/**/*',        // Memantau perubahan di folder JS
           'resources/sass/**/*'       // Memantau perubahan di folder SASS
       ],
       open: false,                    // Set to true jika ingin browser otomatis terbuka saat watch
       notify: false                   // Hapus pesan notifikasi di browser
   })
   .options({
       processCssUrls: false,
   })
   .version();
