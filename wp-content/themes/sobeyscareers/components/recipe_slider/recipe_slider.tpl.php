<?php global $post; ?>

<div <?php $this->component_class() ?> data-js-recipe_slider>
	<?php if (!empty($atts['title'])) : ?>
		<<?php echo $title_tag ?> class="title text-<?php echo $title_align ?>"><?php esc_attr_e($atts['title']); ?></<?php echo $title_tag ?>>
	<?php endif; ?>
	<div class="container-fluid">
		<div role="document" class="component-content-wrapper row mx-auto my-auto">
			<div id="recipe-slider-carousel" class="w-100">



				<?php if ($atts['posts']) : ?>

					<?php foreach ($atts['posts'] as $key => $post) : ?>

						<?php setup_postdata($post) ?>

						<?php

						$meta = get_post_meta($post->ID);
						$post_thumbnail_id = get_post_thumbnail_id($post->ID);

						$cooking_prep_time = get_field('cooking_prep_time');
						$cooking_total_time = get_field('cooking_total_time');
						$description = get_field('general_description');

						$related_products = get_field('related_products');


						global $_wp_additional_image_sizes;
						$image_size = 'tile_640x420';
						$width = 640;
						$height = 420;

						if (isset($_wp_additional_image_sizes[$image_size])) {
							$width = $_wp_additional_image_sizes[$image_size]['width'];
							$height = $_wp_additional_image_sizes[$image_size]['height'];
						}


						?>

						<div class="carousel-item">
							<div class="card">
								<div class="card-image-wrap" style='max-height: <?php echo $height ?>px'>
									<?php if (!$post_thumbnail_id) : ?>
										<img class="card-img-top d-block" alt="<?php echo $post->post_title; ?>" src="https://via.placeholder.com/<?php echo $width ?>x<?php echo $height ?>" />
									<?php else : ?>
										<?php echo wp_get_attachment_image($post_thumbnail_id, $image_size, '', array('class' => 'card-img-top d-block')); ?>
									<?php endif; ?>

									<div class="card-overlay">
										<div class="card-overlay-inner justify-content-center d-flex">
											<?php if (isset($related_products) && is_array($related_products)) : ?>
												<div class="products">
													<?php foreach ($related_products as $product) : ?>
														<?php $product_thumbnail_id =  get_post_thumbnail_id($product->ID) ?>

														<a href="<?php echo get_permalink($product->ID) ?>">
															<span class="images">
																<?php echo wp_get_attachment_image($product_thumbnail_id, 'thumbnail', '', array('class' => 'product-thumbnail')); ?>
															</span>
														</a>
													<?php endforeach; ?>

												</div>
											<?php endif; ?>
											<div class="recipe">
												<?php if (!empty($description)) : ?>
													<p class="description">
														<?php echo $description ?>
													</p>
												<?php endif; ?>
												<ul class="steps">
													<?php if (!empty($cooking_total_time)) : ?>
														<li>
															<span class="prep-times">
																<i class='icon-serve icon'></i>
																<span class="title">Total Time</span>
																<span class="time"><?php echo $cooking_total_time; ?> Minutes</span>
															</span>
														</li>
													<?php endif; ?>
													<?php if (!empty($cooking_prep_time)) : ?>
														<li>
															<span class="prep-times">
																<i class='icon-prep-time icon'></i>
																<span class="title">Prep Time</span>
																<span class="time"><?php echo $cooking_prep_time; ?> Minutes</span>
															</span>
														</li>
													<?php endif; ?>
												</ul>
												<button class="btn recipe-btn" data-url="<?php echo get_permalink($post->ID); ?>" aria-label="<?php echo __('View', FP_TD ) . ' ' . $post->post_title . ' ' . __('recipe', FP_TD ); ?>"><?php _e('Get the recipe', FP_TD); ?></button>
											</div>
										</div>
									</div>
									<!--//card-overlay-->
								</div>
								<div class="card-body">
									<h3 class="card-title"><?php echo $post->post_title; ?></h3>
								</div>
								<!--//card-body-->
							</div>
							<!--//card-->
						</div>
						<!--//carousel-item-->

					<?php endforeach;

					wp_reset_query();

				else : ?>
					<p>No recipes found.</p>
				<?php endif; ?>

			</div>
			<!--//carousel-->
		</div>
		<!--//component-wrapper-->
	</div>
	<!--//container-fluid-->
</div>