FlowPress - Centralized Plugin Distribution
====

This FP plugin is responsible for code distribution and integration with CircleCI jobs. The plugin sets up hooks and WP CLI commands for dealing with customized private plugin repos as well for dealing with module and FP foundaiton updates. All version updates are strictly enforced via customized json package files.

**Table of Contents**

- [Repository Structure](#repository-structure)
- [How the plugin works](#how-the-plugin-works)
- [Plugin Setup](#plugin-setup)
- [Continuous Integration Setup](#continuous-integration-setup)
- [JSON Setting Schema](#json-setting-schema)
- [CLI Commands](#cli-commands)
- [External Plugin Format](#external-plugin-format)
- [Adding a plugin to private repo](#adding-a-plugin-to-private-repo)
- [Changelog](#changelog)

## Repository Structure

This section explains the organizational structure of this repository:

* `admin/` directory contains admin functionality, assets and templates.
* `includes/` contains core plugin loader and activator files.
* `includes/class-fp-plugin-distribution-manager.php` contains core update functionality.
* `languages/` contains localization.
* `fp-plugin-distribution.php/` Igniter to get the plugin going.
* `README.md` You're reading it right now.
* `LICENSE.txt` GNU GENERAL PUBLIC LICENSE.
* `uninstall.php` Fired when the plugin is uninstalled.
* `/fp-plugin-packages.json` JSON structure for plugin packages.
* `/fp-component-packages.json` JSON structure for component packages and fp-foundation.


## Plugin Setup

 1. Install the plugin.
 2. Create the JSON plugin config. Sample version here `/plugins/fp-plugin-distribution/fp-plugin-packages-sample.json` and place it as this `/wp-content/fp-plugin-packages.json`. More details  under [JSON Setting Schema](#json-setting-schema).
 3. Activate the plugin.
 4. There are no special commands or pages, either from WP-Admin or WP-CLI, you should now see if any updates for your private repository are available and run the update as a normal plugin update.
 
 ## Continuous Integration Setup
 Circle CI auto-plugin update scripts can be found here:
 [FlowPress/fp-circle-ci-build-scripts/branch/feature/circle-2-pdo-update-plugins](https://github.com/FlowPress/fp-circle-ci-build-scripts/tree/feature/circle-2-pdo-update-plugins/)
 
 **README** and list of CI environment variables to set up:
 [FlowPress/fp-circle-ci-build-scripts/branch/feature/circle-2-pdo-update-plugins/README.md](https://github.com/FlowPress/fp-circle-ci-build-scripts/blob/feature/circle-2-pdo-update-plugins/steps/autoupdate-plugins-README.md)
 
## How the plugin works

Using various action hooks & filters, we override looking in the default WordPress repository for plugin install and updates. This can be used in WordPress admin as default plugin core update functionality or in WP-CLI as core `wp plugin` ... commands. Update settings are read from a JSON located in webroot folder.

## CLI Commands
For plugin updates, core WP CLI commands have no changed, this plugin will just filter those results with updates from GitHub private repositories.
```
`wp fp-plugin update --dry-run --priority --format=json` - list all high-priority plugin updates
`wp fp-plugin generate-packages` - add all missing plugins in fp-plugin-packages.json file
`wp fp-component list` - list all the components and current versions.
`wp fp-component list --check-update` - list all the components and current versions and check for components updates from GitHub.
`wp fp-component update` - update all components with available updates.
`wp fp-component update --dry-run` - don't run updates, but check.
`wp fp-component update --dry-run --priority` - don't run updates, but check filtering only high-priority.
`wp fp-component update --component=<component_name>` - update a single component.
`wp fp-component update-fp-foundation` -- update the fp-foundation.
`wp fp-component update-fp-foundation --dry-run` - check if there is an available update for fp-foundation.
```

## JSON Setting Schema
```json
{
	"name": "fp-plugin-manager-settings",
	"last-updated": "2018-07-17 16:00:00-0400",
	"description": "Plugin Manager for FlowPress managed repos.",
	"log-core": true, // Log any core updates that are not listed in the packages section below
	"platforms": [ "cli", "admin" ],
	"packages": {
		"formidable-wpml-test": { // folder name.
			"version": "~1.9.1", // See title - Update Version Expression Notation for detailed examples.
			"access_token": "EXTERNAL_GITHUB_TOKEN_CONSTANT", // only used for private gitHub repos, not needed if this is a Wordpress public plugin. Refers to the name of a constant/env variable to use.
			"core": false, // if this is a plugin from the Wordpress plugin repository. We will search the WP repo for available versions and if auto-update is enabled, use our own version matching rules.
			"branch": "master", // only used for private gitHub repos, not needed if this is a Wordpress public plugin.
			"auto-update": true, // auto-update allows us to filter the versioning format, if auto-update is off, the `wp plugin update --all` will update to the latest version. 
			"github-project-name": "formidable-wpml-test", // only used for private gitHub repos, not needed if this is a Wordpress public plugin.
			"github-username": "jbouganim", // only used for private gitHub repos, not needed if this is a Wordpress public plugin.
			"plugin-slug": "formidable-wpml-test" // slug if it's different than folder name, usually the main plugin loader filename.
		}	
	}
}
```

### Update Version Expression Notation
Using [this plugin library](https://packagist.org/packages/vierbergenlars/php-semver) to parse SemVar version expression.

// Another option is to use same notation for composer on auto updates for major or minor patches
// When we parse this file we can force specifiy a version for release if the default wp plugin pluginname update command is run
### Examples:

    "require": {
    "vendor/package": "1.3.2", // matches - exactly 1.3.2
	    // >, <, >=, <= | specify upper / lower bounds i.e. :
	    "vendor/package": ">=1.3.2", // anything above or equal to 1.3.2
	    "vendor/package": "<1.3.2", // anything below 1.3.2

    // * | wildcard
	    "vendor/package": "1.3.*", // >=1.3.0 <1.4.0
    
    // ~ | allows last digit specified to go up
	    "vendor/package": "~1.3.2", // >=1.3.2 <1.4.0
	    "vendor/package": "~1.3", // >=1.3.0 <2.0.0
    
    // ^ | doesn't allow breaking changes (major version fixed - following semver)
	    "vendor/package": "^1.3.2", // >=1.3.2 <2.0.0
	    "vendor/package": "^0.3.2", // >=0.3.2 <0.4.0 // except if major version is 0 
    }
    
## External Plugin Format

If you are not setting the `core: true` in the package JSON settings and looking to read from Git repos instead of the Wordpress repository then the plugin files must exist in the root folder of the repo. There should not be a containing parent folder. Further, releases with proper semVar versions should be created in that git repo, they can be based on branches, specific commits or tags. Detailed instructions on how to create releases is here:
https://help.github.com/en/articles/creating-releases

## Adding a plugin/component to private repo
1. Create a new GitHub repo with `plugin-pluginname`. (or `component-componentname`)
2. Push the contents of the plugin to this new repo's master branch.
3. Update the repo permissions to include the user which has access via the access_token you'll provide. If it's in the FlowPress, add the `Senior Developers` team.
4. Visit https://github.com/Flowpress/plugin-pluginname/releases/
5. Click `Draft a new release`, be sure to put the updated version of the plugin, keeping it the same as we update to ensure the version checking works. Add a version title and description if possible.
6. Publish the release. 
7. Add this plugin packages object in the `wp-content/fp-plugin-packages.json` on the site's repo. Be sure to include the version matching expression, access_token name if different from the default & set `core` to false, if it's a private repo.
8. Once a new verison of the plugin is released, push this to the master branch, repeat steps 3-6 for this updated version.
9. Be sure to add the token in `wp-config.php` or as an environment variable, if different from `FP_PLUGIN_GITHUB_TOKEN`.
10. `wp-content/automatic-update.log` log file is created for updates, add this to your .gitignore to ensure it's not being submitted.


## Changelog
- 1.7.0 - Added ability to self generate fp-plugin-packages.json file if not there via CLI command.
- 1.6.8 - Updated PHP version_compare to custom check which omits .0.
- 1.6.7 - Fix PHP 8 wp cli warnings
- 1.6.6 - Add a limit on transient fp_update_plugins so that plugin updates don't get stuck on old versions
- 1.6.5 - Update API to fp-dashboard for releases of private repos.
- 1.6.4 - Fix globv var single plugin update bug. Update core version sort with version_compare.
- 1.6.3 - Update repo plugin dist endpoint
- 1.6.2 - Updated README - fixed bug in fp-compontent update format.
- 1.6.1 - Updated high-priority flag/shortcode.
- 1.6.0 - Add support for high-priority/critical flag to filter plugins/components.
- 1.5.9 - Fix notice
- 1.5.8 - Filter update functions for when a specific plugin slug update is passed in
- 1.5.7 - Revert to filtering pre_set_site_transient_update_plugins transient instead of set_site_transient_update_plugins.
- 1.5.6 - Removed empty .gitignore.
- 1.5.5 - Update fp-component CLI output to include paths.
- 1.5.4 - Update site transient filter to load later and override premium plugin filters.
- 1.5.3 - Fix for core and 3 level semvar version support.
- 1.5.2 - Hide error logs on format=json to allow for proper parsing.
- 1.5.1 - Added error message for missing plugin repos.
- 1.5.0 - Added support for components and fp-foundation updates.
- 1.4.2 - Fix confilct with `Parsedown` class when class name is already defined
- 1.4.1 - Fixed bug for semvar version mismatch.
- 1.4.0 - Added constant/JSON validation. Updated messaging, added admin notices, fix version checking bugs, removed requirement for plugin-slug.
- 1.3.2 - Fixed notice with missing changelog in JSON core responses.
- 1.3 - Remove globally setting timezone. Using localized date instead.
- 1.2 - Change the hook priority to later.
- 1.1 - Fixed debug statement for missing plugin slug.
- 1.0.9 - Fixed call to get_message.
- 1.0.8 - Fixed issue with semvar with versions that have leading zeroes.
- 1.0.7 - Added self update to default config JSON.
- 1.0.6 - Removed debug and typo.
- 1.0.5 - Added check for env var to update core.
- 1.0.4 - Added fix to network support.
- 1.0.3 - Substitued access_token for name of a constant to look for in config. Added user_login to the log if updating via the admin.
- 1.0.2 - Added field in config JSON to log core plugins even if not in the packages list.
- 1.0.1 - Updated config JSON config with new packages format.
- 1.0.0 
