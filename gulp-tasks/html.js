// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    html(gulp, plugins, customNotifier, ranTasks, onError) {
        // task-specific plugins
        const template = require("gulp-template");

        // copy binaries
        const copyBinaries = (htmlDirectory, source = [`${global.settings.paths.src}/**/*`, `!${global.settings.paths.src}{/assets,/assets/**}`]) => {
            return gulp.src(source)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: onError}))
                // check if source is newer than destination
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(htmlDirectory)))
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
                .pipe(gulp.dest(htmlDirectory));
        };

        // copy composer
        const copyComposer = (htmlDirectory, source = [`${global.settings.paths.vendor}/**/*`]) => {
            return gulp.src(source)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: onError}))
                // check if source is newer than destination
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(htmlDirectory)))
                // output to compiled directory
                .pipe(gulp.dest(`${htmlDirectory}/functions`));
        };

        // process HTML
        const processHTML = (htmlDirectory, source = [`${global.settings.paths.src}/**/*`, `!${global.settings.paths.src}/assets`, `!${global.settings.paths.src}/**/*.{jpg,png,svg}`]) => {
            // read data from package.json
            const JSON_DATA = plugins.json.readFileSync("package.json");

            return gulp.src(source)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: onError}))
                // check if source is newer than destination
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(htmlDirectory)))
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
                .pipe(template({
                    name:            JSON_DATA.name,
                    pwa_name:        JSON_DATA.progressiveWebApp.name + (!plugins.argv.dist ? " - DEV" : ""),
                    pwa_short_name:  JSON_DATA.progressiveWebApp.short_name,
                    pwa_theme_color: JSON_DATA.progressiveWebApp.theme_color,
                    description:     JSON_DATA.description,
                    version:         JSON_DATA.version,
                    repository:      JSON_DATA.repository.url,
                    license:         JSON_DATA.license,
                }))
                // output to compiled directory
                .pipe(gulp.dest(htmlDirectory));
        };

        // html task, copies binaries, converts includes & variables in HTML
        return new Promise((resolve) => {
            // set HTML directory
            const HTML_DIRECTORY = plugins.argv.dist ? global.settings.paths.dist : global.settings.paths.dev;

            // process all non-asset files
            const BINARIES = copyBinaries(HTML_DIRECTORY, [`${global.settings.paths.src}/**/*`, `!${global.settings.paths.src}{/assets,/assets/**}`, `!${global.settings.paths.src}/**/*.{jpg,png,svg}`]);
            const COMPOSER = copyComposer(HTML_DIRECTORY, [`${global.settings.paths.vendor}/**/*`]);
            const HTML = processHTML(HTML_DIRECTORY, [`${global.settings.paths.src}/**/*`, `!${global.settings.paths.src}{/assets,/assets/**}`, `!${global.settings.paths.src}/**/*.{jpg,png,svg}`]);

            // merge both steams back in to one
            plugins.merge(BINARIES, COMPOSER, HTML)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: onError}))
                // notify that task is complete, if not part of default or watch
                .pipe(plugins.gulpif(plugins.argv._.includes("html"), plugins.notify({
                    appIcon:  plugins.path.resolve("./src/assets/media/logo-favicon.png"),
                    title:    "Success!",
                    message:  "HTML task complete!",
                    notifier: process.env.BURNTTOAST === "true" ? customNotifier : false,
                    onLast:   true,
                })))
                // push task to ranTasks array
                .on("data", () => {
                    if (!ranTasks.includes("html")) {
                        ranTasks.push("html");
                    }
                })
                .on("end", () => {
                    resolve();
                });
        });
    },
};
