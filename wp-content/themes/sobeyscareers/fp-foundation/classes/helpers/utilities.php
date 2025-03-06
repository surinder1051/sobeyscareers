<?php
/**
 * Utilities
 *
 * @package fp-foundation
 */

/**
 * Create an admin notice with a custom message
 *
 * @param string $message is the content to display.
 * @param string $type is the WP admin notice type.
 */
add_action(
	'fp_admin_notice',
	function ( $message, $type ) {
		switch ( $type ) {
			case 'error':
				$class = 'notice notice-error';
				break;

			default:
				$class = 'notice notice-success';
				break;
		}
		printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), esc_html( $message ) );
	},
	10,
	2
);

/**
 * Get theme colour options are used in the theming functions to create of options formatted in ACF style, when the options are needed before ACF is loaded.
 * This is and old version
 *
 * @param string $prefix (optional) is the stored colour prefix.
 * @param string $suffix (optional) is the stored colour suffix.
 *
 * @return array $theme
 */
function get_theme_color_options( $prefix = '', $suffix = '' ) {
	$theme = array();

	$colors = get_option( '_options_color_theme' );
	if ( is_countable( $colors ) ) {
		$theme = array( '' => 'Default' );
		if ( ! empty( $colors ) ) {
			foreach ( $colors as $color ) {
				$theme[ $prefix . $color['color'] . $suffix ] = $color['color_name'];
			}
		} else {
			// set some defaults for backwards compatibility in case white or grey were saved somewhere.
			$theme[ $prefix . 'white' . $suffix ] = __( 'White', 'fp' );
			$theme[ $prefix . 'grey' . $suffix ]  = __( 'Grey', 'fp' );
		}
	}
	return $theme;
}

if ( ! function_exists( 'fp_which_env' ) ) {
	/**
	 * Which environment is this site running in. If not on WP Engine, assume local.
	 *
	 * @return string $env
	 */
	function fp_which_env() {
		$env = null;
		if ( defined( 'WPE_PLUGIN_BASE' ) ) {
			if ( ( ! empty( $_SERVER['HTTP_HOST'] ) ) ) {
				if ( false !== strpos( $_SERVER['HTTP_HOST'], 'dev.' ) ) { //phpcs:ignore
					$env = 'DEV';
				} elseif ( false !== strpos( $_SERVER['HTTP_HOST'], 'staging' ) ) { //phpcs:ignore
					$env = 'STAGING';
				} elseif ( false !== strpos( $_SERVER['HTTP_HOST'], 'preview' ) ) { //phpcs:ignore
					$env = 'PREVIEW';
				} else {
					$env = 'PRODUCTION';
				}
			} else {
				$env = 'PRODUCTION';
			}
		} else {
			// Local.
			$env = 'LOCAL';
		}
		$env = 'PRODUCTION';

		if ( ! defined( 'FP_ENV' ) ) {
			define( 'FP_ENV', $env );
		}
		return $env;
	}
}

fp_which_env();

if ( ! function_exists( 'get_registered_thumbnails' ) ) {
	/**
	 * Used in BB select options to display an option list of registered image sizes.
	 *
	 * @return array $thumbnails
	 */
	function get_registered_thumbnails() {
		$thumbnails_array = array_values( get_intermediate_image_sizes() );
		$thumbnails       = array();
		foreach ( $thumbnails_array as $key => $value ) {
			$thumbnails[ $value ] = $value;
		}
		return $thumbnails;
	}
}

if ( ! function_exists( 'get_registered_post_types_option' ) ) {
	/**
	 * Used in BB select options to display an option list of registered post_types.
	 *
	 * @return array $post_types
	 */
	function get_registered_post_types_option() {
		$post_types = get_post_types();
		return $post_types;
	}
}

if ( ! function_exists( 'list_thumbnail_sizes' ) ) {
	/**
	 * Used in BB select options to display an option list of registered image sizes.
	 *
	 * @return array $r_sizes
	 */
	function list_thumbnail_sizes() {
		global $_wp_additional_image_sizes;
		$sizes   = array();
		$r_sizes = array();
		foreach ( get_intermediate_image_sizes() as $s ) {
			$sizes[ $s ] = array( 0, 0 );
			if ( in_array( $s, array( 'thumbnail', 'medium', 'large' ), true ) ) {
				$sizes[ $s ][0] = get_option( $s . '_size_w' );
				$sizes[ $s ][1] = get_option( $s . '_size_h' );
			} else {
				if ( isset( $_wp_additional_image_sizes ) && isset( $_wp_additional_image_sizes[ $s ] ) ) {
					$sizes[ $s ] = array( $_wp_additional_image_sizes[ $s ]['width'], $_wp_additional_image_sizes[ $s ]['height'] );
				}
			}
		}

		foreach ( $sizes as $size => $atts ) {
			$r_sizes[ $size ]['width']  = intval( $atts[0] );
			$r_sizes[ $size ]['height'] = intval( $atts[1] );
		}
		return $r_sizes;
	}
}

