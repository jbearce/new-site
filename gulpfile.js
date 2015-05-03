var gulp    = require("gulp"),
    sass    = require("gulp-sass"),
    minify  = require("gulp-minify-css"),
    concat  = require("gulp-concat"),
    uglify  = require("gulp-uglify");

gulp.task("css", function() {
    gulp.src("./assets/scss/*.scss")
    .pipe(sass())
    .pipe(minify())
    .pipe(gulp.dest("./assets/css"));
});

gulp.task("js", function() {
    gulp.src([
        "./assets/js/vendors/jquery-*.js",
        "./assets/js/vendors/modernizr.custom.*.js",
        "./assets/js/vendors/nwmatcher-*.js",
        "./assets/js/vendors/selectivizr-*.js",
        "./assets/js/vendors/scrollfix.js",
        "./assets/js/*.js"
    ])
    .pipe(concat("scripts.js"))
    //.pipe(uglify())
    .pipe(gulp.dest("./assets/js"));
});


gulp.task("default", ["css", "js"]);
