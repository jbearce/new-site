// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

const GLOB = require("glob");

module.exports = {
    config(plugins, source_directory, js_directory) {
        const ENTRY = {};

        const SCRIPT_FOLDERS = plugins.fs.existsSync(source_directory) ? plugins.fs.readdirSync(source_directory) : false;

        // automatically build entry points based on folders in src/assets/scripts
        if (SCRIPT_FOLDERS && SCRIPT_FOLDERS.length > 0) {
            SCRIPT_FOLDERS.forEach((folder) => {
                ENTRY[folder] = GLOB.sync(`${source_directory}/${folder}/**/*.js`);
            });
        }

        return {
            devtool: plugins.argv.dist ? false : "source-map",
            entry:   ENTRY,
            mode:    plugins.argv.dist ? "production" : "development",
            module:  {
                rules: [
                    {
                        test: /\.js$/,
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
