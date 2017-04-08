// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

// general stuff
const gulp         = require("gulp");
const argv         = require("yargs").options({
    "d": {
        alias: "dist",
        type:  "boolean",
    },
    "e": {
        alias: "experimental",
        type:  "array",
    },
    "f": {
        alias: "ftp",
        type:  "boolean",
    },
    "s": {
        alias: "sync",
        type:  "boolean",
    },
}).argv;
const fs            = require("fs");
const notify        = require("gulp-notify");
const plumber       = require("gulp-plumber");
const run_sequence  = require("run-sequence");
const json          = require("json-file");
const prompt        = require("gulp-prompt");
const del           = require("del");
const newer         = require("gulp-newer");
const merge         = require("merge-stream");
const gulpif        = require("gulp-if");
const watch         = require("gulp-watch");
const sourcemaps    = require("gulp-sourcemaps");
const concat        = require("gulp-concat");
const file_include  = require("gulp-file-include");
const replace       = require("gulp-replace");
const through       = require("through2");
const is_binary     = require("gulp-is-binary");
const remove_code   = require("gulp-remove-code");

// media stuff
const imagemin = require("gulp-imagemin");
const pngquant = require("imagemin-pngquant");

// JS stuff
const eslint = require("gulp-eslint");
const uglify = require("gulp-uglify");
const babel  = require("gulp-babel");

// CSS stuff
const stylelint    = require("gulp-stylelint");
const autoprefixer = require("gulp-autoprefixer");
const pixrem       = require("gulp-pixrem");
const sass         = require("gulp-sass");
const postcss      = require("gulp-postcss");
const flexibility  = require("postcss-flexibility");
const critical     = require("critical");
const uncss        = require("gulp-uncss");

// FTP stuff
const ftp  = require("vinyl-ftp");
const sftp = require("gulp-sftp");

/* STOP! These settings should always be blank! */
/* To configure FTP credentials, run gulp ftp   */
let ftp_host = "";
let ftp_port = "";
let ftp_mode = "";
let ftp_user = "";
let ftp_pass = "";
let ftp_path = "";

// browser-sync stuff
const browser_sync = require("browser-sync");

/* STOP! These settings should always be blank!              */
/* To configure BrowserSync settingss, run gulp watch --sync */
let bs_proxy  = "";
let bs_port   = "";
let bs_open   = "";
let bs_notify = "";

// read data from package.json
const homepage       = json.read("./package.json").get("homepage");
const name           = json.read("./package.json").get("name");
const pwa_name       = json.read("./package.json").get("progressive-web-app.name");
const pwa_short_name = json.read("./package.json").get("progressive-web-app.short_name");
const theme_color    = json.read("./package.json").get("progressive-web-app.theme_color");
const description    = json.read("./package.json").get("description");
const version        = json.read("./package.json").get("version");
const repository     = json.read("./package.json").get("repository");
const license        = json.read("./package.json").get("license");

// set up environment paths
const src  = "./src";
const dev  = "./dev";
const dist = "./dist";

// store which tasks where ran
const ran_tasks = [];

// Error handling
const on_error = function (err) {
    notify.onError({
        title:    "Gulp",
        subtitle: "Error!",
        message:  "<%= error.message %>",
        sound:    "Beep",
    })(err);

    this.emit("end");
};

// function to generate critical CSS
function generate_critical_css(css_directory) {
    const sitemap = json.read("./package.json").get("template-sitemap");
    const plural  = ((Object.keys(sitemap).length * 30) / 60) !== 1 ? "s" : "";

    console.log("Genearting critical CSS, this may take up to " + ((Object.keys(sitemap).length * 30) / 60) + " minute" + plural + ", go take a coffee break.");

    // loop through all the links
    for (const template in sitemap) {
        // make sure the key isn't a prototype
        if (sitemap.hasOwnProperty(template)) {
            // generate the critial CSS
            critical.generate({
                base:       css_directory,
                dest:       "critical_" + template + ".css",
                dimensions: [1920, 1080],
                minify:     true,
                src:        sitemap[template] + "?disable_critical_css=true",
            });
        }
    }
}

