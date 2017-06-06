// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

module.exports = function (gulp, plugins) {
    // WIP: init task, initializes the project
    return function () {
        return Promise.all([
            new Promise ((resolve) => {
                // remove any specified optional modules
                gulp.src(global.settings.paths.src + "/**/*")
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
                    // remove tribe code if --remove tribe is passed
                    .pipe(plugins.remove_code({tribe_html: plugins.argv.remove && plugins.argv.remove.includes("tribe") ? true : false, commentStart: "<!--", commentEnd: "-->"}))
                    .pipe(plugins.remove_code({tribe_css: plugins.argv.remove && plugins.argv.remove.includes("tribe") ? true : false, commentStart: "/*", commentEnd: "*/"}))
                    // output to source directory
                    .pipe(gulp.dest(global.settings.paths.src))
                    .on("end", () => {
                        resolve();
                    });
            })
        ]).then(() => {
            return Promise.all([
                new Promise ((resolve) => {
                    // remove any empty files
                    plugins.glob(global.settings.paths.src + "/**/*", (err, files) => {
                        files.forEach((file) => {
                            if (plugins.fs.statSync(file).size <= 1) {
                                plugins.fs.unlinkSync(file);
                            }
                        });

                        return resolve();
                    });
                })
            ]).then(() => {
                return Promise.all([
                    // remove any empty folders
                    new Promise((resolve) => {
                        plugins.delete_empty(global.settings.paths.src + "/**/*", (err, deleted) => {
                            console.log(deleted);
                        });

                        return resolve();
                    })
                ]).then(() => {
                    // remove any remaining comments
                    return gulp.src(global.settings.paths.src + "/**/*")
                        .pipe(plugins.replace(/((?:\/\*|<!--)(?:end)?[rR]emoveIf\([^)]+\)(?:\*\/|-->))/g, ""))
                        .pipe(gulp.dest(global.settings.paths.src));
                });
            });
        });
    };
};
