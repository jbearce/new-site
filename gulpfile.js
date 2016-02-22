    // general stuff
var gulp = require("gulp"),                      // gulp
    fs = require("fs"),                          // the file system
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

    host = "",                                   // FTP hostname (leave blank)
    user = "",                                   // FTP username (leave blank)
    pass = "",                                   // FTP password (leave blank)
    path = "",                                   // FTP path (leave blank)

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
    return merge(media, screenshot);
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
    var concated = gulp.src([src + "/assets/scripts/vendor.*.js", src + "/assets/scripts/jquery.*.js", src + "/assets/scripts/*.js", "!" + src + "/assets/scripts/jquery.googleMaps.js"])
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
        .pipe(newer(jsDirectory))
        // output to the compiled directory
        .pipe(gulp.dest(jsDirectory + "/fallback"));

    // copy special scripts
    var special = gulp.src([src + "/assets/scripts/jquery.googleMaps.js"])
        // check if source is newer than destination
        .pipe(newer(jsDirectory))
        // output to the compiled directory
        .pipe(gulp.dest(jsDirectory));

    // merge all three steams back in to one
    return merge(linted, concated, copied, special);
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
        // check if source is newer than destination
        .pipe(newer(cssDirectory + "/all.css"))
        // initialize sourcemap
        .pipe(sourcemaps.init())
        // compile SCSS (compress if --dist is passed)
        .pipe(gulpif(argv.dist, sass({outputStyle: "compressed"}).on("error", sass.logError), sass().on("error", sass.logError)))
        // prefix CSS
        .pipe(autoprefixer("last 2 version", "ie 8", "ie 9"))
        // write the sourcemap (if --dist isn't passed)
        .pipe(gulpif(!argv.dist, sourcemaps.write()))
        // output to the compiled directory
        .pipe(gulp.dest(cssDirectory));
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
        .pipe(gulp.dest(htmlDirectory));
});

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

    watch("./src/**/*", function () {
        if (argv.ftp) {
            runSequence(["media", "scripts", "styles", "html"], "ftp");
        } else {
            runSequence(["media", "scripts", "styles", "html"]);
        }
    });
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
    host = json.read("./ftp.json").get("dev.host"),
    user = json.read("./ftp.json").get("dev.user"),
    pass = json.read("./ftp.json").get("dev.pass"),
    path = json.read("./ftp.json").get("dev.path");

    // read dist FTP credentials from ftp.json (if --dist is passed)
    if (argv.dist) {
        host = json.read("./ftp.json").get("dist.host"),
        user = json.read("./ftp.json").get("dist.user"),
        pass = json.read("./ftp.json").get("dist.pass"),
        path = json.read("./ftp.json").get("dist.path");
    }

    if (host === "" || user === "" || pass === "" || argv.config) {
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
        host: host,
        user: user,
        pass: pass,
        path: path,
    })

    // upload the changed files
    return gulp.src(ftpDirectory + "/**/*")
        .pipe(conn.newer(path))
        .pipe(conn.dest(path));

    // return
    cb(null);
});

// combine FTP tasks
gulp.task("ftp", ["ftp-upload", "ftp-config", "ftp-init"]);
