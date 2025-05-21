<?php
/*
    Plugin Name: Sobeys Careers
    Plugin URI: https://www.truinc.com/
    Description: Sobeys Careers plugin for job searching
    Version: 1.0
    Author: Sobeys Careers
    Author URI: https://www.truinc.com/
    License: GPL2
    Text Domain: sobeys-careers
*/

namespace TRU\SOBYES_CAREERS;

if ( ! defined( 'ABSPATH' ) ) {
   die;
}

class Sobeys_Careers_Plugin {
    public function __construct() {  
        $this->load_dependencies();  
        add_action('admin_enqueue_scripts', [$this, 'tru_admin_enqueue_styles_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'tru_enqueue_styles_scripts']);
    }
    private function load_dependencies() {
        require_once dirname(__FILE__) . '/sobeys-constants.php';
        require_once dirname(__FILE__) . '/includes.php';
    }

    /** 
        *Site Admin specific scripts 
    */
    public function tru_admin_enqueue_styles_scripts(){
        if (!isset($_GET['page'])) return;
        if ($_GET['page'] === 'sobeys-careers' || $_GET['page'] === 'sobeys-slider-settings') {
            wp_enqueue_script('sobeys-admin-script', TRU_PLUGIN_URL . '/assets/js/admin-script.js', array('jquery'), date('U'), true);
            wp_localize_script('sobeys-admin-script', 'Ajax',
                array( 
                    'url' => admin_url( 'admin-ajax.php' ),
                    'nonce' => wp_create_nonce( 'sobeys' )
                )
            );
            wp_enqueue_media();
        } 
    }
    /**  
        *Site Frontend specific scripts & style 
    */
    public function tru_enqueue_styles_scripts(){
        wp_enqueue_script( 'wp-util' );
		wp_localize_script( 'wp-util', 'Ajax',
		    array( 
			   'url' => admin_url( 'admin-ajax.php' ),
			   'nonce' => wp_create_nonce( 'sobeys' )
		    )
		);
        wp_enqueue_style( 'sobeys-front-style', TRU_PLUGIN_URL . '/assets/css/style.css', null, date('U'), 'all' );
        wp_register_style( 'sobeys-slick-css', TRU_PLUGIN_URL . '/assets/css/slick.css', null, date('U'), 'all' );
        wp_register_script('sobeys-slick-js', TRU_PLUGIN_URL . '/assets/js/slick.js', array('jquery'), '1.0.0', true);
        wp_enqueue_script('sobeys-frontend-script', TRU_PLUGIN_URL . '/assets/js/frontend-script.js', array('jquery'), date('U'), true);
        // Default fallback values
        $translations = array(
            'view_details'  => __('View Details', 'sobeys-careers'),
            'share'         => __('Share', 'sobeys-careers'),
            'interested'    => __('I’m Interested', 'sobeys-careers'),
            'information'   => __('Information', 'sobeys-careers'),
            'requisition'   => __('Requisition ID', 'sobeys-careers'),
            'career_group'  => __('Career Group', 'sobeys-careers'),
            'job_category'  => __('Job Category', 'sobeys-careers'),
            'travel'        => __('Travel Requirements', 'sobeys-careers'),
            'job_type'      => __('Job Type', 'sobeys-careers'),
            'country'       => __('Country', 'sobeys-careers'),
            'state'         => __('Province', 'sobeys-careers'),
            'city'          => __('City', 'sobeys-careers'),
        );

        // If Polylang is active, use pll__() for translations
        if (function_exists('pll__')) {
            foreach ($translations as $key => $value) {
                $translations[$key] = pll__($value);
            }
        }
        wp_localize_script('sobeys-frontend-script', 'sobeys', $translations);
    }
}

// Initialize the plugin
new Sobeys_Careers_Plugin();


