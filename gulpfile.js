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
let ftp_host = "";
let ftp_port = "";
let ftp_mode = "";
let ftp_user = "";
let ftp_pass = "";
let ftp_path = "";

/* STOP! These settings should always be blank!              */
/* To configure BrowserSync settingss, run gulp watch --sync */
let bs_proxy  = "";
let bs_port   = "";
let bs_open   = "";
let bs_notify = "";

// set up environment paths
const envs = {
    src:  "./src",
    dev:  "./dev",
    dist: "./dist"
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
gulp.task("styles", require("./gulp-tasks/styles")(gulp, plugins, envs, ran_tasks, on_error));
gulp.task("scripts", require("./gulp-tasks/scripts")(gulp, plugins, envs, ran_tasks, on_error));
gulp.task("media", require("./gulp-tasks/media")(gulp, plugins, envs, ran_tasks, on_error));
gulp.task("html", require("./gulp-tasks/html")(gulp, plugins, envs, ran_tasks, on_error));

// WIP: init task, initializes the project
gulp.task("init", function () {
    return gulp.src(envs.src + "/**/*")
        // check if a file is a binary
        .pipe(plugins.is_binary())
        // skip file if it's a binary
        .pipe(plugins.through.obj(function (file, enc, next) {
            if (file.isBinary()) {
                next();
                return;
            }

            // go to next file
            next(null, file);
        }))
        // remove login HTML code if --remove --login is passed
        .pipe(plugins.remove_code({no_login: plugins.argv.remove && plugins.argv.login ? true : false, commentStart: "<!--", commentEnd: "-->"}))
        // output to source directory
        .pipe(gulp.dest(envs.src));
});

// config task, generate configuration file for FTP & BrowserSync and prompt dev for input
gulp.task("config", function (cb) {
    // configure BrowserSync settings
    function configure_browsersync() {
        // read browsersync settings from config.json
        bs_proxy  = plugins.json.read("./config.json").get("browsersync.proxy");
        bs_port   = plugins.json.read("./config.json").get("browsersync.port");
        bs_open   = plugins.json.read("./config.json").get("browsersync.open");
        bs_notify = plugins.json.read("./config.json").get("browsersync.notify");

        if (plugins.argv.all || (gulp.seq.indexOf("config") < gulp.seq.indexOf("sync") || plugins.argv.sync) && (plugins.argv.config || bs_proxy === "" || bs_port === "" || bs_open === "" || bs_notify === "")) {
            // reconfigure settings in config.json if a field is empty or if --config is passed
            gulp.src("./config.json")
                .pipe(prompt.prompt([{
                    // prompt for proxy
                    type:    "input",
                    name:    "proxy",
                    message: "Browsersync proxy:",
                    default: bs_proxy === "" ? "localhost:8888" : bs_proxy,
                },
                {
                    // prompt for port
                    type:    "input",
                    name:    "port",
                    message: "Browsersync port:",
                    default: bs_port === "" ? "8080" : bs_port,
                },
                {
                    // prompt for how to open
                    type:    "input",
                    name:    "open",
                    message: "Browsersync open:",
                    default: bs_open === "" ? "external" : bs_open,
                },
                {
                    // prompt for whether to notify
                    type:    "input",
                    name:    "notify",
                    message: "Browsersync notify:",
                    default: bs_notify === "" ? "false" : bs_notify,
                }], function (res) {
                    // open config.json
                    const file = plugins.json.read("./config.json");

                    // update browsersync settings in config.json
                    file.set("browsersync.proxy",  res.proxy);
                    file.set("browsersync.port",   res.port);
                    file.set("browsersync.open",   res.open);
                    file.set("browsersync.notify", res.notify);

                    // write updated file contents
                    file.writeSync();

                    // read browsersync settings from config.json
                    bs_proxy  = res.proxy;
                    bs_port   = res.port;
                    bs_open   = res.open;
                    bs_notify = res.notify;

                    cb();
                }));
        } else {
            cb();
        }
    }

    // configure FTP credentials
    function configure_ftp() {
        // read FTP settingss from config.json
        if (!plugins.argv.dist) {
            ftp_host = plugins.json.read("./config.json").get("ftp.dev.host");
            ftp_port = plugins.json.read("./config.json").get("ftp.dev.port");
            ftp_mode = plugins.json.read("./config.json").get("ftp.dev.mode");
            ftp_user = plugins.json.read("./config.json").get("ftp.dev.user");
            ftp_pass = plugins.json.read("./config.json").get("ftp.dev.pass");
            ftp_path = plugins.json.read("./config.json").get("ftp.dev.path");
        } else {
            ftp_host = plugins.json.read("./config.json").get("ftp.dist.host");
            ftp_port = plugins.json.read("./config.json").get("ftp.dist.port");
            ftp_mode = plugins.json.read("./config.json").get("ftp.dist.mode");
            ftp_user = plugins.json.read("./config.json").get("ftp.dist.user");
            ftp_pass = plugins.json.read("./config.json").get("ftp.dist.pass");
            ftp_path = plugins.json.read("./config.json").get("ftp.dist.path");
        }

        if (plugins.argv.all || (gulp.seq.indexOf("config") < gulp.seq.indexOf("ftp") || plugins.argv.ftp) && (plugins.argv.config || ftp_host === "" || ftp_user === "" || ftp_pass === "" || ftp_path === "")) {
            // reconfigure settings in config.json if a field is empty or if --config is passed
            gulp.src("./config.json")
                .pipe(prompt.prompt([{
                    // prompt for host
                    type:    "input",
                    name:    "host",
                    message: "FTP hostname:",
                    default: ftp_host,
                },
                {
                    // prompt for port
                    type:    "input",
                    name:    "port",
                    message: "FTP port:",
                    default: ftp_port,
                },
                {
                    // prompt for mode
                    type:    "list",
                    name:    "mode",
                    message: "FTP mode:",
                    choices: ["ftp", "tls", "sftp"],
                    default: ftp_mode === "ftp" ? 0 : ftp_mode === "tls" ? 1 : ftp_mode === "sftp" ? 2 : 0,
                },
                {
                    // prompt for user
                    type:    "input",
                    name:    "user",
                    message: "FTP username:",
                    default: ftp_user,
                },
                {
                    // prompt for password
                    type:    "password",
                    name:    "pass",
                    message: "FTP password:",
                    default: ftp_pass,
                },
                {
                    // prompt for path
                    type:    "input",
                    name:    "path",
                    message: "FTP remote path:",
                    default: ftp_path,
                }], function (res) {
                    // open config.json
                    const file = plugins.json.read("./config.json");

                    // update ftp settings in config.json
                    if (!plugins.argv.dist) {
                        file.set("ftp.dev.host", res.host);
                        file.set("ftp.dev.port", res.port);
                        file.set("ftp.dev.mode", res.mode);
                        file.set("ftp.dev.user", res.user);
                        file.set("ftp.dev.pass", res.pass);
                        file.set("ftp.dev.path", res.path);
                    } else {
                        file.set("ftp.dist.host", res.host);
                        file.set("ftp.dist.port", res.port);
                        file.set("ftp.dist.mode", res.mode);
                        file.set("ftp.dist.user", res.user);
                        file.set("ftp.dist.pass", res.pass);
                        file.set("ftp.dist.path", res.path);
                    }

                    // write updated file contents
                    file.writeSync();

                    // read FTP settings from config.json
                    ftp_host = res.host;
                    ftp_port = res.port;
                    ftp_mode = res.mode;
                    ftp_user = res.user;
                    ftp_pass = res.pass;
                    ftp_path = res.path;

                    configure_browsersync();
                }));
        } else {
            configure_browsersync();
        }
    }

    // generate config.json and start other functions
    plugins.fs.stat("./config.json", function (err) {
        if (err !== null) {
            const json_data =
            `{
                "ftp": {
                    "dev": {
                        "host": "",
                        "port": "21",
                        "mode": "ftp",
                        "user": "",
                        "pass": "",
                        "path": ""
                    },
                    "dist": {
                        "host": "",
                        "port": "21",
                        "mode": "ftp",
                        "user": "",
                        "pass": "",
                        "path": ""
                    }
                },
                "browsersync": {
                    "proxy":  "",
                    "port":   "",
                    "open":   "",
                    "notify": ""
                }
            }`;

            plugins.fs.writeFile("./config.json", json_data, function () {
                configure_ftp(function () {
                    configure_browsersync();
                });
            });
        } else {
            configure_ftp(function () {
                configure_browsersync();
            });
        }
    });
});

// ftp task, upload to FTP environment, depends on config
gulp.task("ftp", ["config"], function () {
    // set FTP directory
    const ftp_directory = plugins.argv.dist ? envs.dist : envs.dev;

    // create SFTP connection
    const sftp_conn = plugins.sftp({
        host:       ftp_host,
        port:       ftp_port,
        username:   ftp_user,
        password:   ftp_pass,
        remotePath: ftp_path,
    });

    // create FTP connection
    const ftp_conn = plugins.ftp.create({
        host:   ftp_host,
        port:   ftp_port,
        secure: ftp_mode === "tls" ? true : false,
        user:   ftp_user,
        pass:   ftp_pass,
        path:   ftp_path,
    });

    // upload changed files
    return gulp.src(ftp_directory + "/**/*")
        // prevent breaking on error
        .pipe(plugins.plumber({errorHandler: on_error}))
        // check if files are newer
        .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer({dest: envs.src, extra: [ftp_directory + "/**/*"]})))
        // check if files are newer
        .pipe(plugins.gulpif(ftp_mode !== "sftp", ftp_conn.newer(ftp_path)))
        // upload changed files
        .pipe(plugins.gulpif(ftp_mode !== "sftp", ftp_conn.dest(ftp_path), sftp_conn))
        // prevent breaking on error
        .pipe(plugins.plumber({errorHandler: on_error}))
        // reload files
        .pipe(plugins.browser_sync.reload({stream: true}))
        // notify that task is complete, if not part of default or watch
        .pipe(plugins.notify({title: "Success!", message: "FTP task complete!", onLast: true}));
});

// sync task, set up a browser_sync server, depends on config
gulp.task("sync", ["config"], function () {
    plugins.browser_sync({
        proxy:  bs_proxy,
        port:   bs_port,
        open:   bs_open === "true" ? true : (bs_open === "false" ? false : bs_open),
        notify: bs_notify === "true" ? true : (bs_notify === "false" ? false : bs_notify),
    });
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
