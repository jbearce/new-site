var gulp = require("gulp"),
    removeFiles = require("gulp-remove-files"),
    watch = require("gulp-watch");

gulp.task("default", function() {
    gulp.watch("./assets/**/*.*", function() {
        gulp.run("update-static");
    });
});

gulp.task("update-static", function() {
    // copy assets
    gulp.src("./_static/assets/**/*.*")
    .pipe(removeFiles());
    gulp.src("./assets/**/*.*")
    .pipe(gulp.dest("./_static/assets"));
});
