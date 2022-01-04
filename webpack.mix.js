const { copy } = require('laravel-mix');
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

mix
.copyDirectory('resources/img','public/img')
.copyDirectory('resources/datepicker','public/datepicker')
.copy('resources/js/odometer.js', 'public/js/')
.copy('resources/js/odometer.min.js', 'public/js/')
.copy('resources/css/odometer-theme-car.css', 'public/css/')
.copy('resources/css/animation.css', 'public/css/')
.copy('resources/js/jquery.min.js','public/js/')
.js('resources/js/timer.js', 'public/js')
.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();
