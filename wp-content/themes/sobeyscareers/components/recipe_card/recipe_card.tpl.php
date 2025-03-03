<?php
/**
 * Component template file
 *
 * @package fp-foundation
 */

?>
<div class="card recipe-card <?php echo esc_attr( $post->post_type ) . '-card'; ?>" 
	<?php if ( true == $enable_gigya_favorate ) : ?>
		data-js-gigya-favorite 
		<?php if ( isset( $legacy_post_id ) && $legacy_post_id ) : ?>
			data-js-legacy-post-id="<?php echo esc_attr( $legacy_post_id ); ?>"
		<?php endif; ?>
		data-js-id="<?php echo esc_attr( $post->ID ); ?>" 
		data-js-post-type="<?php echo esc_attr( $post->post_type ); ?>"
	<?php endif; ?>	
	>

	<a href="<?php echo esc_attr( get_permalink( $id ) ); ?>" class="recipe_card_link" aria-label="<?php echo esc_attr( $button_text . ' ' . $post->post_title ); ?>" >
		<?php $image = wp_get_attachment_image_url( get_post_thumbnail_id( $id ), apply_filters( 'recipe_card_thumbnail_size', 'square_480' ) ); ?>
		<?php if ( $image ) : ?>
			<div class='card-img-top' style='background-image: url("<?php echo esc_attr( $image ); ?>")'></div>
		<?php endif; ?>
	</a>

	<div class="card-body">

		<?php if ( ! empty( $post->post_title ) ) : ?>
			<?php if ( isset( $index ) && $index ) : ?>
				<div class="index"><?php echo esc_html( $index ); ?></div>
			<?php endif; ?>
			<<?php echo esc_attr( $title_tag ); ?> class="card-title equal_height">
				<a href="<?php echo esc_attr( get_permalink( $id ) ); ?>" aria-label="<?php echo esc_attr__( 'View', FP_TD ) . ' ' . esc_attr( $post->post_title ) . ' ' . esc_attr__( 'recipe', FP_TD ); ?>">
					<?php echo esc_html( ( strlen( $post->post_title ) > $title_length ) ? substr( $post->post_title, 0, $title_length ) . '...' : $post->post_title ); ?>
				</a>
			</<?php echo esc_attr( $title_tag ); ?>>
			<?php if ( isset( $enable_rating ) && $enable_rating && isset( $id ) && $id ) : ?>
				<?php echo do_shortcode( "[sobeys_ratings_basic id='" . $id . "' view_only='true' ]" ); ?>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( ! isset( $hide_details ) || true !== $hide_details ) : ?>

			<div class='d-flex bottom-col-wrap'>

				<?php foreach ( $field_data as $single_data ) : ?>
					<?php $value = get_post_meta( $post->ID, $single_data['field'], true ); ?>
					<?php if ( $value ) : ?>
						<div class='col'>
							<span class="<?php echo esc_attr( $single_data['icon'] ); ?>" role="presentation"></span>
							<div class="description">
								<div class="description-term"><?php echo esc_attr( $single_data['label'] ); ?></div>
								<div class="description-detail">
									<?php if ( isset( $single_data['strip_non_numeric'] ) && $single_data['strip_non_numeric'] ) : ?>
										<?php echo esc_html( preg_replace( '/[^0-9-]/', '', $value ) ); ?>
										<?php if ( isset( $single_data['add_minute_suffix'] ) && $single_data['add_minute_suffix'] ) : ?>
											<?php echo esc_html__( 'min', FP_TD ); ?>
										<?php endif; ?>
									<?php else : ?>
										<?php echo esc_html( $value ); ?>
										<?php if ( isset( $single_data['add_minute_suffix'] ) && $single_data['add_minute_suffix'] && strpos( $value, 'min' ) === false ) : ?>
											<?php echo esc_html__( 'min', FP_TD ); ?>
										<?php endif; ?>
									<?php endif; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
