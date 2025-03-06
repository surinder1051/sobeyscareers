<?php
/**
 * Delete Attachments
 * Make sure we delete attachments of posts when the post is deleted
 *
 * @package fp-foundation
 */

add_action(
	'before_delete_post',
	function ( $post_id ) {
		$args  = array(
			'post_type'              => 'attachment',
			'post_parent'            => $post_id,
			'post_status'            => 'any',
			'nopaging'               => true,
			// Optimize query for performance.
			'no_found_rows'          => true,
			'update_post_meta_cache' => false,
			'update_post_term_cache' => false,
		);
		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				wp_delete_attachment( $query->post->ID, true );
			}
		}

		wp_reset_postdata();
	}
);
