// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

module.exports = {
    media(GULP, PLUGINS, RAN_TASKS, ON_ERROR) {
        // compress images, copy other media
        const PROCESS_MEDIA = (MEDIA_DIRECTORY, source = global.settings.paths.src + "/assets/media/**/*") => {
            return GULP.src(source)
                // prevent breaking on error
                .pipe(PLUGINS.plumber({errorHandler: ON_ERROR}))
                // check if source is newer than destination
                .pipe(PLUGINS.gulpif(!PLUGINS.argv.dist, PLUGINS.newer(MEDIA_DIRECTORY)))
                // compress images
                .pipe(PLUGINS.imagemin({
                    progressive: true,
                    svgoPlugins: [{cleanupIDs: false, removeViewBox: false}],
                    use:         [PLUGINS.pngquant()]
                }))
                // output to compiled directory
                .pipe(GULP.dest(MEDIA_DIRECTORY));
        };

        // media task, compresses images, copies other media
        return new Promise ((resolve) => {
            // set media directory
            const MEDIA_DIRECTORY = PLUGINS.argv.dist ? global.settings.paths.dist + "/assets/media" : global.settings.paths.dev + "/assets/media";

            // set screenshot directory
            const SCREENSHOT_DIRECTORY = PLUGINS.argv.dist ? global.settings.paths.dist : global.settings.paths.dev;

            // clean directories if --dist is passed
            if (PLUGINS.argv.dist) {
                PLUGINS.del(MEDIA_DIRECTORY + "/**/*");
                PLUGINS.del(SCREENSHOT_DIRECTORY + "/screenshot.png");
            }

            // process all media
            const MEDIA      = PROCESS_MEDIA(MEDIA_DIRECTORY, global.settings.paths.src + "/assets/media/**/*");
            const SCREENSHOT = PROCESS_MEDIA(SCREENSHOT_DIRECTORY, global.settings.paths.src + "/screenshot.png");

            // merge both steams back in to one
            return PLUGINS.merge(MEDIA, SCREENSHOT)
                // prevent breaking on error
                .pipe(PLUGINS.plumber({errorHandler: ON_ERROR}))
                // reload files
                .pipe(PLUGINS.browser_sync.reload({stream: true}))
                // notify that task is complete, if not part of default or watch
                .pipe(PLUGINS.gulpif(GULP.seq.indexOf("media") > GULP.seq.indexOf("default"), PLUGINS.notify({title: "Success!", message: "Media task complete!", onLast: true})))
                // push task to RAN_TASKS array
                .on("data", () => {
                    if (RAN_TASKS.indexOf("media") < 0) {
                        RAN_TASKS.push("media");
                    }
                })
                .on("end", () => {
                    return resolve();
                });
        });
    }
};
