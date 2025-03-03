<?php //phpcs:ignore
/**
 * Mega Menu (Sobeys) template file
 *
 * @package fp-foundation
 */

$collapse = 'show';
?>

<nav <?php $this->component_class(); ?> data-js-mega_menu aria-labelledby="mmHeading-<?php echo esc_attr( $node_id ); ?>">

	<?php if ( 'true' === $atts['show-navbar-toggler'] ) : ?>
		<button class="navbar-toggler mm-component-link" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="<?php echo esc_attr( $toggle_menu_aria ); ?>">
			<span class="navbar-toggler-icon transformicon" role="presentation"></span>
		</button>
		<?php $collapse = 'collapse'; ?>
	<?php endif; ?>

	<div class="collapse navbar-collapse <?php echo esc_attr( $collapse ); ?> navbar-<?php echo esc_attr( $collapse ); ?>">
		<?php if ( ! empty( $header_logo ) ) : ?>
		<div class="logo-col">
			<a href="<?php echo esc_url( home_url() ); ?>" aria-label="<?php echo esc_attr__( 'Return to home page', FP_TD ); //phpcs:ignore ?>">
				<?php echo wp_kses_post( wp_get_attachment_image( $header_logo, 'full' ) ); ?>
			</a>
		</div>
		<?php endif; ?>

		<h2 class="screen-reader-text visuallyhidden" id="mmHeading-<?php echo esc_attr( $node_id ); ?>" aria-label="<?php echo esc_attr__( 'Menu: ', FP_TD ) . esc_attr( $menu_heading ); //phpcs:ignore ?>"><?php echo esc_attr( $menu_heading ); ?></h2>
		<ul class="<?php echo esc_attr( $menu ); ?> nav navbar-nav nav-fill <?php echo ( ! empty( $vertical ) && 'true' === $vertical ) ? ' flex-column flex-nowr' : ''; ?>" aria-label="<?php echo esc_attr__( 'Main menu navigation list items', FP_TD ); //phpcs:ignore ?>" data-flyout-card="<?php echo esc_attr( $recipe_card_flyout_width ); ?>" data-flyout-standard="<?php echo esc_attr( $standard_flyout_width ); ?>">
			<?php echo wp_kses( $main_menu, $mm_allowed_tags ); ?>
		</ul>
		<?php
		if ( isset( $mm_language_switcher ) && (bool) $mm_language_switcher && function_exists( 'pll_the_languages' ) ) :
			$languages = pll_the_languages(
				array(
					'echo' => '0',
					'raw'  => '1',
				)
			);
			if ( ! empty( $languages ) ) :
				?>
				<div class="mm-language-switcher <?php echo esc_attr( $mm_language_switcher ); ?>">
				<?php
				if ( ! empty( $mm_language_switcher_text ) ) :
					?>
					<span class="mm-ls-text"><?php echo esc_attr( $mm_language_switcher_text ); ?></span>
					<?php
				endif;
				?>
					<span class="mm-ls-buttons">
					<?php foreach ( $languages as $lang ) : ?>
						<span class="mm-ls-button-item<?php echo ( strtolower( $lang['name'] ) === strtolower( pll_current_language() ) ) ? ' current' : ''; ?>"><a href="<?php echo esc_url( $lang['url'] ); ?>"><?php echo esc_attr( $lang['name'] ); ?></a></span>
					<?php endforeach; ?>
					</span>
				</div>
		<?php endif; ?>
	<?php endif; ?>
	</div>
</nav>
