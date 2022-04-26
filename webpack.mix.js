const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js').vue()
    .sass('resources/sass/app.scss', 'public/css/font.css')
    .postCss('resources/css/app.css', 'public/css/all.css', [
        require('postcss-import'),
        require('tailwindcss'),
    ])
    .combine([
        'public/css/font.css',
        'public/css/all.css',
    ], 'public/css/app.css')
    .alias({
        '@': 'resources/js',
    });

if (mix.inProduction()) {
    mix.version();
}