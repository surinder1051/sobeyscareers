<?php
/**
 * BB Icon Library
 *
 * @package fp-foundation
 */

/**
 * This filter will force all BB icon packages to be put into wp-content/bb-icons/icon-brand instead of icon-time()
 *
 * @param $new_path is the directory to move the custom icons into.
 *
 * @return string
 */
add_filter(
	'fl_builder_icon_set_new_path',
	function ( $new_path ) {

		$new_path = ICON_DIR . 'icon-brand/';

		return $new_path;
	},
	10,
	1
);

/**
 * Return the custom icons directory to the BB hook.
 *
 * @param string $dirinfo is the default BB icons dir
 *
 * @return array $dirinfo customized
 */
add_filter(
	'fl_builder_get_cache_dir',
	function( $dirinfo ) {
		if ( 'icons' === basename( $dirinfo['path'] ) ) {
			$dirinfo['path'] = trailingslashit( ABSPATH ) . 'wp-content/bb-icons/';
			$dirinfo['url']  = trailingslashit( site_url() ) . 'wp-content/bb-icons/';
		}
		return $dirinfo;
	}
);


/**
 * Generate our own icon library for BB modal and also css to attach to general BB style of our icons to use through the theme.
 *
 * @return array $results
 */
function generate_icons() {
	$results = array(
		'css'       => '
		.fl-icon-field .fl-icon-preview i{
			padding: 10px;
		}
		',
		// Translators: %1$s is the h2 opening tag, %2$s is the closing h2 tag.
		'html'      => sprintf( __( '%1$sImage Based Icons (Colors not changable)%2$s', 'fp' ), '<h2>', '</h2>' ),
		'className' => '', // Added for ACF icon select field for inline style generation.
	);
	if ( is_dir( ICON_DIR ) && class_exists( 'FLBuilderIcons' ) ) {

		$enabled_icons = FLBuilderIcons::get_sets_for_current_site();

		if ( ! empty( $enabled_icons ) ) {
			foreach ( $enabled_icons as $icon_set => $icon_data ) {
				if ( 'brand' === $icon_data['name'] ) {
					$icon_image_dir = $icon_data['path'] . '/images/';
					if ( file_exists( $icon_image_dir ) ) {
						$icons = scandir( $icon_image_dir );
						foreach ( $icons as $icon ) {
							if ( $icon == '.' || $icon == '..' ) { //phpcs:ignore
								continue;
							}
							$icon_name             = str_replace( '.svg', '', $icon );
							$icon_url              = $icon_data['url'] . 'images/' . $icon;
							$results['html']      .= "<i class=\"fpicon-$icon_name\"></i>";
							$results['css']       .= ".fpicon-$icon_name{ \n
								width: 22px;
								height: 22px;
								display: inline-block;
								background: none !important; \n
								background-size: contain !important; \n
								background-repeat: no-repeat !important; \n
								background-image: url(\"$icon_url\") !important; \n
							}\n";
							$results['className'] .= "fpicon-$icon_name;"; // Added for ACF icon select field for inline style generation.
						}
					}
				}
			}
		}
	}
	return $results;
}

/**
 * Intercept the icon loading modal in BB and serve our own icon library instead of theirs.
 *
 * @param array $result
 */
add_filter(
	'fl_ajax_render_icon_selector',
	function ( $result ) {
		$icons = generate_icons();

		if ( ! empty( $icons['html'] ) ) {
			$pos            = strpos( $result['html'], '<h2>' );
			$result['html'] = substr_replace( $result['html'], $icons['html'], $pos, 0 );
		}

		return $result;
	},
	10,
	1
);


/**
 * Create and attach our own css icon library to use throughout the theme
 *
 * @param string $css is the default BB css
 * @param array  $nodes are BB active components.
 * @param array  $global_settings are BB global settings
 * @param bool   $include_global is a BB setting
 *
 * @see generate_icons()
 *
 * @return string $css updated
 */
add_filter(
	'fl_builder_render_css',
	function ( $css, $nodes, $global_settings, $include_global ) {
		$icon_css = generate_icons();
		if ( ! empty( $icon_css['css'] ) ) {
			$css .= $icon_css['css'];
		}
		return $css;
	},
	10,
	4
);

/**
 * This function creates a list of option items for ACF icon selectors
 * Used in nav menus, taxonomy settings etc.
 *
 * @return array
 */
function generate_icon_acf_list() {
	$results = array();
	if ( defined( 'ICON_DIR' ) && is_dir( ICON_DIR ) ) {

		$enabled_icons = FLBuilderIcons::get_sets_for_current_site();

		if ( ! empty( $enabled_icons ) ) {
			foreach ( $enabled_icons as $icon_set => $icon_data ) {
				if ( strstr( $icon_set, 'icon-' ) ) {
					$icon_image_dir = $icon_data['path'] . 'images/';
					$icon_font_dir  = $icon_data['path'] . 'css/';
					$icomoon_dir    = trailingslashit( ICON_DIR . $icon_set );
					if ( file_exists( $icon_image_dir ) ) {
						$icons = scandir( $icon_image_dir );
						foreach ( $icons as $icon ) {
							if ( $icon == '.' || $icon == '..' ) { //phpcs:ignore
								continue;
							}
							$icon_name                      = str_replace( '.svg', '', $icon );
							$results[ "fpicon-$icon_name" ] = $icon_name;
						}
					}
					if ( file_exists( $icon_font_dir . 'brand.css' ) ) {
						$icon_style = file_get_contents( $icon_font_dir . 'brand.css' );
						if ( false !== $icon_style ) {
							preg_match_all( '/\.(icon\-[A-Za-z\-\_]+)\:before/', $icon_style, $matches );
							if ( isset( $matches[1] ) ) {
								foreach ( $matches[1] as $icon ) {
									$results[ $icon ] = $icon;
								}
							}
						}
					}
					if ( file_exists( $icomoon_dir . 'style.css' ) ) {
						$icon_style = file_get_contents( $icomoon_dir . 'style.css' );
						if ( false !== $icon_style ) {
							preg_match_all( '/\.(icon\-[A-Za-z\-\_]+)\:before/', $icon_style, $matches );
							if ( isset( $matches[1] ) ) {
								foreach ( $matches[1] as $icon ) {
									$results[ $icon ] = $icon;
								}
							}
						}
					}
				}
			}
		}
	}
	return $results;
}
