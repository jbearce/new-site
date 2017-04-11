// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

const gulp    = require("gulp");
const plugins = {
    // general stuff
    argv         : require("yargs").options({
        "d": {
            alias: "dist",
            type:  "boolean",
        },
        "e": {
            alias: "experimental",
            type:  "array",
        },
        "f": {
            alias: "ftp",
            type:  "boolean",
        },
        "s": {
            alias: "sync",
            type:  "boolean",
        },
    }).argv,
    del:          require("del"),
    fs:           require("fs"),
    gulpif:       require("gulp-if"),
    is_binary:    require("gulp-is-binary"),
    json:         require("json-file"),
    merge:        require("merge-stream"),
    newer:        require("gulp-newer"),
    notify:       require("gulp-notify"),
    plumber:      require("gulp-plumber"),
    prompt:       require("gulp-prompt"),
    remove_code:  require("gulp-remove-code"),
    run_sequence: require("run-sequence"),
    sourcemaps:   require("gulp-sourcemaps"),
    through:      require("through2"),
    watch:        require("gulp-watch"),

    // FTP stuff
    ftp:  require("vinyl-ftp"),
    sftp: require("gulp-sftp"),

    // browser-sync stuff
    browser_sync : require("browser-sync"),

    // CSS stuff
    autoprefixer: require("gulp-autoprefixer"),
    critical:     require("critical"),
    flexibility:  require("postcss-flexibility"),
    pixrem:       require("gulp-pixrem"),
    postcss:      require("gulp-postcss"),
    sass:         require("gulp-sass"),
    stylelint:    require("gulp-stylelint"),
    uncss:        require("gulp-uncss"),

    // HTML stuff
    file_include: require("gulp-file-include"),
    replace:      require("gulp-replace"),

    // JS stuff
    babel:  require("gulp-babel"),
    concat: require("gulp-concat"),
    eslint: require("gulp-eslint"),
    uglify: require("gulp-uglify"),

    // media stuff
    imagemin: require("gulp-imagemin"),
    pngquant: require("imagemin-pngquant"),
};

/* STOP! These settings should always be blank! */
/* To configure FTP credentials, run gulp ftp   */

const ftp = {
    host: "",
    port: "",
    mode: "",
    user: "",
    pass: "",
    path: "",
};

/* STOP! These settings should always be blank!              */
/* To configure BrowserSync settingss, run gulp watch --sync */
const bs = {
    proxy:  "",
    port:   "",
    open:   "",
    notify: "",
};

// set up environment paths
const envs = {
    src:  "./src",
    dev:  "./dev",
    dist: "./dist",
};

// store which tasks where ran
const ran_tasks = [];

// Error handling
const on_error = function (err) {
    plugins.notify.onError({
        title:    "Gulp",
        subtitle: "Error!",
        message:  "<%= error.message %>",
        sound:    "Beep",
    })(err);

    this.emit("end");
};

// import tasks
gulp.task("init", require("./gulp-tasks/init")(gulp, plugins, envs));
gulp.task("config", require("./gulp-tasks/config")(gulp, plugins, bs, ftp));

gulp.task("styles", require("./gulp-tasks/styles")(gulp, plugins, envs, ran_tasks, on_error));
gulp.task("scripts", require("./gulp-tasks/scripts")(gulp, plugins, envs, ran_tasks, on_error));
gulp.task("media", require("./gulp-tasks/media")(gulp, plugins, envs, ran_tasks, on_error));
gulp.task("html", require("./gulp-tasks/html")(gulp, plugins, envs, ran_tasks, on_error));

// sync task, set up a browser_sync server, depends on config
gulp.task("sync", ["config"], function () {
    return plugins.browser_sync({
        proxy:  bs.proxy,
        port:   bs.port,
        open:   bs.open   === "true" ? true : (bs.open   === "false" ? false : bs.open),
        notify: bs.notify === "true" ? true : (bs.notify === "false" ? false : bs.notify),
    });
});

// ftp task, upload to FTP environment, depends on config
gulp.task("ftp", ["config"], function () {
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

    // upload changed files
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
});

// default task, runs through everything but dist
gulp.task("default", ["media", "scripts", "styles", "html"], function () {
    // notify that task is complete
    gulp.src("gulpfile.js")
        .pipe(plugins.gulpif(ran_tasks.length, plugins.notify({title: "Success!", message: ran_tasks.length + " task" + (ran_tasks.length > 1 ? "s" : "") + " complete! [" + ran_tasks.join(", ") + "]", onLast: true})));

    // trigger FTP task if FTP flag is passed
    if (plugins.argv.ftp) {
        plugins.run_sequence("ftp");
    }

    // reset ran_tasks array
    ran_tasks.length = 0;
});

// watch task, runs through everything but dist, triggers when a file is saved
gulp.task("watch", function () {
    // set up a browser_sync server, if --sync is passed
    if (plugins.argv.sync) {
        plugins.run_sequence("sync");
    }

    // watch for any changes
    plugins.watch("./src/**/*", function () {
        // run through all tasks
        plugins.run_sequence("default");
    });
});
