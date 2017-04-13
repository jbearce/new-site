// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

module.exports = {
    // upload changed files
    upload(gulp, plugins, ran_tasks, on_error) {
        // set FTP directory
        const ftp_directory = plugins.argv.dist ? global.settings.paths.dist : global.settings.paths.dev;

        // create SFTP connection
        const sftp_conn = plugins.sftp({
            host:       global.settings.ftp.host,
            port:       global.settings.ftp.port,
            username:   global.settings.ftp.user,
            password:   global.settings.ftp.pass,
            remotePath: global.settings.ftp.path,
        });

        // create FTP connection
        const ftp_conn = plugins.ftp.create({
            host:   global.settings.ftp.host,
            port:   global.settings.ftp.port,
            secure: global.settings.ftp.mode === "tls" ? true : false,
            user:   global.settings.ftp.user,
            pass:   global.settings.ftp.pass,
            path:   global.settings.ftp.path,
        });

        return gulp.src(ftp_directory + "/**/*")
            // prevent breaking on error
            .pipe(plugins.plumber({errorHandler: on_error}))
            // check if files are newer
            .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer({dest: global.settings.paths.src, extra: [ftp_directory + "/**/*"]})))
            // check if files are newer
            .pipe(plugins.gulpif(global.settings.ftp.mode !== "sftp", ftp_conn.newer(global.settings.ftp.path)))
            // upload changed files
            .pipe(plugins.gulpif(global.settings.ftp.mode !== "sftp", ftp_conn.dest(global.settings.ftp.path), sftp_conn))
            // prevent breaking on error
            .pipe(plugins.plumber({errorHandler: on_error}))
            // reload files
            .pipe(plugins.browser_sync.reload({stream: true}))
            // notify that task is complete, if not part of default or watch
            .pipe(plugins.notify({title: "Success!", message: "FTP task complete!", onLast: true}));
    }
};
