// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

const GULP = require("gulp");

const PLUGINS = {
    // general stuff
    argv: require("yargs").options({
        "d": {
            alias: "dist",
            type:  "boolean",
        },
        "e": {
            alias: "endpoint",
            type:  "string",
        },
        "r": {
            alias: "rsync",
            type:  "boolean",
        },
        "s": {
            alias: "sync",
            type:  "boolean",
        },
        "u": {
            alias: "upload",
            type:  "boolean",
        },
        "x": {
            alias: "experimental",
            type:  "array",
        },
    }).argv,
    del:        require("del"),
    fs:         require("fs"),
    gulpif:     require("gulp-if"),
    hash:       require("gulp-hash"),
    is_binary:  require("gulp-is-binary"),
    json:       require("jsonfile"),
    merge:      require("merge-stream"),
    newer:      require("gulp-newer"),
    notify:     require("gulp-notify"),
    path:       require("path"),
    plumber:    require("gulp-plumber"),
    prompt:     require("gulp-prompt"),
    sourcemaps: require("gulp-sourcemaps"),
    through:    require("through2"),
};

const {exec} = require("child_process");

// load .env
require("dotenv").config({
    path: PLUGINS.path.resolve(process.cwd(), ".config/.env"),
});

// load BrowserSync only when `--sync` is passed
if (PLUGINS.argv.sync) {
    PLUGINS.browser_sync = require("browser-sync");
}

// store global environment paths
global.settings = {
    paths: {
        src:    "./src",
        dev:    "./dev",
        dist:   "./dist",
        vendor: "./vendor",
    },
};

// store which tasks where ran
const RAN_TASKS = [];

// error handling
const ON_ERROR = function(err) {
    PLUGINS.notify.onError({
        title:    "Gulp",
        subtitle: "Error!",
        message:  "<%= error.message %>",
        sound:    "Beep",
    })(err);

    this.emit("end");
};

