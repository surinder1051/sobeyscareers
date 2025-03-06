<?php
/**
 * BB Disable Column Resize
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'bb_disble_col_resize' ) ) {
	/**
	 * Add some custom stylin in the header to disable BB column resizing in edit mode
	 */
	function bb_disble_col_resize() {
		if ( ! isset( $_GET['fl_builder'] ) ) { //phpcs:ignore
			return;
		}
		echo '<style>
		.fl-block-col-resize{
			display: none;
		}
		.fl-col .fl-drop-target, .fl-col-small .fl-block-copy, .fl-col-small + fl-col .fl-block-copy{
			display: none !important;
		}
		</style>';
	}
	add_action( 'wp_head', 'bb_disble_col_resize' );
}
