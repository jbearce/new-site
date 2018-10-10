// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    scripts(gulp, plugins, ran_tasks, on_error) {
        // task-specific plugins
        const ESLINT  = require("gulp-eslint");
        const WEBPACK = require("webpack-stream");

        const CHECK_IF_NEWER = (source = global.settings.paths.src + "/assets/scripts/**/*.js", file_to_check = global.settings.paths.dev + "/assets/scripts/modern.js") => {
            return new Promise((resolve) => {
                gulp.src(source)
                    // prevent breaking on error
                    .pipe(plugins.plumber({errorHandler: on_error}))
                    // check if source is newer than destination
                    .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(file_to_check)))
                    // if source files are newer, then compile
                    .on("data", () => {
                        resolve(true);
                    })
                    .on("end", () => {
                        resolve(false);
                    });
            });
        };

        const PROCESS_SCRIPTS = (source = global.settings.paths.src + "/assets/scripts/**/*.js", js_directory = global.settings.paths.dev + "/assets/scripts", webpack_config = {}) => {
            return gulp.src(source)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // lint all scripts
                .pipe(ESLINT())
                // print lint errors
                .pipe(ESLINT.format())
                // run webpack
                .pipe(WEBPACK(webpack_config))
                // generate a hash and add it to the file name
                .pipe(plugins.hash({template: "<%= name %>.<%= hash %><%= ext %>"}))
                // output scripts to compiled directory
                .pipe(gulp.dest(js_directory))
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
                // generate a hash manfiest
                .pipe(plugins.hash.manifest(".hashmanifest-scripts", {
                    deleteOld: true,
                    sourceDir: js_directory,
                }))
                // output hash manifest in root
                .pipe(gulp.dest("."));
        };

        // scripts task, lints, concatenates, & compresses JS
        return new Promise ((resolve) => {
            // set JS directory
            const JS_DIRECTORY = plugins.argv.dist ? global.settings.paths.dist + "/assets/scripts" : global.settings.paths.dev + "/assets/scripts";

            // set the source directory
            const SOURCE_DIRECTORY = global.settings.paths.src + "/assets/scripts";

            const WEBPACK_CONFIG = require("../webpack.config.js").config(plugins, SOURCE_DIRECTORY, JS_DIRECTORY);

            // get a hashed file name to check against
            const ALL_FILE_NAMES = plugins.fs.existsSync(JS_DIRECTORY) ? plugins.fs.readdirSync(JS_DIRECTORY) : false;
            let hashed_file_name = ALL_FILE_NAMES.length > 0 && ALL_FILE_NAMES.find((name) => {
                return name.match(new RegExp("[^.]+.[a-z0-9]{8}.js"));
            });

            if (!hashed_file_name) {
                hashed_file_name = "modern.js";
            }

            CHECK_IF_NEWER(SOURCE_DIRECTORY + "/**/*.js", JS_DIRECTORY + "/" + hashed_file_name).then((compile) => {
                if (compile === true) {
                    PROCESS_SCRIPTS(SOURCE_DIRECTORY + "/**/*.js", JS_DIRECTORY, WEBPACK_CONFIG);
                }

                resolve();
            });

        });
    }
};
