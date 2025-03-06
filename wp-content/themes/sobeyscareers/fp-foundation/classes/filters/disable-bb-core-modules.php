<?php
/**
 * Functions to force hide default Beaver Builder modules from the admin screen.
 *
 * @package fp-foundation
 */

/**
 * Filter out which BB core modules we allow to be used. This is to prevent users form turning on modules via UI.
 * A constant can be set in functions.php to add back some core modules: `BB_ALLOW_CORE_MODULES`.
 * Hook into BB `fl_builder_enabled_modules`
 *
 * @param array $modules is the BB array of enable modules.
 *
 * @return array
 */
add_filter(
	'fl_builder_enabled_modules',
	function( $modules ) {

		$new_list        = array();
		$override        = ( defined( 'BB_ALLOW_CORE_MODULES' ) && is_array( BB_ALLOW_CORE_MODULES ) ) ? BB_ALLOW_CORE_MODULES : array();
		$allowed_modules = apply_filters( 'allowed_bb_core_modules', array( 'accordion', 'audio', 'callout', 'contact-form', 'content-slider', 'countdown', 'cta', 'gallery', 'html', 'map', 'numbers', 'post-carousel', 'post-grid', 'post-slider', 'pricing-table', 'sidebar', 'slideshow', 'social-buttons', 'subscribe-form', 'tabs', 'testimonials', 'widget', 'all' ) );

		foreach ( $modules as $key => $module ) {
			if ( in_array( $module, $allowed_modules ) === true && ! in_array( $module, $override ) ) {
				unset( $modules[ $key ] );
			} else {
				$new_list[] = $module;
			}
		}

		return $new_list;
	}
);

if ( ! function_exists( 'fl_builder_settings_exclude_modules' ) && is_admin() ) {

	/**
	 * This function is used load JS that will force uncheck core modules on the admin scren that we want to exclude from our theme build.
	 * A constant can be set in functions.php to add back some core modules: `BB_ALLOW_CORE_MODULES`
	 */
	function fl_builder_settings_exclude_modules() {
		if ( defined( 'LOAD_JS_FP_BB_RESTRICT_MODULES.JS' ) ) {
			wp_enqueue_script( 'LOAD_JS_FP_BB_RESTRICT_MODULES.JS' );

			$override        = ( defined( 'BB_ALLOW_CORE_MODULES' ) && is_array( BB_ALLOW_CORE_MODULES ) ) ? BB_ALLOW_CORE_MODULES : array();
			$exclude_modules = array( 'list' => array( 'accordion', 'audio', 'callout', 'contact-form', 'content-slider', 'countdown', 'cta', 'gallery', 'html', 'map', 'numbers', 'post-carousel', 'post-grid', 'post-slider', 'pricing-table', 'sidebar', 'slideshow', 'social-buttons', 'subscribe-form', 'tabs', 'testimonials', 'widget', 'all' ) );

			if ( ! empty( $override ) ) {
				foreach ( $override as $module ) {
					$unset = array_search( $module, $exclude_modules['list'] ); // phpcs:ignore.
					if ( false !== $unset ) {
						unset( $exclude_modules['list'][ $unset ] );
					}
				}
			}
			wp_localize_script( 'LOAD_JS_FP_BB_RESTRICT_MODULES.JS', 'excludeModules', $exclude_modules );
		}

	}
	add_action( 'admin_enqueue_scripts', 'fl_builder_settings_exclude_modules' );
}
