<?php
/**
 * Plugin Name:	FlowPress Post Importer
 * Description:	Sync posts between Wordpress sites.
 * Version:		3.3.6
 * Author:		Jonathan Bouganim & FlowPress
 * Licence:		GPLv3
 */

defined( 'ABSPATH' ) or die( 'Access forbidden!' );

// plugin version.
define( 'FP_POST_IMPORTER_PLUGIN_VERSION', '3.3.6' );

// Hold our routes and other config details.
require_once dirname( __FILE__ ) . '/fp-post-importer-config.php';

// Load everything admin-related, show metabox...
require_once FP_POST_IMPORTER_LIBS . '/action-scheduler/action-scheduler.php';
require_once FP_POST_IMPORTER_INC . '/fp-action-scheduler-tuning.php';
require_once FP_POST_IMPORTER_INC . '/fp-post-importer.php';
require_once FP_POST_IMPORTER_INC . '/fp-post-importer-admin.php';
require_once FP_POST_IMPORTER_INC . '/fp-post-importer-language-support.php';
require_once FP_POST_IMPORTER_INC . '/fp-post-importer-admin-notices.php';
require_once FP_POST_IMPORTER_INC . '/fp-post-importer-host.php';
require_once FP_POST_IMPORTER_INC . '/fp-post-importer-client.php';
require_once FP_POST_IMPORTER_INC . '/fp-post-importer-cli.php';

// init activation hook
register_activation_hook( __FILE__, 'fp_post_importer_plugin_install' );
register_deactivation_hook( __FILE__, 'fp_post_importer_plugin_uninstall' );

// Run default settings on install to ensure we don't throw any errors
function fp_post_importer_plugin_install() {
    FP_Post_Importer_Admin::set_default_options();
    flush_rewrite_rules(true);
}

// Run default settings on uninstall to remove any default settings
function fp_post_importer_plugin_uninstall() {
    wp_clear_scheduled_hook(FP_Post_Importer_Admin::SCHEDULED_HOOK);
}

$plugin = new FP_Post_Importer();
$plugin->run();