<?php
/**
 * Stylized Dropdowns using Selectric
 *
 * @package fp-foundation
 */

/**
 * Regiester and enqueue selectric js and css files.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_register_script( 'jq-selectric', 'https://cdn.jsdelivr.net/npm/selectric@1.13.0/public/jquery.selectric.min.js', array( 'jquery' ), FP_FOUNDATION_VERSION, false );
		wp_enqueue_script( 'jq-selectric' );

		wp_register_style( 'jq-selectric-css', 'https://cdn.jsdelivr.net/npm/selectric@1.13.0/public/themes/template/selectric.css', array(), FP_FOUNDATION_VERSION );
		wp_enqueue_style( 'jq-selectric-css' );
	}
);

if ( ! function_exists( 'init_selectric' ) ) {
	/**
	 * Add a custom inline script to apply selectric to <select > form elemennts generated by FacetWP.
	 */
	function init_selectric() {
		wp_add_inline_script(
			'jq-selectric',
			'(function($) {


				$(document).on(\'facetwp-loaded load\', function() {
					jQuery("#page select").selectric({
						arrowButtonMarkup: \'<span class="button">&#x25be;</span>\',
						onChange: function(element) {
							// Fix issue where styalized select wasn\'t trigering normal select dropdown change
							var value = $(element).val();
							FWP.extras.sort = value;
							FWP.soft_refresh = true;
							FWP.autoload();
						}
					})
				});



			})(jQuery);'
		);
	}
	add_action( 'wp_enqueue_scripts', 'init_selectric' );
}
