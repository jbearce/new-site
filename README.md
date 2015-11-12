# new-site

I'm rethinking how my build process should work. I want things it to be more like a web app, with versioning, distributions, etc. This is the start of that project.

## Features

**Modules!**

A number of pre-built modules are included, including:

 - A very nice mobile menu, with a one element menu button
 - [Swiper](https://github.com/nolimits4web/Swiper) integration
 - A simple column system

**Gulp tasks!**

 - Compiles, prefixes, and compresses SCSS
 - Concatenates, and uglifies JS
 - Losslessly compresses images
 - Syncs name, version, license, etc between package.json and style.css
 - Automatically FTPs up to either a develoment or production server

**SMACSS!**

Clearly organized code following a set of guidelines means anyone can jump in!

*PS: I'm not SMACSS expert, but it's as close as I can get for now&hellip;*

**Fast!**

Lots of compression, efficient code, and FontAwesome integration means a lightweight, lean framework.

**Documented!**

Check out [CONTRIBUTING.md](CONTRIBUTING.md) for information on setting up, organziation, etc. I still need to add in proper documentation throughout my code.

## Planned

**More Modules**

I need to add in more modules, things like:

 - Breadcrumb navigation
 - Improved responsive tables
 - Light windows
 - Social media icons
 - More stuff as it comes up

**More use of the grid system**

Right now nothing's using the grid system by default. I should really try to avoid using custom grids wherever possible, and instead rely on the grid system.
