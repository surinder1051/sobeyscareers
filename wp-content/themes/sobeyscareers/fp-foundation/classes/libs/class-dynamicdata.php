<?php
/**
 * Dynamic Data
 *
 * @package fp-foundation
 */

namespace fp;

use Exception;
use FLBuilder;
use FLBuilderModel;
use WP_Query;
use WP_REST_Server;
use FLBuilderModule;

/**
 * Runs processes on the dynamic_data_feed_parameters in BB components, if enabled.
 */
class DynamicData extends Component {
	/**
	 * Stores the BB module config options.
	 *
	 * @access private
	 * @var $config
	 */
	private $config;

	/**
	 * Setup the class properties, and load the custom Beaver Builder settings fields.
	 *
	 * @param object $config are the parameters from the BB component.
	 *
	 * @return void
	 */
	public function __construct( $config ) {
		$this->config = $config;
		require_once 'field_post_select_ajax.php';
		require_once 'field_taxonomy_select_ajax.php';
		require_once 'field_checkbox.php';
	}

	/**
	 * Create the query tab in the bb component if dynamic_data_feed_parameters is set
	 *
	 * @throws Exception If dynamic_data_feed_parameters is not set correctly.
	 *
	 * @return void
	 */
	public function init_data_provider_tab() {
		if ( isset( $this->config->dynamic_data_feed_parameters ) && $this->config->dynamic_data_feed_parameters && class_exists( 'FLBuilder' ) ) {

			if ( ! isset( $this->config->dynamic_data_feed_parameters['posts_per_page_default'] ) ) {
				throw new Exception( 'Missing posts_per_page_default for component $this->config->component.' );
			}

			if ( ! isset( $this->config->dynamic_data_feed_parameters['posts_per_page_options'] ) ) {
				throw new Exception( 'Missing posts_per_page_options for component $this->config->component.' );
			}

			$fields_general = array();

			$fields_general['posts_per_page'] = array(
				'type'    => 'select',
				'label'   => __( 'Number of Posts', 'fp' ),
				'default' => $this->config->dynamic_data_feed_parameters['posts_per_page_default'],
				'options' => $this->config->dynamic_data_feed_parameters['posts_per_page_options'],
				'toggle'  => array(),
			);

			if ( isset( $this->config->dynamic_data_feed_parameters['pagination_api'] ) ) {
				$fields_general['pagination'] = array(
					'type'    => 'select',
					'label'   => __( 'Pagination', 'fp' ),
					'default' => 1,
					'options' => array(
						1 => 'On',
						0 => 'Off',
					),
					'toggle'  => array(),
				);
			}

			// Find the highest position avialable.

			if ( isset( $this->config->dynamic_data_feed_parameters['max_overwrites'] ) ) {
				$highest_per_page_value = $this->config->dynamic_data_feed_parameters['max_overwrites'];
			} else {
				$highest_per_page_value = 0;
				foreach ( $this->config->dynamic_data_feed_parameters['posts_per_page_options'] as $per_page_value => $data ) {
					if ( $highest_per_page_value < $per_page_value ) {
						$highest_per_page_value = $per_page_value;
					}
				}
			}

			// Based on selection of number of posts hide all unnecessary hidden position overwrites.

			$positions       = array();
			$position_fields = array();
			for ( $i = 1; $i <= $highest_per_page_value; $i++ ) {
				$positions[] = "position_{$i}_overwrite";

				$position_fields[ "position_{$i}_overwrite" ] = array(
					'type'           => 'fp-post-select-dropdown',
					'post_type'      => $this->config->dynamic_data_feed_parameters['post_types'],
					'show_post_type' => true,
					// Translators: %d is the position number based on the loop.
					'label'          => sprintf( __( 'Position %d', 'fp' ), $i ),
				);
			}

			// Create a post types dropdown list. Options avaliable are set in the compoent, and given as a dropdown list, here.
			if ( isset( $this->config->dynamic_data_feed_parameters['post_types'] ) && count( $this->config->dynamic_data_feed_parameters['post_types'] ) > 0 ) {
				$fields['post_types'] = array(
					'type'           => 'fp-posttype-select-dropdown',
					'post_types'     => $this->config->dynamic_data_feed_parameters['post_types'],
					'default'        => $this->config->dynamic_data_feed_parameters['post_types'][0],
					'label'          => __( 'Post Types', 'fp' ),
					'description'    => __( 'You can select multiple post types.', 'fp' ),
					'multi-select'   => true,
					'select-current' => true,
				);
			}

			// If taxonomies are enabled, create a taxonomy filter for the query.
			if ( isset( $this->config->dynamic_data_feed_parameters['taxonomies'] ) ) {
				$fields['taxonomy_operator'] = array(
					'type'    => 'select',
					'label'   => __( 'Filters', 'fp' ),
					'default' => 'OR',
					'options' => array(
						'OR'  => __( 'Meet ANY taxonomies', 'fp' ),
						'AND' => __( 'Meet ALL taxonomies', 'fp' ),
					),
				);

				// How taxonomy filters are applied to the query.
				$fields['term_operator'] = array(
					'type'    => 'select',
					// 'label' => __('Filters', 'fp),
					'default' => 'AND',
					'options' => array(
						'IN'  => __( 'Meet ANY term', 'fp' ),
						'AND' => __( 'Meet EVERY term', 'fp' ),
					),
				);

				foreach ( $this->config->dynamic_data_feed_parameters['taxonomies'] as $taxonomies ) {
					global $post;
					foreach ( $taxonomies as $taxonomy_name => $taxonomy_data ) {
						$fields[ "taxonomy_{$taxonomy_name}" ] = array(
							'type'              => 'fp-taxonomy-select-dropdown',
							'label'             => ucfirst( $taxonomy_name ),
							'description'       => __( 'You can select multiple tags.', 'fp' ),
							'select-current'    => true,
							'multi-select'      => true,
							'taxonomy'          => $taxonomy_name,
							'preselect_default' => true,
							'default'           => null,
						);
					}
				}
			}

			$this->config->fields[ $this->config->component . '_query' ] = array(
				'title'    => __( 'Query', 'fp' ),
				'sections' => array(
					'general'           => array(
						'title'  => __( 'General', 'fp' ),
						'fields' => $fields_general,
					),
					'dynamic_content'   => array(
						'title'  => __( 'Dynamic Content', 'fp' ),
						'fields' => $fields,
					),
					'static_overwrites' => array(
						'title'  => __( 'Static Overwrites [ Optional ]', 'fp' ),
						'fields' => $position_fields,
					),
				),
			);
		}
	}

