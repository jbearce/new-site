// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

module.exports = {
    // upload changed files
    upload(gulp, plugins, envs, ftp, ran_tasks, on_error) {
        // set FTP directory
        const ftp_directory = plugins.argv.dist ? envs.dist : envs.dev;

        // create SFTP connection
        const sftp_conn = plugins.sftp({
            host:       ftp.host,
            port:       ftp.port,
            username:   ftp.user,
            password:   ftp.pass,
            remotePath: ftp.path,
        });

        // create FTP connection
        const ftp_conn = plugins.ftp.create({
            host:   ftp.host,
            port:   ftp.port,
            secure: ftp.mode === "tls" ? true : false,
            user:   ftp.user,
            pass:   ftp.pass,
            path:   ftp.path,
        });

        return gulp.src(ftp_directory + "/**/*")
            // prevent breaking on error
            .pipe(plugins.plumber({errorHandler: on_error}))
            // check if files are newer
            .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer({dest: envs.src, extra: [ftp_directory + "/**/*"]})))
            // check if files are newer
            .pipe(plugins.gulpif(ftp.mode !== "sftp", ftp_conn.newer(ftp.path)))
            // upload changed files
            .pipe(plugins.gulpif(ftp.mode !== "sftp", ftp_conn.dest(ftp.path), sftp_conn))
            // prevent breaking on error
            .pipe(plugins.plumber({errorHandler: on_error}))
            // reload files
            .pipe(plugins.browser_sync.reload({stream: true}))
            // notify that task is complete, if not part of default or watch
            .pipe(plugins.notify({title: "Success!", message: "FTP task complete!", onLast: true}));
    }
};
