# Contributing

This is only a brief guide to get you up and running with development. For a more robust guide, please see my [Contribution Guide on GitHub](https://github.com/JacobDB/contribution-guide).

## Important Notes

- **Never work out of the master branch!** All changes should be made on develop. master only gets updated when a new [release package](#releasing-a-package) is created.
- **Never edit `./dev` or `./dist` directly!** Changes made here are guaranteed to be overwritten. All changes should be made within `./src`.
- **Try to follow the existing style!** I realize it may be difficult to understand why some things are done the way they are. Speak with a senior developer if you have questions on how to name something, or where to put new code.
- **There is no need to edit the `./gulpfile.js` directly!** All configuration is done via `./config.json`, detailed below. In particular, **FTP credentials should never be stored directly in the `./gulpfile.js`.** This is a security measure, so that credentials never get uploaded.

## Required

1. [Node JS](https://nodejs.org/en/) &ndash; I recommend the latest current release.
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
7. Create a [symlink](http://www.howtogeek.com/howto/16226/complete-guide-to-symbolic-links-symlinks-on-windows-or-linux/) to `./dev` at `/Applications/MAMP/htdocs/wp-content/themes` on Mac, or `C:\MAMP\htdocs\wp-content\themes` on Windows. Note if you did not install MAMP to it's default directory, you should instead create the symlink where you installed it.
8. Make sure your MAMP server is running, and you have your dev theme activated.
9. Open a terminal, and navigate to the cloned repository's root. Run `gulp watch --sync` and enter the matching values when prompted. This will typically be the following:

    ```
    Browsersync proxy: localhost:8888
    Browsersync port: 8080
    Browsersync open: external
    Browsersync notify: false
    ```

    This will open your MAMP server in your default browser. You can now make edits in `./src`, and the page will automatically reload.

## Gulp Tasks

### `gulp`

This is the main task you need to be aware of. It compiles any changed files in `./src` in to `./dev`. If you're not seeing changes take effect, try deleting the `/.dev` folder and recompiling from scratch.

### `gulp watch`

This task simply runs `gulp` every time a change is made in `./src`. If you followed the [Advanced setup instructions](#advanced), you may also use the flag `--sync` in order to automatically refresh the page every time a change is detected (i.e. `gulp watch --sync`).

### `gulp ftp`

This task allows you to upload changed files from the terminal. On first run, it will prompt you for FTP credentials, storing them in `./config.json` locally. You may also use the flag `--ftp` in conjunction with the primary `gulp` task to compile and then immediately upload (i.e. `gulp --ftp`).

## Releasing a Package

**IMPORTANT NOTE:** Only a trained developer should create a build package. If you have not been so trained, **speak with a senior developer to go over this process!**

## CSS Structure

This project uses a custom CSS architecture designed to be modular and flexible. It can be a little daunting trying to understand it at first, but with this guide, you should get the hang of it in no time.

Opening up `./src/assets/styles`, you'll see 3 files and 6 folders. Their purposes are:

- **base** &ndash; Extremely *generic* styles that apply across the entire site. These are used for various generic elements, like titles, inputs, text paragraphs, etc. This is essentially the highest level CSS, that's shared across any number of elements.
- **helpers** &ndash; Extremely *specific* styles that apply across the entire site. These include functions, helper classes, mixins, and variables, each of which is described in more detail below.
    - *Functions* are typically used to convert one value to another. By default, there are two functions set up:
        - `strip-unit`, as it's name implies, will remove units like `px`, `em`, `rem`, `%`, etc from a given number. It's used in the format `strip-unit(65px)`. This function will almost never need to be used; it's mostly there for the `remify` function.
        - `remify` converts a number given in pixels in to it's equivalent value in either `em` or `rem` units. It's format is `remify($top $right $bottom $left, $font-size, $unit)`. **This should be used in place of `px` units wherever possible.** This function can take some getting used to, but if you're [familiar with `em` and `rem` units](https://webdesign.tutsplus.com/tutorials/comprehensive-guide-when-to-use-em-vs-rem--cms-23984), it should be relatively clear. If you omit the `$font-size` variable, units will be in `rem`, otherwise they'll be in `em`. You can override this functionality by specifying `"rem"` as the third variable. The calculation takes the values from the first parameter (`$top $right $bottom $left`) and divides them by the second (`$font-size`, `16` by default). This is best shown through an example:

            ```
            .example {
                border-width: remify(5, 20, "rem"); /* compiles to 0.25rem */
                font-size: remify(15); /* compiles to 1rem */
                margin: remify(15 30, 15); /* compiles to 1em 2em; */
                padding: remify(10 20); /* compiles to 0.5rem 1rem */
            }
            ```

        - `leading` converts the "Leading" value from Photoshop's Character Panel in to a usable `line-height` value. It's format is `leading($leading, $font-size)`. This function follows the formula `$font-size + ($leading / 2)`. This should be used in conjunction with `remify`, like so: `line-height: remify(leading(24, 16), 16);`.
        - `kerning` converts the "Kerning" value from Photoshop's Character Panel in to a usable `letter-spacing` value. It's format is `kerning($kerning, $font-size)`. This function follows the formula `($kerning / 1000) * $font-size`. This should be used in conjunction with `remify`, like so: `letter-spacing: remify(kerning(20, 16), 16);`.

    - *Helpers* are the most specific classes in the entire project. They cannot be overridden, and as such, should be used very sparingly. Helpers are always prefixed with an underscore (i.e. `_helper`), and always one word. If more than one word must be used, they should be combined with no spacing, all lowercase (i.e. `_textcenter`). They're almost always a single rule, suffixed with `!important`. An example would be:

        ```
        ._bold {
            font-weight: 700 !important;
        }
        ```

        Helpers also include some of the most useful classes in the entire project: visibility classes. These are `_phone`, `_tablet`, `_notebook`, and `_desktop`. As their names imply, they make things visible on phoens, tablets, notebooks, or desktops respectively. You can mix and match them in any combination to get elements to show and hide at different screen widths.

    - *Mixins* are tools that are reused throughout the site. They're fairly similar to functions, except that they can contain things besides a simple value. Most mixins included with this project don't get used, but you may see the use of `@icon` or `@placeholder`. I recommend taking a look at the source code for those mixins to understand how they're used. In a few works, `@placeholder` is used to style placeholders on inputs, and `@icon` is used to insert a FontAwesome icon.

    - *Varaibles* are global tools that contain a value to be reused throughout the site. These should be named extremely generically, for maintainability. For example, if you needed to set up a variable that represents the color red, you would  set up something like `$accent: #FF0000;`. You *would not* set up `$red: #FF0000;`. The reason being that if we need to change the accent color from red to blue in the future, the variable name would become confusing. Yes, you could simply find-and-replace `$red` for `$blue`, but then what was the point of using the variable in the first place? If you really need to mark what color a variable represents, do it in a comment next to the value. For example, `$accent: #FF0000; // red`. Typically color variables should only be named as `$variable` and `$variable_alt`. If you need to adjust one of the colors in a specific area to match a design, use Sass's `lighten()` and `darken()` functions, or use to this [Sass Color Function Calculator](https://jacobdb.github.io/sass-colour-function-calculator/).
- **module** &ndash; Fairly *generic* blocks of styles that can be reused across the entire site. These include things like `.widget`, `.logo`, `.menu-list`, `.search-form`, etc. They represent hunks of HTML that can be taken from one spot on a site and placed in another, without change. Modules often contain minor modifications to the basic elements found in the `base` folder, typically with names like `.widget_title`, `.search-form_input`, `.menu-list_link`, etc. In the HTML, these would be represented as `<h4 class='widget_title title'> ... </h4>`, `<a class='menu-list_link link' href='#'> ... </a>`, etc. Sometimes, there may need to be a module within a module. For example, putting a logo within a slideshow. In that case, you would name the sub-module as `.parent-module_sub-module`, represented in HTML as `<div class='parent-module_sub-module sub-module'> ... </div>`. In some cases, a second instance of a module may exist that needs to be styled differently than the initial module. In those cases, you should create a **variant**. Variants are always prefix with a dash (i.e. `-variant`), and always one word. If more than one word must be used, they should be combined with no spacing, all lowercase (i.e. `-variantname`). *Most styles reside in modules.*
- **layout** &ndash; Fairly *specific* blocks of styles that represent different layout areas on a page. These include things like `.header-block`, `.hero-block`, `.content-block`, and `.footer-block`. The top level class for a layout will always be suffixed with `-block`. Layouts often contain minor modifications to the modules found in the `module` folder, typically with names like `.header_logo`, `.content_search-form`, `.navigation_menu-list`, etc. In HTML, these would be represented as `<a class='header_logo logo' href='#'> ... </a>`, `<form class='content_search-form search-form'> ... </form>`, `<ul class='navigation_menu-list menu-list'>`, etc. **Variants** may be used in layouts as well. For more details on how to use a variant, refer to **modules**.
- **vendor** &ndash; Styles that are pulled directly from third party libraries. **Vendor styles should never be directly edited.** Instead, find their related module, which the vendor styles are imported in (typically with the same folder name name), and add in override styles there. This is to ensure updatability.
- **views** &ndash; Simply imports all helpers, layout, and module styles to their appropriate viewports. For example, `layout/content/_content_s.scss` would be imported in `views/_screen_s.scss`.
- **critical.scss** &ndash; Imports all **base** styles, along with all files required to render the above-the-folder content.
- **legacy.scss** &ndash; Imports all views, without media queries, in order to render the desktop site in older browsers.
- **modern.scss** &ndash; Imports all views, in their related media queries, in order to render the site in all viewports.
