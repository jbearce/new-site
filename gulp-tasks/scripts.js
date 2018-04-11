// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

module.exports = {
    scripts(GULP, PLUGINS, RAN_TASKS, ON_ERROR) {
        // lint custom scripts
        const LINT_SCRIPTS = (JS_DIRECTORY, file_name = "modern.js", source = [global.settings.paths.src + "/assets/scripts/*.js", "!" + global.settings.paths.src + "/assets/scripts/vendor.*.js"]) => {
            return GULP.src(source)
                // prevent breaking on error
                .pipe(PLUGINS.plumber({errorHandler: ON_ERROR}))
                // check if source is newer than destination
                .pipe(PLUGINS.gulpif(!PLUGINS.argv.dist, PLUGINS.newer(JS_DIRECTORY + "/" + file_name)))
                // lint all non-vendor scripts
                .pipe(PLUGINS.eslint())
                // print lint errors
                .pipe(PLUGINS.eslint.format());
        };

        // process scripts
        const PROCESS_SCRIPTS = (JS_DIRECTORY, file_name = "modern.js", source = [global.settings.paths.src + "/assets/scripts/vendor.*.js", global.settings.paths.src + "/assets/scripts/jquery.*.js", global.settings.paths.src + "/assets/scripts/*.js"], transpile = false) => {
            return GULP.src(source)
                // prevent breaking on error
                .pipe(PLUGINS.plumber({errorHandler: ON_ERROR}))
                // check if source is newer than destination
                .pipe(PLUGINS.gulpif(!PLUGINS.argv.dist, PLUGINS.newer(JS_DIRECTORY + "/" + file_name)))
                // initialize sourcemap
                .pipe(PLUGINS.sourcemaps.init())
                // concatenate to critical.js
                .pipe(PLUGINS.concat(file_name))
                // transpile to es2015
                .pipe(PLUGINS.gulpif(transpile === true, PLUGINS.babel({"presets": [["env", {modules: false}]]})))
                // uglify (if --dist is passed)
                .pipe(PLUGINS.gulpif(PLUGINS.argv.dist, PLUGINS.uglify()))
                // write sourcemap (if --dist isn't passed)
                .pipe(PLUGINS.gulpif(!PLUGINS.argv.dist, PLUGINS.sourcemaps.write()))
                // output to compiled directory
                .pipe(GULP.dest(JS_DIRECTORY));
        };

        // scripts task, lints, concatenates, & compresses JS
        return new Promise ((resolve) => {
            // set JS directory
            const JS_DIRECTORY = PLUGINS.argv.dist ? global.settings.paths.dist + "/assets/scripts" : global.settings.paths.dev + "/assets/scripts";

            // clean directory if --dist is passed
            if (PLUGINS.argv.dist) {
                PLUGINS.del(JS_DIRECTORY + "/**/*");
            }

            // process all scripts
            const LINTED         = LINT_SCRIPTS(JS_DIRECTORY, "modern.js", [global.settings.paths.src + "/assets/scripts/*.js", "!" + global.settings.paths.src + "/assets/scripts/vendor.*.js"]);
            const CRITICAL       = PROCESS_SCRIPTS(JS_DIRECTORY, "critical.js", [global.settings.paths.src + "/assets/scripts/critical/loadCSS.js", global.settings.paths.src + "/assets/scripts/critical/loadCSS.cssrelpreload.js"]);
            const MODERN         = PROCESS_SCRIPTS(JS_DIRECTORY, "modern.js", [global.settings.paths.src + "/assets/scripts/vendor.*.js", global.settings.paths.src + "/assets/scripts/jquery.*.js", global.settings.paths.src + "/assets/scripts/*.js"], true);
            const LEGACY         = PROCESS_SCRIPTS(JS_DIRECTORY, "legacy.js", [global.settings.paths.src + "/assets/scripts/legacy/**/*"], true);
            const SERVICE_WORKER = PROCESS_SCRIPTS(JS_DIRECTORY, "service-worker.js", [global.settings.paths.src + "/assets/scripts/service-worker/vendor.*.js", global.settings.paths.src + "/assets/scripts/service-worker/*.js"], true);

            // merge all five steams back in to one
            return PLUGINS.merge(LINTED, CRITICAL, MODERN, LEGACY, SERVICE_WORKER)
                // prevent breaking on error
                .pipe(PLUGINS.plumber({errorHandler: ON_ERROR}))
                // reload files
                .pipe(PLUGINS.browser_sync.reload({stream: true}))
                // notify that task is complete, if not part of default or watch
                .pipe(PLUGINS.gulpif(GULP.seq.indexOf("scripts") > GULP.seq.indexOf("default"), PLUGINS.notify({title: "Success!", message: "Scripts task complete!", onLast: true})))
                // push task to RAN_TASKS array
                .on("data", () => {
                    if (RAN_TASKS.indexOf("scripts") < 0) {
                        RAN_TASKS.push("scripts");
                    }
                })
                .on("end", () => {
                    return resolve();
                });
        });
    }
};
