// JavaScript Document

// Scripts written by __gulp_init__author_name @ __gulp_init__author_company

module.exports = {
    build(gulp, plugins, ran_tasks, on_error) {
        // task-specific plugins
        const latest_semver = require("latest-semver");
        const semver_regex  = require("semver-regex");

        // get the current version number
        const current_version = plugins.json.readFileSync("package.json").version;

        // prompt for a new version number, higher than the current
        const prompt_for_version = () => {
            return new Promise ((resolve, reject) => {
                // prompt the user
                gulp.src("CHANGELOG.md")
                    .pipe(plugins.prompt.prompt({
                        type:     "number",
                        name:     "new_version",
                        message:  "Version number:",
                        validate: (new_version) => {
                            if (semver_regex().test(new_version)) {
                                const newer_version = latest_semver([
                                    current_version,
                                    new_version,
                                ]);

                                if (newer_version === new_version && new_version !== current_version) {
                                    return true;
                                } else {
                                    console.log("\n\x1b[31m!\x1b[0m You must enter a higher version than " + current_version + ".");
                                    return false;
                                }
                            } else {
                                console.log("\n\x1b[31m!\x1b[0m You must enter a valid semantic version string.");
                                return false;
                            }
                        }
                    }, (res) => {
                        resolve(res.new_version);
                    }).on("error", () => {
                        // reject the promise
                        reject();
                    }));
            });
        };

        return new Promise ((resolve) => {
            prompt_for_version().then((new_version) => {
                console.log(new_version);
                resolve();
            });
        });
    }
};
