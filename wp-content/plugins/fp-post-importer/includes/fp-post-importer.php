<?php

defined( 'ABSPATH' ) or die( 'Access forbidden!' );

class FP_Post_Importer {

    var $plugin_type;

    function __construct() {
        $this->plugin_type = FP_Post_Importer_Admin::get_plugin_type();
    }

    /**
     * Setup plugin type actions/hooks.
     *
     * @return void
     */
    public function run() {
        
        if ('client' === $this->plugin_type) {
            FP_Post_Importer_Client::init_actions();
        } 
        else if ('host' === $this->plugin_type) {
            FP_Post_Importer_Host::getInstance();
        }    

       // Setup the admin settings page. 
       if (is_admin()) {
            FP_Post_Importer_Admin_Notices::init_hooks();
            $admin_page = new FP_Post_Importer_Admin();
		}
    }

    /**
     * Helper function to get the rest base to build the URL to fetch.
     *
     * @param 	string 	$post_type
     * @return	string	The REST base string to use
     */
    public static function get_post_type_rest_base( $post_type = '' ) {
        $post_type_object = get_post_type_object($post_type);
        return !empty($post_type_object->rest_base) ? $post_type_object->rest_base : $post_type;
    }

    /**
     * Helper function to get the rest URL to fetch.
     *
     * @param 	string 	$post_type
     * @param 	string 	$host_url
     * @return	string	The REST URL
     */
    public static function get_post_type_rest_url( $post_type = '', $host_url = '' ) {
        $host_url = empty($host_url) ? site_url() : $host_url;
        $rest_base = self::get_post_type_rest_base($post_type);
        $api_url = trailingslashit($host_url) . "wp-json/wp/v2/{$rest_base}";
        return $api_url;
    }

    /**
     * Helper function to get the Import by ID rest URL to fetch.
     *
     * @param 	string 	$host_url
     * @return 	string	The REST URL
     */
    public static function get_post_by_id_rest_url( $host_url = '' ) {
        $host_url = empty($host_url) ? site_url() : $host_url;
        $rest_base = self::get_post_type_rest_base($post_type);
        $api_url = trailingslashit($host_url) . "wp-json/wp/v2/{$rest_base}";
        return $api_url;
    }	

	/**
	 * Helper function to aid in performant large array diffs
     * @param Array 	$a		The first array to compare
     * @param Array 	$b		The second array to compare
     * @return Array	$diff	The different array items
	 */
	public static function flip_isset_diff($b, $a) {
		$at = array_flip($a);
		$diff = array();
		foreach ( $b as $i ) {
			if ( !isset($at[$i]) ) {
				$d[] = $i;
			}
		}
		return $diff;
	}

}