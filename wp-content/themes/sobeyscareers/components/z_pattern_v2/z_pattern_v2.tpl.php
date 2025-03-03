<div class="component_<?php esc_attr_e($this->component); ?> <?php esc_attr_e($atts['classes']); ?>" role="document" aria-labelledby="zPatternContent-<?php echo $module->node; ?>">
	<div class="safety-container">

		<?php $this->draw_responsive_image($atts['background_image'], 'background_image', $atts, $module, false, false) ?>

		<div class="text-container">
			<div class="display-table">
				<div class="display-cell">
					<?php if (!empty($atts['heading']) || !empty($atts['content']) || !empty($atts['link_title'])) : ?>
						<div class="accent-bar-left smcenter">
							<?php if (!empty($atts['heading'])) : ?>
								<<?php echo (!empty($atts['heading_type']) ? $atts['heading_type'] : 'h3'); ?> class='<?php echo $atts['heading_color'] ?> heading' id="zPatternContent-<?php echo $module->node; ?>"><?php echo $atts['heading']; ?></<?php echo (!empty($atts['heading_type']) ? $atts['heading_type'] : 'h3'); ?>>
							<?php endif; ?>
							<?php if (!empty($atts['content'])) : ?>
								<div class="field_editor">
									<?php echo $atts['content']; ?>
								</div>
							<?php endif; ?>
							<?php if ('-link-style-button' == $atts['link_type']) : ?>
								<?php if (!empty($atts['link_title']) && !empty($atts['link_url'])) :

									$target = ('new' == $atts['target']) ? ' target="_blank" ' : '';
									$aria 	= (!empty($atts['link_aria_button'])) ? __($atts['link_aria_button']) : __('Read more regarding', FP_TD) . ' ' . $atts['link_title'];

									if (!empty($atts['heading'])) :
										$aria = $atts['link_title'] . __(' regarding ', FP_TD) . $atts['heading'];
									endif;

								?>
									<a <?php echo $target ?> href="<?php echo $atts['link_url']; ?>" class="-link-style-button button <?php echo (!empty($atts['button_color'])) ? $atts['button_color'] : ''; ?>" aria-label="<?php echo __($aria); ?>">
										<?php esc_attr_e($atts['link_title']) ?>
									</a>
								<?php endif; ?>
							<?php elseif ( ('-link-style-text' == $atts['link_type']) && !empty($atts["text_links"]) ) : ?>
								<?php foreach ($atts["text_links"] as $key => $link) : ?>
									<?php if (!empty($link->title) && !empty($link->link)) : ?>
										<?php $target = ('new' == $link->target) ? ' target="_blank" ' : '';
										$aria 	= (!empty($atts['link_aria_label'])) ? __($atts['link_aria_label']) : __('Read more regarding', FP_TD) . ' ' . $atts['link_title'];
										if (!empty($atts['heading']) && !empty($atts['link_aria_label'])) :
											$aria = $atts['heading'] . ' ' . __('resource', FP_TD) . ': ' . $link->title;
										endif;
										?>
										<a <?php echo $target ?> href="<?php echo $atts['link_url']; ?>" aria-label="<?php echo __($aria); ?>">
											<?php esc_attr_e($link->title) ?>
										</a>

									<?php endif; ?>
								<?php endforeach; ?>
							<?php elseif ( ('-link-style-links' == $atts['link_type']) && !empty($atts["icons_links"]) ) : ?>
								<?php foreach ($atts["icons_links"] as $key => $link) : ?>
									<?php if (!empty($link->title) && !empty($link->link)) : ?>
										<?php $target = ('new' == $link->target) ? ' target="_blank" ' : '';
										$aria 	= (!empty($atts['link_aria_label'])) ? __($atts['link_aria_label']) : __('Read more regarding', FP_TD) . ' ' . $atts['link_title'];
										if (!empty($atts['heading']) && !empty($atts['link_aria_label'])) :
											$aria = $atts['heading'] . ' ' . __('resource', FP_TD) . ': ' . $link->title;
										endif;
										?>
										<a class="icon-link" <?php echo $target ?> href="<?php echo $atts['link_url']; ?>" aria-label="<?php echo __($aria); ?>">
											<?php esc_attr_e($link->title) ?>
											<?php if (!empty($link->link_share_icon)) : ?>
											<span class="share-icon">
												<i class="<?php echo $link->link_share_icon ?>" aria-hidden="true"></i>
											</span>
										<?php endif; ?>
										</a>

									<?php endif; ?>
								<?php endforeach; ?>
							<?php endif; ?>

						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>