if ( ! function_exists( 'get_thumbnail_info' ) ) {
	/**
	 * Get the size information for a registered image thumbnail
	 *
	 * @param string $size is the registered thumbnail size name.
	 *
	 * @return array|false
	 */
	function get_thumbnail_info( $size ) {
		$sizes = list_thumbnail_sizes();
		if ( ! empty( $sizes[ $size ] ) ) {
			return $sizes[ $size ];
		}
		return false;
	}
}

if ( ! function_exists( 'draw_responsive_image' ) ) {
	/**
	 * Used to draw picture / img tag within modules
	 *
	 * @param string      $id is the attachment post Id.
	 * @param string      $key is the registered thumbnail size name.
	 * @param array       $atts is the BB saved settings array from the module.
	 * @param object      $module is the BB module object.
	 * @param string      $lazyload (optional) whether to lazyload the image, or not.
	 * @param bool|string $force_alt (optional) whether to force an image alt tag. Send a text string to override the stored alt.
	 *
	 * @return string
	 */
	function draw_responsive_image( $id, $key, $atts, $module, $lazyload = '', $force_alt = false ) {
		$thumbnail_info = get_thumbnail_info( $atts[ $key . '_size' ] );
		$src            = '';
		$src_medium     = '';
		$src_responsive = '';

		if ( empty( $atts['background_image'] ) ) {
			$src = "https://dummyimage.com/{$thumbnail_info['width']}x{$thumbnail_info['height']}/597544/fff";
		} else {
			$src = wp_get_attachment_image_src( $id, $atts[ $key . '_size' ] )[0];
		}

		if ( ! empty( $module->settings->background_image_medium ) ) {
			$src_medium = wp_get_attachment_image_src( intval( $module->settings->background_image_medium ), $atts[ $key . '_size' ] )[0];
		}

		if ( ! empty( $module->settings->background_image_responsive ) ) {
			$src_responsive = wp_get_attachment_image_src( intval( $module->settings->background_image_responsive ), $atts[ $key . '_size' ] )[0];
		}

		if ( isset( $force_alt ) && ! empty( $force_alt ) ) {
			$alt = $force_alt;
		} else {
			$alt = trim( strip_tags( get_post_meta( $module->settings->background_image, '_wp_attachment_image_alt', true ) ) ); // phpcs:ignore
		}

		ob_start();

		?>

		<div class="image-container">

			<?php if ( ! empty( $src_medium ) || ! empty( $src_responsive ) ) : ?>
				<picture>
					<?php if ( ! empty( $src_responsive ) ) : ?>
						<source media="(max-width: 767px)" <?php echo ( ! empty( $lazyload ) ) ? 'data-' : ''; ?>srcset="<?php echo esc_url( $src_responsive ); ?>">
					<?php endif; ?>
					<?php if ( ! empty( $src_medium ) ) : ?>
						<source media="(max-width: 992px)" <?php echo ( ! empty( $lazyload ) ) ? 'data-' : ''; ?>srcset="<?php echo esc_url( $src_medium ); ?>">
					<?php endif; ?>

					<img class='desk-img<?php echo ( ! empty( $lazyload ) ) ? ' lazyload' : ''; ?>' style='max-height: <?php echo esc_attr( $thumbnail_info['height'] ); ?>px' <?php echo ( ! empty( $lazyload ) ) ? 'data-' : ''; ?>src='<?php echo esc_url( $src ); ?>' alt='<?php echo esc_attr( $alt ); ?>' />
				</picture>
			<?php else : ?>
				<img class='desk-img<?php echo ( ! empty( $lazyload ) ) ? ' lazyload' : ''; ?>' style='max-height: <?php echo esc_attr( $thumbnail_info['height'] ); ?>px' <?php echo ( ! empty( $lazyload ) ) ? 'data-' : ''; ?>src='<?php echo esc_url( $src ); ?>' alt='<?php echo esc_attr( $alt ); ?>' />
			<?php endif; ?>

		</div>

		<?php

		ob_end_flush();
		return ob_get_contents();
	}
}


