<?php

defined( 'ABSPATH' ) or die( 'Access forbidden!' );


if ( class_exists('FP_Post_Importer_Admin_Notices') )
	return false;

/**
 * Internal class to add admin notices to any post type/page.
 * i.e.
 * $notice_board = new FP_Post_Importer_Admin_Notices( $media->postID );
 * $notice_board->add_notice("attachments-move-failed-{$media->attachmentID}", "Failed to upload image {$media->filename}: {$error}", 'error', true);
 */
class FP_Post_Importer_Admin_Notices {

    var $notice_post_id = 0;
    var $notice_type;
    var $notice_message;
    var $notice_key;
    var $is_dismissable;
    var $time;
    var $expiry; // in secconds
    
    var $is_post_notice = false;

    const AJAX_DISMISS_HOOK = 'fppi_dismiss_notice';

	/**
	 * Meta
	 */
    const ADMIN_NOTICES_META_KEY = 'fppi_admin_notices';

    /**
     * Notice to class mappings
     */
    private static $notice_types = array(
        'info' => 'notice-info', // blue
        'warning' => 'notice-warning', // orange
        'error' => 'notice-error', // red
        'success' => 'notice-success', // green
    );

    function __construct( $post_id = 0 ) {
        if ( is_int($post_id) && ($post_id > 0) ) {
            $this->is_post_notice = true;
            $this->notice_post_id = $post_id;
        } else if ( is_string($post_id) ) {
            $this->notice_post_id = $post_id;
        }
        
    }

    /**
	 * Setup our action hooks.
	 *
	 * @return void
	 */
	public static function init_hooks() {
        add_action( 'admin_notices', array(__CLASS__, 'display_notices') );
        add_action( 'admin_footer', array(__CLASS__, 'admin_footer_script') );
        add_action( 'wp_ajax_'.self::AJAX_DISMISS_HOOK, array(__CLASS__, 'ajax_dismiss_notice') );
    }

    /**
     * Create a new notice.
     *
     * @param string $notice_key
     * @param string $notice_msg
     * @param string $notice_type options are info|warning|success|error
     * @param boolean $is_dismissable
     * @param boolean $expiry - time in seconds to delete if it's past
     */
	public function add_notice( $notice_key = '', $notice_message = '', $notice_type = 'info', $is_dismissable = false, $expiry = 0 ) {
        $this->notice_key = $notice_key;
        $this->notice_message = $notice_message;
        $this->notice_type = isset( self::$notice_types[ $notice_type ] ) ? self::$notice_types[ $notice_type ] :  self::$notice_types[ 'info' ]; 
        $this->is_dismissable = $is_dismissable;
        $this->time = current_time('timestamp');
        $this->expiry = $expiry;

        $existing_notices = self::get_notices($this->notice_post_id);
        $existing_notices[ $this->notice_key ] = $this;

        if ($this->is_post_notice) { 
            $result = update_post_meta($this->notice_post_id, self::ADMIN_NOTICES_META_KEY, $existing_notices);
        }  else {
            $result = update_option(self::ADMIN_NOTICES_META_KEY, $existing_notices, false);
        }    

        return $result;
    }

    /**
     * Delete new notice.
     *
     * @param string $notice_key
     * @param string $notice_msg
     * @param string $notice_type options are info|warning|success|error
     * @param boolean $is_dismissable
     */
	public function delete_notice( $notice_key = '' ) {
        $existing_notices = self::get_notices($this->notice_post_id);        
        if ( isset($existing_notices[ $notice_key ]) ) {
            unset( $existing_notices[ $notice_key ] );
        }
        if ($this->is_post_notice) { 
            $result = update_post_meta($this->notice_post_id, self::ADMIN_NOTICES_META_KEY, $existing_notices);
        }  else {
            $result = update_option(self::ADMIN_NOTICES_META_KEY, $existing_notices, false);
        }
        return $result;
    }

