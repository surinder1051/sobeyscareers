<div <?php $this->component_class(); ?> data-js-header_logo_search>
	<button class="mob_menu_toggle" aria-label="<?php echo __( 'Click to view the main menu', FP_TD ); ?>">
		<span></span>
	</button>
	<?php if ( ! empty( $header_logo ) || ! empty( $logo_image ) ) : ?>
		<div class="logo-col">
			<a href="<?php echo ( function_exists( 'pll_current_language' ) ? '/' . pll_current_language() : '/' ); ?>" aria-label="<?php _e( 'Return to home page', FP_TD ); ?>" id="search-col-<?php echo $node_id; ?>">

			<?php if ( ! empty( $logo_image ) ) : ?>
				<?php echo wp_get_attachment_image( $logo_image, 'full' ); ?>
			<?php else : ?>
				<span class='main-logo <?php echo $header_logo; ?>'></span>
			<?php endif; ?>

			</a>
		</div>
	<?php endif; ?>
	<?php if ( isset( $show_search ) && $show_search == true ) : ?>
	<button class="mob_search_toggle" data-js-mob_search_toggle role="button" aria-label='<?php _e( 'Click to access site search form', FP_TD ); ?>'>
		<span class='<?php echo $search_button_icon; ?>'></span>
	</button>
	<?php endif; ?>
	<div class="search-col" id="search-col-<?php echo $node_id; ?>">
		<?php if ( isset( $show_search ) && $show_search == true ) : ?>
			<form id='search' class="header-search" action='<?php echo ( function_exists( 'pll_current_language' ) ? '/' . pll_current_language() . '/' : '/' ); ?>' role="search">
				<label for="headerSearch_s" class="search-label">
					<?php echo apply_filters( 'search_placeholder', __( 'Search', FP_TD ) ); ?>
				</label>
				<input class="search-input" type="search" name='s' id="headerSearch_s" value="<?php echo is_search() ? get_search_query() : ''; ?>">
				<input class="form-control mr-sm-2" type="hidden" name='fwp_keyword_search'>
				<div class="invalid-feedback" role="status"><?php echo apply_filters( 'invalid_search', __( 'Invalid Search', FP_TD ) ); ?></div>

				<?php if ( function_exists( 'pll_current_language' ) ) : ?>
					<input type="hidden" name='lang' value='<?php echo pll_current_language(); ?>'>
				<?php endif; ?>

				<?php if ( isset( $show_search_close ) && (bool) $show_search_close ) : ?>
				<button class="search-btn close" type="button"><span class="icon-close"></span></button>
				<?php endif; ?>
				<button class="search-btn" type="submit">
					<span class='<?php echo $search_button_icon; ?>'></span>
					<span class="screen-reader-text">
						<?php echo __( 'Submit', FP_TD ); ?>
					</span>
				</button>
			</form>
		<?php endif; ?>
		<?php
		if ( (bool) $trending_links ) {
			echo do_shortcode( '[fl_builder_insert_layout slug="trending-now"]' );
		}
		?>
	</div>
</div>
