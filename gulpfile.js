var gulp = require("gulp");
var removeFiles = require("gulp-remove-files");

gulp.task("default", function() {
});

gulp.task("update-static", function() {
    // copy assets
    gulp.src("./_static/assets/**/*.*")
    .pipe(removeFiles());
    gulp.src("./assets/**/*.*")
    .pipe(gulp.dest("./_static/assets"));
});
