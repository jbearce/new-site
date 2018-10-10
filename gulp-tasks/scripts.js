// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    scripts(gulp, plugins, ran_tasks, on_error) {
        // task-specific plugins
        const ESLINT  = require("gulp-eslint");
        const GLOB    = require("glob");
        const WEBPACK = require("webpack-stream");

        // scripts task, lints, concatenates, & compresses JS
        return new Promise ((resolve) => {
            // set JS directory
            const JS_DIRECTORY = plugins.argv.dist ? global.settings.paths.dist + "/assets/scripts" : global.settings.paths.dev + "/assets/scripts";

            // set the source directory
            const SOURCE_DIRECTORY = global.settings.paths.src + "/assets/scripts";

            const WEBPACK_CONFIG = {
                mode:   "development",
                entry:   {
                    "critical":       GLOB.sync(SOURCE_DIRECTORY + "/critical/**/*.js"),
                    "legacy":         GLOB.sync(SOURCE_DIRECTORY + "/legacy/**/*.js"),
                    "modern":         GLOB.sync(SOURCE_DIRECTORY + "/modern/**/*.js"),
                    "service-worker": GLOB.sync(SOURCE_DIRECTORY + "/service-worker/**/*.js"),
                },
                output: {
                    path:     plugins.path.resolve(__dirname, JS_DIRECTORY),
                    filename: "[name].js",
                },
            };

            // update webpack config for the current target destination and file name
            WEBPACK_CONFIG.mode = plugins.argv.dist ? "production" : WEBPACK_CONFIG.mode;

            const ALL_FILE_NAMES   = plugins.fs.existsSync(JS_DIRECTORY) ? plugins.fs.readdirSync(JS_DIRECTORY) : false;
            const HASHED_FILE_NAME = ALL_FILE_NAMES ? ALL_FILE_NAMES.find((name) => {
                return name.match(new RegExp(JS_DIRECTORY.split(".")[0] + ".[a-z0-9]{8}.js"));
            }) : "modern.js";

            gulp.src(SOURCE_DIRECTORY + "/**/*")
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // check if source is newer than destination
                .pipe(plugins.newer(JS_DIRECTORY + "/" + HASHED_FILE_NAME))
                // lint all scripts
                .pipe(ESLINT())
                // print lint errors
                .pipe(ESLINT.format())
                // run webpack
                .pipe(WEBPACK(WEBPACK_CONFIG))
                // generate a hash and add it to the file name
                .pipe(plugins.hash({template: "<%= name %>.<%= hash %><%= ext %>"}))
                // output scripts to compiled directory
                .pipe(gulp.dest(JS_DIRECTORY))
                // generate a hash manfiest
                .pipe(plugins.hash.manifest(".hashmanifest-scripts", {
                    deleteOld: true,
                    sourceDir: JS_DIRECTORY
                }))
                // output hash manifest in root
                .pipe(gulp.dest("."))
                // notify that task is complete, if not part of default or watch
                .pipe(plugins.gulpif(gulp.seq.indexOf("scripts") > gulp.seq.indexOf("default"), plugins.notify({
                    title:   "Success!",
                    message: "Scripts task complete!",
                    onLast:  true,
                })))
                // push task to ran_tasks array
                .on("data", () => {
                    if (ran_tasks.indexOf("scripts") < 0) {
                        ran_tasks.push("scripts");
                    }
                })
                // resolve the promise on end
                .on("end", () => {
                    resolve();
                });

        });
    }
};
