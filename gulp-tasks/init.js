// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

module.exports = {
    init(gulp, plugins, on_error) {
        let defaults     = {};
        let project_data = {};

        const get_default_data = (file_name = ".init") => {
            return new Promise((resolve) => {
                // open the file
                plugins.fs.stat(file_name, (err) => {
                    // make sure the file doesn't exist (or otherwise has an error)
                    if (err === null) {
                        defaults = plugins.json.readFileSync(file_name);
                        return resolve();
                    } else {
                        // immediately resolve if file doesn't exist
                        return resolve();
                    }
                });
            });
        };

        // gather project data
        const get_project_data = () => {
            return new Promise((resolve) => {
                return gulp.src("./gulpfile.js")
                    // prevent breaking on error
                    .pipe(plugins.plumber({errorHandler: on_error}))
                    // prompt for project data
                    .pipe(plugins.prompt.prompt([
                        {
                            name:     "full_name",
                            message:  "Project Name:",
                            type:     "input",
                            default:  "full_name" in defaults ? defaults.full_name : "",
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
                            default:  "short_name" in defaults ? defaults.short_name : "",
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
                            default:  "version" in defaults ? defaults.version : "0.1.0",
                            validate: (response) => {
                                if (response.match(/^(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)(-(0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*)(\.(0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*)?(\+[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*)?$/)) {
                                    return true;
                                } else {
                                    return "Please enter a valid version number (semver).";
                                }
                            },
                        },
                        {
                            name:     "description",
                            message:  "Project Description:",
                            type:     "input",
                            default:  "description" in defaults ? defaults.description : "",
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
                            default:  "homepage" in defaults ? defaults.homepage : "",
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
                            default:  "repository" in defaults ? defaults.repository : "",
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
                            default:  "author_name" in defaults ? defaults.author_name : "",
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
                            default:  "author_email" in defaults ? defaults.author_email : "",
                            validate: (response) => {
                                if (response.match(/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/)) {
                                    return true;
                                } else {
                                    return "Please enter a valid email address.";
                                }
                            }
                        },
                        {
                            name:     "author_company",
                            message:  "Author Company:",
                            type:     "input",
                            default:  "author_company" in defaults ? defaults.author_company : "",
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
                            default:  "author_url" in defaults ? defaults.author_url : "",
                            validate: (response) => {
                                if (response.match(/^https?:\/\/(www\.)?[-a-zA-Z0-9@:%._+~#=]{2,256}\.[a-z]{2,6}\b([-a-zA-Z0-9@:%_+.~#?&//=]*)$/)) {
                                    return true;
                                } else {
                                    return "Please enter a valid URL.";
                                }
                            },
                        },
                        {
                            name:     "theme_color",
                            message:  "Theme Color:",
                            type:     "input",
                            default:  "theme_color" in defaults ? defaults.theme_color : "#17AAEC",
                            validate: (response) => {
                                if (response.match(/^#(([0-9a-fA-F]{2}){3}|([0-9a-fA-F]){3})$/)) {
                                    return true;
                                } else {
                                    return "Please enter a valid hex color.";
                                }
                            },
                        },
                    ], (res) => {
                        // store the project data
                        project_data = res;
                    }))
                    // resolve the promise
                    .on("end", () => {
                        resolve();
                    });
            });
        };

        // write project data
        const write_project_data = () => {
            return new Promise((resolve) => {
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
                    .pipe(gulp.dest("./"))
                    // resolve the promise
                    .on("end", () => {
                        return resolve();
                    });
            });
        };

        return new Promise ((resolve) => {
            get_default_data().then(() => {
                return get_project_data();
            }).then(() => {
                return write_project_data();
            }).then(() => {
                return resolve();
            });
        });
    }
};
