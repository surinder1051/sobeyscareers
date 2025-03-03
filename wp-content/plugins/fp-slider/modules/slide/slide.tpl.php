<?php
/**
 * Slide shortcode template
 *
 * @package fp-slider
 */

?>
<div class="<?php echo esc_attr( $slide_class . ' slide-' . $id ); ?>" data-slider-no="<?php echo esc_attr( $count ); ?>">
	<div class="slider_img">
		<?php if ( 'image' === $media ) : ?>
			<?php echo ( $url ) ? '<a aria-label="' . esc_attr( $text_box_button_text ) . '" target="' . esc_attr( $link_target ) . '" href="' . esc_attr( $url ) . '">' : '<span>'; ?>
			<?php if ( $mobile_image_id ) : ?>
				<picture class="desk_slide desk_slide_<?php echo esc_attr( $id ); ?>">
					<source media="(max-width: <?php echo esc_attr( apply_filters( 'slider_img_media_max_width', '480px' ) ); ?>)" srcset="<?php echo esc_attr( $mobile_srcset ); ?>" sizes="<?php echo esc_attr( $mobile_sizes ); ?>">
					<source media="(min-width: <?php echo esc_attr( apply_filters( 'slider_img_media_min_width', '481px' ) ); ?>)" srcset="<?php echo esc_attr( $srcset ); ?>" sizes="<?php echo esc_attr( $sizes ); ?>">
					<img width='100%' height='100%' src="<?php echo esc_attr( $src ); ?>" alt="<?php echo esc_attr( $alt ); ?>">
				</picture>
			<?php else : ?>
				<img class="desk_slide desk_slide_<?php echo esc_attr( $id ); ?>" src="<?php echo esc_attr( $src ); ?>" srcset="<?php echo esc_attr( $srcset ); ?>" sizes="<?php echo esc_attr( $sizes ); ?>" alt="<?php echo esc_attr( $alt ); ?>" />
			<?php endif; ?>
			<?php echo ( $url ) ? '</a>' : '</span>'; ?>
		<?php elseif ( 'video' === $media ) : ?>
			<div class="desk_slide desk_slide_<?php echo esc_attr( $id ); ?>">
				<?php if ( ! empty( $video_id ) ) : ?>
					<div class="slider_video" data-js-simple_video="<?php echo esc_attr( $video_type ); ?>">
						<div class="video-defer-container">
							<div class="video-defer" data-embed="<?php echo esc_attr( $video_id ); ?>">
								<?php if ( ! empty( $icon_slug ) ) : ?>
									<div class="play-button-icon video-remove <?php echo esc_attr( $icon_slug ); ?>"></div>
								<?php else : ?>
									<div class="play-button video-remove"></div>
								<?php endif; ?>

								<?php if ( ! empty( $thumbnail_id ) ) : ?>
									<?php if ( $mobile_image_id ) : ?>
										<picture class="video-remove" >
											<source media="(max-width: <?php echo esc_attr( apply_filters( 'slider_img_media_max_width', '480px' ) ); ?>)" srcset="<?php echo esc_attr( $mobile_srcset ); ?>" sizes="<?php echo esc_attr( $mobile_sizes ); ?>">
											<source media="(min-width: <?php echo esc_attr( apply_filters( 'slider_img_media_min_width', '481px' ) ); ?>)" srcset="<?php echo esc_attr( $srcset ); ?>" sizes="<?php echo esc_attr( $sizes ); ?>">
											<img width='100%' height='100%' class="custom-image" src="<?php echo esc_attr( $src ); ?>" alt="poster-image">
										</picture>
									<?php else : ?>
										<img class="video-remove custom-image" alt="poster-image" src="<?php echo esc_attr( $src ); ?>" srcset="<?php echo esc_attr( $srcset ); ?>"sizes="<?php echo esc_attr( $sizes ); ?>">
									<?php endif; ?>
								<?php elseif ( 'youtube' === $video_type ) : ?>
									<img class="video-remove" src="https://img.youtube.com/vi/<?php echo esc_attr( $video_id ); ?>/sddefault.jpg" alt="default-image">
								<?php endif; ?>

								<div class="video-player-id" id="player-<?php echo esc_attr( $id ); ?>"></div>
							</div>
						</div>
					</div>
				<?php else : ?>
					<h3>Missing/Invalid Video ID</h3>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( ! empty( $text_box ) ) : ?>
		<div class="slider_text_box">
			<div class="slider_text_box_tbl">
				<div class="<?php echo esc_attr( $text_box_cel_class ); ?>">
					<?php if ( $text_content_image ) : ?>
						<span class="text-bg" style="background-image: url('<?php echo esc_attr( $text_content_image ); ?>');" role="decoration"></span>
					<?php endif; ?>

					<?php echo $text_box_heading ? '<h1 class="slide_heading">' . wp_kses_post( $text_box_heading ) . '</h1>' : ''; ?>
					<?php echo $text_box_description ? '<p class="slide_description">' . wp_kses_post( $text_box_description ) . '</p>' : ''; ?>

					<?php if ( $url && $text_box_button_text ) : ?>
						<a class="slider_btn grow_txt" href="<?php echo esc_attr( $url ); ?>" target="<?php echo esc_attr( $link_target ); ?>" aria-label="<?php echo esc_attr( $text_box_button_text ); ?>" tabindex="-1" data-ajax="false">
							<?php echo esc_html( $text_box_button_text ); ?>
						</a>
					<?php endif ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