    /**
     * Get all notices.
     *
     * @param integer $post_id
     * @return array
     */
    public static function get_notices( $post_id = 0 ) {
        if ($post_id > 0) {
            $existing_notices = get_post_meta($post_id, self::ADMIN_NOTICES_META_KEY, true);
        } else {
            $existing_notices = get_option(self::ADMIN_NOTICES_META_KEY, array());
        }
        $existing_notices = !empty($existing_notices) && is_array($existing_notices) ? $existing_notices : array();
        return $existing_notices;
    }

    /**
     * Get a single notice by key.
     *
     * @param integer $post_id
     * @param string $key
     * @return this
     */
    public static function get_notice( $post_id = 0, $key = '' ) {
        $existing_notices = get_post_meta($post_id, self::ADMIN_NOTICES_META_KEY, true);
        return isset( $existing_notices[ $key ] )  ? $existing_notices[ $key ] : false;
    }

    /**
     * Delete all notices for this post.
     *
     * @param integer $post_id
     * @return void
     */
    public static function clear_notices( $post_id = 0 ) {
        return delete_post_meta($post_id, self::ADMIN_NOTICES_META_KEY);
    }

	/**
     * Hook to display our notices.
     *
     * @return void
     */
    public static function display_notices() {
        
        // parent_base - edit
        // base - post
        $screen = get_current_screen();
        $notices = array();
        $post_notices = array();
        // If it's post specific.
		if (isset($screen->parent_base) && ($screen->parent_base != 'edit')) {
            global $post;
            if (isset($post->ID)) {
                $post_notices = self::get_notices($post->ID);
            }
        }
        
        
        $all_notices = self::get_notices(0);
        $notices = array_merge($all_notices, $post_notices);
        if (empty($notices)) {
            return;
        }

        foreach($notices as $notice_key => $notice) :
            // Skip and delete if the notice is expired.
            if ( is_int($notice->expiry) && ($notice->expiry > 0) ) {
                $total_elapsed = current_time('timestamp') - $notice->time;
                if ($total_elapsed > $notice->expiry) {
                    $tmp_notice = new self($notice->notice_post_id);
                    $tmp_notice->delete_notice( $notice->notice_key );
                    continue;
                }
            }

            // If it's a screen specific notice.
            if (!$notice->is_post_notice && (is_string($notice->notice_post_id)) ) {
                if ($notice->notice_post_id !== $screen->base) {
                    continue;
                }
            }

            $notice_classes = " {$notice->notice_type}";
            $notice_classes .= " fppi-is-" . $notice->notice_key;
            if ($notice->is_dismissable) {
                $notice_classes .= " is-dismissible";
            }
            ?>
            <div class="notice fppi-notice<?php echo $notice_classes ?>" data-notice-key="<?php echo $notice->notice_key ?>">
                <p><?php _e( $notice->notice_message, FP_POST_IMPORTER_LOCALE ); ?></p>
            </div>
        <?php
        endforeach;
    }

    /**
     * AJAX handler to delete the notice from client-side.
     *
     * @return void
     */
    public static function ajax_dismiss_notice() {
        if ( ! isset($_REQUEST['post_id']) ) {
            wp_send_json_error('No Post ID set', 400);
        } else {
            $post_id = (int) $_REQUEST['post_id'];
        }

        if ( empty($_REQUEST['key']) ) {
            wp_send_json_error('No key present in the request', 400);
        } else {
            $key = esc_attr( $_REQUEST['key'] );
        }

        $notice = new self( $post_id );
        $result = $notice->delete_notice($key);
        wp_send_json_success($result);
    }
    
    /**
     * Footer JS to send the AJAX request to delete a notice.
     *
     * @return void
     */
    public static function admin_footer_script() {
        global $post;
        $post_id = (isset($post->ID)) ? $post->ID : 0;
        ?>
        <script type="text/javascript">
		(function ($) {
            $(document).on( 'click', '.fppi-notice .notice-dismiss', function() {
                var key = $(this).closest('.fppi-notice').data('notice-key');
		        var data = { action: '<?php echo self::AJAX_DISMISS_HOOK ?>', post_id: '<?php echo $post_id ?>', key: key };
		        $.post( '<?php echo get_admin_url() . 'admin-ajax.php' ?>', data, function() {});
	        });
		})(jQuery);
        </script>
        <?php
    }
}