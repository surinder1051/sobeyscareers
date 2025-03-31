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

require_once dirname(__FILE__) . '/includes.php';
require_once dirname(__FILE__) . '/sobeys-constants.php';

class Sobeys_Careers_Plugin {
    public function __construct() {    
        add_action('admin_head', [$this, 'site_scripts']);
        add_action('wp_head', [$this, 'site_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'sobeys_admin_enqueue_styles_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'sobeys_enqueue_styles_scripts']);
    }

   /* Site scripts variable */
   public function site_scripts() { ?>
    <script type="text/javascript">
        var AjaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>";
    </script>
    <?php }

    /* Site scripts & style */
    public function sobeys_admin_enqueue_styles_scripts(){
        wp_enqueue_script('sobeys-admin-script', TRU_PLUGIN_URL . '/assets/js/admin-script.js', array('jquery'), date('U'), true);
    }
     /**  Function to load scripts */
     public function sobeys_enqueue_styles_scripts(){
        wp_enqueue_style( 'sobeys-front-style', TRU_PLUGIN_URL . '/assets/css/style.css', null, date('U'), 'all' );
        wp_enqueue_script('sobeys-frontend-script', TRU_PLUGIN_URL . '/assets/js/frontend-script.js', array('jquery'), date('U'), true);
    }
}

// Initialize the plugin
new Sobeys_Careers_Plugin();


