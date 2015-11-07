"use strict";

var gulp = require("gulp"),
    json = require("json-file"),

    themeName = json.read("./package.json").get("name"),
    themeVersion = json.read("./package.json").get("version"),
    themeDescription = json.read("./package.json").get("description"),
    themeRepository = json.read("./package.json").get("repository"),
    themeLicense = json.read("./package.json").get("license"),
    themeColor = "#1664A7",

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
    gls = require("gulp-live-server"),
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
    return gulp.src(["!./src/assets", "./src/**/*"])
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

// default task, builds to dev
gulp.task("default", function (callback) {
    runSequence("clean", "styles", "scripts", "media", "php", callback);
});

// uglify and populate dist
gulp.task("build", function (callback) {
    runSequence("clean", "styles", "scripts", "media", "php", "dist", callback);
});

// watch task, runs server, executes default task & updates server on file chagne
gulp.task("watch", function() {
    var server = gls.static("./dev");
    server.start();

    gulp.watch("./src/**/*", function (callback) {
        runSequence("default", function() {
            server.notify.apply(server, [callback]);
        });
    });
});
