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


mix.js('resources/js/app.js', 'public/js').version();
mix.sass('resources/sass/main.scss', 'public/css').version();
mix.sass('resources/sass/bek96.scss', 'public/css').version();

mix.webpackConfig({
    output: {
        chunkFilename: (mix.inProduction()) ? 'js/chunks/[name].js' : 'js/chunks/[name].[hash].js',
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js/'),
            '@modals': path.resolve(__dirname, 'resources/js/includes/modals/'),
            '@mixins': path.resolve(__dirname, 'resources/js/utils/mixins/'),
            '@inventory': path.resolve(__dirname, 'resources/js/includes/fields/inventory/'),
            '@selects': path.resolve(__dirname, 'resources/js/includes/fields/selects/'),
            '@inputs': path.resolve(__dirname, 'resources/js/includes/fields/inputs/')
        }
    }
});

mix.disableNotifications();


// mix.browserSync('dev.gocrm.uz');
