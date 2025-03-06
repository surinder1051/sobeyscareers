# FP Foundation

FlowPress core foundational framework which sets up component and class autoloading, standardized directory structure, various utilities, testing framework, themeing, WP CLI commands.

## Version

2.0.14

## Features

* General Autoloader
* Beaver Themer Shortcode Integration
* Component Autoloader
* Reusable Utilities
  * [Hooks](https://github.com/FlowPress/fp-foundation/tree/master/classes/hooks)
  * [Helpers](https://github.com/FlowPress/fp-foundation/tree/master/classes/helpers)
  * [Filters](https://github.com/FlowPress/fp-foundation/tree/master/classes/filters)
* [Component Template](https://github.com/FlowPress/fp-foundation/tree/master/templates/hiroy)
* [Themeing Framework](https://github.com/FlowPress/fp-foundation/tree/master/classes/testing-framework)

## Installation

1. create `wp-content/themes/fp-foundation-theme/`
2. `cd` into it
3. `git clone git@github.com:FlowPress/fp-foundation.git fp-foundation`
4. `./fp-foundation/init.sh`
5. add `node_modules` to `.gitignore` in the root of WordPress

## Configuration

### functions.php

1. Add the following to top of functions.php

	```php
	if ( file_exists( __DIR__ . '/fp-foundation/classes/autoload.php' ) ) {
		include __DIR__ . '/fp-foundation/classes/autoload.php';
	}
	```

### GulpFile.json

The GulpFile.json file is used for additional configuration but it's optional.

* **paths** - used to overwrite the paths that Gulp watches for js and sass processing. This can be overwritten so that each group has it's own destination folder and input folders. Mostly this will be used while transitioning from older Gulp setups.

* **dev_domain** - used to overwrite the local dev domain that you want BrowserSync to open.

* use - npm install --unsafe-perm=true, if you have trouble running gulp

### Show Configuration

To see what classes you have the option of loading do the following:

1. Login to WordPress
2. Run the domain with **?fp-show-config**
3. Now you'll see what classes are avialble to be autoloaded and what is.
** Note all components **are** autoloaded automatically

### Turning on autoloading of classes

1. In functions.php **before** the autoload.php if statement add the following
	```php
	define( 'LOAD_WP_HEAD', true );
	```

## Scripts

### Script - init.sh

1. Creates the fp-foundation-theme structure
2. Runs update script

### Script - update.sh
__NOTE:__ [the `update.sh` needs to be run from within the theme's directory](https://github.com/FlowPress/fp-foundation/blob/master/update.sh#L2).

1. Updates fp-foundation from GitHub
2. Clones fp-foudnation-theme from GitHub into fp-foundation/fp-foundation-theme directory
3. Copies template files from fp-foundation-theme into your theme (ie jshint.rc, GulpFile.js etc)
4. Runs npm install
5. Shows Gulp commands

### Script - update-core.sh

1. Updates the fp-foundation folder but NOT the fp-foundation-theme/parent theme directory
2. Run `sh ./fp-foundation/update-core.sh true` to SKIP updating your Gulpfile.js/node/package
3. `sh ./fp-foundation/update-core.sh` to update foundation and node


## Related projects
* [`fp-foundation-theme`](https://github.com/FlowPress/fp-foundation-theme)


## Changelog
- 2.0.14 ( July 6, 2023) [Mario]
	- Add hook & filter for loading css on top layer of BB UI, set defaults ( 'bb-field-colour-picker', 'bb-field-icon-picker', 'bb-field-checkbox' )
- 2.0.13 ( July 5, 2023) [Karen]
	- Fix fp-colour-picker and fp-icon-picker to work with BB 2.7.0.5
- 2.0.12 ( June 22, 2023) [Kaleb]
	- Remove redundant `class_exists` checks on `require` calls in post type/taxonomy select lib files
- 2.0.11 ( April 10, 2023) [Karen]
    - Fix theme js override when concat_js doesn't exist (backwards compatibility)
	- Fix an issue with overriding dynamic data by casting posts_per_page to int
	- Always check for extend-frontend.css.php in hiroy template
- 2.0.10 ( March 15, 2023) [Karen]
    - Add an update-core.sh file to only update foundation core files + theme gulp files ( if not skipped )
	- Merge fix from Daniel for Yoast check on excerpt helper
- 2.0.9 ( December 6, 2022) [Karen]
    - Check ACF exists for the options favicon class
- 2.0.8 ( November 30, 2022) [Karen]
    - Check that wp query returns only posts selected in dynamic query override settings
	- ACF nav menu dropdown styling
- 2.0.7 ( November 21, 2022) [Karen]
	- Show the alpha channel on the colour picker when this optio is set via BB
- 2.0.6 ( September 20, 2022) [Karen]
    - Add a property check on the component js register function for theme_override_js
- 2.0.5 ( September 20, 2022) [Karen]
    - Enable _theme.js file, create a concatenated file to load OR override the default component js file. Update hiroy/extend with new enable_js_theme functions. Remove unneeded scss files from update.sh
- 2.0.4 ( July 29, 2022) [Karen]
    - Add optional theme settings to generate theme (accent bar, chevron, background colour). Fix the extend frontend call in hiroy
- 2.0.3 ( May 31, 2022) [Bart]
    - Fix issues with PHP 8 and testing framework not working
- 2.0.2 ( May 19, 2022) [Karen]
	- Fix an issue where custom fonts where loaded as google fonts.
	- Enable custom singular labels when registering post types and taxonomies.
- 2.0.1 ( April 26, 2022) [Gabbi]
	- Update the colour picker scroll css to smooth it out.
- 2.0.0 ( April 14, 2022) [Karen]
	- Revert wp_json_de/encode to json_de/encode
- 1.9.9 ( April 13, 2022 ) - [Kaleb](https://github.com/brainfork)
	- Add missing argument to `function_exists` call in `/classes/helpers/searchable_post_types.php`
- 1.9.8 ( Apr 12, 2022 ) - Karen Laansoo
	- Fix an escaping issue on fp-apply-style
- 1.9.7 ( Apr 11, 2022 ) - Karen Laansoo
	- Use scandir to load bb icon svg images
- 1.9.6 ( Apr 8, 2022 ) - bart@flowpress.com
	- Fixes issue with some modules overwriting $component->atts var with null and fpf failing on echoing classes.
- 1.9.5 ( Apr 6, 2022 ) - bart@flowpress.com
	- Fixes an issue with generate theme checking for array but getting an object and proceeding to fail resulting in pages using generate_theme modules to not save
- 1.9.4 ( Apr 5, 2022 ) - Karen Laansoo
	- Remove duplicate require for register taxonomies
- 1.9.3 ( Mar 29, 2022 ) - Gabbi Nicolau
	- Add Favicon for wp admin
- 1.9.2 ( Mar 24, 2022 ) - Karen Laansoo
	- Code cleanup
- 1.9.1 ( Mar 24, 2022 ) - Bart Dabek
	- Fix PHP8 Testing Framework issues
- 1.9 ( Feb 14, 2022 ) - Karen Laansoo
	- remove redundant files. Update excerpt and title formatting functions to accept option to use `wp_trim_words` instead of chars
- 1.8.73 ( Feb 14, 2022 ) - Karen Laansoo
	- revert wp_json_encode when filters are added. Order `Posts` to the top of the admin menu. Fix flowpress admin URLs for show config and module tests
- 1.8.72 ( Feb 3, 2022 ) - Karen Laansoo
	- Add `$defer_css`, `$defer_js` optional properties to a component to enable deferring of scripts to help improve performance
	- Enabled SentenceCase class structure for component classes to meet naming standards. This is backwards compatible.
	- Fix empty posts being added to dynamic data posts from thumbnail processing (Mario Dabek, Jan 31, 2022 )
- 1.8.71 ( Jan 25, 2022 ) - Karen Laansoo
	- Updated file that creates theme options varibles to use the function that gets the values from get_option (Dec 7, 2021).
- 1.8.70 ( Jan 5, 2022 ) - Karen Laansoo
	- Added linted class name for components and clean up component.php file.
- 1.8.69 ( Jan 4, 2022 ) - Karen Laansoo
	- Set autoload to no for font option and improve scanning of added custom fonts. Add a get parameter to clear fonts option. Use `?clearfonts` to reset the option for new custom fonts, or to set autload to `no`.
- 1.8.68 ( Dec 20, 2021 ) - Karen Laansoo
	- Change init to acf/init for generating theme options fields. It's required in the recent version of acf pro
- 1.8.67 ( Dec 7, 2021 ) - Karen Laansoo
	- Create functions to get theme options via get_option instead of the acf `get_field` function. Add commenting and clean up files to wp standards
- 1.8.66 ( Nov 23, 2021 ) - Karen Laansoo
	- Update Hiroy to use fp-colour-picker, and update color-theme utility function to get the option value and set some default colour values
- 1.8.65 ( Nov 19, 2021 ) - Gabriela Nicolau
	- Fix register style for blog specific theme
- 1.8.64 ( Nov 8, 2021 ) - Mario Dabek
	- Fix static overwrites dropping thumbnail data in dynamic data library
- 1.8.63 ( Oct 27, 2021 ) - Karen Laansoo
	- Add constant option to override any core bb modules we disable by default
- 1.8.62 ( Oct 19, 2021 ) - Mario Dabek
	- Add component config support to dynamic pagination
- 1.8.61 ( Oct 19, 2021 ) - Bart Dabek
	- Make dynamicaly created tests exclude header / footer from testing
- 1.8.60 ( Oct 19, 2021 ) - Bart Dabek
	- Fix issue where in certain cases is_plugin_active wasn't defined
- 1.8.59 ( Oct 8, 2021 ) - Bart Dabek
	- Fix bad dependency
- 1.8.58 ( Oct 5, 2021 ) - Bart Dabek
	- Add component versioning into css / js enqueue
- 1.8.57 ( Oct 4, 2021 ) - Mario Dabek
	- Re-structure acf default_theme_options registration into single call
- 1.8.56 ( Oct 1, 2021 ) - Mario Dabek
	- Add support for custom favicon files from theme options
- 1.8.55 ( Oct 1, 2021 ) - Bart Dabek
	- Add support for wp fp-test command for GI suite & test mass setup routines
- 1.8.54 ( Sept 29, 2021 ) - Mario Dabek
	- Fix dynamic data library terms from getting overwritten when dealing with multiple taxonomies. Added support for stacked terms from all assigned taxonomies.
- 1.8.53 ( Sept 29, 2021 ) - Bart Dabek
	- Add support for multisite component themeing
- 1.8.52 ( Sept 10, 2021 ) - Bart Dabek
	- Add module usage tracking to endpoint
- 1.8.51 ( Sept 8, 2021 ) - Bart Dabek
	- Added rest hook for checking versions of plugins and modules
- 1.8.50 ( Aug 12, 2021 ) - Dale Mugford
	- Update themeing/fp-bb-icon-library.php and themeing/init.php with function checks to prevent fp-foundation from erroring if Beaver Builder is deactivated
- 1.8.49 ( Aug 12, 2021 ) - Karen Laansoo
	- Update generate-custom-theme-style.php and init.php (generate_theme()) to use an option instead of transient for theme_colours when ACF get_field() intermittently returns null
- 1.8.48 ( Aug 11, 2021 ) - Bart Dabek
	- Fix Github API call which has been depricated
- 1.8.47 ( Jul 28, 2021 ) - Bart Dabek
	- Add get_text filter for my-translate-wp to take into account ajax facetwp request lang param
- 1.8.46 ( Jul 26, 2021 ) - Karen Laansoo
	- Update the ACF Taxonomy icon picker to use FLBuilder to get registered icon paths
	- This fixes the icon loading issue on multi-sites
- 1.8.45 ( Jul 23, 2021 ) - Bart Dabek
	- Tweak env detection and add constant FP_ENV
	- Add preview admin bar styling
- 1.8.44 ( Jul 22, 2021 ) - Bart Dabek
	- Added a check
	- Added versioning to hiroy
- 1.8.43 ( Jul 21, 2021 ) - Bart Dabek
	- Add cookie dependency library to IE11 warning
- 1.8.42 ( Jul 19, 2021 )
	- KL - fix some bugs when FLBuilder isn't loaded and validate scripts require it. Call `general_global_theme_list` on theme options saved to ensure the colours transient is re-created
- 1.8.41 ( July 13, 2021 ) - [Kaleb](https://github.com/brainfork)
	- Add IE browser detection pop-up
- 1.8.40 ( Jun 30, 2021 )
	- Add a trigger for verions.json
- 1.8.39 ( Jun 29, 2021 )
	- Add Enable addition of custom fonts (eg typekit) into font selector through constant. Add .hx classes to dynamic theme css for styling non headers like headers
- 1.8.38 ( Jun 29, 2021 )
	- Add Beaver Themer / Polylang fix for matching proper language to site language
- 1.8.37 ( Jun 18, 2021 )
	- Add GOOGLE_SITE_VERIFICATION, FACEBOOK_SITE_VERIFICATION constants to print to wp_head without code push
- 1.8.36 ( June 16, 2021 )
	- Added js to remove active class from mobile menus when they exist during a facet change
- 1.8.35 ( June 15, 2021 )
	- Add automatic search query and query_var alterations for wp search and facetwp search based on config.json file searchable flag
- 1.8.34 ( June 14, 2021 )
	- Fix issue with wronly pointed class function
- 1.8.33 ( June 7, 2021 )
	- Fix versions.json generation cron job
- 1.8.32 ( June 7, 2021 )
	- Fix missing nonce security on bb_ajax_select_field_post function. Restrict to logged in users only.
- 1.8.31  ( June 3, 2021 )
	- Remove admin menu seperators as the custom ones were overwriting menu items
- 1.8.30  ( June 1, 2021 )
	- Add wp 5.6.2 fix for jquery initialize
	- Add optimize select post field
- 1.8.29  ( June 1, 2021 )
	- Add a focus visible polyfill script (github) to enable better cross-browser accessibility using the focus-visible class
	- Add call to FLBuilderIcons::get_sets_for_current_site() for consistent loading of icons across single or multi sites
- 1.8.27  ( May 18, 2021 )
	- Add versioning json generation for tracking assets
- 1.8.26  ( May 13, 2021 )
	- Optimize facetwp helper, remove full page refresh
- 1.8.25  ( May 6, 2021 )
	- Update to 1.8.25 for testing
- 1.8.24  ( May 4, 2021 )
	- Added support to output the component versions and filter for future edits in fp/component.
- 1.8.23  ( Apr 27, 2021 )
	- Adding wp_doing_ajax to check when pre_process function should run, as some gigya ajax modules were not rendering (recipe_card)
- 1.8.22  ( Apr 9, 2021 )
	- Add plugin update json config default overwrites
- 1.8.21  ( Apr 9, 2021 )
	- Fix bad condition
- 1.8.20  ( Apr 8, 2021 )
	- Fix issue with constants trying to override themselfes when they already exist.
- 1.8.19  ( Apr 8, 2021 )
	- Downgrade factwp_helper functions to pre 3.8 hooks
- 1.8.18  ( Apr 5, 2021 )
	- Fix issue with newline in _theme_variables.scss
- 1.8.16  ( Mar 25, 2021 )
	- Fix issue with module settings not being properly set when special characters are passed into a module data feed
	- Improve facetwp helper and autoload if facetwp is active
	- Adjust priority of registering post types as facetWp didn't get them in time for indexing
	- Add helper css classes to deal with card-deck columns
- 1.8.15  ( Mar 23, 2021 )
	- Fix issue where styalized select wasn't trigger facet change
- 1.8.14  ( Mar 22, 2021 )
	- Added search string shortcode
- 1.8.13  ( Mar 3, 2021 )
	- Apply the_content filter on post content to render shortcodes for excerpt display
- 1.8.12  ( Mar 1, 2021 )
	- Add level class to walker
	- Fix issue with module settings not being avialable in tpl file if called via shortcode
	- Add filter to disable character encoded stripping of excerpts
	- Add check for duplicate inline style generation
- 1.8.11  ( Feb 18, 2021 )
	- Added new setup function for all components to have a place to setup filters and other setup functionality.
	- Removed condition from autoloader so init_fields fires all the time again
- 1.8.10  ( Feb 10, 2021 )
	- Modify component autoloader so that it doesn't fire init_fields too often
- 1.8.9  ( Feb 1, 2021 )
	- Add CLI commands for generate plugin packages and monitored urls
- 1.8.8  ( Jan 29, 2021 )
	- Add support for generation of fp-plugin-package.json ( ?update_plugin_packages=1 )
- 1.8.7  ( Jan 25, 2021 )
	- Add support to track module version numbers, WP version, FPF Version
- 1.8.6  ( Jan 22, 2021 )
	- Fix call function to return results ( fixes monitored url dashboard )
- 1.8.5  ( Jan 21, 2021 )
	- Add support for env setting when creating automated GI test suites
	- Fix notices
- 1.8.4  ( Jan 19, 2021 )
	- Add support for tracking plugin versions to dashboard
- 1.8.3  ( Jan 14, 2021 )
	- Add dynamic GI folder / suite generation for monitored urls
- 1.8.2 ( Jan 13, 2021 )
	- Change module tracking function name as something was triggering it automatically
- 1.8.1 ( Jan 11, 2021 )
	- Catch errors for non existant components
	- Add disable_3rd_party=1 to all testing urls
- 1.8.0 ( Jan 7, 2021 )
	- Add support for module variation testing
	- Adjustments to module usage tracking
- 1.7.1 ( Dec 16, 2020 )
	- Update update.sh script to support templates & campaigns scss structure
	- Fix issue with branding file config
- 1.7 ( Dec 15, 2020 )
	- New: Add module usage tracking
- 1.6
	- New feature: Testing Framework
	- New feature: Themeing Framework
	- New feature: Config.json support for constant saving and loading
- 1.5.1
	- Add support for add_item_fp_menu - to add menu items to FlowPress dropdown
	- Add support for save_foundation_config() and load_foundation_config() to save and load data from foundation_config.json file
	- New Feature: module testing framework added accessible via ?tpl=module-testing-dashboard
- 1.5 Init Changelog
