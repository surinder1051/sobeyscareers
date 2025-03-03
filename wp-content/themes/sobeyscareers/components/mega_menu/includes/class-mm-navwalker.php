<?php
/**
 * Custom navwalker for the mega menu so first level "Buttons" are a button element.
 *
 * @package FP_TD
 */

namespace fp\components;

use fp;

if ( ! class_exists( 'MM_Navwalker' ) ) {
	/**
	 * Customize the navwalker for Accessibility (sobeys)
	 */
	class MM_Navwalker extends \Walker_Nav_Menu {
		/**
		 * Starts the list before the elements are added.
		 *
		 * @since WP 3.0.0
		 *
		 * @see Walker_Nav_Menu::start_lvl()
		 *
		 * @param string   $output Used to append additional content (passed by reference).
		 * @param int      $depth  Depth of menu item. Used for padding.
		 * @param stdClass $args   An object of wp_nav_menu() arguments.
		 */
		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}
			$indent  = str_repeat( $t, $depth );
			$classes = array();

			// Default class to add to the file.
			if ( ! isset( $args->dropdown ) || false !== $args->dropdown ) {
				$classes = array( 'dropdown-menu' );
			}

			/**
			 * Filters the CSS class(es) applied to a menu list element.
			 *
			 * @since WP 4.8.0
			 *
			 * @param array    $classes The CSS classes that are applied to the menu `<ul>` element.
			 * @param stdClass $args    An object of `wp_nav_menu()` arguments.
			 * @param int      $depth   Depth of menu item. Used for padding.
			 */
			$class_names = join( ' ', apply_filters( 'nav_menu_submenu_css_class', $classes, $args, $depth ) );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
			/**
			 * The `.dropdown-menu` container needs to have a labelledby
			 * attribute which points to it's trigger link.
			 *
			 * Form a string for the labelledby attribute from the the latest
			 * link with an id that was added to the $output.
			 */
			$labelledby = '';

			// Find all links with an id in the output.
			preg_match_all( '/(<(a|button).*?id=\"|\')(.*?)\"|\'.*?>/im', $output, $matches );

			// With pointer at end of array check if we got an ID match.
			if ( end( $matches[2] ) ) {
				// build a string to use as aria-labelledby.
				$labelledby = 'aria-labelledby="' . end( $matches[3] ) . '"';
			}
			$output .= "{$n}{$indent}<ul$class_names $labelledby aria-label=\"" . __( 'Sub menu navigation list', FP_TD ) . "\">{$n}"; //phpcs:ignore
		}

		/**
		 * Starts the element output.
		 *
		 * @since WP 3.0.0
		 * @since WP 4.4.0 The {@see 'nav_menu_item_args'} filter was added.
		 *
		 * @see Walker_Nav_Menu::start_el()
		 *
		 * @param string   $output Used to append additional content (passed by reference).
		 * @param WP_Post  $item   Menu item data object.
		 * @param int      $depth  Depth of menu item. Used for padding.
		 * @param stdClass $args   An object of wp_nav_menu() arguments.
		 * @param int      $id     Current item ID.
		 */
		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

			$skip_el = apply_filters( 'bs_walker_el', false, $item, $args );
			if ( $skip_el ) {
				return;
			}

			if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
				$t = '';
				$n = '';
			} else {
				$t = "\t";
				$n = "\n";
			}

			if ( isset( $item->classes ) && in_array( 'regionalize', $item->classes ) ) { // phpcs:ignore.
				$can_show_regionalized_content = apply_filters( 'can_show_regionalized_content', $item->ID );

				if ( ! $can_show_regionalized_content ) {
					return;
				}
			}

			$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

			$classes = empty( $item->classes ) ? array() : (array) $item->classes;

			/**
			 * Filters the arguments for a single nav menu item.
			 *
			 *  WP 4.4.0
			 *
			 * @param stdClass $args  An object of wp_nav_menu() arguments.
			 * @param WP_Post  $item  Menu item data object.
			 * @param int      $depth Depth of menu item. Used for padding.
			 */
			$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

			// Add .dropdown or .active classes where they are needed.
			if ( isset( $args->has_children ) && $args->has_children && ( ! isset( $args->dropdown ) || false !== $args->dropdown ) ) {
				$classes[] = 'dropdown';
			}
			if ( in_array( 'current-menu-item', $classes ) || in_array( 'current-menu-parent', $classes ) ) { // phpcs:ignore.
				$classes[] = 'active';
			}

			// Add some additional default classes to the item.
			$classes[] = 'menu-item-' . $item->ID;
			$classes[] = 'nav-item';
			$classes[] = 'level-' . $depth;

			// Allow filtering the classes.
			$classes = apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth );

			// Form a string of classes in format: class="class_names".
			$class_names = join( ' ', $classes );
			$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

			/**
			 * Filters the ID applied to a menu item's list item element.
			 *
			 * @since WP 3.0.1
			 * @since WP 4.1.0 The `$depth` parameter was added.
			 *
			 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
			 * @param WP_Post  $item    The current menu item.
			 * @param stdClass $args    An object of wp_nav_menu() arguments.
			 * @param int      $depth   Depth of menu item. Used for padding.
			 */
			$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
			$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

			$output .= $indent . '<li itemscope="itemscope" itemtype="https://www.schema.org/SiteNavigationElement"' . $id . $class_names . ' tabindex="-1">';

			// initialize array for holding the $atts for the link item.
			$atts = array();

			// Set title from item to the $atts array - if title is empty then
			// default to item title.

			if ( function_exists( 'get_field' ) ) {
				$aria_label         = get_field( 'aria_label', $item );
				$atts['aria-label'] = $aria_label;
			}

			$atts['target'] = ! empty( $item->target ) ? $item->target : '';
			$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';

			// If item has_children add atts to <a>.
			if ( 0 === (int) $item->menu_item_parent && in_array( 'menu-item-has-children', $item->classes ) ) { //phpcs:ignore
				$atts['href']        = $item->url;
				$atts['data-url']    = $item->url;
				$atts['data-toggle'] = 'dropdown';
				$atts['class']       = 'dropdown-toggle nav-link mm-component-link';
				$atts['id']          = 'menu-item-dropdown-' . $item->ID;
				$atts['tag']         = 'button';
			} else {
				$atts['href'] = ! empty( $item->url ) ? $item->url : '#';
				$atts['tag']  = ( '#' === $atts['href'] ) ? 'button' : 'a';
				// Items in dropdowns use .dropdown-item instead of .nav-link.
				if ( $depth > 0 ) {
					$atts['class'] = 'dropdown-item mm-component-link';
				} else {
					$atts['class'] = 'nav-link mm-component-link';
				}
			}

			// Allow filtering of the $atts array before using it.
			$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

			// Build a string of html containing all the atts for the item.
			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( 'tag' !== $attr && ! empty( $value ) ) {
					if ( 'a' === $atts['tag'] && ( 'href' === $attr || 'target' === $attr ) ) {
						$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
						$attributes .= ' ' . $attr . '="' . $value . '"';
					} else {
						$value       = esc_attr( $value );
						$attributes .= ' ' . $attr . '="' . $value . '"';
					}
				}
			}

			if ( in_array( 'current_page_item', $classes ) || in_array( 'current-menu-item', $classes ) ) { //phpcs:ignore
				$attributes .= ' aria-current="page"';
			}

			// START appending the internal item contents to the output.
			$item_output  = isset( $args->before ) ? $args->before : '';
			$item_output .= '<' . $atts['tag'] . $attributes . '>';

			/**
			 * Initiate empty icon var, then if we have a string containing any
			 * icon classes form the icon markup with an <i> element. This is
			 * output inside of the item before the $title (the link text).
			 */
			$icon_html = '';

			// Get icon field from.
			if ( function_exists( 'get_field' ) ) {
				$icon_class_string    = get_field( 'icon', $item );
				$icon_class_font_size = get_field( 'icon_font_size', $item );
				$aria_label           = get_field( 'item_aria_label', $item );
			}

			if ( ! empty( $icon_class_string ) ) {
				// Append an <span> with the icon classes to what is output before links.
				if ( isset( $icon_class_font_size ) && ! empty( $icon_class_font_size ) ) {
					$icon_class_font_size = "style='font-size: {$icon_class_font_size}px' ";
				}
				$icon_html = '<span class="' . esc_attr( $icon_class_string ) . "\" $icon_class_font_size aria-hidden=\"true\"></span> ";
			}

			// This filter is documented in wp-includes/post-template.php.
			if ( isset( $item->title ) ) {
				$title = apply_filters( 'the_title', $item->title, $item->ID );
			} elseif ( isset( $item->post_title ) ) {
				$title = apply_filters( 'the_title', $item->post_title, $item->ID );
			}

			/**
			 * Filters a menu item's title.
			 *
			 * @since WP 4.4.0
			 *
			 * @param string   $title The menu item's title.
			 * @param WP_Post  $item  The current menu item.
			 * @param stdClass $args  An object of wp_nav_menu() arguments.
			 * @param int      $depth Depth of menu item. Used for padding.
			 */
			$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

			// Put the item contents into $output.
			if ( isset( $args->link_before ) && ! empty( $args->link_before ) ) {
				$item_output .= isset( $args->link_before ) ? $args->link_before . $icon_html . $title . $args->link_after : '';
			} else {
				// code...
				$item_output .= $icon_html . $title;
			}

			$item_output .= '</' . $atts['tag'] . '>';

			if ( isset( $args->vertical_expand_buttons ) && in_array( 'dropdown', $classes ) ) { // phpcs:ignore.
				$icon_class    = ( in_array( 'active', $classes ) ) ? $args->vertical_expand_buttons['close-icon'] : $args->vertical_expand_buttons['open-icon']; // phpcs:ignore.
				$item_output .= '<button class="bs-nav-expander" aria-controls="menu-item-' . $item->ID . '" data-open-icon="' . $args->vertical_expand_buttons['open-icon'] . '" data-close-icon="' . $args->vertical_expand_buttons['close-icon'] . '" aria-label="' . __( 'Expand menu items', 'FP_TD' ) . '">';
				$item_output .= '<span class="' . $icon_class . '"></span>';
				$item_output .= '</button>';
			}

			// For accessibility, we put the flyout data in a screen reader element so it can be read in the correct order.
			if ( 1 === (int) $depth && function_exists( 'get_field' ) ) {
				$access_description = '';
				$flyout_description = get_field( 'description', $item );
				$flyout_head_title  = get_field( 'header_title', $item );
				$flyout_heading     = get_field( 'heading', $item );
				$flyout_title       = get_field( 'title', $item );

				if ( ! empty( $flyout_head_title ) ) {
					$access_description = ( $item->title !== $flyout_head_title ) ? $flyout_head_title : '';
				} elseif ( ! empty( $flyout_heading ) ) {
					$access_description = ( $item->title !== $flyout_heading ) ? $flyout_heading : '';
				} elseif ( ! empty( $flyout_title ) ) {
					$access_description = ( $item->title !== $flyout_title ) ? $flyout_title : '';
				}
				if ( ! empty( $description ) ) {
					if ( ! empty( $access_description ) ) {
						$access_description .= ': ' . $description;
					} else {
						$access_description = $description;
					}
				}
				if ( ! empty( $access_description ) ) {
					$item_output .= '<span class="screen-reader-text">' . $access_description . '</span>';
				}
			}

			$item_output .= isset( $args->after ) ? $args->after : '';

			// END appending the internal item contents to the output.
			$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
		}
	}
}
