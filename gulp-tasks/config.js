// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

module.exports = {
    config(gulp, plugins, requested = "", direct_call = false) {
        // task-specific plugins
        const REQUEST = require("request");
        const MKDIRP  = require("mkdirp");

        // store array of gist URIs
        const DATA_SOURCES = {
            browsersync: "https://gist.githubusercontent.com/JacobDB/63852a9ad21207ed195aa1fd75bfeeb8/raw/8fe578c2af7a4d31c2357d1a3c0f2cbc8c1cf42f",
            ftp:         "https://gist.githubusercontent.com/JacobDB/b41b59c11f10e6b5e4fe5bc4ab40d805/raw/3886b5c5c1e6e386fb56eb072d7d87f0952d5128",
            rsync:       "https://gist.githubusercontent.com/JacobDB/71f24559e2291c07256edf8a48028ae5/raw/987c48f67f2a92146d306be470583a247587cfe8",
        };

        // generate .config folder
        const GENERATE_CONFIG = (file_name, mode = "ftp") => {
            // write the file
            return new Promise((resolve, reject) => {
                MKDIRP(".config", () => {
                    // open the file
                    plugins.fs.stat(`.config/${file_name}`, (err) => {
                        // make sure the file doesn't exist (or otherwise has an error)
                        if (err !== null) {
                            // get the file contents from the gist URI
                            REQUEST.get(`${DATA_SOURCES[mode]}/${file_name}`, (error, response, body) => {
                                if (!error && response.statusCode == 200) {
                                    // write the file
                                    plugins.fs.writeFile(`.config/${file_name}`, body, "utf8", () => {
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
            });
        };

        // configue JSON data
        const CONFIGURE_JSON = (file_name, namespace, endpoint, options) => {
            // check if the intended endpoint has been configured
            const JSON_DATA  = plugins.json.readFileSync(`.config/${file_name}`);
            const CONFIGURED = JSON_DATA[endpoint] ? JSON_DATA[endpoint].configured : false;

            const PROMPTS = [];

            // if no requested, or requsted is current namespace
            if (["", namespace].indexOf(requested) >= 0) {

                // if config is called directly, or it's not configured
                if (direct_call || !CONFIGURED) {

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

                        PROMPTS.push(PROMPT);
                    });
                }
            }

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
                                    if (namespace === "ftp") {
                                        // ftp is handled special
                                        JSON_DATA[endpoint] = Object.assign({}, global.settings.ftp);
                                    } else {
                                        // clone the existing data
                                        JSON_DATA[endpoint] = Object.assign({}, JSON_DATA.default);
                                    }
                                }

                                // update the data
                                JSON_DATA[endpoint][key] = VALUE;

                                // store the updated data in the global settings
                                global.settings[namespace][key] = VALUE;
                            });

                            // update file with new JSON data
                            plugins.json.writeFileSync(`.config/${file_name}`, JSON_DATA, { spaces: 2 });
                        })).on("end", () => {
                            // mark new endpoints as configured
                            if (JSON_DATA[endpoint].configured === false) {
                                new Promise((resolve, reject) => {

                                    // if a new endpoints data is FTP or SFTP, retrieve the remaining options
                                    if (namespace === "ftp") {
                                        // get the protocol-specific fields from the gist URI
                                        REQUEST.get(`${DATA_SOURCES.ftp}/.${JSON_DATA[endpoint].protocol}specific`, (error, response, body) => {
                                            if (!error && response.statusCode == 200) {
                                                // add the protocol specific JSON data to the object
                                                JSON_DATA[endpoint] = Object.assign(JSON_DATA[endpoint], JSON.parse(body));

                                                // resolve the promise
                                                resolve();
                                            } else {
                                                reject();
                                            }
                                        });
                                    }
                                }).then(() => {
                                    // make sure "configured" goes to the bottom of the object
                                    delete JSON_DATA[endpoint].configured;

                                    // mark as configured
                                    JSON_DATA[endpoint].configured = true;

                                    // update file with new JSON data
                                    plugins.json.writeFileSync(`.config/${file_name}`, JSON_DATA, { spaces: 2 });
                                });
                            }

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
            GENERATE_CONFIG(".ftpconfig", "ftp"),
            GENERATE_CONFIG(".rsyncconfig", "rsync"),
        ]).then(() => {
            // read browsersync settings from .bsconfig
            const JSON_DATA = plugins.json.readFileSync(".config/.bsconfig");

            // get the data from the intended endpoint, or if it doesn't exist, default
            global.settings.browsersync = JSON_DATA[ENDPOINT] ? JSON_DATA[ENDPOINT] : JSON_DATA.default;
        }).then(() => {
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
            const JSON_DATA = plugins.json.readFileSync(".config/.ftpconfig");

            // get the FTP data from the endpoint if it exists
            global.settings.ftp = JSON_DATA[ENDPOINT] ? JSON_DATA[ENDPOINT] : false;

            // if the endpoint doesn't exist, get the default FTP data from the gist URI
            if (!global.settings.ftp) {
                // get the file contents from the gist URI
                REQUEST.get(`${DATA_SOURCES.ftp}/.ftpconfig`, (error, response, body) => {
                    if (!error && response.statusCode == 200) {
                        global.settings.ftp = JSON.parse(body).default;
                    }
                });
            }
        }).then(() => {
            // construct the prompts
            const PROMPTS = {
                protocol: {
                    default: global.settings.ftp.protocol === "ftp" ? 0 : 1,
                    type:    "list",
                    choices: ["ftp", "sftp"],
                    suffix: "(Once configured, an endpoints protocol cannot be changed)",
                },
                secure: {
                    default: global.settings.ftp.secure === true ? 0 : 1,
                    type:    "list",
                    choices: ["true", "false"],
                    when:    data => data.protocol === "ftp",
                },
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
                    default:  global.settings.ftp.remotePath,
                    type:     "input",
                },
            };

            // don't allow changing of protocol once configured
            if (global.settings.ftp.configured) {
                if (global.settings.ftp.protocol === "ftp") {
                    delete PROMPTS.protocol.choices[1];
                } else if (global.settings.ftp.protocol === "sftp") {
                    delete PROMPTS.protocol.choices[0];
                }
            }

            // configure the JSON
            return CONFIGURE_JSON(".ftpconfig", "ftp", ENDPOINT, PROMPTS);
        }).then(() => {
            // read ftp settings from .ftpconfig
            const JSON_DATA = plugins.json.readFileSync(".config/.rsyncconfig");

            // get the data from the intended endpoint, or if it doesn't exist, default
            global.settings.rsync = JSON_DATA[ENDPOINT] ? JSON_DATA[ENDPOINT] : JSON_DATA.default;
        }).then(() => {
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
                    suffix:  "(Set to false to rsync locally)",
                },
                username: {
                    default: global.settings.rsync.username,
                    type:    "input",
                    when:    data => data.hostname !== "false",
                },
            };

            // configure the JSON
            return CONFIGURE_JSON(".rsyncconfig", "rsync", ENDPOINT, PROMPTS);
        });
    }
};
