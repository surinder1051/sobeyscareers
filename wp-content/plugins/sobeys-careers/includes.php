<?php
/***
 * Load all required files
 */
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
$classes_dir = dirname(__FILE__).'/classes/';
$classes = array_diff(scandir($classes_dir), array('..', '.'));
if(isset($classes)) {
	foreach($classes as $class) {
		$file = $classes_dir.$class;
		if (file_exists( $file ) ) {
			include($file);
		}
	}
}
