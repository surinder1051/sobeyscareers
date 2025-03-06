<?php
/**
 * BB ACF Colour Presets
 *
 * @package fp-foundation
 */

/**
 * Load color presets from ACF vs using default BB option
 *
 * @param array $presets are the colours defined in ACF theme options.
 *
 * @return array
 */
add_filter(
	'fl_builder_color_presets',
	function ( $presets ) {

		$colors = get_field( 'default_theme_options', 'option' );

		if ( empty( $colors ) ) {
			return $presets;
		}

		$themes = get_field( 'theme_colours', 'option' );

		if ( empty( $colors ) && empty( $themes ) ) {
			return $presets;
		}

		$unique_colors = array();

		foreach ( $colors as $key => $value ) {
			if ( empty( $value ) ) {
				continue;
			}
			if ( 'fonts' === $key ) {
				continue; }
			if ( 'text_links' === $key ) {
				continue; }
			if ( 'main_menu' === $key ) {
				continue; }

			if ( is_array( $value ) && ! empty( $value ) ) {
				foreach ( $value as $key2 => $value2 ) {
					if ( substr( $value2, 0, 1 ) !== '#' ) {
						$value2 = "#$value2";
					}
					$unique_colors[ $value2 ] = true;
				}
			} else {
				if ( substr( $value, 0, 1 ) !== '#' ) {
					$value = "#$value";
				}
				$unique_colors[ $value ] = true;
			}
		}

		if ( ! empty( $themes ) ) {
			foreach ( $themes as $theme ) {
				foreach ( $theme as $key => $value3 ) {
					if ( ! is_array( $value3 ) && ! empty( $value3 ) && preg_match( '/\#?[a-fA-F0-9]{3,}/', $value3 ) ) {
						if ( substr( $value3, 0, 1 ) !== '#' ) {
							$value3 = "#$value3";
						}
						$unique_colors[ $value3 ] = true;
					}
				}
			}
		}

		return array_keys( $unique_colors );
	}
);
