// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    config(gulp, plugins, requested = "") {
        // task-specific plugins
        const REQUEST = require("request");

        // generate config.json and start other functions
        const GENERATE_CONFIG = (file_name, mode = "ftp") => {
            // store array of config file URIs
            const DATA_SOURCE = {
                browsersync: "https://gist.githubusercontent.com/JacobDB/63852a9ad21207ed195aa1fd75bfeeb8/raw/8fe578c2af7a4d31c2357d1a3c0f2cbc8c1cf42f/.bsconfig",
                ftp:         "https://gist.githubusercontent.com/JacobDB/b41b59c11f10e6b5e4fe5bc4ab40d805/raw/7f835b5bb32b00f8941c2cc93177bff5362f87fb/.ftpconfig",
                sftp:        "https://gist.githubusercontent.com/JacobDB/cad97b5c4e947b40e3b54c6022fec887/raw/a914ac95afed13051337cfe1a706afcf6520469e/.ftpconfig",
                rsync:       "https://gist.githubusercontent.com/JacobDB/71f24559e2291c07256edf8a48028ae5/raw/987c48f67f2a92146d306be470583a247587cfe8/.rsyncconfig",
            };

            // write the file
            return new Promise((resolve, reject) => {
                // open the file
                plugins.fs.stat(`.config/${file_name}`, (err) => {
                    // make sure the file doesn't exist (or otherwise has an error)
                    if (err !== null) {
                        // get the file contents from the gist_url
                        REQUEST.get(DATA_SOURCE[mode], (error, response, body) => {
                            if (!error && response.statusCode == 200) {
                                // write the file
                                plugins.fs.writeFile(`.config/${file_name}`, body, "utf8", () => {
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
        const CONFIGURE_JSON = (file_name, namespace, endpoint, options) => {
            // check if the intended endpoint has been configured
            const JSON_DATA  = plugins.json.readFileSync(`.config/${file_name}`);
            const CONFIGURED = JSON_DATA[endpoint] ? JSON_DATA[endpoint].configured : false;

            const PROMPTS = [];

            // construct the prompts
            Object.keys(options).forEach(option => {
                const PROPERTIES = options[option];

                // construct the prompt
                const PROMPT = {
                    name:    option,
                    message: `${namespace} ${option}: `,
                };

                // construct the prompt
                Object.keys(PROPERTIES).forEach(property => {
                    PROMPT[property] = PROPERTIES[property];
                });

                // check if the setting has no value or is explicitly requested
                if ((requested !== "" && requested === namespace && (global.settings[namespace][option] === "" || CONFIGURED === false)) || gulp.seq.indexOf("config") >= 0 && (requested === "" || requested === namespace) && (global.settings[namespace][option] === "" || plugins.argv[namespace] || CONFIGURED === false)) {
                    PROMPTS.push(PROMPT);
                }
            });

            return new Promise ((resolve, reject) => {
                if (PROMPTS.length > 0) {
                    // prompt the user
                    gulp.src(`.config/${file_name}`)
                        .pipe(plugins.prompt.prompt(PROMPTS, (res) => {
                            // update options in JSON data
                            Object.keys(options).forEach(key => {
                                // turn stringy true/false values in to booleans
                                const VALUE = res[key] === "true" ? true : (res[key] === "false" ? false : res[key]);

                                // add the endpoint to the JSON data if it doesn't exist
                                if (!JSON_DATA[endpoint]) {
                                    JSON_DATA[endpoint]            = Object.assign({}, JSON_DATA.default);
                                    JSON_DATA[endpoint].configured = false;
                                }

                                // update the data
                                JSON_DATA[endpoint][key] = VALUE;

                                // store the updated data in the global settings
                                global.settings[namespace][key] = VALUE;
                            });

                            // update file with new JSON data
                            plugins.json.writeFileSync(`.config/${file_name}`, JSON_DATA, { spaces: 2 });
                        })).on("end", () => {
                            // mark as configured
                            JSON_DATA[endpoint].configured = true;

                            // update file with new JSON data
                            plugins.json.writeFileSync(`.config/${file_name}`, JSON_DATA, { spaces: 2 });

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

        // check the intended endpoint
        const ENDPOINT = plugins.argv.endpoint ? plugins.argv.endpoint : (process.env.ENDPOINT ? process.env.ENDPOINT : "default");

        // download all config files
        return Promise.all([
            GENERATE_CONFIG(".bsconfig", "browsersync"),
            GENERATE_CONFIG(".ftpconfig", (plugins.argv["sftp"] ? "sftp" : (plugins.argv["ftps"] ? "ftps" : "ftp"))),
            GENERATE_CONFIG(".rsyncconfig", "rsync")
        ]).then(() => {
            // read browsersync settings from .bsconfig
            const JSON_DATA             = plugins.json.readFileSync(".config/.bsconfig");
            global.settings.browsersync = JSON_DATA[ENDPOINT] ? JSON_DATA[ENDPOINT] : JSON_DATA.default;

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
            return CONFIGURE_JSON(".bsconfig", "browsersync", ENDPOINT, PROMPTS);
        }).then(() => {
            // read ftp settings from .ftpconfig
            const JSON_DATA     = plugins.json.readFileSync(".config/.ftpconfig");
            global.settings.ftp = JSON_DATA[ENDPOINT] ? JSON_DATA[ENDPOINT] : JSON_DATA.default;

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
            return CONFIGURE_JSON(".ftpconfig", "ftp", ENDPOINT, PROMPTS);
        }).then(() => {
            // read ftp settings from .ftpconfig
            const JSON_DATA       = plugins.json.readFileSync(".config/.rsyncconfig");
            global.settings.rsync = JSON_DATA[ENDPOINT] ? JSON_DATA[ENDPOINT] : JSON_DATA.default;

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
            return CONFIGURE_JSON(".rsyncconfig", "rsync", ENDPOINT, PROMPTS);
        });
    }
};
