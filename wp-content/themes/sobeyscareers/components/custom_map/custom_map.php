<?php

namespace fp\components;
use fp;

class custom_map extends fp\Component {

	public $version               = '1.0.1';
	public $component             = 'custom_map'; // Component slug should be same as this file base name
	public $component_name        = 'Custom Map'; // Shown in BB sidebar.
	public $component_description = 'Build a custom map from post types with Lat/Long fields and custom markers.';
	public $component_category    = 'FP Dynamic Components';
	public $enable_css            = true;
	public $enable_js             = true;
	public $deps_css              = array(); // WordPress Registered CSS Dependencies
	public $deps_js               = array( 'jquery' ); // WordPress Registered JS Dependencies
	public $fields                = array(); // Placeholder for fields used in BB Module & Shortcode
	public $bbconfig              = array(); // Placeholder for BB Module Registration
	public $base_dir              = __DIR__;
	public $variants              = array( ); // Component CSS Variants as per -> http://rscss.io/variants.html
	public $schema_version        = 3; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
	public $exclude_from_post_content = true;
	public $id                    = null;

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


		$this->forms = array(
			array(
				'custom_map_links',
				array(
					'title' => __('Choose Post', FP_TD),
					'tabs'  => array(
						'general'      => array(
							'title'         => __('General', FP_TD),
							'sections'      => array(
								'general'       => array(
									'fields'        => array(
										'map_item_url' => array(
											'type'    => 'link',
											'label'   => __('Post must have lat/long custom fields', FP_TD),
										),
									),
								),
							),
						),
					),
				),
			),
		);


