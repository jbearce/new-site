    // general stuff
var gulp = require("gulp"),                      // gulp
    fs = require("fs"),                          // the file system
    notify = require("gulp-notify"),             // notifications
    plumber = require("gulp-plumber"),           // prevent pipe breaking
    runSequence = require("run-sequence"),       // allow tasks to be ran in sequence
    json = require("json-file"),                 // read/write JSON files
    prompt = require("gulp-prompt")              // allow user input
    argv = require("yargs").argv,                // --flags
    del = require("del"),                        // delete files & folders
    newer = require("gulp-newer"),               // checks if files are newer
    merge = require("merge-stream"),             // merge streams
    gulpif = require("gulp-if"),                 // if statements in pipes
    watch = require("gulp-watch"),               // watch for file changes
    sourcemaps = require("gulp-sourcemaps"),     // sourcemaps
    concat = require("gulp-concat"),             // concatenater
    fileinclude = require("gulp-file-include"),  // file includer, variable replacer

    // media stuff
    imagemin = require("gulp-imagemin"),         // image compressor
    pngquant = require("imagemin-pngquant"),     // image compressor for PNGs

    // JS stuff
    jshint = require("gulp-jshint"),             // linter
    uglify = require("gulp-uglify"),             // concatenater

    // CSS stuff
    sass = require("gulp-sass"),                 // SCSS compiler
    autoprefixer = require("gulp-autoprefixer"), // autoprefix CSS

    // FTP stuff
    ftp = require("vinyl-ftp"),                  // FTP client

    ftpHost = "",                                // FTP hostname (leave blank)
    ftpUser = "",                                // FTP username (leave blank)
    ftpPass = "",                                // FTP password (leave blank)
    ftpPath = "",                                // FTP path (leave blank)

    // browser-sync stuff
    browserSync = require("browser-sync"),       // browser-sync

    bsProxy = "",                                // browser-sync proxy (leave blank)
    bsPort = "",                                 // browser-sync port (leave blank)
    bsOpen = "",                                 // browser-sync open (leave blank)
    bsNotify = "",                               // browser-sync notify (leave blank)

    // read data from package.json
    name = json.read("./package.json").get("name"),
    description = json.read("./package.json").get("description"),
    version = json.read("./package.json").get("version"),
    repository = json.read("./package.json").get("repository"),
    license = json.read("./package.json").get("license"),

    // set up environment paths
    src = "./src",   // source directory
    dev = "./dev",   // development directory
    dist = "./dist"; // production directory

// Error handling
var onError = function(err) {
    notify.onError({
        title:    "Gulp",
        subtitle: "Error!",
        message:  "<%= error.message %>",
        sound:    "Beep"
    })(err);

    this.emit("end");
};

// media task, compresses images & copies media
gulp.task("media", function () {
    "use strict";

    // development media directory
    var mediaDirectory = dev + "/assets/media";
    var screenshotDirectory = dev;

    // production media directory (if --dist is passed)
    if (argv.dist) {
        mediaDirectory = dist + "/assets/media";
        screenshotDirectory = dist;
    }

    // clean directory if --clean is passed
    if (argv.clean) {
        del(mediaDirectory + "/**/*");
        del(screenshotDirectory + "/screenshot.png");
    }

    // compress images, copy media
    var media = gulp.src(src + "/assets/media/**/*")
        // check if source is newer than destination
        .pipe(newer(mediaDirectory))
        // compress images
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        }))
        // output to the compiled directory
        .pipe(gulp.dest(mediaDirectory));

    // compress screenshot
    var screenshot = gulp.src(src + "/screenshot.png")
        // check if source is newer than destination
        .pipe(newer(screenshotDirectory))
        // compress screenshot
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        }))
        // output to the compiled directory
        .pipe(gulp.dest(screenshotDirectory));

    // merge both steams back in to one
    return merge(media, screenshot)
        // reload the files
        .pipe(browserSync.reload({stream: true}))
        // notify that the task is complete
        .pipe(notify({message: "Media task complete!", onLast: true}));
});

