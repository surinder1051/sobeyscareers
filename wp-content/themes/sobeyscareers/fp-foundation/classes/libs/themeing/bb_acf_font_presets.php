<?php //phpcs:ignore
/**
 * BB ACF Font Presets
 *
 * @package fp-foundation
 */

/**
 * Make Google Webfont API Call.
 * Get a list of custom fonts from functions.php using THEME_CUSTOM_FONTS constant.
 * Google fonts requires FP_GOOGLE_WEBFONT_APIKEY to be set.
 */
function fetch_google_webfonts() {
	$font_data = get_option( 'font_data' );

	if ( is_user_logged_in() && isset( $_GET['clearfonts'] ) ) { //phpcs:ignore
		$font_data = array();
	}

	// If custom fonts are set later, reset the font data.
	if ( defined( 'THEME_CUSTOM_FONTS' ) && ! empty( $font_data ) ) {
		if ( ! is_array( THEME_CUSTOM_FONTS ) ) {
			$custom_fonts[] = THEME_CUSTOM_FONTS;
		} else {
			$custom_fonts = THEME_CUSTOM_FONTS;
		}
		// Put all saved font families in an array and check against added custom fonts.
		$stored_fonts = array();
		foreach ( $font_data as $font ) {
			if ( isset( $font->family ) ) {
				$stored_fonts[] = $font->family;
			}
		}
		if ( ! empty( $custom_fonts ) ) {
			foreach ( $custom_fonts as $cf ) {
				if ( ! in_array( $cf, $stored_fonts, true ) ) {
					$font_data = array();
					break;
				}
			}
		}
	}

	if ( empty( $font_data ) ) {
		if ( defined( 'FP_GOOGLE_WEBFONT_APIKEY' ) ) {

			if ( ! $font_data ) {

				$jsonurl = 'https://www.googleapis.com/webfonts/v1/webfonts?key=' . FP_GOOGLE_WEBFONT_APIKEY;
				$json    = json_decode( file_get_contents( $jsonurl ) ); //phpcs:ignore
				$json    = apply_filters( 'fetch_google_webfonts', $json );

				delete_option( 'font_data' );
				add_option( 'font_data', (object) $json, '', 'no' );

				return $json;

			}
		} else {
			// If google api key doesn't exist, use the Beaver builder classes to generate the dropdown options.
			// Format the output the same as the google api data above, but the variants are part of the selected.
			$system_fonts  = ( class_exists( 'FLBuilderFontFamilies' ) ) ? apply_filters( 'fl_builder_font_families_system', FLBuilderFontFamilies::$system ) : array();
			$google_fonts  = ( class_exists( 'FLBuilderFontFamilies' ) ) ? apply_filters( 'fl_builder_font_families_google', FLBuilderFontFamilies::google() ) : array();
			$json['items'] = array();
			// Allow custom fonts from typekit etc.

			if ( defined( 'THEME_CUSTOM_FONTS' ) ) {
				if ( is_array( THEME_CUSTOM_FONTS ) ) {
					foreach ( THEME_CUSTOM_FONTS as $font_family ) {
						$json['items'][] = (object) array(
							'family'   => $font_family,
							'variants' => array( '400', '400i', '700', '700i' ),
						);
					}
				} elseif ( ! empty( THEME_CUSTOM_FONTS ) ) {
					$json['items'][] = (object) array(
						'family'   => THEME_CUSTOM_FONTS,
						'variants' => array( '400', '400i', '700', '700i' ),
					);
				}
			}

			if ( ! empty( $system_fonts ) ) {
				foreach ( $system_fonts as $name => $variants ) {
					if ( 'Default' !== $name ) {
						$font_family   = $name . ',' . $variants['fallback'];
						$font_variants = array();
						foreach ( $variants['weights'] as $font_weight ) {
							$font_variants[] = $font_weight;
						}
					}
					$json['items'][] = (object) array(
						'family'   => $font_family,
						'variants' => $font_variants,
					);
				}
			}
			if ( ! empty( $google_fonts ) ) {
				foreach ( $google_fonts as $name => $variants ) {
					if ( 'Default' !== $name ) {
						$font_family   = $name;
						$font_variants = array();
						foreach ( $variants as $font_weight ) {
							$font_variants[] = $font_weight;
						}
					}
					$json['items'][] = (object) array(
						'family'   => $font_family,
						'variants' => $font_variants,
					);
				}
			}
			if ( ! empty( $json['items'] ) ) {
				delete_option( 'font_data' );
				add_option( 'font_data', (object) $json, '', 'no' );
				return (object) $json;
			}
		}
	}

	return $font_data;
}

/**
 * Populate ACF font family field using Google Web Fonts API.
 * Use LOAD_THEME_ACF_OPTIONALS to enable heading font family in the ACF theme options.
 *
 * @param array $field is the acf field to auto populate.
 * @return array
 */
function acf_load_font_family_field_choices( $field ) {

	$json             = fetch_google_webfonts();
	$field['choices'] = array();

	if ( isset( $json->items ) ) {
		foreach ( $json->items as $font ) {
			$field['choices'][ $font->family ] = $font->family;
		}
	} else {
		$field['choices'][] = 'Failed to load Google Web Fonts';
	}

	return $field;

}

add_filter( 'acf/load_field/name=font_family', 'acf_load_font_family_field_choices' );
add_filter( 'acf/load_field/name=font_family_main_menu', 'acf_load_font_family_field_choices' );

// If the option to set heading fonts is true, add the saved fonts options as choices.
if ( defined( 'LOAD_THEME_ACF_OPTIONALS' ) && in_array( 'heading_font_settings', LOAD_THEME_ACF_OPTIONALS ) ) { //phpcs:ignore
	for ( $i = 1; $i < 7; $i++ ) {
		add_filter( 'acf/load_field/name=heading_' . $i . '_font_family', 'acf_load_font_family_field_choices' );
		add_filter(
			'acf/load_field/name=heading_' . $i . '_font_variants',
			function( $field ) {
				return setup_font_variant_field( $field, 'options_heading_font_settings_heading_x_font_family' );
			}
		);
	}
}

// Populate ACF font variant field on initial render.

add_filter(
	'acf/load_field/name=font_variants',
	function( $field ) {
		return setup_font_variant_field( $field, 'options_default_theme_options_fonts_font_family' );
	}
);

add_filter(
	'acf/load_field/name=font_variants_main_menu',
	function( $field ) {
		return setup_font_variant_field( $field, 'options_default_theme_options_fonts_font_family' );
	}
);

/**
 * Set up font variant ACF choices for the options page.
 *
 * @param array  $field is the acf field data.
 * @param string $id is the field ID.
 *
 * @return array
 */
function setup_font_variant_field( $field, $id ) {

	// Headings can't pass the iterator through the function and I chose to  run this through a loop instead of seven lines of filters.
	if ( strstr( $id, 'heading_x' ) ) {
		preg_match( '/[123456]{1}/', $field['name'], $h_tag );
		$id = str_replace( 'x', $h_tag[0], $id );
	}

	$field['choices']    = array();
	$current_font_family = get_option( $id );
	$font_data           = get_option( 'font_data' );

	if ( ! $current_font_family || ! $font_data ) {
		return $field;
	}
	if ( ! isset( $font_data->items ) ) {
		return;
	}

	foreach ( $font_data->items as $current_font_data ) {
		if ( $current_font_data->family !== $current_font_family ) {
			continue;
		}
		if ( isset( $current_font_data->variants ) ) {
			foreach ( $current_font_data->variants as $variant ) {
				$field['choices'][ $variant ] = $variant;
			}
		}
	}

	return $field;
}
