<?php
/**
 *
 * This supporting function is used to built the custom wrapping chevron html
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'chevron_it' ) ) {
	/**
	 * Add a chevron icon to a link.
	 *
	 * @param string $title is the content to add a chevron to.
	 *
	 * @return string
	 */
	function chevron_it( $title ) {

		$chevrons = get_option( 'options_default_theme_options_text_links_chevrons' );
		if ( '1' !== $chevrons ) {
			return $title;
		}
		if ( empty( $title ) ) {
			return $title;
		}

		$title = trim( $title );
		$text  = '';

		if ( false !== strrpos( $title, ' ' ) ) {
			$last_word_start = strrpos( $title, ' ' ) + 1;
			$last_word       = substr( $title, $last_word_start );
			$text            = substr( $title, 0, $last_word_start - 1 );
		} else {
			// One word title.
			$last_word = $title;
		}
		return $text . " <span>$last_word</span>";

	}
}
