// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

/* jshint esversion: 6 */

// general stuff
var gulp         = require("gulp"),                                             // gulp
    EventEmitter = require("events").EventEmitter,                              // emit events
    argv         = require("yargs").options({                                   // set up yargs
        "d": {
            alias: "dist",
            type:  "boolean",
        },
        "e": {
            alias: "experimental",
            type:  "array",
        },
        "s": {
            alias: "sync",
            type:  "boolean",
        },
        "u": {
            alias: "ftp",
            type:  "boolean",
        },
    }).argv,
    fs           = require("fs"),                                               // the file system
    notify       = require("gulp-notify"),                                      // notifications
    plumber      = require("gulp-plumber"),                                     // prevent pipe breaking
    runSequence  = require("run-sequence"),                                     // allow tasks to be ran in sequence
    json         = require("json-file"),                                        // read/write JSON files
    prompt       = require("gulp-prompt"),                                      // allow user input
    del          = require("del"),                                              // delete files & folders
    newer        = require("gulp-newer"),                                       // checks if files are newer
    merge        = require("merge-stream"),                                     // merge streams
    gulpif       = require("gulp-if"),                                          // if statements in pipes
    watch        = require("gulp-watch"),                                       // watch for file changes
    sourcemaps   = require("gulp-sourcemaps"),                                  // sourcemaps
    concat       = require("gulp-concat"),                                      // concatenater
    fileinclude  = require("gulp-file-include"),                                // file includer, variable replacer
    replace      = require("gulp-replace"),                                     // replace regular expressions
    through      = require("through2"),                                         // transform the stream
    isBinary     = require("gulp-is-binary"),                                   // detect if a file is a binary
    removeCode   = require("gulp-remove-code"),                                 // remove code between special comments
    request      = require("request"),                                          // request remote files

    // media stuff
    imagemin = require("gulp-imagemin"),                                        // image compressor
    pngquant = require("imagemin-pngquant"),                                    // image compressor for PNGs

    // JS stuff
    jshint = require("gulp-jshint"),                                            // linter
    uglify = require("gulp-uglify"),                                            // uglifier

    // CSS stuff
    critical     = require("critical"),                                         // critical CSS creator
    sass         = require("gulp-sass"),                                        // SCSS compiler
    postcss      = require("gulp-postcss"),                                     // postcss
    postscss     = require("postcss-scss"),                                     // postcss SCSS parser
    bgImage      = require("postcss-bgimage"),                                  // remove backgrond images to improve Critical CSS
    autoprefixer = require("gulp-autoprefixer"),                                // autoprefix CSS
    flexibility  = require("postcss-flexibility"),                              // flexibility
    uncss        = require("gulp-uncss"),                                       // remove unused CSS

    // FTP stuff
    ftp  = require("vinyl-ftp"),                                                // FTP client
    sftp = require("gulp-sftp"),                                                // SFTP client

    /* STOP! These settings should always be blank! */
    /* To configure FTP credentials, run gulp ftp   */
    ftpHost = "",                                                               // FTP hostname (leave blank)
    ftpPort = "",                                                               // FTP port (leave blank)
    ftpMode = "",                                                               // FTP mode (leave blank)
    ftpUser = "",                                                               // FTP username (leave blank)
    ftpPass = "",                                                               // FTP password (leave blank)
    ftpPath = "",                                                               // FTP path (leave blank)

    // browser-sync stuff
    browserSync = require("browser-sync"),                                      // browser-sync

    bsProxy  = "",                                                              // browser-sync proxy (leave blank)
    bsPort   = "",                                                              // browser-sync port (leave blank)
    bsOpen   = "",                                                              // browser-sync open (leave blank)
    bsNotify = "",                                                              // browser-sync notify (leave blank)

    // read data from package.json
    homepage       = json.read("./package.json").get("homepage"),
    name           = json.read("./package.json").get("name"),
    pwa_name       = json.read("./package.json").get("progressive-web-app.name"),
    pwa_short_name = json.read("./package.json").get("progressive-web-app.short_name"),
    theme_color    = json.read("./package.json").get("progressive-web-app.theme_color"),
    description    = json.read("./package.json").get("description"),
    version        = json.read("./package.json").get("version"),
    repository     = json.read("./package.json").get("repository"),
    license        = json.read("./package.json").get("license"),

    // set up environment paths
    src  = "./src",                                                             // source directory
    dev  = "./dev",                                                             // development directory
    dist = "./dist",                                                            // production directory

    ranTasks = [];                                                              // store which tasks where ran

