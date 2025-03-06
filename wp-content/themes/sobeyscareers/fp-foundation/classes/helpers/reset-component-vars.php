<?php
/**
 * Reset Component Vars.
 *
 * @package fp-foundation
 */

if ( ! function_exists( 'reset_component_vars' ) ) {
	/**
	 * Resets global variables that are used between components, so that when they're used again they're not automatically set.
	 */
	function reset_component_vars() {
		unset( $GLOBALS['class'] );
		unset( $GLOBALS['modifier'] );
	}
}
