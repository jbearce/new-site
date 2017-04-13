// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

module.exports = {
    // config task, generate configuration file for FTP & BrowserSync and prompt dev for input
    config(gulp, plugins) {
        // generate config.json and start other functions
        const generate_config = function (callback) {
            return plugins.fs.stat("./config.json", function (err) {
                if (err !== null) {
                    const json_data =
                    `{
                        "ftp": {
                            "dev": {
                                "hostname": "",
                                "port":     "21",
                                "mode":     "ftp",
                                "username": "",
                                "password": "",
                                "path":     ""
                            },
                            "dist": {
                                "hostname": "",
                                "port":     "21",
                                "mode":     "ftp",
                                "username": "",
                                "password": "",
                                "path":     ""
                            }
                        },
                        "browsersync": {
                            "dev": {
                                "proxy":  "",
                                "port":   "",
                                "open":   "",
                                "notify": ""
                            },
                            "dist": {
                                "proxy":  "",
                                "port":   "",
                                "open":   "",
                                "notify": ""
                            }
                        }
                    }`;

                    plugins.fs.writeFile("./config.json", json_data, "utf8", function () {
                        callback();
                    });
                } else if (typeof callback === "function") {
                    callback();
                }
            });
        };

        // configue JSON data
        const configure_json = function (namespace, options, env, callback) {
            const prompts = [];

            // construct the prompts
            Object.keys(options).forEach(option => {
                const properties = options[option];

                // construct the prompt
                const prompt     = {
                    name:    option,
                    message: namespace + " " + option + ": ",
                };

                // construct the prompt
                Object.keys(properties).forEach(property => {
                    prompt[property] = properties[property];
                });

                // put the prompt in the array
                prompts.push(prompt);
            });

            // prompt the user
            return gulp.src("./config.json")
                .pipe(plugins.prompt.prompt(prompts, function (res) {
                    // open config.json
                    const file = plugins.json.read("./config.json");

                    // update data in JSON
                    Object.keys(options).forEach(option => {
                        file.set(namespace + "." + env + "." + option, res[option]);
                        global.settings[namespace][option] = res[option];
                    });

                    // write updated file contents
                    file.writeSync();

                    if (typeof callback === "function") {
                        callback();
                    }
                }));
        };

        return new Promise (function (resolve) {
            // get the target environment
            const env = plugins.argv.dist ? "dist" : "dev";

            // generate config.json
            generate_config(function () {
                // read browsersync settings from config.json
                global.settings.browsersync.proxy  = plugins.json.read("./config.json").get("browsersync." + env + ".proxy");
                global.settings.browsersync.port   = plugins.json.read("./config.json").get("browsersync." + env + ".port");
                global.settings.browsersync.open   = plugins.json.read("./config.json").get("browsersync." + env + ".open");
                global.settings.browsersync.notify = plugins.json.read("./config.json").get("browsersync." + env + ".notify");

                // read FTP settingss from config.json
                global.settings.ftp.host = plugins.json.read("./config.json").get("ftp." + env + ".hostname");
                global.settings.ftp.port = plugins.json.read("./config.json").get("ftp." + env + ".port");
                global.settings.ftp.mode = plugins.json.read("./config.json").get("ftp." + env + ".mode");
                global.settings.ftp.user = plugins.json.read("./config.json").get("ftp." + env + ".username");
                global.settings.ftp.pass = plugins.json.read("./config.json").get("ftp." + env + ".password");
                global.settings.ftp.path = plugins.json.read("./config.json").get("ftp." + env + ".path");

                // configure FTP credentials
                configure_json("ftp", {
                    hostname: {
                        default: global.settings.ftp.host,
                        type:    "input",
                    },
                    port: {
                        default: global.settings.ftp.port,
                        type:    "input",
                    },
                    mode: {
                        default: global.settings.ftp.mode === "ftp" ? 0 : global.settings.ftp.mode === "tls" ? 1 : global.settings.ftp.mode === "sftp" ? 2 : 0,
                        type:    "list",
                        choices: ["ftp", "tls", "sftp"],
                    },
                    username: {
                        default: global.settings.ftp.user,
                        type:    "input",
                    },
                    password: {
                        default: global.settings.ftp.pass,
                        type:    "password",
                    },
                    path: {
                        default: global.settings.ftp.path,
                        type:    "input",
                    },
                }, env, function () {
                    // configure BrowserSync settings
                    configure_json("browsersync", {
                        proxy: {
                            default: global.settings.browsersync.proxy === "" ? "localhost" : global.settings.browsersync.proxy,
                            type: "input",
                        },
                        port: {
                            default: global.settings.browsersync.port === "" ? "8080" : global.settings.browsersync.port,
                            type: "input",
                        },
                        open: {
                            default: global.settings.browsersync.open === "" ? "external" : global.settings.browsersync.open,
                            type: "input",
                        },
                        notify: {
                            default: global.settings.browsersync.open === "" ? "false" : global.settings.browsersync.open,
                            type: "input",
                        },
                    }, env, function () {
                        // resolve the promise
                        resolve();
                    });
                });
            });
        });
    }
};
