// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

module.exports = function (gulp, plugins, envs, ran_tasks, on_error) {
    // get the homepage from the package.json
    const homepage = plugins.json.read("./package.json").get("homepage");

    // function to generate critical CSS
    const generate_critical_css = function (css_directory, sitemap = plugins.json.read("./package.json").get("template-sitemap")) {
        const plural = ((Object.keys(sitemap).length * 30) / 60) !== 1 ? "s" : "";

        console.log("Genearting critical CSS, this may take up to " + ((Object.keys(sitemap).length * 30) / 60) + " minute" + plural + ", go take a coffee break.");

        // loop through all the links
        for (const template in sitemap) {
            // make sure the key isn't a prototype
            if (sitemap.hasOwnProperty(template)) {
                // generate the critial CSS
                plugins.critical.generate({
                    base:       css_directory,
                    dest:       "critical_" + template + ".css",
                    dimensions: [1920, 1080],
                    minify:     true,
                    src:        sitemap[template] + "?disable_critical_css=true",
                });
            }
        }
    };

    // lint custom styles
    const lint_styles = function (source = [envs.src + "/assets/styles/**/*.scss", "!" + envs.src + "/assets/styles/vendor/**/*"]) {
        return gulp.src(source)
            // lint
            .pipe(plugins.stylelint({
                failAfterError: true,
                reporters: [
                    { formatter: "string", console: true }
                ],
                debug: true
            }));
    };

    // process all SCSS in root styles directory
    const process_styles = function (css_directory, file_name = "modern.css", source = [envs.src + "/assets/styles/*.scss"], extra = [envs.src + "/assets/styles/**/*.scss"]) {
        return gulp.src(source)
            // prevent breaking on error
            .pipe(plugins.plumber({errorHandler: on_error}))
            // check if source is newer than destination
            .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer({dest: css_directory + "/" + file_name, extra})))
            // initialize sourcemap
            .pipe(plugins.sourcemaps.init())
            // compile SCSS (compress if --dist is passed)
            .pipe(plugins.gulpif(plugins.argv.dist, plugins.sass({outputStyle: "compressed"}), plugins.sass()))
            // prefix CSS
            .pipe(plugins.autoprefixer("last 2 version", "ie 8", "ie 9"))
            // insert -js-display: flex; for flexbility
            .pipe(plugins.postcss([plugins.flexibility()]))
            // insert px fallback for rems
            .pipe(plugins.pixrem())
            // write sourcemap (if --dist isn't passed)
            .pipe(plugins.gulpif(!plugins.argv.dist, plugins.sourcemaps.write()))
            // remove unused CSS
            .pipe(plugins.gulpif(plugins.argv.experimental && plugins.argv.experimental.length > 0 && plugins.argv.experimental.includes("uncss") && homepage !== "", plugins.uncss({
                html: homepage
            })))
            // output to compiled directory
            .pipe(gulp.dest(css_directory));
    };

    // styles task, compiles & prefixes SCSS
    return function () {
        // set CSS directory
        const css_directory = plugins.argv.dist ? envs.dist + "/assets/styles" : envs.dev + "/assets/styles";

        // clean directory if --dist is passed
        if (plugins.argv.dist) {
            plugins.del(css_directory + "/**/*");
        }

        if (plugins.argv.experimental && plugins.argv.experimental.length > 0 && plugins.argv.experimental.includes("critical")) {
            generate_critical_css(css_directory);
        }

        // process all styles
        const linted    = lint_styles();
        const processed = process_styles(css_directory);

        // merge both steams back in to one
        return plugins.merge(linted, processed)
            // prevent breaking on error
            .pipe(plugins.plumber({errorHandler: on_error}))
            // reload files
            .pipe(plugins.browser_sync.reload({stream: true}))
            // notify that task is complete, if not part of default or watch
            .pipe(plugins.gulpif(gulp.seq.indexOf("styles") > gulp.seq.indexOf("default"), plugins.notify({title: "Success!", message: "Styles task complete!", onLast: true})))
            // push task to ran_tasks array
            .on("data", function () {
                if (ran_tasks.indexOf("styles") < 0) {
                    ran_tasks.push("styles");
                }
            });
    };
};