	/**
	 * Generate taxonomy array for WP_Query
	 *
	 * @access private
	 *
	 * @param string $value is the term to search selected from the BB taxonomy option.
	 * @param string $taxonomy is the selected taxonomy.
	 * @param array  $atts are the fields submitted via BB. Contains term_operator.
	 *
	 * @return array taxonomy query params.
	 */
	private function setup_tax_query( $value, $taxonomy, $atts ) {
		global $post;

		if ( ! taxonomy_exists( $taxonomy ) ) {
			return;
		}

		if ( ! empty( $value ) && 'all' == $value[0] ) { //phpcs:ignore
			$value = get_terms(
				array(
					'taxonomy' => $taxonomy,
					'fields'   => 'ids',
				)
			);
		} elseif ( empty( $value ) ) {
			$value = wp_get_post_terms(
				$post->ID,
				$taxonomy,
				array(
					'fields' => 'ids',
				)
			);
		}

		if ( ! $value ) {
			return;
		}

		return array(
			'taxonomy' => $taxonomy,
			'field'    => 'term_id',
			'terms'    => $value,
			'operator' => $atts['term_operator'],
		);
	}

	/**
	 * Create dynamic query based on user query parameters to load dynamic data for the component. Sends back $atts['posts'] with pre-populated data.
	 *
	 * @param array $atts are the BB saved settings for a component.
	 *
	 * @return array $atts
	 */
	public function pre_load_dynamic_data( $atts ) {

		$tax_query = array();

		if ( ! isset( $this->config->dynamic_data_feed_parameters ) || count( $this->config->dynamic_data_feed_parameters ) === 0 ) {
			// Bail out if no feed parameters exist.
			return $atts;
		}

		if ( isset( $atts['id'] ) && ! empty( $atts['id'] ) ) {
			// If atts id is populated then module is being feed a id to render so we should skip pre-pop of dynamic data for that module.
			return $atts;
		}

		if ( isset( $this->config->dynamic_data_feed_parameters['taxonomies'] ) && count( $this->config->dynamic_data_feed_parameters['taxonomies'] ) > 0 ) {
			foreach ( $this->config->dynamic_data_feed_parameters['taxonomies'] as $taxonomy ) {
				foreach ( $taxonomy as $taxonomy_name => $taxonomy_data ) {
					if ( ! taxonomy_exists( $taxonomy_name ) ) {
						continue;
					}
					if ( isset( $atts[ "taxonomy_$taxonomy_name" ] ) && $atts[ "taxonomy_$taxonomy_name" ] && $atts[ "taxonomy_$taxonomy_name" ][0] !== '') { //phpcs:ignore
						$tax_query[] = $this->setup_tax_query( $atts[ "taxonomy_$taxonomy_name" ], $taxonomy_name, $atts );
					}
				}
			}
		}

		$tax_query['relation'] = ( isset( $atts['taxonomy_operator'] ) ) ? $atts['taxonomy_operator'] : 'OR';
		$tax_query             = array_filter( $tax_query );

		if ( ! empty( $atts['post_types'][0] ) && 'all' == $atts['post_types'][0] ) { //phpcs:ignore
			// Check module post type parameter.
			if ( ! empty( $this->config->dynamic_data_feed_parameters['post_types'] ) ) {
				$atts['post_types'] = $this->config->dynamic_data_feed_parameters['post_types'];
			} else {
				// Load all post types.
				$temp = get_post_types( false, 'names' );
				foreach ( $temp as $t ) {
					$atts['post_types'][] = $t;
				}
			}
		} elseif ( empty( $atts['post_types'][0] ) || '' == $atts['post_types'][0] ) { //phpcs:ignore
			// Load this post_types post_type as default.
			$atts['post_types'] = get_post_type();
		}

		global $post;

		$args = array(
			'calling_component'    => $this->config->component,
			'paged'                => 1,
			'post_status'          => 'publish',
			'post_type'            => $atts['post_types'],
			'posts_per_page'       => $atts['posts_per_page'],
			'ORIGINAL_REQUEST_URI' => $_SERVER['REQUEST_URI'], //phpcs:ignore
			'tax_query'            => $tax_query, //phpcs:ignore
			'order'                => ( isset( $this->config->dynamic_data_feed_parameters['order'] ) ) ? $this->config->dynamic_data_feed_parameters['order'] : 'DESC',
			'orderby'              => ( isset( $this->config->dynamic_data_feed_parameters['orderby'] ) ) ? $this->config->dynamic_data_feed_parameters['orderby'] : 'post_date',
		);

		$atts['posts'] = array();
		$atts          = $this->pre_load_dynamic_featured_data( $atts, $args );

		$posts = $this->pre_load_overwrite_data( $atts );

		$args['post__not_in'] = array();
		if ( is_array( $posts ) ) {
			$args['post__not_in'] = array_column( $posts, 'ID' );
		}

		$original_posts_per_page = $args['posts_per_page'];
		$args['posts_per_page']  = (int) $args['posts_per_page'] - count( $posts );

		if ( isset( $atts['featured'] ) ) {
			$args['post__not_in'][] = $atts['featured']->ID;
		}

		if ( isset( $post->ID ) ) {
			// Exclude current page from dynamically pulled in content.
			$args['post__not_in'][] = $post->ID;
		}
		$results = new WP_Query( $args );

		if ( isset( $post->ID ) ) {
			// Remove after query run to keep counts accurate.
			$key = array_search( $post->ID, $args['post__not_in'] ); //phpcs:ignore
			if ( false !== $key ) {
				unset( $args['post__not_in'][ $key ] );
			}
		}
		$assign_counter = 0;

		// Reset for pagination.
		$args['posts_per_page'] = $original_posts_per_page;

		for ( $i = 0; $i < $original_posts_per_page; $i++ ) {
			if ( ! isset( $posts[ $i ] ) && isset( $results->posts[ $assign_counter ] ) ) {
				$posts[ $i ] = $results->posts[ $assign_counter ];
				$assign_counter++;
			}
		}

		for ( $i = 0; $i < $original_posts_per_page; $i++ ) {
			if ( ! empty( $this->config->dynamic_data_feed_parameters['thumbnails'] ) && is_array( $this->config->dynamic_data_feed_parameters['thumbnails'] ) ) { //phpcs:ignore
				if ( isset( $posts[ $i ]->ID ) ) {
					$posts[ $i ]->thumbnails = array();
					foreach ( $this->config->dynamic_data_feed_parameters['thumbnails'] as $thumbnail_size ) {
						$posts[ $i ]->thumbnails[ $thumbnail_size ] = get_the_post_thumbnail_url( $posts[ $i ]->ID, $thumbnail_size );
					}
				}
			}
		}

		ksort( $posts );

		if ( isset( $atts['featured'] ) ) {
			array_unshift( $posts, $atts['featured'] );
		}

		$atts['posts']                     = $posts;
		$args['main_query_first_page_ids'] = array_column( $atts['posts'], 'ID' );
		$atts['main_query']                = $args;
		$atts['main_query_found_posts']    = $results->found_posts + count( $args['post__not_in'] );

		if ( isset( $post->ID ) ) {
			// Exclude current page from dynamically pulled in content.
			$args['post__not_in'][] = $post->ID;
		}

		$atts['main_query_max_num_pages'] = $results->max_num_pages;
		$atts['main_query_paged']         = $results->query['paged'];
		$atts['main_query_post_count']    = $results->post_count;
		$atts['main_query_encrypted']     = $this->encrypt( json_encode( $atts['main_query'] ), NONCE_SALT ); //phpcs:ignore

		if ( isset( $this->config->dynamic_data_feed_parameters['fetch_taxonomies'] ) && ! empty( $this->config->dynamic_data_feed_parameters['taxonomies'] ) ) {
			if ( $posts ) {

				$post_terms = array();
				foreach ( $posts as $key => $p ) {

					foreach ( $this->config->dynamic_data_feed_parameters['taxonomies'] as $tax => $data ) {

						$taxonomy = false;
						foreach ( $data as $key => $discard ) {
							if ( $taxonomy ) {
								break;
							}
							$taxonomy = $key;
						}
						$the_terms = \get_the_terms( $p->ID, $taxonomy );

						if ( ! \is_wp_error( $the_terms ) && isset( $the_terms[0]->term_id ) ) {
							if ( ! isset( $p->terms ) ) {
								$p->terms = array();
							}
							foreach ( $the_terms as $term ) {
								$post_terms[ $taxonomy ][ $term->term_id ][] = $p->ID;

								$p->terms[] = array(
									'name'     => $term->name,
									'term_id'  => $term->term_id,
									'link'     => \get_term_link( $term, $taxonomy ),
									'taxonomy' => $taxonomy,
								);
							}
							$atts['terms'] = $post_terms;
						}
					}
				}
			}
		}

		return $atts;
	}

