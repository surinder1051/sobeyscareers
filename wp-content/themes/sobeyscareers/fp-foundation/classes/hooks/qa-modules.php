<?php
/**
 * QA Modules
 *
 * @package fp-foundation
 */

use fp\components;

if ( ! function_exists( 'fp_qa_modules' ) ) {
	/**
	 * Run through an array of checks to see if file contains that content.
	 *
	 * @param string $string is the content to check.
	 * @param array  $check are the options to test.
	 * @param bool   $get_results (optional) If true, return an array of all results.
	 *
	 * @return integer|array
	 */
	function multi_strpos( $string, $check, $get_results = false ) {
		$result = array();
		$check  = (array) $check;

		foreach ( $check as $s ) {
			$pos = strpos( $string, $s );

			if ( false !== $pos ) {
				if ( $get_results ) {
					$result[ $s ] = $pos;
				} else {
					return $pos;
				}
			}
		}

		return empty( $result ) ? false : $result;
	}

	/**
	 * Load a component template file to see if it has heading tags.
	 * Run by adding ?qa_modules to the query string.
	 */
	function fp_qa_modules() {
		global $fp_loaded_components;

		if ( empty( $_GET['qa_modules'] ) ) { // phpcs:ignore
			return;
		}
		foreach ( $fp_loaded_components as $key => $component_file ) {

			$tpl = str_replace( '.php', '.tpl.php', $component_file );

			require_once $component_file;
			$path_parts = pathinfo( $component_file );

			$class     = 'fp\components\\' . $path_parts['filename'];
			$component = new $class();

			echo '<h2>' . esc_attr( $component->component ) . '</h2>';

			if ( false !== multi_strpos( file_get_contents( $tpl ), array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'H1', 'H2', 'H3', 'H4', 'H5', 'H6' ) ) ) { //phpcs:ignore
				echo "<h3 style='color: red;'>Contains H tags</h3>";
			}
		}
		die( 'gg' );
	}
	add_action( 'init', 'fp_qa_modules', 10, 1 );
}
