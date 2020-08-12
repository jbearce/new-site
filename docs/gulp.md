# Gulp

## Quick Reference

- `gulp` &ndash; All core tasks; [see flags](#flags)
- `gulp watch` &ndash; Automate all core tasks
- `gulp styles` &ndash; Process styles
- `gulp scripts` &ndash; Process scripts
- `gulp html` &ndash; Process HTML & PHP
- `gulp media` &ndash; Process images
- `gulp upload` &ndash; Upload via FTP, FTPS, or SFTP
- `gulp rsync` &ndash; Upload via rsync
- `gulp sync` &ndash; Start BrowserSync
- `gulp config` &ndash; Configure settings for `sync`, `upload`, or `rsync` tasks
- `gulp init` &ndash; Initialize the repository for a new project

## Tasks

### `gulp`

1. Runs `styles`, `scripts`, `html`, and `media` simultaneously

2. Runs optional tasks based on flags passed.

### `gulp watch`

1. Automates `gulp`, allowing you to automatically compile every time a change is made.

*Works great with `--upload` or `--rsync`.*

### `gulp styles`

1. Generates critical CSS &ndash; *If `--experimental critical` is passed.*

2. Checks if styles have been modified, and therefore need to be compiled.

3. Lints styles with [stylelint](https://stylelint.io/).

4. Compiles SCSS &ndash; *Compressed if `--dist` is passed.*

5. Compiles PostCSS.

6. Hashes the output file names.

7. Writes a sourcemap &ndash; *Only if `--dist` isn't passed.*

8. Outputs to `./dev` &ndash; *Or `./dist` if `--dist` is passed.*

### `gulp scripts`

1. Checks if scripts have been modified, and therefore need to be compiled.

2. Lints scripts with [ESLint](https://eslint.org/).

3. Runs webpack.

    1. Writes imports.

    2. Transpiles to ES2015.

    3. Compresses scripts &ndash; *Only if `--dist` is passed.*

    4. Write a sourcemap &ndash; *Only if `--dist` isn't passed.*

4. Hashes the output file names, except `service-worker.js`.

7. Outputs to `./dev` &ndash; *Or `./dist` if `--dist` is passed.*

### `gulp html` or `gulp php`

1. Copies any new or modified binary files (such as `./src/screenshot.png`) to `./dev` &ndash; *Or `./dist` if `--dist` is passed.*

2. Copies any new or modified Composer files to `./dev` &ndash; *Or `./dist` if `--dist` is passed.*

3. Checks if HTML (or any files excluding `./src/assets`) has been modified, and therefore need to be compiled.

4. Replaces any custom lodash templates (i.e. `<%= pwa_name %>`).

5. Outputs to `./dev` &ndash; *Or `./dist` if `--dist` is passed.*

### `gulp media`

1. Copies any new or modified font files to `./dev` &ndash; *Or `./dist` if `--dist` is passed.*

2. Checks if images have been modified, and therefore need compiled.

3. Run compression on all images.

4. Outputs to `./dev` &ndash; *Or `./dist` if `--dist` is passed.*

### `gulp upload`

1. Checks for FTP settings in `./.config/.ftpconfig`.

2. If no FTP settings are found, downloads a template `.ftpconfig` and prompts for the most important values.

3. Connects to the remote server.

4. Uploads changed files to the remote server.

*Can be used with `--endpoint` to change the upload destination*

### `gulp rsync`

1. Checks for rsync settings in `./.config/.rsyncconfig`.

2. If no rsync settings are found, downloads a template `.rsyncconfig` and prompts for the most important values.

3. Rsyncs to the destination.

*Can be used with `--endpoint` to change the rsync destination*

### `gulp sync`

*This task doesn't do much standalone &ndash; it's to be used with `watch`.*

1. Checks for BrowserSync settings in `./.config/.bsconfig`.

2. If no BrowserSync settings are found, downloads a template `.bsconfig` and prompts for the most important values.

3. Adds custom HTTP headers to the BrowserSync settings, which tells the theme how to load the BrowserSync script.

4. Starts a BrowserSync session.

*Can be used with `--endpoint` to change the BrowserSync proxy*

### `gulp config`

1. Checks for existence of `./.config/.bsconfig`, `./.config/.ftpconfig`, and `./.config/.rsyncconfig`, and downloads them if they don't exist.

2. Prompts for important BrowserSync settings, and writes them to `./.config/.bsconfig`.

3. Prompts for important FTP settings, and writes them to `./.config/.ftpconfig`.

4. Prompts for important rsync settings, and writes them to `./.config/.rsyncconfig`.

*Can be used with `--sync`, `--upload`, or `--rsync` to configure a specific file.*
*Can be used with `--sftp` to configure for SFTP (normally defaults to FTP/S).*

### `gulp init`

*This task should only be used when first setting up the repository.*

1. Prompts for various settings such as the name of the project, the repository URL, and the author name.

2. Runs through all non-binary files in the repository and replaces `__gulp_init_{variable}__` with the values entered.

*To automate settings, create the file `./.config/.init` with a JSON object aligning with the object in the task.*

## Flags

- `-d | --dist` &ndash; Change output directory to `dist`, run additional compression and optimizations. **Note:** this flag should only be used to create build packages.
- `-e | --endpoint {endpoint_name}` &ndash; Change target endpoint to one specific in your configuration files
- `-r | --rsync` &ndash; Run rsync; best used with `gulp` and `gulp watch`
- `-s | --sync` &ndash; Start BrowserSync; best used with `gulp watch`
- `-u | --upload` &ndash; Upload with FTP, FTPS, or SFTP; best used with `gulp` and `gulp watch`
- `-x | --experimental` &ndash; Run optional experimental operations

## `.env`

A `.env` file can be set up in `./.config` to set the default endpoint for the `upload`, `rsync`, and `sync` tasks. It should be formatted as such:

```
ENDPOINT={endpoint_name}
```

## Creating New Tasks

WIP
