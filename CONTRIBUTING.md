# Contributing

This is a work-in-progress. If you have any questions, feel free to contact me.

## Table of Contents

 1. [Software](#software)
 2. [Setting Up](#setting-up)
 3. [CSS](#css)
 4. [JavaScript](#javascript)
 5. [Gulp Tasks](#gulp-tasks)
 6. [Versioning](#versioning)
 7. [Resources](#resources)

## Software

### Required

In order to work with this project, you'll need a few pieces of software. Install the following three applications, in order:

 - Git ([Windows](https://git-for-windows.github.io/) | [Mac](http://git-scm.com/download/mac) | [Linux](http://git-scm.com/download/linux))
 - Node JS ([Download](https://nodejs.org/en/download/))
 - Gulp ([Instructions](https://github.com/gulpjs/gulp/blob/master/docs/getting-started.md))

### Recommended

These are a few of the development tools I can't live without. If you have a development environment you're comfortable with, you can skip this section.

 - Brackets ([Download](https://brackets.io/))
 - Brackets-Git ([Instructions](https://github.com/zaggino/brackets-git#installation))
 - Firefox Developer Edition ([Download](https://www.mozilla.org/en-US/firefox/developer/))

## Setting Up
 
This project uses Git and Gulp. There are few things you need to configure to get it up and running.
 
### Git

In order to contribute to this repository, you'll need to clone it on to your local machine. I recommend cloning with SSH, although it does take a bit of work to configure the first time. [GitHub offers an excellent guide on SSH](https://help.github.com/articles/generating-ssh-keys/).

Once you have SSH configured, you can follow these steps to clone this repository:

 1. Open a command prompt
 2. Use the `cd` command to navigate to the directory that you want to clone in to. For example, I clone my repositories in to `C:\Users\Jacob\Repositories`, so I would run `cd C:\Users\Jacob\Repositories`.
 3. Clone the repository with the command `git clone git@github.com:revxx14/new-site.git`

### Gulp

To compile the SCSS, JS, etc, you'll need to install this project's dependencies. You can follow these steps to do so:

 1. Open a command prompt
 2. Use the `cd` command to navigate to this repository. For example, I would run `cd C:\Users\Jacob\Repositories\new-site`
 3. Install this projects dependencies with the command `npm install`

### Configuration

If you've forked [revxx14/new-site](https://github.com/revxx14/new-site) or [revxx14/new-site-static](https://github.com/revxx14/new-site-static), and are using it for your own projects, you'll need to configure a few additional items:

#### README

Update the README to reflect your projects names, features, and plans.

#### package.json

Update the package.json to reflect your projects name, description, version, repository, and license.

#### FTP

If you'd like to use the `gulp ftp` task, make sure you [download my example ftp.json](https://gist.github.com/revxx14/a04f5ba7e00b267e71e2) and add in the correct parameters for your development environment.

## CSS

This project uses SCSS, a preprocessed version of CSS. In addition, it follows the [SMACSS methodology](http://www.smacss.com/).

### SCSS

SCSS is a preprocessed version of CSS. It adds in a number of excellent features, like variables, functions, mixins, nesting, etc. All standard CSS rules are perfectly usable in SCSS, so I'm not going to go in depth here, but please check out [this excellent guide on A List Apart](http://alistapart.com/article/getting-started-with-sass) to get a grasp on how it all works. Full documentation is available at [sass-lang.com](http://sass-lang.com/documentation/file.SASS_REFERENCE.html).

### SMACSS

SMACSS is a fairly complex methodology, but once you have a grasp on it, it really helps keep your code clean and organized. You can [read the full documentation here](http://www.smacss.com/), but I've also put together a short rundown:

#### File Organization

Each SCSS file is placed in one of 6 folders, depending on what it does.

##### Utilities

All variables, functions, and mixins are placed in `/src/assets/utilities/`. All colors, font families, and major widths, should be set here.

You should never put a color code directly in to the SCSS, you should always use a variable, and adjust it as necessary. [This tool](http://razorjam.github.io/sasscolourfunctioncalculator/) lets you enter your  variable color and the color it should be changed to, and generates a SCSS function to do so. Just remember to replace the color you entered with the proper variable before committing.

There are a number of built-in functions and mixins. The primary ones to be aware of are:

 - **`remify($size, $base, $unit)`**: This function accepts up to three parameters. The first is the size in pixels that you're going to display. Setting just this first parameter will result in REM units, based off of a default size of `16`. For example, `remify(16)` runs `16 / 16rem`, which results in `1rem`. The second parameter is the font size of the parent element. Setting the first two parameters overrides the default unit `REM` and changes it to `EM`. For example, `remify(16, 20)` runs `16 / 20em`, which results in `0.8em`. The final parameter lets you again override `EM` back to `REM`. For example, `remify(16, 20, rem)` runs `16 / 20rem`, which results in `0.8rem`.
 - **`@include clear`**: This, as its name implies, inserts a clearfix.
 - **`@include flexfix`**: This clears floats on flexed items, which fixes an issue in Safari where flexed items could be invisible.
 - **`@include icon($icon)`**: This function inserts a FontAwesome icon. The list of possible icons can be viewed in `/src/assets/utilities/_variables.scss`.
 
##### Views

In `/src/assets/views/`, you'll find one file for each breakpoint. This is where all of your imports will take place.

If you have code that should start working at the large breakpoint, you would create an `_example_l.scss` file and include in the matching `_screen_*.scss` file here.

These `_screen_*.scss` files then get included in their matching breakpoint in `/src/assets/all.scss`.

**IMPORATNT:** This project scales up, rather than scaling down. This means that the default view is mobile, with each bigger size affecting bigger and bigger screens. For example, by default, `_screen_xxs.scss` will affect screen sizes `480px` and wider, `_screen_xs.scss` `640px` and widder, `_screen_s.scss` `768px` and wider, and so on. The reasoning for this is that typically the bigger a device is, the more powerful it will be. Thus, the smallest device processes the least code, and the largest the most.

##### Base

`/src/assets/base/` contains all the most basic, general styles for the project. All of these styles can be used anywhere throughout the project.

`normalize` uses the excellent [Normalize project](https://github.com/necolas/normalize.css) to standardize code between browsers. This shouldn't need to be modified, but if it does, put your custom styles in `_normalize`, and leave `_normalize-vendor` as is.

The `user-content` folder contains styles for raw HTML elements (i.e. `h1`, `h2`, `p`, `ul`, `table`) contained within an element with the class `.user-content`. This can be used to apply different styles to something if a user entered the content.

The `content` folder contains default styles for headings, forms, standard text, etc.

The column framework is contained in `/src/assets/grid/`. These files shouldn't need to be edited, but you can view them to get an understanding of how the system works. Up to ten columns are supported, in any combination, and at any breakpoint. You can change to a certain number of columns at different breakpoints by using classes like `.grid-item.one-third-s`, `.grid-item.six-sevenths-xl`, etc.

##### Modules

In `/src/assets/modules/`, you'll find all the different components of this project. These can be plugged in wherever necessary, so make them as general as possible. For example, modules typically shouldn't have fixed widths, so that they'll fit anywhere on the site.

##### Layout

`/src/assets/layout/` is where you can put styles that affect the layout of the site, as well as custom styles for modules that are dependent on where they're placed on the page.

For example, if the links in a `.menu-list` that's in the header should have a `20px` font size., you could add `.header .menu-item a {font-size: remify(20)}` in `/src/assets/styles/layout/header/_header.scss`.

#### Rule Organization

Rules should be organized in a consistent and orderly manner. Here's a breakdown:

    .example {
        @include clear;                // includes go at the top of a rule
        @include icon("youtube");      // and should be alphabetized
        
        color: $accent;                // rules come next
        font-family: $heading-font;    // are alphabetized
        font-size: remify(20, 16);     // and prefixed rules
        transition: color 0.15s;       // should be excluded
        
        &:active {                     // pseudo classes
            color: $secondary_alt;     // go after rules
        }                              // and are alphabetized
        
        &:hover {                      // 
            color: $accent_alt;        // ...
        }                              // 
        
        &:before {                     // pseudo elements
            @include icon("facebook"); // come after pseudo classes
        }                              // and are organized
        
        &:after {                      // before, then after
            @include icon("twitter");  // as that's how they
        }                              // appearon the page
        
        &.alt {                        // alternate classes 
            color: $secondary;         // come after pseudo elements
        }                              // and are alphabetized
        
        &.alt:active {              // and the cycle
            color: $secondary_alt;     // repeats, without
        }                              // further nesting
    }

#### Rule Naming

All rules should be classes. IDs should never be used, and styles should be never be applied to generic selectors like `p`, `h1`, etc.

Names should be consistent with their parent modules name. For example, if you where building a widget, with a title and content, you might end up with something like this:

    <div class="widget">
        <div class="widget-header">
            <h6 class="widget-title">Demo Widget</h6>
        </div><!--/.widget-header-->
        <div class="widget-content">
            <p class="widget-text">Quisque et est eros. Sed quis dignissim leo. Sed sed porta ex. Maecenas ac tellus massa. Phasellus rutrum ex rhoncus, pretium lectus vel, hendrerit dui. Ut faucibus faucibus eros ut luctus. Curabitur magna elit, dapibus in lobortis ac, faucibus ac mi. Integer aliquet dui at sagittis efficitur.</p>
        </div><!--/.widget-content-->
    </div><!--/.widget-->

## JavaScript

Most of the JavaScript in this project uses jQuery. All the custom code for this project successfully lints, and I exclude third party libraries from the linting process.

If adding new scripts, simply place your new .js file in `/src/assets/scripts/`, and it'll automatically get added to the compiler. Files should be named as `library.scriptName.js`. For example, `jquery.menuButton.js`.

## Gulp Tasks

There are several gulp tasks with this project. The two you'll be using most are `gulp` and `gulp watch`, which compiles `/src/` to `/dev/`, as well as `gulp build` to build to `/dist/`, but there are a number of additional tasks available as well.

### `gulp` (default task)

This task is a combination of a number of other tasks. Namely:

 - `clean`
 - `styles`
 - `scripts`
 - `media`
 - `php`

### `gulp watch`

Watches for changes to `/src/`, and runs `gulp` when changes are detected. Use the `--ftp` flag to automatically upload files to your development server (configured in `ftp.json`)

### `gulp dist`

This is also a combined task, which runs:

 - `clean`
 - `styles`
 - `scripts`
 - `media`
 - `php`
 - `dist`

### `gulp clean`

Wipes out both `/dev/` and `/dist/`, usually in preparation for recompiling new source code.

### `gulp styles`

Compiles and prefixes SCSS in to `/dev/assets/styles/all.css`.

### `gulp scripts``

Concatenates JS in to `/dev/assets/scripts/all.js`.

### `gulp media`

Losslessly compresses images and copies them in to `/dev/assets/media/`.

### `gulp php`

Adds a version string to each URL and copies code in to `/dev/`.

### `gulp dist`

Compresses CSS and JS, and copies all files from `/dev/` in to `/dist/`.

### `gulp ftp`

Uploads via FTP using the settings in `ftp.json`. Add the `--dist` flag to upload to your production environment.

## Versioning

When code is ready to be pushed to production server, the projects version number should be revved. This is done in `package.json`.

 - Major points (i.e. 1.vX.X, 2.vX.X, 3.vX.X) are reserved for major changes, such as redesigns or massive structural changes. When upping a major point, both middle and minor points reset to 0.
 - Middle points (i.e. vX.1.X, vX.2.X, vX.3.X) are for more minor changes that are bigger than bug fixes. For example, if you added a newsletter form to `sidebar.php`, this would be a middle point update. When upping a middle point, minor points get reset to 0.
 - Minor points (i.e. vX.X.1, vX.X.2, vX.X.3) are for minor bug fixes. For example, if you fixed a typo, or added ", Inc." to the copyright information, this would be a minor point update.
 
Note that reaching 9 for a middle or minor point **does not** raise the next level. You could, for instance, have a version `v2.18.42`. 

When you're ready to up the version number, there are a few steps to follow:

 1. Update the version number in `package.json`.
 2. Run `gulp dist` to build out the project with the new version information
 3. Run `git commit -m "your messsage"`, with a message detailing what's changed
 4. Run `git tag -a vX.X.X`, replacing X.X.X with your version number
 5. Run `git push origin vX.X.X`, replacing X.X.X with your version number

## Resources


### Git

 - Git ([Windows](https://git-for-windows.github.io/) | [Mac](http://git-scm.com/download/mac) | [Linux](http://git-scm.com/download/linux))
 - [Configuring SSH](https://help.github.com/articles/generating-ssh-keys/).

### Gulp

 - [Node JS](https://nodejs.org/en/download/)
 - [Gulp](https://github.com/gulpjs/gulp/blob/master/docs/getting-started.md)
 - [NPM](https://www.npmjs.com/)

### CSS

 - [SMACSS Documentation](http://www.smacss.com/)
 - [Normalize](https://github.com/necolas/normalize.css)
 - [SCSS Getting Started Guide](http://alistapart.com/article/getting-started-with-sass)
 - [SCSS Documentation](http://sass-lang.com/documentation/file.SASS_REFERENCE.html)

### Useful Websites

 - [Sass Colour Function Calculator](http://razorjam.github.io/sasscolourfunctioncalculator/)

### Optional Software

 - [Brackets](https://brackets.io/)
 - [Brackets-Git](https://github.com/zaggino/brackets-git#installation)
 - [Firefox Developer Edition](https://www.mozilla.org/en-US/firefox/developer/)
