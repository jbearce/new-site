// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

module.exports = {
    // upload changed files
    upload(gulp, plugins, ran_tasks, on_error) {
        // set upload directory
        const upload_directory = plugins.argv.dist ? global.settings.paths.dist : global.settings.paths.dev;

        // create SFTP connection
        const sftp_conn = plugins.sftp({
            host:       global.settings.remote.hostname,
            port:       global.settings.remote.port,
            username:   global.settings.remote.username,
            password:   global.settings.remote.password,
            remotePath: global.settings.remote.path,
        });

        // create FTP connection
        const ftp_conn = plugins.ftp.create({
            host:   global.settings.remote.hostname,
            port:   global.settings.remote.port,
            secure: global.settings.remote.mode === "tls" ? true : false,
            user:   global.settings.remote.username,
            pass:   global.settings.remote.password,
            path:   global.settings.remote.path,
        });

        return gulp.src(upload_directory + "/**/*")
            // prevent breaking on error
            .pipe(plugins.plumber({errorHandler: on_error}))
            // check if files are newer
            .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer({dest: global.settings.paths.src, extra: [upload_directory + "/**/*"]})))
            // check if files are newer
            .pipe(plugins.gulpif(global.settings.remote.mode !== "sftp", ftp_conn.newer(global.settings.remote.path)))
            // upload changed files
            .pipe(plugins.gulpif(global.settings.remote.mode !== "sftp", ftp_conn.dest(global.settings.remote.path), sftp_conn))
            // prevent breaking on error
            .pipe(plugins.plumber({errorHandler: on_error}))
            // reload files
            .pipe(plugins.browser_sync.reload({stream: true}))
            // notify that task is complete, if not part of default or watch
            .pipe(plugins.notify({title: "Success!", message: "Upload task complete!", onLast: true}));
    }
};