	/**
	 * Pre load dynamic featured content, this needs meta_value of featured = 1 to be picked up
	 *
	 * @access private
	 *
	 * @param array $atts are the BB saved settings.
	 * @param array $args are the wp_query args.
	 *
	 * @return array $atts
	 */
	private function pre_load_dynamic_featured_data( $atts, $args ) {
		// Get featured top story based on primary query.
		if ( isset( $atts['show_featured'] ) && 'Yes' === $atts['show_featured'] ) {
			$args['posts_per_page'] = 1;
			$args['meta_query']     = array( //phpcs:ignore
				array(
					'key'   => 'featured',
					'value' => 1,
				),
			);
			$featured_query         = new WP_Query( $args );
			if ( count( $featured_query->posts ) > 0 ) {
				$atts['found_featured'] = true;
				$atts['featured']       = $featured_query->posts[0];
				$atts['posts']          = array_merge( $atts['posts'], $featured_query->posts );
			}
		}
		return $atts;
	}


	/**
	 * Load and overwrite specific content positions as selected by the user.
	 *
	 * @access private
	 *
	 * @param array $atts are the BB component saved settings.
	 *
	 * @return array $results.
	 */
	private function pre_load_overwrite_data( $atts ) {
		$results = array();
		if ( $atts['posts_per_page'] ) {
			$overwrite_ids                = array();
			$posts_per_page               = intval( $atts['posts_per_page'] );
			$results                      = array();
			$atts['number_of_overwrites'] = 0;
			for ( $i = 1; $i <= $posts_per_page; $i++ ) {
				if ( isset( $atts[ "position_{$i}_overwrite" ] ) && ! empty( $atts[ "position_{$i}_overwrite" ] ) ) {
					$overwrite_id                         = intval( $atts[ "position_{$i}_overwrite" ] );
					$overwrite_positions[ $overwrite_id ] = $i - 1;
					$overwrite_ids[]                      = $overwrite_id;
				}
			}
			if ( count( $overwrite_ids ) > 0 ) {
				$overwrites = new WP_Query(
					array(
						'post_type'      => 'any',
						'post__in'       => $overwrite_ids,
						'posts_per_page' => count( $overwrite_ids ),
					)
				);

				$atts['number_of_overwrites'] = count( $overwrites->posts );

				foreach ( $overwrites->posts as $post ) {
					// Run a check in case WP returns an extra, non-selected post.
					if ( in_array( $post->ID, $overwrite_ids ) ) { //phpcs:ignore
						$overwrite_position             = $overwrite_positions[ $post->ID ];
						$results[ $overwrite_position ] = $post;
					}
				}
			}
		}
		return $results;
	}

