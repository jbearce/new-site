// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

module.exports = {
    upload(gulp, plugins, ran_tasks, on_error) {
        // task-specific plugins
        const ftp  = require("vinyl-ftp");
        const sftp = require("gulp-sftp");

        // set upload directory
        const upload_directory = plugins.argv.dist ? global.settings.paths.dist : global.settings.paths.dev;

        // create FTP connection
        const ftp_conn = ftp.create(global.settings.ftp);

        // create SFTP connection
        const sftp_conn = sftp(global.settings.ftp);

        // styles task, compiles & prefixes SCSS
        return new Promise ((resolve) => {
            return gulp.src(upload_directory + "/**/*")
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // check if files are newer
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer({dest: global.settings.paths.src, extra: [upload_directory + "/**/*"]})))
                // check if files are newer
                .pipe(plugins.gulpif(global.settings.ftp.protocol !== "sftp", ftp_conn.newer(global.settings.ftp.remotePath)))
                // upload changed files
                .pipe(plugins.gulpif(global.settings.ftp.protocol !== "sftp", ftp_conn.dest(global.settings.ftp.remotePath), sftp_conn))
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // notify that task is complete
                .pipe(plugins.notify({title: "Success!", message: "Upload task complete!", onLast: true}))
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
