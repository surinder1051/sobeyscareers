<?php
/**
 * Register All Taxnomies
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'fp_register_all_taxonomies' ) ) {
	/**
	 * Register custom taxonomies
	 *
	 * @return void
	 */
	function fp_register_all_taxonomies() {
		global $fp_taxonomies, $fp_taxonomie_keys;

		if ( ! defined( 'FP_TAXONOMIES' ) ) {
			return;
		}

		$fp_taxonomies = FP_TAXONOMIES;

		foreach ( $fp_taxonomies as $taxonomy_slug => $taxonomy_data ) {
			$fp_taxonomie_keys[] = $taxonomy_slug;
			$plural              = ( isset( $taxonomy_data['plural'] ) ) ? $taxonomy_data['plural'] : $taxonomy_slug;
			$cap_plural          = ucfirst( $plural );
			$cap_post_type       = ucfirst( $taxonomy_slug );

			$args = shortcode_atts(
				array(
					'hierarchical'          => false,
					'show_ui'               => true,
					'show_admin_column'     => true,
					'show_in_rest'          => true,
					'show_in_nav_menus'     => true,
					'update_count_callback' => '_update_generic_term_count',
					'query_var'             => true,
					'public'                => true,
					'rewrite'               => array(
						'slug' => $taxonomy_slug,
					),
				),
				$taxonomy_data
			);

			$cap_plural_display    = ucwords( str_replace( array( '_', '-' ), array( ' ', ' ' ), $cap_plural ) );
			$cap_post_type_display = ucwords( str_replace( array( '_', '-' ), array( ' ', ' ' ), $cap_post_type ) );
			$plural_label          = ucwords( str_replace( array( '_', '-' ), array( ' ', ' ' ), $plural ) );
			$singular_label        = ( isset( $taxonomy_data['singular'] ) ) ? $taxonomy_data['singular'] : $cap_post_type;

			$args['labels'] = array(
				'name'                       => $cap_plural_display,
				'singular_name'              => $singular_label,
				// Translators: %s is the pluralized taxonomy label.
				'search_items'               => sprintf( __( 'Search %s', 'fp' ), $cap_plural_display ),
				// Translators: %s is the plural taxonomy label.
				'popular_items'              => sprintf( __( 'Popular %s', 'fp' ), $cap_plural_display ),
				// Translators: %s is the plural taxonomy label.
				'all_items'                  => sprintf( __( 'All %s', 'fp' ), $cap_plural_display ),
				'parent_item'                => null,
				'parent_item_colon'          => null,
				// Translators: %s is the singular taxonomy label.
				'edit_item'                  => sprintf( __( 'Edit %s', 'fp' ), $cap_post_type_display ),
				// Translators: %s is the plural taxonomy label.
				'update_item'                => sprintf( __( 'Update %s', 'fp' ), $cap_post_type_display ),
				// Translators: %s is the plural taxonomy label.
				'add_new_item'               => sprintf( __( 'Add New %s', 'fp' ), $cap_post_type_display ),
				// Translators: %s is the plural taxonomy label.
				'new_item_name'              => sprintf( __( 'New %s Name', 'fp' ), $cap_post_type_display ),
				// Translators: %s is the plural lowercase taxonomy label.
				'separate_items_with_commas' => sprintf( __( 'Separate %s with commas', 'fp' ), $plural_label ),
				// Translators: %s is the plural lowercase taxonomy label.
				'add_or_remove_items'        => sprintf( __( 'Add or remove %s', 'fp' ), $plural_label ),
				// Translators: %s is the plural lowercase taxonomy label.
				'choose_from_most_used'      => sprintf( __( 'Choose from the most used %s', 'fp' ), $plural_label ),
				// Translators: %s is the plural lowercase taxonomy label.
				'not_found'                  => sprintf( __( 'No %s found', 'fp' ), $plural_label ),
				'menu_name'                  => $cap_plural_display,
			);
			register_taxonomy( $taxonomy_slug, $taxonomy_data['post_types'], $args );
		}
	}

	add_action( 'init', 'fp_register_all_taxonomies', 0 );
}