if ( ! function_exists( 'save_foundation_config' ) ) {

	/**
	 * Format the configuration option to be saved. [$type][$group][$key] = $value.
	 *
	 * @param array  $arr is the stored data from the config file that is being updated.
	 * @param string $path is the configuration option string or path.
	 * @param string $value is the config value.
	 * @param string $separator (optional) is how to split the config path.
	 *
	 * @return void - Array value returned by reference.
	 */
	function assign_array_by_path( &$arr, $path, $value, $separator = '.' ) {
		$keys = explode( $separator, $path );

		foreach ( $keys as $key ) {
			$arr = &$arr[ $key ];
		}

		$arr = $value;
	}

	/**
	 * This is called from cli and ghost inspector utilities to setup some test configurations for flowpress dashbord and GI
	 * Store options as [$type][$group][$key] = $value
	 *
	 * @param string       $type is the data type of the config eg: CONSTANT.
	 * @param string       $group is the named group of config options.
	 * @param string       $key is the individual object key.
	 * @param string|array $value is the stored value.
	 *
	 * @see assign_array_by_path()
	 *
	 * @return void
	 */
	function save_foundation_config( $type, $group, $key, $value ) {
		$file   = get_template_directory() . '/config.json';
		$string = file_get_contents( $file ); // phpcs:ignore
		$config = json_decode( $string, true );

		assign_array_by_path( $config, "$type>$group>$key", $value, '>' );

		file_put_contents( $file, json_encode( $config, JSON_PRETTY_PRINT ) ); // phpcs:ignore
	}
}


if ( ! function_exists( 'load_foundation_config' ) ) {
	/**
	 * Load the fp-foundation config file.
	 * Check if the file exists. If it doesn't, create it and add some sample data.
	 *
	 * @param string $type is the data type of the config to search eg: CONSTANT.
	 * @param string $group is the named group of config options (optional: returns all if null).
	 * @param string $key is the individual object key (optional: returns all if null).
	 *
	 * @return array|null
	 */
	function load_foundation_config( $type, $group = null, $key = null ) {
		$config_file = get_template_directory() . '/config.json';
		$file_data   = null;

		if ( ! file_exists( $config_file ) ) {
			$file_data = file_put_contents( $config_file, '' ); // phpcs:ignore
		} else {
			$file_data = file_get_contents( $config_file ); // phpcs:ignore
		}

		if ( empty( $file_data ) ) {
			file_put_contents( // phpcs:ignore
				$config_file,
				json_encode( //phpcs:ignore
					array(
						'constant' => array(
							'FP_MODULE_TEST_SUITES'  => array(
								'dev'        => array(
									'desktop' => '',
									'tablet'  => '',
									'mobile'  => '',
								),
								'staging'    => array(
									'desktop' => '',
									'tablet'  => '',
									'mobile'  => '',
								),
								'production' => array(
									'desktop' => '',
									'tablet'  => '',
									'mobile'  => '',
								),
							),
							'FP_MONITOR_URLS_SUITES' => array(
								'dev'        => array(
									'desktop' => '',
									'tablet'  => '',
									'mobile'  => '',
								),
								'staging'    => array(
									'desktop' => '',
									'tablet'  => '',
									'mobile'  => '',
								),
								'production' => array(
									'desktop' => '',
									'tablet'  => '',
									'mobile'  => '',
								),
							),
							'FP_MODULE_TESTS'        => array(),
							'FP_MONITOR_URLS_TESTS'  => array(),
						),
					),
					JSON_PRETTY_PRINT
				)
			);
			return null;
		}

		$config = json_decode( $file_data, true );

		if ( empty( $group ) ) {
			if ( ! empty( $config[ $type ] ) ) {
				return $config[ $type ];
			}
		}
		if ( empty( $key ) ) {
			if ( ! empty( $config[ $type ][ $group ] ) ) {
				return $config[ $type ][ $group ];
			}
		} else {
			if ( ! empty( $config[ $type ][ $group ][ $key ] ) ) {
				return $config[ $type ][ $group ][ $key ];
			}
		}
		return null;
	}
}

if ( ! function_exists( 'fp_apply_style' ) ) {
	/**
	 * This function can be used in component BB frontend.css.php files to create a simple styling block.
	 *
	 * @param string $id is the BB generated component id.
	 * @param string $selector is the custom module css selector.
	 * @param string $attr is the style property to assign. Default: 'color'.
	 * @param string $value is the css property value. This should not be null.
	 *
	 * @return void
	 */
	function fp_apply_style( $id, $selector, $attr = 'color', $value = null ) {
		if ( isset( $value ) && $value ) {
			if ( strstr( $attr, 'color' ) && strpos( $value, '#' ) === false ) {
				$value = "#$value";
			}
			if ( is_array( $selector ) ) {
				foreach ( $selector as $class ) {
					echo ".fl-node-{$id} {$class} { $attr : $value; }"; //phpcs:ignore
				}
			} else {
				echo ".fl-node-{$id} {$selector} { $attr : $value; }"; //phpcs:ignore
			}
		}
	}
}

