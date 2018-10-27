// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    html(gulp, plugins, ran_tasks, on_error) {
        // task-specific plugins
        const template = require("gulp-template");

        // copy binaries
        const copy_binaries = (html_directory, source = [global.settings.paths.src + "/**/*", "!" + global.settings.paths.src + "{/assets,/assets/**}"]) => {
            return gulp.src(source)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // check if source is newer than destination
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(html_directory)))
                // check if a file is a binary
                .pipe(plugins.is_binary())
                // skip file if it's not a binary
                .pipe(plugins.through.obj((file, enc, next) => {
                    if (!file.isBinary()) {
                        next();
                        return;
                    }

                    // go to next file
                    next(null, file);
                }))
                // output to compiled directory
                .pipe(gulp.dest(html_directory));
        };

        // process HTML
        const process_html = (html_directory, source = [global.settings.paths.src + "/**/*", "!" + global.settings.paths.src + "{/assets,/assets/**}"]) => {
            // read data from package.json
            const name            = plugins.json.readFileSync("./package.json").name;
            const pwa_name        = plugins.json.readFileSync("./package.json").progressiveWebApp.name;
            const pwa_short_name  = plugins.json.readFileSync("./package.json").progressiveWebApp.short_name;
            const pwa_theme_color = plugins.json.readFileSync("./package.json").progressiveWebApp.theme_color;
            const description     = plugins.json.readFileSync("./package.json").description;
            const version         = plugins.json.readFileSync("./package.json").version;
            const repository      = plugins.json.readFileSync("./package.json").repository.url;
            const license         = plugins.json.readFileSync("./package.json").license;

            return gulp.src(source)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // check if source is newer than destination
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(html_directory)))
                // check if file is a binary
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
                .pipe(template({
                    name,
                    pwa_name,
                    pwa_short_name,
                    pwa_theme_color,
                    description,
                    version,
                    repository,
                    license,
                }))
                // output to compiled directory
                .pipe(gulp.dest(html_directory));
        };

        // html task, copies binaries, converts includes & variables in HTML
        return new Promise ((resolve) => {
            // set HTML directory
            const html_directory = plugins.argv.dist ? global.settings.paths.dist : global.settings.paths.dev;

            // process all non-asset files
            const binaries = copy_binaries(html_directory, [global.settings.paths.src + "/**/*", "!" + global.settings.paths.src + "{/assets,/assets/**}"]);
            const html     = process_html(html_directory, [global.settings.paths.src + "/**/*", "!" + global.settings.paths.src + "{/assets,/assets/**}"]);

            // merge both steams back in to one
            plugins.merge(binaries, html)
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // notify that task is complete, if not part of default or watch
                .pipe(plugins.gulpif(gulp.seq.indexOf("html") > gulp.seq.indexOf("default"), plugins.notify({title: "Success!", message: "HTML task complete!", onLast: true})))
                // push task to ran_tasks array
                .on("data", () => {
                    if (ran_tasks.indexOf("html") < 0) {
                        ran_tasks.push("html");
                    }
                })
                .on("end", () => {
                    resolve();
                });
        });
    }
};
