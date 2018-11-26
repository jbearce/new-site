// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    media(gulp, plugins, custom_notifier, ran_tasks, on_error) {
        // task-specific plugins
        const IMAGEMIN = require("gulp-imagemin");
        const PNGQUANT = require("imagemin-pngquant");

        // media task, compresses images, copies other media
        return new Promise ((resolve) => {
            // set media directory
            const MEDIA_DIRECTORY = plugins.argv.dist ? global.settings.paths.dist : global.settings.paths.dev;

            // copy fonts
            const COPY_FONTS = gulp.src(`${global.settings.paths.src}/assets/media/fonts/**/*.{otf,ttf,woff,woff2}`)
                // prevent breaking on error
                .pipe(plugins.plumber({ errorHandler: on_error }))
                // check if source is newer than destination
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(`${MEDIA_DIRECTORY}/assets/media/fonts`, { extra: [`${MEDIA_DIRECTORY}/assets/media/fonts/**/*.{otf,ttf,woff,woff2}`] })))
                // output to compiled directory
                .pipe(gulp.dest(`${MEDIA_DIRECTORY}/assets/media/fonts`));

            // process images
            const PROCESS_IMAGES = gulp.src(`${global.settings.paths.src}/**/*.{jpg,png,svg}`)
                // prevent breaking on error
                .pipe(plugins.plumber({ errorHandler: on_error }))
                // check if source is newer than destination
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(MEDIA_DIRECTORY)))
                // compress images
                .pipe(IMAGEMIN({
                    progressive: true,
                    svgoPlugins: [{ cleanupIDs: false, removeViewBox: false }],
                    use:         [PNGQUANT()],
                }))
                // output to compiled directory
                .pipe(gulp.dest(MEDIA_DIRECTORY));

            // merge both streams back in to one
            plugins.merge(COPY_FONTS, PROCESS_IMAGES)
                // prevent breaking on error
                .pipe(plugins.plumber({ errorHandler: on_error }))
                // notify that task is complete, if not part of default or watch
                .pipe(plugins.gulpif(plugins.argv._.includes("media"), plugins.notify({
                    appIcon:  plugins.path.resolve("./src/assets/media/logo-favicon.png"),
                    title:    "Success!",
                    message:  "Media task complete!",
                    notifier: custom_notifier,
                    onLast:   true,
                })))
                // push task to ran_tasks array
                .on("data", () => {
                    if (!ran_tasks.includes("media")) {
                        ran_tasks.push("media");
                    }
                })
                .on("end", () => {
                    resolve();
                });
        });
    }
};
