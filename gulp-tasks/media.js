// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    media(gulp, plugins, ran_tasks, on_error) {
        // task-specific plugins
        const imagemin = require("gulp-imagemin");
        const pngquant = require("imagemin-pngquant");

        // media task, compresses images, copies other media
        return new Promise ((resolve) => {
            // set media directory
            const media_directory = plugins.argv.dist ? global.settings.paths.dist : global.settings.paths.dev;

            // merge both steams back in to one
            gulp.src(global.settings.paths.src + "/**/*.{jpg,png,svg}")
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // check if source is newer than destination
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(media_directory)))
                // compress images
                .pipe(imagemin({
                    progressive: true,
                    svgoPlugins: [{cleanupIDs: false, removeViewBox: false}],
                    use:         [pngquant()],
                }))
                // output to compiled directory
                .pipe(gulp.dest(media_directory))
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // notify that task is complete, if not part of default or watch
                .pipe(plugins.gulpif(gulp.seq.indexOf("media") > gulp.seq.indexOf("default"), plugins.notify({title: "Success!", message: "Media task complete!", onLast: true})))
                // push task to ran_tasks array
                .on("data", () => {
                    if (ran_tasks.indexOf("media") < 0) {
                        ran_tasks.push("media");
                    }
                })
                .on("end", () => {
                    resolve();
                });
        });
    }
};
