// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

module.exports = {
    scripts(gulp, plugins, ran_tasks, on_error) {
        // task-specific plugins
        const eslint  = require("gulp-eslint");
        const webpack = require("webpack-stream");

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
                // run webpack
                .pipe(webpack({
                    mode: plugins.argv.dist ? "production" : "development",
                    output: {
                        filename: file_name,
                    }
                }))
                // output to compiled directory
                .pipe(gulp.dest(js_directory));
        };

        // scripts task, lints, concatenates, & compresses JS
        return new Promise ((resolve) => {
            // set JS directory
            const js_directory = plugins.argv.dist ? global.settings.paths.dist + "/assets/scripts" : global.settings.paths.dev + "/assets/scripts";

            // set the source directory
            const source_directory = global.settings.paths.src + "/assets/scripts";

            // clean directory if --dist is passed
            if (plugins.argv.dist) {
                plugins.del(js_directory + "/**/*");
            }

            // set up an empty merged stream
            const merged_streams = plugins.merge();
            // get the script folder list
            const script_folders = plugins.fs.readdirSync(source_directory);

            // @TODO figure out how to make this run synchronously
            script_folders.forEach((file) => {
                // ensure the read file is a folder
                if (plugins.fs.statSync(source_directory + "/" + file).isDirectory()) {
                    // lint all scripts, except for critical
                    if (file !== "critical") {
                        const linted = lint_scripts(js_directory, file + ".js", source_directory + "/" + file + "/**/*");
                        merged_streams.add(linted);
                    }

                    // process all scripts
                    const processed = process_scripts(js_directory, file + ".js", source_directory + "/" + file + "/**/*");
                    merged_streams.add(processed);
                }
            });

            // merge all five steams back in to one
            return merged_streams
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
