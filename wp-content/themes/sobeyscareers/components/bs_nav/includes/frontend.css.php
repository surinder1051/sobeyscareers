<?php
/**
 * This file should contain frontend styles that
 * will be applied to individual module instances.
 *
 * @package fp-foundation
 *
 * You have access to three variables in this file:
 *
 * $module An instance of your module class.
 * $id The module's ID.
 * $settings The module's settings.
 *
 * Note: When used from beaver builder, a cached version of this file will be
 * created that's unique to the instance in the /uploads/bb-plugin/cache/
 * ,however when used by a regular shortcode an inline style will in turn be
 * generated and put on the page where it's been used, no cached file will be
 * created.
 *
 * ** Examples: **

 * To use a active theme that can be updated via Options page for a XXX field you need to generate it at runtime.
 * element can be ('element'     => 'a | button | h1 | h2 | h3 | h4 | h5 | h6 | background',)
 * $settings->field_key = generate_theme($settings->field_key, element);
 * $settings->field_key->default_colour
 * $settings->field_key->hover_colour
 * $settings->field_key->text_colour
 * $settings->field_key->text_hover_colour
 *
 * FLBuilderCSS::typography_field_rule(array(
 * 'settings'     => $settings,
 * 'setting_name' => 'title_typography',
 * 'selector'     => 'body .fl-node-' . $id . ' .title',
 * ));
 * fp_apply_style($id, '.card-title', 'color', $settings->title_color);
 */

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'title_typography',
		'selector'     => 'body .fl-node-' . $id . ' .title',
	)
);

FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'item_typography',
		'selector'     => 'body .fl-node-' . $id . ' .nav-link',
	)
);
?>

<?php if ( ! empty( $settings->link_color ) ) : ?>
	.fl-node-<?php echo $id; ?> .navbar .navbar-nav li a.nav-link {
	color: #<?php echo $settings->link_color; ?>;
	}
<?php endif; ?>

<?php if ( ! empty( $settings->hover_color ) ) : ?>
	.fl-node-<?php echo $id; ?> .navbar .navbar-nav li a.nav-link:hover {
	color: #<?php echo $settings->hover_color; ?>;
	}
<?php endif; ?>

<?php if ( ! empty( $settings->heading_color ) ) : ?>
	.fl-node-<?php echo $id; ?> .navbar .navbar .title {
		color: #<?php echo $settings->heading_color; ?>;
	}
	@media screen and (max-width: 768px) {
		.fl-node-<?php echo $id; ?> .navbar .navbar .title:after {
			border-color: #<?php echo $settings->heading_color; ?>;
		}
	}
<?php endif; ?>

<?php if ( ! empty( $settings->disable_mobile_arrows ) && ( $settings->disable_mobile_arrows === 'true' ) ) : ?>
	.fl-node-<?php echo $id; ?> .navbar .navbar .title:after {
		content: none !important;
	}
<?php endif; ?>