// scripts task, concatenates & lints JS
gulp.task("scripts", function () {
    "use strict";

    // development JS directory
    var jsDirectory = dev + "/assets/scripts";

    // production JS directory (if --dist is passed)
    if (argv.dist) {
        jsDirectory = dist + "/assets/scripts";
    }

    // clean directory if --clean is passed
    if (argv.clean) {
        del(jsDirectory + "/**/*");
    }

    // lint scripts
    var linted = gulp.src([src + "/assets/scripts/*.js", "!" + src + "/assets/scripts/vendor.*.js"])
        // check if source is newer than destination
        .pipe(newer(jsDirectory + "/all.js"))
        // lint all non-vendor scripts
        .pipe(jshint())
        // print lint errors
        .pipe(jshint.reporter("default"));

    // concatenate scripts
    var concated = gulp.src([src + "/assets/scripts/vendor.*.js", src + "/assets/scripts/jquery.*.js", src + "/assets/scripts/*.js"])
        // check if source is newer than destination
        .pipe(newer(jsDirectory + "/all.js"))
        // initialize sourcemap
        .pipe(sourcemaps.init())
        // concatenate to all.js
        .pipe(concat("all.js"))
        // uglify (if --dist is passed)
        .pipe(gulpif(argv.dist, uglify()))
        // write the sourcemap (if --dist isn't passed)
        .pipe(gulpif(!argv.dist, sourcemaps.write()))
        // output to the compiled directory
        .pipe(gulp.dest(jsDirectory));

    // copy fallback scripts
    var copied = gulp.src([src + "/assets/scripts/fallback/**/*"])
        // check if source is newer than destination
        .pipe(newer(jsDirectory + "/fallback"))
        // output to the compiled directory
        .pipe(gulp.dest(jsDirectory + "/fallback"));

    // merge all three steams back in to one
    return merge(linted, concated, copied)
        // reload the files
        .pipe(browserSync.reload({stream: true}))
        // notify that the task is complete
        .pipe(notify({message: "Scripts task complete!", onLast: true}));
});

// styles task, compiles & prefixes SCSS
gulp.task("styles", function () {
    "use strict";

    // development CSS directory
    var cssDirectory = dev + "/assets/styles";

    // production CSS directory (if --dist is passed)
    if (argv.dist) {
        cssDirectory = dist + "/assets/styles";
    }

    // clean directory if --clean is passed
    if (argv.clean) {
        del(cssDirectory + "/**/*");
    }

    // compile all SCSS in the root styles directory
    return gulp.src(src + "/assets/styles/*.scss")
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // check if source is newer than destination
        //.pipe(newer(cssDirectory + "/modern.css")) // doens't work due to imports
        // initialize sourcemap
        .pipe(sourcemaps.init())
        // compile SCSS (compress if --dist is passed)
        .pipe(gulpif(argv.dist, sass({outputStyle: "compressed"}), sass()))
        // prefix CSS
        .pipe(autoprefixer("last 2 version", "ie 8", "ie 9"))
        // write the sourcemap (if --dist isn't passed)
        .pipe(gulpif(!argv.dist, sourcemaps.write()))
        // output to the compiled directory
        .pipe(gulp.dest(cssDirectory))
        // reload the files
        .pipe(browserSync.reload({stream: true}))
        // notify that the task is complete
        .pipe(notify({message: "Styles task complete!", onLast: true}));
});