// media task, compresses images, copies other media
gulp.task("media", function () {
    // set media directory
    const media_directory = argv.dist ? dist + "/assets/media" : dev + "/assets/media";

    // set screenshot directory
    const screenshot_directory = argv.dist ? dist : dev;

    // clean directories if --dist is passed
    if (argv.dist) {
        del(media_directory + "/**/*");
        del(screenshot_directory + "/screenshot.png");
    }

    // compress images, copy other media
    const media = gulp.src(src + "/assets/media/**/*")
        // prevent breaking on error
        .pipe(plumber({errorHandler: on_error}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer(media_directory)))
        // compress images
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{cleanupIDs: false, removeViewBox: false}],
            use:         [pngquant()]
        }))
        // output to compiled directory
        .pipe(gulp.dest(media_directory));

    // compress screenshot
    const screenshot = gulp.src(src + "/screenshot.png")
        // prevent breaking on error
        .pipe(plumber({errorHandler: on_error}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer(screenshot_directory)))
        // compress screenshot
        .pipe(imagemin({
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use:         [pngquant()],
        }))
        // output to compiled directory
        .pipe(gulp.dest(screenshot_directory));

    // merge both steams back in to one
    return merge(media, screenshot)
        // prevent breaking on error
        .pipe(plumber({errorHandler: on_error}))
        // reload files
        .pipe(browser_sync.reload({stream: true}))
        // notify that task is complete, if not part of default or watch
        .pipe(gulpif(gulp.seq.indexOf("media") > gulp.seq.indexOf("default"), notify({title: "Success!", message: "Media task complete!", onLast: true})))
        // push task to ran_tasks array
        .on("data", function () {
            if (ran_tasks.indexOf("media") < 0) {
                ran_tasks.push("media");
            }
        });
});

// scripts task, lints, concatenates, & compresses JS
gulp.task("scripts", function () {
    // set JS directory
    const js_directory = argv.dist ? dist + "/assets/scripts/" : dev + "/assets/scripts";

    // clean directory if --dist is passed
    if (argv.dist) {
        del(js_directory + "/**/*");
    }

    // lint scripts
    const linted = gulp.src([src + "/assets/scripts/*.js", "!" + src + "/assets/scripts/vendor.*.js"])
        // prevent breaking on error
        .pipe(plumber({errorHandler: on_error}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer(js_directory + "/modern.js")))
        // lint all non-vendor scripts
        .pipe(eslint())
        // print lint errors
        .pipe(eslint.format());

    // process critical scripts
    const critical = gulp.src([src + "/assets/scripts/critical/loadCSS.js", src + "/assets/scripts/critical/loadCSS.cssrelpreload.js"])
        // prevent breaking on error
        .pipe(plumber({errorHandler: on_error}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer(js_directory + "/critical.js")))
        // initialize sourcemap
        .pipe(sourcemaps.init())
        // concatenate to critical.js
        .pipe(concat("critical.js"))
        // uglify (if --dist is passed)
        .pipe(gulpif(argv.dist, uglify()))
        // write sourcemap (if --dist isn't passed)
        .pipe(gulpif(!argv.dist, sourcemaps.write()))
        // output to compiled directory
        .pipe(gulp.dest(js_directory));

    // process modern scripts
    const modern = gulp.src([src + "/assets/scripts/vendor.*.js", src + "/assets/scripts/jquery.*.js", src + "/assets/scripts/*.js"])
        // prevent breaking on error
        .pipe(plumber({errorHandler: on_error}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer(js_directory + "/modern.js")))
        // initialize sourcemap
        .pipe(sourcemaps.init())
        // concatenate to modern.js
        .pipe(concat("modern.js"))
        // transpile to es2015
        .pipe(babel({presets: ["es2015"]}))
        // uglify (if --dist is passed)
        .pipe(gulpif(argv.dist, uglify()))
        // write sourcemap (if --dist isn't passed)
        .pipe(gulpif(!argv.dist, sourcemaps.write()))
        // output to compiled directory
        .pipe(gulp.dest(js_directory));

    // process legacy scripts
    const legacy = gulp.src([src + "/assets/scripts/legacy/**/*"])
        // prevent breaking on error
        .pipe(plumber({errorHandler: on_error}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer(js_directory + "/legacy.js")))
        // initialize sourcemap
        .pipe(sourcemaps.init())
        // concatenate to legacy.js
        .pipe(concat("legacy.js"))
        // uglify (if --dist is passed)
        .pipe(gulpif(argv.dist, uglify()))
        // write sourcemap (if --dist isn't passed)
        .pipe(gulpif(!argv.dist, sourcemaps.write()))
        // output to compiled directory
        .pipe(gulp.dest(js_directory));

    // merge all four steams back in to one
    return merge(linted, critical, modern, legacy)
        // prevent breaking on error
        .pipe(plumber({errorHandler: on_error}))
        // reload files
        .pipe(browser_sync.reload({stream: true}))
        // notify that task is complete, if not part of default or watch
        .pipe(gulpif(gulp.seq.indexOf("scripts") > gulp.seq.indexOf("default"), notify({title: "Success!", message: "Scripts task complete!", onLast: true})))
        // push task to ran_tasks array
        .on("data", function () {
            if (ran_tasks.indexOf("scripts") < 0) {
                ran_tasks.push("scripts");
            }
        });
});

