// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    styles(gulp, plugins, ran_tasks, on_error) {
        // task-specific plugins
        const POSTCSS   = require("gulp-postcss");
        const SASS      = require("gulp-sass");
        const STYLELINT = require("gulp-stylelint");

        // styles task, compiles & prefixes SCSS
        return new Promise ((resolve) => {
            // set CSS directory
            const CSS_DIRECTORY = plugins.argv.dist ? global.settings.paths.dist + "/assets/styles" : global.settings.paths.dev + "/assets/styles";

            const ALL_FILE_NAMES   = plugins.fs.existsSync(CSS_DIRECTORY) ? plugins.fs.readdirSync(CSS_DIRECTORY) : false;
            let hashed_file_name = ALL_FILE_NAMES.length > 0 && ALL_FILE_NAMES.find((name) => {
                return name.match(new RegExp("[^.]+.[a-z0-9]{8}.css"));
            });

            if (!hashed_file_name) {
                hashed_file_name = "modern.css";
            }

            // process styles
            gulp.src(global.settings.paths.src + "/assets/styles/**/*.scss")
                // prevent breaking on error
                .pipe(plugins.plumber({
                    errorHandler: on_error
                }))
                // check if source is newer than destination
                .pipe(plugins.newer(CSS_DIRECTORY + "/" + hashed_file_name))
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
                .pipe(plugins.gulpif(plugins.argv.dist, SASS({
                    includePaths: "./node_modules",
                    outputStyle:  "compressed",
                }), SASS({
                    includePaths: "./node_modules",
                })))
                // process post CSS stuff
                .pipe(POSTCSS([
                    require("pixrem"),
                    require("postcss-clearfix"),
                    require("postcss-easing-gradients"),
                    require("postcss-inline-svg"),
                    require("postcss-flexibility"),
                    require("postcss-responsive-type"),
                ]))
                // generate a hash and add it to the file name
                .pipe(plugins.hash({
                    template: "<%= name %>.<%= hash %><%= ext %>",
                }))
                // write sourcemap (if --dist isn't passed)
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.sourcemaps.write()))
                // output styles to compiled directory
                .pipe(gulp.dest(CSS_DIRECTORY))
                // notify that task is complete, if not part of default or watch
                .pipe(plugins.gulpif(gulp.seq.indexOf("styles") > gulp.seq.indexOf("default"), plugins.notify({
                    title:   "Success!",
                    message: "Styles task complete!",
                    onLast:  true,
                })))
                // push task to ran_tasks array
                .on("data", () => {
                    if (ran_tasks.indexOf("styles") < 0) {
                        ran_tasks.push("styles");
                    }
                })
                // generate a hash manfiest
                .pipe(plugins.hash.manifest("./.hashmanifest-styles", {
                    deleteOld: true,
                    sourceDir: CSS_DIRECTORY,
                }))
                // output hash manifest in root
                .pipe(gulp.dest("."))
                // resolve the promise
                .on("end", () => {
                    resolve();
                });
        });
    }
};
