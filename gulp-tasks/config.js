// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

module.exports = {
    // config task, generate configuration file for uploads & BrowserSync and prompt dev for input
    config(gulp, plugins, requested = "") {
        // generate config.json and start other functions
        const generate_config = (callback) => {
            return plugins.fs.stat("./config.json", (err) => {
                if (err !== null) {
                    const json_data =
                    `{
                        "remote": {
                            "dev": {
                                "hostname": "",
                                "port":     "",
                                "mode":     "",
                                "username": "",
                                "password": "",
                                "path":     ""
                            },
                            "dist": {
                                "hostname": "",
                                "port":     "",
                                "mode":     "",
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

                    plugins.fs.writeFile("./config.json", json_data, "utf8", () => {
                        return callback();
                    });
                } else if (typeof callback === "function") {
                    return callback();
                }
            });
        };

        // configue JSON data
        const configure_json = (namespace, options, env, callback) => {
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

                // check if the setting has no value or is explicitly requested
                if ((requested !== "" && requested === namespace && global.settings[namespace][option] === "") || gulp.seq.indexOf("config") >= 0 && (global.settings[namespace][option] === "" || plugins.argv[namespace])) {
                    prompts.push(prompt);
                }
            });

            if (prompts.length > 0) {
                // prompt the user
                gulp.src("./config.json")
                    .pipe(plugins.prompt.prompt(prompts, (res) => {
                        // open config.json
                        const file = plugins.json.read("./config.json");

                        // update data in JSON
                        Object.keys(options).forEach(option => {
                            file.set(namespace + "." + env + "." + option, res[option]);
                            global.settings[namespace][option] = res[option];
                        });

                        // write updated file contents
                        file.writeSync();
                    })).on("end", () => {
                        if (typeof callback === "function") {
                            return callback();
                        }
                    });
            } else if (typeof callback === "function") {
                return callback();
            }
        };

        return new Promise ((resolve) => {
            // get the target environment
            const env = plugins.argv.dist ? "dist" : "dev";

            // generate config.json
            generate_config(() => {
                // read browsersync settings from config.json
                global.settings.browsersync.proxy  = plugins.json.read("./config.json").get("browsersync." + env + ".proxy");
                global.settings.browsersync.port   = plugins.json.read("./config.json").get("browsersync." + env + ".port");
                global.settings.browsersync.open   = plugins.json.read("./config.json").get("browsersync." + env + ".open");
                global.settings.browsersync.notify = plugins.json.read("./config.json").get("browsersync." + env + ".notify");

                // read remote settings from config.json
                global.settings.remote.hostname = plugins.json.read("./config.json").get("remote." + env + ".hostname");
                global.settings.remote.port     = plugins.json.read("./config.json").get("remote." + env + ".port");
                global.settings.remote.mode     = plugins.json.read("./config.json").get("remote." + env + ".mode");
                global.settings.remote.username = plugins.json.read("./config.json").get("remote." + env + ".username");
                global.settings.remote.password = plugins.json.read("./config.json").get("remote." + env + ".password");
                global.settings.remote.path     = plugins.json.read("./config.json").get("remote." + env + ".path");

                // configure remote credentials
                configure_json("remote", {
                    hostname: {
                        default: global.settings.remote.hostname,
                        type:    "input",
                    },
                    port: {
                        default: global.settings.remote.port ? global.settings.remote.port : 21,
                        type:    "input",
                    },
                    mode: {
                        default: global.settings.remote.mode === "ftp" ? 0 : global.settings.remote.mode === "tls" ? 1 : global.settings.remote.mode === "sftp" ? 2 : 0,
                        type:    "list",
                        choices: ["ftp", "tls", "sftp"],
                    },
                    username: {
                        default: global.settings.remote.username,
                        type:    "input",
                    },
                    password: {
                        default: global.settings.remote.password,
                        type:    "password",
                    },
                    path: {
                        default: global.settings.remote.path,
                        type:    "input",
                    },
                }, env, () => {
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
                    }, env, () => {
                        // resolve the promise
                        return resolve();
                    });
                });
            });
        });
    }
};
