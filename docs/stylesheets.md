## Quick reference

- [BEM Documentation](http://getbem.com/)
- "Layout" blocks are top-level element such as the header or footer.
- "Module" blocks are components of layouts, such as a search form or article.
- "Base" blocks are core buliding blocks of modules, such as titles or inputs.

## Methodology

For the most part, utilizing stylelint should ensure that your code follows the standards of this project. Below is a detailed breakdown of the more ambiguous rules that aren't handled well by stylelint.

### Naming Conventions

Wherever possible, elements should *only* be styled with classes. This is to ensure that every ruleset has the same level of specificity, and therefore is easy to override down the line. To learn more about specificity, see [MDN's documentation](https://developer.mozilla.org/en-US/docs/Web/CSS/Specificity).

Class names follow BEM &ndash; Block, Element, Modifier. [Their documentation](http://getbem.com/) will be much more comprehensive than this document, but below is

#### Blocks

Blocks are standalone entities that are meaningful on its own. Examples include `header-block`, `menu-list`, and `input`. Note that the [folder structure](#folder-structure) of blocks is split up in to `base`, `layout`, and `module` folders.

*Layout blocks* function as the main containing sections of each page. A few examples of this include `header-block`, `content-block`, and `footer-block`. These should be named as `{section}-block`, to indicate that they're a layout block.

*Module blocks* are typically the smaller components of layout blocks. A few examples of this include `menu-list`, `search-form`, and `wp-caption`. These should be named with as few words as possible, separated with a single dash.

*Base blocks* are typically the smaller components of module blocks. A few examples of this include `input`, `text`, and `button`. It should be rare that a new base block needs to be created, but when one does, it is highly preferred that it be named one single word, to indicate that it is a simple component.

In some cases, a block may require a *container*, and treating that container as the main element doesn't make sense. In those cases, an **element** of the block should be set up. These are named as `{block}__container`. A few examples of this include `page__container`,  `menu-list__container`, and `article-list__container`. These types of elements should be avoided wherever possible, but on occasion they're a necessary evil.

#### Elements

Elements are a parts of a block that have no standalone meaning and are semantically tied to their blocks.

New elements should be placed within their parent block or element stylesheet, and named as `{block}__{element}`. A few examples of this include `header__inner`, `content__post`, and `menu-list__container`. Typically, elements should be prefixed with their containing block class. A few examples of this include `header__menu-list__container`, `text__link`, and `footer__logo`.

In many cases, we treat child blocks as a kind of element of a parent block. For example, when a `logo` is contained within `footer-block`, we name it `footer__logo logo`. This allows us to make changes to blocks based on the parent that they are within.

#### Modifiers

Modifiers are flags on blocks or elements. Use them to change appearance or behavior.

New modifiers should be placed within their parent block or element stylehseet, after the initial ruleset, and named as `{block}--{modifier}`. A few examples of this include `menu-list--navigation`, `row--padded-tight`, and `swiper-container--hero`.

#### Helpers

Helpers are global modifiers that can be used in conjunction with any other class.

This is a custom feature for our projects, and not a part of native BEM. Helpers are simple things, such as setting `font-color: #FFF`, `text-decoration: underline`, or `margin: 0`. Helpers are always suffixed with `!important`, to ensure that they are always applied. *Helpers should never be overridden.*

Helpers should be named as `__{helper}`. Helpers should always be one word; if multiple words are needed, they should be concatenated, such as `__nomargin`. A few examples of this include `__light`, `__nomargin`, and `__uppercase`.

Note that there are a few exceptions to the concatenation rule &ndash; the visibility helpers are named as `__visible-{breakpoint}` and `__hidden-{breakpoint}`. As these helpers are rather unique, they've been differentiated.

#### A Note on Third Party Overrides

Sometimes, a third party developer has to make our lives difficult, and gets overly specific with their selectors. If we can't modify the markup, or modifying the markup would be too cumbersome for the changes needed, then the exact selectors that the third party developer used should be used to override their styles. For example, if the WordPress login button is styled with `body.login div#login form input.button` by the developers of WordPress, then that selector should be used to override its styles.

### Folder Structure

There are six distinct CSS folders for this project. These are:

- *base* contains the core blocks such as `input`, `text`, and `title`.
- *helpers* contains helper classes, variables, functions, and mixins.
- *layout* contains layout blocks such as `header-block`, `content-block`, and `footer-block`.
- *module* contains smaller blocks such as `menu-list`, `logo`, and `article`.
- *vendor* contains third party styles that could not be obtained via NPM ([more on that later](#adding-third-party-stylesheets)).
- *views* contains all of the imports from the other folders.

Within each folder, each block has its own folder. Within these folders, each breakpoint has its own file. For example, the tablet styles for the navigation would be defined in `layout/navigation/_navigation_xs.scss`. This file then gets imported under the `layout` section in `views/_screen_xs.scss`.

### Breakpoints

Breakpoints on this project go from narrow to wide. Each breakpoint is listed below.

| Name | Width  |
|:----:|:------:|
| xxxs | 480px  |
| xxs  | 640px  |
| xs   | 768px  |
| s    | 853px  |
| m    | 960px  |
| l    | 1024px |
| xl   | 1280px |
| xxl  | 1366px |
| xxxl | 1440px |

Two custom mixins exist for handling breakpoints &ndash; `larger-than($width)` for `min-width` breakpoitns, and `smaller-than($width)` for `max-width` breakpoints. `smaller-than()` automatically removes 1px from the specified width, to ensure it does not overlap with `larger-than` breakpoints with the same width spcified. For example, `smaller-than(xs)` will apply when the browser is <= 767px wide, and `larger-than(xs)` will apply when the browser is >= 768px wide.

In most cases, breakpoints should be targeted by including a new file for each breakpoint in `views/_screen_{breakpoint}.scss`. The rare exception to this is when applying a style that needs to revert upon hitting a breakpoint.

For example, if the footer text needs to be centered on mobile, instead of resetting its alignment at a larger breakpoint, it's better to instead unset the centered alignment altogether. See the example below.

```scss
.footer-block {
    @include smaller-than(xs) {
        text-align: center;
    }
}
```

### Nesting

Sass supports [nesting](http://sass-lang.com/guide#topic-3) rulesets within each other. For developers without much experience with Sass, it can often be tempting to go overboard with this feature. Nesting too deeply can become a major headache down the road, when trying to make a modification. It can be extremely difficult to manually decipher how a nesting chain breaks down.

To combat this, nesting should never exceed two levels deep; even two is often too much, and one should be preferred wherever possible. This is to improve readability of code, so we don't get lost in the never ending chain of nesting.

That being said, each base ruleset should be nested within a `& { ... }` to help maintain readability with the main ruleset and its children. See the example below.

```scss
.menu-list {
    & {
        font-size: remify(16, 16);
    }

    .menu-list__item {
        margin: remify(0 8, 16);
    }
}
```

## Creating new master stylesheets

It should be rare that a new master stylesheet needs created, but when one does, it should be created in the root styles folder, and added to the enqueues using the same method as the other stylesheets. *Note:* the file name will get hashed when it is compiled, so be sure to use the `__gulp_init_namespace___get_theme_file_path()` PHP function to retrieve the most recent hashed file name.

## Helper Functions and Mixins

Throughout the code of this project, you may notice custom functions or mixins being used. These are defined in `helpers/_functions.scss` and `helpers/_mixins.scss`. The most prevalent of these will certainly be `remify()`, which can be quite confusing to understand at first glance.

`remify()` is used to convert `px` units in to `em` or `rem` units. The first parameter is up to four numbers representing desired widths in pixels, much like `margin` or `padding`. The second parameter is the font-size to base the conversion off of.

If you're unfamiliar with the concept of `em` and `rem` units, the basic breakdown is that they are used to proportionality scale sizing based on the font size of the elements parent. As such, `1em` is equal to the `font-size` of the element it is applied to, or `16` if the element and all of its parents lack a set font size. `em` units are relative to the current elements font size, and `rem` units are relative to the root elements (typically `<html>`) font size. If this still doesn't make sense, check out [this article on SitePoint](https://www.sitepoint.com/understanding-and-using-rem-units-in-css/).

To use the `remify()` function, you simply provide it the size as you normally would, and set the second parameter to the elements font size. See the example below.

```scss
.menu-list {
    & {
        font-size: remify(14, 16); // 16 is used for the base as we assume it has not been modified at this point.
        line-height: remify(28, 14); // 14 is used because the element has its `font-size` set to 14.
    }

    .menu-list__item {
        margin: remify(0 7, 14); // 14 is used for the base because the parent element has its `font-size` set to 14.
    }

    .menu-list--child {
        font-size: remify(12, 14); // 14 is used because the closest parent with a set `font-size` is `.menu-list`, which is 14.
    }

    .menu-list--child .menu-list__item {
        margin: remify(0 6, 12); // 12 is used because the closest parent with a set `font-size` is `.menu-list--child`, which is 12.
    }
}
```

### `em` vs `rem` with `remify()`

`remify()` can output either `em` or `rem` units depending on the settings provided to it. `rem` should typically be used when specifying values that ***don't*** depend on the elements font size (for example, the `max-width` or `padding` of a `layout`). `em` should typically be used when speicfying values that ***do*** depend on the elements font size (for example, `line-height` or `margin-bottom` on a `base`).

If you only supply `remify()` with one paramter (the size value(s)), `rem` units will be returned. If you specify two values (the size value(s) and the base size), `em` units will be returned. You can override either of these by providing a third parameter (the unit) to the function, specifying either `em` or `rem`. See the examples below.

```scss
.hero__inner {
    & {
        max-width: remify(1280); // will output 80rem
    }
}

.hero__text {
    & {
        font-size: remify(18); // will output 1.125rem
        line-height: remify(22.5, 18); // will output 1.25em
        margin-bottom: remify(8, 16, "rem"); // will output 0.5rem
    }
}
```

## Adding third-party stylesheets

Many third-party scripts have associated stylesheets that also need imported in order to function properly. Luckily, our custom importer makes this very easy, as it already knows to look in `node_modules` when you use `@import`.

After installing a JavaScript library, check to see if it has an associated stylesheet that also needs imported. If it does, set up a new stylesheet under `modules` with the library name, and import the stylesheet like so:

```scss
@import "library-name/path/to/stylesheet";
```

If you're not sure if the library you're using has an associated stylesheet, open up the `node_modules` folder, browse to the libraries folder, and look for any stylesheets. If one exists, note the path to it relative to `node_modules`, and import it as described above.
