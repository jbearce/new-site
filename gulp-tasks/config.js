// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

module.exports = function (gulp, plugins, bs, ftp) {
    // generate config.json and start other functions
    const generate_config = function () {
        return new Promise (function (resolve, reject) {
            plugins.fs.stat("./config.json", function (err) {
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

                    plugins.fs.writeFile("./config.json", json_data, "utf8", function (err) {
                        if (err) {
                            reject(err);
                        }  else {
                            resolve(json_data);
                        }
                    });
                } else {
                    resolve("File already exists.");
                }
            });
        });
    };

    // configure BrowserSync settings
    const configure_browsersync = function (bs_proxy = plugins.json.read("./config.json").get("browsersync.proxy"), bs_port = plugins.json.read("./config.json").get("browsersync.port"), bs_open = plugins.json.read("./config.json").get("browsersync.open"), bs_notify = plugins.json.read("./config.json").get("browsersync.notify"), callback) {
        if (((plugins.argv.all || plugins.argv.sync) && gulp.seq.indexOf("config") === 0) || ((bs_proxy === "" || bs_port === "" || bs_open === "" || bs_notify === "")) && (gulp.seq.indexOf("sync") >= 0 || (gulp.seq.indexOf("config") === (gulp.seq.length - 1)))) {
            // reconfigure settings in config.json if a field is empty or --all or --sync is passed directly to config
            gulp.src("./config.json")
                .pipe(plugins.prompt.prompt([{
                    // prompt for proxy
                    type:    "input",
                    name:    "proxy",
                    message: "Browsersync proxy:",
                    default: bs_proxy === "" ? "localhost" : bs_proxy,
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
                    const file = plugins.json.read("./config.json");

                    // update browsersync settings in config.json
                    file.set("browsersync.proxy",  res.proxy);
                    file.set("browsersync.port",   res.port);
                    file.set("browsersync.open",   res.open);
                    file.set("browsersync.notify", res.notify);

                    // write updated file contents
                    file.writeSync();

                    // read browsersync settings from config.json
                    bs.proxy  = res.proxy;
                    bs.port   = res.port;
                    bs.open   = res.open;
                    bs.notify = res.notify;

                    if (typeof callback === "function") {
                        callback();
                    }
                }));
        } else if (typeof callback === "function") {
            callback();
        }
    };

    // configure FTP credentials
    const configure_ftp = function (ftp_host = plugins.json.read("./config.json").get("ftp.dev.host"), ftp_port = plugins.json.read("./config.json").get("ftp.dev.port"), ftp_mode = plugins.json.read("./config.json").get("ftp.dev.mode"), ftp_user = plugins.json.read("./config.json").get("ftp.dev.user"), ftp_pass = plugins.json.read("./config.json").get("ftp.dev.pass"), ftp_path = plugins.json.read("./config.json").get("ftp.dev.path"), env = "dev", callback) {
        if (((plugins.argv.all || plugins.argv.ftp) && gulp.seq.indexOf("config") === 0) || ((ftp_host === "" || ftp_user === "" || ftp_pass === "" || ftp_path === "")) && (gulp.seq.indexOf("ftp") >= 0 || (gulp.seq.indexOf("config") === (gulp.seq.length - 1)))) {
            // reconfigure settings in config.json if a field is empty or --all or --ftp is passed directly to config
            gulp.src("./config.json")
                .pipe(plugins.prompt.prompt([{
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
                    const file = plugins.json.read("./config.json");

                    // update ftp settings in config.json
                    file.set("ftp." + env + ".host", res.host);
                    file.set("ftp." + env + ".port", res.port);
                    file.set("ftp." + env + ".mode", res.mode);
                    file.set("ftp." + env + ".user", res.user);
                    file.set("ftp." + env + ".pass", res.pass);
                    file.set("ftp." + env + ".path", res.path);

                    // write updated file contents
                    file.writeSync();

                    // read FTP settings from config.json
                    ftp.host = res.host;
                    ftp.port = res.port;
                    ftp.mode = res.mode;
                    ftp.user = res.user;
                    ftp.pass = res.pass;
                    ftp.path = res.path;

                    if (typeof callback === "function") {
                        callback();
                    }
                }));
        } else if (typeof callback === "function") {
            callback();
        }
    };

    // config task, generate configuration file for FTP & BrowserSync and prompt dev for input
    return function () {
        return new Promise (function (resolve) {
            // get the target environment
            const env = plugins.argv.dist ? "dist" : "dev";

            // generate config.json
            generate_config().then(function () {
                // read browsersync settings from config.json
                bs.proxy  = plugins.json.read("./config.json").get("browsersync.proxy");
                bs.port   = plugins.json.read("./config.json").get("browsersync.port");
                bs.open   = plugins.json.read("./config.json").get("browsersync.open");
                bs.notify = plugins.json.read("./config.json").get("browsersync.notify");

                // read FTP settingss from config.json
                ftp.host = plugins.json.read("./config.json").get("ftp." + env + ".host");
                ftp.port = plugins.json.read("./config.json").get("ftp." + env + ".port");
                ftp.mode = plugins.json.read("./config.json").get("ftp." + env + ".mode");
                ftp.user = plugins.json.read("./config.json").get("ftp." + env + ".user");
                ftp.pass = plugins.json.read("./config.json").get("ftp." + env + ".pass");
                ftp.path = plugins.json.read("./config.json").get("ftp." + env + ".path");

                configure_browsersync(bs.proxy, bs.port, bs.open, bs.notify, function () {
                    configure_ftp(ftp.host, ftp.port, ftp.mode, ftp.user, ftp.pass, ftp.path, env, function () {
                        resolve();
                    });
                });
            });
        });
    };
};
