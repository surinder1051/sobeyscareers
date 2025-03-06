<?php
/**
 * FacetWP Filters.
 *
 * @package fp-foundation
 */

/**
 * Update the facet pager to add aria attributes on the <a> tags.
 *
 * @param string $output is the facet generated html.
 * @param array  $params are the properties of the facet object being sent to the function.
 *
 * @return string
 */
add_filter(
	'facetwp_facet_html',
	function( $output, $params ) {
		if ( strstr( $params['facet']['type'], 'pager' ) !== false ) {

			preg_match_all( '/data\-page\=[\"\'](\d+)[\"\']/', $output, $matches );
			if ( ! empty( $matches[1] ) ) {
				foreach ( $matches[1] as $index => $data ) {
					$output = str_replace( $matches[0][ $index ], $matches[0][ $index ] . ' role="button" tabindex="0" aria-label="' . __( 'Go to page', 'fp-foundation' ) . ' ' . $data . '"', $output );
				}
			}
		}
		return $output;
	},
	10,
	2
);

/**
 * Update the facet pager wrapper to add some accessibility attributes.
 *
 * @param string $output is the facet generated html.
 * @param array  $atts are the properties of the facet object being sent to the function.
 *
 * @return string
 */
add_filter(
	'facetwp_shortcode_html',
	function( $output, $atts ) {

		if ( isset( $atts['facet'] ) && strstr( $atts['facet'], 'pager' ) !== false ) {
			$output = str_replace( 'data-type="pager"', 'data-type="pager" role="navigation"', $output );
		}
		return $output;
	},
	10,
	2
);