// styles task, compiles & prefixes SCSS
gulp.task("html", function () {
    "use strict";

    // development HTML directory
    var htmlDirectory = dev;

    // production HTML directory (if --dist is passed)
    if (argv.dist) {
        htmlDirectory = dist;
    }

    // clean directory if --clean is passed
    if (argv.clean) {
        del([htmlDirectory + "/**/*", "!" + htmlDirectory + "{/assets,/assets/**}"]);
    }

    // import HTML files and replace their variables
    return gulp.src([src + "/**/*", "!" + src + "/screenshot.png", "!" + src + "{/assets,/assets/**}"])
        // check if source is newer than destination
        .pipe(newer(htmlDirectory))
        // insert variables
        .pipe(fileinclude({
            prefix: "@@",
            basepath: "@file",
            context: {
                name: name,
                description: description,
                version: version,
                repository: repository,
                license: license,
            }
        }))
        // output to the compiled directory
        .pipe(gulp.dest(htmlDirectory))
        // reload the files
        .pipe(browserSync.reload({stream: true}))
        // notify that the task is complete
        .pipe(notify({message: "HTML task complete!", onLast: true}));
});

// initialize ftp.json
gulp.task("ftp-init", function(cb) {
    // check if the ftp.json exists
    fs.stat("./ftp.json", function (err, stats) {
        if (err != null) {
            // if it doesn't, create it
            fs.writeFile("./ftp.json", "{\"dev\": {\"host\": \"\",\"user\": \"\",\"pass\": \"\",\"path\": \"\"\},\"dist\": {\"host\": \"\",\"user\": \"\",\"pass\": \"\",\"path\": \"\"\}}", function (err) {
                cb(err);
            });
        } else {
            // otherwise return
            cb(err);
        }
    });
});

// configure FTP credentials in ftp.json, depends on ftp-init
gulp.task("ftp-config", ["ftp-init"], function(cb) {
    // read FTP credentials from ftp.json
    ftpHost = json.read("./ftp.json").get("dev.host"),
    ftpUser = json.read("./ftp.json").get("dev.user"),
    ftpPass = json.read("./ftp.json").get("dev.pass"),
    ftpPath = json.read("./ftp.json").get("dev.path");

    // read dist FTP credentials from ftp.json (if --dist is passed)
    if (argv.dist) {
        ftpHost = json.read("./ftp.json").get("dist.host"),
        ftpUser = json.read("./ftp.json").get("dist.user"),
        ftpPass = json.read("./ftp.json").get("dist.pass"),
        ftpPath = json.read("./ftp.json").get("dist.path");
    }

    if (ftpHost === "" || ftpUser === "" || ftpPass === "" || ftpPath === "" || argv.config) {
        // reconfigure ftp.json if a field is empty or if --config is passed
        gulp.src("./ftp.json")
            .pipe(prompt.prompt([{
                // prompt for the hostname
                type: "input",
                name: "host",
                message: "host:",
                default: host,
            },
            {
                // prompt for the username
                type: "input",
                name: "user",
                message: "username:",
                default: user,
            },
            {
                // prompt for the password
                type: "password",
                name: "pass",
                message: "password:",
                default: pass,
            },
            {
                // prompt for the remote path
                type: "input",
                name: "path",
                message: "remote path:",
                default: path,
            }], function(res) {
                // open the ftp.json
                var file = json.read("./ftp.json");

                // set connection to dev
                var connection = "dev";

                // set connection to dist (if --dist is passed)
                if (argv.dist) {
                    connection = "dist";
                }

                // update the file contents
                file.set(connection + ".host", res.host);
                file.set(connection + ".user", res.user);
                file.set(connection + ".pass", res.pass);
                file.set(connection + ".path", res.path);

                // write the updated file contents
                file.writeSync();

                // read FTP credentials from ftp.json
                ftpHost = res.host,
                ftpUser = res.user,
                ftpPass = res.pass,
                ftpPath = res.path;

                cb(null);
            }));
    } else {
        // otherwise return
        cb(null);
    }
})

// upload to FTP environment, depends on ftp-config, ftp-init
gulp.task("ftp-upload", ["ftp-config", "ftp-init"], function(cb) {
    // development FTP directory
    var ftpDirectory = dev;

    // production FTP directory (if --dist is passed)
    if (argv.dist) {
        ftpDirectory = dist;
    }

    // create the FTP connection
    var conn = ftp.create({
        host: ftpHost,
        user: ftpUser,
        path: ftpPath,
        path: ftpPath,
    })

    // upload the changed files
    return gulp.src(ftpDirectory + "/**/*")
        // check if files are newer
        .pipe(conn.newer(ftpPath))
        // upload changed files
        .pipe(conn.dest(ftpPath))
        // notify that the task is complete
        .pipe(notify({message: "FTP task complete!", onLast: true}));

    // return
    cb(null);
});