// styles task, compiles & prefixes SCSS
gulp.task("styles", function () {
    // set CSS directory
    const css_directory = argv.dist ? dist + "/assets/styles" : dev + "/assets/styles";

    // clean directory if --dist is passed
    if (argv.dist) {
        del(css_directory + "/**/*");
    }

    if (argv.experimental && argv.experimental.length > 0 && argv.experimental.includes("critical")) {
        generate_critical_css(css_directory);
    }

    const linted = gulp.src([src + "/assets/styles/**/*.scss", "!" + src + "/assets/styles/vendor/**/*"])
        // lint
        .pipe(stylelint({
            failAfterError: true,
            reporters: [
                { formatter: "string", console: true }
            ],
            debug: true
        }));

    // process all SCSS in root styles directory
    const processed = gulp.src([src + "/assets/styles/*.scss"])
        // prevent breaking on error
        .pipe(plumber({errorHandler: on_error}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer({dest: css_directory + "/modern.css", extra: [src + "/assets/styles/**/*.scss"]})))
        // initialize sourcemap
        .pipe(sourcemaps.init())
        // compile SCSS (compress if --dist is passed)
        .pipe(gulpif(argv.dist, sass({outputStyle: "compressed"}), sass()))
        // prefix CSS
        .pipe(autoprefixer("last 2 version", "ie 8", "ie 9"))
        // insert -js-display: flex; for flexbility
        .pipe(postcss([flexibility()]))
        // insert px fallback for rems
        .pipe(pixrem())
        // write sourcemap (if --dist isn't passed)
        .pipe(gulpif(!argv.dist, sourcemaps.write()))
        // remove unused CSS
        .pipe(gulpif(argv.experimental && argv.experimental.length > 0 && argv.experimental.includes("uncss") && homepage !== "", uncss({
            html: homepage
        })))
        // output to compiled directory
        .pipe(gulp.dest(css_directory));

    // merge both steams back in to one
    return merge(linted, processed)
        // prevent breaking on error
        .pipe(plumber({errorHandler: on_error}))
        // reload files
        .pipe(browser_sync.reload({stream: true}))
        // notify that task is complete, if not part of default or watch
        .pipe(gulpif(gulp.seq.indexOf("scripts") > gulp.seq.indexOf("default"), notify({title: "Success!", message: "Styles task complete!", onLast: true})))
        // push task to ran_tasks array
        .on("data", function () {
            if (ran_tasks.indexOf("styles") < 0) {
                ran_tasks.push("styles");
            }
        });
});

