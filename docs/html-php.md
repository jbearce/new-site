# HTML/PHP

## Quick Reference

WIP

## Methodology

WIP

## Adding third-party HTML / PHP

### Libraries

Third-party libraries should be installed using [Composer](https://getcomposer.org/).

To install a library from Composer, install it via:

```sh
composer require namespace/library
```

To use the new library, you typically must import it in your script like so:

```php
use namespace\library;

library();
```

### Filters, Functions, etc.

Small filters and functions taken from sites like Stack Overflow should be cleaned up to match the style of this project. Function prefixes like `mytheme` should be changed to `__gulp_init_namespace__`, single quotes should be changed to double, tabs changed to 4 spaces, and so on.

Once cleaned up, filters, functions, etc. can be placed in their respective files within `./src/functions`.
