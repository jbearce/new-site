// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

module.exports = function (gulp, plugins) {
    // WIP: init task, initializes the project
    return function () {
        return gulp.src(global.settings.paths.src + "/**/*")
        // check if a file is a binary
        .pipe(plugins.is_binary())
        // skip file if it's a binary
        .pipe(plugins.through.obj(function (file, enc, next) {
            if (file.isBinary()) {
                next();
                return;
            }

            // go to next file
            next(null, file);
        }))
        // remove login code if --remove login is passed
        .pipe(plugins.remove_code({login_html: plugins.argv.remove && plugins.argv.remove.includes("login") ? true : false, commentStart: "<!--", commentEnd: "-->"}))
        .pipe(plugins.remove_code({login_css: plugins.argv.remove && plugins.argv.remove.includes("login") ? true : false, commentStart: "/*", commentEnd: "*/"}))
        // output to source directory
        .pipe(gulp.dest(global.settings.paths.src));
    };
};
