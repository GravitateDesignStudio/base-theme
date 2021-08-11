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

#### ESLint

ESLint is a tool that will compare the JavaScript in the theme to a defined set of rules. While it is not a part of the build process, it's highly recommended to run ESLint regularly in order to maintain consistency and prevent errors. The included `.eslintrc.json` file should be usable by linting plugins in editors such as Visual Studio Code and Atom in order to lint JS as you're editing it. To run ESLint manually, use the following command at the theme root: `npm run eslint`.

ESLint will hurt your feelings -- and that's a good thing.

#### Build System based on Gulp, dart-sass, and Webpack

Gulp is being used as the build system for this theme and the currently configured tasks can be found in `gulpfile.js`. Dart-sass is being used to parse the SCSS source files and it is configured within `gulpfile.js`. Webpack in combination with Babel is being used to transpile and minify the JavaScript source files.

With the combination of Webpack and Babel, it is possible to use [ES6+](https://babeljs.io/learn-es2015/) features in your code without worrying about browser compatibility. Additionally, both the [CommonJS](https://nodejs.org/docs/latest/api/modules.html) and [ES6 Module](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/import) (_preferred_) formats can be used to modularize your code.

#### Included Libraries

##### Embla Carousel

[Embla Carousel](https://www.embla-carousel.com/) can be included via the `loadEmblaCarousel` method in `js/util/load-dependencies.js`. This method will include the library dynamically and return a promise with the Embla instance after a successful load.

### How To Build

The following NPM script aliases have been include and can be run with `npm run <script-name>`:

-   `eslint` - Run ESLint against your JavaScript to check for any issues or errors
-   `build-css` - Run the Gulp build task for your SCSS files
-   `build-js` - Run the Gulp build task for your JavaScript files
-   `build` - Run both the CSS and JS build tasks together
-   `watch` - Start a BrowserSync proxy instance and put both build tasks into a watch state where they will trigger on any changes to the source files
