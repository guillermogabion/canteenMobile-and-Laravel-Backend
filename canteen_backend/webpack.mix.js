const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();
    mix.copy('node_modules/sweetalert2/dist/sweetalert2.min.css', 'public/css/sweetalert2.min.css');
    mix.copy('node_modules/sweetalert2/dist/sweetalert2.min.js', 'public/js/sweetalert2.min.js');
    mix.copy('node_modules/jquery.cookie/jquery.cookie.js', 'public/js/jquery.cookie.js');
    