// html task, copies binaries, converts includes & variables in HTML
gulp.task("html", function () {
    // set HTML directory
    const html_directory = argv.dist ? dist : dev;

    // clean directory if --dist is passed
    if (argv.dist) {
        del([html_directory + "/**/*", "!" + html_directory + "{/assets,/assets/**}"]);
    }

    // copy binaries
    const binaries = gulp.src([src + "/**/*", "!" + src + "{/assets,/assets/**}"])
        // prevent breaking on error
        .pipe(plumber({errorHandler: on_error}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer(html_directory)))
        // check if a file is a binary
        .pipe(is_binary())
        // skip file if it's not a binary
        .pipe(through.obj(function (file, enc, next) {
            if (!file.isBinary()) {
                next();
                return;
            }

            // go to next file
            next(null, file);
        }))
        // output to compiled directory
        .pipe(gulp.dest(html_directory));

    // process HTML
    const html = gulp.src([src + "/**/*", "!" + src + "{/assets,/assets/**}"])
        // prevent breaking on error
        .pipe(plumber({errorHandler: on_error}))
        // check if source is newer than destination
        .pipe(gulpif(!argv.dist, newer(html_directory)))
        // check if file is a binary
        .pipe(is_binary())
        // skip file if it's a binary
        .pipe(through.obj(function (file, enc, next) {
            if (file.isBinary()) {
                next();
                return;
            }

            // go to next file
            next(null, file);
        }))
        // replace variables
        .pipe(file_include({
            prefix:   "@@",
            basepath: "@file",
            context: {
                name,
                pwa_name,
                pwa_short_name,
                theme_color,
                description,
                version,
                repository,
                license,
            }
        }))
        // replace icon placeholders
        .pipe(replace(/(?:<icon:)([A-Za-z0-9\-\_][^:>]+)(?:\:([A-Za-z0-9\-\_\ ][^:>]*))?(?:>)/g, "<i class='icon $2'><svg class='icon_svg' aria-hidden='true'><use xlink:href='#$1' \/><\/svg></i>"))
        // output to compiled directory
        .pipe(gulp.dest(html_directory));

    // merge both steams back in to one
    return merge(binaries, html)
        // prevent breaking on error
        .pipe(plumber({errorHandler: on_error}))
        // reload files
        .pipe(browser_sync.reload({stream: true}))
        // notify that task is complete, if not part of default or watch
        .pipe(gulpif(gulp.seq.indexOf("html") > gulp.seq.indexOf("default"), notify({title: "Success!", message: "HTML task complete!", onLast: true})))
        // push task to ran_tasks array
        .on("data", function () {
            if (ran_tasks.indexOf("html") < 0) {
                ran_tasks.push("html");
            }
        });
});

gulp.task("init", function () {
    return gulp.src(src + "/**/*")
        // check if a file is a binary
        .pipe(is_binary())
        // skip file if it's a binary
        .pipe(through.obj(function (file, enc, next) {
            if (file.isBinary()) {
                next();
                return;
            }

            // go to next file
            next(null, file);
        }))
        // remove login HTML code if --remove --login is passed
        .pipe(remove_code({no_login: argv.remove && argv.login ? true : false, commentStart: "<!--", commentEnd: "-->"}))
        // output to source directory
        .pipe(gulp.dest(src));
});

