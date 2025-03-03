<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.flowpress.com
 * @since      1.0.0
 *
 * @package    FP_Plugin_Distribution
 * @subpackage FP_Plugin_Distribution/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    FP_Plugin_Distribution
 * @subpackage FP_Plugin_Distribution/admin
 * @author     Jonathan Bouganim <jonathan@flowpress.com>
 */
class FP_Plugin_Distribution_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Singleton instance.
	 *
	 * @var self
	 */
	private static $instance;

	const NOTICES_KEY = 'fp_plugin_dist_notices';
	const AJAX_DISMISS_HOOK = 'fp_plugin_dist_dismiss_ajax';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
	}
    
    /**
	 * Singleton get.
	 * @return this
	 */
	public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

	/**
	 * Display the admin notices
	 *
	 * @return void
	 */
	public function display_admin_notices() {
		if (!current_user_can('manage_options')) {
			return;
		}
		$notices = get_option( self::NOTICES_KEY, [] );
		if (!is_array($notices) || empty($notices)) {
			return;
		}

		foreach($notices as $key => $notice) {
			// Expired. Delete it.
			$expiration = (int) $notice['expiration'];
			if ( ($expiration !== 0) && ( current_time('timestamp') >= (int) $notice['expiration'] ) ) {
				$this->delete_notice($key);
				continue;
			}
			$notice_level = sprintf("notice-%s", $notice['level'] );
			$is_dismissable = (bool) $notice['is_dismissable'] ? ' is-dismissible' : '';
			?>
			<div class="notice fp-plugin-notice <?php echo $notice_level ?><?php echo $is_dismissable ?>" data-notice-key="fp-plugin-notice-<?php echo $key ?>">
        		<p>FP Plugin Distribution: <?php _e( $notice['message'], 'fp-plugin-distribution' ); ?></p>
    		</div>
			<?php
		}
	}

	/**
	 * Delete a notice by key or message
	 *
	 * @param string $key
	 * @param boolean $encode_key
	 * @return boolean
	 */
	public function delete_notice($key = '', $encode_key = false) {
		$key = $encode_key ? md5($key) : $key;
		$notices = get_option( self::NOTICES_KEY, [] );
		if (!is_array($notices) || empty($notices[$key])) {
			return false;
		}
		unset($notices[$key]);
		return update_option(self::NOTICES_KEY, $notices, false);
	}

	/**
	 * Remove all notices
	 *
	 * @return boolean
	 */
	public function delete_all_notices() {
		return delete_option(self::NOTICES_KEY);
	}

	/**
     * AJAX handler to delete the notice from client-side.
     *
     * @return void
     */
    public function ajax_dismiss_notice() {
        if ( empty($_REQUEST['key']) ) {
            wp_send_json_error();
        } else {
            $key = esc_attr( $_REQUEST['key'] );
        }

        $result = $this->delete_notice($key);
        wp_send_json_success($result);
    }

	/**
     * Footer JS to send the AJAX request to delete a notice.
     *
     * @return void
     */
    public function admin_footer_script() {
        ?>
        <script type="text/javascript">
		(function ($) {
            $(document).on( 'click', '.fp-plugin-notice .notice-dismiss', function() {
                var key = $(this).closest('.fp-plugin-notice').data('notice-key');
		        var data = { action: '<?php echo self::AJAX_DISMISS_HOOK ?>', key: key };
		        $.post( '<?php echo get_admin_url() . 'admin-ajax.php' ?>', data, function() {});
            });
		})(jQuery);
        </script>
        <?php
    }

	/**
	 * Add an admin notice
	 *
	 * @param string $message
	 * @param string $level warning | error | info | success
	 * @param integer $expiration in minutes
	 * @param boolean $is_dismissable
	 * @return void
	 */
	public function add_admin_notice($message = '', $level = 'warning', $expiration = 0, $is_dismissable = true ) {
		$expiration = (int) $expiration === 0 ? (int) $expiration : ( (int) $expiration * MINUTE_IN_SECONDS ) + current_time('timestamp');
		$notice = compact("message", "level", "expiration", "is_dismissable");
		$key = md5($message);
		$notices = get_option( self::NOTICES_KEY, [] );
		$notices = !is_array($notices) ? [] : $notices;
		$notices[$key] = $notice;
		update_option(self::NOTICES_KEY, $notices, false);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in FP_Plugin_Distribution_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The FP_Plugin_Distribution_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/fp-plugin-distribution-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in FP_Plugin_Distribution_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The FP_Plugin_Distribution_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/fp-plugin-distribution-admin.js', array( 'jquery' ), $this->version, false );

	}

}
