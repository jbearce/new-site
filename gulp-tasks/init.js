// JavaScript Document

// Scripts written by α__init_author_name @ α__init_author_company

module.exports = {
    init(gulp, plugins, on_error) {
        let project_data = {};

        // gather project data
        const get_project_data = (callback) => {
            return gulp.src("./gulpfile.js")
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // prompt for project data
                .pipe(plugins.prompt.prompt([
                    {
                        name:    "full_name",
                        message: "Project Name:",
                        type:    "input",
                    },
                    {
                        name:    "short_name",
                        message: "Project Short Name:",
                        type:    "input",
                    },
                    {
                        name:    "version",
                        message: "Project Version:",
                        type:    "input",
                        validate: (response) => {
                            if (response.match(/^(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)(-(0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(\.(0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*)?(\+[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*)?$/)) {
                                return true;
                            } else {
                                return "Please enter a valid version number (semver).";
                            }
                        },
                    },
                    {
                        name:    "description",
                        message: "Project Description:",
                        type:    "input",
                        default: "A brand new WordPress theme built just for α__init_full_name!",
                    },
                    {
                        name:    "homepage",
                        message: "Project URL:",
                        type:    "input",
                        validate: (response) => {
                            if (response.match(/^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)$/)) {
                                return true;
                            } else {
                                return "Please enter a valid URL.";
                            }
                        },
                    },
                    {
                        name:    "repository",
                        message: "Project Repository:",
                        type:    "input",
                        validate: (response) => {
                            if (response.match(/^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)$/)) {
                                return true;
                            } else {
                                return "Please enter a valid URL.";
                            }
                        },
                    },
                    {
                        name:    "author_name",
                        message: "Author Name:",
                        type:    "input",
                    },
                    {
                        name:    "author_email",
                        message: "Author Email:",
                        type:    "input",
                        validate: (response) => {
                            if (response.match(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
                                return true;
                            } else {
                                return "Please enter a valid email address.";
                            }
                        }
                    },
                    {
                        name:    "author_company",
                        message: "Author Company:",
                        type:    "input",
                    },
                    {
                        name:    "author_url",
                        message: "Author URL:",
                        type:    "input",
                        validate: (response) => {
                            if (response.match(/^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)$/)) {
                                return true;
                            } else {
                                return "Please enter a valid URL.";
                            }
                        },
                    },
                    {
                        name:    "theme_color",
                        message: "Theme Color:",
                        type:    "input",
                        validate: (response) => {
                            if (response.match(/^#(([0-9a-fA-F]{2}){3}|([0-9a-fA-F]){3})$/)) {
                                return true;
                            } else {
                                return "Please enter a valid hex color.";
                            }
                        },
                    },
                    {
                        name:    "remove_modules",
                        message: "Modules to Remove:",
                        type:    "checkbox",
                        choices: ["Resources", "Tribe Events"],
                    },
                ], (res) => {
                    // store the project data
                    project_data = res;
                })).on("end", () => {
                    // return the callback
                    if (typeof callback === "function") {
                        return callback();
                    }
                });
        };

        // write project data
        const write_project_data = (callback) => {
            return gulp.src(["./*", "./gulp-tasks/*", "./src/**/*"], {base: "./"})
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
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
                // replace variables with project data
                .pipe(plugins.file_include({
                    prefix:   "α__init_",
                    basepath: "@file",
                    context: {
                        full_name:      project_data.full_name,
                        short_name:     project_data.short_name,
                        npm_name:       project_data.full_name.toLowerCase().replace(/[^A-Za-z ]/, "").replace(/ /g, "-"),
                        namespace:      project_data.short_name.toLowerCase().replace(/[^A-Za-z ]/, "").replace(/ /g, "_"),
                        version:        project_data.version,
                        description:    project_data.description,
                        homepage_url:   project_data.homepage,
                        repository:     project_data.repository.replace(/(\.git$)|(\/$)/, ""),
                        author_name:    project_data.author_name,
                        author_company: project_data.author_company,
                        author_email:   project_data.author_email,
                        author_url:     project_data.author_url,
                        theme_color:    project_data.theme_color,
                    }
                }))
                // write the file
                .pipe(gulp.dest("./")).on("end", () => {
                    // return the callback
                    if (typeof callback === "function") {
                        return callback();
                    }
                });
        };

        // remove modules selected during init
        const remove_selected_modules = (callback) => {
            return gulp.src(global.settings.paths.src + "/**/*")
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

                // remove resource code if selected
                .pipe(plugins.remove_code({resources_html: project_data.remove_modules.indexOf("Resources") > -1 ? true : false, commentStart: "<!--", commentEnd: "-->"}))
                .pipe(plugins.remove_code({resources_css_js_php: project_data.remove_modules.indexOf("Resources") > -1 ? true : false, commentStart: "/*", commentEnd: "*/"}))

                // remove tribe code if selected
                .pipe(plugins.remove_code({tribe_html: project_data.remove_modules.indexOf("Tribe Events") > -1 ? true : false, commentStart: "<!--", commentEnd: "-->"}))
                .pipe(plugins.remove_code({tribe_css_js_php: project_data.remove_modules.indexOf("Tribe Events") > -1 ? true : false, commentStart: "/*", commentEnd: "*/"}))

                // output to source directory
                .pipe(gulp.dest(global.settings.paths.src)).on("end", () => {
                    // return the callback
                    if (typeof callback === "function") {
                        return callback();
                    }
                });
        };

        return new Promise ((resolve) => {
            get_project_data(() => {
                write_project_data(() => {
                    remove_selected_modules(() => {
                        return resolve();
                    });
                });
            });
        });
    }
};
