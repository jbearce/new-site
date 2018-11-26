// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    sync(gulp, plugins, custom_notifier) {
        // retrieve the version number
        const BROWSERSYNC_VERSION = require("browser-sync/package.json").version;

        // set up function for adding custom headers
        const BROWSERSYNC_HEADER = (proxyReq) => {
            proxyReq.setHeader("X-BrowserSync-Port", global.settings.browsersync.port);
            proxyReq.setHeader("X-BrowserSync-Version",  BROWSERSYNC_VERSION);
        };

        if (global.settings.browsersync.proxy !== false) {
            // convert proxy to an object if it's a string
            if (typeof global.settings.browsersync.proxy !== "object") {
                global.settings.browsersync.proxy = {
                    "target": global.settings.browsersync.proxy,
                };
            }
    
            // // if proxyReq is undefined, define it as an empty array
            if (!("proxyReq" in global.settings.browsersync.proxy)) {
                global.settings.browsersync.proxy.proxyReq =  [];
            }
    
            // add the custom headers to the proxyReq array
            global.settings.browsersync.proxy.proxyReq.push(BROWSERSYNC_HEADER);
            
            // run BrowserSync
            return plugins.browser_sync(global.settings.browsersync);
        } else {
            return gulp.src(".config/.bsconfig")
                .pipe(plugins.notify({
                    appIcon:  plugins.path.resolve("./src/assets/media/logo-favicon.png"),
                    title:    "Error!",
                    message:  "\x1b[31mNo proxy is defined in .bsconfig! Try running gulp config --browsersync",
                    notifier: custom_notifier,
                }));
        }
    }
};
