    // general stuff
var gulp = require("gulp"),                                                     // gulp
    fs = require("fs"),                                                         // the file system
    notify = require("gulp-notify"),                                            // notifications
    plumber = require("gulp-plumber"),                                          // prevent pipe breaking
    runSequence = require("run-sequence"),                                      // allow tasks to be ran in sequence
    json = require("json-file"),                                                // read/write JSON files
    prompt = require("gulp-prompt"),                                            // allow user input
    argv = require("yargs").argv,                                               // --flags
    del = require("del"),                                                       // delete files & folders
    newer = require("gulp-newer"),                                              // checks if files are newer
    merge = require("merge-stream"),                                            // merge streams
    gulpif = require("gulp-if"),                                                // if statements in pipes
    watch = require("gulp-watch"),                                              // watch for file changes
    sourcemaps = require("gulp-sourcemaps"),                                    // sourcemaps
    concat = require("gulp-concat"),                                            // concatenater
    fileinclude = require("gulp-file-include"),                                 // file includer, variable replacer
    replace = require("gulp-replace"),                                          // replace regular expressions
    through = require("through2"),                                              // transform the stream
    isBinary = require("gulp-is-binary"),                                       // detect if a file is a binary

    // media stuff
    imagemin = require("gulp-imagemin"),                                        // image compressor
    pngquant = require("imagemin-pngquant"),                                    // image compressor for PNGs

    // JS stuff
    jshint = require("gulp-jshint"),                                            // linter
    uglify = require("gulp-uglify"),                                            // uglifier

    // CSS stuff
    sass = require("gulp-sass"),                                                // SCSS compiler
    postcss = require("gulp-postcss"),                                          // postcss
    postscss = require("postcss-scss"),                                         // postcss SCSS parser
    bgImage = require("postcss-bgimage"),                                       // remove backgrond images to improve Critical CSS
    autoprefixer = require("gulp-autoprefixer"),                                // autoprefix CSS
    flexibility = require("postcss-flexibility"),                               // flexibility

    // FTP stuff
    ftp = require("vinyl-ftp"),                                                 // FTP client
    sftp = require("gulp-sftp"),                                                // SFTP client

    ftpHost = "",                                                               // FTP hostname (leave blank)
    ftpPort = "",                                                               // FTP port (leave blank)
    ftpMode = "",                                                               // FTP mode (leave blank)
    ftpUser = "",                                                               // FTP username (leave blank)
    ftpPass = "",                                                               // FTP password (leave blank)
    ftpPath = "",                                                               // FTP path (leave blank)

    // browser-sync stuff
    browserSync = require("browser-sync"),                                      // browser-sync

    bsProxy = "",                                                               // browser-sync proxy (leave blank)
    bsPort = "",                                                                // browser-sync port (leave blank)
    bsOpen = "",                                                                // browser-sync open (leave blank)
    bsNotify = "",                                                              // browser-sync notify (leave blank)

    // read data from package.json
    name = json.read("./package.json").get("name"),
    description = json.read("./package.json").get("description"),
    version = json.read("./package.json").get("version"),
    repository = json.read("./package.json").get("repository"),
    license = json.read("./package.json").get("license"),

    // set up environment paths
    src = "./src",                                                              // source directory
    dev = "./dev",                                                              // development directory
    dist = "./dist",                                                            // production directory

    ranTasks = [];                                                              // store which tasks where ran

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

// media task, compresses images, copies other media
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

    // clean directory if --dist is passed
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
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        }))
        // output to the compiled directory
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
            use: [pngquant()]
        }))
        // output to the compiled directory
        .pipe(gulp.dest(screenshotDirectory));

    // merge both steams back in to one
    return merge(media, screenshot)
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // reload the files
        .pipe(browserSync.reload({stream: true}))
        // notify that the task is complete, if not part of default or watch
        .pipe(gulpif(gulp.seq.indexOf("media") > gulp.seq.indexOf("default"), notify({title: "Success!", message: "Media task complete!", onLast: true})))
        // push the task to the ranTasks array
        .on("data", function() {
            if (ranTasks.indexOf("media") < 0) ranTasks.push("media");
        });
});

// scripts task, lints, concatenates, & compresses JS
gulp.task("scripts", function () {
    "use strict";

    // development JS directory
    var jsDirectory = dev + "/assets/scripts";

    // production JS directory (if --dist is passed)
    if (argv.dist) jsDirectory = dist + "/assets/scripts";

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
        // write the sourcemap (if --dist isn't passed)
        .pipe(gulpif(!argv.dist, sourcemaps.write()))
        // output to the compiled directory
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
        // write the sourcemap (if --dist isn't passed)
        .pipe(gulpif(!argv.dist, sourcemaps.write()))
        // output to the compiled directory
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
        // write the sourcemap (if --dist isn't passed)
        .pipe(gulpif(!argv.dist, sourcemaps.write()))
        // output to the compiled directory
        .pipe(gulp.dest(jsDirectory));

    // merge all four steams back in to one
    return merge(linted, critical, modern, legacy)
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // reload the files
        .pipe(browserSync.reload({stream: true}))
        // notify that the task is complete, if not part of default or watch
        .pipe(gulpif(gulp.seq.indexOf("scripts") > gulp.seq.indexOf("default"), notify({title: "Success!", message: "Scripts task complete!", onLast: true})))
        // push the task to the ranTasks array
        .on("data", function() {
            if (ranTasks.indexOf("scripts") < 0) ranTasks.push("scripts");
        });
});

