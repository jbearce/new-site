// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    styles(gulp, plugins, custom_notifier, ran_tasks, on_error) {
        // task-specific plugins
        const CSS_IMPORTER = require("node-sass-simple-css-importer");
        const POSTCSS      = require("gulp-postcss");
        const SASS         = require("gulp-sass");
        const STYLELINT    = require("gulp-stylelint");
        const TOUCH        = require("gulp-touch-fd");

        const CHECK_IF_NEWER = (source = `${global.settings.paths.src}/assets/styles/**/*.scss`, folder_name = `${global.settings.paths.dev}/assets/styles/`, master_files = []) => {
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
                const MATCH = DEST_FILES.filter((dest_file) => {
                    return dest_file.match(new RegExp(`^${master_file.split(".")[0]}`));
                });

                if (MATCH.length > 0) {
                    /**
                     * Store the check for later
                     */
                    COLLECTOR.push(new Promise((resolve) => {
                        /**
                         * Check if each matched file is old
                         */
                        gulp.src(source)
                            // prevent breaking on error
                            .pipe(plugins.plumber({ errorHandler: on_error }))
                            // check if source is newer than destination
                            .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(`${folder_name}/${MATCH[0]}`)))
                            // if source files are newer, then mark the destination for cleaning
                            .on("data", () => {
                                resolve(MATCH[0]);
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

        const PROCESS_STYLES = (source = `${global.settings.paths.src}/assets/styles/**/*.scss`, css_directory = `${global.settings.paths.dev}/assets/styles`) => {
            return new Promise((resolve) => {
                // process styles
                gulp.src(source)
                    // prevent breaking on error
                    .pipe(plugins.plumber({
                        errorHandler: on_error
                    }))
                    // lint
                    .pipe(STYLELINT({
                        debug: true,
                        failAfterError: true,
                        reporters: [
                            {
                                console: true,
                                formatter: "string",
                            },
                        ],
                    }))
                    // initialize sourcemap
                    .pipe(plugins.sourcemaps.init())
                    // compile SCSS (compress if --dist is passed)
                    .pipe(SASS({
                        importer: CSS_IMPORTER(),
                        includePaths: "node_modules",
                        outputStyle: plugins.argv.dist ? "compressed" : "nested",
                    }))
                    // process post CSS stuff
                    .pipe(POSTCSS([
                        require("pixrem"),
                        require("postcss-clearfix"),
                        require("postcss-easing-gradients"),
                        require("postcss-inline-svg"),
                        require("postcss-responsive-type"),
                    ]))
                    // generate a hash and add it to the file name
                    .pipe(plugins.hash({
                        template: "<%= name %>.<%= hash %><%= ext %>",
                    }))
                    // write sourcemap (if --dist isn't passed)
                    .pipe(plugins.gulpif(!plugins.argv.dist, plugins.sourcemaps.write()))
                    // output styles to compiled directory
                    .pipe(gulp.dest(css_directory))
                    // update the mtime to prevent gulpjs/gulp#1461
                    .pipe(TOUCH())
                    // notify that task is complete, if not part of default or watch
                    .pipe(plugins.gulpif(plugins.argv._.includes("styles"), plugins.notify({
                        appIcon:  plugins.path.resolve("./src/assets/media/logo-favicon.png"),
                        title:    "Success!",
                        message:  "Styles task complete!",
                        notifier: process.env.BURNTTOAST === "true" ? custom_notifier : false,
                        onLast:   true,
                    })))
                    // push task to ran_tasks array
                    .on("data", () => {
                        if (!ran_tasks.includes("styles")) {
                            ran_tasks.push("styles");
                        }
                    })
                    .on("end", () => {
                        resolve();
                    });
            });
        };

        // generate critical CSS
        const GENERATE_CRITICAL_CSS = (css_directory = `${global.settings.paths.dev}/assets/styles`) => {
            return new Promise((resolve) => {

                const CRITICAL = require("critical");
                const MKDIRP   = require("mkdirp");
                const MOMENT   = require("moment");

                const SITEMAP     = plugins.json.readFileSync("package.json").templates;
                const KEYS        = Object.keys(SITEMAP);
                const ESTIMATE    = MOMENT.duration((Object.keys(SITEMAP).length * 30) / 60, "m");
                const EST_MOMENT  = Math.floor(ESTIMATE.asHours()) + MOMENT.utc(ESTIMATE.asMilliseconds()).format("h:m:s");
                const EST_ARRAY   = EST_MOMENT.split(":");
                const ACCUMULATOR = [];

                console.log(`  Generating critical CSS, this may take up to ${EST_ARRAY[1]}m ${EST_ARRAY[2]}s, go get some ☕`);

                // create the "critical" directory
                MKDIRP(`${css_directory}/critical`);

                for (let i = 0, p = Promise.resolve(); i < KEYS.length; i++) {
                    ACCUMULATOR.push(p = p.then(() => new Promise(resolve =>
                        (() => {
                            console.log(`  \u001b[33m! \u001b[0mGenerating ${css_directory}/critical/${KEYS[i]}.css from ${SITEMAP[KEYS[i]]}`);

                            CRITICAL.generate({
                                base: `${css_directory}/critical`,
                                dest: `${KEYS[i]}.css`,
                                dimensions: [1920, 1080],
                                ignore: ["@font-face", "@import"],
                                minify: true,
                                src: `${SITEMAP[KEYS[i]]}?disable=critical_css`
                            }).then(() => {
                                console.log("  \u001b[32m✔\u001b[0m Success!");

                                resolve();
                            });
                        })()
                    )));
                }

                Promise.all(ACCUMULATOR).then(() => {
                    resolve(true);
                });
            });
        };

        // styles task, compiles & prefixes SCSS
        return new Promise ((resolve) => {
            // set CSS directory
            const CSS_DIRECTORY = plugins.argv.dist ? `${global.settings.paths.dist}/assets/styles` : `${global.settings.paths.dev}/assets/styles`;

            // set the source directory
            const SOURCE_DIRECTORY = `${global.settings.paths.src}/assets/styles`;

            // read the contents of the source directory
            const ALL_FILES =  plugins.fs.existsSync(SOURCE_DIRECTORY) ? plugins.fs.readdirSync(SOURCE_DIRECTORY) : [];

            // filter out everything exccept SCSS files
            const MASTER_FILES = ALL_FILES.filter((file_name) => {
                return file_name.match(/\.scss$/);
            });

            CHECK_IF_NEWER(`${SOURCE_DIRECTORY}/**/*.scss`, CSS_DIRECTORY, MASTER_FILES)
                .then((old_files) => {
                    if (plugins.argv.experimental && plugins.argv.experimental.includes("critical")) {
                        return GENERATE_CRITICAL_CSS(CSS_DIRECTORY).then(() => old_files);
                    } else {
                        return Promise.resolve(old_files);
                    }
                })
                .then((old_files) => {
                    /**
                     * Compile if any old files are found, or if no files exist
                     */
                    if ((old_files.length > 0 || old_files === false) && MASTER_FILES.length > 0) {
                        return PROCESS_STYLES(`${SOURCE_DIRECTORY}/**/*.scss`, CSS_DIRECTORY);
                    } else {
                        resolve();
                    }
                }).then(() => {
                    resolve();
                });
        });
    }
};
