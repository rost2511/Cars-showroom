# bt Development Theme
This is a starter-theme for WordPress based on [Underscores](http://underscores.me/) that includes two most popular Front-End frameworks: [Bootstrap](http://getbootstrap.com/) and [ZURB Foundation](http://foundation.zurb.com/). The purpose of this theme, is to act as a small and handy toolbox that contains the essentials needed to build any design, and its meant to be a starting point, not the final product.

All project dependensies can be added with `npm` (Please note that for SCSS you still need to use @import tag to include scss file, but thanks to Webpack you can  Prefix your imports with `~` to grab from node_modules/ @see https://github.com/webpack-contrib/sass-loader#imports)

## Requirements

This project uses [webpack module bundler](https://webpack.js.org/) that requires [Node.js](http://nodejs.org) v8.9.x  to be installed on your machine. 
PHP7.0+
## Quickstart

### 1. Clone the repository and prepare your theme

```bash
$ cd my-wordpress-folder/wp-content/themes/
$ git clone http://git.bt.se/vromanenko/bt-starter.git
$ rename bt-theme folder (better with underscores or together like in 'twentyseventeen')
$ cd your_theme_name
```

### 1.2 Setup framework

To enable one of the pre-installed frameworks, go to theme folder, then open framework-specific folder and simply copy its contents to root theme folder:

![Framework Setup](http://i.imgur.com/dqVv2T9.gif)

### 1.3 Rename files and strings according to the project name
```bash
$ in the beginning of functions.php find Text domain definition and replace 'theme_text_domain' to the text domain according to your theme name
$ find & replace in the whole theme 'bt_' and 'bt' with the name of your theme
$ check top comment section in /style.css for the correct information about the theme
$ add screenshot.png of your future theme appearance (1200px wide by 900px)
```

### 1.4 Cleenup

Delete Bootstrap and Foundation folders from theme

### 2. Install with npm:
```bash
$ npm install
```
or you can use Yarn 
```bash
$ yarn
```
### 3. Setup your assets/config.json:

#### 3.1 Live reload
Add your local server URL, so LiveReload can refresh browser as you are working on your code :

```javascript
  "publicPath": "/wp-content/themes/THEME_NAME",
  "devUrl": "http://DOMAIN.DEV/",
```
# If you have local domain inside folder ie http://localhost/project-name you should add your folder as prefix to `publicPath`  "LOCALHOST_FOLDER/wp-content/themes/THEME_NAME"

*`assets/stylesheets/main.scss` — primary theme CSS, barebones partials are imported to help get your styling started
*`assets/scripts/main.js` — primary theme JS

Look at entry in assets/config.json to see how they're built:
```javascript
"entry": {
  "main": [
    "./scripts/main.js",
    "./styles/main.scss"
  ],
  "customizer": [
    "./scripts/customizer.js"
  ]
}
```
To create additional CSS or JS files, you'll need to:
Create the files within the `assets/scripts/` or `assets/styles/` directories
Open `assets/config.json` and add the new files to entry in a new array.

### 4. Build commands
* `npm run start` — Compile assets when file changes are made, start Browsersync session
* `npm run build` — Compile and optimize the files in your assets directory
* `npm run build:production` — Compile assets for production
or
* `yarn run start` — Compile assets when file changes are made, start Browsersync session
* `yarn run build` — Compile and optimize the files in your assets directory
* `yarn run build:production` — Compile assets for production

### 5. Git commit like 'Theme setup'

## Overview
### 1. Folder structure

```
bt-theme/
├───assets
│   ├───build
│   │   ├───helpers
│   │   └───util
│   ├───fonts
│   ├───images
│   ├───scripts
│   │   └───autoload
│   └───styles
│       ├───autoload
│       ├───common
│       ├───components
│       └───layouts
├───dist
│   ├───scripts
│   ├───styles
│   └───vendor
├───languages
├───lib
├───page-templates
├───template-parts
└───vc_templates
```

dist folder will be created after build task.

### 2. Javascript
Write all your project's scripts to `assets\scripts\main.js`. Separate modules can be placed inside `assets\scripts\modules\` folder and use ES import (https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/import)


### 3. SCSS
All SCSS files are split into four main subfolders:
# Inside scss files ``` ../``` will be resolved as ```/assets``` folder. So inside any scss file if you need use image or font you need write ```../images/name.svg```  not relative path.
```
│       └───scss
│           ├───components
│           ├───layouts
│           └───template-parts
```

The `components` folder contains secondary styles, such as core Wordpress classes styling, forms, comments, etc. Put any small, reusable styles (buttons, etc) to `_parts.scss`.
The `layouts` folder is a place for all your page/template specific styles (header/footer, single, 404, etc). Try to avoid writing styles directly in `styles.scss`, its main purpose is to connect all your files for compilation via `@import`.
The `template-parts` folder should contain styles for reusable Wordpress template parts.

## 3rd party packages
Example of how to add 3rd party packages and have them included in the theme:

From the theme directory, run:
------
```
@ themes/your-theme-name/
$ npm install <package name>

Install Slick carousel:
$ npm install slick-carousel
```
or

``` 
@ themes/your-theme-name/
$ yarn add <package name>
Install Slick carousel:
$ yarn add slick-carousel
```

Open up main.js and main.scss to add the entry points for the package. If you're using the Slick Carousel then your theme JS and CSS would look like:

```
/** import external dependencies */
import 'jquery';
import 'bootstrap';

// Import Slick
import 'slick-carousel';
```

------

``` 
/* assets/styles/main.scss */
@import "common/variables";

// Import npm dependencies
@import "~bootstrap/scss/bootstrap";
@import "~font-awesome/scss/font-awesome";
// Import Slick
@import "~slick-carousel/slick/slick.scss";
@import "~slick-carousel/slick/slick-theme.scss";

```

## Important
* After running yarn run build from the theme directory, your package will be built with your theme assets. The dist folder will contain a _/node_modules/ directory that has any assets referenced from your packages. The compiled CSS and JS will reference these assets without having to manually edit paths. 
* Running `npm run build:production` or `yarn run build:production` will fail if 3rd party package's relative paths are not configured before imported. In example to load Slick Carousel's paths add the following line in your common/_variables.scss file:

```
/* assets/styles/common/_variables.scss */
// Slick Carousel font path
$slick-font-path: "~slick-carousel/slick/fonts/";
// Slick Carousel ajax-loader.gif path
$slick-loader-path: "~slick-carousel/slick/";
```

## Linters and prettier
In theme we use  [stylelint](https://stylelint.io) and [Eslint](https://eslint.org/) alongide with [phpcs](https://github.com/squizlabs/PHP_CodeSniffer)

You can use all benefits from modern setup with prettier.
Install prettier as global dependency 
```text
npm install --global prettier
```

#### Then you should configure your IDE
#### For VS Code 
At first install package ```Prettier formatter for Visual Studio Code```
Move ```.stylelintrc``` and ```.eslintrc.js``` to root of your project
Then add user settings 
  ```text
    "editor.formatOnSave": true,
    "prettier.eslintIntegration": true,
    "prettier.stylelintIntegration": true,
```

#### For PhpStorm
Move ```.stylelintrc``` and ```.eslintrc.js``` to root of your project
For PhpStorm you should create file watchers for js and scss

Download exported watchers  [Link to file watchers](https://drive.google.com/file/d/1zRsyFSjv-kbdFm2hz0T4hoYNXFBC01Sa/view?usp=sharing)
Then import watchers to your project. ``` File -> Settings -> Tools -> File Watcher ``` (http://prntscr.com/kdvhye)

After ``` npm install```  you should change path to your cmd file in watchers configuration

[for scss](https://i.imgur.com/atlez1c.png)
[and for js](https://i.imgur.com/BadLhrD.png). Also change scope for watchers to trigger watchers only on your files (https://i.imgur.com/9dOPb6r.png)
After that watchers will autocorrect most of mistakes
More about rules you can read on [stylelint.io](https://stylelint.io/user-guide/rules/) and [Eslint recomended](https://eslint.org/docs/rules/)


### 4. Images
Webpack will compress all images from `assets\images\` and put them to `dist\images`.
### 5. PHP and `\lib` folder

The `lib` folder contains php files, connected to `functions.php`:

* `cleanup.php` - contains functions for cleaning up default Wordpress styles, scripts, etc.
* `enqueue-scripts.php` - serves for registering your styles and scripts in Wordpress
* `framework.php` - contains pre-configured menu walker, comment form and pagination with classes, specific to the framework you are using. Each framework has its own `framework.php` file.
* `jetpack.php` - handles compatibility issues if Jetpack plugin is enabled.
* `menu-areas.php` - serves for registering menu areas.
* `template-tags.php` - contains pre-configured custom template tags.
* `theme-support.php` - serves for registering theme support for languages, menus, post-thumbnails, post-formats etc. Most of them are already enabled.
* `vc_shortcodes.php` - serves for registering VisualComposer modules.
* `widget-areas.php` - serves for registering menu areas.

### 5. Workflow features

#### 5.1 Helper Classes

The theme contains javascript library, that detects user browser, OS and displays their names as classes for `<body>` tag. This allows you to easily debug all device or browser-specific ussies.
Also, if sidebar is active, `<body>` will have class `has_sidebar`.

#### 5.2 Working with breakpoints
Use default Bootstrap or Foundation breakpoint mixins
[Bootstrap](https://getbootstrap.com/docs/4.0/layout/overview/) 
```scss
@include media-breakpoint-up(xs) { ... }
@include media-breakpoint-up(sm) { ... }
@include media-breakpoint-up(md) { ... }
@include media-breakpoint-up(lg) { ... }
@include media-breakpoint-up(xl) { ... }

// Example usage:
@include media-breakpoint-up(sm) {
  .some-class {
    display: block;
  }
}
```
[Foundation](https://foundation.zurb.com/sites/docs/media-queries.html) 
```scss
.element {
  // Only affects medium screens and smaller
  @include breakpoint(medium down) { }
  // Only affects medium screens, not small or large
  @include breakpoint(medium only) { }
}
```

---
For use phpcs you need to install phpcs as global package
```text
composer global require "squizlabs/php_codesniffer=*"
```
The for PhpStorm you can use [this guide](https://confluence.jetbrains.com/display/PhpStorm/PHP+Code+Sniffer+in+PhpStorm)
For VS Code you can use ```package PHP CS Fixer for Visual Studio Code```
and add [settings](https://i.imgur.com/z1b66q8.png)
## Test your project
* [Dummy content generator wpfill.me](http://www.wpfill.me/)
* [Set of data to test all core Wordress features (post/content formats, multilevel menus, etc)](http://wptest.io/)
* https://codex.wordpress.org/Theme_Development#Theme_Testing_Process
* https://codex.wordpress.org/Theme_Unit_Test

## Pull Requests

Pull requests are highly appreciated. Please follow these guidelines:

1. Solve a problem. Features are great, but even better is cleaning-up and fixing issues in the code that you discover
2. Make sure that your code is bug-free and does not introduce new bugs
3. Create a [pull request](https://help.github.com/articles/creating-a-pull-request)
