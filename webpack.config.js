const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .addStyleEntry('forms', './assets/styles/scss/forms.scss')
    .enableSingleRuntimeChunk()
    .enableSassLoader()
    .enablePostCssLoader()
    .enableSourceMaps(!Encore.isProduction())

module.exports = Encore.getWebpackConfig();
