# Media

## Quick reference

WIP

## Methodology

In general, as few images as possible should be included with the theme. If an image makes sense to be part of the content, it should be included there, and not as a part of the theme.

### Generating Mobile Icons and Splash Screens

Within the media folder, there are folders named `android`, `ios`, and `safari`. Each of these folders contains a number of images that will need modified when the project is first set up, or a logo change occurs.

### Android

1. If possible, take the icon from the project logo, make it solid white, and export it as a transaprent PNG sized 2048x2048. If there is no icon in the logo, simply use what is available. If the logo isn't well suited to being set to solid white, use it as-is.

2. Navigate to [Android Asset Studio](https://jgilfelt.github.io/AndroidAssetStudio/icons-launcher.html), and upload the logo you've just created.

3. Adjust the padding slider until the icon looks reasonable placed with some decent breathing room.

4. Unders "Shape," Choose "CIRCLE."

5. Set the background color to the primary or accent color of the theme, depending on which appears to be more brand-relevant.

6. Download the ZIP file that gets generated.

7. Extract the ZIP file, and rename each image file as the images are named in the `android` folder.

8. Copy your 2048x2048 logo image, resize it to 512x512, and save it named `splash-icon-512x512.png`.

9. Replace each image in `./src/assets/media/android` with the icons you've generated.

10. Copy the 144x144 icon, rename it to `logo-favicon.png`, and resize it to 128x128. Replace `logo-favicon.png` in the `./src/assets/media/` with it.

### iOS

1. Use your 2048x2048 logo from the Android section, or otherwise create a new one.

2. Open each image in the `ios` folder, and paste the new logo as a new layer over it.

3. Size the larger dimension of the logo to 50% the smaller dimension of the image. For example, if your logo is 2048x2048, and the image is 2732x2048, the logo should be resized to 1024x1024.

4. Create a new layer under the logo, and fill it in with the primary or accent color of the theme, depending on which appears to be more brand-relevant.

### Safari

1. Create a 16x16 version of the logo that is a single path and solid black.

2. Export it as an SVG named `mask-icon.svg`.

3. Edit the SVG to remove everything except for the `<svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">` and the `<path>`.

4. Replace `./src/assets/media/safari/mask-icon.svg` with the version you've just created.

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

import "@fortawesome/fontawesome-pro";

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
