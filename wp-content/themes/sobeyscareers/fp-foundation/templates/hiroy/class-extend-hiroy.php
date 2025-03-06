<?php //phpcs:ignore
/**
 * FP Foundation custom component.
 *
 * @package fp-foundation
 */

namespace fp\components;

use fp;

if ( class_exists( 'fp\Component' ) ) {
	/**
	 * Extend the component. Delete this class if not using.
	 * Call functions statically.
	 */
	class Extend_hiroy { //phpcs:ignore

		/**
		 * Extend the main fields array by adding a new tab with fields
		 *
		 * @param array $fields are the initial BB fields, updated by reference.
		 *
		 * @return void
		 */
		public static function extend_init_fields( &$fields ) {
			$fields['tab-4'] = array(
				'title'       => __( 'Custom Site', 'fp' ),
				'description' => __( 'Extended Settings', 'fp' ),
				'sections'    => array(
					'Custom theme' => array(
						'title'  => __( 'Add a photo', 'fp' ),
						'fields' => array(
							'my_photo_field'    => array(
								'type'        => 'photo',
								'label'       => __( 'Photo Field', 'fp' ),
								'show_remove' => false,
							),
							'extend_form_field' => array(
								'type'  => 'form',
								'label' => __( 'Form Text', 'fp' ),
								'form'  => 'extended_form',
							),
						),
					),
				),
			);
		}

		/**
		 * Extend the main fields array by adding a new form with fields.
		 *
		 * @param array $forms are the initial BB fields, updated by reference.
		 *
		 * @return void
		 */
		public static function extend_init_forms( &$forms ) {

			$new_form = array(
				'extended_form',
				array(
					'title' => __( 'My Form Field', 'fl-builder' ),
					'tabs'  => array(
						'general' => array(
							'title'    => __( 'General', 'fl-builder' ),
							'sections' => array(
								'general' => array(
									'title'  => '',
									'fields' => array(
										'label' => array(
											'type'  => 'text',
											'label' => __( 'Label', 'fl-builder' ),
										),
									),
								),
							),
						),
					),
				),
			);

			array_push( $forms, $new_form );
		}

		/**
		 * Process additional settings and return the new atts to the parent module.
		 *
		 * @param array       $atts are the saved settings.
		 * @param object|null $module is a module instance.
		 *
		 * @return array
		 */
		public static function pre_process_data( $atts, $module ) {

			return $atts;
		}

		/**
		 * Extend parent component dynamic feed parameters. This can be called from the parent setup function.
		 *
		 * @param array $dynamic_data_feed_parameters is the class prop updated by reference.
		 *
		 * @return void
		 */
		public static function extend_dynamic_parameters( &$dynamic_data_feed_parameters ) {
			$dynamic_data_feed_parameters = array(
				'pagination_api'         => true,
				'posts_per_page_default' => '3',
				'posts_per_page_options' => array(
					'1' => 1,
					'2' => 3,
					'3' => 3,
				),
				'post_types'             => array( 'post', 'page' ),
				'max_overwrites'         => 9,
				'order'                  => 'DESC',
				'orderby'                => 'menu_order',
				'fetch_taxonomies'       => true, // Return the taxonomies in the post data for display.
				'taxonomies'             => array(
					array( 'category' => array() ),
					array(
						'post_tag' => array(
							'none-option' => true,
						),
					),
				),
			);
		}
		/**
		 * Whether to use the combined javascript file, or override the default js file with theme.js.
		 * Default: false
		 *
		 * @return true|false
		 */
		public static function extend_js_theme() {
			return false;
		}
	}
}
