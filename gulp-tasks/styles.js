// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

module.exports = {
    styles(gulp, plugins, ran_tasks, on_error) {
        // task-specific plugins
        const postcss   = require("gulp-postcss");
        const sass      = require("gulp-sass");
        const stylelint = require("gulp-stylelint");

        // styles task, compiles & prefixes SCSS
        return new Promise ((resolve) => {
            // set CSS directory
            const css_directory = plugins.argv.dist ? global.settings.paths.dist + "/assets/styles" : global.settings.paths.dev + "/assets/styles";

            // generate critical CSS if requested
            if (plugins.argv.experimental && plugins.argv.experimental.length > 0 && plugins.argv.experimental.includes("critical")) {
                const sitemap  = plugins.json.readFileSync("./package.json").templates;
                const critical = require("critical");
                const mkdirp   = require("mkdirp");

                console.log("Genearting critical CSS, this may take up to " + ((Object.keys(sitemap).length * 30) / 60) + " minute" + (((Object.keys(sitemap).length * 30) / 60) !== 1 ? "s" : "") + ", go take a coffee break.");

                // create the "critical" directory
                mkdirp(css_directory + "/critical");

                // loop through all the links
                for (const template in sitemap) {
                    // make sure the key isn't a prototype
                    if (sitemap.hasOwnProperty(template)) {
                        // generate the critial CSS
                        critical.generate({
                            base:       css_directory + "/critical",
                            dest:       template + ".css",
                            dimensions: [1920, 1080],
                            minify:     true,
                            src:        sitemap[template] + "?disable=critical_css"
                        });
                    }
                }
            }

            // process styles
            return gulp.src(global.settings.paths.src + "/assets/styles/*.scss")
                // prevent breaking on error
                .pipe(plugins.plumber({errorHandler: on_error}))
                // check if source is newer than destination
                .pipe(plugins.newer({
                    extra: global.settings.paths.src + "/assets/styles/**/*.scss",
                    dest: css_directory,
                    map: (src) => {
                        const all_file_names   = plugins.fs.existsSync(css_directory) ? plugins.fs.readdirSync(css_directory) : false;
                        const hashed_file_name = all_file_names ? all_file_names.find((name) => {
                            return name.match(new RegExp(src.split(".")[0] + ".[a-z0-9]{8}.css"));
                        }) : src;

                        return hashed_file_name;
                    },
                }))
                // lint
                .pipe(stylelint({
                    debug: true,
                    failAfterError: true,
                    reporters: [
                        { formatter: "string", console: true }
                    ],
                }))
                // initialize sourcemap
                .pipe(plugins.sourcemaps.init())
                // compile SCSS (compress if --dist is passed)
                .pipe(plugins.gulpif(plugins.argv.dist, sass({ includePaths: "./node_modules", outputStyle: "compressed" }), sass({ includePaths: "./node_modules" })))
                // process post CSS stuff
                .pipe(postcss([require("pixrem"), require("postcss-clearfix"), require("postcss-easing-gradients"), require("postcss-inline-svg"), require("postcss-flexibility"), require("postcss-responsive-type")]))
                // generate a hash and add it to the file name
                .pipe(plugins.hash({template: "<%= name %>.<%= hash %><%= ext %>"}))
                // write sourcemap (if --dist isn't passed)
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.sourcemaps.write()))
                // output styles to compiled directory
                .pipe(gulp.dest(css_directory))
                // notify that task is complete, if not part of default or watch
                .pipe(plugins.gulpif(gulp.seq.indexOf("styles") > gulp.seq.indexOf("default"), plugins.notify({title: "Success!", message: "Styles task complete!", onLast: true})))
                // push task to ran_tasks array
                .on("data", () => {
                    if (ran_tasks.indexOf("styles") < 0) {
                        ran_tasks.push("styles");
                    }
                })
                // generate a hash manfiest
                .pipe(plugins.hash.manifest("./.hashmanifest-styles", {deleteOld: true, sourceDir: css_directory}))
                // output hash manifest in root
                .pipe(gulp.dest("."))
                // resolve the promise
                .on("end", () => {
                    return resolve();
                });
        });
    }
};
