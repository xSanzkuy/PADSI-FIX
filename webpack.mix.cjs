const mix = require('laravel-mix');

// Mengatur build JS dan CSS
mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .copy('node_modules/chart.js/dist/chart.min.js', 'public/js/chart.min.js') // Copy Chart.js ke public
   .copy('node_modules/bootstrap/dist/css/bootstrap.min.css', 'public/css/bootstrap.min.css') // Copy Bootstrap CSS
   .setResourceRoot('/') // Untuk memastikan asset path sesuai
   .webpackConfig({
        module: {
            rules: [
                {
                    test: /\.m?js$/, // Mendukung file JS dan MJS (ES6 Modules)
                    exclude: /(node_modules|bower_components)/,
                    use: {
                        loader: 'babel-loader',
                        options: {
                            presets: ['@babel/preset-env'] // ES6 Compatibility
                        }
                    }
                }
            ]
        }
    })
   .options({
        processCssUrls: false // Mencegah Laravel Mix dari mengubah URL asset di CSS
   })
   .version(); // Cache Busting
