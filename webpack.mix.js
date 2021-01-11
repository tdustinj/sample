const mix = require("laravel-mix");

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

mix.sass("resources/assets/sass/pdf.scss", "public/css");
mix.sass("resources/assets/sass/mail.scss", "public/css");
mix.copyDirectory("resources/assets/images", "public/images");
