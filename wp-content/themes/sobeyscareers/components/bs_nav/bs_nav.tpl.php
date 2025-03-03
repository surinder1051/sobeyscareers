<?php
/**
 * Component template file
 *
 * @package fp-foundation
 */

$collapse = '';
?>
<nav <?php $this->component_class(); ?> data-js-bs_nav aria-labelledby="bsnavHeading-<?php echo esc_attr( $module->node ); ?>">

	<div class="container">

		<?php if ( ! empty( $atts['show-navbar-toggler'] ) && 'true' === $atts['show-navbar-toggler'] ) : ?>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="<?php echo esc_attr__( 'Click to view menu', FP_TD ); ?>">
				<span class="navbar-toggler-icon" role="presentation"></span>
			</button>
			<?php $collapse = 'collapse'; ?>
		<?php endif; ?>

		<?php if ( ! empty( $atts['brand_content'] ) ) : ?>
			<?php echo apply_filters( 'the_content', $atts['brand_content'] ); ?>
		<?php endif; ?>

		<div class="<?php echo esc_attr( $collapse ); ?> navbar navbar-<?php echo esc_attr( $collapse ); ?>">
			<?php if ( ! empty( $title ) ) : ?>
			<<?php echo esc_attr( $title_tag ); ?> class="title<?php echo esc_attr( ( 'false' === $title_visible ) ? ' screen-reader-text visuallyhidden' : '' ); ?>" id="bsnavHeading-<?php echo esc_attr( $module->node ); ?>" aria-label="<?php echo esc_attr__( 'Menu', FP_TD ); ?>: <?php echo $title; ?>">
				<?php _e( $title, FP_TD ); ?>
			</<?php echo esc_attr( $title_tag ); ?>>
			<?php endif; ?>
			<ul class="<?php echo esc_attr( $atts['menu'] ); ?> navbar-nav <?php echo ( ! empty( $atts['vertical'] ) && 'true' === $atts['vertical'] ) ? 'nav flex-column flex-nowr' : ''; ?>">

				<?php
				$menu_args = array(
					'theme_location' => $atts['menu'],
					'menu_id'        => $atts['menu'],
					'menu_class'     => 'nav',
					'items_wrap'     => '%3$s',
					'fallback_cb'    => false,
					'container'      => false,
				);

				if ( isset( $enable_vertical_collapse ) && 'yes' === $enable_vertical_collapse ) {
					if ( isset( $vertical_open_icon ) && isset( $vertical_close_icon ) ) {
						$menu_args['vertical_expand_buttons'] = array(
							'open-icon'  => $vertical_open_icon,
							'close-icon' => $vertical_close_icon,
						);
					}
				}
				wp_nav_menu( $menu_args );

				?>
			</ul>
			<?php if ( ! empty( $atts['show_search'] ) && 'ture' === $atts['show_search'] ) : ?>
				<form class="form-inline my-2 my-lg-0">
					<label for="mr-sm-2-<?php echo esc_attr( $module->node ); ?>" class="screen-reader-text visuallyhidden"><?php echo esc_html__( 'Enter search keywords', FP_TD ); ?></label>
					<input class="form-control mr-sm-2" type="search" placeholder="<?php echo esc_attr__( 'Search', FP_TD ); ?>" id="mr-sm-2-<?php echo esc_attr( $module->node ); ?>">
					<button class="btn btn-outline-success my-2 my-sm-0" type="submit"><?php echo esc_html__( 'Search', FP_TD ); ?></button>
				</form>
			<?php endif; ?>
		</div>
	</div>
</nav>
