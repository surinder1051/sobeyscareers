<div <?php $this->component_class() ?> data-js-photo_bs_card_simple>
	<?php if (!empty($atts['callouts'])) : ?>
		<div class="card-grid-row <?php echo $atts['card_grid_class']; ?>">
			<?php foreach ($atts['callouts'] as $index => $cardItem) : ?>
				<div id="card-<?php echo $node_id; ?>-<?php echo $index; ?>" class="card <?php echo $cardItem['classes']; ?>" <?php echo !empty($cardItem['img']) ? 'style="background-image: url(' . $cardItem['img'] . ');"' : ''; ?>>
					<div class="card-img-overlay d-flex align-items-end">
						<?php if ((bool) $cardItem['overlay']) : ?>
							<span class="gradient-overlay"></span>
						<?php endif; ?>
						<div>
							<div class="card-content">
								<<?php echo $scard_heading_type ?> class="card-title" area-level="<?php echo str_replace("h", "", $scard_heading_type) ?>">
									<?php echo $cardItem['title']; ?>
								</<?php echo $scard_heading_type ?>>
								<?php if (!empty($cardItem['format']->callout_description)) : ?>
									<div class="card-text">
										<?php echo $cardItem['format']->callout_description ?>
									</div>
								<?php endif ?>
							</div>
							<div class="card-button">
								<a href="<?php echo $cardItem['format']->callout_url; ?>" class="button <?php echo $cardItem['format']->callout_button_theme; ?>" target="<?php echo $cardItem['link_target']; ?>" aria-label="<?php echo $cardItem['format']->callout_aria_label; ?>"><?php echo $cardItem['format']->callout_link_text; ?></a>
							</div>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>