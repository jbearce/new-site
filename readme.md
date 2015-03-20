New Site
========

These are the files I use whenever I start a new site. They're pretty bare bones at this stage, but I am working on a miniamlistic framework as well (see the framework branch).

Features:
---------

*   App-style mobile menu

*   Standard styles for various text and form elements

*   Minimal markup for header, main, and footer

*   Tectite form integration (with noCAPTCHA reCAPTCHA)

*   gulpfile.js to maintain assets between a possible _static folder and the root

Planned:
--------

*   Drag to the left or right to show mobile menu

*   Improved SCSS mixins for margin and padding (I'm trying to figure out how to mimic the default behavior of `margin` and `padding`, i.e. `@include margin(15, 20)` would output `margin: 15px 20px; margin: 0.9375rem 1.25rem`)

*   Barebones framework for rapidly getting a site off the ground (see framework branch)

*   Improved gulpfile.js with `gulp-watch` to automatically update _static assets whenever root assets change

*   Lightweight framework with various alternate settings (i.e. left sidebar vs right sidebar, full bleed slideshow vs centered slideshow, etc. See framework branch for more.).

Considering:

*   gulp to compile SCSS

*   gulp to concat JS
