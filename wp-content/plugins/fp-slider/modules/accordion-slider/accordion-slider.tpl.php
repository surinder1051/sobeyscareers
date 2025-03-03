<?php
/**
 * Accordion Slider shortcode template
 *
 * @package fp-slider
 */

?>
<div class="<?php echo esc_attr( $class ); ?>" data-js-accordion_slider role="banner" aria-label="<?php esc_attr_e( 'Promotional Image Slider', FP_TD ); ?>" id='node-<?php echo esc_attr( $id ); ?>'>
	<div class='slider has-<?php echo count( $slides ); ?>' style="opacity: 1" data-breakpoint='<?php echo esc_attr( $slide_breakpoint ); ?>'>
		<?php foreach ( $slides as $count => $slide_id ) : ?>
			<div class="card <?php echo $count > 0 ? esc_attr( 'collapsed slide-' . $count ) : esc_attr( 'slide-' . $count ); ?>" data-toggle="" data-target="#collapse-<?php echo esc_attr( $id . '_' . $slide_id ); ?>" aria-expanded="<?php echo $count > 0 ? 'false' : 'true'; ?>" aria-controls="collapse-<?php echo esc_attr( $id . '_' . $slide_id ); ?>" style="<?php echo ! empty( $styles[ $slide_id ]['background'] ) ? esc_attr( 'background: ' . $styles[ $slide_id ]['background'] . ';' ) : ''; ?> z-index: <?php echo esc_attr( $count ); ?>;" id="card-<?php echo esc_attr( $id . '_' . $slide_id ); ?>" data-button-active="<?php echo esc_attr( $styles[ $slide_id ]['button_active_colour'] ); ?>" data-index="<?php echo esc_attr( $count ); ?>">
				<div class="screen-reader-text status">
					<?php esc_attr_e( 'You are on slide', FP_TD ); ?><span class='slide-number'><?php echo esc_attr( 1 + $count ); ?></span><?php esc_attr_e( 'of', FP_TD ) . ' ' . count( $slides ); ?>
				</div>

				<div class="card-header" role="heading" aria-level="2" id="heading-<?php echo esc_attr( $id . '-' . $slide_id ); ?>">
					<button class="mb-0">
						<span class="button-text" style="color: <?php echo esc_attr( $styles[ $slide_id ]['heading_colour'] ); ?>"><?php echo esc_attr( $styles[ $slide_id ]['button_text'] ); ?></span>
						<?php if ( ! empty( $slide_icon ) ) : ?>
						<span class="button-icon <?php echo esc_attr( $slide_icon ); ?>"<?php echo 0 === $count && ! empty( $styles[ $slide_id ]['button_active_colour'] ) ? esc_attr( ' style="color: #' . $styles[ $slide_id ]['button_active_colour'] . '"' ) : ''; ?>></span>
						<?php endif; ?>
					</button>
				</div>

				<div id="collapse-<?php echo esc_attr( $id . '_' . $slide_id ); ?>" class="collapse <?php echo ( 0 === $count ) ? 'show' : ''; ?>" aria-labelledby="heading-<?php echo esc_attr( $id . '-' . $slide_id ); ?>" data-parent="#node-<?php echo esc_attr( $id ); ?>">
					<div class="card-body has-<?php echo count( $slides ); ?> <?php echo esc_attr( $styles[ $slide_id ]['slide-layout'] ); ?>" data-aria-label='<?php echo esc_attr__( 'Click to view ' . $styles[ $slide_id ]['button_text'], FP_TD ); ?>'>
						<?php echo do_shortcode( "[fp_slide id='$slide_id' count='$count' no_container='true']" ); ?>
					</div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</div>