// config task, generate configuration file for FTP & BrowserSync and prompt dev for input
gulp.task("config", function (cb) {
    // configure BrowserSync settings
    function configure_browsersync() {
        // read browsersync settings from config.json
        bs_proxy  = json.read("./config.json").get("browsersync.proxy");
        bs_port   = json.read("./config.json").get("browsersync.port");
        bs_open   = json.read("./config.json").get("browsersync.open");
        bs_notify = json.read("./config.json").get("browsersync.notify");

        if (argv.all || (gulp.seq.indexOf("config") < gulp.seq.indexOf("sync") || argv.sync) && (argv.config || bs_proxy === "" || bs_port === "" || bs_open === "" || bs_notify === "")) {
            // reconfigure settings in config.json if a field is empty or if --config is passed
            gulp.src("./config.json")
                .pipe(prompt.prompt([{
                    // prompt for proxy
                    type:    "input",
                    name:    "proxy",
                    message: "Browsersync proxy:",
                    default: bs_proxy === "" ? "localhost:8888" : bs_proxy,
                },
                {
                    // prompt for port
                    type:    "input",
                    name:    "port",
                    message: "Browsersync port:",
                    default: bs_port === "" ? "8080" : bs_port,
                },
                {
                    // prompt for how to open
                    type:    "input",
                    name:    "open",
                    message: "Browsersync open:",
                    default: bs_open === "" ? "external" : bs_open,
                },
                {
                    // prompt for whether to notify
                    type:    "input",
                    name:    "notify",
                    message: "Browsersync notify:",
                    default: bs_notify === "" ? "false" : bs_notify,
                }], function (res) {
                    // open config.json
                    const file = json.read("./config.json");

                    // update browsersync settings in config.json
                    file.set("browsersync.proxy",  res.proxy);
                    file.set("browsersync.port",   res.port);
                    file.set("browsersync.open",   res.open);
                    file.set("browsersync.notify", res.notify);

                    // write updated file contents
                    file.writeSync();

                    // read browsersync settings from config.json
                    bs_proxy  = res.proxy;
                    bs_port   = res.port;
                    bs_open   = res.open;
                    bs_notify = res.notify;

                    cb();
                }));
        } else {
            cb();
        }
    }

    // configure FTP credentials
    function configure_ftp() {
        // read FTP settingss from config.json
        if (!argv.dist) {
            ftp_host = json.read("./config.json").get("ftp.dev.host");
            ftp_port = json.read("./config.json").get("ftp.dev.port");
            ftp_mode = json.read("./config.json").get("ftp.dev.mode");
            ftp_user = json.read("./config.json").get("ftp.dev.user");
            ftp_pass = json.read("./config.json").get("ftp.dev.pass");
            ftp_path = json.read("./config.json").get("ftp.dev.path");
        } else {
            ftp_host = json.read("./config.json").get("ftp.dist.host");
            ftp_port = json.read("./config.json").get("ftp.dist.port");
            ftp_mode = json.read("./config.json").get("ftp.dist.mode");
            ftp_user = json.read("./config.json").get("ftp.dist.user");
            ftp_pass = json.read("./config.json").get("ftp.dist.pass");
            ftp_path = json.read("./config.json").get("ftp.dist.path");
        }

        if (argv.all || (gulp.seq.indexOf("config") < gulp.seq.indexOf("ftp") || argv.ftp) && (argv.config || ftp_host === "" || ftp_user === "" || ftp_pass === "" || ftp_path === "")) {
            // reconfigure settings in config.json if a field is empty or if --config is passed
            gulp.src("./config.json")
                .pipe(prompt.prompt([{
                    // prompt for host
                    type:    "input",
                    name:    "host",
                    message: "FTP hostname:",
                    default: ftp_host,
                },
                {
                    // prompt for port
                    type:    "input",
                    name:    "port",
                    message: "FTP port:",
                    default: ftp_port,
                },
                {
                    // prompt for mode
                    type:    "list",
                    name:    "mode",
                    message: "FTP mode:",
                    choices: ["ftp", "tls", "sftp"],
                    default: ftp_mode === "ftp" ? 0 : ftp_mode === "tls" ? 1 : ftp_mode === "sftp" ? 2 : 0,
                },
                {
                    // prompt for user
                    type:    "input",
                    name:    "user",
                    message: "FTP username:",
                    default: ftp_user,
                },
                {
                    // prompt for password
                    type:    "password",
                    name:    "pass",
                    message: "FTP password:",
                    default: ftp_pass,
                },
                {
                    // prompt for path
                    type:    "input",
                    name:    "path",
                    message: "FTP remote path:",
                    default: ftp_path,
                }], function (res) {
                    // open config.json
                    const file = json.read("./config.json");

                    // update ftp settings in config.json
                    if (!argv.dist) {
                        file.set("ftp.dev.host", res.host);
                        file.set("ftp.dev.port", res.port);
                        file.set("ftp.dev.mode", res.mode);
                        file.set("ftp.dev.user", res.user);
                        file.set("ftp.dev.pass", res.pass);
                        file.set("ftp.dev.path", res.path);
                    } else {
                        file.set("ftp.dist.host", res.host);
                        file.set("ftp.dist.port", res.port);
                        file.set("ftp.dist.mode", res.mode);
                        file.set("ftp.dist.user", res.user);
                        file.set("ftp.dist.pass", res.pass);
                        file.set("ftp.dist.path", res.path);
                    }

                    // write updated file contents
                    file.writeSync();

                    // read FTP settings from config.json
                    ftp_host = res.host;
                    ftp_port = res.port;
                    ftp_mode = res.mode;
                    ftp_user = res.user;
                    ftp_pass = res.pass;
                    ftp_path = res.path;

                    configure_browsersync();
                }));
        } else {
            configure_browsersync();
        }
    }

    // generate config.json and start other functions
    fs.stat("./config.json", function (err) {
        if (err !== null) {
            const json_data =
            `{
                "ftp": {
                    "dev": {
                        "host": "",
                        "port": "21",
                        "mode": "ftp",
                        "user": "",
                        "pass": "",
                        "path": ""
                    },
                    "dist": {
                        "host": "",
                        "port": "21",
                        "mode": "ftp",
                        "user": "",
                        "pass": "",
                        "path": ""
                    }
                },
                "browsersync": {
                    "proxy":  "",
                    "port":   "",
                    "open":   "",
                    "notify": ""
                }
            }`;

            fs.writeFile("./config.json", json_data, function () {
                configure_ftp(function () {
                    configure_browsersync();
                });
            });
        } else {
            configure_ftp(function () {
                configure_browsersync();
            });
        }
    });
});

