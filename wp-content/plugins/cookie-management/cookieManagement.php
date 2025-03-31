<?php
/**
 * Plugin Name: Sobeys Cookie Management
 * Description: A simple cookie management plugin using OOP.
 * Version: 1.0
 * Author: Your Name
 */

if (!defined('ABSPATH')) {
    exit; // Prevent direct access
}

// Define Plugin Path Constant
define('SOBEYS_PLUGIN_DIR', plugin_dir_path(__FILE__));

class Sobeys_Cookie_Manager {

    // Constructor
    public function __construct() {
        // Register Activation Hook
        register_activation_hook(__FILE__, array($this, 'activate'));

        // Add Shortcode
        add_shortcode('sobeys_cookie_shortcode', array($this, 'render_cookie_banner'));

        // Enqueue Scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    // Plugin Activation Function
    public function activate() {
        add_option('sobeys_plugin_activated', true);
    }

    // Render the Cookie Banner Shortcode
    public function render_cookie_banner() {
        include SOBEYS_PLUGIN_DIR . 'view/cookie-management.php';
    }
    

    public function enqueue_scripts() {
        wp_enqueue_style('sobeys-cookie-style', plugins_url('assets/style.css', __FILE__));
        wp_enqueue_script('sobeys-cookie-script', plugins_url('assets/script.js', __FILE__), array('jquery'), null, true);
    }
}

// Initialize Plugin
new Sobeys_Cookie_Manager();
    