		$this->fields = array(
			'fp-map-tab-1' => array(
				'title'         => __( 'Settings', FP_TD ),
				'sections'      => array(
					'attributes' => array(
						'title'     => __( 'Top Content', FP_TD ),
						'fields'    => array(
							'heading_text' => array(
								'type'      => 'text',
								'label'     => __( 'Heading', FP_TD ),
								'default'   => __( 'Sample Heading', FP_TD ),
								'test' 		=> true,
								'test_content' => array(
									'min' => $this->get_sample_text(5,32),
									'max' => $this->get_sample_text(36, 418),
								),
								'maxlength' => 40,
							),
							'heading_colour' => array(
								'type'	=> 'fp-colour-picker',
								'label'	=> __('Select the heading colour', FP_TD ),
								'element'	=> 'h1'
							),
							'heading_background' => array(
								'type'      => 'photo',
								'label'     => __( 'Optional: Add a Heading Background', FP_TD )
							),
							'heading_type' => array(
								'type'      => 'select',
								'label'     => __( 'Set the heading style', FP_TD ),
								'options' => array(
									'h1'	=> 'Heading 1',
									'h2'	=> 'Heading 2',
									'h3'	=> 'Heading 3',
								),
								'default'	=> 'h2'
							),
							'heading_typography' => array(
								'type'      => 'typography',
								'label'     => __( 'Set the heading styling', FP_TD ),
								'responsive' => true
							),
						),
					),
					'filters' => array(
						'title'     => __( 'Filters', FP_TD ),
						'fields'    => array(
							'map_content_type' => array(
								'type'          => 'select',
								'label'         => __( 'Select Map Content', FP_TD ),
								'options'		=> array(
									'post_type'	=> 'By Post Type',
									'post_tax'	=> 'By Taxonomy/Term',
									'post_item'	=> 'By Post',
								),
								'toggle' => array(
									'post_type' => array('fields' => array('map_post_type')),
									'post_tax' => array('fields' => array('map_term_overwrite')),
									'post_item' => array('fields' => array('map_posts_overwrite')),
								)
							),
							'map_post_type' => array(
								'type'          => 'select',
								'label'         => __( 'Map by Post Type', FP_TD ),
								'options'		=> get_post_types(array('_builtin' => false)),
								'description'	=> __( 'Post Type must have latitude and longitude fields', FP_TD ),
							),
							'map_term_overwrite' => array(
								'type'          => 'select',
								'label'         => __( 'Type', FP_TD ),
								'options'		=> $this->get_term_options(),
								'description'	=> __( 'Post Type(s) must have latitude and longitude fields', FP_TD ),
							),
							'map_posts_overwrite' => array(
								'type'          => 'form',
								'label'         => __( 'Add Map Marker', FP_TD ),
								'description'	=> __( 'Post Type(s) must have latitude and longitude fields', FP_TD ),
								'multiple'		=> true,
								'max'			=> 30,
								'form'			=> 'custom_map_links'
							),
						),
					),
					'map_content' => array(
						'title'     => __( 'Map Content', FP_TD ),
						'fields'    => array(
							'map_api_key' => array(
								'type'          => 'text',
								'label'         => __( 'Google Map API Key', FP_TD ),
							),
							'map_icon' => array(
								'type'          => 'fp-icon-picker',
								'label'         => __( 'Select a Map Marker', FP_TD ),
							),
							'map_theme' => array(
								'type'          => 'fp-colour-picker',
								'label'         => __( 'Select a Map Colour Theme', FP_TD ),
								'element'		=> 'button'
							),
							'map_button_text' => array(
								'type'          => 'text',
								'label'         => __( 'Set the button text', FP_TD ),
								'default'		=> __( 'View More Info' , FP_TD)
							),
							'map_popup_content' => array(
								'type'          => 'select',
								'label'         => __( 'Map Pop-up Content', FP_TD ),
								'options'		=> array(
									'featured-image' => 'Featured Image',
									'post-content'	=> 'Post Content'
								)
							),
						),
					),
				),
			),
		);
	}

	protected function get_term_options() {
		if (!isset($_GET['fl_builder'])) {
			// Don't run this if not in fl_builder edit page mode
			return;
		}
		$taxonomies = get_taxonomies(array('_builtin' => false));
		$termList = array();
		$options = array();
		if (!empty($taxonomies)) {
			foreach($taxonomies as $tax) {
				if (strstr($tax, 'language') == false && strstr($tax, 'translation') == false) {
					$terms = get_terms($tax);
					if (isset($terms[0]->term_id) ) {
						foreach($terms as $term) {
							$termList[$term->taxonomy . ': ' . $term->name] = $term->term_id;
						}
					}
				}
			}
		}
		if (!empty($termList)) {
			ksort($termList);
			$options = array_flip($termList);
		}
		$options[''] = 'All';
		return $options;
	}

	// Sample as to how to pre-process data before it gets sent to the template

	public function pre_process_data($atts, $module) {

		if (!isset($_GET['fl_builder'])) {
			wp_enqueue_script('google_maps', 'https://maps.googleapis.com/maps/api/js?key=' . $atts['map_api_key'], array('jquery'), '1.0.0', true);
		}
		$posts = array();

		$postTypes = array('recipe', 'article', 'store', 'product', 'faq', 'cooking-tip', 'easy-meal', 'how-to', 'video', 'collection', 'local-farmer');
		if (!empty($atts['map_content_type'])) {
			switch($atts['map_content_type']) {
				case 'post_type' :
					if (!empty($atts['map_post_type'])) {
						$posts = get_posts(array('post_type' => $atts['map_post_type'], 'numberposts' => 30, 'orderby' => 'post_title', 'order' => 'ASC'));
					}
					break;
				case 'post_tax':
					$atts['postTypes'] = $postTypes;
					if (!empty($atts['map_term_overwrite'])) {
						$termObj = get_term($atts['map_term_overwrite']);
						if (isset($termObj->term_id) ) {
							$taxonomy = get_taxonomy( $termObj->taxonomy );
							$postTypes = (isset($taxonomy->object_type)) ? $taxonomy->object_type : array();
							$tax_query = array(
								array(
									'taxonomy'         => $termObj->taxonomy,
									'terms'            => $termObj->slug,
									'field'            => 'slug',
								),
							);
							$posts = get_posts(array('post_type'=> $postTypes, 'numberposts'  => -1, 'tax_query' => $tax_query, 'orderby' => 'post_title', 'order' => 'ASC' )  );
						}
					}
					break;
				case 'post_item':
					if (!empty($atts['map_posts_overwrite'])) {

						foreach($atts['map_posts_overwrite'] as $index => $link) {

							$mapObject = get_page_by_path(basename(untrailingslashit(str_replace(site_url() ."/", '',  $link->map_item_url ) ) ), OBJECT, $postTypes );
							if (isset($mapObject->ID)) {
								$posts[] = $mapObject;
							}
						}
					}
					break;
				default:
					break;

			}
			if (!empty($posts)) {
				foreach($posts as $index => $post) {

					if ($atts['map_popup_content'] == 'featured-image' && false !== ($fimg = get_post_thumbnail_id($post->ID))) {
						$posts[$index]->post_content = wp_get_attachment_image($fimg, array(600, 400));
					}
				}
			}


		}
		$atts['posts'] = $posts;
		return $atts;
	}

}

new custom_map;