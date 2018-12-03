## `not found: notify-send`

This error typically occurs when the notification library is missing on Linux. To fix this, install the package `libnotify-bin`. On Ubuntu, this can be achieved with the following command:

```sh
sudo apt-get install libnotify-bin -y
```

## `npm ERR! Failed at the node-sass@X.X.X install script 'node scripts/install.js'.`

This error typically occurs when compilation libraries are missing on Linux. To fix this, install the package `build-essential`. On Ubuntu, this can be achieved with the following command:

```sh
sudo apt-get install build-essential -y
```

## `nvm: command not found` on Mac

This error typically occurs when your path is not set correctly for Node Version Manager. To fix this, create or modify a file `~/.bash_profile` and add the following lines of code to it:

```sh
export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" # This loads nvm
```

### `ERROR in ./src/assets/scripts/modern/fontawesome.init.js`

This error typically occurs when FontAwesome Pro is not installed correctly. Please refer to the [getting started](getting-started.md#fontawesome-5-pro) guide for more information on configuring FontAwesome 5 Pro.

### `404 Not Found: @fortawesome/fontawesome-pro@latest`

This error typically occurs when FontAwesome Pro is not installed correctly. Please refer to the [getting started](getting-started.md#fontawesome-5-pro) guide for more information on configuring FontAwesome 5 Pro.
