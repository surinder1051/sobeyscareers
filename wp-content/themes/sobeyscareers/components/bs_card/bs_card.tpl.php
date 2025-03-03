<div <?php $this->component_class( 'card' ); ?> style='<?php echo isset( $card_min_width ) ? 'min-width: ' . $card_min_width . ' ' : ''; ?><?php echo $card_img_bg; ?>' data-js-bs_card>
	<?php $show_button = ( isset( $text_links[0] ) && $text_links[0] ) ? true : false; ?>
	<?php $enable_image_overlay = false; ?>
	<?php if ( ! empty( $image ) && $image_position == 'top' ) : ?>
		<?php if ( ! empty( $module->settings->overlay_cta_url ) && ( ! empty( $enable_overlay ) && $enable_overlay == 'true' ) ) : ?>
			<a href="<?php echo $module->settings->overlay_cta_url; ?>" class="card_link_wrap" target='<?php echo ( isset( $overlay_cta_url_target ) ? $overlay_cta_url_target : '_self' ); ?>' aria-hidden="true" tabindex="-1" class="button<?php echo isset( $overlay_cta_theme ) ? $overlay_cta_theme : ''; ?>' ">
				<?php if ( ! empty( $enable_overlay ) && $enable_overlay == 'true' ) : ?>
					<div class="overlay row">
						<?php if ( isset( $atts['overlay_cta_text'] ) ) : ?>
							<span class='align-self-center button'>
								<?php echo $atts['overlay_cta_text']; ?>
							</span>
						<?php endif; ?>
					</div>
				<?php endif ?>
				<div class='card-img-top' style='<?php echo $card_img_top; ?>' data-height="<?php echo $image_props[2]; ?>" data-width="<?php echo $image_props[1]; ?>"></div>
			</a>
		<?php else : ?>
			<div class='card-img-top' style='<?php echo $card_img_top; ?>' data-height="<?php echo $image_props[2]; ?>" data-width="<?php echo $image_props[1]; ?>"></div>
		<?php endif; ?>
	<?php endif; ?>

	<div class="card-body">
		<?php if ( ( ( ! empty( $show_date ) && $show_date == 'true' ) || ( ! empty( $show_category ) && $show_category == 'true' ) ) && $meta_position == 'above' ) : ?>
			<div class="row">
				<?php if ( ! empty( $show_date ) && $show_date == 'true' ) : ?>
					<div class='date col-md-6'>
						<?php echo apply_filters( 'get_the_date', $date ); ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $show_category ) && $show_category == 'true' && isset( $first_term_in_category->name ) ) : ?>
					<div class='col-md-6'>
						<span class='category'><?php echo $first_term_in_category->name; ?></span>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $title ) ) : ?>
			<?php if ( ! empty( $module->settings->overlay_cta_url ) && ( ! empty( $enable_overlay ) && $enable_overlay == 'true' ) ) : ?>
				<<?php echo $heading_type; ?> class="card-title" id="cardTitle-<?php echo $node_id; ?>">
					<a href="<?php echo $module->settings->overlay_cta_url; ?>" class="card_link_wrap" target='<?php echo ( isset( $overlay_cta_url_target ) ? $overlay_cta_url_target : '_self' ); ?>'>
						<?php echo $title; ?>
					</a>
				</<?php echo $heading_type; ?>>
				<?php if ( isset( $atts['enable_rating'] ) && $atts['enable_rating'] && isset( $atts['id'] ) && $atts['id'] ) : ?>
					<?php echo do_shortcode( "[sobeys_ratings_basic id='" . $atts['id'] . "' ]" ); ?>
				<?php endif; ?>
			<?php else : ?>
				<<?php echo $heading_type; ?> class="card-title" id="cardTitle-<?php echo $node_id; ?>">
					<?php echo $title; ?>
				</<?php echo $heading_type; ?>>
			<?php endif; ?>
		<?php endif; ?>

		<?php if ( ( ( ! empty( $show_date ) && $show_date == 'true' ) || ( ! empty( $show_category ) && $show_category == 'true' ) ) && $meta_position == 'below' ) : ?>
			<div class="row">
				<?php if ( ! empty( $show_date ) && $show_date == 'true' && ! empty( $date ) ) : ?>
					<div class='date col-md-6'>
						<?php echo apply_filters( 'get_the_date', $date ); ?>
					</div>
				<?php endif; ?>

				<?php if ( ! empty( $show_category ) && $show_category == 'true' && isset( $first_term_in_category->name ) ) : ?>
					<div class='col-md-6'>
						<span class='category'><?php echo $first_term_in_category->name; ?></span>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $description ) ) : ?>
			<div class="card-text">
				<?php if ( isset( $description_expand ) && $description_expand == 'yes' ) : ?>
					<?php if ( ! empty( $description_expand_limit ) && strlen( $description ) > $description_expand_limit ) : ?>
						<?php $visibleDesc = wp_trim_words( strip_tags( $description ), ceil( $description_expand_limit / 4 ), '' ); ?>
						<?php $hiddenDesc = str_replace( $visibleDesc, '', strip_tags( $description ) ); ?>
						<?php echo $visibleDesc; ?>
						<span class="hellip">&hellip;</span>
						<span class="bs-more-description" aria-expanded="false" aria-hidden="true" id="describe-more-<?php echo $node_id; ?>">
							<?php echo $hiddenDesc; ?>
						</span>
						<button class="no-style expand-description" aria-controls="describe-more-<?php echo $node_id; ?>">
							<span class="screen-reader-text"><?php _e( 'Expand the description', FP_TD ); ?></span>
							<span class="<?php echo $description_expand_icon; ?> icon"></span>
						</button>
						<button class="no-style collapse-description hidden" aria-controls="describe-more-<?php echo $node_id; ?>">
							<span class="screen-reader-text"><?php _e( 'Collapse the description', FP_TD ); ?></span>
							<span class="<?php echo $description_hide_icon; ?> icon"></span>
						</button>
					<?php else : ?>
						<?php echo $description; ?>
					<?php endif; ?>
				<?php else : ?>
					<?php echo $description; ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		<?php if ( ! empty( $text_links ) ) : ?>
			<div class="text-links">
				<?php if ( ! empty( $link_section_title ) ) : ?>
					<<?php echo $link_heading_type; ?>>
						<?php echo $link_section_title; ?>
					</<?php echo $link_heading_type; ?>>
				<?php endif ?>
				<?php foreach ( $text_links as $link ) : ?>
					<?php
					$link_aria = ( isset( $link->link_aria ) && ! empty( $link->link_aria ) ) ? 'aria-label="' . $link->link_aria . '"' : '';
					$link_text = $link->link_title;
					// if the link text is a substring of the aria label, then create screen reader text. otherwise, create an aria label.
					if ( ! empty( $link_aria ) && strstr( $link->link_aria, $link->link_title ) ) {
						$link_aria = '';
						$link_text = $link->link_title . '<span class="screen-reader-text">' . str_replace( $link->link_title, '', $link->link_aria ) . '</span>';
					}
					$linkClass = ( isset( $link->link_style ) && $link->link_style == 'button' ) ? 'button' : '';
					if ( $linkClass == 'button' && ! empty( $link->link_button_theme ) ) {
						$linkClass .= ' ' . $link->link_button_theme;
					} elseif ( ! empty( $link->link_text_theme ) ) {
						$linkClass = $link->link_text_theme;
					}
					?>
					<?php if ( ! empty( $link->link_url ) ) : ?>
						<a href="<?php echo $link->link_url; ?>" target='<?php echo $link->link_url_target; ?>' <?php echo $link_aria; ?> class='<?php echo $linkClass; ?>'>
							<?php echo $link_text; ?>
						</a>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
