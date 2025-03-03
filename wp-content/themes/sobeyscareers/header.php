<?php

/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package fp
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<link rel="shortcut icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/assets/img/favicon.ico">
	<?php if ( defined( 'GOOGLE_SITE_VERIFICATION' ) ) { ?>
		<meta name="google-site-verification" content="<?php echo GOOGLE_SITE_VERIFICATION; ?>" />
	<?php } ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php do_action( 'gtm_print_noscript_tag' ); ?>
	<a href="#content" class="screen-reader-text"><?php _e( 'Skip to Content', FP_TD ); ?></a>
	<header>
		<?php if ( ! isset( $post ) || get_post_meta( $post->ID, 'disable_default_header__footer', true ) !== '1' ) : ?>
			<?php echo do_shortcode( '[fl_builder_insert_layout slug="food-alert"]' ); ?>
			<?php echo do_shortcode( '[fl_builder_insert_layout slug="header-top"]' ); ?>
			<?php echo do_shortcode( '[fl_builder_insert_layout slug="header-brand"]' ); ?>
			<?php if ( ! is_front_page() ) : ?>
				<?php echo do_shortcode( '[fl_builder_insert_layout slug="header-breadcrumbs"]' ); ?>
			<?php endif; ?>
		<?php endif; ?>
	</header>

	<div id="page" class="site">
		<div id="content" class="site-content">