// styles task, compiles & prefixes SCSS
gulp.task("styles", function () {
    "use strict";

    // development CSS directory
    var cssDirectory = dev + "/assets/styles";

    // production CSS directory (if --dist is passed)
    if (argv.dist) cssDirectory = dist + "/assets/styles";

    // clean directory if --dist is passed
    if (argv.dist) del(cssDirectory + "/**/*");

    // process critical SCSS
    var critical = gulp.src(src + "/assets/styles/critical.scss")
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer({dest: cssDirectory + "/critical.css", extra: [src + "/assets/styles/**/*.scss"]})))
        // remove background images to prevent 404s
        .pipe(postcss([bgImage({mode: "cutter"})], {syntax: postscss}))
        // compile SCSS
        .pipe(sass({outputStyle: "compressed"}))
        // compile SCSS (again, to recompress)
        .pipe(sass({outputStyle: "compressed"}))
        // prefix CSS
        .pipe(autoprefixer("last 2 version", "ie 8", "ie 9"))
        // insert -js-display: flex; for flexbility
        .pipe(postcss([flexibility()]))
        // output to the compiled directory
        .pipe(gulp.dest(cssDirectory));

    // process all SCSS in the root styles directory
    var standard = gulp.src([src + "/assets/styles/*.scss", "!" + src + "/assets/styles/critical.scss"])
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
        // write the sourcemap (if --dist isn't passed)
        .pipe(gulpif(!argv.dist, sourcemaps.write()))
        // output to the compiled directory
        .pipe(gulp.dest(cssDirectory));

    // merge both steams back in to one
    return merge(critical, standard)
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // reload the files
        .pipe(browserSync.reload({stream: true}))
        // notify that the task is complete, if not part of default or watch
        .pipe(gulpif(gulp.seq.indexOf("styles") > gulp.seq.indexOf("default"), notify({title: "Success!", message: "Styles task complete!", onLast: true})))
        // push the task to the ranTasks array
        .on("data", function() {
            if (ranTasks.indexOf("styles") < 0) ranTasks.push("styles");
        });
});

// html task, copies binaries, converts includes & variables in HTML
gulp.task("html", function () {
    "use strict";

    // development HTML directory
    var htmlDirectory = dev;

    // production HTML directory (if --dist is passed)
    if (argv.dist) htmlDirectory = dist;

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
        // skip the file if it's not a binary
        .pipe(through.obj(function(file, enc, next) {
            if (!file.isBinary()) {
                next();
                return;
            }

            next(null, file);
        }))
        // output to the compiled directory
        .pipe(gulp.dest(htmlDirectory));

    // process HTML
    var html = gulp.src([src + "/**/*", "!" + src + "{/assets,/assets/**}"])
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer(htmlDirectory)))
        // check if a file is a binary
        .pipe(isBinary())
        // skip the file if it's a binary
        .pipe(through.obj(function(file, enc, next) {
            if (file.isBinary()) {
                next();
                return;
            }

            next(null, file);
        }))
        // replace variables
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
        // replace icon placeholders
        .pipe(replace(/(?:<icon:)([A-Za-z0-9\-\_][^:>]+)(?:\:([A-Za-z0-9\-\_\ ][^:>]*))?(?:>)/g, "<i class='icon'><svg class='icon_svg $2' aria-hidden='true'><use xlink:href='#$1' \/><\/svg></i>"))
        // output to the compiled directory
        .pipe(gulp.dest(htmlDirectory));

    // merge both steams back in to one
    return merge(binaries, html)
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // reload the files
        .pipe(browserSync.reload({stream: true}))
        // notify that the task is complete, if not part of default or watch
        .pipe(gulpif(gulp.seq.indexOf("html") > gulp.seq.indexOf("default"), notify({title: "Success!", message: "HTML task complete!", onLast: true})))
        // push the task to the ranTasks array
        .on("data", function() {
            if (ranTasks.indexOf("html") < 0) ranTasks.push("html");
        });
});

