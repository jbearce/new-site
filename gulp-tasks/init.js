// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

module.exports = function (gulp, plugins, envs) {
    // WIP: init task, initializes the project
    return function () {
        return gulp.src(envs.src + "/**/*")
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
        // remove login HTML code if --remove --login is passed
        .pipe(plugins.remove_code({no_login: plugins.argv.remove && plugins.argv.login ? true : false, commentStart: "<!--", commentEnd: "-->"}))
        // output to source directory
        .pipe(gulp.dest(envs.src));
    };
};
