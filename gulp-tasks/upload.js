// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    upload(gulp, plugins, customNotifier, ranTasks, onError) {
        const PROTOCOL = global.settings.ftp.protocol;

        // task-specific plugins
        const ftp = PROTOCOL === "ftp" ? require("vinyl-ftp") : false;
        const sftp = PROTOCOL === "sftp" ? require("gulp-sftp") : false;

        // set upload directory
        const UPLOAD_DIRECTORY = plugins.argv.dist ? global.settings.paths.dist : global.settings.paths.dev;

        // create FTP connection
        const FTP_CONN = PROTOCOL === "ftp" ? ftp.create(global.settings.ftp) : false;

        // create SFTP connection
        const SFTP_CONN = PROTOCOL === "sftp" ? sftp(global.settings.ftp) : false;

        // styles task, compiles & prefixes SCSS
        return new Promise((resolve) => {
            gulp.src(`${UPLOAD_DIRECTORY}/**/*`)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: onError}))
                // check if files are newer
                .pipe(plugins.gulpif(PROTOCOL === "ftp", FTP_CONN.newer(global.settings.ftp.remotePath)))
                // upload changed files
                .pipe(plugins.gulpif(PROTOCOL === "ftp", FTP_CONN.dest(global.settings.ftp.remotePath), SFTP_CONN))
                // notify that task is complete
                .pipe(plugins.notify({
                    appIcon:  plugins.path.resolve("./src/assets/media/logo-favicon.png"),
                    title:    "Success!",
                    message:  "Upload task complete!",
                    notifier: process.env.BURNTTOAST === "true" ? customNotifier : false,
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
    },
};