// config task, generate configuration file for FTP & BrowserSync and prompt dev for input
gulp.task("config", function (cb) {
    "use strict";

    // generate the config.json and start the other functions
    fs.stat("./config.json", function (err, stats) {
        if (err !== null) {
            fs.writeFile("./config.json", "{\"ftp\":{\"dev\":{\"host\":\"\",\"port\":\"21\",\"mode\":\"ftp\",\"user\":\"\",\"pass\":\"\",\"path\":\"\"},\"dist\":{\"host\":\"\",\"port\":\"21\",\"mode\":\"ftp\",\"user\":\"\",\"pass\":\"\",\"path\":\"\"}},\"browsersync\":{\"proxy\":\"localhost:8888\",\"port\":\"8080\",\"open\":\"external\",\"notify\":\"false\"}}", function (err) {
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
                    // prompt for the host
                    type: "input",
                    name: "host",
                    message: "FTP hostname:",
                    default: ftpHost,
                },
                {
                    // prompt for the port
                    type: "input",
                    name: "port",
                    message: "FTP port:",
                    default: ftpPort,
                },
                {
                    // prompt for the mode
                    type: "input",
                    name: "mode",
                    message: "FTP mode:",
                    default: ftpMode,
                },
                {
                    // prompt for the user
                    type: "input",
                    name: "user",
                    message: "FTP username:",
                    default: ftpUser,
                },
                {
                    // prompt for the host
                    type: "password",
                    name: "pass",
                    message: "FTP password:",
                    default: ftpPass,
                },
                {
                    // prompt for the path
                    type: "input",
                    name: "path",
                    message: "FTP remote path:",
                    default: ftpPath,
                }], function(res) {
                    // open the config.json
                    var file = json.read("./config.json");

                    // update the ftp settings in config.json
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

                    // write the updated file contents
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
        bsProxy = json.read("./config.json").get("browsersync.proxy");
        bsPort = json.read("./config.json").get("browsersync.port");
        bsOpen = json.read("./config.json").get("browsersync.open");
        bsNotify = json.read("./config.json").get("browsersync.notify");

        if (argv.all || (gulp.seq.indexOf("config") < gulp.seq.indexOf("sync") || argv.sync) && (argv.config || bsProxy === "" || bsPort === "" || bsOpen === "" || bsNotify === "")) {
            // reconfigure settings in config.json if a field is empty or if --config is passed
            gulp.src("./config.json")
                .pipe(prompt.prompt([{
                    // prompt for the proxy
                    type: "input",
                    name: "proxy",
                    message: "Browsersync proxy:",
                    default: bsProxy,
                },
                {
                    // prompt for the port
                    type: "input",
                    name: "port",
                    message: "Browsersync port:",
                    default: bsPort,
                },
                {
                    // prompt for how to open
                    type: "input",
                    name: "open",
                    message: "Browsersync open:",
                    default: bsOpen,
                },
                {
                    // prompt for whether to notify
                    type: "input",
                    name: "notify",
                    message: "Browsersync notify:",
                    default: bsNotify,
                }], function(res) {
                    // open the config.json
                    var file = json.read("./config.json");

                    // update the browsersync settings in config.json
                    file.set("browsersync.proxy", res.proxy);
                    file.set("browsersync.port", res.port);
                    file.set("browsersync.open", res.open);
                    file.set("browsersync.notify", res.notify);

                    // write the updated file contents
                    file.writeSync();

                    // read browsersync settings from config.json
                    bsProxy = res.proxy;
                    bsPort = res.port;
                    bsOpen = res.open;
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
    // development FTP directory
    var ftpDirectory = dev;

    // production FTP directory (if --dist is passed)
    if (argv.dist) ftpDirectory = dist;

    // Create the SFTP connection
    var sftpConn = sftp({
        host: ftpHost,
        port: ftpPort,
        username: ftpUser,
        password: ftpPass,
        remotePath: ftpPath
    });

    // create the FTP connection
    var ftpConn = ftp.create({
        host: ftpHost,
        port: ftpPort,
        secure: ftpMode === "tls" ? true : false,
        user: ftpUser,
        pass: ftpPass,
        path: ftpPath,
    });

    // upload the changed files
    return gulp.src(ftpDirectory + "/**/*")
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // check if files are newer
        .pipe(gulpif(!argv.dist, newer({dest: src, extra: [ftpDirectory + "/**/*"]})))
        // upload changed files
        .pipe(gulpif(ftpMode === "sftp", sftpConn, ftpConn.newer(ftpPath)))
        // prevent breaking on error
        .pipe(plumber({errorHandler: onError}))
        // reload the files
        .pipe(browserSync.reload({stream: true}))
        // notify that the task is complete, if not part of default or watch
        .pipe(gulpif(gulp.seq.indexOf("ftp") > gulp.seq.indexOf("default"), notify({title: "Success!", message: "FTP task complete!", onLast: true})));
});

// sync task, set up a browserSync server, depends on config
gulp.task("sync", ["config"], function(cb) {
    browserSync({
        proxy: bsProxy,
        port: bsPort,
        open: bsOpen,
        notify: bsNotify,
    });
});

// default task, runs through everything but dist
gulp.task("default", ["media", "scripts", "styles", "html"], function () {
    "use strict";

    // notify that the task is complete
    gulp.src("gulpfile.js")
        .pipe(gulpif(ranTasks.length, notify({title: "Success!", message: ranTasks.length + " task" + (ranTasks.length > 1 ? "s" : "") + " complete! [" + ranTasks.join(", ") + "]", onLast: true})));

    // trigger FTP task if FTP flag is passed
    if (argv.ftp) runSequence("ftp");

    // reset the ranTasks array
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
