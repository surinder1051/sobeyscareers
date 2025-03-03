<?php

// Allows us to disable user snap on staging for performance testing
add_filter('option_active_plugins', 'lg_disable_cart66_plugin');

function lg_disable_cart66_plugin($plugins)
{
	if (isset($_GET['disable_user_snap'])) {
		$key = array_search('usersnap/usersnap.php', $plugins);
		if (false !== $key) {
			unset($plugins[$key]);
		}
	}

	if (isset($_GET['disable_3rd_party'])) {
		$key = array_search('usersnap/usersnap.php', $plugins);
		if (false !== $key) {
			unset($plugins[$key]);
		}
		$key = array_search('gigya-web-api/gigya-web-api.php', $plugins);
		if (false !== $key) {
			unset($plugins[$key]);
		}
		$key = array_search('gtm-tag-manager/gtm_tag_manager.php', $plugins);
		if (false !== $key) {
			unset($plugins[$key]);
		}
	}
	
	if (defined('DISABLE_PLUGINS_LOCAL')) {
		$plugins = array_diff($plugins, DISABLE_PLUGINS_LOCAL);
	}
	
	return $plugins;
}
