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
                    },
                    {
                        name:    "repository",
                        message: "Project Repository:",
                        type:    "input",
                    },
                    {
                        name:    "author_name",
                        message: "Author Name:",
                        type:    "input",
                    },
                    {
                        name:    "author_company",
                        message: "Author Company:",
                        type:    "input",
                    },
                    {
                        name:    "author_email",
                        message: "Author Email:",
                        type:    "input",
                    },
                    {
                        name:    "author_url",
                        message: "Author URL:",
                        type:    "input",
                    },
                    {
                        name:    "theme_color",
                        message: "Theme Color:",
                        type:    "input",
                        default: "#17AAEC",
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

        return new Promise ((resolve) => {
            get_project_data(() => {
                write_project_data(() => {
                    return resolve();
                });
            });
        });
    }
};
