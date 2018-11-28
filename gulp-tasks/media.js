// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    media(gulp, plugins, customNotifier, ranTasks, onError) {
        // task-specific plugins
        const imagemin = require("gulp-imagemin");
        const pngquant = require("imagemin-pngquant");

        // media task, compresses images, copies other media
        return new Promise((resolve) => {
            // set media directory
            const MEDIA_DIRECTORY = plugins.argv.dist ? global.settings.paths.dist : global.settings.paths.dev;

            // copy fonts
            const COPY_FONTS = gulp.src(`${global.settings.paths.src}/assets/media/fonts/**/*.{otf,ttf,woff,woff2}`)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: onError}))
                // check if source is newer than destination
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(`${MEDIA_DIRECTORY}/assets/media/fonts`, {extra: [`${MEDIA_DIRECTORY}/assets/media/fonts/**/*.{otf,ttf,woff,woff2}`]})))
                // output to compiled directory
                .pipe(gulp.dest(`${MEDIA_DIRECTORY}/assets/media/fonts`));

            // process images
            const PROCESS_IMAGES = gulp.src(`${global.settings.paths.src}/**/*.{jpg,png,svg}`)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: onError}))
                // check if source is newer than destination
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(MEDIA_DIRECTORY)))
                // compress images
                .pipe(imagemin({
                    progressive: true,
                    svgoPlugins: [{cleanupIDs: false, removeViewBox: false}],
                    use:         [pngquant()],
                }))
                // output to compiled directory
                .pipe(gulp.dest(MEDIA_DIRECTORY));

            // merge both streams back in to one
            plugins.merge(COPY_FONTS, PROCESS_IMAGES)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: onError}))
                // notify that task is complete, if not part of default or watch
                .pipe(plugins.gulpif(plugins.argv._.includes("media"), plugins.notify({
                    appIcon:  plugins.path.resolve("./src/assets/media/logo-favicon.png"),
                    title:    "Success!",
                    message:  "Media task complete!",
                    notifier: process.env.BURNTTOAST === "true" ? customNotifier : false,
                    onLast:   true,
                })))
                // push task to ranTasks array
                .on("data", () => {
                    if (!ranTasks.includes("media")) {
                        ranTasks.push("media");
                    }
                })
                .on("end", () => {
                    resolve();
                });
        });
    },
};
