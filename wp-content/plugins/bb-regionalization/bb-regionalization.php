<?php
/*
 Plugin Name: Beaver Builder Regionalization
 Description: Allows regionalizing beaver builder modules.
 Author: Jonathan Bouganim
 Version: 1.9.2
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class FP_BB_Module_Regionalization {



	var $regions;
	var $bb_settings;

	const TRANSIENT_ALL_REGIONS = 'fp_bb_all_regions';
	const TRANSIENT_CHILD_ARRAY = 'fp_bb_parsed_child_array';

	/**
	* Key where the plugin settings are saved.
	*/
	const SETTINGS_KEY = 'fp-bb-region';

	/**
	 * Initialize our plugin add our hooks/filters.
	 */
	function __construct() {
		// Priority `1` here is key, most post types/taxonomies are loaded with priority 0, the core BB elements are loaded before priority 5.
		add_action( 'init', array( $this, 'init_actions' ), 1 );
		// add_action('save_post_store', array($this, 'update_hierarchical_regions') );
		if ( isset( $_GET['fl_builder'] ) ) {
			add_filter( 'fl_builder_register_settings_form', array( $this, 'add_regions_tab' ), 99, 2 );
		}

		add_action( 'clear_bb_regionalization_regions', array( $this, 'update_hierarchical_regions' ) );

		if ( ! wp_next_scheduled( 'clear_bb_regionalization_regions' ) ) {
			wp_schedule_event( time(), 'daily', 'clear_bb_regionalization_regions' );
		}
	}

	public function init_actions() {
		$this->regions = self::get_hierarchical_regions();
		if ( empty( $this->regions ) ) {
			add_action( 'admin_notices', array( __CLASS__, 'add_admin_notice' ) );
			return;
		}

		$this->settings = self::get_bb_settings();
		$filter_suffix  = isset( $this->settings['include_children'] ) && ( true === $this->settings['include_children'] ) ? '_reverse' : '';

		if ( isset( $_GET['fl_builder'] ) ) {
			// add_filter('fl_builder_register_settings_form', array($this, 'add_regions_tab'), 999, 2);
			add_filter( 'wp_head', array( __CLASS__, 'render_region_stylesheet_rules' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_fl_builder_js' ) );
			// Sets the filter and selection child options.
			add_filter( 'fl_builder_module_attributes', array( $this, 'add_custom_module_classes' . $filter_suffix ), 10, 2 );
			add_filter( 'fl_builder_row_attributes', array( $this, 'add_custom_module_classes' . $filter_suffix ), 10, 2 );
			add_filter( 'fl_builder_column_attributes', array( $this, 'add_custom_module_classes' . $filter_suffix ), 10, 2 );
		}

		add_filter( 'fl_builder_is_node_visible', array( $this, 'filter_visible_nodes' . $filter_suffix ), 10, 2 );

		// Setup the admin settings page within BB Settings.
		if ( is_admin() && isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], array( 'fl-builder-settings', 'fl-builder-multisite-settings' ) ) ) {
			add_filter( 'fl_builder_admin_settings_nav_items', array( __CLASS__, 'admin_settings_nav_items' ) );
			add_action( 'fl_builder_admin_settings_render_forms', array( __CLASS__, 'admin_settings_render_forms' ) );
			add_action( 'fl_builder_admin_settings_save', array( __CLASS__, 'save_bb_settings' ) );
		}

		// Set default store / region
		add_filter(
			'fp_bb_get_current_region',
			function ( $default_region ) {
				global $my_store_data;
				if ( ! empty( $my_store_data['post']->post_name ) ) {
					return $my_store_data['post']->post_name;
				}
				$default_store = get_field( 'default_store', 'option' );
				if ( ! empty( $default_store->post_name ) ) {
					return $default_store->post_name;
				}
				return $default_region;
			}
		);
	}

	/**
	 * Adds the white label nav items to the admin settings.
	 *
	 * @since 1.8
	 * @param array $nav_items
	 * @return array
	 */
	public static function admin_settings_nav_items( $nav_items ) {
		if ( method_exists( 'FLBuilderAdminSettings', 'multisite_support' ) ) {
			$nav_items[ self::SETTINGS_KEY ] = array(
				'title'    => __( 'Regionalization', 'fl-builder' ),
				'show'     => is_network_admin() || ! FLBuilderAdminSettings::multisite_support(),
				'priority' => 525,
			);
		}

		return $nav_items;
	}

	/**
	 * Returns the settings for the builder's help button.
	 *
	 * @since 1.4.9
	 * @return array
	 */
	public static function get_bb_settings() {
		$defaults = self::get_bb_regionalization_defaults();
		if ( method_exists( 'FLBuilderModel', 'get_admin_settings_option' ) ) {
			$value    = FLBuilderModel::get_admin_settings_option( '_fl_builder_' . self::SETTINGS_KEY, false );
			$defaults = apply_filters( 'fl_builder_bb_reg_settings', $defaults );
		}

		return false === $value ? $defaults : $value;
	}

	/**
	 * Returns the default settings for the builder's help button.
	 *
	 * @since 1.4.9
	 * @return array
	 */
	public static function get_bb_regionalization_defaults() {
		$defaults = array(
			'include_children' => true,
		);

		return $defaults;
	}

	/**
	 * Renders the admin settings white label forms.
	 *
	 * @since 1.8
	 * @return void
	 */
	public static function admin_settings_render_forms() {
		$template_path = plugin_dir_path( __FILE__ ) . 'templates/admin-settings-regionalization.php';
		include $template_path;
	}

	/**
	 * Saves the branding settings.
	 *
	 * @since 1.0
	 * @access private
	 * @return void
	 */
	public static function save_bb_settings() {
		if ( isset( $_POST['fl-fp-bb-region-nonce'] ) && wp_verify_nonce( $_POST['fl-fp-bb-region-nonce'], self::SETTINGS_KEY ) ) {

			$settings                     = self::get_bb_regionalization_defaults();
			$settings['include_children'] = isset( $_POST['fl-fp-bb-region-child-enabled'] ) ? true : false;

			FLBuilderModel::update_admin_settings_option( '_fl_builder_' . self::SETTINGS_KEY, $settings, false );
		}
	}

	/**
	 * Add the admin notice that the filter is missing.
	 *
	 * @return void
	 */
	public static function add_admin_notice() {
		if ( ! is_admin() ) {
			return;
		}
		?>
		<div class="notice notice-error is-dismissible">
			<p><?php _e( 'Beaver Builder Regionalization: missing regions set via `fp_bb_get_all_regions` filter.', 'fp_bb_region' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Output CSS for filter and module borders.
	 */
	public static function render_region_stylesheet_rules() {
		?>
		<style type="text/css">
			.fl-builder--saving-indicator {
				min-width: unset !important;
			}

			.fl-theme-builder-preview-select.fl-builder-button {
				flex-basis: auto !important;
			}

			.fp-region-module-visible {
				border: 3px dashed green;
			}

			.fp-region-module-visible.fl-row {
				border: 3px dotted green;
			}

			.fp-region-module-hidden {
				border: 3px dashed red;
			}

			.fp-region-module-hidden.fl-row {
				border: 3px dotted red;
			}

			.bb_region_filter {
				flex-basis: unset !important;
				padding: 0 5px;
			}

			.bb_region_filter select.fp-region-dropdown {
				flex-basis: 100%;
				max-width: 150px;
				font-size: 13px;
			}

			.bb_region_filter label {
				line-height: 33px;
				font-size: 13px;
				margin-right: 8px;
				color: #000;
				display: flex;
				justify-content: flex-end;
				font-style: italic;
			}

			.bb_region_filter select:after {
				content: "&#8681;";
				font-size: 12px;
				color: white;
			}

			@media (min-width: 980px) and (max-width: 1020px) {
				.fl-builder-bar-title {
					flex-shrink: 1;
				}

				.bb_region_filter select {
					width: 140px;
				}
			}

			@media (max-width: 870px) {
				.bb_region_filter select {
					width: 130px;
				}
			}

			@media (max-width: 750px) {
				.bb_region_filter select {
					width: 100px;
				}
			}

			@media (max-width: 720px) {
				.fl-builder-bar-title {
					flex: 0 0 80px !important;
				}

				.fl-builder-bar-title-area {
					display: none;
				}
			}
		</style>
		<?php
	}

	/**
	 * Enqueue and localize our JS.
	 */
	public function enqueue_fl_builder_js() {
		$localized_vars = array(
			'regions'          => $this->get_regions_filter(),
			'default_region'   => self::get_default_region(),
			'child_regions'    => $this->create_child_array(),
			'include_children' => isset( $this->settings['include_children'] ) && ( true === $this->settings['include_children'] ) ? 'true' : 'false',
		);

		wp_register_script( 'fp-bb-module-regionalization', plugins_url( 'js/bb-module-regionalization.js', __FILE__ ), array( 'jquery' ), '123' );
		wp_localize_script( 'fp-bb-module-regionalization', 'fp_bb_regionalization', $localized_vars );
		// Enqueued script with localized data.
		wp_enqueue_script( 'fp-bb-module-regionalization' );
	}

	/**
	 * Filter modules from displaying on the front-end based on their region visibility settings.
	 * filter `fl_builder_is_node_visible`
	 *
	 * @param  bool   $is_visible To display or not.
	 * @param  object $node module object.
	 * @return bool
	 */
	public function filter_visible_nodes( $is_visible, $node ) {
		// Should return all region/subregions it is visible to.
		$current_region = self::get_current_region();

		// If no regions are specifically selected for visibility, defaults to all regions
		if ( empty( $node->settings->visible_regions ) && empty( $node->settings->hidden_regions ) ) {
			return true;
		} elseif ( ! empty( $node->settings->visible_regions ) ) {
			$current_child_regions = $this->get_children_of( $current_region );
			$current_child_regions = array_keys( $current_child_regions );
			$is_visible            = (bool) count( array_intersect( $current_child_regions, $node->settings->visible_regions ) );
		}
		// override the is_visible if the same region is in the hidden list
		if ( ! empty( $node->settings->hidden_regions ) && $is_visible ) {
			$current_regions = array( $current_region );
			$is_visible      = ! (bool) count( array_intersect( $current_regions, $node->settings->hidden_regions ) );
		}
		return $is_visible;
	}

	/**
	 * Filter modules from displaying on the front-end based on their region visibility settings.
	 * filter `fl_builder_is_node_visible`
	 *
	 * @param  bool   $is_visible To display or not.
	 * @param  object $node module object.
	 * @return bool
	 */
	public function filter_visible_nodes_reverse( $is_visible, $node ) {
		// Should return all region/subregions it is visible to.
		$current_region = self::get_current_region();

		// If no regions are specifically selected for visibility, defaults to all regions
		if ( empty( $node->settings->visible_regions ) && empty( $node->settings->hidden_regions ) ) {
			return true;
		} elseif ( ! empty( $node->settings->visible_regions ) ) {
			$applicable_visible_regions = array();
			// Get all the children region for this parent
			foreach ( $node->settings->visible_regions as $visible_region ) {
				$visible_region_children    = $this->get_children_of( $visible_region );
				$visible_region_children    = array_keys( $visible_region_children );
				$applicable_visible_regions = array_merge( $applicable_visible_regions, $visible_region_children );
			}
			// Intersect with the current region and it's children
			$current_child_regions = $this->get_children_of( $current_region );
			$current_child_regions = array_keys( $current_child_regions );
			$is_visible            = (bool) count( array_intersect( $current_child_regions, $applicable_visible_regions ) );
		}
		// override the is_visible if the same region is in the hidden list
		if ( ! empty( $node->settings->hidden_regions ) && $is_visible ) {
			$applicable_hidden_regions = array();
			// Get all the children region for this parent
			foreach ( $node->settings->hidden_regions as $hidden_region ) {
				$hidden_region_children    = $this->get_children_of( $hidden_region );
				$hidden_region_children    = array_keys( $hidden_region_children );
				$applicable_hidden_regions = array_merge( $applicable_hidden_regions, $hidden_region_children );
			}
			$current_regions = array( $current_region );
			$is_visible      = ! (bool) count( array_intersect( $current_regions, $applicable_hidden_regions ) );
		}
		return $is_visible;
	}

	/**
	 * Add custom classes to modules, rows and columns for easy JS/CSS filtering.
	 * filter `fl_builder_row_attributes`,`fl_builder_column_attributes`,`fl_builder_module_attributes`
	 *
	 * @param [array]  $attrs. $el attributes
	 * @param [object] $module. Row, column or module node.
	 */
	public function add_custom_module_classes( $attrs, $module ) {
		$attrs['class'][] = 'fp-region-module';

		if ( ! empty( $module->settings->visible_regions ) ) {
			$attrs['class'][] = 'fp-region-module-visible';
			// get all visible and child visible regions
			foreach ( $module->settings->visible_regions as $visible_region ) {
				$visible_region   = sanitize_title( $visible_region );
				$attrs['class'][] = "fp-region-visible-{$visible_region}";
			}
		} else {
			$attrs['class'][] = 'fp-region-visible-no-selection';
		}

		if ( ! empty( $module->settings->hidden_regions ) ) {
			$attrs['class'][] = 'fp-region-module-hidden';
			foreach ( $module->settings->hidden_regions as $hidden_region ) {
				$hidden_region    = sanitize_title( $hidden_region );
				$attrs['class'][] = "fp-region-hidden-{$hidden_region}";
			}
		}
		return $attrs;
	}

	/**
	 * Not currently used, previous way of filtering modules on the back-end. Here if we want to revert.
	 * Add custom classes to modules reversed, rows and columns for easy JS/CSS filtering.
	 * filter `fl_builder_row_attributes`,`fl_builder_column_attributes`,`fl_builder_module_attributes`
	 *
	 * @param [array]  $attrs. $el attributes
	 * @param [object] $module. Row, column or module node.
	 */
	public function add_custom_module_classes_reverse( $attrs, $module ) {
		$attrs['class'][] = 'fp-region-module';

		if ( ! empty( $module->settings->visible_regions ) ) {
			$attrs['class'][] = 'fp-region-module-visible';
			// get all visible and child visible regions
			foreach ( $module->settings->visible_regions as $visible_region ) {
				$visible_child_regions = $this->get_children_of( $visible_region );
				foreach ( $visible_child_regions as $visible_child_region_key => $visible_child_region ) {
					$visible_region   = sanitize_title( $visible_child_region_key );
					$attrs['class'][] = "fp-region-visible-{$visible_region}";
				}
			}
		} else {
			$attrs['class'][] = 'fp-region-visible-no-selection';
		}

		if ( ! empty( $module->settings->hidden_regions ) ) {
			$attrs['class'][] = 'fp-region-module-hidden';
			foreach ( $module->settings->hidden_regions as $hidden_region ) {
				$hidden_child_regions = $this->get_children_of( $hidden_region );
				foreach ( $hidden_child_regions as $hidden_child_region_key => $hidden_child_region ) {
					$hidden_region    = sanitize_title( $hidden_child_region_key );
					$attrs['class'][] = "fp-region-hidden-{$hidden_region}";
				}
			}
		}
		return $attrs;
	}

	/**
	 * Add regions tab to all Beaver Builder modules.
	 * bb_filter `fl_builder_register_settings_form`.
	 *
	 * @param array   $form BB form.
	 * @param form id $id.
	 */
	public function add_regions_tab( $form, $id ) {
		if ( isset( $form ) ) {
			if ( false === ( $regions = get_transient( 'bb_regionalization_flattened_regions' ) ) ) {
				// Cache the regions as this was adding a lot of overhead when opening each module in beaver builder
				if ( empty( $this->regions ) ) {
					// error_log( __CLASS__ . ": Missing regions, cannot add tab.");
					return $form;
				}
				$regions = self::flatten_regions( $this->regions );
				set_transient( 'bb_regionalization_flattened_regions', $regions, 1 * HOUR_IN_SECONDS );
			}
			$field = array(
				'title'    => __( 'Regions', 'fl-builder' ),
				'sections' => array(
					'regions' => array(
						'title'       => 'Regions',
						'description' => 'Choose region(s) this module will be visible/hidden to.',
						'fields'      => array(
							'visible_regions' => array(
								'type'         => 'select',
								'label'        => __( 'Visible Regions', 'fl-builder' ),
								'description'  => 'If none are selected, this module will be visible to all regions.',
								'options'      => $regions,
								'multi-select' => true,
							),
							'hidden_regions'  => array(
								'type'         => 'select',
								'label'        => __( 'Hidden Regions', 'fl-builder' ),
								'options'      => $regions,
								'multi-select' => true,
							),
						),
					),
				),
			);

			if ( $id === 'row' || $id === 'col' ) {
				if ( ! empty( $form['tabs'] ) ) {
					$form['tabs']['regions'] = $field;
				} else {
					$form['tabs']            = array();
					$form['tabs']['regions'] = $field;
				}
			} else {
				$form['regions'] = $field;
			}
		}
		return $form;
	}

	/**
	 * Get all region with extra options for the filters on the fl_builder end.
	 *
	 * @return array
	 */
	public function get_regions_filter() {
		$defaults = array(
			'all'  => 'All Modules',
			'none' => 'No Region',
		);
		$regions  = self::flatten_regions( $this->regions );
		return array_merge( $defaults, $regions );
	}

	/**
	 * Returns all regions.
	 * wp_filter `fp_bb_get_all_regions` to override this.
	 *
	 * @return array
	 */
	public static function get_hierarchical_regions() {
		$all_regions = get_transient( self::TRANSIENT_ALL_REGIONS );
		if ( ! empty( $all_regions ) ) {
			return apply_filters( 'fp_bb_get_all_regions', $all_regions );
		}

		$all_regions = get_option( self::TRANSIENT_ALL_REGIONS );
		if ( ! empty( $all_regions ) ) {
			return apply_filters( 'fp_bb_get_all_regions', $all_regions );
		}

		$all_regions = self::update_hierarchical_regions();
		$all_regions = apply_filters( 'fp_bb_get_all_regions', $all_regions );
		return $all_regions;
	}

	/**
	 * Returns all regions.
	 * wp_filter `fp_bb_get_all_regions` to override this.
	 *
	 * @return array
	 */
	public static function update_hierarchical_regions() {
		$all_regions = self::get_hierarchical_default_regions();
		update_option( self::TRANSIENT_ALL_REGIONS, $all_regions, false );
		set_transient( self::TRANSIENT_ALL_REGIONS, $all_regions );
		return $all_regions;
	}

	/**
	 * Returns all possibilites for child regions, used for localizing this client-side.
	 *
	 * @return array
	 */
	public function create_child_array() {
		if ( false !== ( $child_array = get_transient( self::TRANSIENT_CHILD_ARRAY ) ) ) {
			return $child_array;
		}

		$all_regions = self::flatten_regions( $this->regions, 0, '' );
		$child_array = array();
		foreach ( $all_regions as $key => $value ) {
			$children = self::get_children_of( $key );
			if ( count( $children ) >= 1 ) {
				$child_array[ $key ] = array_keys( $children );
			}
		}
		set_transient( self::TRANSIENT_CHILD_ARRAY, $child_array );
		return $child_array;
	}

	/**
	 * Helper function for `get_child_regions`, provided a key, return the child regions.
	 *
	 * @param  string $name
	 * @return array
	 */
	public function get_children_of( $name = '' ) {
		if ( empty( $name ) ) {
			return self::flatten_regions( $this->regions, 0, '' );
		}

		$children = self::get_child_regions( $name, $this->regions );
		return $children;
	}

	/**
	 * Similar to array_search but returns child regions for a specified hierarchical array.
	 *
	 * @param  string  $name  unique key to search for.
	 * @param  array   $input hierarchical array.
	 * @param  integer $level current level of recurision, used to determine if we have found the key and are nesting through that.
	 * @return array  Returns child regions for a specified hierarchical array.
	 */
	public static function get_child_regions( $name = '', $input = array(), $level = 0 ) {
		$children = array();
		foreach ( $input as $key => $value ) {
			// if next level is an array, add the current & recurse
			if ( is_array( $value ) ) {
				// provided there is no key for an multi-dimensional array, we create one.
				$slug = sanitize_title( $key );
				if ( $slug == $name || $level > 0 ) {
					$children[ $slug ] = "{$key}";
					$children          = array_merge( $children, self::get_child_regions( $name, $value, $level + 1 ) );
				} else {
					$children = array_merge( $children, self::get_child_regions( $name, $value, 0 ) );
				}
			} else {
				if ( $key == $name || $level > 0 ) {
					$slug              = sanitize_title( $key );
					$children[ $slug ] = "{$value}";
				}
			}
		}
		return $children;
	}

	/**
	 * Recursively flatten hierarchical/multi-dimensional array into a single level array with indentation for better readibility.
	 *
	 * @param  array   $input Input array to flatten.
	 * @param  integer $level current level of recurision, used for indentation.
	 * @param  string  $indent_char Indentation character.
	 * @return array One level array with all regions.
	 */
	public static function flatten_regions( $input = array(), $level = 0, $indent_char = '-' ) {
		$flattened_array = array();
		foreach ( $input as $key => $value ) {
			if ( is_array( $value ) ) {
				// provided there is no key for an multi-dimensional array, we create one.
				$slug                     = sanitize_title( $key );
				$indent                   = str_repeat( $indent_char, $level );
				$flattened_array[ $slug ] = ! empty( $indent ) ? "{$indent} {$key}" : $key;
				$flattened_array          = array_merge( $flattened_array, self::flatten_regions( $value, $level + 1, $indent_char ) );
			} else {
				$indent                   = str_repeat( $indent_char, $level );
				$slug                     = sanitize_title( $key );
				$flattened_array[ $slug ] = ! empty( $indent ) ? "{$indent} {$value}" : $value;
			}
		}
		return $flattened_array;
	}

	/**
	 * Should return all region/subregions it is visible to.
	 * wp_filter `fp_bb_get_current_region` to override this.
	 *
	 * @return string returns the current region.
	 */
	public static function get_current_region() {
		$default_region = self::is_testing_mode() ? ( ! empty( $_GET['fp_bb_region'] ) ? trim( $_GET['fp_bb_region'] ) : self::get_default_region() ) : '';
		return apply_filters( 'fp_bb_get_current_region', $default_region );
	}

	/**
	 * Helper function for testing.
	 * Country/state/store - Sample regions
	 *
	 * @return [array]
	 */
	public static function get_hierarchical_default_regions() {
		$regions = array(
			'Canada'        => array(
				'Ontario'          => array(
					'toronto'      => 'Toronto',
					'vaughan'      => 'Vaughan',
					'richmondhill' => 'Richmond Hill',
				),
				'Alberta'          => array(
					'edmonton' => 'Edmonton',
					'calgary'  => 'Calgary',
					'reddeer'  => 'Red Deer',
				),
				'British Columbia' => array(
					'vancouver' => 'Vancouver',
					'victoria'  => 'Victorai',
					'kelowna'   => 'Kelowna',
				),
			),
			'United States' => array(
				'California' => array(
					'sanfrancisco' => 'San Francisco',
					'losangeles'   => 'Los Angeles',
					'sandiego'     => 'San Diego',
				),
				'New York'   => array(
					'newyork' => 'New York',
					'buffalo' => 'Buffalo',
					'albany'  => 'Albany',
				),
			),
		);

		if ( ! class_exists( 'StoreLocator\GetStoreData' ) ) {
			return $regions;
		}

		$GetStoreData  = new StoreLocator\GetStoreData();
		$store_regions = array();
		$stores        = $GetStoreData->get_stores( 'store', 500 );
		foreach ( $stores as $key => $store ) {
			if ( $store->store_region ) {
				$store_regions[ $store->store_region->name ][ $store->store_sub_region->name ][ $store->post_name ] = $store->post_title;
			}
		}

		return $store_regions;
	}

	/**
	 * Helper function for testing.
	 *
	 * @return string Returns the default region.
	 */
	public static function get_default_region() {
		return 'on';
	}

	/**
	 * Helper function to enable sample regions.
	 *
	 * @return boolean
	 */
	public static function is_testing_mode() {
		return ( defined( 'FP_BB_REGION_DEBUG' ) && ( FP_BB_REGION_DEBUG == true ) );
	}
}
$fp_bb_regionalization = new FP_BB_Module_Regionalization();
