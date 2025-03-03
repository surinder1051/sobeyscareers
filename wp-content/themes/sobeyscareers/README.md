# FP Foundation Theme

Part of the [`fp-foundation`](https://github.com/FlowPress/fp-foundation) framework for scaffolding and maintaining WordPress sites at FlowPress.

## Features

Includes out-of-the-box support for the following Gulp tooling:

* Bootstrap 4 - Configure [Gulpfile.json](https://github.com/FlowPress/fp-foundation-theme/blob/7f45fac432343b6c999c91f26b41e213fef473fe/Gulpfile.json#L36) and [enqueue.php](https://github.com/FlowPress/fp-foundation-theme/blob/7f45fac432343b6c999c91f26b41e213fef473fe/classes/enqueue.php#L22)
* [Autoprefixer](https://github.com/postcss/autoprefixer) (configured via [`.browserlistrc`](https://github.com/ai/browserslist))
* Code Linting: Sass-lint, ESLint, PHP Code Sniffing (aka PHPCS), EditorConfig
* BrowserSync - synchronised browser testing (via proxy mode, e.g. `yourproject.dev`)
* Sourcemaps - SASS and Javascript
* SVG icon system

## FP-Foundation
FP-Foundation is a core framework used within this started theme. It's meant to be an updatable core to the theme which can be developed over time with new features. Currently FP-Foundation adds the following features:
* Autoloading of /classes folder
* Distibution of global components
* Distribution of global helper classes ( helpers, hooks, filters)
* Beaver Builder / WordPress Shortcode sharable framework
* Preview & Testing framework for Beaver Builder components
* Templates for post_type & taxonomy registration

## Prerequisites

1. [Install node ver 10+](https://nodejs.org/en/download/)
2. [Install NPM](https://www.npmjs.com/get-npm)

## Installation

1. Clone Starter Theme
    ```shell
    git clone git@github.com:FlowPress/fp-foundation-theme.git fp-foundation-theme
    ```
2. cd into fp-foundation-theme
3. Initialize FP Foundation
    ```shell
    ./fp-foundation/init.sh
    ```
    >more instructions at Install FP-Foundation as per instructions at https://github.com/FlowPress/fp-foundation

4. Install Required Libraries
    ```shell
    npm install
    ```
5. add node_modules to .gitignore
6. Run Gulp
    ```shell
    gulp
    ```
    >This will be the command you run everytime you start development on the project. This process converts SCSS files to CSS in real time and minifies JS files.

## Update FP-Foundation
1. cd into theme
2. run ./fp-foundation/update.sh


## Terminology

__Component(s)__ - Components are self encapsulated groups of files which represent a UI element on a page. A componenet includes:
* **PHP Class File** - resopnsible for registering the component with FP Foundation, registering and enquing CSS / JS files, setting up shortcode and linking the shortcode to Beaver Builder.
* **Template File** - this file will house only the html structure necessary for the component to be displayed. 
* **JS File** - supporting JavaScript code for the component can be placed here if necessary.
* **SCSS File** - SCSS styling rules for the component can be placed here, variations of the componenet will also be included in this SCSS code.


# Theme Structure

```

├── assets
│    ├── fonts                          # Fonts -- custom fonts provided by client.
│    ├── img                            # Original, source images.
│    ├── svg                            # All SVG icons ( `fill` property is stripped w/ Gulp ).
│    ├── scss                           # SCSS files  
│        ├── pages
│            ├── single-post.scss
│        ├── colors.scss
│        ├── variables.scss
│        ├── default_mixins.scss        # Self Updating file from fp-foundation
│        ├── mixins.scss                # Custom to the project
│        ├── index.scss
│    ├── js                             # JS files not tied to any components
│        ├── general.js
├── components                          # All Components are grouped by folder, specific to the client
│    ├── componentA                     # All related component files live here (where do we put vendor files?)
│        ├── componentA.js
│        ├── componentA.scss
│        ├── componentA.php
│        ├── componentA.tpl.php
|   ├── global-component-overwrite
|        ├── globalComponentA.scss      # Enqueued automatically and used to tweak a global component for this site
├── dist                                # All files auto-generated with Gulp.
│    ├── img                            # Optimized images
│        ├── svg_icons.svg              # `<defs>` of svgs
|        |── *.png
|        |── *.jpg
│    ├── css                            # All minified CSS files from the `assets/scss/*` folder
│        ├── pages
│            ├── single-post.css
│    ├── js                             # All minified JS files from the `assets/js/*` folder
│        ├── general.js
│    ├── components                     # All component front-end component/**/*.js (js/css) files
│        ├── componentA.min.js
│        ├── componentA.css
│        ├── componentB.min.js
│        ├── componentB.css
├── docs                                # Documentation files
├── fp-foundation                       # Self Updating Directory [DO NOT CHANGE!!!]
│    ├── global-components              # Shared FP custom build components
|        ├── globalComponentA.js
|        ├── globalComponentA.scss
|        ├── globalComponentA.tpl.php
|        ├── globalComponentA.php
|    ├── classes
|        ├── hooks/
|        ├── helpers/
|        ├── actions/
|        ├── shortcodes/
│        ├── enqueue.php                # All our enqueueing.
|        ├── autoload.php
|        ├── component.php
|    ├── templates                      # Scaffolding for registering post types and taxonomies
|        ├── register-post-types.php
|        ├── register-taxonomies.php
|        ├── site-init.php
│    ├── autoload.php                   # Responsible for autoloading of classes
│    ├── init.sh                        # Init new theme structure
│    ├── update.sh                      # Self Update Script
├── classes                             # Other project specific classes, classes are AUTO-LOADED
|    ├── hooks/
|    ├── helpers/
|    ├── actions/
│    ├── enqueue.php                    # Load styles and scripts.
├── templates                           # WordPress Reusable Templates
|    ├── template-two-column.php        # Reusable templates that can be applied to multiple pages
|    ├── template-three-column.php
├── template-parts                      # Template Partials that can be used with reusable templates
├── index.php
├── search.php
├── header.php
├── footer.php
├── page.php
├── page-slug.php                       # Single use templates per page
├── 404.php
├── archive.php
├── single.php
├── single-post_type.php
└── style.css                           # WordPress theme declaration header mostly.
 ```

# Development

## Bootstrap: Why? How?

We're using the officially supported Bootstrap Sass project within this theme. Out of the box we're _only_ using the grid system.

### Why the grid only?

We use Beaver Builder, and as of BB 1.10 [they're using Bootstrap's grid](http://kb.wpbeaverbuilder.com/article/141-theme-css-reference). Otherwise, we would likely roll our own grid system.

### How to use Bootstrap's grid within FP Foundation Theme

First, please read [Bootstrap's Grid System documentation](http://bootstrapdocs.com/v3.0.3/docs/css/#grid).

## Registering Post Types & Taxnomies

Post type registration and taxonomy registration should happen via mu-plugins. Follow the following steps:
1. cp theme/fp-foundation/templates/site-init.php to /wp-content/mu-plugins/site-init.php
1. cp theme/fp-foundation/templates/register-post-types.php to /wp-content/mu-plugins/register-post-types.php
1. cp theme/fp-foundation/templates/register-taxonomies.php to /wp-content/mu-plugins/register-taxonomies.php

>Important rule: These files must be copied out to your theme file. Nothing in fp-foundations should ever be edited directly

## Supporting Plugins

* ACF - Advanced Custom Fields is required to be used for all meta data field registration. However **NOT** for post_type or taxonomy registration. There should be no other frameworks used for meta data fields creation.

## Post-Processing

To convert SCSS files to CSS and minifiy JavaScript files you need to run the Gulp command from the theme folder. This process will take care of getting files ready for production. 

**It's very important that this command gets run before you commit your changes, otherwise you won't see any changes once deployed.**

1. cd wp-content/themes/your_theme
2. gulp

The process will first clean out the your_theme/dist folder and recreate all files 

## Enquing non component files

By default use of components the requirement for additional CSS and JS files is substantially reduced. However, if there is functionality or styling that's additionally required you can enqueue those files through inc/enqueue.php file.

The SCSS files should be palced in **assets/scss/**.

>**Important rule:** Do not mix more then one piece of functionality in the same file. Keep code seperated on file by file basis and **name** your files clearly and closely to their functionality.


## New PHP Classes

New PHP classes should be placed in one of the following directories:
* classes/filters/
* classes/hooks/
* classes/shortcodes/
* classes/helpers

>Please note: All files within /classes are **AUTO-LOADED**, you do not need to include them in functions.php

>Also please keep one piece of functionality **per file** 

## Pages and Templates

The template files in `/theme/templates/` are meant to be used for building pages that share a similar layout. These templates can then be applied to Pages within the wp-editor.

The best use-case for this scenario would be to quickly build several pages through the wp-editor with a smiliar look and feel, but different content. A single template file could cover multiple pages while offering dynamic content powered through the wp-editor.

The standard `page-slug.php` templates in the root of the theme are for building pages that do not share a similar layout. In that case you would add a `page-slug.php` for each unique page you are building, adding shared components to these templates as required. 

> Example: If you were building an FAQ section for the site which consisted of multiple pages, you would create a single reusable template as `/theme/templates/template-faq.php` instead of individual templates per page as `page-faq1.php` `page-faq2.php` `page-faq3.php`, since all FAQ pages share a simliar layout with only the content body changing.

Finally, `/theme/template-parts/` are for use with reusable templates in `/theme/templates/` allowing you to keep the shared structure from your templates while changing content areas as needed. These can be ignored if not using reusable templates.


# Components

## Global Components
Global components are components that are to be shared across multiple websites and distributed through the fp-foundation framework. 

## Creating Components
You can create new components by running a gulp task which will guide you through the steps.
```
gulp new-component
```
A folder will be created in components/new-component with required component assets.

## Adding Component to WordPress Template

Within your page templates, and within your post loops if applicable, include the following line of code to call your component.

```php
include( locate_template( 'components/hero/hero.tpl.php', false, false ) );
```

You can pass variables from the templates to components by doing the following

## Regionalization of Components

The content within a component, as well as the entire component itself, can be modified to render differently based on region. 

To first enable geoip support, add the following [WP Engine plugin](https://wordpress.org/plugins/wpengine-geoip/) to your site.

### Regionalizing the content of a component

Shortcodes can be used to regionalize the content of a component through the WordPress editor.

As an example, here is how to limit a line of copy to visits within Ontario, Canada.

`[geoip-content country="CA" region="ON"] Content just for Ontario visitors [/geoip-content]`

You may also create content fields through ACF and reference those fields through your templates to pass to your components. 


### Regionalizing an entire component

You may choose to only display certain components for users from select locations. You can control this at the template level like so:

```php
    
    $geos = array(
        'countryname'  => getenv( 'HTTP_GEOIP_COUNTRY_NAME' ),
        'region'       => getenv( 'HTTP_GEOIP_REGION' ),
        'city'         => getenv( 'HTTP_GEOIP_CITY' ),
    );
    if ( $geos['city'] == 'Toronto' ) {
    ?>
        <h2>GeoIP Test for Toronto Component</h2>

        <?php
        include( locate_template( 'fp-foundation/global-components/content_box/content_box.tpl.php', false, false ) );
    }

```
### Global regionalization

The existing `lib/class_region.php` has been moved in to `classes/helpers/regions.php` along with all initizalition logic. 

## Overwriting Global Components

In the event that you need to tweak the CSS a global component for a specific website you can create an overwrite SCSS file.

Example: Global Component content_box

original located in
```
/theme/fp-foundation/global-components/content_box/
````

create overwrite in 
```
/theme/components/content_box/content_box.scss
```
The changes in content_box.scss will automatically be loaded and you can create overwrites of this global component for this website.

# Options Page

Please refer to the [following wiki page](https://github.com/FlowPress/fp-foundation-theme/wiki/Options-Page) for detailed information on setting up an options page
