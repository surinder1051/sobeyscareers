<div <?php echo $this->component_class(); ?> data-js-photo_bs_card_overlay>
	<?php if (!empty($atts['callouts'])) : ?>
		<div class="card-grid-row <?php echo $atts['card_grid_class']; ?>">
			<?php foreach ($atts['callouts'] as $index => $cardItem) : ?>
				<div class="card <?php echo $cardItem['classes']; ?>" <?php echo $cardItem['img']; ?>>
					<div class="card-img-overlay d-flex align-items-end">
						<div class="card-img-overlay-inner">
							<div class="col-12">
								<?php if ($display_subheading == 'true') : ?>
									<p class="sub-title" <?php echo $cardItem['heading_colour']; ?>>
										<?php echo $cardItem['post_type']; ?>
									</p>
								<?php endif; ?>
								<<?php echo $callout_heading_type ?> class="card-title" id="cardTitle-<?php echo $node_id; ?>-<?php echo $index; ?>" area-level="<?php echo $callout_heading_aria_level; ?>" <?php echo $cardItem['heading_colour']; ?>>
									<?php echo $cardItem['title']; ?>
								</<?php echo $callout_heading_type ?>>
								<div class="card-button">
									<a href="<?php echo $cardItem['link']; ?>" class="button <?php echo $cardItem['button_theme']; ?>" target="<?php echo $cardItem['link_target']; ?>" <?php if ( !empty($cardItem['aria_label']) ): ?>aria-label="<?php echo $cardItem['aria_label']; ?><?php endif; ?>">
										<?php echo $cardItem['link_text']; ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
