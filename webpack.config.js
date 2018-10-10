// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

const GLOB = require("glob");

module.exports = {
    config(plugins, source_directory, js_directory) {
        return {
            mode:   plugins.argv.dist ? "production" : "development",
            entry:  {
                "critical":       GLOB.sync(source_directory + "/critical/**/*.js"),
                "legacy":         GLOB.sync(source_directory + "/legacy/**/*.js"),
                "modern":         GLOB.sync(source_directory + "/modern/**/*.js"),
                "service-worker": GLOB.sync(source_directory + "/service-worker/**/*.js"),
            },
            output: {
                path:     plugins.path.resolve(__dirname, js_directory),
                filename: "[name].js",
            },
        };
    }
};
