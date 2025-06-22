const Encore = require('@symfony/webpack-encore');

// Configure the runtime environment if not already configured
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // Directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // Public path used by the web server to access the output path
    .setPublicPath('/build')

    /*
     * ENTRY CONFIG
     */
    // JavaScript entries
    .addEntry('app', './assets/app.js')
    .addEntry('calendar', './assets/calendar.js')
    // CSS entry for Tailwind
    .addStyleEntry('app', './assets/styles/app.css')

    // Splitting and optimization
    .splitEntryChunks()
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     */
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())

    // Configure Babel for modern JS
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.38';
    })

    // Enable PostCSS (reads postcss.config.js)
    .enablePostCssLoader()
;

module.exports = Encore.getWebpackConfig();
