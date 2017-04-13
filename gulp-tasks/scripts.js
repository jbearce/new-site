// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

module.exports = function (gulp, plugins, ran_tasks, on_error) {
    // lint custom scripts
    const lint_scripts = function (js_directory, file_name = "modern.js", source = [global.settings.paths.src + "/assets/scripts/*.js", "!" + global.settings.paths.src + "/assets/scripts/vendor.*.js"]) {
        return gulp.src(source)
            // prevent breaking on error
            .pipe(plugins.plumber({errorHandler: on_error}))
            // check if source is newer than destination
            .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(js_directory + file_name)))
            // lint all non-vendor scripts
            .pipe(plugins.eslint())
            // print lint errors
            .pipe(plugins.eslint.format());
    };

    // process scripts
    const process_scripts = function (js_directory, file_name = "modern.js", source = [global.settings.paths.src + "/assets/scripts/vendor.*.js", global.settings.paths.src + "/assets/scripts/jquery.*.js", global.settings.paths.src + "/assets/scripts/*.js"]) {
        return gulp.src(source)
            // prevent breaking on error
            .pipe(plugins.plumber({errorHandler: on_error}))
            // check if source is newer than destination
            .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(js_directory + file_name)))
            // initialize sourcemap
            .pipe(plugins.sourcemaps.init())
            // concatenate to critical.js
            .pipe(plugins.concat(file_name))
            // transpile to es2015
            .pipe(plugins.babel({presets: ["es2015"]}))
            // uglify (if --dist is passed)
            .pipe(plugins.gulpif(plugins.argv.dist, plugins.uglify()))
            // write sourcemap (if --dist isn't passed)
            .pipe(plugins.gulpif(!plugins.argv.dist, plugins.sourcemaps.write()))
            // output to compiled directory
            .pipe(gulp.dest(js_directory));
    };

    // scripts task, lints, concatenates, & compresses JS
    return function () {
        // set JS directory
        const js_directory = plugins.argv.dist ? global.settings.paths.dist + "/assets/scripts/" : global.settings.paths.dev + "/assets/scripts";

        // clean directory if --dist is passed
        if (plugins.argv.dist) {
            plugins.del(js_directory + "/**/*");
        }

        // process all scripts
        const linted   = lint_scripts(js_directory, [global.settings.paths.src + "/assets/scripts/*.js", "!" + global.settings.paths.src + "/assets/scripts/vendor.*.js"]);
        const critical = process_scripts(js_directory, "critical.js", [global.settings.paths.src + "/assets/scripts/critical/loadCSS.js", global.settings.paths.src + "/assets/scripts/critical/loadCSS.cssrelpreload.js"]);
        const modern   = process_scripts(js_directory, "modern.js", [global.settings.paths.src + "/assets/scripts/vendor.*.js", global.settings.paths.src + "/assets/scripts/jquery.*.js", global.settings.paths.src + "/assets/scripts/*.js"]);
        const legacy   = process_scripts(js_directory, "legacy.js", [global.settings.paths.src + "/assets/scripts/legacy/**/*"]);

        // merge all four steams back in to one
        return plugins.merge(linted, critical, modern, legacy)
            // prevent breaking on error
            .pipe(plugins.plumber({errorHandler: on_error}))
            // reload files
            .pipe(plugins.browser_sync.reload({stream: true}))
            // notify that task is complete, if not part of default or watch
            .pipe(plugins.gulpif(gulp.seq.indexOf("scripts") > gulp.seq.indexOf("default"), plugins.notify({title: "Success!", message: "Scripts task complete!", onLast: true})))
            // push task to ran_tasks array
            .on("data", function () {
                if (ran_tasks.indexOf("scripts") < 0) {
                    ran_tasks.push("scripts");
                }
            });
    };
};
