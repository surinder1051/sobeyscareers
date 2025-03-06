<?php
/**
 * BB Field Checkbox
 *
 * @package fp-foundation
 */

if ( ! class_exists( 'Setup_Select_Posts_Rest_Response' ) ) {
	/**
	 * Rest route to fetch post listing using parameters for BB post select dropdown field.
	 */
	class Setup_Select_Posts_Rest_Response {

		/**
		 * Setup the rest API and js hooks
		 *
		 * @return void
		 */
		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		/**
		 * Enqueue the rest nonce and url to the js api objects.
		 *
		 * @return void
		 */
		public function enqueue_scripts() {
			wp_localize_script(
				'wp-api',
				'wpApiSettings',
				array(
					'root'  => esc_url_raw( rest_url() ),
					'nonce' => wp_create_nonce( 'wp_rest' ),
				)
			);
		}

		/**
		 * Register the rest routes
		 *
		 * @see self::get_results()
		 * @see self::permissions_check()
		 *
		 * @return void
		 */
		public function register_routes() {
			register_rest_route(
				'wp/v1',
				'/get_select_field_posts',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_results' ),
						'permission_callback' => array( $this, 'permissions_check' ),
						'args'                => array(
							'post_type'      => array(
								'default' => array( 'post' ),
							),
							'posts_per_page' => array(
								'default'           => 1,
								'sanitize_callback' => 'absint',
							),
							'orderby'        => array(
								'default'           => 'name',
								'sanitize_callback' => 'sanitize_title',
							),
							'order'          => array(
								'default'           => 'asc',
								'sanitize_callback' => 'sanitize_title',
							),
							'show_post_type' => array(
								'default' => false,
							),
						),
					),
				)
			);
		}

		/**
		 * Get the posts from the requested query
		 *
		 * @param array $request_data are the headers sent from the ajax request.
		 *
		 * @return array|WP_Error
		 */
		public function get_results( $request_data ) {

			$parameters = $request_data->get_params();
			$results    = new WP_Query( $parameters );
			$data       = array();

			if ( $results->found_posts ) {
				while ( $results->have_posts() ) {
					$results->the_post();
					$title = get_the_title();
					$id    = get_the_ID();
					if ( strlen( $title ) > 60 ) {
						$title = substr( $title, 0, 60 ) . ' ... ';
					}
					if ( $parameters['show_post_type'] == true ) { //phpcs:ignore
						$title = ucfirst( get_post_type() ) . ' - ' . $title;
					}
					$data[] = array(
						'id'    => $id,
						'title' => $title,
					);
				}
				wp_reset_postdata();
			}

			if ( empty( $data ) ) {
				return new WP_Error( 'no_results', 'No results found', array( 'status' => 404 ) );
			}

			return $data;
		}

		/**
		 * REST Api permissions check. Return logged in status
		 *
		 * @return bool
		 */
		public function permissions_check() {
			return is_user_logged_in();
		}
	}

	new Setup_Select_Posts_Rest_Response();
}
