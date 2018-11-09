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

9. Replace each image in `assets/media/android` with the icons you've generated.

10. Copy the 144x144 icon, rename it to `logo-favicon.png`, and resize it to 128x128. Replace `logo-favicon.png` in the `assets/media/` with it.

### iOS

1. Use your 2048x2048 logo from the Android section, or otherwise create a new one.

2. Open each image in the `ios` folder, and paste the new logo as a new layer over it.

3. Size the larger dimension of the logo to 50% the smaller dimension of the image. For example, if your logo is 200px wide and 250px tall, and the image is 300px wide and 200px tall, size the logo so that it is 80px wide and 100px tall.

4. Create a new layer under the logo, and fill it in with the primary or accent color of the theme, depending on which appears to be more brand-relevant.

### Safari

1. Create a 16x16 version of the logo that is a single path and solid black.

2. Export it as an SVG named `mask-icon.svg`.

3. Edit the SVG to remove everything except for the `<svg viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">` and the `<path>`.

4. Replace `assets/media/safari/mask-icon.svg` with the version you've just created.

### Adding custom FontAwesome icons

Icons should be prioritized as FontAwesome icons, but if a corresponding icons isn't defined in FontAwesome, or a specific icon is required to be used, a custom FontAwesome icon can be set up as long as you have a single-path SVG for the file.

WIP

## Adding third-party media

Many third-party scripts have custom stylesheets which have associated images that also need imported in order to function properly.

After installing a JavaScript library, check to see if it has an associated images that also needs imported. If it does, copy the images in to `assets/media/venodor`. Then open up the associated stylesheet under `modules` with the library name, and import the fix the image references like so:

```scss
.third-party-selector {
    & {
        background-image: url("../media/vendor/third-party-image.png");
    }
}
```

If you're not sure if the library you're using has any associated images, open up the `node_modules` folder, browse to the libraries folder, and look for any stylesheets. If one exists, open it, and look for any uses of `background-image`. If any exist, copy the images in `assets/media/vendor`, then copy the selector for that rule, and use it in place of `.third-party-selector` in the example above.
