<?php

namespace FpSlider;

class Init
{
	function __construct()
	{
		add_action('init', [$this, 'register_post_type']);
		add_action('init', [$this, 'load_scripts']);

		require_once FPSLIDER_PLUGIN_PATH . 'generate-acf.php';
		require_once FPSLIDER_PLUGIN_PATH . 'modules/slide/slide.php';
		require_once FPSLIDER_PLUGIN_PATH . 'modules/slider/slider.php';

		add_action('init', [$this, 'load_bb_modules'], 10);

		// Modules
		$this->slide = new Slide();
		$this->slider = new Slider();
	}

	function register_post_type()
	{
		$post_type   = 'slide';
		$name   = ucwords($post_type);
		$plural = $name . 's';

		$args = array(
			'description'        => __('Description.', FP_TD),
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
			'supports'           => array('title', 'thumbnail'),
			'taxonomies'         => array(),
			'labels'             => array(
				'name'               => _x($name, 'post type general name', FP_TD),
				'singular_name'      => _x($name, 'post type singular name', FP_TD),
				'menu_name'          => _x($plural, 'admin menu', FP_TD),
				'name_admin_bar'     => _x($name, 'add new on admin bar', FP_TD),
				'add_new'            => _x('Add New', $name, FP_TD),
				'add_new_item'       => __('Add New ' . $name, FP_TD),
				'new_item'           => __('New ' . $name, FP_TD),
				'edit_item'          => __('Edit ' . $name, FP_TD),
				'view_item'          => __('View ' . $name, FP_TD),
				'all_items'          => __('All ' . $plural, FP_TD),
				'search_items'       => __('Search ' . $plural, FP_TD),
				'parent_item_colon'  => __('Parent ' . $plural . ':', FP_TD),
				'not_found'          => __('No ' . $plural . ' found.', FP_TD),
				'not_found_in_trash' => __('No ' . $plural . ' found in Trash.', FP_TD),
			),
		);

		register_post_type($post_type, $args);
	}

	function load_scripts()
	{
		wp_register_script('slick', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.min.js', ['jquery'], '1.9.0');
		wp_enqueue_script('slick');
	}

	function load_bb_modules()
	{
		if (class_exists('FLBuilderModule')) {
			require_once FPSLIDER_PLUGIN_PATH . 'modules/slider/bbmodule-slider.php';
		}
	}

	public function run()
	{
		$this->slide->run();
		$this->slider->run();
	}
}
