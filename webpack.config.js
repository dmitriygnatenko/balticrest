var Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    // Balticrest
    .addEntry('balticrest_main', './assets/balticrest/balticrest-main.js')
    .addEntry('balticrest_ie9', './assets/balticrest/balticrest-ie9.js')
    .addEntry('balticrest_map', './assets/balticrest/balticrest-map.js')

    // Admin
    .addEntry('admin', './assets/admin/admin.js')

    // https://symfony.com/doc/current/frontend.html#adding-more-features
    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
