# REDESIGN BBG WordPress theme based on the U.S. Web Design Standards

We're creating a Broadcasting Board of Governors WordPress theme based on 18F and U.S. Digital Services' proposed U.S. Web Design Standards. 


## U.S. Web Design Standards

The [U.S. Web Design Standards](https://playbook.cio.gov/designstandards) is a library of open source UI components and a visual style guide for U.S. federal government websites.

These tools follow industry-standard web accessibility guidelines and reuse the best practices of existing style libraries and modern web design. Created and maintained by [U.S. Digital Service](https://www.whitehouse.gov/digital/united-states-digital-service) and [18F](https://18f.gsa.gov) designers and developers, the Web Design Standards are designed to support government product teams in creating beautiful and easy-to-use online experiences for the American people. Learn more about this project in their announcement [blog post](https://18f.gsa.gov/2015/09/28/web-design-standards/).

Design files of all the assets included on this site are available for download here: [https://github.com/18F/web-design-standards-assets](https://github.com/18F/web-design-standards-assets).


## Setup for your local environment

### Installation

1. Install [WordPress](https://codex.wordpress.org/Installing_WordPress).
2. Clone or download this theme into the wp-content/themes folder.
3. We're using grunt to compile the SASS files on the fly, so you'll need to install the Grunt command line interface and you'll need to make sure SASS is installed. To install the grunt command line interface:
    `$ npm install -g grunt-cli`
The next step is to install Sass. 
    `$ sudo gem install sass`
4. From inside of the theme directory type:
    `$ npm install`
5. To start watching/compiling the SASS, type:
    `$ grunt`

This will compile a human readable version of the CSS, a version of the CSS with vendor prefixes and the minified version for production (based on the Gruntfile.js)

### Plugins and Custom Fields
We've documented our usage of custom fields and plugins in our [SETUP.md] (https://github.com/BBGInnovate/bbgWPtheme/blob/master/SETUP.md) file 


## Got feedback?

You can reach us at bbgpublicaffairs@bbg.gov.



## Licenses and attribution

### A few parts of this project are not in the public domain

The Source Sans Pro font files in `assets/fonts` are a customized subset of [Source Sans Pro](https://github.com/adobe-fonts/source-sans-pro), licensed under the [SIL Open Font License](http://scripts.sil.org/cms/scripts/page.php?item_id=OFL), and copyright 2010, 2012, 2014 [Adobe Systems Incorporated](http://www.adobe.com/), with Reserved Font Name 'Source'. All Rights Reserved. Source is a trademark of Adobe Systems Incorporated in the United States and/or other countries.

The Merriweather font files in `assets/fonts` are from [Google Web Fonts](https://www.google.com/fonts#UsePlace:use/Collection:Merriweather:400,300,400italic,700,700italic), licensed under the [SIL Open Font License](http://scripts.sil.org/cms/scripts/page.php?item_id=OFL), and copyright © 2010-2013, [Sorkin Type Co](www.sorkintype.com) with Reserved Font Name 'Merriweather'.

The files in `assets/img` are from [Font Awesome](http://fontawesome.io/) by Dave Gandy under the [SIL Open Font License 1.1](http://scripts.sil.org/OFL).

The files in `assets/_scss/lib/bourbon` are from [Bourbon](http://bourbon.io/), copyright © 2011–2015 [thoughtbot](https://thoughtbot.com/), inc., under the [MIT license](https://github.com/thoughtbot/neat/blob/master/LICENSE.md).

The files in `assets/_scss/lib/neat` are from [Neat](http://neat.bourbon.io/), copyright © 2012–2015 [thoughtbot](https://thoughtbot.com/), inc., also under the [MIT license](https://github.com/thoughtbot/neat/blob/master/LICENSE.md).

The file `assets/css/normalize.min.css` is from [Normalize.css](https://github.com/necolas/normalize.css), copyright © Nicolas Gallagher and Jonathan Neal, under the [MIT license](https://github.com/necolas/normalize.css/blob/master/LICENSE.md).

The file `assets/js/component.js` includes `politespace.js` from [Politespace](https://github.com/filamentgroup/politespace), copyright © 2013 Zach Leatherman, under the [MIT license](https://github.com/filamentgroup/politespace/blob/master/LICENSE).

The file `assets/js/vendor/html5shiv.js` is from [HTML5 Shiv](https://github.com/afarkas/html5shiv), copyright © 2014 Alexander Farkas (aFarkas), under the [MIT license](https://github.com/aFarkas/html5shiv/blob/master/MIT%20and%20GPL2%20licenses.md).

The file `assets/js/vendor/jquery-1.11.3.min.js` is from [jQuery](https://jquery.com/), copyright © 2015 The jQuery Foundation, under the [MIT license](https://jquery.org/license/).

The file `assets/js/vendor/rem.min.js` is from [REM unit polyfill](https://github.com/chuckcarpenter/REM-unit-polyfill), copyright © 2015 Chuck Carpenter, under the [MIT license](https://github.com/chuckcarpenter/REM-unit-polyfill/blob/master/LICENSE.md).

The file `assets/js/vendor/respond.js` is from [Respond.js](https://github.com/scottjehl/Respond), copyright © 2012 Scott Jehl, under the [MIT license](https://github.com/scottjehl/Respond/blob/master/LICENSE-MIT).

The file `assets/js/vendor/selectivisr-min.js` is from [Selectivizr](http://selectivizr.com/), copyright © Keith Clark, under the [MIT license](http://opensource.org/licenses/mit-license.php).

The files `assets-styleguide/js/vendor/prism.js` and `assets-styleguide/css/prism.css` are from [Prism](http://prismjs.com/), copyright © 2012-2013 Lea Verou, under the [MIT license](https://github.com/PrismJS/prism/blob/gh-pages/LICENSE).

### The rest of this project is in the public domain

The rest of this project is in the worldwide [public domain](LICENSE.md). As stated in [CONTRIBUTING](CONTRIBUTING.md):

> This project is in the public domain within the United States, and copyright and related rights in the work worldwide are waived through the [CC0 1.0 Universal public domain dedication](https://creativecommons.org/publicdomain/zero/1.0/).
>
> All contributions to this project will be released under the CC0 dedication. By submitting a pull request, you are agreeing to comply with this waiver of copyright interest.
