// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

module.exports = {
    // upload changed files
    upload(GULP, PLUGINS, RAN_TASKS, ON_ERROR) {
        // set upload directory
        const UPLOAD_DIRECTORY = PLUGINS.argv.dist ? global.settings.paths.dist : global.settings.paths.dev;

        // create FTP connection
        const FTP_CONN = PLUGINS.ftp.create(global.settings.ftp);

        // create SFTP connection
        const SFTP_CONN = PLUGINS.sftp(global.settings.ftp);

        return GULP.src(UPLOAD_DIRECTORY + "/**/*")
            // prevent breaking on error
            .pipe(PLUGINS.plumber({errorHandler: ON_ERROR}))
            // check if files are newer
            .pipe(PLUGINS.gulpif(!PLUGINS.argv.dist, PLUGINS.newer({dest: global.settings.paths.src, extra: [UPLOAD_DIRECTORY + "/**/*"]})))
            // check if files are newer
            .pipe(PLUGINS.gulpif(global.settings.ftp.protocol !== "sftp", FTP_CONN.newer(global.settings.ftp.remotePath)))
            // upload changed files
            .pipe(PLUGINS.gulpif(global.settings.ftp.protocol !== "sftp", FTP_CONN.dest(global.settings.ftp.remotePath), SFTP_CONN))
            // prevent breaking on error
            .pipe(PLUGINS.plumber({errorHandler: ON_ERROR}))
            // reload files
            .pipe(PLUGINS.browser_sync.reload({stream: true}))
            // notify that task is complete
            .pipe(PLUGINS.notify({title: "Success!", message: "Upload task complete!", onLast: true}));
    }
};
