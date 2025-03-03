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

if ( function_exists( 'generate_theme' ) ) {
	$main_theme      = generate_theme( $settings->main_theme, 'background' );
	$dropdown_theme  = generate_theme( $settings->dropdown_theme, 'background' );
	$mobile_theme    = generate_theme( $settings->mm_mobile_background, 'background' );
	$mobile_dropdown = generate_theme( $settings->mm_mobile_dropdown, 'background' );
}

$display_opts = ( isset( $settings->mm_display ) && is_countable( $settings->mm_display ) ) ? $settings->mm_display : array( '-all' );

if ( isset( $settings->menu_align ) && ! empty( $settings->menu_align ) ) :
	?>
.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item {
	text-align: <?php echo esc_attr( $settings->menu_align ); ?>;
}
	<?php
endif;

// Screen display options.
if ( ! in_array( '-show-mobile', $display_opts ) && ! in_array( '-all', $display_opts ) ) : //phpcs:ignore
	?>
	@media screen and (max-width: 767px) {
		.fl-col.mm-<?php echo esc_attr( $id ); ?>,
		.fl-node-<?php echo esc_attr( $id ); ?>,
		.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu {
			display: none;
		}
	}

	<?php
endif;
if ( ! in_array( '-show-tablet', $display_opts ) && ! in_array( '-all', $display_opts ) ) : //phpcs:ignore
	?>
@media screen and (min-width: 768px) and (max-width: 1024px) {
	.fl-col.mm-<?php echo esc_attr( $id ); ?>,
	.fl-node-<?php echo esc_attr( $id ); ?>,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu {
		display: none;
	}
}
	<?php
endif;
if ( ! in_array( '-show-desktop', $display_opts ) && ! in_array( '-all', $display_opts ) ) : //phpcs:ignore
	?>
@media screen and (min-width: 1025px) {
	.fl-col.mm-<?php echo esc_attr( $id ); ?>,
	.fl-node-<?php echo esc_attr( $id ); ?>,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu {
		display: none;
	}
}
	<?php
endif;
?>

@media screen and (min-width: 768px) {
	<?php
	// Mega menu custom background.
	if ( isset( $settings->mm_background ) && ! empty( $settings->mm_background ) ) :
		?>
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu {
		background-color: #<?php echo esc_attr( $settings->mm_background ); ?>;
	}
	<?php endif; ?>
	<?php
	// Dropdown item border on large.
	if ( isset( $settings->dropdown_item_border_color ) && ! empty( $settings->dropdown_item_border_color ) ) :
		?>

	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .navbar-collapse ul.nav .nav-standard li {
		border-bottom-color: #<?php echo esc_attr( $settings->dropdown_item_border_color ); ?>;
	}
	<?php endif; ?>
}
<?php
if ( isset( $main_theme->default_colour ) ) :
	// primary items.
	?>

.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .navbar-collapse ul.nav li.level-0 {
	color: <?php echo esc_attr( $main_theme->text_colour ); ?>;
	background-color: <?php echo esc_attr( $main_theme->default_colour ); ?>;
}

.fl-node-<?php echo esc_attr( $id ); ?>.sticky-header .component_mega_menu .navbar-collapse ul.nav li.level-0 {
	background: none;
}

.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .navbar-collapse ul.nav li.level-0:hover,
.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .navbar-collapse ul.nav li.level-0.hover,
.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .navbar-collapse ul.nav li.level-0.current,
.fl-node-<?php echo esc_attr( $id ); ?>.sticky-header .component_mega_menu .navbar-collapse ul.nav li.level-0:hover,
.fl-node-<?php echo esc_attr( $id ); ?>.sticky-header .component_mega_menu .navbar-collapse ul.nav li.level-0:hover > a.dropdown-toggle,
.fl-node-<?php echo esc_attr( $id ); ?>.sticky-header .component_mega_menu .navbar-collapse ul.nav li.level-0:hover > a.nav-link,
.fl-node-<?php echo esc_attr( $id ); ?>.sticky-header .component_mega_menu .navbar-collapse ul.nav li.level-0:hover > button.dropdown-toggle,
.fl-node-<?php echo esc_attr( $id ); ?>.sticky-header .component_mega_menu .navbar-collapse ul.nav li.level-0:hover > button.nav-link {
	color: <?php echo esc_attr( $main_theme->text_hover_colour ); ?>;
	background: <?php echo esc_attr( $main_theme->hover_colour ); ?>;
}
.fl-node-<?php echo esc_attr( $id ); ?>.sticky-header .component_mega_menu .navbar-collapse ul.nav li.level-0 > button.nav-link:hover,
.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .navbar-collapse ul.nav li.level-0 > button.nav-link:hover {
	color: <?php echo esc_attr( $main_theme->text_hover_colour ); ?> !important;
}

.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .navbar-collapse ul.nav li.level-0.hover .dropdown-toggle:after,
.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .navbar-collapse ul.nav li.level-0.current .dropdown-toggle:after,
.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .navbar-collapse ul.nav li.level-0:hover .dropdown-toggle:after {
	border-bottom-color: <?php echo esc_attr( $main_theme->text_hover_colour ); ?>;
	border-right-color: <?php echo esc_attr( $main_theme->text_hover_colour ); ?>;
}

