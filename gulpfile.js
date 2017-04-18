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

/* STOP! These settings should always be blank!              */
/* To configure FTP credentials, run gulp ftp                */
/* To configure BrowserSync settingss, run gulp watch --sync */

global.settings = {
    ftp: {
        hostname: "",
        port:     "",
        mode:     "",
        username: "",
        password: "",
        path:     "",
    },
    browsersync: {
        proxy:  "",
        port:   "",
        open:   "",
        notify: "",
    },
    paths: {
        src:  "./src",
        dev:  "./dev",
        dist: "./dist",
    }
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

// import custom modules
const init_module    = require("./gulp-tasks/init")(gulp, plugins);
const config_module  = require("./gulp-tasks/config");
const styles_module  = require("./gulp-tasks/styles")(gulp, plugins, ran_tasks, on_error);
const scripts_module = require("./gulp-tasks/scripts")(gulp, plugins, ran_tasks, on_error);
const media_module   = require("./gulp-tasks/media")(gulp, plugins, ran_tasks, on_error);
const html_module    = require("./gulp-tasks/html")(gulp, plugins, ran_tasks, on_error);
const upload_module  = require("./gulp-tasks/upload");
const sync_module    = require("./gulp-tasks/sync");

// configuration tasks
gulp.task("init", init_module);
gulp.task("config", function () {
    return config_module.config(gulp, plugins);
});

// primary tasks
gulp.task("styles", styles_module);
gulp.task("scripts", scripts_module);
gulp.task("media", media_module);
gulp.task("html", html_module);

// secondary tasks
gulp.task("ftp", function () {
    return config_module.config(gulp, plugins).then(function () {
        return upload_module.upload(gulp, plugins, ran_tasks, on_error);
    });
});
gulp.task("sync", function () {
    return config_module.config(gulp, plugins).then(function () {
        return sync_module.sync(plugins, global.settings.browsersync);
    });
});

// default task, runs through all primary tasks
gulp.task("default", ["media", "scripts", "styles", "html"], function () {
    // notify that task is complete
    gulp.src("gulpfile.js")
        .pipe(plugins.gulpif(ran_tasks.length, plugins.notify({title: "Success!", message: ran_tasks.length + " task" + (ran_tasks.length > 1 ? "s" : "") + " complete! [" + ran_tasks.join(", ") + "]", onLast: true})));

    // trigger FTP task if FTP flag is passed
    if (plugins.argv.ftp) {
        config_module.config(gulp, plugins).then(function () {
            return upload_module.upload(gulp, plugins, ran_tasks, on_error);
        });
    }

    // reset ran_tasks array
    ran_tasks.length = 0;

    // end the task
    return;
});

// watch task, runs through all primary tasks, triggers when a file is saved
gulp.task("watch", function () {
    // set up a browser_sync server, if --sync is passed
    if (plugins.argv.sync) {
        config_module.config(gulp, plugins).then(function () {
            sync_module.sync(plugins, global.settings.browsersync);
        });
    }

    // watch for any changes
    plugins.watch("./src/**/*", function () {
        // run through all tasks
        plugins.run_sequence("default");
    });
});
