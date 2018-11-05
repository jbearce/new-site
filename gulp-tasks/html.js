// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    html(gulp, plugins, ran_tasks, on_error) {
        // task-specific plugins
        const TEMPLATE = require("gulp-template");

        // copy binaries
        const COPY_BINARIES = (html_directory, source = [global.settings.paths.src + "/**/*", "!" + global.settings.paths.src + "{/assets,/assets/**}"]) => {
            return gulp.src(source)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // check if source is newer than destination
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(html_directory)))
                // check if a file is a binary
                .pipe(plugins.is_binary())
                // skip file if it's not a binary
                .pipe(plugins.through.obj((file, enc, next) => {
                    if (!file.isBinary()) {
                        next();
                        return;
                    }

                    // go to next file
                    next(null, file);
                }))
                // output to compiled directory
                .pipe(gulp.dest(html_directory));
        };

        // copy composer
        const COPY_COMPOSER = (html_directory, source = [global.settings.paths.vendor + "/**/*"]) => {
            return gulp.src(source)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // check if source is newer than destination
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(html_directory)))
                // output to compiled directory
                .pipe(gulp.dest(html_directory + "/functions"));
        };

        // process HTML
        const PROCESS_HTML = (html_directory, source = [global.settings.paths.src + "/**/*", "!" + global.settings.paths.src + "{/assets,/assets/**}"]) => {
            // read data from package.json
            const NAME            = plugins.json.readFileSync("./package.json").name;
            const PWA_NAME        = plugins.json.readFileSync("./package.json").progressiveWebApp.name;
            const PWA_SHORT_NAME  = plugins.json.readFileSync("./package.json").progressiveWebApp.short_name;
            const PWA_THEME_COLOR = plugins.json.readFileSync("./package.json").progressiveWebApp.theme_color;
            const DESCRIPTION     = plugins.json.readFileSync("./package.json").description;
            const VERSION         = plugins.json.readFileSync("./package.json").version;
            const REPOSITORY      = plugins.json.readFileSync("./package.json").repository.url;
            const LICENSE         = plugins.json.readFileSync("./package.json").license;

            return gulp.src(source)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // check if source is newer than destination
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(html_directory)))
                // check if file is a binary
                .pipe(plugins.is_binary())
                // skip file if it's a binary
                .pipe(plugins.through.obj((file, enc, next) => {
                    if (file.isBinary()) {
                        next();
                        return;
                    }

                    // go to next file
                    next(null, file);
                }))
                // replace variables
                .pipe(TEMPLATE({
                    name: NAME,
                    pwa_name: PWA_NAME,
                    pwa_short_name: PWA_SHORT_NAME,
                    pwa_theme_color: PWA_THEME_COLOR,
                    description: DESCRIPTION,
                    version: VERSION,
                    repository: REPOSITORY,
                    license: LICENSE,
                }))
                // output to compiled directory
                .pipe(gulp.dest(html_directory));
        };

        // html task, copies binaries, converts includes & variables in HTML
        return new Promise ((resolve) => {
            // set HTML directory
            const HTML_DIRECTORY = plugins.argv.dist ? global.settings.paths.dist : global.settings.paths.dev;

            // process all non-asset files
            const BINARIES = COPY_BINARIES(HTML_DIRECTORY, [global.settings.paths.src + "/**/*", "!" + global.settings.paths.src + "{/assets,/assets/**}", "!" + global.settings.paths.src + "/**/*.{jpg,png,svg}"]);
            const COMPOSER = COPY_COMPOSER(HTML_DIRECTORY, [global.settings.paths.vendor + "/**/*"]);
            const HTML     = PROCESS_HTML(HTML_DIRECTORY, [global.settings.paths.src + "/**/*", "!" + global.settings.paths.src + "{/assets,/assets/**}", "!" + global.settings.paths.src + "/**/*.{jpg,png,svg}"]);

            // merge both steams back in to one
            plugins.merge(BINARIES, COMPOSER, HTML)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // notify that task is complete, if not part of default or watch
                .pipe(plugins.gulpif(gulp.seq.indexOf("html") > gulp.seq.indexOf("default"), plugins.notify({title: "Success!", message: "HTML task complete!", onLast: true})))
                // push task to ran_tasks array
                .on("data", () => {
                    if (ran_tasks.indexOf("html") < 0) {
                        ran_tasks.push("html");
                    }
                })
                .on("end", () => {
                    resolve();
                });
        });
    }
};
