<?php
/**
 * Action: Change the admin toolbar background colour.
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'my_styles_method' ) ) {
	/**
	 * Add custom styling of the toolbar to the header based on their environment (local, dev, staging, prod) when a user is logged in.
	 *
	 * @see fp_which_env()
	 *
	 * @return string|void
	 */
	function my_styles_method() {
		wp_enqueue_style( 'fp-admin-bg-color' );

		$color = '#000';
		$type  = fp_which_env();

		if ( empty( $type ) ) {
			return $type;
		}

		switch ( $type ) {
			case 'LOCAL':
				$color   = '#5b468e';
				$bgcolor = '#f1f1f1';
				break;
			case 'DEV':
				$color   = '#144578';
				$bgcolor = '#69a3f9';
				break;
			case 'STAGING':
				$color   = '#875123';
				$bgcolor = '#f9ab69';
				break;
			case 'PREVIEW':
				$color   = '#875123';
				$bgcolor = '#426739';
				break;
			default:
				return;
		}

		$style_tag = "<style>
			div#wpadminbar{background-color: $color}
			.wp-admin #wpadminbar #wp-admin-bar-site-name>.ab-item:before{
				content: '$type';
				font-family: unset;
				background-color: $bgcolor;
				padding: 3px 6px;
				border-radius: 5px;
				font-size: 12px;
				margin: 5px 5px 0 0;
				color: $color;
				font-weight: bold;
			}

		</style>";
		echo $style_tag; // phpcs:ignore.
	}
	add_action( 'admin_head', 'my_styles_method' );
}
