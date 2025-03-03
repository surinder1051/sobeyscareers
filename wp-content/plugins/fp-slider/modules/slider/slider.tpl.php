<?php
/**
 * Slider shortcode template
 *
 * @package fp-slider
 */

?>
<div class="<?php echo esc_attr( $class ); ?>" data-js-slider role="banner" tabindex="0" aria-label="<?php esc_attr_e( 'Promotional Image Slider', FP_TD ); ?>">
	<div class='slider'>
		<?php if ( ! empty( $slides ) && is_array( $slides ) ) : ?>
			<?php foreach ( $slides as $count => $slide_id ) : ?>
				<?php echo do_shortcode( "[fp_slide id='$slide_id' count='$count' no_container='true' icon_slug='$icon_slug']" ); ?>
			<?php endforeach; ?>
		<?php endif; ?>
	</div>
	<?php if ( 'true' === $show_dots ) : ?>
		<div class="slick-nav"></div>
	<?php endif; ?>
</div>
