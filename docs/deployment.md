# Deployment

## Quick Reference

WIP

## Development Releases

While working on changes to this project, you should create a variant theme diretory ending in `-dev` to test out of. For example, if the theme folder is `__gulp_init_npm_name__`, name your development folder `__gulp_init_npm_name__-dev`. Activate this theme while working on your changes, and switch back to the "live" theme when finished.

**Note:** You may need to re-assign things like menu locations, customizations, widgets, etc. when activating the development theme.

## Distribution Releases

When a change or set of changes is completed, a distribution package should be created. This process performs various optimizations on the code to decrease page load times, obfuscate compiled code, remove extraneous files, etc.

To create a `dist` package, follow the steps below:

1. Ensure all changes are commited and pushed to the `release` branch.

2. Ensure that all changes are described in `CHANGELOG.md` in accordance with [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

    - Not every commit requires a line in `CHANGELOG.md`. For example, if over the course of 7 commits various styles on the navigation where adjusted, a single line should be added under "Changed" with a message such as "Adjusted navigation styles."

    - Details in `CHANGELOG.md` should be simplified, and human readable. For example, rather than "Implemented new randomization algorithm for sorting posts," a message such as "Improve randomness of post order" would be more appropriate.

3. Update the `package.json` and `package-lock.json` with a new version number, in line with [Semantic Versioning](https://semver.org/).

    - If the theme has been nearly completely rewritten, a **major** version bump should be used.

    - If a significant new feature was added, such as a new Store Locator feature, or an Events list on the home page, a **minor** version bump should be used.

    - If only minor adjustments were made, such as increasing the `line-height` on paragraph, a **patch** version bump should be used.

4. Update `CHANGELOG.md`, moving everything under "Unreleased" in to a new section titled with the new version number and the date of commit. For example, `[1.3.7] - 2019-05-21`. Also be sure to update the version diff list at the bottom of the file.

7. Delete the current `dist` folder, and run `gulp --dist` to create the revised package. If critical CSS is to be used, you can also specify `--experimental critical` as an additional parameter, resulting in `gulp --dist --experimental critical`.

8. Commit the new build package with the message `Build package for v1.3.7`, replacing `1.3.7` with the new version number.

9. Tag the commit with the message and annotation `v1.3.7`, replacing `1.3.7` with the new version number.

10. Push up the change to `release` with `git push origin release --follow-tags`.

11. Open the repository, and create a new merge/pull request from `release` in to `master`. Assign the merge/pull request to a user who is in charge of the repository. They can then handle the last step, listed below.

12. If you are in charge of the repository, wait for any CI pipelines to finish, and then merge the release in to master.

13. If this project does not use Continuous Deployment, upload the new `dist` package to the original theme folder, and re-activate the theme if necessary. Otherwise, see [Continuous Deployment](#continuous-deployment) below.

## Continuous Deployment

If this project uses Continuous Deployment, the live theme folder should be a symlink. If this is the case, then the theme will automatically pull down the latest code when it gets merged in the `master`. This is done by using [git-deploy](https://github.com/vicenteguerra/git-deploy).

If Continuous Deployment isn't already set up, and you'd like to enable it, follow the steps below.

WIP
