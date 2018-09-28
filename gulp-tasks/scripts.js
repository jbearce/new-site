// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

module.exports = {
    scripts(gulp, plugins, ran_tasks, on_error) {
        // task-specific plugins
        const eslint  = require("gulp-eslint");
        const webpack = require("webpack-stream");

        // lint custom scripts
        const lint_scripts = (js_directory, compare_file_name = "modern.js", source = [global.settings.paths.src + "/assets/scripts/**/*.js"]) => {
            return gulp.src(source)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // check if source is newer than destination
                .pipe(plugins.newer(js_directory + "/" + compare_file_name))
                // lint all non-vendor scripts
                .pipe(eslint())
                // print lint errors
                .pipe(eslint.format());
        };

        // process scripts
        const process_scripts = (js_directory, destination_file_name = "modern.js", compare_file_name = "modern.js", source = [global.settings.paths.src + "/assets/scripts/*.js"]) => {
            return new Promise((resolve, reject) => {
                const webpack_config = {
                    mode: "development",
                };

                // update webpack config for the current target destination and file name
                webpack_config.mode   = plugins.argv.dist ? "production" : webpack_config.mode;
                webpack_config.output = {filename: destination_file_name};

                const TASK = gulp.src(source)
                    // prevent breaking on error
                    .pipe(plugins.plumber({errorHandler: on_error}))
                    // check if source is newer than destination
                    .pipe(plugins.newer(js_directory + "/" + compare_file_name))
                    // run webpack
                    .pipe(webpack(webpack_config))
                    // generate a hash and add it to the file name
                    .pipe(plugins.hash({template: "<%= name %>.<%= hash %><%= ext %>"}))
                    // output scripts to compiled directory
                    .pipe(gulp.dest(js_directory))
                    // generate a hash manfiest
                    .pipe(plugins.hash.manifest(".hashmanifest-scripts", {deleteOld: true, sourceDir: js_directory}))
                    // output hash manifest in root
                    .pipe(gulp.dest("."))
                    // reject after errors
                    .on("error", () => {
                        reject(TASK);
                    })
                    // return the task after completion
                    .on("end", () => {
                        resolve(TASK);
                    });
            });
        };

        // scripts task, lints, concatenates, & compresses JS
        return new Promise ((resolve) => {
            // set JS directory
            const js_directory = plugins.argv.dist ? global.settings.paths.dist + "/assets/scripts" : global.settings.paths.dev + "/assets/scripts";

            // set the source directory
            const source_directory = global.settings.paths.src + "/assets/scripts";

            // set up an empty merged stream
            const merged_streams = plugins.merge();
            // get the script source folder list
            const script_folders = plugins.fs.readdirSync(source_directory);
            // get the script destination file list
            const script_files   = plugins.fs.existsSync(js_directory) ? plugins.fs.readdirSync(js_directory) : false;

            // process all the script folders
            const process_script_folders = () => {
                return new Promise((resolve) => {
                    const folder_name = script_folders.shift();
                    const file_name   = script_files ? script_files.find((name) => {
                        return name.match(new RegExp(folder_name + ".[a-z0-9]{8}.js"));
                    }) : folder_name + ".js";

                    // lint all scripts, except for critical, update the stream
                    if (folder_name !== "critical") {
                        const linted = lint_scripts(js_directory, file_name, source_directory + "/" + folder_name + "/**/*");
                        merged_streams.add(linted);
                    }

                    // process all scripts, update the stream
                    process_scripts(js_directory, folder_name + ".js", file_name, source_directory + "/" + folder_name + "/**/*").then((processed) => {
                        merged_streams.add(processed);
                        resolve();
                    });
                }).then(() => script_folders.length > 0 ? process_script_folders() : resolve()); // loop again if foldres remain, otherwise resolve
            };

            return process_script_folders().then(() => {
                // merge all five steams back in to one
                return merged_streams
                    // prevent breaking on error
                    .pipe(plugins.plumber({errorHandler: on_error}))
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

        });
    }
};
