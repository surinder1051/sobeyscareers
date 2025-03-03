<div <?php $this->component_class('card flex-row') ?> data-js-bs_card_horizontal_dynamic>
	<div class="row no-gutters">
		<?php if ($image = wp_get_attachment_image_url(get_post_thumbnail_id($post->ID), $thumbnail_image_size)) : ?>
			<div class="col-auto">
				<a href="<?php echo get_the_permalink($post->ID); ?>" aria-hidden="true" tabindex="-1">
					<img src="<?php echo $image; ?>" alt="<?php _e('Read more about', FP_TD); ?> <?php echo $post->post_title; ?>" />
				</a>
			</div>
		<?php endif; ?>
		<div class="col">
			<div class="card-block">
				<?php if ( !empty($show_date) && $show_date == 'true'): ?>
					<div class="date">
						<?php echo get_the_date() ?>
					</div>
				<?php endif; ?>
				<?php if (!empty($post->post_title)) : ?>
					<<?php echo $title_tag ?> class="card-title">
						<a href="<?php echo get_the_permalink($post->ID) ?>"><?php echo $post->post_title; ?></a>
					</<?php echo $title_tag; ?>>
				<?php endif; ?>
				<?php if (!isset($atts['no_excerpt']) || (isset($atts['no_excerpt']) && $atts['no_excerpt'] !== 'true') ): ?>
					<?php if ($excerpt = fp_extract_excerpt($post,$atts)) : ?>
						<div class="card-text">
							<?php echo html_entity_decode($excerpt) ?>
							<?php if ( !empty($show_read_more) && $show_read_more == 'true' ): ?>
								<a href="<?php the_permalink() ?>" target='_self' aria-hidden="true" tabindex="-1"><?php echo $read_more_label ?></a>
							<?php endif; ?>
						</div>
					<?php endif; ?>
				<?php endif; ?>
				<?php if (isset($atts['enable_rating']) && $atts['enable_rating'] && isset($atts['id']) && $atts['id']) : ?>
					<?php echo do_shortcode("[sobeys_ratings_basic id='" . $atts['id'] . "' view_only='true' ]"); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>