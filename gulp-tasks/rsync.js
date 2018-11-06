// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    rsync(gulp, plugins, ran_tasks, on_error) {
        // task-specific plugins
        const RSYNC = require("gulp-rsync");

        // set upload directory
        const RSYNC_DIRECTORY = plugins.argv.dist ? global.settings.paths.dist : global.settings.paths.dev;

        return new Promise((resolve) => {
            return gulp.src(`${RSYNC_DIRECTORY}/**/*`)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // check if files are newer
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer({dest: global.settings.paths.src, extra: [`${RSYNC_DIRECTORY}/**/*`]})))
                // rsync files
                .pipe(RSYNC(global.settings.rsync))
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // notify that task is complete
                .pipe(plugins.notify({title: "Success!", message: "Rsync task complete!", onLast: true}))
                // consume the stream to prevent rvagg/through2#82
                .on("data", () => {
                    // do nothing
                })
                // resolve the promise
                .on("end", () => {
                    return resolve();
                });
        });
    }
};