// Error handling
var onError = function(err) {
    notify.onError({
        title:    "Gulp",
        subtitle: "Error!",
        message:  "<%= error.message %>",
        sound:    "Beep",
    })(err);

    this.emit("end");
};

// media task, compresses images, copies other media
gulp.task("media", function () {
    "use strict";

    // set media directories
    var mediaDirectory = argv.cist ? dist + "/assets/media" : dev + "/assets/media";
    var screenshotDirectory = argv.dist ? dist : dev;

    // clean directories if --dist is passed
    if (argv.dist) {
        del(mediaDirectory + "/**/*");
        del(screenshotDirectory + "/screenshot.png");
    }

    // compress images, copy other media
    var media = gulp.src(src + "/assets/media/**/*")
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer(mediaDirectory)))
        // compress images
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{cleanupIDs: false, removeViewBox: false}],
            use:         [pngquant()]
        }))
        // output to compiled directory
        .pipe(gulp.dest(mediaDirectory));

    // compress screenshot
    var screenshot = gulp.src(src + "/screenshot.png")
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer(screenshotDirectory)))
        // compress screenshot
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use:         [pngquant()],
        }))
        // output to compiled directory
        .pipe(gulp.dest(screenshotDirectory));

    // merge both steams back in to one
    return merge(media, screenshot)
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // reload files
        .pipe(browserSync.reload({stream: true}))
        // notify that task is complete, if not part of default or watch
        .pipe(gulpif(gulp.seq.indexOf("media") > gulp.seq.indexOf("default"), notify({title: "Success!", message: "Media task complete!", onLast: true})))
        // push task to ranTasks array
        .on("data", function() {
            if (ranTasks.indexOf("media") < 0) ranTasks.push("media");
        });
});

// scripts task, lints, concatenates, & compresses JS
gulp.task("scripts", function () {
    "use strict";

    // set JS directory
    var jsDirectory = argv.dist ? dist + "/assets/scripts/" : dev + "/assets/scripts";

    // clean directory if --dist is passed
    if (argv.dist) del(jsDirectory + "/**/*");

    // lint scripts
    var linted = gulp.src([src + "/assets/scripts/*.js", "!" + src + "/assets/scripts/vendor.*.js"])
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer(jsDirectory + "/modern.js")))
        // lint all non-vendor scripts
        .pipe(jshint())
        // print lint errors
        .pipe(jshint.reporter("default"));

    // process critical scripts
    var critical = gulp.src([src + "/assets/scripts/critical/loadCSS.js", src + "/assets/scripts/critical/loadCSS.cssrelpreload.js"])
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer(jsDirectory + "/critical.js")))
        // initialize sourcemap
        .pipe(sourcemaps.init())
        // concatenate to critical.js
        .pipe(concat("critical.js"))
        // uglify (if --dist is passed)
        .pipe(gulpif(argv.dist, uglify()))
        // write sourcemap (if --dist isn't passed)
        .pipe(gulpif(!argv.dist, sourcemaps.write()))
        // output to compiled directory
        .pipe(gulp.dest(jsDirectory));

    // process modern scripts
    var modern = gulp.src([src + "/assets/scripts/vendor.*.js", src + "/assets/scripts/jquery.*.js", src + "/assets/scripts/*.js"])
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer(jsDirectory + "/modern.js")))
        // initialize sourcemap
        .pipe(sourcemaps.init())
        // concatenate to modern.js
        .pipe(concat("modern.js"))
        // uglify (if --dist is passed)
        .pipe(gulpif(argv.dist, uglify()))
        // write sourcemap (if --dist isn't passed)
        .pipe(gulpif(!argv.dist, sourcemaps.write()))
        // output to compiled directory
        .pipe(gulp.dest(jsDirectory));

    // process legacy scripts
    var legacy = gulp.src([src + "/assets/scripts/legacy/**/*"])
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer(jsDirectory + "/legacy.js")))
        // initialize sourcemap
        .pipe(sourcemaps.init())
        // concatenate to legacy.js
        .pipe(concat("legacy.js"))
        // uglify (if --dist is passed)
        .pipe(gulpif(argv.dist, uglify()))
        // write sourcemap (if --dist isn't passed)
        .pipe(gulpif(!argv.dist, sourcemaps.write()))
        // output to compiled directory
        .pipe(gulp.dest(jsDirectory));

    // merge all four steams back in to one
    return merge(linted, critical, modern, legacy)
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // reload files
        .pipe(browserSync.reload({stream: true}))
        // notify that task is complete, if not part of default or watch
        .pipe(gulpif(gulp.seq.indexOf("scripts") > gulp.seq.indexOf("default"), notify({title: "Success!", message: "Scripts task complete!", onLast: true})))
        // push task to ranTasks array
        .on("data", function() {
            if (ranTasks.indexOf("scripts") < 0) ranTasks.push("scripts");
        });
});

