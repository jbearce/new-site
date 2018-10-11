// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    styles(gulp, plugins, ran_tasks, on_error) {
        // task-specific plugins
        const GLOB      = require("glob");
        const POSTCSS   = require("gulp-postcss");
        const SASS      = require("gulp-sass");
        const STYLELINT = require("gulp-stylelint");

        // styles task, compiles & prefixes SCSS
        return new Promise ((resolve) => {
            // set CSS directory
            const CSS_DIRECTORY = plugins.argv.dist ? global.settings.paths.dist + "/assets/styles" : global.settings.paths.dev + "/assets/styles";

            // generate critical CSS if requested
            if (plugins.argv.experimental && plugins.argv.experimental.length > 0 && plugins.argv.experimental.includes("critical")) {
                const SITEMAP  = plugins.json.readFileSync("./package.json").templates;
                const CRITICAL = require("critical");
                const MKDIRP   = require("mkdirp");

                console.log("Genearting critical CSS, this may take up to " + ((Object.keys(SITEMAP).length * 30) / 60) + " minute" + (((Object.keys(SITEMAP).length * 30) / 60) !== 1 ? "s" : "") + ", go take a coffee break.");

                // create the "critical" directory
                MKDIRP(CSS_DIRECTORY + "/critical");

                // loop through all the links
                for (const TEMPLATE in SITEMAP) {
                    // make sure the key isn't a prototype
                    if (SITEMAP.hasOwnProperty(TEMPLATE)) {
                        // generate the critial CSS
                        CRITICAL.generate({
                            base:       CSS_DIRECTORY + "/critical",
                            dest:       TEMPLATE + ".css",
                            dimensions: [1920, 1080],
                            minify:     true,
                            src:        SITEMAP[TEMPLATE] + "?disable=critical_css"
                        });
                    }
                }
            }

            const ALL_FILE_NAMES   = plugins.fs.existsSync(CSS_DIRECTORY) ? plugins.fs.readdirSync(CSS_DIRECTORY) : false;
            let hashed_file_name = ALL_FILE_NAMES.length > 0 && ALL_FILE_NAMES.find((name) => {
                return name.match(new RegExp("[^.]+.[a-z0-9]{8}.css"));
            });

            if (!hashed_file_name) {
                hashed_file_name = "modern.css";
            }

            // process styles
            gulp.src(global.settings.paths.src + "/assets/styles/**/*.scss")
                // prevent breaking on error
                .pipe(plugins.plumber({
                    errorHandler: on_error
                }))
                // check if source is newer than destination
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(CSS_DIRECTORY + "/" + hashed_file_name)))
                // lint
                .pipe(STYLELINT({
                    debug: true,
                    failAfterError: true,
                    reporters: [
                        {
                            console: true,
                            formatter: "string",
                        },
                    ],
                }))
                // initialize sourcemap
                .pipe(plugins.sourcemaps.init())
                // compile SCSS (compress if --dist is passed)
                .pipe(SASS({
                    importer: (url, prev, done) => {
                        // ensure the imported file isn't remote, doesn't have an extension specified, or is a .css file
                        if (url.match(/^(?!https?:\/\/)[^.]+(\.css)?$/)) {
                            // get the path to the folder containing the importing file
                            const PREV_PATH = prev.replace(new RegExp(/\/[^./]+\.(sass|scss)$/), "") + "/";

                            // construct a glob pattern based on the importing path and url to the imported file
                            const GLOB_PATTERN = (url) => {
                                const PATH = url.replace(new RegExp(/[^/]+$/), "");
                                const FILE = url.replace(new RegExp(/^.*\/([^.]+)/), "$1");

                                return "{" + PREV_PATH + "," + "./node_modules/}" + PATH + "?(_)" + FILE + ".css";
                            };

                            // try to find a matching file
                            const GLOBBED = GLOB.sync(GLOB_PATTERN(url));

                            // ensure only one result was matched; let node-sass handle the "It's not clear" error
                            if (GLOBBED.length === 1) {
                                // return the contents of the imported file
                                return done({contents: plugins.fs.readFileSync(GLOBBED[0], "utf8")});
                            }
                        }

                        return done();
                    },
                    includePaths: "./node_modules",
                    outputStyle:  plugins.argv.dist ? "compressed" : "nested",
                }))
                // process post CSS stuff
                .pipe(POSTCSS([
                    require("pixrem"),
                    require("postcss-clearfix"),
                    require("postcss-easing-gradients"),
                    require("postcss-inline-svg"),
                    require("postcss-flexibility"),
                    require("postcss-responsive-type"),
                ]))
                // generate a hash and add it to the file name
                .pipe(plugins.hash({
                    template: "<%= name %>.<%= hash %><%= ext %>",
                }))
                // write sourcemap (if --dist isn't passed)
                .pipe(plugins.gulpif(!plugins.argv.dist, plugins.sourcemaps.write()))
                // output styles to compiled directory
                .pipe(gulp.dest(CSS_DIRECTORY))
                // notify that task is complete, if not part of default or watch
                .pipe(plugins.gulpif(gulp.seq.indexOf("styles") > gulp.seq.indexOf("default"), plugins.notify({
                    title:   "Success!",
                    message: "Styles task complete!",
                    onLast:  true,
                })))
                // push task to ran_tasks array
                .on("data", () => {
                    if (ran_tasks.indexOf("styles") < 0) {
                        ran_tasks.push("styles");
                    }
                })
                // generate a hash manfiest
                .pipe(plugins.hash.manifest("./.hashmanifest-styles", {
                    deleteOld: true,
                    sourceDir: CSS_DIRECTORY,
                }))
                // output hash manifest in root
                .pipe(gulp.dest("."))
                // resolve the promise
                .on("end", () => {
                    resolve();
                });
        });
    }
};