	/**
	 * Register pagination Rest API if the module requires it.
	 *
	 * @return void
	 */
	public function register_api_load_dynamic_paginated_data() {
		if ( isset( $this->config->dynamic_data_feed_parameters['pagination_api'] ) && $this->config->dynamic_data_feed_parameters['pagination_api'] ) {
			register_rest_route(
				'fp/v1',
				'/pagination',
				array(
					'methods'             => WP_REST_Server::READABLE,
					'permission_callback' => '__return_true',
					'callback'            => array( $this, 'api_load_dynamic_paginated_data' ),
					'args'                => array(
						'q'      => array(
							'type' => 'string',
						),
						's'      => array(
							'type' => 'string',
						),
						'page'   => array(
							'type' => 'int',
						),
						'sort'   => array(
							'type' => 'string',
						),
						'filter' => array(
							'type' => 'string',
						),
					),
				)
			);
		}
	}

	/**
	 * Encrypt the posts query used for custom pagination.
	 *
	 * @param string $string is the content to encrypt.
	 * @param string $key is the key to the encryption.
	 *
	 * @return string encoded
	 */
	public function encrypt( $string, $key ) {
		$result     = '';
		$str_length = strlen( $string );
		for ( $i = 0; $i < $str_length; $i++ ) {
			$char    = substr( $string, $i, 1 );
			$keychar = substr( $key, ( $i % strlen( $key ) ) - 1, 1 );
			$char    = chr( ord( $char ) + ord( $keychar ) );
			$result .= $char;
		}

		return base64_encode( $result ); //phpcs:ignore
	}

