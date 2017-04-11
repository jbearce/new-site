// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

module.exports = function (plugins, bs) {
    // sync task, set up a browser_sync server, depends on config
    return plugins.browser_sync({
        proxy:  bs.proxy,
        port:   bs.port,
        open:   bs.open   === "true" ? true : (bs.open   === "false" ? false : bs.open),
        notify: bs.notify === "true" ? true : (bs.notify === "false" ? false : bs.notify),
    });
};
