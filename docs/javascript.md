# JavaScript

## Quick reference

WIP

## Methodology

WIP

For the most part, utilizing ESLint should ensure that your code follows the standards of this project.

## Creating new master scripts

New master scripts should generally be avoided, but if the need arises to create one, simply create a new folder in `./src/assets/scripts` and all the script files within it will be compiled to a file bearing the same name as the folder (i.e. `./src/assets/scripts/modern/**/*.js` outputs to `./dev/assets/scripts/modern.js`). The new script will then need registered and enqueud in `./src/functions/styles-scripts.php`.

## Adding third-party scripts

Third party scripts should be managed through [NPM](https://www.npmjs.com/). When looking for new libraries to include, try to keep in mind the weight of doing so. For example, take a look at the dependencies for the library, and the size of the library itself. In some cases, it might be more efficient to write your own scripts.

To install a library from NPM, install it via:

```sh
npm install --save library-name
```

To use the new library, import it in your script like so:

```js
import SomeFunction from "library-name";

SomeFunction();
```
