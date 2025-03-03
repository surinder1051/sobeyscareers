<div <?php $this->component_class(); ?> data-js-multi_item_carousel>
	<?php if (!empty($title)) : ?>
		<div class="carousel-header">
			<<?php echo $title_tag ?> class="title"><?php esc_attr_e($title); ?></<?php echo $title_tag ?>>

			<?php if ($slide_layout == '-title-card') : ?>
				<?php if (!empty($title_card_link)) : ?>
					<a aria-label="<?php echo $title_card_link_aria; ?>" href="<?php echo $title_card_link; ?>" class="cta-button <?php echo $cta_style; ?>"><?php echo $title_card_link_text; ?></a>
				<?php endif; ?>

				<button class="slick-prev slick-arrow" aria-label="Previous" type="button"><?php _e('Previous', FP_TD); ?></button>
				<button class="slick-next slick-arrow" aria-label="Next" type="button"><?php _e('Next', FP_TD); ?></button>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="container-fluid">
		<div role="document" class="component-content-wrapper row mx-auto my-auto">
			<div id="multi-item-carousel-<?php echo $node_id; ?>" class="carousel-wrapper w-100" data-slick='{
				"slidesToShow": <?php echo $slides_to_show; ?>,
				"slidesToScroll": <?php echo $slides_per_scroll; ?>,
				"centerMode": <?php echo $center_mode; ?>,
				"prevArrow": "<?php echo $prev_arrow; ?>",
				"nextArrow": "<?php echo $next_arrow; ?>",
				"responsive": [{"breakpoint": 767, "settings": {"slidesToShow": <?php echo $slides_to_show_mobile; ?>, "slidesToScroll": <?php echo $slides_per_scroll_mobile; ?>}}],
				"dots": <?php echo $enable_dots; ?>,
				"infinite": true,
				"variableWidth": <?php echo $variable_width; ?>}'>

				<?php if ($posts) : ?>
					<?php foreach ($posts as $key => $post) : ?>
						<?php
						if ($post->post_type == 'custom') {
							$postType             = 'custom';
							$post_thumbnail_id    = $post->thumbnail_id;
							$hover_thumbnail_id   = $post->thumbnail_hover_id;
							$cta_link             = $post->permalink;
							$thumbnail_image_size = $post->thumbnail_hover_image_size;
							$postDate             = $post->post_date;
							$button_text          = $post->button_text;
							$button_type          = $post->button_type;
							$category             = $post->post_category;
						} else {
							$postType             = (isset($post->post_type)) ? $post->post_type : ' ';
							$post_thumbnail_id    = get_post_thumbnail_id($post->ID);
							$cta_link             = get_permalink($post->ID);
							$postDate             = get_the_date('', $post->ID);
							$button_text          = $post->button_text ?: $cta_text;
							$button_type          = $post->button_type ?: '-simple-link-with-cta';

							$terms = get_the_terms($post->ID, $category_taxonomy);
							if (!is_wp_error($terms) && $terms && count($terms) > 0) {
								$first_term_in_category = $terms[0];
								$category = $first_term_in_category->name;
							}
						}
						
                        $cta_aria = (!empty($post->button_aria) && $post->button_aria != $post->button_text) ? $post->button_aria : '';
                        $cta_aria = strip_tags($cta_aria);

						if ($post->button_target == '_blank') :
							if (!empty($cta_aria)) :
								$cta_aria .= ' (' .__('Link opens in a new window', FP_TD) .')';
							else :
								$cta_aria = __('Link opens in a new window', FP_TD);
							endif;
						endif;
						?>

						<div class="carousel-item">
							<div class="card">
								<div class="card-image-wrap">
									<?php if (!(bool)$enable_overlay && !empty($cta_link)) : ?>
										<a href="<?php echo $cta_link; ?>" target="<?php echo $post->button_target;?>" aria-hidden='true' tabindex='-1' aria-label='<?php echo $cta_aria; ?>'>
										<?php endif; ?>

										<?php if (!$post_thumbnail_id) : ?>
											<img class="card-img-top d-block" alt="<?php echo $post->post_title; ?>" src="https://via.placeholder.com/<?php echo $width; ?>x<?php echo $height; ?>" />
										<?php else : ?>
											<?php echo wp_get_attachment_image($post_thumbnail_id, array($width, $height), '', array('class' => 'card-img-top d-block')); ?>
										<?php endif; ?>

										<?php if (!(bool)$enable_overlay && !empty($cta_link)) : ?>
										</a>
									<?php endif; ?>

									<?php if ((bool)$enable_overlay) : ?>
										<div class="card-overlay">
											<div class="card-overlay-inner justify-content-center d-flex">
												<?php if ($postType == 'video') : ?>
													<div class="card-cta -video">
														<button onclick="javascript:window.location.href='<?php echo get_permalink($post->ID); ?>'" data-js-video-url="<?php echo get_permalink($post->ID); ?>" aria-label="<?php _e('Play the video: ', FP_TD); ?><?php echo $post->post_title; ?>">
															<span class="icon-play"></span>
														</button>
													</div>
												<?php else : ?>
													<div class="card-cta -standard <?php _e($button_type, FP_TD); ?>">
														<?php if ($postType == 'custom') : ?>
															<a href="<?php echo $post->permalink; ?>" class="button <?php echo $cta_style; ?>" target="<?php echo $post->button_target; ?>" aria-label="<?php echo $cta_aria; ?>" aria-hidden="true" tabindex="-1"><?php echo $button_text; ?></a>
														<?php else : ?>
															<a href="<?php echo get_permalink($post->ID); ?>" class="button <?php echo $cta_style; ?>" target="<?php echo $post->button_target; ?>" aria-label="<?php echo $cta_aria; ?>" aria-hidden="true" tabindex="-1"><?php _e('Learn More', FP_TD); ?></a>
														<?php endif; ?>
													</div>
												<?php endif; ?>
											</div>
										</div>
									<?php endif; ?>

									<?php if (!empty($hover_thumbnail_id)) : ?>
										<div class="card-overlay">
											<?php echo wp_get_attachment_image($hover_thumbnail_id, $thumbnail_image_size, '', array('class' => 'card-img-top d-block')); ?>
										</div>
									<?php endif; ?>
								</div>

								<div class="card-body<?php echo ((bool)$slide_show_description) ? ' -with-description' : ''; ?>">
									<h3 class="card-title">
									<?php if (!(bool)$enable_overlay && !empty($cta_link)) : ?>
										<a href="<?php echo $cta_link; ?>" target="<?php echo $post->button_target;?>"<?php if ($post->button_target == '_blank') { echo ' aria-label="' . __('Link opens in a new window', FP_TD) . '"'; } ?>>
									<?php endif; ?>
										<?php echo $post->post_title; ?>
									<?php if (!(bool)$enable_overlay && !empty($cta_link)) : ?>
										</a>
									<?php endif;?>
									</h3>

									<?php if ((!empty($show_date) && $show_date == 'true') || (!empty($show_category) && $show_category == 'true')) : ?>
										<div class="row">
											<?php if (!empty($show_date) && $show_date == 'true' && isset($postDate)) : ?>
												<div class='date col-md-6'>
													<?php echo $postDate; ?>
												</div>
											<?php endif; ?>

											<?php if (!empty($show_category) && $show_category == 'true' && isset($category)) : ?>
												<div class='col-md-6'>
													<span class='category'><?php echo $category; ?></span>
												</div>
											<?php endif; ?>
										</div>
									<?php endif; ?>

									<?php if ((bool)$slide_show_description) : ?>
										<div class="card-description">
											<?php
												$excerpt = $postType == 'custom' ? $post->post_excerpt : substr(get_the_excerpt($post->ID), 0, $slide_description_length);
												echo $postType == 'custom' ? $excerpt : substr($excerpt, 0, strrpos($excerpt, ' '));
											?>
										</div>
									<?php endif; ?>
									<?php if (!empty($cta_link) && $button_type == '-simple-link-with-cta') : ?>
										<a href="<?php echo $cta_link; ?>" class="cta-button <?php echo $cta_style; ?>" target="<?php echo $post->button_target; ?>"<?php if (isset($cta_aria) && !empty($cta_aria) ) { echo 'aria-label="' . $cta_aria . '"'; } ?>><?php echo $button_text; ?></a>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else : ?>
					<p>No content found.</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>