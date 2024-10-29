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
   .options({
       processCssUrls: false,
   })
   .version();
