// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

module.exports = {
    scripts(gulp, plugins, ran_tasks, on_error) {
        // task-specific plugins
        const ESLINT  = require("gulp-eslint");
        const WEBPACK = require("webpack-stream");

        // process scripts
        const PROCESS_SCRIPTS = (js_directory, destination_file_name = "modern.js", compare_file_name = "modern.js", source = [global.settings.paths.src + "/assets/scripts/*.js"]) => {
            return new Promise((resolve, reject) => {
                const WEBPACK_CONFIG = {
                    mode: "development",
                };

                // update webpack config for the current target destination and file name
                WEBPACK_CONFIG.mode   = plugins.argv.dist ? "production" : WEBPACK_CONFIG.mode;
                WEBPACK_CONFIG.output = {
                    filename: destination_file_name
                };

                const TASK = gulp.src(source)
                    // prevent breaking on error
                    .pipe(plugins.plumber({errorHandler: on_error}))
                    // check if source is newer than destination
                    .pipe(plugins.newer(js_directory + "/" + compare_file_name))
                    // lint all scripts
                    .pipe(ESLINT())
                    // print lint errors
                    .pipe(ESLINT.format())
                    // run webpack
                    .pipe(WEBPACK(WEBPACK_CONFIG))
                    // generate a hash and add it to the file name
                    .pipe(plugins.hash({template: "<%= name %>.<%= hash %><%= ext %>"}))
                    // output scripts to compiled directory
                    .pipe(gulp.dest(js_directory))
                    // generate a hash manfiest
                    .pipe(plugins.hash.manifest(".hashmanifest-scripts", {
                        deleteOld: true,
                        sourceDir: js_directory
                    }))
                    // output hash manifest in root
                    .pipe(gulp.dest("."))
                    // reject after errors
                    .on("error", () => {
                        return reject(TASK);
                    })
                    // return the task after completion
                    .on("end", () => {
                        return resolve(TASK);
                    });
            });
        };

        // scripts task, lints, concatenates, & compresses JS
        return new Promise ((resolve) => {
            // set JS directory
            const JS_DIRECTORY = plugins.argv.dist ? global.settings.paths.dist + "/assets/scripts" : global.settings.paths.dev + "/assets/scripts";

            // set the source directory
            const SOURCE_DIRECTORY = global.settings.paths.src + "/assets/scripts";

            // set up an empty merged stream
            const MERGED_STREAMS = plugins.merge();
            // get the script source folder list
            const SCRIPT_FOLDERS = plugins.fs.readdirSync(SOURCE_DIRECTORY);
            // get the script destination file list
            const SCRIPT_FILES   = plugins.fs.existsSync(JS_DIRECTORY) ? plugins.fs.readdirSync(JS_DIRECTORY) : false;

            // process all the script folders
            const PROCESS_SCRIPT_FOLDERS = () => {
                return new Promise((resolve) => {
                    const FOLDER_NAME = SCRIPT_FOLDERS.shift();
                    const FILE_NAME   = SCRIPT_FILES ? SCRIPT_FILES.find((name) => {
                        return name.match(new RegExp(FOLDER_NAME + ".[a-z0-9]{8}.js"));
                    }) : FOLDER_NAME + ".js";

                    // process all scripts, update the stream
                    return PROCESS_SCRIPTS(JS_DIRECTORY, FOLDER_NAME + ".js", FILE_NAME, SOURCE_DIRECTORY + "/" + FOLDER_NAME + "/**/*").then((processed) => {
                        MERGED_STREAMS.add(processed);
                        return resolve();
                    });
                }).then(() => SCRIPT_FOLDERS.length > 0 ? PROCESS_SCRIPT_FOLDERS() : resolve()); // loop again if foldres remain, otherwise resolve
            };

            return PROCESS_SCRIPT_FOLDERS().then(() => {
                // wrap up
                return MERGED_STREAMS
                    // prevent breaking on error
                    .pipe(plugins.plumber({
                        errorHandler: on_error,
                    }))
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
                    .on("end", () => {
                        return resolve();
                    });
            });

        });
    }
};
