<div <?php $this->component_class() ?> data-js-store_feature>
	<?php if (!empty($cta_link) && empty($button_text)) : ?>
		<a class="feature-link" href="<?php echo $cta_link ?>">
		<?php endif; ?>
		<?php if ($theme == 'default') : ?>
			<div class="-theme-default">
				<?php if (!empty($title)) : ?>
					<div class="title"><?php esc_attr_e($title); ?></div>
				<?php endif; ?>

				<?php if (!empty($subtitle)) : ?>
					<div class="subtitle"><?php esc_attr_e($subtitle); ?></div>
				<?php endif; ?>

				<?php if (!empty($feature_points_list)) : ?>
					<div class="feature-list">
						<?php foreach ($feature_points_list as $feature_point) : ?>
							<div class="feature-item-wrapper">
								<div class="feature-item">
									<span class="feature-icon <?php echo $feature_point->icon ?>"></span>
									<span class="feature-text"><?php esc_attr_e($feature_point->text) ?></span>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		<?php elseif ($theme == 'image') : ?>
			<div class="-theme-image image-pos-<?php echo $background_image_pos ?> <?php echo (!empty($button_text)) ? '-has-button' : '-no-button'; ?>">
				<?php //if ($background_image_pos == 'left') : 
				$img_src = wp_get_attachment_image_src($background_image, 'tile_835x304');
				$image_id = get_post($background_image);
				$image_alt = get_post_meta($background_image, '_wp_attachment_image_alt', true);
				$image_title = $image_id->post_title;

				?>
				<div class="feature-image-wrapper">
					<?php //draw_responsive_image($background_image, 'background_image', $atts, $module) 
					?>
					<img class="feature-image" src="<?php echo $img_src[0] ?>" aria-label="<?php echo $background_image_aria_label ?>" alt="<?php echo $image_alt ?>" title="<?php echo $image_title ?>" />
				</div>

				<div class="feature-summary">
					<div class="feature-text">
						<?php if (!empty($title)) : ?>
							<div class="title"><?php esc_attr_e($title); ?></div>
						<?php endif; ?>
					</div>

					<?php if (!empty($button_text) && !empty($cta_link)) : ?>
						<a class="feature-cta-button" href="<?php echo $cta_link ?>"><?php esc_attr_e($button_text); ?></a>
					<?php endif; ?>
				</div>
			</div>
		<?php endif; ?>
		<?php if (!empty($cta_link) && empty($button_text)) : ?>
		</a>
	<?php endif; ?>
</div>