<?php
/**
 * Setup Select Taxonomies via REST for BB query options.
 *
 * @package fp-foundation
 */

namespace fp;

use WP_Error;

if ( ! class_exists( 'Setup_Select_Taxonomy_Rest_Response' ) ) {

	/**
	 * Setup the REST API endpoint to autopopulate taxonomy select lists in the Query tab of BB settings.
	 */
	class Setup_Select_Taxonomy_Rest_Response {

		/**
		 * Call the rest api route hook
		 *
		 * @see self::register_routes()
		 */
		public function __construct() {
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		}

		/**
		 * Function to register the route.
		 *
		 * @see self::get_results()
		 *
		 * @return void
		 */
		public function register_routes() {
			register_rest_route(
				'wp/v1',
				'/get_select_field_taxonomies',
				array(
					array(
						'methods'             => \WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_results' ),
						'permission_callback' => ( is_user_logged_in() ? '__return_true' : '__return_false' ),
					),
				)
			);
		}

		/**
		 * Get a list of registered taxonomies and return it.
		 *
		 * @param array $request_data is the data sent through the ajax request.
		 *
		 * @return array $data
		 */
		public function get_results( $request_data ) {

			$data = get_taxonomies();

			if ( empty( $data ) ) {
				return new WP_Error( 'no_results', 'No results found', array( 'status' => 404 ) );
			}

			return $data;
		}

	}

	new Setup_Select_Taxonomy_Rest_Response();
}