if ( ! function_exists( 'strip_tags_content' ) ) {
	/**
	 * Custom strip tags function.
	 *
	 * @param string $text is the content to strip.
	 * @param string $tags are the tags to keep (optional).
	 * @param bool   $invert remove tags that don't match. Default false.
	 */
	function strip_tags_content( $text, $tags = '', $invert = false ) {

		preg_match_all( '/<(.+?)[\s]*\/?[\s]*>/si', trim( $tags ), $tags );
		$tags = array_unique( $tags[1] );

		if ( is_array( $tags ) && count( $tags ) > 0 ) {
			if ( false === $invert ) {
				return preg_replace( '@<(?!(?:' . implode( '|', $tags ) . ')\b)(\w+)\b.*?>.*?</\1>@si', '', $text );
			} else {
				return preg_replace( '@<(' . implode( '|', $tags ) . ')\b.*?>.*?</\1>@si', '', $text );
			}
		} elseif ( false === $invert ) {
			return preg_replace( '@<(\w+)\b.*?>.*?</\1>@si', '', $text );
		}
		return $text;
	}
}

if ( ! function_exists( 'fp_extract_excerpt' ) ) {
	/**
	 * Extract an excerpt from content generated in BB or yoast SEO.
	 *
	 * @param object $post is the post object.
	 * @param array  $atts are optional excerpt settings ( length and ellipsis ).
	 * @param bool   $as_words show the excerpt as whole words for a clean break. Optional.
	 *
	 * @return string
	 */
	function fp_extract_excerpt( $post, $atts = null, $as_words = false ) {
		$atts = shortcode_atts(
			array(
				'excerpt_length'   => 160,
				'excerpt_ellipsis' => '...',
			),
			$atts
		);

		$using_actual_excerpt = false;

		if ( class_exists( 'WPSEO_Options' ) ) {
			$meta_description = get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true );
		}

		if ( strpos( $post->post_content, '[' ) !== false ) {
			// Only run this filter if we need to eval shortcodes... this caused performance issues when templates where used in pages and caused an infinite loop of templates being rendered within templates.
			$post_bb_data = get_post_meta( $post->ID, '_fl_builder_data', true );

			if ( ! empty( $post_bb_data ) && false === strpos( json_encode( $post_bb_data ), '"settings":{"template' ) ) { //phpcs:ignore
				// Only run the_content filter if there is no templates in the beaver builder page.
				$post->post_content = apply_filters( 'the_content', $post->post_content );
			}
		}

		$post->post_content = wp_strip_all_tags( $post->post_content );

		if ( ! empty( $meta_description ) ) {
			$excerpt              = $meta_description;
			$using_actual_excerpt = true;
		} elseif ( ! empty( $post->post_excerpt ) ) {
			// If there is an excerpt use it.
			$excerpt = $post->post_excerpt;
		} elseif ( false !== strpos( $post->post_content, '</h1>' ) ) {
			// If there is a module with H1 split it and use what's left over after it.
			$excerpt = explode( '</h1>', $post->post_content, 2 );
			$excerpt = $excerpt[1];
		} else {
			// If all fails use post_content as a starting point.
			$excerpt = $post->post_content;
		}
		// Remove all tags and content inside those tags and leave just <p> tags and their content.
		$excerpt = strip_tags_content( $excerpt, '<p>' );
		if ( false !== strpos( $excerpt, '<p>' ) ) {
			// If <p> exists split on the first one to remove any prepending text (like dates).
			$excerpt = explode( '<p>', $excerpt, 2 );
			$excerpt = $excerpt[1];
			// Replace left over <p> tags with spaces.
			$excerpt = preg_replace( '#<[^>]+>#', ' ', $excerpt );
		}

		if ( ! $using_actual_excerpt ) {
			$found_year_in_first_paragraph = null;
			if ( preg_match( '/\b\d{4}\b/', substr( $excerpt, 0, 20 ), $matches ) ) {
				$found_year_in_first_paragraph = $matches[0];
				if ( strpos( wp_strip_all_tags( $excerpt ), $found_year_in_first_paragraph ) < $atts['excerpt_length'] ) {
					$excerpt = explode( $found_year_in_first_paragraph, $excerpt, 2 );
					$excerpt = $excerpt[1];
				}
			}
		}

		// Add space after period.
		$excerpt = preg_replace( '/(\.)([[:alpha:]]{2,})/', '$1 $2', $excerpt );

		if ( strlen( $excerpt ) > $atts['excerpt_length'] ) {
			if ( $as_words ) {
				$excerpt = wp_trim_words( $excerpt, round( $atts['excerpt_length'] / 4 ), $atts['excerpt_ellipsis'] );
			} else {
				$excerpt = rtrim( substr( $excerpt, 0, $atts['excerpt_length'] ) ) . $atts['excerpt_ellipsis'];
			}
		}

		if ( true === apply_filters( 'fp_filter_excerpts', true ) ) {
			// Convert things like ’.
			$excerpt = htmlentities( $excerpt );

			// Strip out specials unicode as it's breaking ajax responses https://www.google.com/search?q=%EF%BF%BD&oq=%EF%BF%BD&aqs=chrome..69i57.221j0j7&sourceid=chrome&ie=UTF-8
			// https://stackoverflow.com/questions/1176904/php-how-to-remove-all-non-printable-characters-in-a-string .
			$excerpt = preg_replace( '/[\x00-\x1F\x7F-\xFF]/', '', $excerpt );
		}
		return $excerpt;
	}
}

