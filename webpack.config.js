var Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    // Balticrest

    /*
    .addEntry('balticrest_js_jquery', './assets/balticrest/jquery/js/jquery.min.js')

    .addEntry('balticrest_js_ie9', [
        './assets/balticrest/html5shiv/js/html5shiv.min.js',
        './assets/balticrest/respond/js/respond.min.js',
    ])

    .addEntry('balticrest_js_main', [
        './assets/balticrest/bootstrap/js/bootstrap.min.js',
        './assets/balticrest/balticrest/js/all_scr.js',
        './assets/balticrest/wow/js/wow.min.js',
        './assets/balticrest/jquery/js/jquery.parallax-0.2-min.js',
    ])

    .addEntry('balticrest_css_main', [
        './assets/balticrest/bootstrap/css/bootstrap.css',
        './assets/balticrest/balticrest/css/main.css',
        './assets/balticrest/balticrest/css/adaptive.css',
        './assets/balticrest/pushy/css/pushy.css',
        './assets/balticrest/animate/css/animate.css',
        './assets/balticrest/font-awesome/css/font-awesome.css',
    ])

    .addEntry('balticrest_js_map_index', [
        './assets/balticrest/balticrest/js/map_index.js',
    ])
    */

    // Admin

    .addEntry('admin', './assets/admin/admin.js')

    // https://symfony.com/doc/current/frontend.html#adding-more-features
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .enableSingleRuntimeChunk()
    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
