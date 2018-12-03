# HTML/PHP

## Quick Reference

WIP

## Methodology

WIP

### Creating new partials

There are three different types of partials:

- Layouts &ndash; Top-level elements of a page. Layouts take up the entire width of the viewport on the browser, and are made up of many parts. The header would be considered a layout.

- Modules &ndash; Smaller portions of a page that can be dropped in as needed. Modules only occupy small portions of a page, often accompanied by more modules of the same type. The logo would be considered a module.

- Articles &ndash; Articles displaying the contents for a given post. Articles are a special kind of module, and may be made up of several sub-modules, or stand completely alone. Articles should be named in the format `{$post_type}-{$variant}.php`. One example would be `post-excerpt.php`

## Adding third-party HTML / PHP

### Libraries

Third-party libraries should be installed using [Composer](https://getcomposer.org/).

To install a library from Composer, install it via:

```sh
composer require namespace/library
```

To use the new library, import it in your script like so:

```php
use namespace\library;

library();
```

### Filters, Functions, etc.

Small filters and functions taken from sites like Stack Overflow should be cleaned up to match the style of this project. Function prefixes like `mytheme` should be changed to `__gulp_init_namespace__`, Single quotes should be changed to double, tabs changed to 4 spaces, and so on.

Once cleaned up, filters, functions, etc. can be placed in their respective files within `./src/functions`.