	/**
	 * Decrypt a string based on its key. Used in custom pagination.
	 *
	 * @param string $string is the string to decrypt.
	 * @param string $key is the key to removing the decryption.
	 *
	 * @return string $result decoded.
	 */
	public function decrypt( $string, $key ) {
		$result     = '';
		$string     = base64_decode( $string ); //phpcs:ignore
		$str_length = strlen( $string );

		for ( $i = 0; $i < $str_length; $i++ ) {
			$char    = substr( $string, $i, 1 );
			$keychar = substr( $key, ( $i % strlen( $key ) ) - 1, 1 );
			$char    = chr( ord( $char ) - ord( $keychar ) );
			$result .= $char;
		}

		return $result;
	}

	/**
	 * Handle the pagination API request.
	 *
	 * @param array $request is the JS request data.
	 *
	 * @return array rest_response
	 */
	public function api_load_dynamic_paginated_data( $request ) {
		$results = array();

		$paged = $request['paged'];
		$query = $request['q'];
		$node  = $request['node'];
		$query = (array) json_decode( $this->decrypt( $query, NONCE_SALT ), true );

		if ( isset( $query['ORIGINAL_REQUEST_URI'] ) ) {
			$_SERVER['REQUEST_URI'] = $query['ORIGINAL_REQUEST_URI'];
		}

		if ( isset( $query['calling_component'] ) ) {
			$component_name = $query['calling_component'];
			$module_class   = 'fp\components\\' . $component_name;
			$component      = new $module_class( true );
		}

		// Skip all stories on first page and start fresh new paginated content.
		$query['post__not_in'] = $query['main_query_first_page_ids'];
		$query['paged']        = $paged;

		$results = new WP_Query( $query );

		if ( $results->found_posts ) {
			$posts      = array();
			$post_terms = array();
			foreach ( $results->posts as $key => $post ) {

				if ( isset( $component ) ) {
					// Return thumbnail urls for specific thumbnail sizes.
					if ( ! empty( $component->dynamic_data_feed_parameters['thumbnails'] ) && is_array( $component->dynamic_data_feed_parameters['thumbnails'] ) ) {
						$post->thumbnails = array();
						foreach ( $component->dynamic_data_feed_parameters['thumbnails'] as $thumbnail_size ) {
							$posts[ $key ]['thumbnails'][ $thumbnail_size ] = get_the_post_thumbnail_url( $post->ID, $thumbnail_size );
						}
					}

					// Return terms if part of the dynamic feed parameters.
					if ( isset( $component->dynamic_data_feed_parameters['fetch_taxonomies'] ) && ! empty( $component->dynamic_data_feed_parameters['taxonomies'] ) ) {
						foreach ( $component->dynamic_data_feed_parameters['taxonomies'][0] as $taxonomy => $data ) {
							$the_terms = \get_the_terms( $post->ID, $taxonomy );
							if ( ! \is_wp_error( $the_terms ) && isset( $the_terms[0]->term_id ) ) {
								foreach ( $the_terms as $term ) {
									$posts[ $key ]['terms'][] = array(
										'term_name' => $term->name,
										'term_link' => \get_term_link( $term->term_id, $taxonomy ),
										'taxonomy'  => $taxonomy,
									);

									$post_terms[ $taxonomy ][ $term->slug ][] = $post->ID;
								}
							}
						}
					}
				}

				$posts[ $key ]['title']     = get_the_title( $post->ID );
				$posts[ $key ]['permalink'] = apply_filters( 'the_permalink', get_permalink( $post->ID ), $post );
				$posts[ $key ]              = apply_filters( 'fp_process_dynamic_paginated_post', $posts[ $key ], $post, $request );

			}
		}

		$paged++;

		$return['post_terms']     = ( isset( $post_terms ) ) ? $post_terms : array();
		$return['paged']          = $paged;
		$return['posts']          = ( isset( $posts ) ) ? $posts : array();
		$return['node']           = $node;
		$return['found_posts']    = $results->found_posts;
		$return['posts_per_page'] = $results->query_vars['posts_per_page'];

		$return['$results'] = $results;

		if ( $paged > $results->max_num_pages ) {
			$return['paged'] = 'hide';
		}

		return rest_ensure_response( $return );
	}
}


