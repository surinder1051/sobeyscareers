<?php

namespace fp\components;

use fp;

if ( class_exists( 'fp\Component' ) ) {
	class header_logo_search extends fp\Component {

		public $schema_version        = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
		public $version               = '1.1.1';
		public $component             = 'header_logo_search'; // Component slug should be same as this file base name
		public $component_name        = 'Header: Logo + Search'; // Shown in BB sidebar.
		public $component_description = 'Add a site logo and search bar';
		public $component_category    = 'FP Global';
		public $enable_css            = true;
		public $enable_js             = true;
		public $deps_css              = array( 'brand' ); // WordPress Registered CSS Dependencies
		public $deps_js               = array( 'jquery' ); // WordPress Registered JS Dependencies
		// public $deps_css_remote       			= array( 'https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css'); // WordPress Registered CSS Dependencies
		// public $deps_js_remote 		  			= array( 'https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js', 'https://cdn.datatables.net/plug-ins/1.10.19/sorting/absolute.js'); // WordPress Registered JS Dependencies
		public $base_dir = __DIR__;
		public $fields   = array(); // Placeholder for fields used in BB Module & Shortcode
		public $bbconfig = array(); // Placeholder for BB Module Registration
		public $variants = array( '-expand-above' ); // Component CSS Variants as per -> http://rscss.io/variants.html
		// public $exclude_from_post_content 		= true; // Exclude content of this module from being saved to post_content field
		public $load_in_header               = true;
		public $dynamic_data_feed_parameters = array( // Generates $atts[posts] object with dynamically populated data
			// 'pagination_api' => true, // enable ajax pagination
			// 'posts_per_page_default' => '3',
			// 'posts_per_page_options' => array(
			// '1' => 1,
			// '2' => 2,
			// '3' => 3,
			// '4' => 4,
			// '5' => 5,
			// '6' => 6,
			// '7' => 7,
			// '8' => 8,
			// '9' => 9,
			// ),
			// 'post_types' => array('post','page'),
			// 'taxonomies' => array(
			// array('category' => array()),
			// array('content_tag' => array('none-option' => true)),
			// )
		);

		public function init_fields() {
			// Documentation @ https://www.wpbeaverbuilder.com/custom-module-documentation/#setting-fields-ref

			/*
			Field Types:

			https://www.wpbeaverbuilder.com/custom-module-documentation/#setting-fields-ref

			Code
			Color
			Editor
			Font
			Icon
			Link
			Loop
			Form
			Multiple Audios
			Multiple Photos
			Photo
			Photo Sizes
			Post Type
			Select
			Service
			Suggest
			Textarea
			Time
			Timezone
			Video

			Repeater Fields
			'multiple'      => true,
			Not supported in Editor Fields, Loop Settings Fields, Photo Fields, and Service Fields.


			*/

			$this->fields = array(
				'fp-header_logo_search-tab-1' => array(
					'title'    => __( 'Settings', FP_TD ),
					'sections' => array(
						'logo'   => array(
							'title'  => __( 'Logo', FP_TD ),
							'fields' => array(
								'logo_image'      => array(
									'type'        => 'photo',
									'label'       => __( 'Logo Image', FP_TD ),
									'show_remove' => true,
								),
								'header_logo'     => array(
									'type'        => 'icon',
									'show_remove' => true,
									'label'       => __( 'Optional: Set a logo image if not set in Options', FP_TD ),
									'show'        => array(
										'fields' => array( 'header_logo_alt', 'logo_size', 'logo_margin' ),
									),
								),
								'header_logo_alt' => array(
									'type'    => 'text',
									'label'   => __( 'Optional: Set the logo alternate text (accessibility)', FP_TD ),
									'default' => get_bloginfo( 'name' ) . ' ' . __( 'Home', FP_TD ),
								),
								'logo_size'       => array(
									'type'         => 'unit',
									'label'        => 'Logo Size',
									'description'  => 'px',
									'slider'       => true,
									'units'        => array( 'px', 'vw', '%' ),
									'default_unit' => 'px', // Optional
									'default'      => 20,
									'responsive'   => true,
									'preview'      => array(
										'type'     => 'css',
										'selector' => '.main-logo',
										'property' => 'font-size',
									),
								),
								'logo_margin'     => array(
									'type'        => 'dimension',
									'label'       => 'Logo Margins',
									'responsive'  => true,
									'description' => 'px',
									'slider'      => true,
									'preview'     => array(
										'type'     => 'css',
										'selector' => '.main-logo',
										'property' => 'margin',
									),
								),
							),
						),
						'search' => array(
							'title'  => __( 'Search', FP_TD ),
							'fields' => array(
								'show_search'            => array(
									'type'        => 'select',
									'label'       => __( 'Show Search', FP_TD ),
									'description' => __( 'Show search input box', FP_TD ),
									'default'     => 'false',
									'options'     => array(
										'true'  => 'Yes',
										'false' => 'No',
									),
								),
								'search_button_icon'     => array(
									'type'    => 'icon',
									'label'   => __( 'Search Button Icon', FP_TD ),
									'default' => 'fpicon-search-red',
								),
								'search_icon_colour'     => array(
									'type'  => 'color',
									'label' => __( 'Set the search button colour', FP_TD ),
								),
								'show_search_close'      => array(
									'type'    => 'select',
									'label'   => __( 'Show the close search button on mobile', FP_TD ),
									'options' => array(
										'0' => 'No',
										'1' => 'Yes',
									),
									'default' => '0',
								),
								'search_position_mobile' => array(
									'type'    => 'select',
									'label'   => __( 'Search position on mobile', FP_TD ),
									'options' => array(
										'-expand-above' => __( 'Expand from Top', FP_TD ),
										'-expand-below' => __( 'Expand Below (Default)', FP_TD ),
									),
									'default' => '-expand-below',
								),
								'trending_links'         => array(
									'type'    => 'select',
									'label'   => __( 'Show Trending Links', FP_TD ),
									'options' => array(
										'0' => __( 'No', FP_TD ),
										'1' => __( 'Yes (Default)', FP_TD ),
									),
									'default' => '1',
								),
							),
						),
					),
				),

			);
		}

		// Sample as to how to pre-process data before it gets sent to the template

		public function pre_process_data( $atts, $module ) {
			if ( isset( $atts['search_position_mobile'] ) ) {
				$atts['classes'] = $atts['search_position_mobile'];
			} else {
				$atts['classes'] = '-expand-below';
			}
			$atts['classes'] .= ' header_section';

			if ( ! isset( $atts['trending_links'] ) ) {
				$atts['trending_links'] = '1';
			}
			return $atts;
		}

	}

	new header_logo_search();
}
