<?php
/**
 * WP Rest API functions
 *
 * @package fp-foundation
 */

/**
 * Remove the WP default users API endpoints.
 *
 * @param array $endpoints are all the registered REST API endpoints.
 *
 * @return array
 */
add_filter(
	'rest_endpoints',
	function( $endpoints ) {
		if ( isset( $endpoints['/wp/v2/users'] ) ) {
			unset( $endpoints['/wp/v2/users'] );
		}
		if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
			unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
		}
		return $endpoints;
	}
);
