<?php //phpcs:ignore
/**
 * FP Foundation custom component.
 *
 * @package fp-foundation
 */

namespace fp\components;

use fp;

if ( file_exists( trailingslashit( __DIR__ ) . 'includes/class-mm-navwalker.php' ) ) {
	require_once trailingslashit( __DIR__ ) . 'includes/class-mm-navwalker.php';
}

if ( class_exists( 'fp\Component' ) ) {
	/**
	 * Mega menu for Sobeys
	 */
	class mega_menu extends fp\Component { //phpcs:ignore

		/**
		 * The current module version
		 *
		 * @var string $version
		 */
		public $version = '1.6.13a';
		/**
		 * This needs to be updated manually when we make changes to the this template so we know if it was updated when foundation was updated.
		 * This should be the foundation version.
		 *
		 * @var string $schema_version
		 */
		public $schema_version = 8;
		/**
		 * The component slug - should be same as this file base name for the custom tpl to override the BB frontend.php file.
		 *
		 * @var string $component
		 */
		public $component = 'mega_menu';
		/**
		 * The human readable name used in module selection in admin, and in page edit mode.
		 *
		 * @var string $component_name
		 */
		public $component_name = 'Mega Menu with Flyout';
		/**
		 * The short description of what this module does.
		 *
		 * @var string $component_description
		 */
		public $component_description = 'Show a mega menu with custom flyout options';
		/**
		 * How this module is categorized in page edit mode. This is important and should be updated properly.
		 *
		 * @var string $component_category
		 */
		public $component_category = 'FP Navigation';
		/**
		 * Should foundation load the component css file.
		 * It's important to disable if not being used for better performance.
		 *
		 * @var bool $enable_css
		 */
		public $enable_css = true;
		/**
		 * Should foundation load the component js file.
		 * It's important to disable if not being used for better performance.
		 *
		 * @var bool $enable_js
		 */
		public $enable_js = true;
		/**
		 * Should foundation load any dependencies for this file.
		 *
		 * @var array $deps_css
		 */
		public $deps_css = array();
		/**
		 * Should foundation load any dependencies for this file.
		 * Always include jQuery here, if the module uses jquery.
		 *
		 * @var array $deps_js
		 */
		public $deps_js = array( 'jquery', 'what-input' );

		/**
		 * Should foundation defer loading of the component css file.
		 * Turn off for mega menus, and hero components if they're typically above the fold.
		 *
		 * @var bool $defer_css
		 */
		public $defer_css = false;
		/**
		 * Should foundation defer loading of the component js file.
		 * Turn off for mega menus, and hero components if they're typically above the fold.
		 *
		 * @var bool $defer_js
		 */
		public $defer_js = false;

		/**
		 * Should a theme js file override the main component js file. If false, a concatenated file is used.
		 *
		 * @see self::extend_js_theme()
		 *
		 * @var bool $theme_override_js
		 */
		public $theme_override_js = false;

		/**
		 * Used for autoloading
		 *
		 * @var string $base_dir
		 */
		public $base_dir = __DIR__;
		/**
		 * Initialize the component BB fields array
		 *
		 * @var array $fields
		 */
		public $fields = array();
		/**
		 * Initialize the bb module config variable.
		 *
		 * @var array $bbconfig
		 */
		public $bbconfig = array();
		/**
		 * List any user display variants as per -> http://rscss.io/variants.html
		 *
		 * @var array $variants
		 */
		public $variants = array( '-vertical', '-show-desktop', '-show-mobile', '-show-tablet' );
		/**
		 * Create a settings tab in BB to pull posts by post type, and number of post
		 * Generates $atts[posts] object with dynamically populated data. Use/edit any of the following parameters by adding them to the array.
		 *
		 * 'pagination_api' => true, // enable ajax pagination
		 * 'posts_per_page_default' => '3',
		 * 'posts_per_page_options' => array( '1' => 1, '2' => 3, '3' => 3, ),
		 * 'post_types' => array('post','page'),
		 * 'max_overwrites' => 9, // enable custom select of posts and overwrite the default.-
		 * 'taxonomies' => array( array( 'category' => array() ), array('post_tag' => array( 'none-option' => true ) ),
		 * 'order' => 'DESC',
		 * 'orderby' => 'menu_order',
		 * 'fetch_taxonomies' => true // Return the taxonomies in the post data for display.
		 *
		 * @var bool $load_in_header
		 */
		public $dynamic_data_feed_parameters = array();

		/**
		 * Are there any remote css files to load.
		 * Uncomment to use.
		 * eg: 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'
		 *
		 * @var array $deps_css_remote
		 *
		 * public $deps_css_remote = array();
		 */

		/**
		 * Are there any remote js files to load.
		 * Uncomment to use.
		 * eg: 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js'
		 *
		 * @var array $deps_js_remote
		 *
		 * public $deps_js_remote = array();
		 */

		/**
		 * This will allow for testin iframes to be set at this height automatically.
		 * Uncomment if using the testing framework.
		 *
		 * @var integer $min_height
		 *
		 * public $min_height = 600;
		 */

		/**
		 * Exclude content of this module from being saved to post_content field
		 * Uncomment if using the testing framework.
		 *
		 * @var bool $exclude_from_post_content
		 *
		 * public $exclude_from_post_content = false;
		 */

		/**
		 * Exclude content of this module from being saved to post_content field
		 * Uncomment if using the testing framework.
		 *
		 * @var bool $load_in_header
		 *
		 * public $load_in_header = true;
		 */

		/**
		 * Put filters and other setup code into the setup function, this function runs multiple times so be careful with running queries here and consider caching.
		 * Comment out if not using.
		 */
		public function setup() {
			if ( file_exists( trailingslashit( get_stylesheet_directory() ) . 'dist/components/mega_menu/js/what-input.min.js' ) ) {
				// Use BB add_js to enqueue this dependency.
				$this->add_js( 'what-input', trailingslashit( get_stylesheet_directory_uri() ) . 'dist/components/mega_menu/js/what-input.min.js', array(), '4.0.0', true );
			}
		}

		/**
		 * Only field setup arrays should exist in this function.
		 * Documentation @ https://www.wpbeaverbuilder.com/custom-module-documentation/#setting-fields-ref
		 * Field Types: https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference
		 *
		 * Align - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#align-field
		 * Border - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#border-field
		 * button-group - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#button-group-field
		 * code - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#code-field
		 * color - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#color-field
		 * dimension - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#dimension-field
		 * editor - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#editor-field
		 * font - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#font-field
		 * form - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#form-field
		 * gradient - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#gradient-field
		 * icon - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#icon-field
		 * link - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#link-field
		 * loop - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#loop-settings-fields
		 * multiple-audios - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#multiple-audios-field
		 * multiple-photos - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#multiple-photos-field
		 * photo - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#photo-field
		 * photo-sizes - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#photo-sizes-field
		 * Post Type - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#photo-sizes-field
		 * Select - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#select-field
		 * Service - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#service-fields
		 * shadow - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#shadow-field
		 * Suggest - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#suggest-field
		 * text - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#text-field
		 * Textarea - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#textarea-field
		 * Time - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#time-field
		 * Timezone - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#time-zone-field
		 * typography - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#typography-field
		 * unit - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#unit-field
		 * Video - https://kb.wpbeaverbuilder.com/article/616-cmdg-10-setting-fields-reference#video-field
		 *
		 * Repeater Fields: 'multiple'      => true (Not supported in Editor Fields, Loop Settings Fields, Photo Fields, and Service Fields).
		 *
		 * Dynamic Colour Selector Fields params:
		 * 'type'        => 'fp-colour-picker',
		 * 'element'     => 'a | button | h1 | h2 | h3 | h4 | h5 | h6 | background',
		 *
		 * Dynamic Colour Selector Fields use:
		 * background class in template class="-bg-[colour selected]"
		 * button/header class="[colour selected]"
		 * Additional choices for button: include a select field with options: [outline | solid( default )] eg: Outline: class="outline [color selected]"
		 *
		 * Custom SVG Icon Picker
		 * 'type'        => 'fp-icon-picker',
		 *
		 * Install svg icons through BB font tool in a subdir called /images/ eg: wp-content/bb-icons/brand/images
		 */
		public function init_fields() {
			if ( file_exists( trailingslashit( __DIR__ ) . 'includes/mega_menu_acf.php' ) ) {
				require_once 'includes/mega_menu_acf.php';
			}

			$menus = array( '' => 'None' );
			$menus = array_merge( $menus, get_registered_nav_menus() );

			$this->fields = array(
				'fp-mega_menu-tab-1' => array(
					'title'    => __( 'Settings', 'fp' ),
					'sections' => array(

						'attributes'     => array(
							'title'  => __( 'Attributes', 'fp' ),
							'fields' => array(
								'header_logo'          => array(
									'type'        => 'photo',
									'show_remove' => true,
									'label'       => __( 'Optional: Set a logo image if not set in Options', 'fp' ),
								),
								'menu'                 => array(
									'type'        => 'select',
									'label'       => __( 'Select Menu', 'fp' ),
									'description' => __( 'Select a menu to load', 'fp' ),
									'default'     => 'option-1',
									'options'     => $menus,
								),
								'menu_align'           => array(
									'type'       => 'align',
									'label'      => __( 'Text Align', 'fp' ),
									'default'    => 'center',
									'responsive' => true,
								),
								'vertical'             => array(
									'type'        => 'select',
									'label'       => __( 'Vertical', 'fp' ),
									'description' => __( 'Is the menu to be shown stacked?', 'fp' ),
									'default'     => 'false',
									'options'     => array(
										'true'  => 'Yes',
										'false' => 'No',
									),
								),
								'show-navbar-toggler'  => array(
									'type'        => 'select',
									'label'       => __( 'Show Mobile Toggler', 'fp' ),
									'description' => __( 'Show hamburger menu in mobile', 'fp' ),
									'default'     => 'true',
									'options'     => array(
										'true'  => 'Yes',
										'false' => 'No',
									),
								),
								'mm_display'           => array(
									'type'         => 'select',
									'label'        => __( 'Menu Display', 'fp' ),
									'options'      => array(
										'-all'          => 'All Sizes',
										'-show-desktop' => 'Large Screens',
										'-show-tablet'  => 'Medium Screens',
										'-show-mobile'  => 'Small/Mobile Screens',
									),
									'default'      => '-all',
									'multi-select' => true,
								),
								'mm_sticky'            => array(
									'type'    => 'select',
									'label'   => __( 'Menu Sticky', 'fp' ),
									'options' => array(
										'yes' => 'Yes',
										'no'  => 'No',
									),
									'default' => 'yes',
								),
								'mm_language_switcher' => array(
									'type'    => 'select',
									'label'   => __( 'Show language switcher? (Polylang Sites Only)', 'fp' ),
									'options' => array(
										'0'      => __( 'No', 'fp' ),
										'mobile' => __( 'Mobile Only', 'fp' ),
										'large'  => __( 'Desktop Only', 'fp' ),
										'all'    => __( 'All Sizes', 'fp' ),
									),
									'default' => '0',
									'toggle'  => array(
										'mobile' => array( 'fields' => array( 'mm_language_switcher_text' ) ),
										'large'  => array( 'fields' => array( 'mm_language_switcher_text' ) ),
										'all'    => array( 'fields' => array( 'mm_language_switcher_text' ) ),
										'0'      => array(),
									),
								),
								'mm_language_switcher_text' => array(
									'type'    => 'text',
									'label'   => __( 'Language Switcher Assistive Text', 'fp' ),
									'default' => 'Switch Language',
								),
							),
						),
						'theming'        => array(
							'title'  => __( 'Theming', 'fp' ),
							'fields' => array(
								'mm_background'            => array(
									'type'    => 'color',
									'label'   => __( 'Optional: Set the menu background Colour', 'fp' ),
									'default' => '',
								),
								'main_theme'               => array(
									'type'    => 'fp-colour-picker',
									'label'   => __( 'Main Theme (top level link)', 'fp' ),
									'default' => '',
									'element' => 'background',
								),
								'main_theme_padding'       => array(
									'type'        => 'dimension',
									'label'       => __( 'Main theme padding (top level link)', 'fp' ),
									'description' => 'px',
									'responsive'  => true,
								),
								'dropdown_theme'           => array(
									'type'    => 'fp-colour-picker',
									'label'   => __( 'Dropdown Item Theme', 'fp' ),
									'default' => '',
									'element' => 'background',
								),
								'dropdown_menu_width'      => array(
									'type'        => 'unit',
									'label'       => __( 'Set the dropdown menu width', 'fp' ),
									'description' => 'px',
									'default'     => 100,
								),
								'dropdown_level1_left'     => array(
									'type'        => 'unit',
									'label'       => __( 'Set the dropdown menu left margin', 'fp' ),
									'description' => 'px',
									'default'     => 0,
								),
								'dropdown_level1_padding'  => array(
									'type'        => 'dimension',
									'label'       => __( 'Set the second level item padding', 'fp' ),
									'description' => 'px',
									'responsive'  => true,
								),
								'dropdown_level2_padding'  => array(
									'type'        => 'dimension',
									'label'       => __( 'Set the third level item padding', 'fp' ),
									'description' => 'px',
									'responsive'  => true,
								),
								'dropdown_item_border_color' => array(
									'type'  => 'color',
									'label' => __( 'Dropdown Item Border Color', 'fp' ),
								),
								'standard_flyout_width'    => array(
									'type'        => 'unit',
									'label'       => __( 'Set the standard flyout width', 'fp' ),
									'description' => 'px',
									'default'     => 310,
								),
								'recipe_card_flyout_width' => array(
									'type'        => 'unit',
									'label'       => __( 'Set the recipe card flyout width', 'fp' ),
									'description' => 'px',
									'default'     => 400,
								),
							),
						),
						'mobile_theming' => array(
							'title'  => __( 'Mobile Theming', 'fp' ),
							'fields' => array(
								'mm_mobile_background'     => array(
									'type'    => 'fp-colour-picker',
									'label'   => __( 'Optional: Set a different colour for the mobile background', 'fp' ),
									'element' => 'background',
								),
								'mobile_item_border_color' => array(
									'type'  => 'color',
									'label' => __( 'Mobile: Dropdown Item Border Color', 'fp' ),
								),
								'mm_mobile_dropdown'       => array(
									'type'    => 'fp-colour-picker',
									'label'   => __( 'Optional: Mobile Dropdown Item Theme', 'fp' ),
									'element' => 'background',
								),
							),
						),
					),
				),
			);
			if ( ! has_filter( 'nav_menu_link_attributes', array( $this, 'setup_sidemenu_data' ) ) ) {
				add_filter( 'nav_menu_link_attributes', array( $this, 'setup_sidemenu_data' ), 10, 4 );
			}
			if ( class_exists( 'fp\components\Extend_mega_menu' ) ) {
				fp\components\Extend_mega_menu::extend_init_forms( $this->forms );
				fp\components\Extend_mega_menu::extend_init_fields( $this->fields );
			}
		}

		/**
		 * Based on ACF Fields, setup side menu flyout data attributes on the link item
		 *
		 * @param array   $atts are the beaver builder settings.
		 * @param object  $item is the nav item from the menu.
		 * @param array   $args are the nav menu args.
		 * @param integer $depth is the link item level.
		 */
		public function setup_sidemenu_data( $atts, $item, $args, $depth ) {
			if ( ! function_exists( 'get_field' ) ) {
				return $atts;
			}
			if ( isset( $atts['data-image_cover'] ) && ! empty( $atts['data-image_cover'] ) ) {
				// Already done, as this filter runs multiple times.
				return $atts;
			}
			if ( isset( $atts['data-flyout-shortcode'] ) && ! empty( $atts['data-flyout-shortcode'] ) ) {
				return $atts;
			}
			if ( empty( $args ) || ! isset( $args->menu_class ) || ! strpos( $args->menu_class, 'mega-menu' ) ) {
				return $atts;
			}

			$aria_label = get_field( 'aria_label', $item );

			if ( ! empty( $aria_label ) ) {
				$atts['aria-label'] = $aria_label;
			}

			$has_flyout = get_field( 'show_flyout_content', $item );

			$flyout_type = get_field( 'flyout_content', $item );

			// Standard side menu.

			if ( 'recipe' !== $flyout_type ) {
				$content           = '';
				$header            = '';
				$footer            = '';
				$text              = '';
				$image_cover       = get_field( 'image_cover', $item );
				$text_colour       = get_field( 'text_color', $item );
				$heading_colour    = get_field( 'heading_color', $item );
				$background_colour = get_field( 'background_color', $item );

				$value = get_field( 'header_title', $item );

				if ( ! empty( $value ) ) {
					$header .= '<h2 class="title" aria-hidden="true">' . htmlentities( $value );
				}
				$header_button = get_field( 'header_button_label', $item );
				$h_url         = get_field( 'header_button_url', $item );

				if ( ! empty( $header_button ) && ! empty( $h_url ) ) {
					$header .= '<a href="' . $h_url . '" aria-hidden="true" tabindex="-1">' . $header_button . '</a>';
				}
				if ( ! empty( $header ) ) {
					$header  .= '</h2>';
					$content .= '<header style="background-color: ' . $background_colour . ';color: ' . $text_colour . '">' . $header . '</header>';
				}

				$image = get_field( 'image', $item );
				if ( isset( $image['sizes'] ) ) {
					$post_img = $image['sizes']['large'];
				}

				$post = get_field( 'link_to_content', $item );
				if ( isset( $post->ID ) ) {
					$post_url = get_permalink( $post->ID );
					if ( ! isset( $post_img ) ) {
						$post_img = get_the_post_thumbnail_url( $post->ID, 'large' );
					}
					if ( true === get_field( 'post_date', $item ) ) {
						$post_date = htmlentities( get_the_date( 'F j, Y', $post->ID ) );
					}

					if ( ! empty( get_field( 'heading', $item ) ) ) {
						$post_title = get_field( 'heading', $item );
					} else {
						$post_title = htmlentities( $post->post_title );
					}
				} else {
					$title = get_field( 'title', $item );
					$url   = get_field( 'learn_more_url', $item );
					if ( ! empty( $title ) ) {
						$post_title = $title;
					}
					if ( ! empty( $url ) ) {
						$post_url = $url;
					}
				}

				if ( isset( $post_img ) ) {
					if ( 'Top' === $image_cover ) {
						$footer .= '<div class="card-img-top"><img src="' . $post_img . '" alt="" width="100%" aria-hidden="true" /></div>';
					}
				}
				if ( isset( $post_date ) ) {
					$text .= '<p class="post-meta" style="color:' . $text_colour . '">' . $post_date . '</p>';
				}
				if ( isset( $post_title ) ) {
					$text .= '<h2 class="heading"';
					if ( $heading_colour ) {
						$text .= ' style="color:' . $heading_colour . '"';
					}
					$text .= '>' . $post_title . '</h2>';
				}
				$description = get_field( 'description', $item );
				if ( ! empty( $description ) ) {
					$text .= '<p style="color:' . $text_colour . '" aria-hidden="true">' . htmlentities( $description ) . '</p>';
				}

				if ( isset( $post_url ) ) {
					$link_title = get_field( 'link_title', $item );
					if ( ! empty( $link_title ) ) {
						$text .= '<a href="' . $post_url . '" style="color:' . $text_colour . '" aria-hidden="true" tabindex="-1">' . htmlentities( $link_title ) . '</a>';
					}
				}

				if ( ! empty( $text ) || ! empty( $header ) || ! empty( $footer ) ) {
					if ( 'Top' === $image_cover ) {
						$content .= '<footer ' . ( $background_colour ? ' style="background-color: ' . $background_colour . '"' : '' ) . '>';
						$content .= $footer;
					} else {
						$content .= '<footer ' . ( ! empty( $post_img ) ? ' style="background-image:url(\'' . $post_img . '\')"' : '' ) . '>';
					}
					$content .= '<div class="text" style="color:' . $text_colour . '">';
					$content .= $text;
					$content .= '</div>'; // End text.
					$content .= '</footer>';
				}

				if ( ! empty( $content ) ) {
					$atts['data-class']            = 'image_' . strtolower( $image_cover );
					$atts['data-flyout-type']      = 'standard';
					$atts['data-flyout-shortcode'] = \base64_encode( $content ); //phpcs:ignore
					$atts['aria-haspopup']         = 'true';
				}
			} elseif ( $has_flyout && 'recipe' === $flyout_type ) {
				$recipe = get_field( 'recipe_flyout', $item );
				if ( isset( $recipe->ID ) ) {
					$shortcode = array(
						'id'           => $recipe->ID,
						'title_tag'    => 'h3',
						'no_container' => $recipe->post_title,
						'overlay_item' => 'button',
						'button_text'  => __( 'View Recipe', 'fp' ),
						'button_aria'  => '',
						'hide_details' => false,
						'shortcode'    => 'recipe_card',
					);

					$recipe_card = do_shortcode( '[recipe_card hide_details="false" button_aria="" button_text="" overlay_item="button" no_container="' . $recipe->post_title . '" title_tag="h3" id="' . $recipe->ID . '"]' );

					if ( ! empty( $recipe_card ) ) {
						$theme = get_field( 'flyout_theme', $item );
						// Add the selected theme as a class.
						if ( false !== $theme ) {
							$recipe_card = str_replace( 'card-body', 'card-body ' . $theme, $recipe_card );
						}

						// Hide the overlay and make it hidden.
						$recipe_card = str_replace( 'class="overlay row"', 'class="overlay row hidden" aria-hidden="true"', $recipe_card );
						$recipe_card = str_replace( $recipe->post_title, htmlentities( $recipe->post_title ), $recipe_card );
					}
					$atts['data-flyout-type']      = 'recipe';
					$atts['data-flyout-theme']     = get_field( 'flyout_theme', $item );
					$atts['data-flyout-shortcode'] = \base64_encode( $recipe_card ); //phpcs:ignore
					$atts['aria-haspopup']         = 'true';
				}
			}
			return $atts;
		}

		/**
		 * Extend the theme js file to override the main js file.
		 * By default, this method will enqueue a concatenated file if both the main js file and theme js file exist.
		 * Otherwise, just the standard component js file is enqueued.
		 *
		 * @see fp\Component::register_assets()
		 *
		 * @return true|false
		 */
		public function extend_js_theme() {
			if ( class_exists( 'fp\components\Extend_mega_menu' ) ) {
				if ( method_exists( 'fp\components\Extend_mega_menu', 'extend_js_theme' ) ) {
					return fp\components\Extend_mega_menu::extend_js_theme();
				} else {
					return false;
				}
			} else {
				return false;
			}
		}

		/**
		 * Pre-process data before it gets sent to the template.
		 *
		 * @param array       $atts are the saved settings.
		 * @param object|null $module is a module instance.
		 *
		 * @return array
		 */
		public function pre_process_data( $atts, $module ) {
			$atts['classes'] = 'navbar navbar-expand-lg';
			if ( ! empty( $atts['vertical'] ) && 'true' === $atts['vertical'] ) {
				$atts['classes'] .= ' -vertical';
			}
			if ( isset( $atts['mm_display'] ) && is_array( $atts['mm_display'] ) ) {
				$atts['classes'] .= ' ' . implode( ' ', $atts['mm_display'] );
			}
			if ( isset( ( $atts['mm_sticky'] ) ) && 'yes' === $atts['mm_sticky'] ) {
				$atts['classes'] .= ' mm-sticky';
			}

			// These are aria labels which will not be shown and can be set to defaults.
			$atts['toggle_menu_aria'] = __( 'Click to hide/show the main menu', 'fp' );
			$atts['menu_heading']     = __( 'Main Menu', 'fp' );

			remove_filter( 'wp_nav_menu_args', 'prefix_modify_nav_menu_args' );

			$main_menu = wp_nav_menu(
				array(
					'theme_location' => $atts['menu'],
					'menu_id'        => $atts['menu'],
					'menu'           => $atts['menu'],
					'menu_class'     => 'nav mega-menu',
					'depth'          => 3,
					'fallback_cb'    => false,
					'items_wrap'     => '%3$s',
					'container'      => false,
					'echo'           => false,
					'walker'         => new MM_Navwalker(),
				)
			);

			$atts['main_menu']       = $main_menu;
			$atts['mm_allowed_tags'] = array(
				'a'      => array(
					'href'                  => array(),
					'class'                 => array(),
					'aria-current'          => array(),
					'aria-label'            => array(),
					'aria-haspopup'         => array(),
					'aria-expanded'         => array(),
					'id'                    => array(),
					'data-flyout-shortcode' => array(),
					'data-flyout-theme'     => array(),
					'data-flyout-type'      => array(),
				),
				'ul'     => array(
					'aria-label'           => array(),
					'data-flyout-card'     => array(),
					'data-flyout-standard' => array(),
					'class'                => array(),
					'id'                   => array(),
					'aria-labelledby'      => array(),
				),
				'li'     => array(
					'itemscope' => array(),
					'itemtype'  => array(),
					'id'        => array(),
					'class'     => array(),
					'tabindex'  => array(),
				),
				'div'    => array(
					'class' => array(),
				),
				'span'   => array(
					'class' => array(),
				),
				'button' => array(
					'data-url'      => array(),
					'data-toggle'   => array(),
					'aria-label'    => array(),
					'aria-haspopup' => array(),
					'aria-expanded' => array(),
					'class'         => array(),
					'id'            => array(),
				),
			);

			add_filter( 'wp_nav_menu_args', 'prefix_modify_nav_menu_args' );

			if ( class_exists( 'fp\components\Extend_mega_menu' ) ) {
				$atts = fp\components\Extend_mega_menu::pre_process_data( $atts, $module );
			}

			return $atts;
		}
	}

	new mega_menu();
}
