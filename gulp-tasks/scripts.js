// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    scripts(gulp, plugins, customNotifier, ranTasks, onError) {
        // task-specific plugins
        const eslint = require("gulp-eslint");
        const webpack = require("webpack");
        const webpackStream = require("webpack-stream");

        const checkIfNewer = (source = `${global.settings.paths.src}/assets/scripts/**/*.js`, folderName = `${global.settings.paths.dev}/assets/scripts/`, fileName = "modern.js") => {
            let clean = false;

            return new Promise((resolve) => {
                gulp.src(source)
                    // prevent breaking on error
                    .pipe(plugins.plumber({errorHandler: onError}))
                    // check if source is newer than destination
                    .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(`${folderName}/${fileName}`)))
                    // if source files are newer, then mark the destination for cleaning
                    .on("data", () => {
                        clean = true;
                    })
                    // clean the directory if necessary and resolve the promsie
                    .on("end", () => {
                        if (clean) {
                            // delete the folder, becuase it's being replaced
                            plugins.del(folderName).then(() => {
                                // resolve the promise, compile
                                resolve(true);
                            });
                        } else {
                            // resolve the promise, don't compile
                            resolve(false);
                        }
                    });
            });
        };

        const processScripts = (source = `${global.settings.paths.src}/assets/scripts/**/*.js`, js_directory = `${global.settings.paths.dev}/assets/scripts`, webpack_config = {}) => {
            return new Promise((resolve) => {
                gulp.src(source)
                    // prevent breaking on error
                    .pipe(plugins.plumber({errorHandler: onError}))
                    // lint all scripts
                    .pipe(eslint())
                    // print lint errors
                    .pipe(eslint.format())
                    // run webpack
                    .pipe(webpackStream(webpack_config, webpack))
                    // generate a hash and add it to the file name, except service worker
                    .pipe(plugins.gulpif((file) => file.basename !== "service-worker.js", plugins.hash({template: "<%= name %>.<%= hash %><%= ext %>"})))
                    // output scripts to compiled directory
                    .pipe(gulp.dest(js_directory))
                    // notify that task is complete, if not part of default or watch
                    .pipe(plugins.gulpif(plugins.argv._.includes("scripts"), plugins.notify({
                        appIcon:  plugins.path.resolve("./src/assets/media/logo-favicon.png"),
                        title:    "Success!",
                        message:  "Scripts task complete!",
                        notifier: process.env.BURNTTOAST === "true" ? customNotifier : false,
                        onLast:   true,
                    })))
                    // push task to ranTasks array
                    .on("data", () => {
                        if (!ranTasks.includes("scripts")) {
                            ranTasks.push("scripts");
                        }
                    })
                    .on("end", () => {
                        resolve();
                    });
            });
        };

        // scripts task, lints, concatenates, & compresses JS
        return new Promise((resolve) => {
            // set JS directory
            const JS_DIRECTORY = plugins.argv.dist ? `${global.settings.paths.dist}/assets/scripts` : `${global.settings.paths.dev}/assets/scripts`;

            // set the source directory
            const SOURCE_DIRECTORY = `${global.settings.paths.src}/assets/scripts`;

            const WEBPACK_CONFIG = require("../webpack.config.js").config(plugins, SOURCE_DIRECTORY, JS_DIRECTORY);

            // get a hashed file name to check against
            const ALL_FILE_NAMES = plugins.fs.existsSync(JS_DIRECTORY) ? plugins.fs.readdirSync(JS_DIRECTORY) : false;
            let hashedFileName = ALL_FILE_NAMES.length > 0 && ALL_FILE_NAMES.find((name) => {
                return name.match(new RegExp("[^.]+.[a-z0-9]{8}.js"));
            });

            if (!hashedFileName) {
                hashedFileName = "modern.js";
            }

            checkIfNewer(`${SOURCE_DIRECTORY}/**/*.js`, JS_DIRECTORY, hashedFileName).then((compile) => {
                if (compile === true) {
                    processScripts(`${SOURCE_DIRECTORY}/**/*.js`, JS_DIRECTORY, WEBPACK_CONFIG).then(() => {
                        resolve();
                    });
                } else {
                    resolve();
                }
            });
        });
    },
};
