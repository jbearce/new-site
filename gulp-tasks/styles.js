// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    styles(gulp, plugins, customNotifier, ranTasks, onError) {
        // task-specific plugins
        const cssImporter = require("node-sass-simple-css-importer");
        const postcss = require("gulp-postcss");
        const sass = require("gulp-sass");
        const stylelint = require("gulp-stylelint");
        const touch = require("gulp-touch-fd");

        const checkIfNewer = (source = `${global.settings.paths.src}/assets/styles/**/*.scss`, folderName = `${global.settings.paths.dev}/assets/styles/`, fileName = "modern.css") => {
            let clean = false;

            return new Promise((resolve) => {
                gulp.src(source)
                    // prevent breaking on error
                    .pipe(plugins.plumber({errorHandler: onError}))
                    // check if source is newer than destination
                    .pipe(plugins.gulpif(!plugins.argv.dist, plugins.newer(`${folderName}/${fileName}`)))
                    // if source files are newer, then mark the destination for cleaning
                    .on("data", () => {
                        clean = true;
                    })
                    // clean the directory if necessary and resolve the promsie
                    .on("end", () => {
                        if (clean) {
                            // delete the folder, becuase it's being replaced
                            plugins.del(folderName).then(() => {
                                // resolve the promise, compile
                                resolve(true);
                            });
                        } else {
                            // resolve the promise, don't compile
                            resolve(false);
                        }
                    });
            });
        };

        const processStyles = (source = `${global.settings.paths.src}/assets/styles/*.scss`, css_directory = `${global.settings.paths.dev}/assets/styles`) => {
            return new Promise((resolve) => {
                // process styles
                gulp.src(source)
                    // prevent breaking on error
                    .pipe(plugins.plumber({errorHandler: onError}))
                    // lint
                    .pipe(stylelint({
                        debug:          true,
                        failAfterError: true,
                        reporters:      [
                            {
                                console:   true,
                                formatter: "string",
                            },
                        ],
                    }))
                    // initialize sourcemap
                    .pipe(plugins.sourcemaps.init())
                    // compile SCSS (compress if --dist is passed)
                    .pipe(sass({
                        importer:     cssImporter(),
                        includePaths: "node_modules",
                        outputStyle:  plugins.argv.dist ? "compressed" : "nested",
                    }))
                    // process post CSS stuff
                    .pipe(postcss([
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
                    .pipe(gulp.dest(css_directory))
                    // update the mtime to prevent gulpjs/gulp#1461
                    .pipe(touch())
                    // notify that task is complete, if not part of default or watch
                    .pipe(plugins.gulpif(plugins.argv._.includes("styles"), plugins.notify({
                        appIcon:  plugins.path.resolve("./src/assets/media/logo-favicon.png"),
                        title:    "Success!",
                        message:  "Styles task complete!",
                        notifier: process.env.BURNTTOAST === "true" ? customNotifier : false,
                        onLast:   true,
                    })))
                    // push task to ranTasks array
                    .on("data", () => {
                        if (!ranTasks.includes("styles")) {
                            ranTasks.push("styles");
                        }
                    })
                    .on("end", () => {
                        resolve();
                    });
            });
        };

        // styles task, compiles & prefixes SCSS
        return new Promise((resolve) => {
            // set CSS directory
            const CSS_DIRECTORY = plugins.argv.dist ? `${global.settings.paths.dist}/assets/styles` : `${global.settings.paths.dev}/assets/styles`;

            // set the source directory
            const SOURCE_DIRECTORY = `${global.settings.paths.src}/assets/styles`;

            // generate critical CSS if requested
            if (plugins.argv.experimental && plugins.argv.experimental.includes("critical")) {
                const SITEMAP = plugins.json.readFileSync("package.json").templates;
                const critical = require("critical");
                const mkdirp = require("mkdirp");

                console.log(`Genearting critical CSS, this may take up to ${((Object.keys(SITEMAP).length * 30) / 60)} minute ${(((Object.keys(SITEMAP).length * 30) / 60) !== 1 ? "s" : "")}, go take a coffee break.`);

                // create the "critical" directory
                mkdirp(`${CSS_DIRECTORY}/critical`);

                // loop through all the links
                for (const TEMPLATE in SITEMAP) {
                    // make sure the key isn't a prototype
                    if (SITEMAP.hasOwnProperty(TEMPLATE)) {
                        // generate the critial CSS
                        critical.generate({
                            base:       `${CSS_DIRECTORY}/critical`,
                            dest:       `${TEMPLATE}.css`,
                            dimensions: [1920, 1080],
                            minify:     true,
                            src:        `${SITEMAP[TEMPLATE]}?disable=critical_css`,
                        });
                    }
                }
            }

            const ALL_FILE_NAMES = plugins.fs.existsSync(CSS_DIRECTORY) ? plugins.fs.readdirSync(CSS_DIRECTORY) : false;

            let hashedFileName = ALL_FILE_NAMES.length > 0 && ALL_FILE_NAMES.find((name) => {
                return name.match(new RegExp("[^.]+.[a-z0-9]{8}.css"));
            });

            if (!hashedFileName) {
                hashedFileName = "modern.css";
            }

            checkIfNewer(`${SOURCE_DIRECTORY}/**/*.scss`, CSS_DIRECTORY, hashedFileName).then((compile) => {
                if (compile === true) {
                    processStyles(`${SOURCE_DIRECTORY}/*.scss`, CSS_DIRECTORY).then(() => {
                        resolve();
                    });
                } else {
                    resolve();
                }
            });
        });
    },
};