if ( ! function_exists( 'bb_field_taxonomy_select_dropdown' ) ) {
	/**
	 * Custom taxonomy term selection dropdown for Beaver Builder taxonomy select
	 *
	 * @param string $name is the select field name.
	 * @param string $value is the select field value.
	 * @param array  $field are the field properties.
	 *
	 * @return void
	 */
	function bb_field_taxonomy_select_dropdown( $name, $value, $field ) {

		global $post;

		if ( isset( $field['multi-select'] ) ) {
			$multiple = $field['multi-select'] ? ' multiple ' : '';
		}

		if ( isset( $field['select-current'] ) && ( true == $field['select-current'] ) ) { //phpcs:ignore
			if ( ! $value ) {
				$value = wp_get_post_terms( $post->ID, $field['taxonomy'], array( 'fields' => 'ids' ) );
			}
		}

		if ( is_string( $field['taxonomy'] ) && ! taxonomy_exists( $field['taxonomy'] ) ) {
			return;
		}

		if ( is_array( $field['taxonomy'] ) && ! taxonomy_exists( $field['taxonomy'][0] ) ) {
			return;
		}

		$terms = get_terms( $field['taxonomy'], array( 'hide_empty' => 0 ) );

		if ( is_array( $terms ) && count( $terms ) > 1 ) {
			echo "<select name='{$name}[]' $multiple>"; //phpcs:ignore
		} else {
			if ( ! isset( $field['none-option'] ) || (true == $field['none-option'] ) ) { //phpcs:ignore
				echo "<select disabled name='{$name}'>"; //phpcs:ignore
			}
		}

		if ( ! $terms ) {
			echo "<option value=''>No {$field['label']} to select.</option>"; //phpcs:ignore
		} else {
			if ( $value || $terms ) {
				if ( ! isset( $field['all-option'] ) || ( true == $field['all-option'] ) ) { //phpcs:ignore
					if ( isset( $value[0] ) && 'all' == $value[0] ) { //phpcs:ignore
						echo "<option selected value='all'>" . esc_attr__( 'All', 'fp' ). "</option>"; //phpcs:ignore
					} else {
						echo "<option value='all'>" . esc_attr__( 'All', 'fp' ). "</option>"; //phpcs:ignore
					}
				}
			}
		}

		if ( is_array( $terms ) && count( $terms ) > 0 ) {
			foreach ( $terms as $term ) {
				if ( $value ) {
					$selected = in_array( $term->term_id, $value ) ? ' selected ' : ''; //phpcs:ignore
				} elseif ( empty( $value ) && ! empty( $field['preselect_default'] ) && $field['preselect_default'] ) {
					$post_default_terms = wp_get_post_terms( $post->ID, $field['taxonomy'], array( 'fields' => 'ids' ) );
					$selected           = in_array( $term->term_id, $post_default_terms ) ? ' selected ' : ''; //phpcs:ignore
				} else {
					$selected = '';
				}
				echo "<option $selected value='$term->term_id'>$term->name</option>"; //phpcs:ignore
			}

			if ( ! isset( $field['none-option'] ) || ( true == $field['none-option'] ) ) { //phpcs:ignore
				echo "<option value=''>None</option>";
			}
		}

		echo '</select>';
	}

	add_action( 'fl_builder_control_fp-taxonomy-select-dropdown', 'fp\bb_field_taxonomy_select_dropdown', 1, 3 );
}

