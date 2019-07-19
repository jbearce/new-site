# Media

## Quick reference

- Avoid adding media if at all possible.
- Use FontAwesome for icons.
- Any third-party media should be added under `./src/assets/media/vendor`.

## Methodology

In general, as few images as possible should be included with the theme. If an image makes sense to be part of the content, it should be included there, and not as a part of the theme.

### Generating Mobile Icons and Splash Screens

Within the media folder, there are folders named `android`, `ios`, and `safari`. Each of these folders contains a number of images that will need modified when the project is first set up, or a logo change occurs.

### Android & iOS

To facilitate icon creation for Android & iOS, I wrote a custom CLI module called [pwa-icon-generator](https://www.npmjs.com/package/pwa-icon-generator). Using this tool, we can very quickly generate all the necessary icons for Android and iOS.

1. Open Terminal.

2. Install the CLI tool.

    ```sh
    npm install --global pwa-icon-generator
    ```

3. Create a source icon in an image editing application such as Photoshop or Affinity Photo.

    1. Create a new document sized 2048x2048.

    2. Add your custom icon to the document, and center it.

    3. Resize the icon to 2040 in its longest dimension.

    4. If possible, overlay the icon with solid white. Depending on the complexity of the icon, this may not work well, so use your best judgment.

    5. Save the icon as `source.png`.

4. In terminal, change directory to the location the icon is saved.

5. Run the command `generate-icons --icon source.png --color $THEME_COLOR`, replacing `$THEME_COLOR` with the HEX code (without the `#`) for the theme color of the website, typically found in `package.json` (**NOTE:** If you could not overlay the icon with solid white, use `FFFFFF` as the background color.).

6. Once complete, copy the contents of the `media` directory in to `./src/assets/media`, replacing the existing `ios` and `android` directories.

### Favicon

Google recommends using a 144x144 favicon, which luckily got generated for Android already.

1. Copy the `./src/assets/media/android/launcher-icon-144x144.png` to `./src/assets/media/logo-favicon.png`.

### Safari

Pinned tabs in Safari can offer their own custom SVG favicons. These icons must be a single path on a canvas sized 16x16.

1. Open a vector editing application, such as Adobe Illustrator or Affinity designer.

2. Create a new document sized 16x16.

3. Add your cusotm icon to the document, and center it.

4. Make sure that the icon is merged in to a single path.

5. Resize the icon to 14.5 in its longest dimension.

6. Set the fill on the icon to solid black.

7. Export the icon to `./src/assets/media/safari/mask-icon.svg`.

8. In a text editor such as VS Code, open `mask-icon.svg`.

9. Clean out everything except the singular `<path>` and the containing `<svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">`.

### FontAwesome Icons

This project uses FontAwesome 5 Pro by default. To use an icon, please refer to [FontAwesome's icon list](https://fontawesome.com/icons).

#### Configuring FontAwesome Pro

This project uses FontAweseom 5 Pro, but may not come with an `.npmrc` preconfigured. Please refer to the [getting started](getting-started.md#fontawesome-5-pro) guide for more information on configuring FontAwesome 5 Pro.

#### Custom Icons

Icons should be prioritized as FontAwesome icons, but if a corresponding icons isn't defined in FontAwesome, or a specific icon is required to be used, a custom FontAwesome icon can be set up as long as you have a single-path SVG for the icon, using FontAwesome's [`library.add()` API](https://fontawesome.com/how-to-use/with-the-api/methods/library-add).

For example, to create a custom icon to represent this projects logo (a rocket, by default), open `./src/assets/scripts/fontawesome/fontawesome.init.js`, and define the icon as demonstrated below. Note that multiple icons can be defined, as `library.add()` accepts an object containing multiple icons.

```js
// JavaScript Document

// Scripts written by __gulp_init_author_name__ @ __gulp_init_author_company__

import { library, dom } from "@fortawesome/fontawesome-svg-core";

document.addEventListener("DOMContentLoaded", function () {
    window.FontAwesome.library.add({
        "__gulp_init_npm_name__": { // icon name
            iconName: "__gulp_init_npm_name__", // icon name
            prefix: "fac", // icon prefix
            icon: [
                512, // viewbox x
                512, // viewbox y
                [],  // ligature (unused)
                "e000", // unicode character (recommend e000-e999)
                "M505.1 19.1C503.8 13 499 8.2 492.9 6.9 460.7 0 435.5 0 410.4 0 307.2 0 245.3 55.2 199.1 128H94.9c-18.2 0-34.8 10.3-42.9 26.5L2.6 253.3c-8 16 3.6 34.7 21.5 34.7h95.1c-5.9 12.8-11.9 25.5-18 37.7-3.1 6.2-1.9 13.6 3 18.5l63.6 63.6c4.9 4.9 12.3 6.1 18.5 3 12.2-6.1 24.9-12 37.7-17.9V488c0 17.8 18.8 29.4 34.7 21.5l98.7-49.4c16.3-8.1 26.5-24.8 26.5-42.9V312.8c72.6-46.3 128-108.4 128-211.1.1-25.2.1-50.4-6.8-82.6zM400 160c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48z", // path
            ]
        },
    });
});
```

To use the new icon, reference it like any other FontAwesome icon, except utilize the prefix and named as defined in `fontawesome.init.js`.

```html
<i class="fac fa-__gulp_init_npm_name__"></i>
```

## Adding third-party media

Many third-party scripts have custom stylesheets which have associated images that also need imported in order to function properly.

After installing a JavaScript library, check to see if it has an associated images that also needs imported. If it does, copy the images in to `.src/assets/media/vendor`. Then open up the associated stylesheet under `./src/assets/styles/modules` with the library name, and import the fix the image references like so:

```scss
.third-party-selector {
    & {
        background-image: url("../media/vendor/third-party-image.png");
    }
}
```

If you're not sure if the library you're using has any associated images, open up the `node_modules` folder, browse to the libraries folder, and look for any stylesheets. If one exists, open it, and look for any uses of `background-image`. If any exist, copy the images in `./src/assets/media/vendor`, then copy the selector for that rule, and use it in place of `.third-party-selector` in the example above.
