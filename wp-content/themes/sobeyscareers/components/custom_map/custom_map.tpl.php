<div <?php $this->component_class($atts['classes']); ?> data-js-custom_map>
<div class="safety-container">
		<div class="interactive-map-module-component-inner">
			<div class="heading" <?php if (isset($heading_background_src)) { echo 'style="background: url('.$heading_background_src.'); "'; } ?>>
				<?php if (!empty($atts['heading_text'])) : ?>
				<<?php echo $heading_type;?> class="map-title <?php echo $heading_colour;?>"><?php echo esc_attr_e( $heading_text ) ?></<?php echo $heading_type;?>>
				<?php endif; ?>
			</div>
			<div class="map-container">
				<div class="map-area-wrap">
					<div class="map-area" aria-hidden="true">
						<div id="map"></div>
						<div class="mapshadow"></div>
					</div>
				</div>
			</div>
			<div class="map-legend" aria-hidden="true">
				<ul class="cta-list" aria-hidden="true" aria-label="<?php _e('Map Legend', FP_TD ); ?>">
					<?php
					if (isset($atts['posts']) && !empty($atts['posts'])) :
						foreach ($atts['posts'] as $index => $post) : ?>
						<?php
							$lat = get_field('latitude', $post->ID);
							$long = get_field('longitude', $post->ID);
							if (!empty($lat) && !empty($long)) :

								$button_text = __($atts['map_button_text'], FP_TD );
								$button_class = $atts['map_theme'];
						?>
							<li data-latitude='<?php echo $lat;?>'
							data-longitude='<?php echo $long;?>'
							data-button='<?php echo $button_text;?>'
							data-title='<?php echo $post->post_title;?>'
							data-content='<?php echo $post->post_content;?>'
							data-icon='<?php echo $atts['map_icon'];?>'
							data-theme='<?php echo $atts['map_theme'];?>'
							>
								<span class="icon">
									<img src='<?php echo $atts['map_icon'] ?>' alt='<?php _e('Map Marker', FP_TD );?>' />
								</span>
								<h4><?php echo $post->post_title;?></h4>
							</li>
								<?php endif; ?>
						<?php
						endforeach;
					endif;
					?>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="interactive-map-lightbox-wrap">
	<div class="interactive-map-lightbox-inner">
		<div class="interactive-map-lightbox-content">
			<a href="javascript:void(0)" class="lightbox-close modal-action" aria-label="Click to close the lightbox">
				<span class="icon-close"></span>
			</a>
			<div class="map-preview-large" ></div>
		</div>
	</div>
</div>