if ( ! function_exists( 'format_titles_excerpts' ) ) {
	/**
	 * Format titles and excerpts to a specific length, with an without elllipsis.
	 * Use for post arrays created from component dynamic parameters.
	 *
	 * @param array $posts are the posts to format.
	 * @param array $atts are the optional formatting parameters.
	 * @param bool  $as_words convert the title length to number of words for clean break. Optional.
	 *
	 * @return array $posts (formatted).
	 */
	function format_titles_excerpts( $posts, $atts, $as_words = false ) {
		$atts = shortcode_atts(
			array(
				'title_length'     => 36,
				'title_ellipsis'   => '...',
				'excerpt_length'   => 160,
				'excerpt_ellipsis' => '...',
			),
			$atts
		);

		if ( $posts ) {
			foreach ( $posts as $key => $post ) {
				setup_postdata( $post );
				$posts[ $key ]->post_title = $post->post_title;
				if ( strlen( $post->post_title ) > $atts['title_length'] ) {
					if ( $as_words ) {
						$posts[ $key ]->post_title = wp_trim_words( $post->post_title, round( $atts['title_length'] / 4 ), $atts['title_ellipsis'] );
					} else {
						$posts[ $key ]->post_title = substr( $post->post_title, 0, $atts['title_length'] ) . $atts['title_ellipsis'];
					}
				}

				$excerpt                     = fp_extract_excerpt( $post, $atts, $as_words );
				$posts[ $key ]->post_excerpt = $excerpt;
			}
		}

		return $posts;
	}
}

if ( ! function_exists( 'colour_constrast_check' ) ) {
	/**
	 * Test if set of colours pass accessibility contrast. Based on this site
	 * https://www.splitbrain.org/blog/2008-09/18-calculating_color_contrast_with_php
	 * check luminosity difference between two colours. Should be > 5
	 * If not return default background colours to be used
	 * parameters: hex values for Foreground (fg), Background(bg)
	 * Uses Beaver Builder hex_to_rgb to get the rgb values from a  hex string
	 *
	 * @param string $fg is the foreground colour.
	 * @param string $bg is the background colour.
	 *
	 * @return string accessible colour.
	 */
	function colour_constrast_check( $fg, $bg ) {
		$c1        = \FLBuilderColor::hex_to_rgb( $fg );
		$c2        = \FLBuilderColor::hex_to_rgb( $bg );
		$lum_score = 0;

		$lum_1 = 0.2126 * pow( $c1['r'] / 255, 2.2 ) +
		0.7152 * pow( $c1['g'] / 255, 2.2 ) +
		0.0722 * pow( $c1['b'] / 255, 2.2 );

		$lum_2 = 0.2126 * pow( $c2['r'] / 255, 2.2 ) +
		0.7152 * pow( $c2['r'] / 255, 2.2 ) +
		0.0722 * pow( $c2['r'] / 255, 2.2 );

		if ( $lum_1 > $lum_2 ) {
			$lum_score = ( $lum_1 + 0.05 ) / ( $lum_2 + 0.05 );
		} else {
			$lum_score = ( $lum_2 + 0.05 ) / ( $lum_1 + 0.05 );
		}

		if ( $lum_score > 5 ) {
			return $bg;
		} else {
			if ( $c1['r'] > 117 ) {
				// 757575 is the lightest gray that is visible on white, so using this as a starting point
				return '000000';
			} else {
				return 'ffffff';
			}
		}
	}
}
