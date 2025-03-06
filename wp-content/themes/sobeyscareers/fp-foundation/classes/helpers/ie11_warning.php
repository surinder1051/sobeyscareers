<?php
/**
 * Enable IE11 warning pop-up
 *
 * LOAD_IE11_WARNING constant must be defined in theme functions.php file.
 * Bootstrap modal sass must be imported in theme /assets/scss/includes/_bootstrap.scss file.
 *
 * @package fp-foundation
 */

add_action(
	'wp_enqueue_scripts',
	function () {
		$fp_foundation_assets = get_template_directory_uri() . '/fp-foundation/assets/';

		// Check if JS file constant is defined and manually enqueue JS if not.

		wp_enqueue_script( 'cookie-js', 'https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js', array( 'jquery' ), FP_FOUNDATION_VERSION, false );

		if ( ! defined( 'LOAD_JS_IE11_WARNING.JS' ) ) {
			wp_enqueue_script( 'LOAD_JS_IE11_WARNING.JS', $fp_foundation_assets . 'js/ie11_warning.js', array( 'jquery', 'bootstrap-modal-js', 'cookie-js' ), FP_FOUNDATION_VERSION, false );
		}

		wp_localize_script(
			'LOAD_JS_IE11_WARNING.JS',
			'ie11_warning',
			array(
				'title'         => __( 'Improve Your Experience!', 'fp-foundation' ),
				'body'          => __( 'We noticed that you are using an unsupported version of Internet Explorer which may cause some issues while using our website. Please upgrade your browser version or use a different browser.', 'fp-foundation' ),
				'close_aria'    => __( 'Close', 'fp-foundation' ),
				'download_text' => __( 'Download', 'fp-foundation' ),
				'icon_path'     => $fp_foundation_assets . 'icons/',
				'browsers'      => array(
					'chrome'  => array(
						'name'          => __( 'Google Chrome', 'fp-foundation' ),
						'download_link' => 'https://www.google.com/intl/en_ca/chrome/',
					),
					'firefox' => array(
						'name'          => __( 'Firefox', 'fp-foundation' ),
						'download_link' => 'https://www.mozilla.org/en-CA/firefox/new/',
					),
					'safari'  => array(
						'name'          => __( 'Safari (Mac only)', 'fp-foundation' ),
						'download_link' => 'https://support.apple.com/en_CA/downloads/safari',
					),
					'edge'    => array(
						'name'          => __( 'Microsoft Edge (PC only)', 'fp-foundation' ),
						'download_link' => 'https://www.microsoft.com/en-us/edge',
					),
				),
			)
		);
	}
);
