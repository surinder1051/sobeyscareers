<?php

namespace fp\components;

use fp;

class list_module extends fp\Component
{

    public $schema_version        = 5; // This needs to be updated manuall when we make changes to the this template so we can find out of date components
	public $component             = 'list_module'; // Component slug should be same as this file base name
    public $version               = '1.0.1';
    public $component_name        = 'List/Table of Contents'; // Shown in BB sidebar.
	public $component_description = 'List module single column for up to 4 or Double Column for up to 8';
	public $component_category    = 'FP Global';
	public $enable_css            = true;
	public $enable_js             = true;
	public $deps_css              = array(); // WordPress Registered CSS Dependencies
	public $deps_js               = array('jquery'); // WordPress Registered JS Dependencies
	public $fields                = array(); // Placeholder for fields used in BB Module & Shortcode
	public $bbconfig              = array(); // Placeholder for BB Module Registration
	public $base_dir              = __DIR__;
	public $variants              = array('-two-col', '-three-col', '-ordered', '-unordered'); // Component CSS Variants as per -> http://rscss.io/variants.html

	public function init_fields()
	{

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
				'list_items',
				array(
					'title' => __('Attributes', 'fl-builder'),
					'tabs'  => array(
						'general'      => array(
							'title'         => __('General', 'fl-builder'),
							'sections'      => array(
								'general'       => array(
									'title'         => '',
									'fields'        => array(
										'item_title' => array(
											'type' => 'text',
											'label'    	=> __('Text', FP_TD),
										),
										'item_url' => array(
											'type' => 'link',
											'label'    	=> __('URL', FP_TD),
										),
										'target' => array(
											'type'        => 'select',
											'label'       => __('Target Window', FP_TD),
											'default'     => 'current',
											'options'     => array(
												'current'     => 'Current Window',
												'new'	     => 'New Window',
											),
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
			'fp-list_module-tab-1' => array(
				'title'         => __('Settings', FP_TD),
				'sections'      => array(
					'attributes' => array(
						'title'     => __('Attributes', FP_TD),
						'fields'    => array(
							'h2' => array(
								'type'        => 'text',
								'label'       => __('Heading ( optional )', FP_TD),
								'default'     => __('Sample Heading', FP_TD),
								'maxlength' => 200,
							),
							'title_tag' => array(
								'type'        => 'select',
								'label'       => __('Title Tag', FP_TD),
								'default'     => 'h2',
								'options'     => array(
									'h1'     => 'H1',
									'h2'     => 'H2',
									'h3'     => 'H3',
									'h4'     => 'H4',
									'h5'     => 'H5',
									'h6'     => 'H6'
								),
							),
							'heading_alignment' => array(
								'type'        => 'align',
								'label'       => __('Heading Alignment', FP_TD),
								'default'     => 'center',
							),
							'columns'       => array(
								'type'      => 'select',
								'label'     => __('Columns', FP_TD),
								'default'   => '2',
								'options'   => array(
									'one_col'   => __('One', FP_TD),
									'two_col'   => __('Two', FP_TD),
									'three_col'   => __('Three', FP_TD),
								)
							),
							'list_alignment' => array(
								'type'        => 'align',
								'label'       => __('List Alignment', FP_TD),
								'default'     => 'center',
							),
							'list_toggle'   => array(
								'type'      => 'select',
								'label'     => __('Ordered / Unordered', FP_TD),
								'default'	=> 'ordered',
								'options'   => array(
									'ordered'   => __('Ordered', FP_TD),
									'unordered'    => __('Unordered', FP_TD)
								),
								'toggle' => array(
									'ordered' => array('fields' => array('ol_list_style')),
									'unordered' => array()
								)
							),
							'ul_list_style'   => array(
								'type'      => 'select',
								'label'     => __('Unordered List Style', FP_TD),
								'default'	=> 'default',
								'options'   => array(
									'default'   => __('Right Angle Bracket (default)', FP_TD),
									'style-bullet'    => __('Bullet', FP_TD),
								),
								'toggle' => array(
									'style-bullet' => array('fields' => array('ol_list_colour')),
									'default' => array()
								)
							),
							'ol_list_style'   => array(
								'type'      => 'select',
								'label'     => __('Ordered List Style', FP_TD),
								'default'	=> 'default',
								'options'   => array(
									'default'   => __('Right Border (default)', FP_TD),
									'style-background'    => __('Coloured Background', FP_TD),
									'style-checkmark'    => __('Custom Checkmark', FP_TD)
								),
								'toggle' => array(
									'style-background' => array('fields' => array('ol_list_colour')),
									'style-checkmark' => array('fields' => array('ol_list_colour')),
									'default' => array()
								)
							),
							'ol_list_colour' => array(
								'type'	=> 'fp-colour-picker',
								'element'	=> 'background',
								'label'     => __('Choose the bullet colour', FP_TD),
							),
							'intro_content' => array(
								'type'          => 'editor',
								'label'         => __('Intro ( optional )', FP_TD),
								'default'     	=> __('Sample Intro Content', FP_TD),
								'media_buttons' => false,
								'rows'          => 2,
								'maxlength' => 200,
							),
							'list_items' => array(
								'type'    		=> 'form',
								'form'          => 'list_items',
								'multiple' 		=> true,
								'label'    		=> __('List Item', FP_TD),
								'preview_text'  => 'item_title', // ID of a field to use for the preview text.
								'default' 		=> array(
									0 => array(
										'item_title' 		=> 'Sample Text Item',
									),
									1 => array(
										'item_title' 		=> 'Sample Link Item',
										'item_url'	 		=> 'https://google.com',
									),
								),
							),
							'list_default_colour' => array(
								'type'		=> 'color',
								'label'     => __('Choose the default list TEXT colour', FP_TD),
							),
							'list_link_colour' => array(
								'type'	=> 'fp-colour-picker',
								'element'	=> 'a',
								'label'     => __('Choose the list LINK colour', FP_TD),
							),
							'list_item_margin' => array(
								'type'			=> 'dimension',
								'label'     	=> __('Set the list item margins', FP_TD),
								'responsive'	=> true,
								'description'	=> 'px'
							),
							'mobile_layout'   => array(
								'type'      => 'select',
								'label'     => __('Mobile Layout', FP_TD),
								'description' 	=> __('The "Dropdown" works best if all list items work as links', FP_TD),
								'default'	=> 'list',
								'options'   => array(
									'list'   => __('View as List', FP_TD),
									'dropdown'    => __('View as Dropdown Menu', FP_TD)
								)
							),
						),
					),
				),
			),
		);
	}

	// Sample as to how to pre-process data before it gets sent to the template

	public function pre_process_data($atts, $module)
	{
		$classes[] = '-' . $atts['columns'];
		if ('' != $atts['list_toggle']) {
			$classes[] = '-' . $atts['list_toggle'];
		} else {
			$classes[] = '-ordered';
		}
		$atts['item_count'] = count($atts['list_items']);
		$atts['classes'] = implode(' ', $classes);
		$atts['mobile_format'] = (isset($atts['mobile_layout'])) ? $atts['mobile_layout'] : 'list';

		if ($atts['list_toggle'] == 'ordered') {
			$atts['list_tag'] = 'ol';
		} else {
			$atts['list_tag'] = 'ul';
		}

		return $atts;
	}
}

new list_module;
