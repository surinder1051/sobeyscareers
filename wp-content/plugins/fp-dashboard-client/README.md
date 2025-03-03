# Installation

It allows the dashboard to load data from the remote site running the plugin. The plugin sets up a rest endpoint that can be queries for different data functions.

Example: https://dashboard.flowpress.com/domain/unilock/wordpress/

# Configuration

* define the password from 1Password in your wp-config.php file. [FP_CLIENT_USER_PASSWORD]
* Activate the plugin and goto Settings > FP Client
* Click the admin notice link to create the FP Client user.

# Options

By default, admin emails will not be shown. To enable admin emails, define the variable 'FP_ENABLE_CLIENT_ADMIN_EMAILS' in your wp-config.php

example

* define( 'FP_ENABLE_CLIENT_ADMIN_EMAILS', 'True' );

## Changelog

- 0.1.7 ( Dec 20, 2022 ) - [Bart](https://github.com/raptor235)
    - Disabling fp-update_checker.json generation

- 0.1.6 ( May 09, 2022 ) - [Kaleb](https://github.com/brainfork)
	- Add variable definitions for `$admin_array`, `$user_array`, `$return`, `$tax_array`, `$post_types_json`, `$thumbnail_sizes_json`, and `$filesize` to functions in `fp-dashboard-data.php`

- 0.1.5 ( Sept 9, 2021 ) by Bart Dabek
	- Disable fp-update_checker.json generation by default.
