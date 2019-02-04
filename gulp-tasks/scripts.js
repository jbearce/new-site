// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    scripts(gulp, plugins, custom_notifier, ran_tasks, on_error) {
        // task-specific plugins
        const ESLINT         = require("gulp-eslint");
        const WEBPACK        = require("webpack");
        const WEBPACK_STREAM = require("webpack-stream");

        const CHECK_IF_NEWER = (source = `${global.settings.paths.src}/assets/scripts`, folder_name = `${global.settings.paths.dev}/assets/scripts/`, master_files = []) => {
            /**
             * Collect promises
             */
            const COLLECTOR = [];

            /**
             * Read all files in the destination folder
             */
            const DEST_FILES = plugins.fs.existsSync(folder_name) ? plugins.fs.readdirSync(folder_name) : false;

            /**
             * If no destination files exist, immeidately return
             */
            if (!DEST_FILES || DEST_FILES.length <= 0) {
                return Promise.resolve(false);
            }

            /**
             * Check if each master file is newer
             */
            master_files.forEach((master_file) => {
                /**
                 * Find hashed file name
                 */
                const MATCHES = DEST_FILES.filter((dest_file) => {
                    return dest_file.match(new RegExp(`^${master_file.split(".")[0]}`));
                });

                /**
                 * Fall back to master file name if no matched file exists
                 */
                const MATCH = MATCHES.length > 0 ? MATCHES[0] : master_file;

                if (MATCH.length > 0) {
                    /**
                     * Store the check for later
                     */
                    COLLECTOR.push(new Promise((resolve) => {
                        /**
                         * Check if each matched file is old
                         */
                        gulp.src(`${source}/${master_file}/**/*.js`)
                            // prevent breaking on error
                            .pipe(plugins.plumber({ errorHandler: on_error }))
                            // check if source is newer than destination
                            .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(`${folder_name}/${MATCH}`)))
                            // if source files are newer, then mark the destination for cleaning
                            .on("data", () => {
                                resolve(MATCH);
                            }).on("end", () => {
                                resolve(false);
                            });
                    }));
                }
            });

            /**
             * Wait for all files to be checked
             */
            return Promise.all(COLLECTOR)
                .then((old_files) => {
                    /**
                     * Filter out any 'false' old files
                     */
                    old_files = old_files.filter(file_name => file_name !== false);

                    /**
                     * Delete the old files if they exist
                     */
                    if (old_files.length > 0) {
                        old_files.forEach((file) => {
                            plugins.del(`${folder_name}/${file}`);
                        });
                    }

                    /**
                     * Return the old files
                     */
                    return Promise.resolve(old_files);
                });
        };

        const PROCESS_SCRIPTS = (source = `${global.settings.paths.src}/assets/scripts/**/*.js`, js_directory = `${global.settings.paths.dev}/assets/scripts`, webpack_config = {}) => {
            return new Promise((resolve) => {
                gulp.src(source)
                    // prevent breaking on error
                    .pipe(plugins.plumber({ errorHandler: on_error }))
                    // lint all scripts
                    .pipe(ESLINT())
                    // print lint errors
                    .pipe(ESLINT.format())
                    // run webpack
                    .pipe(WEBPACK_STREAM(webpack_config, WEBPACK))
                    // generate a hash and add it to the file name, except service worker
                    .pipe(plugins.gulpif(file => file.basename !== "service-worker.js", plugins.hash({ template: "<%= name %>.<%= hash %><%= ext %>" })))
                    // output scripts to compiled directory
                    .pipe(gulp.dest(js_directory))
                    // notify that task is complete, if not part of default or watch
                    .pipe(plugins.gulpif(plugins.argv._.includes("scripts"), plugins.notify({
                        appIcon:  plugins.path.resolve("./src/assets/media/logo-favicon.png"),
                        title:    "Success!",
                        message:  "Scripts task complete!",
                        notifier: process.env.BURNTTOAST === "true" ? custom_notifier : false,
                        onLast:   true,
                    })))
                    // push task to ran_tasks array
                    .on("data", () => {
                        if (!ran_tasks.includes("scripts")) {
                            ran_tasks.push("scripts");
                        }
                    })
                    .on("end", () => {
                        resolve();
                    });
            });
        };

        // scripts task, lints, concatenates, & compresses JS
        return new Promise ((resolve) => {
            // set JS directory
            const JS_DIRECTORY = plugins.argv.dist ? `${global.settings.paths.dist}/assets/scripts` : `${global.settings.paths.dev}/assets/scripts`;

            // set the source directory
            const SOURCE_DIRECTORY = `${global.settings.paths.src}/assets/scripts`;

            // retreive the webpack config
            const WEBPACK_CONFIG = require("../webpack.config.js").config(plugins, SOURCE_DIRECTORY, JS_DIRECTORY);

            // read the contents of the source directory
            const MASTER_FILES = plugins.fs.existsSync(SOURCE_DIRECTORY) ? plugins.fs.readdirSync(SOURCE_DIRECTORY) : [];

            CHECK_IF_NEWER(SOURCE_DIRECTORY, JS_DIRECTORY, MASTER_FILES).then((old_files) => {
                /**
                 * Compile if any old files are found, or if no files exist
                 */
                if ((old_files.length > 0 || old_files === false) && MASTER_FILES.length > 0) {
                    /**
                     * Filter out unchanged files from the Webpack entry
                     */
                    if (old_files !== false) {
                        MASTER_FILES.forEach((master_file) => {
                            const CHANGED = old_files.filter((old_file) => {
                                return old_file.match(new RegExp(`^${master_file}`));
                            });

                            if (CHANGED.length <= 0) {
                                delete WEBPACK_CONFIG.entry[master_file];
                            }
                        });
                    }

                    PROCESS_SCRIPTS(`${SOURCE_DIRECTORY}/**/*.js`, JS_DIRECTORY, WEBPACK_CONFIG).then(() => {
                        resolve();
                    });
                } else {
                    resolve();
                }
            });

        });
    }
};
