// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    upload(gulp, plugins, ran_tasks, on_error) {
        // task-specific plugins
        const FTP  = require("vinyl-ftp");
        const SFTP = require("gulp-sftp");

        // set upload directory
        const UPLOAD_DIRECTORY = plugins.argv.dist ? global.settings.paths.dist : global.settings.paths.dev;

        // create FTP connection
        const FTP_CONN = FTP.create(global.settings.ftp);

        // create SFTP connection
        const SFTP_CONN = SFTP(global.settings.ftp);

        // styles task, compiles & prefixes SCSS
        return new Promise ((resolve) => {
            gulp.src(`${UPLOAD_DIRECTORY}/**/*`)
                // prevent breaking on error
                .pipe(plugins.plumber({ errorHandler: on_error }))
                // check if files are newer
                .pipe(plugins.gulpif(global.settings.ftp.protocol !== "sftp", FTP_CONN.newer(global.settings.ftp.remotePath)))
                // upload changed files
                .pipe(plugins.gulpif(global.settings.ftp.protocol !== "sftp", FTP_CONN.dest(global.settings.ftp.remotePath), SFTP_CONN))
                // prevent breaking on error
                .pipe(plugins.plumber({ errorHandler: on_error }))
                // notify that task is complete
                .pipe(plugins.notify({
                    title:   "Success!",
                    message: "Upload task complete!",
                    onLast:  true,
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
