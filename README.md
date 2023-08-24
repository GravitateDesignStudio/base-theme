# Gravitate WP Starter Theme

**Note**: If you have not yet done so, make sure to run `npm install` and `composer install` in the theme root to install the necessary dependencies

## Features

### CSS / SCSS

#### BEM-enabled

This starter theme is using the BEM methodology for CSS which improves modularity and helps with common specificity issues. To learn more about BEM, visit [getbem.com](http://getbem.com/) and [BEM By Example](https://seesparkbox.com/foundry/bem_by_example).

#### Foundation 6 Flex Grid

The Foundation 6 Flex Grid is included by default. It is installed via NPM so that the current version can be easily referenced and updated if necessary.

By default, only the `foundation-global-styles`, `foundation-flex-grid`, `foundation-visiblity-classes`, and `foundation-flex-classes` modules are included to keep the overall bundle size down. If you need to include additional Foundation components you can do so in `master.scss`.

[Foundation 6 Flex Grid Documentation](https://foundation.zurb.com/sites/docs/flex-grid.html)

#### object-fit-images polyfill

The [object-fit-images](https://github.com/bfred-it/object-fit-images/) polyfill has been included and will allow you to use the `object-fit` and `object-position` CSS properties in browsers that don't have support for them such as IE 11, Edge (pre-chromium), and Safari (<= 9).

### PHP

#### Composer

3rd party PHP packages for this theme are managed using Composer. If you do not have Composer installed you can do so by using [Homebrew](https://brew.sh/) `brew install composer` or by going to [https://getcomposer.org/](https://getcomposer.org/).

#### WP-Util Package

The WP-Util package (repo: [https://github.com/dougfrei/wp-util](https://github.com/dougfrei/wp-util) | packagist: [https://packagist.org/packages/dfrei/wp-util](https://packagist.org/packages/dfrei/wp-util)) contains many theme-independent utility methods for WordPress and common 3rd party integrations.

### JavaScript

#### Build System based WPack.io

#### Included Libraries

### How To Build
