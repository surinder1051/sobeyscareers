FlowPress - BeaverBuidler Regionalization
====

### Settings 
- There is a settings page in WP-Admin > Settings > Beaver Builder > Regionaliztion. Here you can edit if you want to force pre-select all child regions.

### Pre-requisites
For this plugin to function properly you need to use two Wordpress filters to set all and the current region.
- `fp_bb_get_all_regions` - use this filter to override a multi-dimensional array you region hierarchy.
- `fp_bb_get_current_region` - use this filter to override the current region for the front-end filtering.

### Testing
You can set this constant in your `wp-config.php` to use sample set of region hierarchy, this will also allow you to set the current region via a GET param of `?fp_bb_region=region-name` for testing.
```
define('FP_BB_REGION_DEBUG', true);
```

### Changelog

- v1.9.2 ( November 25, 2021 ) - [Kaleb](mailto:kaleb@flowpress.com)
	- Add conditional check to prevent regionless stores being added to region select fields

- v1.9.1
	- Adding cron to clear regionliazation regions once a day

- v1.9.0
	- Added fp_bb_get_current_region filter into the plugin as it's the same filter for all sites

- v1.8.0
	- Reverted to priority 1, but moved the filter out and add seperate inserts for row/col/modules.

- v1.7.0
	- Added support for regionalized rows and columns. Added regions to options table so we can load the plugin on init - 0.

- v1.6.0
	- Fix missing filter_suffix.

- v1.5.0
	- Move classes to fl_builder page filtering only.

- v1.4.0
	- Fix wildcard region selection for tabs.

- v1.3.0
	- Fixed button alignment for beaver themer builder.

- v1.2.0
	- added ABSPATH check.

- v1.1.1
	- Fixed bug for current region not pulling.

- v1.1.0
	- Added feature to enable pre-selecting all child regions.

- v1.0.0
	- Init.
