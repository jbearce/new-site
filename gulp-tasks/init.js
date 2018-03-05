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
                        name:     "full_name",
                        message:  "Project Name:",
                        type:     "input",
                        validate: (response) => {
                            if (response.trim().length > 0 && response.match(/\w/)) {
                                return true;
                            } else {
                                return "Please enter a full name.";
                            }
                        },
                    },
                    {
                        name:     "short_name",
                        message:  "Project Short Name:",
                        type:     "input",
                        validate: (response) => {
                            if (response.trim().length > 0 && response.match(/\w/)) {
                                return true;
                            } else {
                                return "Please enter a short name.";
                            }
                        },
                    },
                    {
                        name:     "version",
                        message:  "Project Version:",
                        type:     "input",
                        default:  "0.1.0",
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
                        validate: (response) => {
                            if (response.trim().length > 0 && response.match(/\w/)) {
                                return true;
                            } else {
                                return "Please enter a description.";
                            }
                        },
                    },
                    {
                        name:     "homepage",
                        message:  "Project URL:",
                        type:     "input",
                        validate: (response) => {
                            if (response.match(/^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)$/)) {
                                return true;
                            } else {
                                return "Please enter a valid URL.";
                            }
                        },
                    },
                    {
                        name:     "repository",
                        message:  "Project Repository:",
                        type:     "input",
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
                        validate: (response) => {
                            if (response.trim().length > 0 && response.match(/\w/)) {
                                return true;
                            } else {
                                return "Please enter a name.";
                            }
                        },
                    },
                    {
                        name:     "author_email",
                        message:  "Author Email:",
                        type:     "input",
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
                        validate: (response) => {
                            if (response.trim().length > 0 && response.match(/\w/)) {
                                return true;
                            } else {
                                return "Please enter a company.";
                            }
                        },
                    },
                    {
                        name:     "author_url",
                        message:  "Author URL:",
                        type:     "input",
                        validate: (response) => {
                            if (response.match(/^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_\+.~#?&//=]*)$/)) {
                                return true;
                            } else {
                                return "Please enter a valid URL.";
                            }
                        },
                    },
                    {
                        name:    "remove_modules",
                        message: "Modules to Remove:",
                        type:    "checkbox",
                        choices: ["Resources", "Tribe Events"],
                    },
                    {
                        name:     "theme_color",
                        message:  "Theme Color:",
                        type:     "input",
                        validate: (response) => {
                            if (response.match(/^#(([0-9a-fA-F]{2}){3}|([0-9a-fA-F]){3})$/)) {
                                return true;
                            } else {
                                return "Please enter a valid hex color.";
                            }
                        },
                    },
                    {
                        name:    "heading_font",
                        message: "Heading Font:",
                        type:    "input",
                        default: "\"Open Sans\", \"Arial\", \"Helvetica\", sans-serif",
                    },
                    {
                        name:    "body_font",
                        message: "Body Font:",
                        type:    "input",
                        default: "\"Open Sans\", \"Arial\", \"Helvetica\", sans-serif",
                    },
                    {
                        name:     "site_width",
                        message:  "Site Width:",
                        type:     "input",
                        default:  "1500",
                        validate: (response) => {
                            if (response.match(/[0-9]+/)) {
                                return true;
                            } else {
                                return "Please enter a valid positive integer.";
                            }
                        },
                    },
                    {
                        name:     "column_gap",
                        message:  "Column Gap:",
                        type:     "input",
                        default:  "30",
                        validate: (response) => {
                            if (response.match(/[0-9]+/)) {
                                return true;
                            } else {
                                return "Please enter a valid positive integer.";
                            }
                        },
                    },
                    {
                        name:     "content_padding",
                        message:  "Content Padding:",
                        type:     "input",
                        default:  "25",
                        validate: (response) => {
                            if (response.match(/[0-9]+/)) {
                                return true;
                            } else {
                                return "Please enter a valid positive integer.";
                            }
                        },
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
                        full_name:       project_data.full_name,
                        short_name:      project_data.short_name,
                        npm_name:        project_data.full_name.toLowerCase().replace(/[^A-Za-z ]/, "").replace(/ /g, "-"),
                        namespace:       project_data.short_name.toLowerCase().replace(/[^A-Za-z ]/, "").replace(/ /g, "_"),
                        version:         project_data.version,
                        description:     project_data.description,
                        homepage_url:    project_data.homepage,
                        repository:      project_data.repository.replace(/(\.git$)|(\/$)/, ""),
                        author_name:     project_data.author_name,
                        author_email:    project_data.author_email,
                        author_company:  project_data.author_company,
                        author_url:      project_data.author_url,
                        theme_color:     project_data.theme_color,
                        heading_font:    project_data.heading_font,
                        body_font:       project_data.body_font,
                        site_width:      project_data.site_width,
                        column_gap:      project_data.column_gap,
                        content_padding: project_data.content_padding,
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
            return new Promise ((resolve) => {
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

                    // remove resource code if selected
                    .pipe(plugins.remove_code({resources_html: project_data.remove_modules.indexOf("Resources") > -1 ? true : false, commentStart: "<!--", commentEnd: "-->"}))
                    .pipe(plugins.remove_code({resources_css_js_php: project_data.remove_modules.indexOf("Resources") > -1 ? true : false, commentStart: "/*", commentEnd: "*/"}))

                    // remove tribe code if selected
                    .pipe(plugins.remove_code({tribe_html: project_data.remove_modules.indexOf("Tribe Events") > -1 ? true : false, commentStart: "<!--", commentEnd: "-->"}))
                    .pipe(plugins.remove_code({tribe_css_js_php: project_data.remove_modules.indexOf("Tribe Events") > -1 ? true : false, commentStart: "/*", commentEnd: "*/"}))

                    // output to source directory
                    .pipe(gulp.dest(global.settings.paths.src)).on("end", () => {
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
                                .pipe(gulp.dest(global.settings.paths.src))
                                .on("end", () => {
                                    // return the callback
                                    if (typeof callback === "function") {
                                        return callback();
                                    }
                                });
                        });
                    });
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