// set up a custom notifier to support toasts on WSL
const CUSTOM_NOTIFIER = function(options, callback) {
    // translate the Unix path to Windows
    exec(`wslpath -w ${options.appIcon}`, (error, stdout) => {
        // ensure that no control (i.e. color) characters exist in the message string, otherwise the toast won't show
        options.message.replace(/[\x00-\x1F\x7F-\x9F]\[[0-9]+m/g, "");

        // show the toast
        exec(`powershell.exe -command "New-BurntToastNotification -AppLogo '${stdout}' -Text '${options.title}', '${options.message}'"`);
    });

    callback();
};

// import custom modules
const STYLES_MODULE= require("./gulp-tasks/styles");
const SCRIPTS_MODULE = require("./gulp-tasks/scripts");
const HTML_MODULE = require("./gulp-tasks/html");
const MEDIA_MODULE = require("./gulp-tasks/media");
const SYNC_MODULE = require("./gulp-tasks/sync");
const UPLOAD_MODULE = require("./gulp-tasks/upload");
const RSYNC_MODULE = require("./gulp-tasks/rsync");
const CONFIG_MODULE = require("./gulp-tasks/config");
const INIT_MODULE = require("./gulp-tasks/init");

// primary tasks
GULP.task("styles", () => {
    return STYLES_MODULE.styles(
        GULP, PLUGINS, CUSTOM_NOTIFIER, RAN_TASKS, ON_ERROR
    );
});
GULP.task("scripts", () => {
    return SCRIPTS_MODULE.scripts(
        GULP, PLUGINS, CUSTOM_NOTIFIER, RAN_TASKS, ON_ERROR
    );
});
GULP.task("html", () => {
    return HTML_MODULE.html(
        GULP, PLUGINS, CUSTOM_NOTIFIER, RAN_TASKS, ON_ERROR
    );
});
GULP.task("php", () => {
    return HTML_MODULE.html(
        GULP, PLUGINS, CUSTOM_NOTIFIER, RAN_TASKS, ON_ERROR
    );
});
GULP.task("media", () => {
    return MEDIA_MODULE.media(
        GULP, PLUGINS, CUSTOM_NOTIFIER, RAN_TASKS, ON_ERROR
    );
});

// secondary tasks
GULP.task("upload", () => {
    return CONFIG_MODULE.config(GULP, PLUGINS, "ftp").then(() => {
        return UPLOAD_MODULE.upload(
            GULP, PLUGINS, CUSTOM_NOTIFIER, RAN_TASKS, ON_ERROR
        );
    });
});
GULP.task("rsync", () => {
    return CONFIG_MODULE.config(GULP, PLUGINS, "rsync").then(() => {
        return RSYNC_MODULE.rsync(
            GULP, PLUGINS, CUSTOM_NOTIFIER, RAN_TASKS, ON_ERROR
        );
    });
});

// configuration tasks
GULP.task("init", () => {
    return INIT_MODULE.init(GULP, PLUGINS, ON_ERROR);
});
GULP.task("config", () => {
    let mode = "";

    if (PLUGINS.argv.sync) {
        mode = "browsersync";
    } else if (PLUGINS.argv.upload) {
        mode = "ftp";
    } else if (PLUGINS.argv.rsync) {
        mode = "rsync";
    }

    return CONFIG_MODULE.config(
        GULP, PLUGINS, mode, true
    );
});

let currentlyRunning = false;

// default task, runs through all primary tasks
GULP.task("default", GULP.series(
    GULP.parallel("styles", "scripts", "html", "media"), function finalize() {
        // notify that task is complete
        GULP.src("gulpfile.js")
            .pipe(PLUGINS.gulpif(RAN_TASKS.length, PLUGINS.notify({
                appIcon:  PLUGINS.path.resolve("./src/assets/media/logo-favicon.png"),
                title:    "Success!",
                message:  `${RAN_TASKS.length} ${RAN_TASKS.length === 1 ? "task" : "tasks"} complete! [${RAN_TASKS.join(", ")}]`,
                notifier: process.env.BURNTTOAST === "true" ? CUSTOM_NOTIFIER : false,
                onLast:   true,
            })));

        // handle optional tasks sequentially
        return new Promise((resolve) => {
            // trigger upload task if --upload is passed
            if (PLUGINS.argv.upload) {
                CONFIG_MODULE.config(GULP, PLUGINS, "ftp").then(() => {
                    return UPLOAD_MODULE.upload(
                        GULP, PLUGINS, CUSTOM_NOTIFIER, RAN_TASKS, ON_ERROR
                    );
                }).then(() => {
                    resolve();
                });
            } else {
                resolve();
            }
        }).then(() => {
            // trigger rsync task if --rsync is passed
            return new Promise((resolve) => {
                if (PLUGINS.argv.rsync) {
                    CONFIG_MODULE.config(GULP, PLUGINS, "rsync").then(() => {
                        return RSYNC_MODULE.rsync(
                            GULP, PLUGINS, CUSTOM_NOTIFIER, RAN_TASKS, ON_ERROR
                        );
                    }).then(() => {
                        resolve();
                    });
                } else {
                    resolve();
                }
            });
        }).then(() => {
            // trigger sync task if --sync is passed
            return new Promise((resolve) => {
                if (PLUGINS.argv.sync) {
                    PLUGINS.browser_sync.reload();
                }

                // reset ran_tasks array
                RAN_TASKS.length = 0;

                currentlyRunning = false;

                resolve();
            });
        });
    }
));

// watch task, runs through all primary tasks, triggers when a file is saved
GULP.task("watch", () => {
    // set up a browser_sync server, if --sync is passed
    if (PLUGINS.argv.sync) {
        CONFIG_MODULE.config(GULP, PLUGINS, "browsersync").then(() => {
            SYNC_MODULE.sync(GULP, PLUGINS, CUSTOM_NOTIFIER);
        });
    }

    // watch for any changes
    const WATCHER = GULP.watch("src/**/*");

    // run default task on any change
    WATCHER.on("all", () => {
        if (!currentlyRunning) {
            currentlyRunning = true;
            GULP.task("default")();
        }
    });

    // end the task
    return;
});
