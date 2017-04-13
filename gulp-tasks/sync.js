// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

module.exports = function (plugins, browsersync) {
    // sync task, set up a browser_sync server, depends on config
    return function () {
        plugins.browser_sync({
            proxy:  browsersync.proxy,
            port:   browsersync.port,
            open:   browsersync.open   === "true" ? true : (browsersync.open   === "false" ? false : browsersync.open),
            notify: browsersync.notify === "true" ? true : (browsersync.notify === "false" ? false : browsersync.notify),
        });
    };
};
