var gulp         = require("gulp"),
    del          = require("del"),
    include      = require("gulp-file-include"),
    sass         = require("gulp-sass"),
    autoprefixer = require("gulp-autoprefixer"),
    minify       = require("gulp-minify-css"),
    concat       = require("gulp-concat"),
    uglify       = require("gulp-uglify"),
    image        = require("gulp-image");

gulp.task("clean", function(cb) {
    del(["dist/**/*.*"], cb);
});

gulp.task("inc", function() {
    gulp.src("./src/*.*")
    .pipe(include({
        prefix: "@@",
        basepath: "@file"
    }))
    .pipe(gulp.dest("./dist/"));
});

gulp.task("css", function() {
    gulp.src("./src/assets/scss/*.scss")
    .pipe(sass())
    .pipe(autoprefixer("last 2 version", "> 5%"))
    .pipe(minify())
    .pipe(gulp.dest("./dist/assets/css"));
});

gulp.task("js", function() {
    gulp.src([
        "./src/assets/js/vendors/jquery-*.js",
        "./src/assets/js/vendors/modernizr.custom.*.js",
        "./src/assets/js/vendors/nwmatcher-*.js",
        "./src/assets/js/vendors/selectivizr-*.js",
        "./src/assets/js/vendors/scrollfix.js",
        "./src/assets/js/*.js"
    ])
    .pipe(concat("scripts.js"))
    //.pipe(uglify()) // doesn't work right with selectivizr
    .pipe(gulp.dest("./dist/assets/js"));
});

gulp.task("img", function() {
    gulp.src("./src/assets/img/*.*")
    .pipe(image())
    .pipe(gulp.dest("./dist/assets/img"));
});

gulp.task("default", ["clean"], function() {
    gulp.start(["inc", "css", "js", "img"]);
});

gulp.task("theme", function() {
    gulp.src("./dist/assets/**/*.*")
    .pipe(gulp.dest("./theme/assets/"));
})

gulp.task("watch", function() {
    gulp.watch("./src/includes/**/*.*", ["inc"]);
    gulp.watch("./src/assets/scss/**/*.scss", ["css"]);
    gulp.watch("./src/assets/js/**/*.js", ["js"]);
    gulp.watch("./src/assets/img/**/*", ["img"]);
    livereload.listen();
    gulp.watch(["./dist/**"]).on("change", livereload.changed);
});

