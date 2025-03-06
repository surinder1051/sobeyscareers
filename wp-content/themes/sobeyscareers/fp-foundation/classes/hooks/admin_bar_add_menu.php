<?php
/**
 * Admin Bar Add Menu
 *
 * @package fp-foundation
 */

namespace fp;

/**
 * Add some custom menu items to the WP admin toolbar: FacetWP, FlowPress
 *
 * @param object $admin_bar is the WP admin bar to edit.
 *
 * @return void
 */
function add_item( $admin_bar ) {

	global $current_user;

	if ( false !== strpos( $current_user->user_email, 'flowpress' ) && defined( 'FACETWP_VERSION' ) ) {
		$args = array(
			'id'    => 'facetwp', // Must be a unique name.
			'title' => 'FacetWP', // Label for this item.
			'href'  => '/wp-admin/options-general.php?page=facetwp',
			'meta'  => array(
				'target' => '_blank', // Opens the link with a new tab.
				'title'  => __( 'Your site', 'fp' ), // Text will be shown on hovering.
			),
		);
		$admin_bar->add_menu( $args );
	}

	if ( false !== strpos( $current_user->user_email, 'flowpress' ) ) {
		$args = array(
			'id'    => 'dbmgp', // Must be a unique name.
			'title' => 'DB-MP', // Label for this item.
			'href'  => '/wp-admin/tools.php?page=wp-migrate-db-pro',
			'meta'  => array(
				'target' => '_blank', // Opens the link with a new tab.
				'title'  => __( 'Your site', 'fp' ), // Text will be shown on hovering.
			),
		);
		$admin_bar->add_menu( $args );
	}

	$args = array(
		'id'    => 'fp', // Must be a unique name.
		'title' => 'FlowPress', // Label for this item.
		'href'  => __( 'your_site_url', 'fp' ),
		'meta'  => array(
			'target' => '_blank', // Opens the link with a new tab.
			'title'  => __( 'Your site', 'fp' ), // Text will be shown on hovering.
		),
	);
	$admin_bar->add_menu( $args );

	$args = array(
		'id'     => 'child-menu-module-tests',
		'parent' => 'fp',
		'title'  => __( 'Module Tests', 'fp' ),
		'href'   => site_url() . '?tpl=module-tests',
	);
	$admin_bar->add_menu( $args );

	$args = array(
		'id'     => 'child-menu-config',
		'parent' => 'fp',
		'title'  => __( 'FP Foundation Config', 'fp' ),
		'href'   => site_url() . '?fp-show-config=1',
	);
	$admin_bar->add_menu( $args );

	$args = array(
		'id'     => 'child-menu-queries',
		'parent' => 'fp',
		'title'  => __( 'Log # of Queries per component to QM and error log', 'fp' ),
		'href'   => site_url() . '?test_queries=1',
	);
	$admin_bar->add_menu( $args );

}
add_action( 'admin_bar_menu', 'fp\add_item', 9 ); // 10 = Position on the admin bar

/**
 * Add a meny item to the flowpress menu container.
 *
 * @param string $name is the menu name.
 * @param string $url is the url for the menu item.
 *
 * @return void
 */
function add_item_fp_menu( $name, $url ) {

	add_action(
		'admin_bar_menu',
		function ( $admin_bar ) use ( $name, $url ) {
			$args = array(
				'id'     => sanitize_title( $name ),
				'parent' => 'fp',
				'title'  => $name,
				'href'   => $url,
			);
			$admin_bar->add_menu( $args );
		},
		10
	);
}

add_action( 'add_item_fp_menu', 'fp\add_item_fp_menu', 10, 2 );

/**
 * Position the factwp and foundation menu items in a specific order of the admin toolbar.
 *
 * @return void
 */
function reorder_admin_bar() {
	global $wp_admin_bar;

	// The desired order of identifiers (items).
	$ids_sequence = array(
		'wp-logo',
		'my-sites',
		'site-name',
		'new-content',
		'edit',
		'fl-builder-frontend-edit-link',
		'new_draft',
		'fp',
	);

	// Get an array of all the toolbar items on the current page.
	$nodes = $wp_admin_bar->get_nodes();

	// Perform recognized identifiers.
	foreach ( $ids_sequence as $id ) {
		if ( ! isset( $nodes[ $id ] ) ) {
			continue;
		}

		// This will cause the identifier to act as the last menu item.
		$wp_admin_bar->remove_node( $id );
		$wp_admin_bar->add_node( $nodes[ $id ] );

		// Remove the identifier from the list of nodes.
		unset( $nodes[ $id ] );
	}

	// Unknown identifiers will be moved to appear after known identifiers.
	foreach ( $nodes as $id => &$obj ) {
		// There is no need to organize unknown children identifiers (sub items).
		if ( ! empty( $obj->parent ) ) {
			continue;
		}

		// This will cause the identifier to act as the last menu item.
		$wp_admin_bar->remove_node( $id );
		$wp_admin_bar->add_node( $obj );
	}
}
add_action( 'wp_before_admin_bar_render', 'fp\reorder_admin_bar', 1000 );
