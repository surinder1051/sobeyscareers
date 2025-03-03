<?php
/**
 * Accordion Slider Beaver Builder module class
 *
 * @package fp-slider
 */

/**
 * Accordion_Slider
 */
class Accordion_Slider extends FLBuilderModule {

	/**
	 * __construct
	 */
	public function __construct() {
		parent::__construct(
			array(
				'name'            => __( 'Accordion Slider', FP_TD ),
				'description'     => __( 'Promotional Banner in Accordion Style', FP_TD ),
				'category'        => __( 'FP Sliders', FP_TD ),
				'partial_refresh' => true,
			)
		);
	}
}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module(
	'Accordion_Slider',
	array(
		'accordion_slider_settings' => array(
			'title'    => __( 'Settings', FP_TD ),
			'sections' => array(
				'attributes' => array(
					'title'  => __( 'Attributes', FP_TD ),
					'fields' => array(
						'slides'           => array(
							'type'   => 'suggest',
							'label'  => __( 'Slides', FP_TD ),
							'action' => 'fl_as_posts', // Search posts.
							'data'   => 'slide', // Slug of the post type to search.
							'limit'  => 3, // Limits the number of selections that can be made.
						),
						'slide_icon'       => array(
							'type'    => 'icon',
							'label'   => __( 'Accordion Expand Icon', FP_TD ),
							'default' => 'fas fa-arrow-alt-circle-down',
						),
						'slide_breakpoint' => array(
							'type'    => 'unit',
							'label'   => __( 'Set the breakpoint from horizontal to vertical', FP_TD ),
							'default' => 976,
						),
					),
				),
			),
		),
	)
);