// ftp task, upload to FTP environment, depends on config
gulp.task("ftp", ["config"], function () {
    // set FTP directory
    const ftp_directory = argv.dist ? dist : dev;

    // create SFTP connection
    const sftp_conn = sftp({
        host:       ftp_host,
        port:       ftp_port,
        username:   ftp_user,
        password:   ftp_pass,
        remotePath: ftp_path,
    });

    // create FTP connection
    const ftp_conn = ftp.create({
        host:   ftp_host,
        port:   ftp_port,
        secure: ftp_mode === "tls" ? true : false,
        user:   ftp_user,
        pass:   ftp_pass,
        path:   ftp_path,
    });

    // upload changed files
    return gulp.src(ftp_directory + "/**/*")
        // prevent breaking on error
        .pipe(plumber({errorHandler: on_error}))
        // check if files are newer
        .pipe(gulpif(!argv.dist, newer({dest: src, extra: [ftp_directory + "/**/*"]})))
        // check if files are newer
        .pipe(gulpif(ftp_mode !== "sftp", ftp_conn.newer(ftp_path)))
        // upload changed files
        .pipe(gulpif(ftp_mode !== "sftp", ftp_conn.dest(ftp_path), sftp_conn))
        // prevent breaking on error
        .pipe(plumber({errorHandler: on_error}))
        // reload files
        .pipe(browser_sync.reload({stream: true}))
        // notify that task is complete, if not part of default or watch
        .pipe(notify({title: "Success!", message: "FTP task complete!", onLast: true}));
});

// sync task, set up a browser_sync server, depends on config
gulp.task("sync", ["config"], function () {
    browser_sync({
        proxy:  bs_proxy,
        port:   bs_port,
        open:   bs_open === "true" ? true : (bs_open === "false" ? false : bs_open),
        notify: bs_notify === "true" ? true : (bs_notify === "false" ? false : bs_notify),
    });
});

// default task, runs through everything but dist
gulp.task("default", ["media", "scripts", "styles", "html"], function () {
    // notify that task is complete
    gulp.src("gulpfile.js")
        .pipe(gulpif(ran_tasks.length, notify({title: "Success!", message: ran_tasks.length + " task" + (ran_tasks.length > 1 ? "s" : "") + " complete! [" + ran_tasks.join(", ") + "]", onLast: true})));

    // trigger FTP task if FTP flag is passed
    if (argv.ftp) {
        run_sequence("ftp");
    }

    // reset ran_tasks array
    ran_tasks.length = 0;
});

// watch task, runs through everything but dist, triggers when a file is saved
gulp.task("watch", function () {
    // set up a browser_sync server, if --sync is passed
    if (argv.sync) {
        run_sequence("sync");
    }

    // watch for any changes
    watch("./src/**/*", function () {
        // run through all tasks
        run_sequence("default");
    });
});
