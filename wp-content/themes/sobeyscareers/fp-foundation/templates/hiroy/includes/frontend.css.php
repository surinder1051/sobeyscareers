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

?>

.fl-node-<?php echo esc_attr( $id ); ?> {
<?php if ( isset( $settings->fp_hiroy_background_color ) && ! empty( $settings->fp_hiroy_background_color ) ) : ?>
	background-color: #<?php echo esc_attr( $settings->fp_hiroy_background_color ); ?>;
<?php endif; ?>

<?php if ( isset( $settings->theme ) && 'Custom' === $settings->theme ) : ?>
	color: red;
	font-weight: bold;
<?php endif; ?>
}

<?php
/**
 * ** TO EXTEND THIS FILE: **
 */
if ( file_exists( trailingslashit( __DIR__ ) . '/extend-frontend.css.php' ) ) :
	include trailingslashit( __DIR__ ) . '/extend-frontend.css.php';
endif;
