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

const publicPath = '../../public/modules/chat/';

mix.setPublicPath(publicPath);

mix.js('Resources/assets/js/app.js', 'public/js')
    .sass('Resources/assets/sass/app.scss', 'public/css').vue();