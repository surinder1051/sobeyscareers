<?php
/**
 * Flush WP Engine cache
 * Based on https://github.com/a7/wpe-cache-flush/ allows to flush wpe cache by calling a function.
 * use by calling \FP\WPE_Cache_Flush\cache_flush()
 * or
 * do_action('wpe_cache_flush')
 *
 * @package fp-foundation
 */

namespace FP\WPE_Cache_Flush;

/**
 * Allow cache flushing to be called independently of web hook
 *
 * @return void
 */

add_action(
	'init',
	function () {
		add_action(
			'wpe_cache_flush',
			function () {
				if ( function_exists( '\FP\WPE_Cache_Flush\cache_flush' ) ) {
					\FP\WPE_Cache_Flush\cache_flush();
				}
			}
		);

		if ( ! empty( $_GET['flush_wpe_cache'] ) && is_user_logged_in() ) { //phpcs:ignore
			if ( function_exists( '\FP\WPE_Cache_Flush\cache_flush' ) ) {
				\FP\WPE_Cache_Flush\cache_flush();
			}
		}
	}
);

if ( ! function_exists( 'add_fp_menu_item' ) ) {
	/**
	 * Add a link to this action in the FlowPress admin bar nav item.
	 *
	 * @param object $admin_bar is the nav bar object.
	 *
	 * @return void
	 */
		function add_fp_menu_item( $admin_bar ) {

		global $current_user;

		if ( false === strpos( $current_user->user_email, 'flowpress' ) ) {
			return;
		}

		$args = array(
			'id'     => 'flush-cache',
			'parent' => 'fp',
			'title'  => __( 'Flush WPE Cache', 'fp-foundation' ),
			'href'   => '?flush_wpe_cache=1',
		);
		$admin_bar->add_menu( $args );
	}
	add_action( 'admin_bar_menu', '\FP\WPE_Cache_Flush\add_fp_menu_item', 10 ); // 10 = Position on the admin bar.
}

if ( ! function_exists( 'cache_flush' ) ) {
	/**
	 * Clear the WP Engine cache if the mu plugin exists. (won't work locally).
	 *
	 * @return false|string on error.
	 */
	function cache_flush() {
		// Don't cause a fatal if there is no WpeCommon class.
		if ( ! class_exists( 'WpeCommon' ) ) {
			return false;
		}
		if ( function_exists( 'WpeCommon::purge_memcached' ) ) {
			\WpeCommon::purge_memcached();
		}
		if ( function_exists( 'WpeCommon::clear_maxcdn_cache' ) ) {
			\WpeCommon::clear_maxcdn_cache();
		}
		if ( function_exists( 'WpeCommon::purge_varnish_cache' ) ) {
			\WpeCommon::purge_varnish_cache();
		}
		global $wp_object_cache;
		// Check for valid cache. Sometimes this is broken -- we don't know why! -- and it crashes when we flush.
		// If there's no cache, we don't need to flush anyway.
		$error = '';
		if ( $wp_object_cache && is_object( $wp_object_cache ) ) {
			try {
				wp_cache_flush();
			} catch ( \Exception $ex ) {
				$error = 'Warning: error flushing WordPress object cache: ' . $ex->getMessage();
			}
		}
		return $error;
	}
}
