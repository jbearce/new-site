// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    init(gulp, plugins, onError) {
        const replace = require("gulp-replace");

        let projectData = {};

        const getProjectDefaults = () => {
            return new Promise((resolve) => {
                if (plugins.fs.existsSync(".config/.init")) {
                    projectData = plugins.json.readFileSync(".config/.init");
                }

                resolve();
            });
        };

        // gather project data
        const getProjectData = () => {
            return new Promise((resolve) => {
                return gulp.src("gulpfile.js")
                    // prevent breaking on error
                    .pipe(plugins.plumber({errorHandler: onError}))
                    // prompt for project data if defaults are not set
                    .pipe(plugins.gulpif(Object.getOwnPropertyNames(projectData).length === 0, plugins.prompt.prompt([
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
                            name:     "description",
                            message:  "Project Description:",
                            type:     "input",
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
                            name:     "author_name",
                            message:  "Author Name:",
                            type:     "input",
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
                            },
                        },
                        {
                            name:     "author_company",
                            message:  "Author Company:",
                            type:     "input",
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
                    ], (res) => {
                        // store the project data
                        projectData = res;
                    })))
                    // consume the stream the stream
                    .on("data", () => {
                        // do nothing
                    })
                    // resolve the promise
                    .on("end", () => {
                        resolve();
                    });
            });
        };

        // write project data
        const writeProjectData = () => {
            return new Promise((resolve, reject) => {
                return gulp.src(["*", "gulp-tasks/*", "src/**/*"], {base: "./"})
                    // prevent breaking on error
                    .pipe(plugins.plumber({errorHandler: onError}))
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
                    // replace variables
                    .pipe(replace(/__gulp_init_[a-z0-9_]+?__/g, (match) =>{
                        const KEYWORD = match.match(/__gulp_init_([a-z0-9_]+?)__/)[1];

                        const REPLACEMENTS = {
                            full_name:      projectData.full_name,
                            short_name:     projectData.short_name,
                            npm_name:       projectData.full_name.toLowerCase().replace(/[^A-Za-z ]/, "").replace(/ /g, "-"),
                            namespace:      projectData.short_name.toLowerCase().replace(/[^A-Za-z ]/, "").replace(/ /g, "_"),
                            version:        projectData.version,
                            description:    projectData.description,
                            homepage_url:   projectData.homepage,
                            repository:     projectData.repository.replace(/(\.git$)|(\/$)/, ""),
                            author_name:    projectData.author_name,
                            author_email:   projectData.author_email,
                            author_company: projectData.author_company,
                            author_url:     projectData.author_url,
                            theme_color:    projectData.theme_color,
                        };

                        if (KEYWORD in REPLACEMENTS) {
                            return REPLACEMENTS[KEYWORD];
                        } else {
                            console.log(`Error! ${KEYWORD} not found in provided project data!`);
                            return reject();
                        }
                    }))
                    // write the file
                    .pipe(gulp.dest("./"))
                    // resolve the promise
                    .on("end", () => {
                        resolve();
                    });
            });
        };

        return new Promise((resolve) => {
            getProjectDefaults().then(() => {
                return getProjectData();
            }).then(() => {
                return writeProjectData();
            }).then(() => {
                resolve();
            });
        });
    },
};