.fl-node-<?php echo esc_attr( $id ); ?>.sticky-header .component_mega_menu .navbar-collapse ul.nav li.level-0.menu-item-has-children a.dropdown-toggle:after,
.fl-node-<?php echo esc_attr( $id ); ?>.sticky-header .component_mega_menu .navbar-collapse ul.nav li.level-0.menu-item-has-children button.dropdown-toggle:after {
	border-bottom-color: <?php echo esc_attr( $main_theme->text_colour ); ?>;
	border-right-color: <?php echo esc_attr( $main_theme->text_colour ); ?>;
}
.fl-node-<?php echo esc_attr( $id ); ?>.sticky-header .component_mega_menu .navbar-collapse ul.nav li.level-0.menu-item-has-children:hover a.dropdown-toggle:after,
.fl-node-<?php echo esc_attr( $id ); ?>.sticky-header .component_mega_menu .navbar-collapse ul.nav li.level-0.menu-item-has-children:hover button.dropdown-toggle:after {
	border-bottom-color: <?php echo esc_attr( $main_theme->text_hover_colour ); ?>;
	border-right-color: <?php echo esc_attr( $main_theme->text_hover_colour ); ?>;
}

	<?php
endif;

if ( isset( $dropdown_theme->default_colour ) ) :
	// Dropdown items.
	?>

.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .dropdown-menu,
.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item {
	background-color: <?php echo esc_attr( $dropdown_theme->default_colour ); ?>;
	color: <?php echo esc_attr( $dropdown_theme->text_colour ); ?>;

}

.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu:not(.-vertical) .navbar-collapse ul.navbar-nav .nav-grid {
	background-color: <?php echo esc_attr( $dropdown_theme->default_colour ); ?>;
}

@media screen and (min-width: 768px) {
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item.show.current,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item.hover.current,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.is-tabbing.current,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item.show.current > li.hover,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item.show.current > li:hover,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item.hover,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item:hover {
		background-color: <?php echo esc_attr( $dropdown_theme->hover_colour ); ?>;
		color: <?php echo esc_attr( $dropdown_theme->text_hover_colour ); ?>;
	}
}

	<?php
endif;

// Custom mobile themeing if different than desktop.
?>

@media screen and (max-width: 767px) {
<?php
if ( isset( $mobile_theme->default_colour ) && ! empty( $mobile_theme->default_colour ) ) :
	?>
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu:not(.-vertical) .navbar-collapse ul.navbar-nav .nav-grid {
		background-color: <?php echo esc_attr( $mobile_theme->default_colour ); ?>;
	}
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu button.navbar-toggler .transformicon:before,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu button.navbar-toggler .transformicon:after {
		background: <?php echo esc_attr( $mobile_theme->text_colour ); ?>;
	}
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .navbar-collapse ul.nav li.level-0,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .navbar-collapse ul.nav li.level-0 a.nav-link,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .navbar-collapse ul.nav li.level-0 button.nav-link,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .dropdown-menu,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .mm-language-switcher span.mm-ls-button-item a,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu button.navbar-toggler:hover {
		color: <?php echo esc_attr( $mobile_theme->text_colour ); ?>;
		background-color: <?php echo esc_attr( $mobile_theme->default_colour ); ?>;
	}

	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .navbar-collapse ul.nav li.level-0.current,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .navbar-collapse ul.nav li.level-0:hover,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .navbar-collapse ul.nav li.level-0.hover,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item.show.current,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item.hover.current,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.is-tabbing.current,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item.show.current > li.hover,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item.show.current > li:hover,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item.hover,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item:hover,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .mm-language-switcher span.mm-ls-button-item a:hover {
		color: <?php echo esc_attr( $mobile_theme->text_hover_colour ); ?>;
		background-color: <?php echo esc_attr( $mobile_theme->hover_colour ); ?>;
	}

	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .navbar-collapse ul.nav li.level-0.hover .dropdown-toggle:after,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .navbar-collapse ul.nav li.level-0.current .dropdown-toggle:after,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .navbar-collapse ul.nav li.level-0:hover .dropdown-toggle:after {
		border-bottom-color: <?php echo esc_attr( $mobile_theme->text_hover_colour ); ?>;
		border-right-color: <?php echo esc_attr( $mobile_theme->text_hover_colour ); ?>;
	}

	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .mm-language-switcher {
		color: <?php echo esc_attr( $mobile_theme->text_colour ); ?>;
	}
	<?php
endif;
if ( isset( $settings->mobile_item_border_color ) && ! empty( $settings->mobile_item_border_color ) ) :
	?>
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu li.level-0 {
		border-bottom: 1px solid #<?php echo esc_attr( $settings->mobile_item_border_color ); ?>;
	}
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .mm-language-switcher span.mm-ls-button-item,
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .mm-language-switcher span.mm-ls-text {
		border-right: 1px solid #<?php echo esc_attr( $settings->mobile_item_border_color ); ?>;
	}
	<?php
endif;

if ( isset( $mobile_dropdown->default_colour ) && ! empty( $mobile_dropdown->default_colour ) ) :
	?>
		.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item {
			background-color: <?php echo esc_attr( $mobile_dropdown->default_colour ); ?>;
			color: <?php echo esc_attr( $mobile_dropdown->text_colour ); ?>;
		}
		.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item.show.current,
		.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item.hover.current,
		.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.is-tabbing.current,
		.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item.show.current > li.hover,
		.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item.show.current > li:hover,
		.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item.hover,
		.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.dropdown-menu li.nav-item:hover {
			background-color: <?php echo esc_attr( $mobile_dropdown->hover_colour ); ?>;
			color: <?php echo esc_attr( $mobile_dropdown->text_hover_colour ); ?>;
		}
	<?php
endif;
?>
}