// styles task, compiles & prefixes SCSS
gulp.task("styles", function () {
    "use strict";

    // check whether or not to generate critical CSS;
    var generateCritical = false;

    // set CSS directory
    var cssDirectory = argv.dist ? dist + "/asets/styles" : dev + "/assets/styles";

    // clean directory if --dist is passed
    if (argv.dist) del(cssDirectory + "/**/*");

    // process all SCSS in root styles directory
    return gulp.src([src + "/assets/styles/*.scss"])
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer({dest: cssDirectory + "/modern.css", extra: [src + "/assets/styles/**/*.scss"]})))
        // initialize sourcemap
        .pipe(sourcemaps.init())
        // compile SCSS (compress if --dist is passed)
        .pipe(gulpif(argv.dist, sass({outputStyle: "compressed"}), sass()))
        // prefix CSS
        .pipe(autoprefixer("last 2 version", "ie 8", "ie 9"))
        // insert -js-display: flex; for flexbility
        .pipe(postcss([flexibility()]))
        // write sourcemap (if --dist isn't passed)
        .pipe(gulpif(!argv.dist, sourcemaps.write()))
        // remove unused CSS
        .pipe(gulpif(argv.experimental && argv.experimental.length > 0 && argv.experimental.includes("uncss") && sitemap.data !== false, uncss({
            html: homepage
        })))
        // generate critical CSS
        .pipe(gulpif(argv.experimental && argv.experimental.length > 0 && argv.experimental.includes("critical"), through.obj(function(file, enc, next) {
            if (!generateCritical) {
                generateCritical = true;

                critical.generate({
                    base:   cssDirectory,
                    src:    homepage,
                    dest:   "critical.css",
                    height: 900,
                    width:  1280,
                    minify: true,
                });
            }

            // go to next file
            next(null, file);
        })))
        // output to compiled directory
        .pipe(gulp.dest(cssDirectory))
        // reload files
        .pipe(browserSync.reload({stream: true}))
        // notify that task is complete, if not part of default or watch
        .pipe(gulpif(gulp.seq.indexOf("styles") > gulp.seq.indexOf("default"), notify({title: "Success!", message: "Styles task complete!", onLast: true})))
        // push task to ranTasks array
        .on("data", function() {
            if (ranTasks.indexOf("styles") < 0) ranTasks.push("styles");
        });
});

// html task, copies binaries, converts includes & variables in HTML
gulp.task("html", function () {
    "use strict";

    // set HTML directory
    var htmlDirectory = argv.dist ? dist : dev;

    // clean directory if --dist is passed
    if (argv.dist) del([htmlDirectory + "/**/*", "!" + htmlDirectory + "{/assets,/assets/**}"]);

    // copy binaries
    var binaries = gulp.src([src + "/**/*", "!" + src + "{/assets,/assets/**}"])
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer(htmlDirectory)))
        // check if a file is a binary
        .pipe(isBinary())
        // skip file if it's not a binary
        .pipe(through.obj(function(file, enc, next) {
            if (!file.isBinary()) {
                next();
                return;
            }

            // go to next file
            next(null, file);
        }))
        // output to compiled directory
        .pipe(gulp.dest(htmlDirectory));

    // process HTML
    var html = gulp.src([src + "/**/*", "!" + src + "{/assets,/assets/**}"])
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer(htmlDirectory)))
        // check if file is a binary
        .pipe(isBinary())
        // skip file if it's a binary
        .pipe(through.obj(function(file, enc, next) {
            if (file.isBinary()) {
                next();
                return;
            }

            // go to next file
            next(null, file);
        }))
        // replace variables
        .pipe(fileinclude({
            prefix:   "@@",
            basepath: "@file",
            context: {
                name:           name,
                pwa_name:       pwa_name,
                pwa_short_name: pwa_short_name,
                theme_color:    theme_color,
                description:    description,
                version:        version,
                repository:     repository,
                license:        license,
            }
        }))
        // replace icon placeholders
        .pipe(replace(/(?:<icon:)([A-Za-z0-9\-\_][^:>]+)(?:\:([A-Za-z0-9\-\_\ ][^:>]*))?(?:>)/g, "<i class='icon $2'><svg class='icon_svg' aria-hidden='true'><use xlink:href='#$1' \/><\/svg></i>"))
        // output to compiled directory
        .pipe(gulp.dest(htmlDirectory));

    // merge both steams back in to one
    return merge(binaries, html)
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // reload files
        .pipe(browserSync.reload({stream: true}))
        // notify that task is complete, if not part of default or watch
        .pipe(gulpif(gulp.seq.indexOf("html") > gulp.seq.indexOf("default"), notify({title: "Success!", message: "HTML task complete!", onLast: true})))
        // push task to ranTasks array
        .on("data", function() {
            if (ranTasks.indexOf("html") < 0) ranTasks.push("html");
        });
});

