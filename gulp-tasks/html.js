// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    html(gulp, plugins, ran_tasks, on_error) {
        // task-specific plugins
        const TEMPLATE = require("gulp-template");

        // copy binaries
        const COPY_BINARIES = (html_directory, source = [`${global.settings.paths.src}/**/*`, `!${global.settings.paths.src}{/assets,/assets/**}`]) => {
            return gulp.src(source)
                // prevent breaking on error
                .pipe(plugins.plumber({ errorHandler: on_error }))
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
        const COPY_COMPOSER = (html_directory, source = [`${global.settings.paths.vendor}/**/*`]) => {
            return gulp.src(source)
                // prevent breaking on error
                .pipe(plugins.plumber({ errorHandler: on_error }))
                // check if source is newer than destination
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(html_directory)))
                // output to compiled directory
                .pipe(gulp.dest(`${html_directory}/functions`));
        };

        // process HTML
        const PROCESS_HTML = (html_directory, source = [`${global.settings.paths.src}/**/*`, `!${global.settings.paths.src}/assets`, `!${global.settings.paths.src}/**/*.{jpg,png,svg}`]) => {
            // read data from package.json
            const JSON_DATA = plugins.json.readFileSync("package.json");

            return gulp.src(source)
                // prevent breaking on error
                .pipe(plugins.plumber({ errorHandler: on_error }))
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
                    name: JSON_DATA.name,
                    pwa_name: JSON_DATA.progressiveWebApp.name + (!plugins.argv.dist ? " - DEV" : ""),
                    pwa_short_name: JSON_DATA.progressiveWebApp.short_name,
                    pwa_theme_color: JSON_DATA.progressiveWebApp.theme_color,
                    description: JSON_DATA.description,
                    version: JSON_DATA.version,
                    repository: JSON_DATA.repository.url,
                    license: JSON_DATA.license,
                }))
                // output to compiled directory
                .pipe(gulp.dest(html_directory));
        };

        // html task, copies binaries, converts includes & variables in HTML
        return new Promise ((resolve) => {
            // set HTML directory
            const HTML_DIRECTORY = plugins.argv.dist ? global.settings.paths.dist : global.settings.paths.dev;

            // process all non-asset files
            const BINARIES = COPY_BINARIES(HTML_DIRECTORY, [`${global.settings.paths.src}/**/*`, `!${global.settings.paths.src}{/assets,/assets/**}`, `!${global.settings.paths.src}/**/*.{jpg,png,svg}`]);
            const COMPOSER = COPY_COMPOSER(HTML_DIRECTORY, [`${global.settings.paths.vendor}/**/*`]);
            const HTML     = PROCESS_HTML(HTML_DIRECTORY, [`${global.settings.paths.src}/**/*`, `!${global.settings.paths.src}{/assets,/assets/**}`, `!${global.settings.paths.src}/**/*.{jpg,png,svg}`]);

            // merge both steams back in to one
            plugins.merge(BINARIES, COMPOSER, HTML)
                // prevent breaking on error
                .pipe(plugins.plumber({ errorHandler: on_error }))
                // notify that task is complete, if not part of default or watch
                .pipe(plugins.gulpif(plugins.argv._.indexOf("html") > plugins.argv._.indexOf("default"), plugins.notify({
                    title:   "Success!",
                    message: "HTML task complete!",
                    onLast:  true,
                })))
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
