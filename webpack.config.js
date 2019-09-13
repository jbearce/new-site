// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

const GLOB = require("glob");
const PATH = require("path");

module.exports = {
    config(plugins, source_directory, js_directory) {
        const ENTRY = {};

        const FILES = GLOB.sync(`${source_directory}/*.js`);

        if (FILES.length > 0) {
            FILES.forEach((file) => {
                ENTRY[PATH.basename(file, ".js")] = file;
            });
        }

        return {
            devtool: plugins.argv.dist ? false : "source-map",
            entry:   ENTRY,
            mode:    plugins.argv.dist ? "production" : "development",
            module:  {
                rules: [
                    {
                        exclude: /workbox|service-worker/,
                        test: /\.m?js$/,
                        use: {
                            loader: "babel-loader",
                            options: {
                                presets: ["@babel/preset-env"],
                            },
                        },
                    },
                ],
            },
            output:  {
                path:     plugins.path.resolve(__dirname, js_directory),
                filename: (DATA) => {
                    return DATA.chunk.name !== "service-worker" ? "[name].[chunkhash:8].js" : "[name].js";
                },
            },
        };
    }
};
