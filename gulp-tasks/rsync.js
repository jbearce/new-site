// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    rsync(gulp, plugins, custom_notifier, ran_tasks, on_error) {
        // task-specific plugins
        const RSYNC = require("gulp-rsync");

        return new Promise((resolve) => {
            return gulp.src(global.settings.rsync.root)
                // prevent breaking on error
                .pipe(plugins.plumber({ errorHandler: on_error }))
                // notify that `root` needs set to `dist` if `dist` is passed and it's not equal to `globla.settings.paths.dist`
                .pipe(plugins.gulpif(plugins.argv.dist && global.settings.rsync.root !== global.settings.paths.dist, plugins.notify({
                    appIcon:  plugins.path.resolve("./src/assets/media/logo-favicon.png"),
                    title:    "Notice!",
                    message:  `\x1b[33mIt appears that --dist was passed, but the rsync root is set to '${global.settings.rsync.root}'.`,
                    notifier: custom_notifier,
                })))
                // check if files are newer
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer({ dest: global.settings.paths.src, extra: [`${global.settings.rsync.root}/**/*`] })))
                // rsync files
                .pipe(RSYNC(global.settings.rsync))
                // prevent breaking on error
                .pipe(plugins.plumber({ errorHandler: on_error }))
                // notify that task is complete
                .pipe(plugins.notify({
                    title:    "Success!",
                    message:  "Rsync task complete!",
                    notifier: custom_notifier,
                    onLast:   true,
                }))
                // consume the stream to prevent rvagg/through2#82
                .on("data", () => {
                    // do nothing
                })
                // resolve the promise
                .on("end", () => {
                    resolve();
                });
        });
    }
};
