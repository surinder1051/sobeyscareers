<?php
/**
 * Register post types
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'fp_register_all_post_types' ) ) {
	/**
	 * Register all custom post types listed in the FP_POST_TYPES constant (config.json)
	 *
	 * @return array|void - empty array if there are no post type set.
	 */
	function fp_register_all_post_types() {
		global $fp_post_types, $fp_post_types_keys, $fp_searchable_post_types;

		if ( ! defined( 'FP_POST_TYPES' ) ) {
			define( 'FP_POST_TYPES', array() );
		}

		if ( empty( FP_POST_TYPES ) ) {
			return FP_POST_TYPES;
		}

		$fp_post_types      = FP_POST_TYPES;
		$fp_post_types_keys = array_keys( FP_POST_TYPES );

		$fp_searchable_post_types[] = 'page';

		foreach ( $fp_post_types as $post_type => $post_type_data ) {
			$plural               = ( isset( $post_type_data['plural'] ) ) ? $post_type_data['plural'] : $post_type;
			$cap_plural           = ucfirst( $plural );
			$cap_post_type        = ucfirst( $post_type );
			$show_for_admin_only  = ! empty( $post_type_data['show_for_admin_only'] );
			$advanced_permissions = isset( $post_type_data['advanced_permissions'] ) ? $post_type_data['advanced_permissions'] : false;
			$singular             = ( isset( $post_type_data['singular'] ) ) ? $post_type_data['singular'] : $cap_post_type;

			$args = shortcode_atts(
				array(
					'description'         => __( 'Description.', 'fp' ),
					'public'              => true,
					'exclude_from_search' => false,
					'publicly_queryable'  => true,
					'query_var'           => true,
					'show_ui'             => true,
					'show_in_menu'        => true,
					'show_in_nav_menus'   => true,
					'rewrite'             => array(
						'slug'       => $plural,
						'with_front' => false,
					),
					'map_meta_cap'        => null,
					'has_archive'         => false,
					'hierarchical'        => false,
					'menu_icon'           => null,
					'menu_position'       => null,
					'show_in_rest'        => true,
					'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
					'taxonomies'          => array(),
				),
				$post_type_data
			);
			if ( ! empty( $advanced_permissions ) && $advanced_permissions == 'true' ) { //phpcs:ignore
				$args['capability_type'] = array( $post_type, $plural );

				$args['capabilities'] = array(
					'edit_post'            => 'edit_' . $post_type,
					'read_post'            => 'read_' . $post_type,
					'delete_post'          => 'delete_' . $post_type,
					'delete_posts'         => 'delete_' . $plural,
					'delete_others_posts'  => 'delete_others_' . $plural,
					'edit_posts'           => 'edit_' . $plural,
					'edit_published_posts' => 'edit_published_' . $plural,
					'edit_others_posts'    => 'edit_others_' . $plural,
					'publish_posts'        => 'publish_' . $plural,
					'read_private_posts'   => 'read_private_' . $plural,
					'create_posts'         => 'edit_' . $plural,
				);

				if ( ! has_filter( 'map_meta_cap', 'fp_map_meta_cap' ) ) {
					add_filter( 'map_meta_cap', 'fp_map_meta_cap', 10, 4 );
				}
			}

			$post_type_plural = ucwords( str_replace( array( '_', '-' ), array( ' ', ' ' ), $plural ) );
			$print_cap_plural = ucwords( str_replace( array( '_', '-' ), array( ' ', ' ' ), $cap_plural ) );
			$uc_post_type     = ucwords( str_replace( array( '_', '-' ), array( ' ', ' ' ), $singular ) );

			$args['labels'] = array(
				'name'               => $uc_post_type,
				'singular_name'      => $uc_post_type,
				'menu_name'          => $print_cap_plural,
				'name_admin_bar'     => $uc_post_type,
				// Translators: %s is the label for the post type.
				'add_new'            => sprintf( __( 'Add New %s', 'fp' ), $uc_post_type ),
				// Translators: %s is the label for the post type.
				'add_new_item'       => sprintf( __( 'Add New %s', 'fp' ), $uc_post_type ),
				// Translators: %s is the label for the post type.
				'new_item'           => sprintf( __( 'New %s', 'fp' ), $uc_post_type ),
				// Translators: %s is the label for the post type.
				'edit_item'          => sprintf( __( 'Edit %s', 'fp' ), $uc_post_type ),
				// Translators: %s is the post type.
				'view_item'          => sprintf( __( 'View %s', 'fp' ), $uc_post_type ),
				// Translators: %s is the pluralized post type.
				'all_items'          => sprintf( __( 'All %s', 'fp' ), $print_cap_plural ),
				// Translators: %s is the pluralized post type.
				'search_items'       => sprintf( __( 'Search %s', 'fp' ), $print_cap_plural ),
				// Translators: %s is the pluralized post type.
				'parent_item_colon'  => sprintf( __( 'Parent %s:', 'fp' ), $print_cap_plural ),
				// Translators: %s is the pluralized post type.
				'not_found'          => sprintf( __( 'No %s found', 'fp' ), $post_type_plural ),
				// Translators: %s is the pluralized post type.
				'not_found_in_trash' => sprintf( __( 'No %s found in Trash', 'fp' ), $post_type_plural ),
			);

			register_post_type( $post_type, $args );

			// If searchable is defined and true make it searchable via query filter for search, if not defined then assume it's searchable.

			if ( ( isset( $post_type_data['searchable'] ) && filter_var( $post_type_data['searchable'], FILTER_VALIDATE_BOOLEAN ) ) || ! isset( $post_type_data['searchable'] ) ) {
				$fp_searchable_post_types[] = $post_type;
			}
		}
	}

	add_action( 'init', 'fp_register_all_post_types', 1 );
}

