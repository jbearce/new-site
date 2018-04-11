// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

module.exports = {
    styles(GULP, PLUGINS, RAN_TASKS, ON_ERROR) {
        // get the homepage from the package.json
        const HOMEPAGE = PLUGINS.json.readFileSync("./package.json").homepage;

        // function to generate critical CSS
        const GENERATE_CRITICAL_CSS = (CSS_DIRECTORY, sitemap = PLUGINS.json.readFileSync("./package.json").templates) => {
            console.log("Genearting critical CSS, this may take up to " + ((Object.keys(sitemap).length * 30) / 60) + " minute" + (((Object.keys(sitemap).length * 30) / 60) !== 1 ? "s" : "") + ", go take a coffee break.");

            // create the "critical" directory
            PLUGINS.mkdirp(CSS_DIRECTORY + "/critical");

            // loop through all the links
            for (const TEMPLATE in sitemap) {
                // make sure the key isn't a prototype
                if (sitemap.hasOwnProperty(TEMPLATE)) {
                    // generate the critial CSS
                    PLUGINS.critical.generate({
                        base:       CSS_DIRECTORY + "/critical",
                        dest:       TEMPLATE + ".css",
                        dimensions: [1920, 1080],
                        minify:     true,
                        src:        sitemap[TEMPLATE] + "?disable=critical_css"
                    });
                }
            }
        };

        // lint custom styles
        const LINT_STYLES = (CSS_DIRECTORY, file_name = "modern.css", source = [global.settings.paths.src + "/assets/styles/**/*.scss", "!" + global.settings.paths.src + "/assets/styles/vendor/**/*"], extra = [global.settings.paths.src + "/assets/styles/**/*.scss"]) => {
            return GULP.src(source)
                // check if source is newer than destination
                .pipe(PLUGINS.gulpif(!PLUGINS.argv.dist, PLUGINS.newer({dest: CSS_DIRECTORY + "/" + file_name, extra})))
                // lint
                .pipe(PLUGINS.stylelint({
                    failAfterError: true,
                    reporters: [
                        { formatter: "string", console: true }
                    ],
                    debug: true
                }));
        };

        // process all SCSS in root styles directory
        const PROCESS_STYLES = (CSS_DIRECTORY, file_name = "modern.css", source = [global.settings.paths.src + "/assets/styles/*.scss"], extra = [global.settings.paths.src + "/assets/styles/**/*.scss"]) => {
            return GULP.src(source)
                // prevent breaking on error
                .pipe(PLUGINS.plumber({errorHandler: ON_ERROR}))
                // check if source is newer than destination
                .pipe(PLUGINS.gulpif(!PLUGINS.argv.dist, PLUGINS.newer({dest: CSS_DIRECTORY + "/" + file_name, extra})))
                // initialize sourcemap
                .pipe(PLUGINS.sourcemaps.init())
                // compile SCSS (compress if --dist is passed)
                .pipe(PLUGINS.gulpif(PLUGINS.argv.dist, PLUGINS.sass({outputStyle: "compressed"}), PLUGINS.sass()))
                // process post CSS stuff
                .pipe(PLUGINS.postcss([PLUGINS.flexibility(), PLUGINS.easing_gradients()]))
                // insert px fallback for rems
                .pipe(PLUGINS.pixrem())
                // insert run through rucksack
                .pipe(PLUGINS.rucksack({autoprefixer: true}))
                // write sourcemap (if --dist isn't passed)
                .pipe(PLUGINS.gulpif(!PLUGINS.argv.dist, PLUGINS.sourcemaps.write()))
                // remove unused CSS
                .pipe(PLUGINS.gulpif(PLUGINS.argv.experimental && PLUGINS.argv.experimental.length > 0 && PLUGINS.argv.experimental.includes("uncss") && HOMEPAGE !== "", PLUGINS.uncss({
                    html: HOMEPAGE
                })))
                // output to compiled directory
                .pipe(GULP.dest(CSS_DIRECTORY));
        };

        // styles task, compiles & prefixes SCSS
        return new Promise ((resolve) => {
            // set CSS directory
            const CSS_DIRECTORY = PLUGINS.argv.dist ? global.settings.paths.dist + "/assets/styles" : global.settings.paths.dev + "/assets/styles";

            // clean directory if --dist is passed
            if (PLUGINS.argv.dist) {
                PLUGINS.del(CSS_DIRECTORY + "/**/*");
            }

            if (PLUGINS.argv.experimental && PLUGINS.argv.experimental.length > 0 && PLUGINS.argv.experimental.includes("critical")) {
                GENERATE_CRITICAL_CSS(CSS_DIRECTORY);
            }

            // process all styles
            const LINTED    = LINT_STYLES(CSS_DIRECTORY);
            const PROCESSED = PROCESS_STYLES(CSS_DIRECTORY);

            // merge both steams back in to one
            return PLUGINS.merge(LINTED, PROCESSED)
                // prevent breaking on error
                .pipe(PLUGINS.plumber({errorHandler: ON_ERROR}))
                // reload files
                .pipe(PLUGINS.browser_sync.reload({stream: true}))
                // notify that task is complete, if not part of default or watch
                .pipe(PLUGINS.gulpif(GULP.seq.indexOf("styles") > GULP.seq.indexOf("default"), PLUGINS.notify({title: "Success!", message: "Styles task complete!", onLast: true})))
                // push task to RAN_TASKS array
                .on("data", () => {
                    if (RAN_TASKS.indexOf("styles") < 0) {
                        RAN_TASKS.push("styles");
                    }
                })
                .on("end", () => {
                    return resolve();
                });
        });
    }
};