gulp.task("init", function () {
    "use strict";

    return gulp.src(src + "/**/*")
        // check if a file is a binary
        .pipe(isBinary())
        // skip file if it's a binary
        .pipe(through.obj(function(file, enc, next) {
            if (file.isBinary()) {
                next();
                return;
            }

            // go to next file
            next(null, file);
        }))
        // remove login HTML code if --remove --login is passed
        .pipe(removeCode({no_login: argv.remove && argv.login ? true : false, commentStart: "<!--", commentEnd: "-->"}))
        // output to source directory
        .pipe(gulp.dest(src));
});

// config task, generate configuration file for FTP & BrowserSync and prompt dev for input
gulp.task("config", function (cb) {
    "use strict";

    // generate config.json and start other functions
    fs.stat("./config.json", function (err, stats) {
        if (err !== null) {
            var json_data =
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
}\n`;

            fs.writeFile("./config.json", json_data, function (err) {
                configureFTP(function() {
                  configureBrowsersync();
                });
            });
        } else {
            configureFTP(function() {
              configureBrowsersync();
            });
        }
    });

    // configure FTP credentials
    function configureFTP(cb) {
        // read FTP settingss from config.json
        if (!argv.dist) {
            ftpHost = json.read("./config.json").get("ftp.dev.host");
            ftpPort = json.read("./config.json").get("ftp.dev.port");
            ftpMode = json.read("./config.json").get("ftp.dev.mode");
            ftpUser = json.read("./config.json").get("ftp.dev.user");
            ftpPass = json.read("./config.json").get("ftp.dev.pass");
            ftpPath = json.read("./config.json").get("ftp.dev.path");
        } else {
            ftpHost = json.read("./config.json").get("ftp.dist.host");
            ftpPort = json.read("./config.json").get("ftp.dist.port");
            ftpMode = json.read("./config.json").get("ftp.dist.mode");
            ftpUser = json.read("./config.json").get("ftp.dist.user");
            ftpPass = json.read("./config.json").get("ftp.dist.pass");
            ftpPath = json.read("./config.json").get("ftp.dist.path");
        }

        if (argv.all || (gulp.seq.indexOf("config") < gulp.seq.indexOf("ftp") || argv.ftp) && (argv.config || ftpHost === "" || ftpUser === "" || ftpPass === "" || ftpPath === "")) {
            // reconfigure settings in config.json if a field is empty or if --config is passed
            gulp.src("./config.json")
                .pipe(prompt.prompt([{
                    // prompt for host
                    type:    "input",
                    name:    "host",
                    message: "FTP hostname:",
                    default: ftpHost,
                },
                {
                    // prompt for port
                    type:    "input",
                    name:    "port",
                    message: "FTP port:",
                    default: ftpPort,
                },
                {
                    // prompt for mode
                    type:    "list",
                    name:    "mode",
                    message: "FTP mode:",
                    choices: ["ftp", "tls", "sftp"],
                    default: ftpMode === "ftp" ? 0 : ftpMode === "tls" ? 1 : ftpMode === "sftp" ? 2 : 0,
                },
                {
                    // prompt for user
                    type:    "input",
                    name:    "user",
                    message: "FTP username:",
                    default: ftpUser,
                },
                {
                    // prompt for password
                    type:    "password",
                    name:    "pass",
                    message: "FTP password:",
                    default: ftpPass,
                },
                {
                    // prompt for path
                    type:    "input",
                    name:    "path",
                    message: "FTP remote path:",
                    default: ftpPath,
                }], function(res) {
                    // open config.json
                    var file = json.read("./config.json");

                    // update ftp settings in config.json
                    if (!argv.dist) {
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
                    ftpHost = res.host;
                    ftpPort = res.port;
                    ftpMode = res.mode;
                    ftpUser = res.user;
                    ftpPass = res.pass;
                    ftpPath = res.path;

                    configureBrowsersync();
                }));
        } else {
            configureBrowsersync();
        }
    }

    // configure BrowserSync settings
    function configureBrowsersync() {
        // read browsersync settings from config.json
        bsProxy  = json.read("./config.json").get("browsersync.proxy");
        bsPort   = json.read("./config.json").get("browsersync.port");
        bsOpen   = json.read("./config.json").get("browsersync.open");
        bsNotify = json.read("./config.json").get("browsersync.notify");

        if (argv.all || (gulp.seq.indexOf("config") < gulp.seq.indexOf("sync") || argv.sync) && (argv.config || bsProxy === "" || bsPort === "" || bsOpen === "" || bsNotify === "")) {
            // reconfigure settings in config.json if a field is empty or if --config is passed
            gulp.src("./config.json")
                .pipe(prompt.prompt([{
                    // prompt for proxy
                    type:    "input",
                    name:    "proxy",
                    message: "Browsersync proxy:",
                    default: bsProxy === "" ? "localhost:8888" : bsProxy,
                },
                {
                    // prompt for port
                    type:    "input",
                    name:    "port",
                    message: "Browsersync port:",
                    default: bsPort === "" ? "8080" : bsPort,
                },
                {
                    // prompt for how to open
                    type:    "input",
                    name:    "open",
                    message: "Browsersync open:",
                    default: bsOpen === "" ? "external" : bsOpen,
                },
                {
                    // prompt for whether to notify
                    type:    "input",
                    name:    "notify",
                    message: "Browsersync notify:",
                    default: bsNotify === "" ? "false" : bsNotify,
                }], function(res) {
                    // open config.json
                    var file = json.read("./config.json");

                    // update browsersync settings in config.json
                    file.set("browsersync.proxy",  res.proxy);
                    file.set("browsersync.port",   res.port);
                    file.set("browsersync.open",   res.open);
                    file.set("browsersync.notify", res.notify);

                    // write updated file contents
                    file.writeSync();

                    // read browsersync settings from config.json
                    bsProxy  = res.proxy;
                    bsPort   = res.port;
                    bsOpen   = res.open;
                    bsNotify = res.notify;

                    cb();
                }));
        } else {
            cb();
        }
    }
});

// ftp task, upload to FTP environment, depends on config
gulp.task("ftp", ["config"], function() {
    "use strict";

    // set FTP directory
    var ftpDirectory = argv.dist ? dist : dev;

    // create SFTP connection
    var sftpConn = sftp({
        host:       ftpHost,
        port:       ftpPort,
        username:   ftpUser,
        password:   ftpPass,
        remotePath: ftpPath,
    });

    // create FTP connection
    var ftpConn = ftp.create({
        host:   ftpHost,
        port:   ftpPort,
        secure: ftpMode === "tls" ? true : false,
        user:   ftpUser,
        pass:   ftpPass,
        path:   ftpPath,
    });

    // upload changed files
    return gulp.src(ftpDirectory + "/**/*")
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // check if files are newer
        .pipe(gulpif(!argv.dist, newer({dest: src, extra: [ftpDirectory + "/**/*"]})))
        // check if files are newer
        .pipe(gulpif(ftpMode !== "sftp", ftpConn.newer(ftpPath)))
        // upload changed files
        .pipe(gulpif(ftpMode !== "sftp", ftpConn.dest(ftpPath), sftpConn))
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // reload files
        .pipe(browserSync.reload({stream: true}))
        // notify that task is complete, if not part of default or watch
        .pipe(notify({title: "Success!", message: "FTP task complete!", onLast: true}));
});

// sync task, set up a browserSync server, depends on config
gulp.task("sync", ["config"], function(cb) {
    "use strict";

    browserSync({
        proxy:  bsProxy,
        port:   bsPort,
        open:   bsOpen,
        notify: bsNotify,
    });
});

// default task, runs through everything but dist
gulp.task("default", ["media", "scripts", "styles", "html"], function () {
    "use strict";

    // notify that task is complete
    gulp.src("gulpfile.js")
        .pipe(gulpif(ranTasks.length, notify({title: "Success!", message: ranTasks.length + " task" + (ranTasks.length > 1 ? "s" : "") + " complete! [" + ranTasks.join(", ") + "]", onLast: true})));

    // trigger FTP task if FTP flag is passed
    if (argv.ftp) runSequence("ftp");

    // reset ranTasks array
    ranTasks.length = 0;
});

// watch task, runs through everything but dist, triggers when a file is saved
gulp.task("watch", function () {
    "use strict";

    // set up a browserSync server, if --sync is passed
    if (argv.sync) runSequence("sync");

    // watch for any changes
    watch("./src/**/*", function () {
        // run through all tasks, then ftp, if --ftp is passed
        runSequence("default");
    });
});
