<?php
/**
 * WP Head Remove Generator
 * This will remove the WordPress generator meta tags from the head.
 *
 * @package fp-foundation
 */

remove_action( 'wp_head', 'wp_generator' );
