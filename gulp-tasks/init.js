// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

module.exports = (gulp, plugins) => {
    // WIP: init task, initializes the project
    return () => {
        return new Promise ((resolve) => {
            // remove any specified optional modules
            gulp.src(global.settings.paths.src + "/**/*")
                // check if a file is a binary
                .pipe(plugins.is_binary())
                // skip file if it's a binary
                .pipe(plugins.through.obj((file, enc, next) => {
                    if (file.isBinary()) {
                        next();
                        return;
                    }

                    // go to next file
                    next(null, file);
                }))
                // remove resource code if --remove resources is passed
                .pipe(plugins.remove_code({resources_html: plugins.argv.remove && plugins.argv.remove.includes("resources") ? true : false, commentStart: "<!--", commentEnd: "-->"}))
                .pipe(plugins.remove_code({resources_css_js_php: plugins.argv.remove && plugins.argv.remove.includes("resources") ? true : false, commentStart: "/*", commentEnd: "*/"}))
                // remove tribe code if --remove tribe is passed
                .pipe(plugins.remove_code({tribe_html: plugins.argv.remove && plugins.argv.remove.includes("tribe") ? true : false, commentStart: "<!--", commentEnd: "-->"}))
                .pipe(plugins.remove_code({tribe_css_js_php: plugins.argv.remove && plugins.argv.remove.includes("tribe") ? true : false, commentStart: "/*", commentEnd: "*/"}))
                // output to source directory
                .pipe(gulp.dest(global.settings.paths.src))
                // resolve the promise
                .on("end", () => {
                    // resolve the promise
                    resolve();
                });
        }).then(() => {
            return new Promise ((resolve) => {
                // remove any empty files
                plugins.glob(global.settings.paths.src + "/**/*", (err, files) => {
                    files.forEach((file) => {
                        if (plugins.fs.statSync(file).size <= 1) {
                            plugins.fs.unlinkSync(file);
                            console.log("\x1b[32m✔\x1b[0m deleted: " + plugins.path.relative(process.cwd(), file));
                        }
                    });

                    // resolve the promise
                    resolve();
                });
            }).then(() => {
                // remove any empty folders
                return new Promise ((resolve) => {
                    plugins.delete_empty(global.settings.paths.src, () => {
                        // resolve the promise
                        resolve();
                    });
                }).then(() => {
                    // remove any remaining comments
                    gulp.src(global.settings.paths.src + "/**/*")
                        // check if a file is a binary
                        .pipe(plugins.is_binary())
                        // skip file if it's a binary
                        .pipe(plugins.through.obj((file, enc, next) => {
                            if (file.isBinary()) {
                                next();
                                return;
                            }

                            // go to next file
                            next(null, file);
                        }))
                        .pipe(plugins.replace(/((?:\/\*|<!--)(?:end)?[rR]emoveIf\([^)]+\)(?:\*\/|-->))/g, ""))
                        .pipe(gulp.dest(global.settings.paths.src));
                });
            });
        });
    };
};
