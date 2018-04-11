// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

module.exports = {
    // config task, generate configuration file for uploads & BrowserSync and prompt dev for input
    config(gulp, plugins, requested = "") {
        // generate config.json and start other functions
        const generate_config = (file_name, mode = "ftp") => {
            // store array of config file URIs
            const data_source = {
                bs:    "https://gist.githubusercontent.com/JacobDB/63852a9ad21207ed195aa1fd75bfeeb8/raw/7d011d22bef966f06d0c8b84d50891419738ac8b/.bsconfig",
                ftp:   "https://gist.githubusercontent.com/JacobDB/b41b59c11f10e6b5e4fe5bc4ab40d805/raw/1edc9488cccf2200831b13565a02180fce2afc5a/.ftpconfig",
                sftp:  "https://gist.githubusercontent.com/JacobDB/cad97b5c4e947b40e3b54c6022fec887/raw/31ade808eb3b90940864271a9236be7c45e8233e/.ftpconfig",
                rsync: "https://gist.githubusercontent.com/JacobDB/71f24559e2291c07256edf8a48028ae5/raw/91214db28e8f9cbbecb7f8ddcf5e42cbe5c5b0a8/.rsyncconfig",
            };

            // check which config URI to use
            const gist_url = typeof file_name !== "undefined" ? (file_name === ".bsconfig" ? data_source.bs : (file_name === ".ftpconfig" ? (mode === "sftp" ? data_source.sftp : data_source.ftp) : (mode === "rsync" ? data_source.rsync : ""))) : "";

            // write the file
            return new Promise((resolve, reject) => {
                // open the file
                plugins.fs.stat(file_name, (err) => {
                    // make sure the file doesn't exist (or otherwise has an error)
                    if (err !== null) {
                        // get the file contents from the gist_url
                        plugins.request.get(gist_url, (error, response, body) => {
                            if (!error && response.statusCode == 200) {
                                // write the file
                                plugins.fs.writeFile(file_name, body, "utf8", () => {
                                    // resolve the promise
                                    resolve();
                                });
                            } else {
                                reject();
                            }
                        });
                    } else {
                        // automatically resolve the promise if the file already exists
                        resolve();
                    }
                });
            });
        };

        // configue JSON data
        const configure_json = (file_name, namespace, options) => {
            const prompts    = [];
            const configured = plugins.json.readFileSync(file_name).configured;

            // construct the prompts
            Object.keys(options).forEach(option => {
                const properties = options[option];

                // construct the prompt
                const prompt = {
                    name:    option,
                    message: namespace + " " + option + ": ",
                };

                // construct the prompt
                Object.keys(properties).forEach(property => {
                    prompt[property] = properties[property];
                });

                // check if the setting has no value or is explicitly requested
                if ((requested !== "" && requested === namespace && (global.settings[namespace][option] === "" || configured === false)) || gulp.seq.indexOf("config") >= 0 && (requested === "" || requested === namespace) && (global.settings[namespace][option] === "" || plugins.argv[namespace] || configured === false)) {
                    prompts.push(prompt);
                }
            });

            return new Promise ((resolve, reject) => {
                if (prompts.length > 0) {
                    // prompt the user
                    gulp.src(file_name)
                        .pipe(plugins.prompt.prompt(prompts, (res) => {
                            // read the file to retrieve the JSON data
                            const json_data = plugins.json.readFileSync(file_name);

                            // update options in JSON data
                            Object.keys(options).forEach(key => {
                                // turn stringy true/false values in to booleans
                                const value = res[key] === "true" ? true : (res[key] === "false" ? false : res[key]);

                                // update the data
                                json_data[key] = value;

                                // store the updated data in the global settings
                                global.settings[namespace][key] = value;
                            });

                            // update file with new JSON data
                            plugins.json.writeFileSync(file_name, json_data, {spaces: 2});
                        })).on("end", () => {
                            // read the file to retrieve the JSON data
                            const json_data = plugins.json.readFileSync(file_name);

                            // mark as configured
                            json_data["configured"] = true;

                            // update file with new JSON data
                            plugins.json.writeFileSync(file_name, json_data, {spaces: 2});

                            // resolve the promise
                            resolve();
                        }).on("error", () => {
                            // reject the promise
                            reject();
                        });
                } else {
                    // automatically resolve the promise if no prompts exist
                    resolve();
                }
            });
        };

        const download_configs = () => {
            // download all config files
            return Promise.all([
                generate_config(".bsconfig", "browsersync"),
                generate_config(".ftpconfig", (plugins.argv["sftp"] ? "sftp" : (plugins.argv["ftps"] ? "ftps" : "ftp"))),
                generate_config(".rsyncconfig", "rsync")
            ]);
        };

        const configure_browsersync = () => {
            // read browsersync settings from .bsconfig
            global.settings.browsersync = plugins.json.readFileSync(".bsconfig");

            // construct the prompts
            const prompts = {
                proxy: {
                    default: global.settings.browsersync.proxy,
                    type:    "input",
                },
                port: {
                    default: global.settings.browsersync.port,
                    type:    "input",
                },
                open: {
                    default: global.settings.browsersync.open === "local" ? 1 : (global.settings.browsersync.open === "external" ? 2 : (global.settings.browsersync.open === "ui" ? 3 : (global.settings.browsersync.open === "ui-external" ? 4 : (global.settings.browsersync.open === "tunnel" ? 5 : (global.settings.browsersync.open === "false" ? 6 : 0))))),
                    type:    "list",
                    choices: ["true", "local", "external", "ui", "ui-external", "tunnel", "false"],
                },
                notify: {
                    default: global.settings.browsersync.notify === true ? 0 : 1,
                    type:    "list",
                    choices: ["true", "false"],
                },
            };

            // configure the JSON
            return configure_json(".bsconfig", "browsersync", prompts);
        };

        const configure_ftp = () => {
            // read ftp settings from .ftpconfig
            global.settings.ftp = plugins.json.readFileSync(".ftpconfig");

            // construct the prompts
            const prompts = {
                host: {
                    default: global.settings.ftp.host,
                    type:    "input",
                },
                user: {
                    default: global.settings.ftp.user,
                    type:    "input",
                },
                pass: {
                    default: global.settings.ftp.pass,
                    type:    "password",
                },
                remotePath: {
                    default: global.settings.ftp.remotePath,
                    type:    "input",
                },
                secure: {
                    default: global.settings.ftp.secure === true ? 0 : 1,
                    type:    "list",
                    choices: ["true", "false"],
                },
            };

            // don't prompt for protocol for SFTP
            if (global.settings.ftp.protocol === "sftp") {
                delete prompts.secure;
            }

            // configure the JSON
            return configure_json(".ftpconfig", "ftp", prompts);
        };

        const configure_rsync = () => {
            // read ftp settings from .ftpconfig
            global.settings.rsync = plugins.json.readFileSync(".rsyncconfig");

            // construct the prompts
            const prompts = {
                destination: {
                    default: global.settings.rsync.destination,
                    type:    "input",
                },
                root: {
                    default: global.settings.rsync.root,
                    type:    "input",
                },
                hostname: {
                    default: global.settings.rsync.hostname,
                    type:    "input",
                },
                username: {
                    default: global.settings.rsync.username,
                    type:    "input",
                },
            };

            // configure the JSON
            return configure_json(".rsyncconfig", "rsync", prompts);
        };

        // download and configure config files
        return download_configs()
            .then(configure_browsersync)
            .then(configure_ftp)
            .then(configure_rsync);
    }
};
