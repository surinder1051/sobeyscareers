<div <?php $this->component_class() ?> data-js-related>
	<?php if (!empty($title) && !empty($posts)) : ?>
		<<?php echo $title_tag ?> class='text-<?php echo $title_align ?>' id='relatedHeading-<?php echo $node_id; ?>'><?php echo $title ?></<?php echo $title_tag ?>>
	<?php endif; ?>
	<?php if (!empty($posts)) : ?>
		<div class='card-deck <?php echo ($stacked == 'true') ? "flex-column" : "" ?> <?php echo 'columns_' . count($posts) ?>'>
			<?php foreach ($posts as $post) : ?>
				<?php
				// If the source of related is from imported meta post IDs, we map those wp-content post ids to check if they exist locally.
				if ( is_numeric($post) && !empty($atts['centralized_content']) ) {
					$mapped_id_to_centralized_content = $this->get_local_postID($post);
					$post = get_post($mapped_id_to_centralized_content);
				}
				echo do_shortcode("[" . $shortcode . " id='" . $post->ID . "' no_container='" . $atts['no_container'] . "'] ");

				?>
			<?php endforeach; ?>
		</div>
	<?php elseif (is_user_logged_in()) : ?>
		<b><i>Data is missing for related module.</i></b>
	<?php endif; ?>
</div>