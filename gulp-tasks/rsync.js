// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

module.exports = {
    // rsync changed files
    rsync(GULP, PLUGINS, RAN_TASKS, ON_ERROR) {
        // set upload directory
        const RSYNC_DIRECTORY = PLUGINS.argv.dist ? global.settings.paths.dist : global.settings.paths.dev;

        return GULP.src(RSYNC_DIRECTORY + "/**/*")
            // prevent breaking on error
            .pipe(PLUGINS.plumber({errorHandler: ON_ERROR}))
            // check if files are newer
            .pipe(PLUGINS.gulpif(!PLUGINS.argv.dist, PLUGINS.newer({dest: global.settings.paths.src, extra: [RSYNC_DIRECTORY + "/**/*"]})))
            // rsync files
            .pipe(PLUGINS.rsync(global.settings.rsync))
            // prevent breaking on error
            .pipe(PLUGINS.plumber({errorHandler: ON_ERROR}))
            // reload files
            .pipe(PLUGINS.browser_sync.reload({stream: true}))
            // notify that task is complete
            .pipe(PLUGINS.notify({title: "Success!", message: "Rsync task complete!", onLast: true}));
    }
};
