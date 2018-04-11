// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

module.exports = {
    // config task, generate configuration file for uploads & BrowserSync and prompt dev for input
    config(GULP, PLUGINS, REQUESTED = "") {
        // generate config.json and start other functions
        const GENERATE_CONFIG = (file_name, mode = "ftp") => {
            // store array of config file URIs
            const DATA_SOURCE = {
                bs:    "https://gist.githubusercontent.com/JacobDB/63852a9ad21207ed195aa1fd75bfeeb8/raw/7d011d22bef966f06d0c8b84d50891419738ac8b/.bsconfig",
                ftp:   "https://gist.githubusercontent.com/JacobDB/b41b59c11f10e6b5e4fe5bc4ab40d805/raw/1edc9488cccf2200831b13565a02180fce2afc5a/.ftpconfig",
                sftp:  "https://gist.githubusercontent.com/JacobDB/cad97b5c4e947b40e3b54c6022fec887/raw/31ade808eb3b90940864271a9236be7c45e8233e/.ftpconfig",
                rsync: "https://gist.githubusercontent.com/JacobDB/71f24559e2291c07256edf8a48028ae5/raw/91214db28e8f9cbbecb7f8ddcf5e42cbe5c5b0a8/.rsyncconfig",
            };

            // check which config URI to use
            const GIST_URL = typeof file_name !== "undefined" ? (file_name === ".bsconfig" ? DATA_SOURCE.bs : (file_name === ".ftpconfig" ? (mode === "sftp" ? DATA_SOURCE.sftp : DATA_SOURCE.ftp) : (mode === "rsync" ? DATA_SOURCE.rsync : ""))) : "";

            // write the file
            return new Promise((resolve, reject) => {
                // open the file
                PLUGINS.fs.stat(file_name, (err) => {
                    // make sure the file doesn't exist (or otherwise has an error)
                    if (err !== null) {
                        // get the file contents from the GIST_URL
                        PLUGINS.request.get(GIST_URL, (error, response, body) => {
                            if (!error && response.statusCode == 200) {
                                // write the file
                                PLUGINS.fs.writeFile(file_name, body, "utf8", () => {
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
        const CONFIGURE_JSON = (file_name, namespace, options) => {
            const PROMPTS    = [];
            const CONFIGURED = PLUGINS.json.readFileSync(file_name).configured;

            // construct the prompts
            Object.keys(options).forEach(option => {
                const PROPERTIES = options[option];

                // construct the prompt
                const PROMPT = {
                    name:    option,
                    message: namespace + " " + option + ": ",
                };

                // construct the prompt
                Object.keys(PROPERTIES).forEach(property => {
                    prompt[property] = PROPERTIES[property];
                });

                // check if the setting has no value or is explicitly requested
                if ((REQUESTED !== "" && REQUESTED === namespace && (global.settings[namespace][option] === "" || CONFIGURED === false)) || GULP.seq.indexOf("config") >= 0 && (REQUESTED === "" || REQUESTED === namespace) && (global.settings[namespace][option] === "" || PLUGINS.argv[namespace] || CONFIGURED === false)) {
                    PROMPTS.push(PROMPT);
                }
            });

            return new Promise ((resolve, reject) => {
                if (PROMPTS.length > 0) {
                    // prompt the user
                    GULP.src(file_name)
                        .pipe(PLUGINS.prompt.prompt(PROMPTS, (res) => {
                            // read the file to retrieve the JSON data
                            const JSON_DATA = PLUGINS.json.readFileSync(file_name);

                            // update options in JSON data
                            Object.keys(options).forEach(key => {
                                // turn stringy true/false values in to booleans
                                const VALUE = res[key] === "true" ? true : (res[key] === "false" ? false : res[key]);

                                // update the data
                                JSON_DATA[key] = VALUE;

                                // store the updated data in the global settings
                                global.settings[namespace][key] = VALUE;
                            });

                            // update file with new JSON data
                            PLUGINS.json.writeFileSync(file_name, JSON_DATA, {spaces: 2});
                        })).on("end", () => {
                            // read the file to retrieve the JSON data
                            const JSON_DATA = PLUGINS.json.readFileSync(file_name);

                            // mark as configured
                            JSON_DATA["configured"] = true;

                            // update file with new JSON data
                            PLUGINS.json.writeFileSync(file_name, JSON_DATA, {spaces: 2});

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

        const DOWNLOAD_CONFIGS = () => {
            // download all config files
            return Promise.all([
                GENERATE_CONFIG(".bsconfig", "browsersync"),
                GENERATE_CONFIG(".ftpconfig", (PLUGINS.argv["sftp"] ? "sftp" : (PLUGINS.argv["ftps"] ? "ftps" : "ftp"))),
                GENERATE_CONFIG(".rsyncconfig", "rsync")
            ]);
        };

        const CONFIGURE_BROWSERSYNC = () => {
            // read browsersync settings from .bsconfig
            global.settings.browsersync = PLUGINS.json.readFileSync(".bsconfig");

            // construct the prompts
            const PROMPTS = {
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
            return CONFIGURE_JSON(".bsconfig", "browsersync", PROMPTS);
        };

        const CONFIGURE_FTP = () => {
            // read ftp settings from .ftpconfig
            global.settings.ftp = PLUGINS.json.readFileSync(".ftpconfig");

            // construct the prompts
            const PROMPTS = {
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
                delete PROMPTS.secure;
            }

            // configure the JSON
            return CONFIGURE_JSON(".ftpconfig", "ftp", PROMPTS);
        };

        const CONFIGURE_RSYNC = () => {
            // read ftp settings from .ftpconfig
            global.settings.rsync = PLUGINS.json.readFileSync(".rsyncconfig");

            // construct the prompts
            const PROMPTS = {
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
            return CONFIGURE_JSON(".rsyncconfig", "rsync", PROMPTS);
        };

        // download and configure config files
        return DOWNLOAD_CONFIGS()
            .then(CONFIGURE_BROWSERSYNC)
            .then(CONFIGURE_FTP)
            .then(CONFIGURE_RSYNC);
    }
};
