# Contributing

This is only a brief guide to get you up and running with development. For a more robust guide, please see my [Contribution Guide on GitHub](https://github.com/JacobDB/contribution-guide).

## Important Notes

- **Never work out of the master branch!** All changes should be made on develop. master only gets updated when a new [release package](#releasing-a-package) is created.
- **Never edit `./dev` or `./dist` directly!** Changes made here are guaranteed to be overwritten. All changes should be made within `./src`.
- **Try to follow the existing style!** I realize it may be difficult to understand why some things are done the way they are. Speak with a senior developer if you have questions on how to name something, or where to put new code.
- **There is no need to edit the `./gulpfile.js` directly!** All configuration is done via `./config.json`, detailed below. In particular, **FTP credentials should never be stored directly in the `./gulpfile.js`.** This is a security measure, so that credentials never get uploaded.

## Required

1. [Node JS](https://nodejs.org/en/) &ndash; recommend latest Current relase)
2. [Gulp](https://github.com/gulpjs/gulp/blob/master/docs/getting-started.md)

## Recommended

1. [MAMP](https://www.mamp.info/en/) &ndash; A LAMP stack for desktop OS's. Useful for the `sync` gulp task.
2. [Atom](https://www.atom.io/) &ndash; A fantastic code editor that works well with git.
3. [GitKraken](https://www.gitkraken.com/) &ndash; A powerful GUI client for git.

## Getting Started

### Basic

1. Open a terminal window, and cd to where you'd like to clone the repository.
2. Run `git clone <repository_path>`, then cd in to it's root.
3. Run `npm install`. This will take a few minutes to run.
4. Run `gulp` in your terminal to compile `./src` to `./dev`.
5. Repeat step 4 every time you'd like to compile.

### Advanced

6. [Install WordPress locally with MAMP](https://codex.wordpress.org/Installing_WordPress_Locally_on_Your_Mac_With_MAMP).
7. Create a [symlink](http://www.howtogeek.com/howto/16226/complete-guide-to-symbolic-links-symlinks-on-windows-or-linux/) to `./dev` at `/Applications/MAMP/htdocs/wp-content/themes/dev` on Mac, or `C:\MAMP\htdocs\wp-content\themes\dev` on Windows. Note if you did not install MAMP to it's default directory, you should instead create the symlink where you installed it.
8. Make sure your MAMP server is running, and you have your dev theme activated.
9. Open a terminal, and navigate to the cloned repository's root. Run `gulp watch --sync` and enter the matching values when prompted. This will typically be the following:

        Browsersync proxy: localhost:8888
        Browsersync port: 8080
        Browsersync open: external
        Browsersync notify: false

    This will open your MAMP server in your default browser. You can now make edits in `./src`, and the page will automatically reload.

## Gulp Tasks

### `gulp`

This is the main task you need to be aware of. It compiles any changed files in `./src` in to `./dev`. You may also use the `--force` flag to recompile even if no changes have been made (i.e. `gulp --force`

### `gulp watch`

This task simply runs `gulp` every time a change is made in `./src`. If you followed the [Advanced setup instructions](#advanced), you can also use the flag `--sync` in order to automatically refresh the page every time a change is detected.

### `gulp ftp`

This task allows you to upload changed files from the terminal. On first run, it will prompt you for FTP credentials, storing them in `./config.json` locally. You may also use the flag `--ftp` in conjunction with the primary `gulp` task to compile and then immediately upload (i.e. `gulp --ftp`).

## Releasing a Package

**IMPORTANT NOTE:** Only a trained developer should create a build package. If you have not been so trained, **speak with a senior developer to go over this process!**
