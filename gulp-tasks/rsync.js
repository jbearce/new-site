// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

module.exports = {
    rsync(gulp, plugins, ran_tasks, on_error) {
        // task-specific plugins
        const rsync = require("gulp-rsync");

        // set upload directory
        const rsync_directory = plugins.argv.dist ? global.settings.paths.dist : global.settings.paths.dev;

        return new Promise((resolve) => {
            return gulp.src(rsync_directory + "/**/*")
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // check if files are newer
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer({dest: global.settings.paths.src, extra: [rsync_directory + "/**/*"]})))
                // rsync files
                .pipe(rsync(global.settings.rsync))
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