if ( ! function_exists( 'bb_field_posttype_select_dropdown' ) ) {
	/**
	 * Custom post type selection dropdown
	 *
	 * @param string $name is the select field name.
	 * @param string $value is the select field value.
	 * @param array  $field are the field properties.
	 *
	 * @return void
	 */
	function bb_field_posttype_select_dropdown( $name, $value, $field ) {

		if ( isset( $field['multi-select'] ) ) {
			$multiple = $field['multi-select'] ? ' multiple ' : '';
		}

		if ( isset( $field['select-current'] ) ) {
			if ( ! $value ) {
				$value[0] = get_post_type();
			}
		}

		$registered_post_types = get_post_types( false, 'objects' );

		// Filter allowed post types for the field.
		$allowed_post_types = array();

		foreach ( $field['post_types'] as $post_type ) {
			$allowed_post_types[] = $post_type;
		}

		// [] needs to be ommited if there is only one choice.
		if ( count( $allowed_post_types ) > 1 ) {
			echo "<select name='{$name}[]' $multiple>"; //phpcs:ignore
			if ( 'all' == $value[0] || ! $value ) { //phpcs:ignore
				echo "<option selected value='all'>" . esc_attr__( 'All', 'fp' ). "</option>"; //phpcs:ignore
			} else {
				echo "<option value='all'>" . esc_attr__( 'All', 'fp' ). "</option>"; //phpcs:ignore
			}
		} else {
			echo "<select selected name='{$name}'>"; //phpcs:ignore
		}

		foreach ( $registered_post_types as $p ) {
			if ( in_array( $p->name, $allowed_post_types ) || in_array( 'any', $field['post_types'] ) ) { //phpcs:ignore
				if ( count( $allowed_post_types ) === 1 ) {
					$selected = ' selected ';
				} else {
					$selected = in_array( $p->name, (array) $value ) ? ' selected ' : ''; //phpcs:ignore
				}
				echo "<option $selected value='$p->name'>$p->label</option>"; //phpcs:ignore
			}
		}

		echo '</select>';
	}

	add_action( 'fl_builder_control_fp-posttype-select-dropdown', 'fp\bb_field_posttype_select_dropdown', 1, 3 );
}
