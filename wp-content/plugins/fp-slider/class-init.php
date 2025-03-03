<?php
/**
 * Plugin initialization class
 *
 * @package fp-slider
 */

namespace FpSlider;

/**
 * Init
 */
class Init {

	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register_post_type' ) );
		add_action( 'init', array( $this, 'load_scripts' ) );

		require_once FPSLIDER_PLUGIN_PATH . 'generate-acf.php';
		require_once FPSLIDER_PLUGIN_PATH . 'modules/slide/class-slide.php';
		require_once FPSLIDER_PLUGIN_PATH . 'modules/slider/class-slider.php';
		require_once FPSLIDER_PLUGIN_PATH . 'modules/accordion-slider/class-accordion-slider.php';

		add_action( 'init', array( $this, 'load_bb_modules' ), 10 );

		// Modules.
		$this->slide            = new Slide();
		$this->slider           = new Slider();
		$this->accordion_slider = new Accordion_Slider();
	}

	/**
	 * Register the "Slide" post type
	 *
	 * @return void
	 */
	public function register_post_type() {
		$post_type = 'slide';

		$args = array(
			'description'        => __( 'Description.', FP_TD ),
			'public'             => true,
			'publicly_queryable' => true,
			'query_var'          => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_nav_menus'  => false,
			'rewrite'            => array(
				'slug'       => $post_type,
				'with_front' => false,
			),
			'map_meta_cap'       => null,
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_icon'          => null,
			'menu_position'      => null,
			'show_in_rest'       => true,
			'supports'           => array( 'title', 'thumbnail' ),
			'taxonomies'         => array(),
			'labels'             => array(
				'name'               => _x( 'Slide', 'post type general name', FP_TD ),
				'singular_name'      => _x( 'Slide', 'post type singular name', FP_TD ),
				'menu_name'          => _x( 'Slides', 'admin menu', FP_TD ),
				'name_admin_bar'     => _x( 'Slide', 'add new on admin bar', FP_TD ),
				'add_new'            => _x( 'Add New', 'Slide', FP_TD ),
				'add_new_item'       => __( 'Add New Slide', FP_TD ),
				'new_item'           => __( 'New Slide', FP_TD ),
				'edit_item'          => __( 'Edit Slide', FP_TD ),
				'view_item'          => __( 'View Slide', FP_TD ),
				'all_items'          => __( 'All Slides', FP_TD ),
				'search_items'       => __( 'Search Slides', FP_TD ),
				'parent_item_colon'  => __( 'Parent Slides:', FP_TD ),
				'not_found'          => __( 'No Slides found.', FP_TD ),
				'not_found_in_trash' => __( 'No Slides found in Trash.', FP_TD ),
			),
		);

		register_post_type( $post_type, $args );
	}

	/**
	 * Load scripts
	 *
	 * @return void
	 */
	public function load_scripts() {
		wp_register_script( 'slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js', array( 'jquery' ), '1.9.0', true );
		wp_enqueue_script( 'slick' );
	}

	/**
	 * Load Beaver Builder modules
	 *
	 * @return void
	 */
	public function load_bb_modules() {
		if ( class_exists( 'FLBuilderModule' ) ) {
			require_once FPSLIDER_PLUGIN_PATH . 'modules/slider/bbmodule-slider.php';
			require_once FPSLIDER_PLUGIN_PATH . 'modules/accordion-slider/bbmodule-accordion-slider.php';
		}
	}

	/**
	 * Run module code
	 *
	 * @return void
	 */
	public function run() {
		$this->slide->run();
		$this->slider->run();
		$this->accordion_slider->run();
	}
}
