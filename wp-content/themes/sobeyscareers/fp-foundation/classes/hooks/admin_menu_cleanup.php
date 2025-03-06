<?php
/**
 * Admin menu clean up
 *
 * @package fp-foundation
 */

remove_action( 'welcome_panel', 'wp_welcome_panel' );

if ( ! function_exists( 'isa_disable_dashboard_widgets' ) ) {
	/**
	 * Remove the WP news and events widget, and the Yoast dashboard widget.
	 *
	 * @return void
	 */
	function isa_disable_dashboard_widgets() {
		/**
		 * Other meta box options:
		 * remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); -  Remove "At a Glance".
		 * remove_meta_box('dashboard_activity', 'dashboard', 'normal');// Remove "Activity" which includes "Recent Comments"
		 * remove_meta_box('dashboard_quick_press', 'dashboard', 'side');// Remove Quick Draft
		 */
		remove_meta_box( 'dashboard_primary', 'dashboard', 'core' ); // Remove WordPress Events and News.
		remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'side' );
	}
	add_action( 'admin_menu', 'isa_disable_dashboard_widgets' );
}

if ( ! function_exists( 'wpse26980_remove_tools' ) ) {
	/**
	 * Remove the tools menu option for non-flowpress users.
	 */
	function wpse26980_remove_tools() {
		global $current_user;

		if ( current_user_can( 'administrator' ) && false === strpos( $current_user->data->user_email, 'flowpress' ) ) {
			// Non FP Admin.
			remove_menu_page( 'tools.php' );

			remove_menu_page( 'edit.php?post_type=acf-field-group' );
			remove_submenu_page( 'options-general.php', 'facetwp' );
			remove_submenu_page( 'plugins.php', 'plugin-editor.php' );
			remove_submenu_page( 'themes.php', 'themes.php' );
			remove_submenu_page( 'themes.php', 'theme-editor.php' );
		} elseif ( in_array( 'editor', $current_user->roles ) && ! in_array( 'administrator', $current_user->roles ) ) { //phpcs:ignore
			// Editor.
			remove_menu_page( 'tools.php' );
		} elseif ( in_array( 'contributor', $current_user->roles ) && ! in_array( 'administrator ', $current_user->roles ) && ! in_array( 'editor', $current_user->roles ) ) { //phpcs:ignore
			// Contributor.
			remove_menu_page( 'tools.php' );
		}
	}
	add_action( 'admin_menu', 'wpse26980_remove_tools', 105 );
}



/**
 * Register Admin Menu Seperator(s).
 *
 * Function add_admin_menu_separator( $position ) {
 * global $menu;
 * $menu[$position] = array(
 * 0 =>    '',
 * 1 =>    'read',
 * 2 =>    'separator' . $position,
 * 3 =>    '',
 * 4 =>    'wp-menu-separator'
 * );
* }
* add_action('admin_init', 'add_admin_menu_separator');
*/


if ( ! function_exists( 'wpse_custom_menu_order' ) ) {

	/**
	 * Customize the admin menu order.
	 *
	 * @param array $menu_ord are the menu items.
	 *
	 * @return bool|array bool if there are no menus, updated menu, if there are.
	 */
	function wpse_custom_menu_order( $menu_ord ) {
		if ( ! $menu_ord ) {
			return true;
		}

		$post_types = array();
		foreach ( $menu_ord as $slug ) {
			if ( false !== strpos( $slug, 'edit.php' ) ) {
				$post_types[] = $slug;
			}
		}

		$order_a = array(
			'index.php', // Dashboard.
			'separator1', // First separator.
			'edit.php', // Posts.
			'edit.php?post_type=page', // Pages.
		);
		$order_c = array(
			'separator2', // Second separator.

			'upload.php', // Media.

			'separator90', // Second separator.

			'formidable', // Formidable.
			'wpseo_dashboard', // Yoast.
			'edit.php?post_type=fl-builder-template', // Beaver Builder.
			'edit.php?post_type=acf-field-group', // ACF.

			'separator91', // Second separator.

			'acf-options', // Theme Options.
			'themes.php', // Appearance.
			'plugins.php', // Plugins.
			'users.php', // Users.
			'tools.php', // Tools.

			'separator93', // Second separator.

			'options-general.php', // Settings.
			'separator-last', // Last separator.
		);

		$merged = array_merge( $order_a, $post_types, $order_c );

		return $merged;
	}
	add_filter( 'custom_menu_order', 'wpse_custom_menu_order', 1, 1 );
	add_filter( 'menu_order', 'wpse_custom_menu_order', 1, 1 );

}