// combine FTP tasks
gulp.task("ftp", ["ftp-upload", "ftp-config", "ftp-init"]);

// initialize the browsersync.json
gulp.task("browsersync-init", function(cb) {
    // check if the browsersync.json exists
    fs.stat("./browsersync.json", function (err, stats) {
        if (err != null) {
            // if it doesn't, create it
            fs.writeFile("./browsersync.json", "{\"proxy\": \"\",\"port\": \"\",\"open\": \"\",\"notify\": \"\"}", function (err) {
                cb(err);
            });
        } else {
            // otherwise return
            cb(err);
        }
    });
});

// set values in browsersync.json, depends on browsersync-init
gulp.task("browsersync-config", ["browsersync-init"], function(cb) {
    // read browsersync settings from browsersync.json
    bsProxy = json.read("./browsersync.json").get("proxy"),
    bsPort = json.read("./browsersync.json").get("port"),
    bsOpen = json.read("./browsersync.json").get("open"),
    bsNotify = json.read("./browsersync.json").get("notify");

    if (bsProxy === "" || bsPort === "" || bsOpen === "" || bsNotify === "" || argv.config) {
        // reconfigure browsersync settings in browsersync.json if a field is empty or if --config is passed
        gulp.src("./browsersync.json")
            .pipe(prompt.prompt([{
                // prompt for the proxy
                type: "input",
                name: "bsProxy",
                message: "proxy:",
                default: proxy,
            },
            {
                // prompt for the port
                type: "input",
                name: "bsPort",
                message: "port:",
                default: port,
            },
            {
                // prompt for how to open
                type: "input",
                name: "bsOpen",
                message: "open:",
                default: open,
            },
            {
                // prompt for whether to notify
                type: "input",
                name: "bsNotify",
                message: "notify:",
                default: notify,
            }], function(res) {
                // open the browsersync.json
                var file = json.read("./browsersync.json");

                // update the file contents
                file.set("proxy", res.proxy);
                file.set("port", res.port);
                file.set("open", res.open);
                file.set("notify", res.notify);

                // write the updated file contents
                file.writeSync();

                // read browsersync settings from browsersync.json
                bsProxy = res.proxy,
                bsPort = res.port,
                bsOpen = res.open,
                bsNotify = res.notify;

                cb(null);
            }));
    } else {
        // otherwise return
        cb(null);
    }
});

// set up a browserSync server, depends on browsersync-config and browsersync-init
gulp.task("browsersync-serve", ["browsersync-config", "browsersync-init"], function(cb) {
    browserSync({
        proxy: bsProxy,
        port: bsPort,
        open: bsOpen,
        notify: bsNotify,
    });
});

// combine browsersync tasks
gulp.task("browsersync", ["browsersync-serve", "browsersync-config", "browsersync-init"]);

// default task, runs through everything but dist
gulp.task("default", function () {
    "use strict";

    if (argv.ftp) {
        runSequence(["media", "scripts", "styles", "html"], "ftp");
    } else {
        runSequence(["media", "scripts", "styles", "html"]);
    }
});

// watch task, runs through everything but dist, triggers when a file is saved
gulp.task("watch", function () {
    "use strict";

    // set up a browserSync server, if --sync is passed
    if (argv.sync) {
        runSequence("browsersync");
    }

    // watch for any changes
    watch("./src/**/*", function () {
        // run through all tasks, then ftp, if --ftp is passed
        if (argv.ftp) {
            runSequence(["media", "scripts", "styles", "html"], "ftp");
        // run through all tasks
        } else {
            runSequence(["media", "scripts", "styles", "html"]);
        }
    });
});
