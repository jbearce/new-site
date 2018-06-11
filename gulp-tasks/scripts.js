// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

module.exports = {
    scripts(gulp, plugins, ran_tasks, on_error) {
        // task-specific plugins
        const babel  = require("gulp-babel");
        const eslint = require("gulp-eslint");
        const uglify = require("gulp-uglify");

        // lint custom scripts
        const lint_scripts = (js_directory, file_name = "modern.js", source = [global.settings.paths.src + "/assets/scripts/**/*.js", "!" + global.settings.paths.src + "/assets/scripts/vendor/**/*"], extra =  [global.settings.paths.src + "/assets/scripts/**/*.js"]) => {
            return gulp.src(source)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // check if source is newer than destination
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(js_directory + "/" + file_name, extra)))
                // lint all non-vendor scripts
                .pipe(eslint())
                // print lint errors
                .pipe(eslint.format());
        };

        // process scripts
        const process_scripts = (js_directory, file_name = "modern.js", source = [global.settings.paths.src + "/assets/scripts/*.js"], extra =  [global.settings.paths.src + "/assets/scripts/**/*.js"]) => {
            return gulp.src(source)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // check if source is newer than destination
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(js_directory + "/" + file_name, extra)))
                // initialize sourcemap
                .pipe(plugins.sourcemaps.init())
                // replace variables
                .pipe(plugins.file_include({
                    prefix:   "// @@",
                    basepath: "@file",
                }))
                // transpile to es2015
                .pipe(babel({"presets": [["env", {modules: false}]]}))
                // uglify (if --dist is passed)
                .pipe(plugins.gulpif(plugins.argv.dist, uglify()))
                // write sourcemap (if --dist isn't passed)
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.sourcemaps.write()))
                // output to compiled directory
                .pipe(gulp.dest(js_directory));
        };

        // scripts task, lints, concatenates, & compresses JS
        return new Promise ((resolve) => {
            // set JS directory
            const js_directory = plugins.argv.dist ? global.settings.paths.dist + "/assets/scripts" : global.settings.paths.dev + "/assets/scripts";

            // clean directory if --dist is passed
            if (plugins.argv.dist) {
                plugins.del(js_directory + "/**/*");
            }

            // process all scripts
            const linted    = lint_scripts(js_directory);
            const processed = process_scripts(js_directory);

            // merge all five steams back in to one
            return plugins.merge(linted, processed)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // reload files
                .pipe(plugins.browser_sync.reload({stream: true}))
                // notify that task is complete, if not part of default or watch
                .pipe(plugins.gulpif(gulp.seq.indexOf("scripts") > gulp.seq.indexOf("default"), plugins.notify({title: "Success!", message: "Scripts task complete!", onLast: true})))
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
    }
};
