// JavaScript Document

// Scripts written by init_author_name @ init_author_company

module.exports = {
    // rsync changed files
    rsync(gulp, plugins, ran_tasks, on_error) {
        // set upload directory
        const rsync_directory = plugins.argv.dist ? global.settings.paths.dist : global.settings.paths.dev;

        return gulp.src(rsync_directory + "/**/*")
            // prevent breaking on error
            .pipe(plugins.plumber({errorHandler: on_error}))
            // check if files are newer
            .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer({dest: global.settings.paths.src, extra: [rsync_directory + "/**/*"]})))
            // rsync files
            .pipe(plugins.rsync({
                root: global.settings.rsync.root,
                hostname: global.settings.rsync.hostname,
                username: global.settings.rsync.username,
                destination: global.settings.rsync.destination,
                archive: global.settings.rsync.archive,
                silent: global.settings.rsync.silent,
                compress: global.settings.rsync.root.compress
            }))
            // prevent breaking on error
            .pipe(plugins.plumber({errorHandler: on_error}))
            // reload files
            .pipe(plugins.browser_sync.reload({stream: true}))
            // notify that task is complete
            .pipe(plugins.notify({title: "Success!", message: "Rsync task complete!", onLast: true}));
    }
};
