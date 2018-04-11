// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

module.exports = {
    init(GULP, PLUGINS, ON_ERROR) {
        let project_data = {};

        // gather project data
        const GET_PROJECT_DATA = (callback) => {
            return GULP.src("./gulpfile.js")
                // prevent breaking on error
                .pipe(PLUGINS.plumber({errorHandler: ON_ERROR}))
                // prompt for project data
                .pipe(PLUGINS.prompt.prompt([
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
                            if (response.match(/^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_+.~#?&//=]*)$/)) {
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
                            if (response.match(/^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_+.~#?&//=]*)$/)) {
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
                            if (response.match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
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
                            if (response.match(/^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_+.~#?&//=]*)$/)) {
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
                        default:  "#17AAEC",
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
                        default: "\"Open Sans\", \"Helvetica\", \"Arial\", sans-serif",
                    },
                    {
                        name:    "body_font",
                        message: "Body Font:",
                        type:    "input",
                        default: "\"Open Sans\", \"Helvetica\", \"Arial\", sans-serif",
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
        const WRITE_PROJECT_DATA = (callback) => {
            return GULP.src(["./*", "./gulp-tasks/*", "./src/**/*"], {base: "./"})
                // prevent breaking on error
                .pipe(PLUGINS.plumber({errorHandler: ON_ERROR}))
                // check if a file is a binary
                .pipe(PLUGINS.is_binary())
                // skip file if it's a binary
                .pipe(PLUGINS.through.obj((file, enc, next) => {
                    if (file.isBinary()) {
                        next();
                        return;
                    }

                    // go to next file
                    next(null, file);
                }))
                // replace variables with project data
                .pipe(PLUGINS.file_include({
                    prefix:   "__gulp_init__",
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
                .pipe(GULP.dest("./")).on("end", () => {
                    // return the callback
                    if (typeof callback === "function") {
                        return callback();
                    }
                });
        };

        // remove modules selected during init
        const REMOVE_SELECTED_MODULES = (callback) => {
            return new Promise ((resolve) => {
                GULP.src(global.settings.paths.src + "/**/*")
                    // check if a file is a binary
                    .pipe(PLUGINS.is_binary())
                    // skip file if it's a binary
                    .pipe(PLUGINS.through.obj((file, enc, next) => {
                        if (file.isBinary()) {
                            next();
                            return;
                        }

                        // go to next file
                        next(null, file);
                    }))

                    // remove resource code if selected
                    .pipe(PLUGINS.remove_code({resources_html: project_data.remove_modules.indexOf("Resources") > -1 ? true : false, commentStart: "<!--", commentEnd: "-->"}))
                    .pipe(PLUGINS.remove_code({resources_css_js_php: project_data.remove_modules.indexOf("Resources") > -1 ? true : false, commentStart: "/*", commentEnd: "*/"}))

                    // remove tribe code if selected
                    .pipe(PLUGINS.remove_code({tribe_html: project_data.remove_modules.indexOf("Tribe Events") > -1 ? true : false, commentStart: "<!--", commentEnd: "-->"}))
                    .pipe(PLUGINS.remove_code({tribe_css_js_php: project_data.remove_modules.indexOf("Tribe Events") > -1 ? true : false, commentStart: "/*", commentEnd: "*/"}))

                    // output to source directory
                    .pipe(GULP.dest(global.settings.paths.src)).on("end", () => {
                        // resolve the promise
                        resolve();
                    });
            }).then(() => {
                return new Promise ((resolve) => {
                    // remove any empty files
                    PLUGINS.glob(global.settings.paths.src + "/**/*", (err, files) => {
                        files.forEach((file) => {
                            if (PLUGINS.fs.statSync(file).size <= 1) {
                                PLUGINS.fs.unlinkSync(file);
                                console.log("\x1b[32m✔\x1b[0m deleted: " + PLUGINS.path.relative(process.cwd(), file));
                            }
                        });

                        // resolve the promise
                        resolve();
                    });
                }).then(() => {
                    // remove any empty folders
                    return new Promise ((resolve) => {
                        PLUGINS.delete_empty(global.settings.paths.src, () => {
                            // resolve the promise
                            resolve();
                        });
                    }).then(() => {
                        // remove any remaining comments
                        GULP.src(global.settings.paths.src + "/**/*")
                            // check if a file is a binary
                            .pipe(PLUGINS.is_binary())
                            // skip file if it's a binary
                            .pipe(PLUGINS.through.obj((file, enc, next) => {
                                if (file.isBinary()) {
                                    next();
                                    return;
                                }

                                // go to next file
                                next(null, file);
                            }))
                            // for comments that are on their own line, remove the entire line, otherwise just delete the comment
                            .pipe(PLUGINS.replace(/(^((?:\/\*|<!--)(?:end)?[rR]emoveIf\([^)]+\)(?:\*\/|-->))\n$)|(((?:\/\*|<!--)(?:end)?[rR]emoveIf\([^)]+\)(?:\*\/|-->)))/gm, ""))
                            .pipe(GULP.dest(global.settings.paths.src))
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
            GET_PROJECT_DATA(() => {
                WRITE_PROJECT_DATA(() => {
                    REMOVE_SELECTED_MODULES(() => {
                        return resolve();
                    });
                });
            });
        });
    }
};
