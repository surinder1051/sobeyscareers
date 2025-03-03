<?php
/**
 * Component template file
 *
 * @package fp-foundation
 */

?>
<div <?php esc_attr( $this->component_class() ); ?> data-js-recipe_card_slider>
	<?php if ( ! empty( $title ) ) : ?>
		<<?php echo esc_attr( $title_tag ); ?> class="title"><?php echo esc_attr( $title ); ?></<?php echo esc_attr( $title_tag ); ?>>
	<?php endif; ?>

	<?php if ( ! empty( $posts ) ) : ?>
		<div class="container-fluid">
			<div class="component-content-wrapper row mx-auto my-auto">
				<div class="recipe-card-slider-carousel w-100">
					<?php foreach ( $posts as $recipe_card_post ) : ?>
						<div class="carousel-item">
							<?php echo do_shortcode( '[recipe_card id="' . $recipe_card_post->ID . '" no_container="' . $atts['no_container'] . '"] ' ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>

	<?php endif; ?>
</div>
