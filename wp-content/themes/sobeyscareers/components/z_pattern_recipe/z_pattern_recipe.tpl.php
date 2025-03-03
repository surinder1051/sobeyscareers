<div <?php $this->component_class($atts['classes']); ?> id="<?php echo $module->node;?>" data-js_zpattern_recipe>
	<div class="safety-container">
		<div class="image-container">
			<?php if ( empty($atts['image_src']) ): ?>
				<img src="https://dummyimage.com/900x500/597544/fff" class="desk-img">
			<?php else: ?>
			<img src="<?php echo $image_src;?>" class="desk-img" />
			<?php endif; ?>
		</div>
		<div class="text-container">
			<div class="display-table">
				<div class="display-cell">
					<div class="accent-bar-left smcenter">
						<p class="sub-title"><?php _e('Featured Recipe', FP_TD); ?></p>
						<?php if ( !empty($recipe->post_title) ): ?>
							<<?php echo $heading_type;?> class="zpattern-heading">
								<?php echo $recipe->post_title; ?>
							</<?php echo $heading_type;?>>
						<?php endif; ?>

							<div class="field_editor">
								<div class="recipe-detail-container">
									<?php if (isset($atts['content']['prep_time']) && !empty($atts['content']['prep_time'])) : ?>
									<div class="recipe-detail">
										<div class="prep-time">
											<span class="icon-prep-time"></span>
											<dl>
												<dt><?php _e('Prep Time', FP_TD); ?>:</dt>
												<dd><?php echo $atts['content']['prep_time'];?> min</dd>
											</dl>
										</div>
									</div>
									<?php endif; ?>
									<?php if (isset($atts['content']['total_time']) && !empty($atts['content']['total_time'])) : ?>
									<div class="recipe-detail">
										<div class="total-time">
											<span class="icon-total-time"></span>
											<dl>
												<dt><?php _e('Total Time', FP_TD); ?>:</dt>
												<dd><?php echo $atts['content']['total_time'];?> min</dd>
											</dl>
										</div>
									</div>
									<?php endif; ?>
									<?php if (isset($atts['content']['yield']) && !empty($atts['content']['yield'])) : ?>
									<div class="recipe-detail">
											<div class="serves">
												<span class="icon-serve"></span>
												<dl>
													<dt><?php _e('Serves', FP_TD); ?>:</dt>
													<dd><?php echo preg_replace('/[^\d]+/', '', $atts['content']['yield']);?></dd>
												</dl>
											</div>
										</div>
									</div>
									<?php endif; ?>
								</div>
							</div>

							<a href="<?php echo get_permalink($recipe->ID); ?>" class="-link-style-button button <?php echo $button_colour;?>" aria-label="<?php echo $button_aria; ?>">
								<?php esc_attr_e( $button_title, FP_TD ); ?>
							</a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php if ($atts['image_edge'] == '-angle-edge') : ?>
	<span class='angle-edge'></span>
	<?php endif; ?>
</div>