/**
 * Setup a global array that contains all canonical post_types.
 * This code will loop through all registered post types and add mappings for custom capabilitiy checks that happen in core, ie current_user_can('edit_posts') will be treated as current_user_can('edit_stories)
 *
 * @param array  $caps are the post admin capabilities.
 * @param string $cap is the post type capability.
 * @param string $user_id the current user.
 * @param array  $args are custom capabilities for the post type being registered.
 *
 * @return array $caps.
 */
function fp_map_meta_cap( $caps, $cap, $user_id, $args ) {
	global $fp_post_types_keys, $current_user;

	if ( ! isset( $args[0] ) ) {
		return $caps;
	}

	$user_type = null;

	if ( current_user_can( 'administrator' ) ) {
		$user_type = 'administrator';
	} elseif ( in_array( 'editor', $current_user->roles ) && ! in_array( 'administrator', $current_user->roles ) ) { //phpcs:ignore
		// Editor.
		$user_type = 'editor';
	} elseif ( in_array( 'contributor', $current_user->roles ) && ! in_array( 'administrator', $current_user->roles ) && ! in_array( 'editor', $current_user->roles ) ) { //phpcs:ignore
		// Contributor.
		$user_type = 'contributor';
	}

	foreach ( $fp_post_types_keys as $fp_post_type ) {
		// Code...
		// If editing, deleting, or reading a movie, get the post and post type object.

		if ( strpos( str_replace( 'edit_', '', $cap ), $fp_post_type ) === false ) {
			continue;
		}

		if ( 'edit_' . $fp_post_type == $cap || 'delete_' . $fp_post_type == $cap || 'read_' . $fp_post_type == $cap ) { //phpcs:ignore
			$post      = get_post( $args[0] );
			$post_type = get_post_type_object( $post->post_type );

			// Set an empty array for the caps.
			$caps = array();
		}

		if ( 'edit_published_' . $fp_post_type === $cap ) {
			if ( (int) $user_id === (int) $post->post_author ) {
				$caps[] = $post_type->cap->edit_posts;
			} else {
				$caps[] = $post_type->cap->edit_others_posts;
			}
		}

		// If editing a story, assign the required capability.
		if ( 'edit_' . $fp_post_type === $cap ) {
			// Contriburors can only edit non pending and non published post types.
			if ( ( 'contributer' !== $user_type || ! in_array( $post->post_status, array( 'pending', 'publish' ), true ) ) && (int) $user_id === (int) $post->post_author ) {
				$caps[] = $post_type->cap->edit_posts;
			} elseif ( isset( $post ) && 'publish' === $post->post_status ) {
				$caps[] = $post_type->cap->edit_published_posts;
			} else {
				$caps[] = $post_type->cap->edit_others_posts;
			}
		}

		// If deleting a story, assign the required capability. */ elseif ('delete_' . $fp_post_type == $cap).
		if ( (int) $user_id === (int) $post->post_author ) {
			$caps[] = $post_type->cap->delete_posts;
		} else {
			$caps[] = $post_type->cap->delete_others_posts;
		}

		// If reading a private story, assign the required capability. */ elseif ('read_' . $fp_post_type == $cap).

		if ( 'private' !== $post->post_status ) {
			$caps[] = 'read';
		} elseif ( (int) $user_id === (int) $post->post_author ) {
			$caps[] = 'read';
		} else {
			$caps[] = $post_type->cap->read_private_posts;
		}

		// Return the capabilities required by the user.
	}

	return $caps;
}
