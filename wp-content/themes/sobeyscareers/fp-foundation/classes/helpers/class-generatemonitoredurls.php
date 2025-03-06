<?php
/**
 * Generate Monitored URLs
 *
 * @package fp-foundation
 * @since 1.8.71
 */

namespace fp;

use WP_Query;

if ( ! class_exists( 'GenerateMonitoredUrls' ) ) {

	/**
	 * Add customized urls to the global array
	 * Access by adding ?generate_monitored_urls to the site URL.
	 */
	class GenerateMonitoredUrls {

		/**
		 * Create a hook to generate a list of URLs to monitor in webops.
		 *
		 * @see self::init()
		 */
		public function __construct() {
			add_action( 'generate_monitored_urls', array( $this, 'init' ), 10, 2 );
		}

		/**
		 * Add a selection of URLs by post type and language (if polylang) to the URLs array.
		 * This is used for testing/web ops.
		 *
		 * @param bool        $return (Optional) Default: false. If true, return the urls array.
		 * @param null|string $domain (Optional) Default:  null.
		 *
		 * @see self::add_url()
		 *
		 * @return void|array
		 */
		public function init( $return = false, $domain = null ) {
			global $urls;
			// Empty the global array and re-populate it with custom urls.
			$urls = array(); //phpcs:ignore

			$post_types = FP_POST_TYPES;

			// If polylang, add the english and french language home pages.
			if ( function_exists( 'pll_is_translated_post_type' ) ) {
				$this->add_url( get_site_url() . '/en/', $domain );
				$this->add_url( get_site_url() . '/fr/', $domain );
			}

			// Archive pages by post type.
			foreach ( $post_types as $post_type => $data ) {
				if ( get_post_type_archive_link( $post_type ) ) {
					$this->add_url( get_post_type_archive_link( $post_type ), $domain );

					$results = new WP_Query(
						array(
							'post_type'      => $post_type,
							'posts_per_page' => 1,
							'post_status'    => 'publish',
							'orderby'        => 'ID',
						)
					);
					if ( count( $results->posts ) > 0 ) {
						$this->add_url( get_permalink( $results->posts[0]->ID ), $domain );
					}

					// If polylang, add the french language translation urls.
					if ( function_exists( 'pll_is_translated_post_type' ) && pll_is_translated_post_type( $post_type ) ) {
						$this->add_url( str_replace( '/en', '/fr', $post_type_archive_link ), $domain );
						$results = new WP_Query(
							array(
								'post_type'      => $post_type,
								'posts_per_page' => 1,
								'post_status'    => 'publish',
								'orderby'        => 'ID',
								'lang'           => 'fr',
							)
						);
						if ( count( $results->posts ) > 0 ) {
							$this->add_url( get_permalink( $results->posts[0]->ID ), $domain );
						}
					}
				}
			}
			// Single post urls.
			$query_args = array(
				'post_type'      => 'page',
				'posts_per_page' => -1,
				'post_status'    => 'publish',
				'orderby'        => 'ID',
			);
			if ( function_exists( 'pll_is_translated_post_type' ) ) {
				$query_args['lang'] = 'en';
			}

			$results = new WP_Query( $query_args );
			foreach ( $results->posts as $post ) {
				$this->add_url( get_permalink( $post->ID ), $domain );
			}

			// Translated urls.
			if ( function_exists( 'pll_is_translated_post_type' ) ) {
				$query_args['lang'] = 'fr';
				$results            = new WP_Query( $query_args );
				foreach ( $results->posts as $post ) {
					$this->add_url( get_permalink( $post->ID ), $domain );
				}
			}

			if ( $return ) {
				return $urls;
			}

			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				/**
				 * WP_CLI\Utils\format_items( 'table', $ur/ls, 'URL' );
				 */
				foreach ( $urls as $key => $value ) {
					WP_CLI::log( $value );
				}
			} else {
				header( 'Content-Type: application/json' );
				die( json_encode( $urls ) ); //phpcs:ignore
			}
		}
		/**
		 * Format the domain to force https
		 *
		 * @param string $domain is the site domain name.
		 * @param string $url is the page url.
		 *
		 * @return string $url
		 */
		public function format_domain( $domain, $url ) {
			$url = str_replace( get_site_url(), 'https://' . $domain, $url );
			return $url;
		}

		/**
		 * Add the queried URL to the globals array.
		 *
		 * @param string $url is the page URL.
		 * @param string $domain (optional) is the domain name to format.
		 *
		 * @return void
		 */
		public function add_url( $url, $domain = null ) {
			global $urls;

			if ( ! empty( $domain ) && null !== $domain ) {
				$url = $this->format_domain( $domain, $url );
			}

			if ( ! in_array( $url, $urls, true ) ) {
				$urls[] = $url; //phpcs:ignore
			}
		}
	}

	$generate_monitored_urls = new GenerateMonitoredUrls();

	if ( isset( $_GET['generate_monitored_urls'] ) ) { //phpcs:ignore
		add_action( 'init', array( $generate_monitored_urls, 'init' ) );
	}
}
