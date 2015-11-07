"use strict";

var gulp = require("gulp"),
    json = require("json-file"),

    themeName = json.read("./package.json").get("name"),
    themeVersion = json.read("./package.json").get("version"),
    themeDescription = json.read("./package.json").get("description"),
    themeRepository = json.read("./package.json").get("repository"),
    themeLicense = json.read("./package.json").get("license"),
    themeColor = "#1664A7",

    devHost = json.read("./ftp.json").get("dev.host"),
    devUser = json.read("./ftp.json").get("dev.user"),
    devPass = json.read("./ftp.json").get("dev.pass"),
    devPath = json.read("./ftp.json").get("dev.path"),

    distHost = json.read("./ftp.json").get("dist.host"),
    distUser = json.read("./ftp.json").get("dist.user"),
    distPass = json.read("./ftp.json").get("dist.pass"),
    distPath = json.read("./ftp.json").get("dist.path"),

    sourcemaps = require("gulp-sourcemaps"),
    autoprefixer = require("gulp-autoprefixer"),
    sass = require("gulp-sass"),
    jshint = require("gulp-jshint"),
    concat = require("gulp-concat"),
    imagemin = require("gulp-imagemin"),
    pngquant = require("imagemin-pngquant"),
    fileinclude = require("gulp-file-include"),
    uglify = require("gulp-uglify"),
    runSequence = require("run-sequence"),
    argv = require("yargs").argv,
    ftp = require("vinyl-ftp"),
    watch = require("gulp-watch"),
    batch = require("gulp-batch"),
    del = require("del");

// delete dev & dist directories
gulp.task("clean", function() {
    del(["./dev/", "./dist/"]);
});

// compile and autoprefix styles
gulp.task("styles", function () {
    return gulp.src("./src/assets/styles/all.scss")
        // compile SASS
        .pipe(sourcemaps.init())
        .pipe(sass({outputStyle: "expanded"}).on("error", sass.logError))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest("./dev/assets/styles/"))

        // autoprefix
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(autoprefixer("last 2 version", "ie 8", "ie 9"))
        .pipe(sourcemaps.write("./"))
        .pipe(gulp.dest("./dev/assets/styles/"))
});

// lint and concat scripts
gulp.task("scripts", function () {
    // lint
    gulp.src(["!./src/assets/scripts/jquery.min.js", "!./src/assets/scripts/modernizr.custom.min.js", "!./src/assets/scripts/swiper.jquery.min.js", "!./src/assets/scripts/scrollfix.js", "./src/assets/scripts/*.js"])
        .pipe(jshint())
        .pipe(jshint.reporter("default"))

    // concat
    gulp.src(["./src/assets/scripts/jquery.min.js", "./src/assets/scripts/modernizr.custom.min.js", "./src/assets/scripts/scrollfix.js", "./src/assets/scripts/*.js"])
        .pipe(sourcemaps.init())
        .pipe(concat("all.js"))
        .pipe(sourcemaps.write("./"))
        .pipe(gulp.dest("./dev/assets/scripts/"))

    // copy fallbacks
    gulp.src("./src/assets/scripts/fallback/*")
        .pipe(gulp.dest("./dev/assets/scripts/fallback/"))
});

// compress images
gulp.task("media", function () {
    return gulp.src("./src/assets/media/**/*")
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        }))
        .pipe(gulp.dest("./dev/assets/media/"));
});

// add version number in PHP
gulp.task("php", function () {
    return gulp.src(["./src/**/*",  "!./src/{assets,assets/**}"])
        .pipe(fileinclude({
            prefix: "@@",
            basepath: "@file",
            context: {
                name: themeName,
                version: themeVersion,
                description: themeDescription,
                repository: themeRepository,
                license: themeLicense,
                color: themeColor,
            }
        }))
        .pipe(gulp.dest("./dev/"));
});

// distribute to dist
gulp.task("dist", function () {
    // compress styles
    gulp.src("./dev/assets/styles/all.css")
        .pipe(sass({outputStyle: "compressed"}))
        .pipe(gulp.dest("./dist/assets/styles/"))

    // compress scripts
    gulp.src("./dev/assets/scripts/all.js")
        .pipe(uglify())
        .pipe(gulp.dest("./dist/assets/scripts/"))

    // copy compressed media
    gulp.src("./dev/assets/media/*")
        .pipe(gulp.dest("./dist/assets/media/"))

    // copy PHP
    gulp.src(["!./dev/assets", "./dev/**/*"])
        .pipe(gulp.dest("./dist/"))
});

// upload to FTP environment
gulp.task("ftp", function() {
    if (argv.dist) {
        var conn = ftp.create({
                host: distHost,
                user: distUser,
                pass: distPass,
            })

        return gulp.src("./dist/**/*")
            .pipe(conn.newer(distPath))
            .pipe(conn.dest(distPath));
    } else {
        var conn = ftp.create({
                host: devHost,
                user: devUser,
                pass: devPass,
            })

        return gulp.src("./dev/**/*")
            .pipe(conn.newer(devPath))
            .pipe(conn.dest(devPath));
    }
})

// default task, builds to dev
gulp.task("default", function (callback) {
    runSequence("clean", "styles", "scripts", "media", "php", callback);
});

// uglify and populate dist
gulp.task("build", function (callback) {
    runSequence("clean", "styles", "scripts", "media", "php", "dist", callback);
});

// watch task, executes default task & updates server on file chagne
gulp.task("watch", function () {
    watch("./src/**/*", batch(function (events, callback) {
        runSequence("default", "ftp", callback);
    }));
});