<?php
// Large screen desktop layout option.
?>
@media screen and (min-width: 768px) {
<?php
if ( ! empty( $settings->dropdown_menu_width ) ) :
	?>
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu:not(.-vertical) .nav-grid {
		width: <?php echo esc_attr( 2 * ( $settings->dropdown_menu_width + 58 ) ); ?>px;
	}
	<?php
endif;
if ( ! empty( $settings->dropdown_menu_width ) ) :
	?>
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu:not(.-vertical) .nav-grid .dropdown-menu {
		width: <?php echo esc_attr( $settings->dropdown_menu_width + 58 ); ?>px;
	}
	<?php
endif;
if ( ! empty( $settings->dropdown_menu_width ) ) :
	?>
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu:not(.-vertical) .nav-grid .level-1 .dropdown-menu {
		left: <?php echo esc_attr( $settings->dropdown_menu_width + 56 ); ?>px;
	}
	<?php
endif;
?>
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu ul.nav>li .dropdown-menu li a {
		text-align: <?php echo esc_attr( $settings->menu_align ); ?>;
	}
<?php
if ( ! empty( $settings->dropdown_menu_width ) ) :
	?>
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu:not(.-vertical) .navbar-collapse ul.nav .nav-standard {
		min-width: <?php echo esc_attr( $settings->dropdown_menu_width + 58 ); ?>px;
	}
	<?php
endif;
if ( ! empty( $settings->dropdown_level1_left ) && 0 !== (int) $settings->dropdown_level1_left ) :
	?>
	.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu:not(.-vertical) .navbar-collapse ul.nav > li > div {
		left: <?php echo esc_attr( $settings->dropdown_level1_left ); ?>px;
	}
	<?php
endif;
?>
}
<?php
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'main_theme_padding',
		'selector'     => ".fl-node-$id .component_mega_menu .navbar-collapse ul.nav li.level-0 a.nav-link, .fl-node-$id .component_mega_menu .navbar-collapse ul.nav li.level-0 button.nav-link",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'main_theme_padding_top',
			'padding-right'  => 'main_theme_padding_right',
			'padding-bottom' => 'main_theme_padding_bottom',
			'padding-left'   => 'main_theme_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'dropdown_level1_padding',
		'selector'     => ".fl-node-$id .component_mega_menu .navbar-collapse ul.nav li.level-1 > a.dropdown-item",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'dropdown_level1_padding_top',
			'padding-right'  => 'dropdown_level1_padding_right',
			'padding-bottom' => 'dropdown_level1_padding_bottom',
			'padding-left'   => 'dropdown_level1_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'main_theme_padding',
		'selector'     => ".fl-node-$id .component_mega_menu .mm-language-switcher",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'main_theme_padding_top',
			'padding-right'  => 'main_theme_padding_right',
			'padding-bottom' => 'main_theme_padding_bottom',
			'padding-left'   => 'main_theme_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'dropdown_level2_padding',
		'selector'     => ".fl-node-$id .component_mega_menu .navbar-collapse ul.nav li.level-2 > a.dropdown-item",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'dropdown_level2_padding_top',
			'padding-right'  => 'dropdown_level2_padding_right',
			'padding-bottom' => 'dropdown_level2_padding_bottom',
			'padding-left'   => 'dropdown_level2_padding_left',
		),
	)
);

FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'dropdown_level2_padding',
		'selector'     => ".fl-node-$id .component_mega_menu .navbar-collapse ul.nav li.menu-item-has-children button.multi-level-expand",
		'unit'         => 'px',
		'props'        => array(
			'top' => 'dropdown_level2_padding_top', // As in $settings->padding_top.
		),
	)
);

// Custom flyout styling.
if ( ! empty( $settings->standard_flyout_width ) ) :
	?>
.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .sidemenu:not(.recipe-card) {
	width: <?php echo esc_attr( $settings->standard_flyout_width ); ?>px;
}
	<?php
endif;

if ( ! empty( $settings->recipe_card_flyout_width ) ) :
	?>
.fl-node-<?php echo esc_attr( $id ); ?> .component_mega_menu .sidemenu.recipe-card {
	width: <?php echo esc_attr( $settings->recipe_card_flyout_width ); ?> px;
}
	<?php
endif;
