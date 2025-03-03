<?php

defined( 'ABSPATH' ) or die( 'Access forbidden!' );

class FP_Post_Importer_Client {

    const IMPORTED_META_HOST = '_fppi_imported_host';
    const IMPORTED_META_POST = '_fppi_imported_postID';
    const MEDIA_GALLERY_META = 'media_gallery';
    const IMPORTED_META_ATTACHMENT = '_fppi_imported_attachmentURL';
    const IMPORT_PAGE_MAX = '10';

    /**
     * Import page specific required data.
     */
    var $import_post_type = false;
    var $import_lang = false;
    var $host_url;

    /**
     * Import loop specific required data.
     */
    var $action_counts = [];
    var $latest_published_post_date = 0;
    var $log_file;

    /**
     * Option Data, does not update with import.
     */
    var $importable_post_types;
    var $importable_langs;
    var $plugin_type;
    var $import_tags;
    var $host_settings;
    var $include_method;
    var $api_token = false;

    /**
     * Variable import option data.
     */
    var $download_attachments = false;
    var $should_append_terms = false;
    var $should_skip_unmodified = false;
    var $last_modified_cursor = false;
    var $import_post_ids = [];
    var $translated_post_ids = [];
    var $start_page = 1;
    var $job_type = 'manual';
    var $taxonomies_to_ignore = [ 'ds_client_group', 'yst_prominent_words' ];

    /**
	 * Singleton instance.
	 *
	 * @var self
	 */
	private static $instance;
    
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

    function __construct() {
        self::$instance = $this;

        // Hooks to log import actions.
        $this->setup_fetch_hooks();

        $current_filter = current_filter();
        
		if (!empty($current_filter)) {
            $this->setup_instance();
        } 
    }

    /**
     * Add hooks to capture log output during a fetch.
     * Not needed if in CLI as it has it's own loggers.
     *
     * @return void
     */
    public function setup_fetch_hooks() {
        if ( defined('WP_CLI') && WP_CLI ) {
            return;
        } else {
            // Load our actions to capture all import actions.
            add_action('fppi_importer_header', array( $this, 'header_logger'));
            add_action('fppi_importer_deleted', array( $this, 'logger'));
            add_action('fppi_importer_inserted', array( $this, 'logger'), 10, 2);
            add_action('fppi_importer_adding_featured_image', array( $this, 'logger'));
            add_action('fppi_importer_updated', array( $this, 'logger'));
            add_action('fppi_importer_skipped', array( $this, 'logger'),10,2);
            add_action('fppi_importer_failed', array( $this, 'logger'));
            add_action('fppi_importer_exception', array( $this, 'error_logger'));
            add_action('fppi_importer_debug', array( $this, 'debug_logger'));
            add_action('fppi_importer_footer', array( $this, 'footer_logger'));
            add_action('fppi_importer_summary', array( $this, 'summary_logger'));
            add_action('fppi_delete_summary', array( $this, 'delete_summary_logger'));
            add_action('fppi_importer_post_log', array( $this, 'ol_logger'));
        } 
    }

    public function setup_instance() {
        if (!empty($this->host_settings)) {
            return;
        }
        $this->plugin_type = FP_Post_Importer_Admin::get_plugin_type();
        $this->host_url = FP_Post_Importer_Admin::get_host_url();
        $this->host_settings = FP_Post_Importer_Admin::get_client_host_settings();
        $this->last_modified_cursor = FP_Post_Importer_Admin::get_client_import_cursor();
        
        $this->importable_post_types = FP_Post_Importer_Admin::get_importable_cpt();
        $this->importable_langs = FP_Post_Importer_Admin::get_importable_lang();
        $this->api_token = FP_Post_Importer_Admin::get_host_token();
        $this->download_attachments = (FP_Post_Importer_Admin::get_download_attachment_option() == 'true');
        $this->should_append_terms = (FP_Post_Importer_Admin::get_append_terms_option() == 'true');
        $this->should_skip_unmodified = (FP_Post_Importer_Admin::get_skip_unmodified_option() == 'true');

        // No need to import all posts, running it all host side for now.
        //$this->include_method = FP_Post_Importer_Admin::get_include_filter_method();
        $this->include_method = 'host';
        
        $this->import_tags = FP_Post_Importer_Admin::get_importable_cpt_tags();
        $this->action_counts = $this->fetch_action_counts();

        if (defined( 'DOING_CRON' ) && DOING_CRON) {
            $this->job_type = 'cron';
        } else if ( defined('WP_CLI') && WP_CLI ) {
            $this->job_type = 'CLI';
        }
        
        if ( $this->plugin_type !== 'client' ) {
            do_action('fppi_importer_exception', "Invalid client type, you can not delete posts as a host");
            throw new Exception('FPPI: Invalid client type, you can not import posts as a host', 405);
       }
    }

    /**
     * Setup action hooks/filters for client / always initialized.
     *
     * @return void
     */
    public static function init_actions() {
        add_action( 'before_delete_post', array(__CLASS__, 'delete_post_attachments') );
        add_action( 'admin_notices', array(__CLASS__, 'maybe_add_admin_error_notice') );
        // When debugging, log to object cache.
        //add_filter( 'fppi_log_to_file', '__return_false');

        // Importer action scheduler hooks
        $importer = self::getInstance();
        add_action( 'rest_api_init', array(&$importer, 'register_routes' ) );
        add_action( FP_Post_Importer_Admin::SCHEDULED_HOOK.'-cron', array($importer, 'run_cron_import' ) );
        // Importer specific hooks
        add_action( 'fppi_check_post_count', array(&$importer, 'check_post_count'), 10, 2 );
        add_action( 'fppi_delete_posts', array(&$importer, 'delete_posts'), 10, 2 );
        add_action( 'fppi_start_import', array(&$importer, 'start_import_log'), 10, 1 );
        add_action( 'fppi_import_page', array(&$importer, 'fetch_page'), 10, 6 );
        add_action( 'fppi_end_import', array(&$importer, 'end_import_log') );
        add_action( 'fppi_output_summary', array(&$importer, 'output_summary_log') );
    }

    /**
     * Hook to run the job via cron.
     *
     * @return void
     */
    public function run_cron_import() {
        $this->setup_instance();
        $this->load_import_into_as();
    }

    /**
     * Add admin notice for failed jobs
     *
     * @return void
     */
    public static function maybe_add_admin_error_notice() {
        if ( get_transient('fppi_failed_job') !== true ) {
            return;
        }
        $class = 'notice notice-error is-dismissible';
	    $message = __( sprintf('FlowPress Post Importer: An error has occurred during the last job, review the <a href="%s">logs</a> for more details.', admin_url( 'tools.php?page='.FP_Post_Importer_Admin::SETTINGS_PAGE_SLUG ) ), 'fppi' );
	    printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message ); 
    }

    /**
     * Action hook to delete all associated media with an imported post.
     *
     * @param int $id
     * @return void
     */
    static function delete_post_attachments( $id ) {
		$original_post_id = get_post_meta( $id, self::IMPORTED_META_POST, true );
		if (empty($original_post_id)) // we don't want to delete attachment for other posts
            return;
            
        // The attachment could be used by more than one post i.e. deleting one language, in which case, don't delete    
        if ( wp_attachment_is_image( $id ) ) {
			$thumbnail_query = new WP_Query( array(
				'meta_key'       => '_thumbnail_id',
				'meta_value'     => $id,
				'post_type'      => 'any',	
				'fields'         => 'ids',
				'no_found_rows'  => true,
				'posts_per_page' => -1,
			) );

			if ($thumbnail_query->posts > 1) {
                return;
            }
		}

		$attachments = get_attached_media( '', $id );
		foreach ($attachments as $attachment) {
			wp_delete_attachment( $attachment->ID, 'true' );
		}
	}

    /**
     * Helper function to get the log file path.
     *
     * @param string $prefix
     * @return void
     */
    static function get_log_file( $prefix = '' ) {
		$output_dir = trailingslashit( wp_upload_dir()['basedir'] ) . "fppi/log/";
		if ( !file_exists( $output_dir ) ) {
			wp_mkdir_p( $output_dir );
        }
        $prefix = !empty($prefix) ? "{$prefix}-" : "";
		$output_file = trailingslashit($output_dir) . "{$prefix}log.html";
		return $output_file;
	}

    /**
     * Helper function to get the log file URL.
     *
     * @param string $prefix
     * @return void
     */
	static function get_log_file_url( $prefix = '' ) {
        $output_dir = trailingslashit( wp_upload_dir()['baseurl'] ) . "fppi/log/";
        $prefix = !empty($prefix) ? "{$prefix}-" : "";
		$output_url = trailingslashit($output_dir) . "{$prefix}log.html";
		return $output_url;
    }

    /**
     * Return vars to be used client-side.
     *
     * @return array
     */
    static function get_localized_vars() {
        return array(
            'is_running' => !empty( self::get_pending_importer_jobs() ),
            'endpoints' => array(
                'test' => get_rest_url(null, FP_POST_IMPORTER_API_NAMESPACE . FP_POST_IMPORTER_API_CLIENT_TEST ),
                'clear' => get_rest_url(null, FP_POST_IMPORTER_API_NAMESPACE . FP_POST_IMPORTER_API_CLIENT_CLEAR_PROCESS_LOCK ),
                'cancel' => get_rest_url(null, FP_POST_IMPORTER_API_NAMESPACE . FP_POST_IMPORTER_API_CLIENT_CANCEL_PROCESS ),
                'import' => get_rest_url(null, FP_POST_IMPORTER_API_NAMESPACE . FP_POST_IMPORTER_API_CLIENT_IMPORT ),
                'log' => get_rest_url(null, FP_POST_IMPORTER_API_NAMESPACE . FP_POST_IMPORTER_API_CLIENT_LOG ),
            ),
        );
    }

    /**
     * Register API endpoints for the host settings.
     *
     * @return void
     */
    public function register_routes() {
		$endpoints = array(
			FP_POST_IMPORTER_API_CLIENT_TEST => array( 
				array(
					'callback' => array( $this, 'test_connection' ),
					'methods'  => WP_REST_Server::CREATABLE,
					'args'     => array(),
					'permission_callback' => array(__CLASS__, 'is_user_allowed'),
				),
            ),
            
            FP_POST_IMPORTER_API_CLIENT_CLEAR_PROCESS_LOCK => array( 
				array(
					'callback' => array( $this, 'clear_process_lock' ),
					'methods'  => WP_REST_Server::READABLE,
					'args'     => array(),
					'permission_callback' => array(__CLASS__, 'is_user_allowed'),
				),
			),

			FP_POST_IMPORTER_API_CLIENT_IMPORT => array( 
				array(
					'callback' => array( $this, 'run_import' ),
                    'methods'  => WP_REST_Server::CREATABLE,
					'args'     => array(),
					'permission_callback' => array(__CLASS__, 'is_user_allowed'),
				),
            ),

            FP_POST_IMPORTER_API_CLIENT_CANCEL_PROCESS => array( 
				array(
					'callback' => array( $this, 'cancel_process' ),
					'methods'  => WP_REST_Server::READABLE,
					'args'     => array(),
					'permission_callback' => array(__CLASS__, 'is_user_allowed'),
				),
			),
            
            FP_POST_IMPORTER_API_CLIENT_LOG => array( 
				array(
					'callback' => array( __CLASS__, 'fetch_log_ajax' ),
                    'methods'  => WP_REST_Server::READABLE,
					'args'     => array(),
					'permission_callback' => array(__CLASS__, 'is_user_allowed'),
				),
			),
		);	

		foreach ($endpoints as $path => $options) {
			register_rest_route( FP_POST_IMPORTER_API_NAMESPACE, $path, $options);
		}
    }

    /**
     * Register WP Rest callback to test the host/client connection.
     *
     * @param WP_Request $request
     * @return void
     */
    public function test_connection( $request ) {
        $params = $request->get_params();
        if ( empty( $params['host'] ) ) {
            wp_send_json_error('Missing Host URL');
        }
        // Prepare our host URL.
        $host_settings_url = sprintf('%swp-json/%s%s', trailingslashit( $params['host'] ), FP_POST_IMPORTER_API_NAMESPACE, FP_POST_IMPORTER_API_HOST_SETTINGS);

        $args = array(
            'sslverify'   => false,
            'timeout'     => 10,
        );
        $response = wp_remote_get( $host_settings_url, $args );
        if ( is_wp_error($response) ) {
            wp_send_json_error( $response->get_error_message(), $response->get_error_code() );
        }
        $body = $response['body']; 
        $host_settings = json_decode( $body, true );
        
        if ( empty($host_settings['success']) ) {
            wp_send_json_error( "Failed connection.", 500 );
        }

        // If we made it here, we got a successful response.
        FP_Post_Importer_Admin::set_client_host_settings( $host_settings['data'] );
        wp_send_json_success($host_settings['data']);
    }

    /**
     * Register WP Rest callback to test the host/client connection.
     *
     * @param WP_Request $request
     * @return void
     */
    public function clear_process_lock( $request = '' ) {
        $unlocked = $this->clear_process_lock_internal();
        wp_send_json_success($unlocked);
    }

    /**
     * Internal call to clear process lock.
     *
     * @return void
     */
    public function clear_process_lock_internal() {
        // Unlock.
        $unlocked = FP_Post_Importer_Admin::set_client_import_lock(false);
        return $unlocked;
    }

    /**
     * Get the pending action scheduler jobs for the importer.
     *
     * @return array
     */
    public static function get_pending_importer_jobs() {
        $args = [
            'group' => FP_Post_Importer_Admin::SCHEDULED_HOOK,
            'per_page' => -1,
            'status' => 'pending',
        ];
        $jobs = as_get_scheduled_actions( $args, 'OBJECT' );
        return $jobs;
    }

    /**
     * Cancel all the pending action scheduler jobs.
     *
     * @return void
     */
    public function cancel_all_pending_jobs() {
        $jobs = self::get_pending_importer_jobs();
        if (empty($jobs)) {
            return 'No jobs found';
        }
        foreach($jobs as $job) {
            as_unschedule_all_actions($job->get_hook(), $job->get_args(), $job->get_group());
        }
        return count($jobs);
    }

    /**
     * Register WP Rest callback to cancel the pending action scheduler jobs.
     *
     * @param WP_Request $request
     * @return void
     */
    public function cancel_process( $request ) {
        $this->clear_process_lock_internal();
        try {
            $result = $this->cancel_all_pending_jobs();
        } catch(Exception $e) {
            wp_send_json_error($e->getMessage(), $e->getCode());
        }
        
        if (is_string($result)) {
            wp_send_json_error($result, 400);
        } else {
            wp_send_json_success($result);
        }
    }

    /**
     * Callback to verify user permissions for REST call.
     *
     * @param WP_Reqest $request
     * @return boolean
     */ 
    static function is_user_allowed($request) {		
		return current_user_can( FP_Post_Importer_Admin::PERMISSION_LEVEL );
    }
    
    public function clear_all_as_jobs() {
        $result = $this->cancel_all_pending_jobs();
        $this->clear_process_lock_internal();
        if (is_string($result)) {
            do_action('fppi_importer_debug', "No jobs to clear");
        } else {
            do_action('fppi_importer_debug', "Cleared jobs");
        }        
    }

    public function flush_as_logs() {
        global $wpdb;
        $statuses = ['complete', 'canceled', 'failed'];
        $status_list = implode("', '", $statuses);
        $slug = FP_Post_Importer_Admin::SCHEDULED_HOOK;
        $results = $wpdb->get_row( "SELECT group_id FROM {$wpdb->prefix}actionscheduler_groups WHERE slug = '{$slug}'" );
        if (empty($results)) {
            do_action('fppi_importer_debug', "No jobs found");
            return;
        }

        $group_id = $results->group_id;
        $results = $wpdb->get_results( "SELECT action_id FROM {$wpdb->prefix}actionscheduler_actions WHERE `group_id` = {$group_id} AND `status` IN ('{$status_list}')" );
        if (empty($results)){
            return;
        }
        $action_ids = wp_list_pluck($results, 'action_id');
        $action_id_list = implode(',', $action_ids);
        
        $results = $wpdb->get_results( "DELETE FROM {$wpdb->prefix}actionscheduler_logs WHERE `action_id` IN ({$action_id_list})" );
        $results = $wpdb->get_results( "DELETE FROM {$wpdb->prefix}actionscheduler_actions WHERE `group_id` = {$group_id} AND `status` IN ('{$status_list}')" );
    }

    /**
     * Action to delete posts locally based on response of deleted post ids from host.
     *
     * @return void
     */
    public function check_post_count($import_post_type = false, $import_lang = false, $job_type = false) {
        set_time_limit(0);
        $this->setup_instance();
        $this->import_post_type = !empty($import_post_type) ? $import_post_type : $this->import_post_type;
        $this->import_lang = !empty($import_lang) ? $import_lang : $this->import_lang;
        $this->job_type = !empty($job_type) ? $job_type : $this->job_type;
        $this->action_counts = array();

        if (empty($this->import_post_type)) {
            // throw new Exception("Invalid post type {$this->import_post_type}", 505);
             do_action('fppi_importer_exception', "Invalid post type for checking post count");
             return false;
        }       
 
        $language_desc = "";
        // Host URL changes if we are importing other languages i.e. /en vs /fr
        if ( !empty($this->import_lang) ) {
            if (!empty($this->host_settings['languages'][ $this->import_lang ])) {
                $this->host_url = $this->host_settings['languages'][ $this->import_lang ]['url'];
            }
            else {
                $msg = "Invalid language saved in host settings.";
                do_action('fppi_importer_exception', $msg);
                throw new Exception($msg);
            }
            $language_desc = sprintf(" for language %s", strtoupper($this->import_lang) );
        }

        

        if ( empty($this->host_url) ) {
            //throw new Exception('Invalid host_url, cannot continue', 405);
            do_action('fppi_importer_exception', "Invalid host_url, breaking...");
            return false;
       }

        do_action('fppi_importer_debug', "Checking post count{$language_desc} via {$this->job_type} job for {$this->import_post_type}");

        // Used for our API request.
        $query_args = array();
        // Leave the default for fetching post_ids
        //$query_args['per_page']	= self::IMPORT_PAGE_MAX;
        $no_pages = 2; // default, this will be updated after the first fetch.
        
        $rest_url = trailingslashit($this->host_url) . "wp-json/" . FP_POST_IMPORTER_API_NAMESPACE . FP_POST_IMPORTER_API_HOST_ALL_POSTS_IDS . "/{$this->import_post_type}";
        $deleted_post_id_data = array();

        do_action('fppi_importer_debug', "Running validation check - fetching IDs for {$this->import_post_type}");

        for ( $page = 1; $page <= $no_pages; $page++ ) :
            
            // Whether we are fetching a list of post IDS or not.
            $query_args['page'] = $page;
            if (!empty($this->api_token)) {
                $query_args[ FP_POST_API_TOKEN_KEY ] = $this->api_token;
            }

            // If we filter on the host side, meaning only request posts with this, 
            // We need to ensure the filtering for the exclude is still done in import_posts
            if (!empty( $this->import_tags[ $this->import_post_type ]['include'] ) ) {
                $include_string = $this->import_tags[ $this->import_post_type ]['include'];
                $include_terms = explode(",", $include_string);
                foreach($include_terms as $include_term) :
                    $include_parts = explode("=", $include_term);
                    $include_taxonomy = isset( $include_parts[0] ) ? trim($include_parts[0]) : false;
                    $include_slug = isset( $include_parts[1] ) ? trim($include_parts[1]) : false;
                    if (empty($include_taxonomy) || empty($include_slug)) {
                        do_action('fppi_importer_exception', "Could not parse host-side import tag {$include_term}");
                        continue;
                    } else {
                        //do_action('fppi_importer_debug', "Apply the include param to the query {$include_term}");
                        $query_args['taxonomy'][$include_taxonomy] = $include_slug;
                    } 
                endforeach;
            }

            $fetch_url = add_query_arg( $query_args, $rest_url );
            // For debugging safely.
            $api_token_replace_key = defined('WP_ENV') && (WP_ENV == 'local') ? $this->api_token : 'TOKEN';
            $debug_url = !empty($this->api_token) ? preg_replace('#'.$this->api_token.'#', $api_token_replace_key, $fetch_url) : $fetch_url;
            do_action('fppi_importer_debug', "Fetching from: {$debug_url} (page: {$page})");
            
			$args = array(
			    'timeout'     => 60,
			    'redirection' => 5,
			    'user-agent'  => 'WordPress/version1.1',
			    'sslverify'   => false,
			); 

            $response = wp_remote_get( $fetch_url, $args );
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
			    $headers = $response['headers']; // array of http header lines
                $body    = $response['body']; // use the content
                $code    = wp_remote_retrieve_response_code( $response );
                $existing_post_id_data = json_decode($body, true);

                $no_pages = !empty( $headers['x-wp-totalpages'] ) ? $headers['x-wp-totalpages'] : 1;
                do_action('fppi_importer_debug', "Fetched page: {$page}/{$no_pages} - " . self::get_datetime_formatted());

                if ( $code != 200 || is_null($existing_post_id_data) || (!isset($existing_post_id_data['data']['ids']) ) ) {
                    do_action('fppi_importer_exception', "Invalid response from API: " . $body );
                    continue;
                }

                $this->check_for_existing_posts( $existing_post_id_data['data']['ids'] );
                
			} else {
				$error_msg = $response->get_error_message();
				do_action('fppi_importer_exception', "Fetch URL Error: {$error_msg}");
			}
		endfor;

        do_action('fppi_check_post_summary');

    }

    /**
     * Action to delete posts locally based on response of deleted post ids from host.
     *
     * @return void
     */
    public function delete_posts($import_post_type = false, $job_type = false) {
        set_time_limit(0);
        $this->setup_instance();
        $this->import_post_type = !empty($import_post_type) ? $import_post_type : $this->import_post_type;
        $this->job_type = !empty($job_type) ? $job_type : $this->job_type;
        $this->action_counts = array();

        if (empty($this->import_post_type)) {
            // throw new Exception("Invalid post type {$this->import_post_type}", 505);
             do_action('fppi_importer_exception', "Invalid post type");
             return false;
        }       
 
        if ( empty($this->host_url) ) {
             //throw new Exception('Invalid host_url, cannot continue', 405);
             do_action('fppi_importer_exception', "Invalid host_url, breaking...");
             return false;
        }

        do_action('fppi_importer_debug', "Deleting via {$this->job_type} job for {$this->import_post_type}");

        // Used for our API request.
        $query_args = array();
        // Leave the default for fetching post_ids
        //$query_args['per_page']	= self::IMPORT_PAGE_MAX;
        $no_pages = 2; // default, this will be updated after the first fetch.
        
        $rest_url = trailingslashit($this->host_url) . "wp-json/" . FP_POST_IMPORTER_API_NAMESPACE . FP_POST_IMPORTER_API_HOST_DELETED_POSTS_IDS . "/{$this->import_post_type}";
        $deleted_post_id_data = array();

        do_action('fppi_importer_debug', "Running delete job - fetching IDs for {$this->import_post_type}");

        for ( $page = 1; $page <= $no_pages; $page++ ) :
            
            // Whether we are fetching a list of post IDS or not.
            $query_args['page'] = $page;
            if (!empty($this->api_token)) {
                $query_args[ FP_POST_API_TOKEN_KEY ] = $this->api_token;
            }
            $fetch_url = add_query_arg( $query_args, $rest_url );
            // For debugging safely.
            $api_token_replace_key = defined('WP_ENV') && (WP_ENV == 'local') ? $this->api_token : 'TOKEN';
            $debug_url = !empty($this->api_token) ? preg_replace('#'.$this->api_token.'#', $api_token_replace_key, $fetch_url) : $fetch_url;
            do_action('fppi_importer_debug', "Fetching from: {$debug_url} - page: {$page}");
            
			$args = array(
			    'timeout'     => 60,
			    'redirection' => 5,
			    'user-agent'  => 'WordPress/version1.1',
			    'sslverify'   => false,
			); 

            $response = wp_remote_get( $fetch_url, $args );
			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
			    $headers = $response['headers']; // array of http header lines
                $body    = $response['body']; // use the content
                $code    = wp_remote_retrieve_response_code( $response );
                $deleted_post_id_data = json_decode($body, true);

                $no_pages = !empty( $headers['x-wp-totalpages'] ) ? $headers['x-wp-totalpages'] : 1;
                do_action('fppi_importer_debug', "Fetched page: {$page}/{$no_pages} - " . self::get_datetime_formatted());

                if ( $code != 200 || is_null($deleted_post_id_data) || (!isset($deleted_post_id_data['data']['ids']) ) ) {
                    do_action('fppi_importer_exception', "Invalid response from API: " . $body );
                    continue;
                }

				do_action('fppi_importer_debug', "Checking for deleted posts...");
                $this->check_for_deleted_posts( $deleted_post_id_data['data']['ids'] );
                
			} else {
				$error_msg = $response->get_error_message();
				do_action('fppi_importer_exception', "Fetch URL Error: {$error_msg}");
			}
		endfor;

        do_action('fppi_delete_summary');

    }

    /**
     * Check for existing associated posts.
     *
     * @param array $existing_post_ids
     * @return array
     */
    public function check_for_existing_posts($existing_post_ids) {
		if ( function_exists( 'wp_raise_memory_limit' ) ) {
			return wp_raise_memory_limit( 'admin' );
		}
		if (empty($existing_post_ids)) {
            return false;
        }
        $local_post_mapping = [];
        $total_posts_source = count($existing_post_ids);
        $args = [
            'post_type' => $this->import_post_type,
            'post_status' => ['draft', 'pending', 'private', 'publish', 'future', 'auto-draft', 'inherit'],
            'posts_per_page' => -1,
            'fields' => 'ids',
        ];
        if (!empty($this->import_lang)) {
            $args['lang'] = $this->import_lang;
        }
        $all_posts = new WP_Query($args);

        $total_posts_local = $all_posts->found_posts;
        $local_non_imported_posts = [];

        if ($total_posts_local > 0) {
			// Chunk the loop to reduce memory consumption, but preserve keys
            foreach( array_chunk($all_posts->posts, 5, true) as $post_ids ) {
				foreach( $post_ids as $local_post_id ) {
					$original_post_id = (int) get_post_meta($local_post_id, self::IMPORTED_META_POST, true);
					if (!empty($original_post_id)) {
						$local_post_mapping[ $original_post_id ] = $local_post_id;
					} else {
						$local_non_imported_posts[] = $local_post_id; 
					}
				}
            }
            // Check for posts that don't exist locally.
			$not_exists = FP_Post_Importer::flip_isset_diff( $existing_post_ids, array_keys($local_post_mapping) );
            // Check for posts that exist locally but not on the host.
			$extra_post_ids = FP_Post_Importer::flip_isset_diff( array_keys($local_post_mapping), $existing_post_ids );

        }
        // Output our findings.
        if (!empty($not_exists)) {
            $not_existing_post_ids = json_encode($not_exists);
            $total_not_existing = count($not_exists);
            do_action('fppi_importer_exception', "Source Post Count Check found difference. {$total_posts_source} source vs. {$total_posts_local} locally. There are {$total_not_existing} posts that don't exist locally. The following posts IDs exist on the source site but not the client: {$not_existing_post_ids}"  );
        } else {
            do_action('fppi_importer_debug', "Post Count Check. All source post IDs exist locally and in sync."  );
        }
		
        // Output our findings.
        if (!empty($extra_post_ids)) {
            $local_extra_post_id = [];
            foreach($extra_post_ids as $extra_post_id) {
                $extra_pid = $local_post_mapping[$extra_post_id];
                $extra_post = get_post($extra_pid);
                if (empty($extra_post->ID)) {
                    do_action('fppi_importer_debug', "Cannot find extra post ID {$extra_pid} locally, skipping deletion..."  );
                }
                $local_extra_post_id[] = $local_post_mapping[$extra_post_id];
                do_action('fppi_importer_deleted', array( 'post_id' => $extra_post->ID, 'post_data' => (array) $extra_post ) );
                wp_trash_post($extra_post->ID);
            }
            $total_extra_post_ids = count($local_extra_post_id);
            $extra_post_id_list = json_encode($local_extra_post_id);
            do_action('fppi_importer_exception', "Local Post Count Check found difference. {$total_posts_source} source vs. {$total_posts_local} locally. There were {$total_extra_post_ids} posts locally that don't exist on the source. The following posts IDs exist on the local site but not the source and are now deleted: {$extra_post_id_list}"  );
        } else {
            do_action('fppi_importer_debug', "Post Count Check. There are no extra posts locally that don't exist on the source."  );
        }

        // Output our findings.
        if (!empty($local_non_imported_posts)) {
            $total_extra_post_ids = count($local_non_imported_posts);
            $extra_post_id_list = json_encode($local_non_imported_posts);
            do_action('fppi_importer_exception', "Local Post Count Check found difference. {$total_extra_post_ids} extra posts exist locally that were not imported: {$extra_post_id_list}"  );
        } else {
            do_action('fppi_importer_debug', "Post Count Check. There are no extra posts locally that don't exist on the source that were not imported."  );
        }

        return;   
    }

    /**
     * Check for existing and delete associated posts.
     *
     * @param array $deleted_post_ids
     * @return void
     */
    public function check_for_deleted_posts($deleted_post_ids) {
        foreach($deleted_post_ids as $deleted_post_id) {
            $existing_post = self::get_post_id_by_importedID( (int) $deleted_post_id, $this->import_post_type, 'all' );
            if (!empty($existing_post->ID)) {
                do_action('fppi_importer_deleted', array( 'post_id' => $existing_post->ID, 'post_data' => (array) $existing_post ) );
                wp_trash_post($existing_post->ID);
            }
        }   
    }

    /**
     * Action scheduler job hook to output the summary and action counts.
     *
     * @return void
     */
    public function output_summary_log() {
        $this->setup_instance();
        do_action('fppi_importer_summary');
        $this->clear_action_counts();     
    }

    /**
     * Action scheduler job hook to output the header of the log.
     *
     * @return void
     */
    public function start_import_log( $job_type = false ) {
        $this->setup_instance();
        $this->job_type = !empty($job_type) ? $job_type : $this->job_type;
        // Otherwise, lock.
        FP_Post_Importer_Admin::set_client_import_lock(true);
        $this->flush_log();
        $this->clear_action_counts();
        $this->maybe_send_notification_email();
        do_action('fppi_importer_header', sprintf("Import starting on %s from cursor date %s", self::get_datetime_formatted(), $this->last_modified_cursor ) );
    }

    public function maybe_send_notification_email() {
        $notification_email = FP_Post_Importer_Admin::get_notification_email();
        if (!empty($notification_email)) {
            $site_url = preg_replace('#http(s)*://#', '', get_site_url() );
            $subject = "Post Importer - Import starting on {$site_url}";
            $message = sprintf("Import by %s job starting %s from cursor date %s", $this->job_type, self::get_datetime_formatted(), $this->last_modified_cursor );
            wp_mail($notification_email, $subject, $message);
        }
    }

    /**
     * Action scheduler job hook to output the footer of the log.
     *
     * @return void
     */
    public function end_import_log() {
        $this->setup_instance();
        $start_time = FP_Post_Importer_Admin::get_client_import_lock();
        $end_time = microtime(true); 
        $execution_time = ($end_time - $start_time);
        $time = gmdate("H:i:s", $execution_time);
        do_action('fppi_importer_footer', sprintf("&#x2714; Finished - Total Time - %s seconds",  $time) );
        $datetime = self::get_datetime_formatted();
        FP_Post_Importer_Admin::set_client_import_cursor( $datetime );

        // Unlock.
        FP_Post_Importer_Admin::set_client_import_lock(false);
        $this->cancel_all_pending_jobs();
        $this->clear_process_lock_internal();

        // Write log to file
        $log_data = self::fetch_log();
        file_put_contents( self::get_log_file( "{$this->job_type}-".self::get_datetime_formatted( 'YmdTHis' ) ), $log_data );
    }

    /**
     * Get the max number of pages for this modififed cursor, language and post type.
     *
     * @return int
     */
    public function get_max_pages() {
        $total_pages = false;
        $language_desc = !empty($this->import_lang) ? sprintf(" for language %s", strtoupper($this->import_lang) ) : "";
        // Host URL changes if we are importing other languages i.e. /en vs /fr
        if ( !empty($this->import_lang) && !empty($this->host_settings['languages'][ $this->import_lang ]) ) {
            $this->host_url = $this->host_settings['languages'][ $this->import_lang ]['url'];
        } else if (empty($this->import_lang)) {
            // do nothing.
        } 
        else {
            do_action('fppi_importer_exception', "Invalid language saved in host settings.");
            return false;
        }
       
        // Now let's create the API url from our post type.
        $rest_url = FP_Post_Importer::get_post_type_rest_url( $this->import_post_type, $this->host_url );
        
        // Used for our API request.
        $query_args = array();
        $query_args['per_page']	= apply_filters('fppi_client_max_per_page', self::IMPORT_PAGE_MAX );
        $query_args['page'] = 1;
        // Last Modified
        $query_args['filter']['date_query'][0]['column'] = 'post_modified';
        $query_args['filter']['date_query'][0]['after'] = $this->last_modified_cursor;
        // If the token is set try getting private/draft posts as well.
        if (!empty($this->api_token)) {
            $query_args['status'] = array('publish', 'draft', 'private');
            $query_args[ FP_POST_API_TOKEN_KEY ] = $this->api_token;
        }

        // If we filter on the host side, meaning only request posts with this, 
        // We need to ensure the fitlering for the exclude is still done in the import_posts.
        if (!empty( $this->import_tags[ $this->import_post_type ]['include'] ) ) {
            $include_string = $this->import_tags[ $this->import_post_type ]['include'];
            $include_terms = explode(",", $include_string);
            foreach($include_terms as $include_term) :
                $include_parts = explode("=", $include_term);
                $include_taxonomy = isset( $include_parts[0] ) ? trim($include_parts[0]) : false;
                $include_slug = isset( $include_parts[1] ) ? trim($include_parts[1]) : false;
                if (empty($include_taxonomy) || empty($include_slug)) {
                    do_action('fppi_importer_exception', "Could not parse host-side import tag {$include_term}");
                    continue;
                } else {
                    //do_action('fppi_importer_debug', "Apply the include param to the query {$include_term}");
                    $query_args['filter'][$include_taxonomy] = $include_slug;
                } 
            endforeach;
        }
        
        $fetch_url = add_query_arg( $query_args, $rest_url );
        $ssl_verify = ! ( defined('WP_ENV') && (WP_ENV == 'local') );
        // For debugging safely.
        $api_token_replace_key = defined('WP_ENV') && (WP_ENV == 'local') ? $this->api_token : 'TOKEN';
        $debug_url = !empty($this->api_token) ? preg_replace('#'.$this->api_token.'#', $api_token_replace_key, $fetch_url) : $fetch_url;

        do_action('fppi_importer_debug', "Fetching max pages from via {$this->job_type} job for {$this->import_post_type}{$language_desc} at URL {$debug_url}");

        $args = array(
            'timeout'     => 60,
            'redirection' => 5,
            'user-agent'  => 'WordPress/version1.1',
            'sslverify'   => $ssl_verify,
        ); 

        $response = wp_remote_get( $fetch_url, $args );

        if ( is_array( $response ) && ! is_wp_error( $response ) ) {
            $headers = $response['headers']; // array of http header lines
            $body    = $response['body']; // use the content
            $code    = wp_remote_retrieve_response_code( $response );
            $posts = json_decode($body, true);

            if ( intval($code) != 200 || is_null($posts) ) {
                do_action('fppi_importer_exception', "Invalid response from API to fetch max pages: " . $body );
            }

            $total_pages = !empty( $headers['x-wp-totalpages'] ) ? (int) $headers['x-wp-totalpages'] : 1;
            //do_action('fppi_importer_debug', "Fetched total pages: {$total_pages}");
            
        } else {
            $error_msg = $response->get_error_message();
            do_action('fppi_importer_exception', "Fetch URL Error: {$error_msg}");
        }

        return $total_pages;
    }

    /**
     * Fetch a single page of the import.
     *
     * @param integer $page_no
     * @return void
     */
    public function fetch_page( $page_no = 1, $import_post_type = false, $import_lang = false, $last_modified_cursor = false, $download_attachments = null, $job_type = false ) { 
        $this->setup_instance();
        $this->import_post_type = !empty($import_post_type) ? $import_post_type : $this->import_post_type;
        $this->import_lang = !empty($import_lang) ? $import_lang : $this->import_lang;
        $this->start_page = !empty($page_no) ? $page_no : $this->start_page;
        $this->last_modified_cursor = !empty($last_modified_cursor) ? $last_modified_cursor : $this->last_modified_cursor;
        $this->download_attachments = is_bool( $download_attachments ) ? $download_attachments : $this->download_attachments;
        $this->job_type = !empty($job_type) ? $job_type : $this->job_type;

        if (empty($this->import_post_type)) {
            $msg = "Invalid post type";
            do_action('fppi_importer_exception', $msg);
            throw new Exception($msg);
        }

        $language_desc = "";
        // Host URL changes if we are importing other languages i.e. /en vs /fr
        if ( !empty($this->import_lang) ) {
            if (!empty($this->host_settings['languages'][ $this->import_lang ])) {
                $this->host_url = $this->host_settings['languages'][ $this->import_lang ]['url'];
            }
            else {
                $msg = "Invalid language saved in host settings.";
                do_action('fppi_importer_exception', $msg);
                throw new Exception($msg);
            }
            $language_desc = sprintf(" for language %s", strtoupper($this->import_lang) );
        } 
       
        // Now let's create the API url from our post type.
        $rest_url = FP_Post_Importer::get_post_type_rest_url( $this->import_post_type, $this->host_url );
        
        // Used for our API request.
        $query_args = array();
        $query_args['per_page']	= apply_filters('fppi_client_max_per_page', self::IMPORT_PAGE_MAX );
        $query_args['page'] = $page_no;
        // Last Modified
        $query_args['filter']['date_query'][0]['column'] = 'post_modified';
        $query_args['filter']['date_query'][0]['after'] = $this->last_modified_cursor;
        $query_args['orderby'] = 'date';
        $query_args['order'] = 'desc';
        // If the token is set try getting private/draft posts as well.
        if (!empty($this->api_token)) {
            $query_args['status'] = array('publish', 'draft', 'private');
            $query_args[ FP_POST_API_TOKEN_KEY ] = $this->api_token;
        }

        // Host default.
        if ($this->include_method != 'client') {
            if (!empty( $this->import_tags[ $this->import_post_type ]['include'] ) ) {
                $include_string = $this->import_tags[ $this->import_post_type ]['include'];
                $include_terms = explode(",", $include_string);
                foreach($include_terms as $include_term) :
                    $include_parts = explode("=", $include_term);
                    $include_taxonomy = isset( $include_parts[0] ) ? trim($include_parts[0]) : false;
                    $include_slug = isset( $include_parts[1] ) ? trim($include_parts[1]) : false;
                    if (empty($include_taxonomy) || empty($include_slug)) {
                        do_action('fppi_importer_exception', "Could not parse host-side import tag {$include_term}");
                        continue;
                    } else {
                        do_action('fppi_importer_debug', "Apply the include param to the query {$include_term}");
                        $query_args['filter'][$include_taxonomy] = $include_slug;
                    } 
                endforeach;
            }
        }
        
        $fetch_url = add_query_arg( $query_args, $rest_url );
        $ssl_verify = ! ( defined('WP_ENV') && (WP_ENV == 'local') );
        // For debugging safely.
        $api_token_replace_key = defined('WP_ENV') && (WP_ENV == 'local') ? $this->api_token : 'TOKEN';
        $debug_url = !empty($this->api_token) ? preg_replace('#'.$this->api_token.'#', $api_token_replace_key, $fetch_url) : $fetch_url;

        do_action('fppi_importer_debug', "Fetching page {$page_no} via {$this->job_type} job for {$this->import_post_type}{$language_desc} at URL {$debug_url}");

        $args = array(
            'timeout'     => 60,
            'redirection' => 5,
            'user-agent'  => 'WordPress/version1.1',
            'sslverify'   => $ssl_verify,
        ); 

        $response = wp_remote_get( $fetch_url, $args );

        if ( is_array( $response ) && ! is_wp_error( $response ) ) {
            $headers = $response['headers']; // array of http header lines
            $body    = $response['body']; // use the content
            $code    = wp_remote_retrieve_response_code( $response );
            $posts = json_decode($body, true);

            if ( intval($code) != 200 || is_null($posts) ) {
                do_action('fppi_importer_exception', "Invalid response from API to fetch page {$page_no}: " . $body );
            }

            $total_pages = !empty( $headers['x-wp-totalpages'] ) ? (int) $headers['x-wp-totalpages'] : 1;
            do_action('fppi_importer_debug', "Fetched page {$page_no} of {$total_pages}, importing...");
            $this->import_posts( $posts );
            
        } else {
            $error_msg = $response->get_error_message();
            do_action('fppi_importer_exception', "Fetch URL Error: {$error_msg}");
            return false;
        }
        // Update our action counts
        $this->set_action_counts();
        return true;
    }

    /**
     * Triggered from a manual import to load and then launch the action scheduler runner.
     *
     * @return void
     */
    public function run_background_import() {
        $this->flush_log();
        $this->load_import_into_as();
        ActionScheduler::runner()->run();
        wp_send_json_success();
    }

    /**
     * Load all the pages to fetch into the action scheduler job queue.
     *
     * @return void
     */
    public function load_import_into_as() {
        // Check if importer is running.
        $lock = FP_Post_Importer_Admin::get_client_import_lock();
        if ( !empty($lock) ) {
            // If it's a cron job, and it's still locked, obviously something has failed, add an admin notice.
            if (defined( 'DOING_CRON' ) && DOING_CRON) {
                set_transient('fppi_failed_job', true);
            }
            //throw new Exception('Importer currently running', 405);
            do_action('fppi_importer_exception', "Importer lock, process currently running");
            throw new Exception("Importer cron lock, process currently running");
        }

        $job_count = 0;
        // Otherwise, lock.
        // @todo doesn't mean this won't be at the end of a batch, triggering a wrong start time? 
        as_enqueue_async_action( 'fppi_start_import', [ $this->job_type ], FP_Post_Importer_Admin::SCHEDULED_HOOK );
        $job_count++;
        // Run for each post type.
        foreach( $this->importable_post_types as $cpt ) :
            $this->import_post_type = $cpt;

            // Run the delete, prior.
            // $this->delete_posts();
            $args = [ $this->import_post_type, $this->job_type ];
            as_enqueue_async_action( 'fppi_delete_posts', $args, FP_Post_Importer_Admin::SCHEDULED_HOOK );
            $job_count++;

            $this->importable_langs = ! empty($this->importable_langs) ? $this->importable_langs : [ '' ]; // used to fetch default host URL lang

            // Check for lanuages. 
            foreach( $this->importable_langs as $lang ) :
                $this->import_lang = $lang;
                $no_pages = $this->get_max_pages(); // default, this will be updated after the first fetch.
                
                for ( $page = $this->start_page; $page <= $no_pages; $page++ ) :
                    // Run the importer.
                    // Add each page to the import queue.
                    $args = [ $page, $this->import_post_type, $this->import_lang, $this->last_modified_cursor, $this->download_attachments, $this->job_type ];
                    as_enqueue_async_action( 'fppi_import_page', $args, FP_Post_Importer_Admin::SCHEDULED_HOOK );
                    $job_count++;

                    //$this->fetch_page( $page, $this->import_post_type, $this->import_lang ); 

                endfor;
                as_enqueue_async_action( 'fppi_output_summary', [], FP_Post_Importer_Admin::SCHEDULED_HOOK );

                $args = [ $this->import_post_type, $this->import_lang, $this->job_type ];
                as_enqueue_async_action( 'fppi_check_post_count', $args, FP_Post_Importer_Admin::SCHEDULED_HOOK );

                $job_count++;
            endforeach; // language

        endforeach; // post type
        as_enqueue_async_action( 'fppi_end_import', [], FP_Post_Importer_Admin::SCHEDULED_HOOK );
        $job_count++;

        update_option('fppi_total_job_count', $job_count, false);
    }

    /**
     * REST API callback for import request.
     * Only used to fetch posts by manual import.
     * 
     * @param WP_Request $request
     * @return void
     */
	public function run_import( $request = array() ) {
        
		$this->setup_instance();

        $params = is_array($request) ? $request : $request->get_params();
		
		// Set the class variables to be used during the import
		
        $this->start_page = !empty( $params['start_page'] ) ? (int) $params['start_page'] : 1;
        $this->last_modified_cursor = !empty( $params['fppi_client_import_cursor'] ) ? $params['fppi_client_import_cursor'] : $this->last_modified_cursor;
        $this->download_attachments = !empty( $params['download_attachments'] ) && ( $params['download_attachments'] == 'true' );

		$this->flush_log();

		// Check for selected importable post types
		if ( empty($params['fppi_client_manual_cpt']) ) {
			do_action('fppi_importer_exception', "You must select a post type to import.");
			wp_send_json_error('You must select a post type to import.', 400);
			exit();
		} else {
			$this->importable_post_types = $params['fppi_client_manual_cpt'];
			$importable_cpt = $this->importable_post_types;
		}

		// If manual-sync-type is set to *by date*, run a background import by date
        if ( !empty($params['manual_sync_type']) && $params['manual_sync_type'] == 'date' ) {
            $this->run_background_import();
            return;
		// If manual-sync-type is set to *post_ids*, run a manual import by post ids
		} elseif ( !empty($params['manual_sync_type']) && $params['manual_sync_type'] == 'post_id' ) {
			$this->import_post_ids = !empty( $params['post_ids'] ) ? explode(",", $params['post_ids']) : array();
			if ( empty( $this->import_post_ids ) ) {
				do_action('fppi_importer_exception', "You chose to import by Post IDs, but no IDs were set.");
				wp_send_json_error('You chose to import by Post IDs, but no IDs were set.', 400);
				exit();
			}
		}

		try {
            // Check if importer is running.
            $lock = FP_Post_Importer_Admin::get_client_import_lock();
            if ( !empty($lock) ) {
                //throw new Exception('Importer currently running', 405);
                do_action('fppi_importer_exception', "Importer lock, process currently running");
                wp_send_json_error('Importer lock, process currently running', 400);
				exit();
            }

            // Otherwise, lock.
            $lock = FP_Post_Importer_Admin::set_client_import_lock(true);
            $start_time = microtime(true);
            do_action('fppi_importer_header', sprintf("Import run %s from cursor date %s", self::get_datetime_formatted(), $this->last_modified_cursor ) );
            
			$importable_lang = $this->importable_langs;

            // Run for each post type.
            foreach( $importable_cpt as $cpt ) {
                $this->import_post_type = $cpt;
                // Check for lanuages, if no languages set, just run
                if ( empty( $importable_lang ) ) {
                    $this->_fetch();
                } else {
                    // Run for each language:
                    foreach( $importable_lang as $lang ) {
                        $host_settings = FP_Post_Importer_Admin::get_client_host_settings();
                        if ( !empty($host_settings['languages'][ $lang ]) ) {
                            $this->host_url = $host_settings['languages'][ $lang ]['url'];
                            $this->import_lang = $lang;
                            // If there are translated ID's
                            $this->import_post_ids = !empty( $this->translated_post_ids[ $this->import_lang ] ) ? $this->translated_post_ids[ $this->import_lang ] : $this->import_post_ids;
                            // Run the importer.
                            $this->_fetch();
                        } else {
                            do_action('fppi_importer_exception', "Invalid language saved in host settings.");
                            wp_send_json_error('Invalid language saved in host settings', 400);
                        }   
                    }
                }
                
            }
            $end_time = microtime(true); 
            $execution_time = ($end_time - $start_time); 
            do_action('fppi_importer_footer', sprintf("Finished - Total Time: %0.2f seconds.", $execution_time ));
            $datetime = self::get_datetime_formatted();
            FP_Post_Importer_Admin::set_client_import_cursor( $datetime );

            // Unlock.
            FP_Post_Importer_Admin::set_client_import_lock(false);

            // Write log to file
            $log_data = self::fetch_log();
            file_put_contents( self::get_log_file( 'manual-'.self::get_datetime_formatted( 'YmdTHis' ) ), $log_data );

            wp_send_json_success($datetime);
        }
		catch(Exception $e) {
            // If anything else went wrong, let's catch it here.
			error_log(sprintf('%s in %s: %s (code %d)', get_class($e), __FUNCTION__, $e->getMessage(), $e->getCode()));
			$error_message = __('Something unexpected happened when fetching the posts.', 'fppi');
            $error_message = $e->getMessage();
            $error_code = $e->getCode();
			call_user_func( array( $this, 'error_logger'), $error_message, $error_code);
		}

		exit();
    }

    /**
     * Legacy single synchronous fetch function, based on instance state, will import single post type, or by post_ids.
     * Mainly used now to fetch posts by IDs.
     *
     * @return void
     */
    public function _fetch() {
		set_time_limit(0);
        
        if (empty($this->import_post_type)) {
           // throw new Exception("Invalid post type {$this->import_post_type}", 505);
            do_action('fppi_importer_exception', "Invalid post type");
			return false;
        }

		if ( empty($this->host_url) ) {
            //throw new Exception('Invalid host_url, cannot continue', 405);
			do_action('fppi_importer_exception', "Invalid host_url, breaking...");
			return false;
		}
        
        // Used for our API request.
        $query_args = array();
        $query_args['per_page']	= self::IMPORT_PAGE_MAX;
        $no_pages = !empty($this->import_post_ids) ? count($this->import_post_ids) : 999; // default, this will be updated after the first fetch.
        
        $rest_url = FP_Post_Importer::get_post_type_rest_url( $this->import_post_type, $this->host_url );
        $posts = array();

        $language_desc = !empty($this->import_lang) ? sprintf(" for language %s", strtoupper($this->import_lang) ) : "";
        do_action('fppi_importer_debug', "Fetching via {$this->job_type} job for {$this->import_post_type}{$language_desc}");
        $error_count = 0;

        for ( $page = $this->start_page; $page <= $no_pages; $page++ ) :

            if ($error_count > 10) {
                // We don't want to continue if we got 10 invalid responses.
                break;
            }

            $token = FP_Post_Importer_Admin::get_host_token();
            $this->include_method = FP_Post_Importer_Admin::get_include_filter_method();
            $this->import_tags = FP_Post_Importer_Admin::get_importable_cpt_tags();
        
            // Whether we are fetching a list of post IDS or not.
            if (empty($this->import_post_ids)) {
                $api_url = $rest_url;
                $query_args['page'] = $page;
                // To filter by published date.
                //$query_args['after'] = $this->last_modified_cursor;
                // To filter by last modified date.
                $query_args['filter']['date_query'][0]['column'] = 'post_modified';
                $query_args['filter']['date_query'][0]['after'] = $this->last_modified_cursor;

                if ($this->include_method != 'client') {
                    
                    if (!empty( $this->import_tags[ $this->import_post_type ]['include'] ) ) {
                        $include_string = $this->import_tags[ $this->import_post_type ]['include'];
                        $include_terms = explode(",", $include_string);
                        foreach($include_terms as $include_term) :
                            $include_parts = explode("=", $include_term);
                            $include_taxonomy = isset( $include_parts[0] ) ? trim($include_parts[0]) : false;
                            $include_slug = isset( $include_parts[1] ) ? trim($include_parts[1]) : false;
                            if (empty($include_taxonomy) || empty($include_slug)) {
                                do_action('fppi_importer_exception', "Could not parse host-side import tag {$include_term}");
                                continue;
                            } else {
                                do_action('fppi_importer_debug', "Apply the include param to the query {$include_term}");
                                $query_args['filter'][$include_taxonomy] = $include_slug;
                            } 
                        endforeach;
                    }
                }

            } else {
                $current_post_id = $this->import_post_ids[ $page - 1 ];
                $api_url =  "{$rest_url}/{$current_post_id}";
                $query_args['per_page'] = 1;
            }
            
            // If the token is set try getting private/draft posts as well.
            if (!empty($token)) {
                $query_args['status'] = array('publish', 'draft', 'private');
                $query_args[ FP_POST_API_TOKEN_KEY ] = $token;
            }

            $fetch_url = add_query_arg( $query_args, $api_url );
            $temp_no_pages = $no_pages == 999 ? "X" : $no_pages;
            
            $token_replace_key = defined('WP_ENV') && (WP_ENV == 'local') ? $token : 'TOKEN';
            $ssl_verify = ! ( defined('WP_ENV') && (WP_ENV == 'local') );
            $debug_url = !empty($token) ? preg_replace('#'.$token.'#', $token_replace_key, $fetch_url) : $fetch_url;
            do_action('fppi_importer_debug', "Fetching from: {$debug_url} - page: {$page}/{$temp_no_pages}");
            
			$args = array(
			    'timeout'     => 60,
			    'redirection' => 5,
			    'user-agent'  => 'WordPress/version1.1',
			    'sslverify'   => $ssl_verify,
			); 

            $response = wp_remote_get( $fetch_url, $args );

			if ( is_array( $response ) && ! is_wp_error( $response ) ) {
			    $headers = $response['headers']; // array of http header lines
                $body    = $response['body']; // use the content
                $code    = wp_remote_retrieve_response_code( $response );
                $posts = json_decode($body, true);

                if ( intval($code) != 200 || is_null($posts) ) {
                    $error_count++;
                    do_action('fppi_importer_exception', "Invalid response from API: " . $body );
                    continue;
                }

                if (empty($this->import_post_ids)) {
                    // Update our page number to total number of pages returned.
                    $no_pages = !empty( $headers['x-wp-totalpages'] ) ? $headers['x-wp-totalpages'] : 1;
                }

                do_action('fppi_importer_debug', "Fetched page: {$page}/{$no_pages} - " . self::get_datetime_formatted());

                $this->import_posts( $posts );
                
			} else {
                $error_count++;
				$error_msg = $response->get_error_message();
				do_action('fppi_importer_exception', "Fetch URL Error: {$error_msg}");
			}
		endfor;

        do_action('fppi_importer_summary');


    }
	
    /**
     * Import the posts we fetched from `_fetch` or the action scheduler job queue.
     * This insert/update a post, download the featured image, assign terms, and assign the translation.
     *
     * @param array $posts
     * @return void
     */ 
    public function import_posts( $posts = array() ) {
        
        // Output the open ordered list HTML <ol> for counting the posts.
        do_action('fppi_importer_post_log', false);

        $posts = isset($posts['id']) ? array( $posts ) : $posts;

        // Parse current URL for search and replace.
		$current_url = parse_url( site_url() );
		$replace_host = !empty( $current_url['host'] ) ? $current_url['host'] : false;
		$replace_scheme = !empty( $current_url['scheme'] ) ? $current_url['scheme'] : false;

		$search_host = parse_url( $this->host_url );
		$search_host = !empty( $search_host['host'] ) ? $search_host['host'] : false;
        $search_scheme = !empty( $search_host['scheme'] ) ? $search_host['scheme'] : false;
        
        if (!empty($search_host) && !empty($replace_host)) {
            //do_action('fppi_importer_debug', "Performing search and replace `{$search_host}` for `{$replace_host}`");
        }

        // Now let's insert	
		foreach ( $posts as $index => $imported_post ) {
            
            if ( empty($imported_post['id']) ) {
                do_action('fppi_importer_exception', "Invalid Post Found " . json_encode($imported_post) );
                continue;
            } 
            $importPostID = $imported_post['id'];

            // Ensure we are not importing the wrong lang post id.
            if ( !empty( $imported_post['translation']['current_lang'] ) && !empty($this->import_post_ids) ) {
                if ( $imported_post['translation']['current_lang'] !== $this->import_lang ) {
                    $debug_imported_post['post_title'] = !empty( $imported_post['title']['rendered'] ) ? $imported_post['title']['rendered'] : "";	
                    $debug_imported_post['post_status'] = !empty( $imported_post['status'] ) ? $imported_post['status'] : "";
                    do_action('fppi_importer_skipped', array( 'post_id' => $imported_post['id'], 'post_data' => $debug_imported_post ), "Language" );
                    //do_action('fppi_importer_exception', "Current post is wrong language " . json_encode($imported_post) );
                    continue;
                }
            }

            // Set our cursor point.
            $this->latest_published_post_date = ( strtotime( $imported_post['date'] ) > $this->latest_published_post_date ) ? $imported_post['date'] : $this->latest_published_post_date;

            $post = array(
                //'post_author'           => $user_id,
                'post_content'          => !empty( $imported_post['content']['rendered'] ) ? $imported_post['content']['rendered'] : '',
                'post_title'            => !empty( $imported_post['title']['rendered'] ) ? html_entity_decode( $imported_post['title']['rendered'] ) : '',
                'post_name'             => !empty( $imported_post['slug'] ) ? $imported_post['slug'] : '',
                'post_excerpt'          => !empty( $imported_post['excerpt']['rendered'] ) ? html_entity_decode( $imported_post['excerpt']['rendered'] ) : '',
                'post_status'           => !empty( $imported_post['status'] ) ? $imported_post['status'] : 'private',
                // 'post_modified'         => !empty( $imported_post['modified'] ) ? $imported_post['modified'] : '',
                // 'post_modified_gmt'     => !empty( $imported_post['modified_gmt'] ) ? $imported_post['modified_gmt'] : '',
                'post_type'             => $this->import_post_type,
            );

            $thumbnailURL = $imported_post['featured-image'];
            $media_gallery_images = !empty( $imported_post['media-gallery'] ) ? $imported_post['media-gallery'] : array();
				
            $existing_post = self::get_post_id_by_importedID( $importPostID, $this->import_post_type, 'all' );
            $existing_post_id = !empty($existing_post->ID) ?  $existing_post->ID : false;
            $post_exists = ($existing_post_id !== false);
			$post_id = false; // not false if we were update or inserted

            $post['meta_input'] = array();
            $post['meta_input'] = !empty( $imported_post['meta-fields'] ) ? $imported_post['meta-fields'] : array();
            $post['meta_input'][ self::IMPORTED_META_HOST ] = $this->host_url;
            $post['meta_input'][ self::IMPORTED_META_POST ] = $importPostID;
            // Before we insert, let's unset a reference to featured image that doesn't exist.
            if (isset($post['meta_input']['_thumbnail_id'])) {
                unset($post['meta_input']['_thumbnail_id']);
            }
            // Same with the gallery.
            if (isset($post['meta_input'][ self::MEDIA_GALLERY_META ])) {
                unset($post['meta_input'][ self::MEDIA_GALLERY_META ]);
            }
            $tax_input = !empty( $imported_post['tax-fields'] ) ? $imported_post['tax-fields'] : array();
            // Doesn't save properly, let's do it manually.
            //$post['tax_input'] = !empty( $imported_post['tax-fields'] ) ? $imported_post['tax-fields'] : array();

            $should_search_replace = apply_filters('fp_post_importer_should_search_replace', FP_POST_IMPORTER_API_CLIENT_SEARCH_REPLACE);
            
			// search and replace
			if (!empty($search_host) && !empty($replace_host) && $should_search_replace) {
                // Temp replace all URL's to image 
				$post['post_content'] = str_ireplace( "http://{$search_host}/wp-content/", "{wp-content-url}", $post['post_content'] );
				$post['post_content'] = str_ireplace( "https://{$search_host}/wp-content/", "{wps-content-url}", $post['post_content'] );

                // Replace all other local URLs
				$post['post_content'] = str_ireplace( "http://{$search_host}", "{$replace_scheme}://{$replace_host}", $post['post_content'] );
				$post['post_content'] = str_ireplace( "https://{$search_host}", "{$replace_scheme}://{$replace_host}", $post['post_content'] );

                // Restore 
				$post['post_content'] = str_ireplace( "{wp-content-url}", "http://{$search_host}/wp-content/", $post['post_content'] );
				$post['post_content'] = str_ireplace( "{wps-content-url}", "https://{$search_host}/wp-content/", $post['post_content'] );
            }

            // Check if we are going to include or exclude by tags
            if ( ($this->include_method == 'client') && !empty( $this->import_tags[ $this->import_post_type ]['include'] ) ) {
           // if ( (($this->include_method == 'client') || !empty($this->import_post_ids)) && !empty( $this->import_tags[ $this->import_post_type ]['include'] ) ) {
                $include_string = $this->import_tags[ $this->import_post_type ]['include'];
                $include_terms = explode(",", $include_string);
                foreach($include_terms as $include_term) :
                    $include_parts = explode("=", $include_term);
                    $include_taxonomy = isset( $include_parts[0] ) ? trim($include_parts[0]) : false;
                    $include_slug = isset( $include_parts[1] ) ? trim($include_parts[1]) : false;
                    if (empty($include_taxonomy) || empty($include_slug)) {
                        do_action('fppi_importer_exception', "Post {$importPostID} import error, could not parse import tag {$include_term}");
                        continue;
                    } else {
                        // We don't care if the client side has this tax or not, we are just filtering.
                        // if ( ! taxonomy_exists($include_taxonomy) ) {
                        //     do_action('fppi_importer_exception', "Taxonomy for import tag {$include_taxonomy} does not exist on client, skipping...");
                        //     continue;
                        // }
                        if ( ! isset( $tax_input[ $include_taxonomy ] ) ) {
                            do_action('fppi_importer_debug', "Post {$importPostID} does not contain this taxonomy {$include_taxonomy}, skipping...");
                            continue 2;
                        } else if ( empty($tax_input[ $include_taxonomy ]['terms']) ) {
                            do_action('fppi_importer_debug', "Post {$importPostID} does not contain include terms for this taxonomy {$include_taxonomy}, skipping...");
                            continue 2;
                        } else if ( ! isset( $tax_input[ $include_taxonomy ]['terms'][$include_slug]  ) ) {
                            // If we have our term in this list, continue inserting, otherwise skip... 
                            do_action('fppi_importer_debug', "Post {$importPostID} does not contain include term {$include_slug} for taxonomy {$include_taxonomy}, skipping...");
                            continue 2;
                        } else {
                            do_action('fppi_importer_debug', "Post {$importPostID} contains include term {$include_slug}, continuing...");
                        }
                    } 
                endforeach;
            }
            
            // Now see if we want to skip based on exclude tags.
            if (!empty( $this->import_tags[ $this->import_post_type ]['exclude'] ) ) {
                $exclude_string = $this->import_tags[ $this->import_post_type ]['exclude'];
                $exclude_terms = explode(",", $exclude_string);
                foreach($exclude_terms as $exclude_term) :
                    $exclude_parts = explode("=", $exclude_term);
                    $exclude_taxonomy = isset( $exclude_parts[0] ) ? trim($exclude_parts[0]) : false;
                    $exclude_slug = isset( $exclude_parts[1] ) ? trim($exclude_parts[1]) : false;
                    if (empty($exclude_taxonomy) || empty($exclude_slug)) {
                        do_action('fppi_importer_exception', "Post {$importPostID} import error, could not parse import tag {$exclude_term}");
                        continue;
                    } else {
                        // if ( ! taxonomy_exists($exclude_taxonomy) ) {
                        //     do_action('fppi_importer_exception', "Taxonomy for import tag {$exclude_taxonomy} does not exist on client, skipping...");
                        //     continue;
                        // }

                        //$exclude_tags[ $exclude_taxonomy ][] = $exclude_slug;
                        if ( isset( $tax_input[ $exclude_taxonomy ] ) && !empty($tax_input[ $exclude_taxonomy ]['terms'])) {
                            // If we have our term in this list, continue inserting, otherwise skip... 
                            if ( isset( $tax_input[ $exclude_taxonomy ]['terms'][$exclude_slug]  ) ) {
                                do_action('fppi_importer_debug', "Post {$importPostID} contains exclude term {$exclude_slug}, skipping...");
                                continue 2;
                            } 
                        }
                    }
                endforeach;
            }
            
            // If the post exists by ID, let's update it.
			if ($post_exists) {
                // Check if we need to update it.
                if ( (strtotime( $imported_post['modified_gmt'] ) < strtotime( $existing_post->post_modified_gmt )) && $this->should_skip_unmodified ) {
                    do_action('fppi_importer_skipped', array( 'post_id' => $post_id, 'post_data' => $post ), "Time" );
                } else {
                    $post['ID'] = $existing_post_id;
                    $post_id = wp_update_post( $post, true );
                    if( is_wp_error($post_id) ){
                        $msg = $post_id->get_error_message();
                        do_action('fppi_importer_exception', "Post {$importPostID} exists but cannot update. {$msg}");
                    } else {
                        do_action('fppi_importer_updated', array( 'post_id' => $post_id, 'post_data' => $post ) );

                        // Set feature image if available or set default image
                        if (!empty($thumbnailURL)) {
                            do_action('fppi_importer_debug', "Featured image exists, attempting to download and assign...");
                            if ($this->download_attachments) {
                                do_action('fppi_importer_debug', "Downloading featured image(s)...");
                                self::add_featured_image( $post_id, $thumbnailURL );
                            }
                        }
                        else{
                            do_action('fppi_importer_debug', "No featured image exists, attempting to set default image...");
                            self::add_default_image( $post_id );
                        }
                    }
                }
                
			} else {
                // Post doesn't exist by ID but maybe by slug.
                // Check if the post exists by slug, could have been inserted manually or for some reason missing the post meta. 
                $post_slug = $post['post_name'];
                $post_slug_exists_id = self::get_post_id_by_name( $post_slug, $this->import_post_type );
                $post_slug_exists = (bool) (!empty($post_slug_exists_id));
                if ($post_slug_exists) {
                    // Let's import the post meta so we can find it next time.
                    update_post_meta($post_slug_exists_id, self::IMPORTED_META_HOST, $this->host_url );
                    update_post_meta($post_slug_exists_id, self::IMPORTED_META_POST, $importPostID );
                    do_action('fppi_importer_skipped', array( 'post_id' => $post_slug_exists_id, 'post_data' => $post ), "Slug" );
                    do_action('fppi_importer_exception', "Post {$post_slug} exists locally by slug, skipping insertion.");
                } else {
                    unset($post['ID']);
                    $post_id = wp_insert_post( $post, true );
                    if( is_wp_error($post_id) ){
                        $msg = $post_id->get_error_message();
                        do_action('fppi_importer_exception', "Post {$importPostID} cannot be inserted. {$msg}");
                    } else {
                        do_action('fppi_importer_inserted', array( 'post_id' => $post_id, 'post_data' => $post ) );

                        if (!empty($thumbnailURL)) {
                            do_action('fppi_importer_debug', "Source featured image exists");
                            if ($this->download_attachments) {
                                do_action('fppi_importer_debug', "Downloading and assigning featured image...");
                                self::add_featured_image( $post_id, $thumbnailURL );
                            }
                        }
                        else{
                            do_action('fppi_importer_debug', "No featured image exists, attempting to set default image...");
                            self::add_default_image( $post_id );
                        }                        
                    }
                }
			}

            // If we made a successful insert/update.
			if ($post_id) {
                // Maybe insert media_gallery images
                if (!empty($media_gallery_images) && is_array($media_gallery_images)) {
                    $total_gallery_images = count($media_gallery_images);
                    do_action('fppi_importer_debug', "Media Gallery images - {$total_gallery_images} found.");
                    if ($this->download_attachments) {
                        $gallery_image_meta = [];
                        foreach($media_gallery_images as $gallery_index => $gallery_image) {
                            $current_index = $gallery_index+1;
                            do_action('fppi_importer_debug', "Downloading and assigning gallery image {$current_index}/{$total_gallery_images}...");
                            $local_attachmentID = self::add_featured_image( $post_id, $gallery_image, false );
                            if (!empty($local_attachmentID)) {
                                $gallery_image_meta[] = $local_attachmentID;
                            } else {
                                do_action('fppi_importer_exception', "Failed to add gallery image {$current_index}.");
                            }
                        }
                        if (!empty($gallery_image_meta)) {
                            update_post_meta($post_id, self::MEDIA_GALLERY_META, $gallery_image_meta);
                        }
                    }
                }

                global $polylang;
                // Set our post language with Polylang no matter first.
                if (!empty($this->import_lang) && function_exists('pll_set_post_language') && empty($this->import_post_ids)) {
                    // Set our language first
                    pll_set_post_language($post_id, $this->import_lang);
                }

                clean_post_cache($post_id);
                $translated_original_terms = array();
                // Insert our terms.
				foreach ( $tax_input as $tax => $tax_terms ) {
                    $this->taxonomies_to_ignore = apply_filters('fp_post_importer_ignore_taxonomies', $this->taxonomies_to_ignore);
                    if ( in_array($tax, $this->taxonomies_to_ignore) ) {
                        continue;
                    }
                    // do_action('fppi_importer_debug', "Inserting terms...");

					// Skip wpcontent client's WPML's translation_priority taxonomy
					if ( $tax === 'translation_priority' ) {
                        continue;
                    }

                    if ( !taxonomy_exists( $tax ) ) {
                        do_action('fppi_importer_debug', "Taxonomy {$tax} does not exist on client, skipping inserting these terms...");
                        continue;
                    }

                    // Check if we have a mismatch in translatable taxonomies...
                    if (!empty($polylang->model) && method_exists( $polylang->model, 'is_translated_taxonomy')) {
                        $is_translatable = $polylang->model->is_translated_taxonomy( $tax );
                        if ( isset($tax_terms['is_translated']) ) {
                            if ( $tax_terms['is_translated'] !== $is_translatable ) {
                                $is_translatable_string = $is_translatable ? "is translated" : "is not translated";
                                $is_host_translatable = $tax_terms['is_translated'] ? "is translated" : "is not translated";
                                do_action('fppi_importer_exception', "Taxonomy {$tax} translatable mismatch, client {$is_translatable_string} vs host {$is_host_translatable}, skipping...");
                                continue;
                            }
                        }
                    }

                    $is_hierarchical = ($tax_terms['type'] === 'hierarchical');
                    $local_taxonomy = get_taxonomy($tax);
                    if (isset($local_taxonomy->hierarchical) && ($local_taxonomy->hierarchical !== $is_hierarchical)) {
                        do_action('fppi_importer_exception', "Taxonomy {$tax} format mismatch hierarchical vs non-hierarchical, skipping...");
                        continue;
                    }
                    
                    $category_terms = array();
                    $translated_original_terms[$tax] = $tax_terms['translations'];
                    if ( empty($tax_terms['terms']) ) {
                        do_action('fppi_importer_debug', "No terms to import for {$tax}");
                        continue;
                    }

                    $should_create_terms = apply_filters('fp_post_importer_should_create_terms', FP_POST_IMPORTER_API_CLIENT_CREATE_TERMS);
                    $this->should_append_terms = apply_filters('fp_post_importer_should_append_terms', $this->should_append_terms);

                    $original_terms = [];
                    foreach( $tax_terms['terms'] as $slug => $term ) {
                        $original_terms[$slug] = $term['name'];
                        $new_term = $this->create_or_get_term( $term, $tax, $is_hierarchical, $should_create_terms );
                        if (!empty($new_term)) {
                            $category_terms[] = $new_term;
                        }
                    }

                    $original_term_list = json_encode($original_terms);
                    do_action('fppi_importer_debug', "Original term list for taxonomy \"{$tax}\": {$original_term_list}");

                    if (count($category_terms) !== count( $tax_terms['terms'] )) {
                        $term_list = array_keys($tax_terms['terms']);
                        $term_diff = array_diff( $category_terms,  $term_list );
                        do_action('fppi_importer_exception', "Terms inserted vs. Original term count mismatch, terms not created/existing: " . json_encode($term_diff) );
                    }

                    if (!empty($category_terms)) {
                        do_action('fppi_importer_debug', "Taxonomy \"{$tax}\", assigning terms: " . json_encode($category_terms));
                        $update = wp_set_post_terms($post_id, $category_terms, $tax, $this->should_append_terms);
                        if (is_wp_error($update)) {
                            do_action('fppi_importer_exception', "WP Error assigning terms: " . $update->get_error_message() );
                        } else {
                            do_action('fppi_importer_debug', "Successfully assigned all terms.");
                        }
                    }
                } // End insert terms.
                
                if ( !empty( $imported_post['translation'] ) ) {
                    global $sitepress;
                    $current_lang = !empty( $imported_post['translation']['current_lang'] ) && !empty($this->import_post_ids) ? $imported_post['translation']['current_lang'] : $this->import_lang;

                    // Polylang support for connecting the translations now.
                    if (function_exists('pll_default_language')) {

                        // If we are importing posts by IDs let's capture the french alternative if any
                        // if (!empty($this->import_post_ids)) {
                        //     pll_set_post_language($post_id, $current_lang);
                        //     foreach ( $imported_post['translation']['translations'] as $language_code => $translation_info ) {
                        //         if ( $language_code == $current_lang ) {
                        //             continue;
                        //         }
                        //         // If it's not the current language
                        //         // $this->translated_post_ids[ $language_code ][] = intval( $translation_info['element_id'] );
                        //     }
                        // }
                        
                        // Assign the post translation
                        // @todo we want to ensure, whatever the default language is, we are still importing the translations. We assume the default language will be first
                        $default_lang = pll_default_language();
                        if ( $default_lang !== $this->import_lang ) {
                            do_action('fppi_importer_debug', "Attempting to link {$this->import_lang} language post via PolyLang...");

                            $is_translatable = pll_is_translated_post_type($this->import_post_type);
                            if (!$is_translatable) {
                                do_action('fppi_importer_exception', "PolyLang - this post type `{$this->import_post_type}` is not translatable, skipping");
                                continue;
                            }
                            
                            // See if we imported the orignal translation, if so this is how we will assign.
                            $original_source_lang_postID = $imported_post['translation']['original_id'];
                        
							$original_lang_postID = self::get_post_id_by_importedID( $original_source_lang_postID, $this->import_post_type );
                        
							if (empty($original_lang_postID)) {
                                do_action('fppi_importer_exception', "Cannot find the default language post ID {$original_source_lang_postID} locally, skipping language post assignment");
                                continue;
                            }

							// Current post translations
                            $post_languages = pll_get_post_translations($original_lang_postID);
							// The imported post's current lang
                            $post_languages[ $current_lang ] = intval($post_id);
							// Need to include the aternate lang when we update with pll_save_post_translations
							// Add the opposite to the language being linked
							if ( strtolower($current_lang) == 'fr' ) {
								$post_languages[ 'en' ] = intval($original_lang_postID);
							} else {
								$post_languages[ 'fr' ] = intval($original_lang_postID);
							}

							// Save the assigned translations
							pll_save_post_translations($post_languages);

                            do_action('fppi_importer_debug', "Successfully link $this->import_lang translation for {$post_id} to {$original_lang_postID}");

                            // Assign the post term translation
                            if (!empty($translated_original_terms)) {
                                do_action('fppi_importer_debug', "Looking for term translations for {$post_id}...");
                                // We know terms for this tax exist already
                                foreach($translated_original_terms as $translation_taxonomy => $translations) {
                                    
                                    // Loop through each translations term, since we are not in the default lang, we look for that.
                                    foreach($translations as $alt_lang_slug => $alt_langs) {

                                        if ( isset($alt_langs[$default_lang]) ) {
                                            $translation_slug = $alt_langs[$default_lang]['slug'];
                                            //$translation_name = $alt_langs[$default_lang]['name'];

                                            do_action('fppi_importer_debug', "Looking for term translations with {$alt_lang_slug} => {$translation_slug}");
                                            
                                            // Since we are on the alt lang import now, the default version must exist from the previous round.
                                            $exists = term_exists( $translation_slug, $translation_taxonomy );
                                            $original_exists = term_exists( $alt_lang_slug, $translation_taxonomy );

                                            // We always want to append the term, PL plugin will set the term if we make the translation.
                                            //$update = wp_set_post_terms($post_id, array( $category_term->term_id ), $category_term->taxonomy, true);
                                            if ( $exists && $original_exists ) {
                                                $term_translations = pll_get_term_translations( (int) $original_exists['term_id'] );
                                                $term_translations[ $default_lang ] = (int) $exists['term_id'];
                                                pll_save_term_translations($term_translations);
                                                do_action('fppi_importer_debug', "Updated term translations with {$alt_lang_slug} => {$translation_slug}");
                                            } else {
                                                do_action('fppi_importer_debug', "Cannot find original slug term {$translation_slug}, skipping");
                                            }
                                        }
                                    }
                                }
                            }
                        }

                        // pll_is_translated_post_type($post_type);
                        // pll_get_post_language($post_id, $field);
                        // pll_set_post_language($post_id, $lang);
                        // pll_save_post_translations
                        // pll_get_post($post_id, $slug);
                        // pll_the_languages(array('hide_empty'=>0,'display_names_as'=>'slug')); 
                        // pll_default_language('');
                        // pll_save_term_translations
                    }
                    
                    // WPML Import Support
                    // If the WPML plugin global function is not available, skip the translation import.
                    else if ( $sitepress !== NULL && method_exists( $sitepress, 'get_default_language' ) ) {
                        //do_action('fppi_importer_debug', "SitePress global function not found, cannot assign {$this->import_lang}");
                        //continue;

                        // If we are importing posts by IDs let's capture the french alternative if any
                        // Not capturing alt lanugages right now.
                        // if (!empty($this->import_post_ids)) {
                        //     foreach ( $imported_post['translation']['translations'] as $language_code => $translation_info ) {
                        //         if ( $language_code == $this->import_lang ) {
                        //             continue;
                        //         }
                        //         // If it's not the current language
                        //         // $this->translated_post_ids[ $language_code ][] = intval( $translation_info['element_id'] );
                        //     }
                        // }
                        
                        if ( $sitepress->get_default_language() !== $this->import_lang ) {
                            do_action('fppi_importer_debug', "Assigning {$this->import_lang} language via WPML...");
                            $wpml_element_type = $imported_post['translation']['type'];
                            
                            // See if we imported the orignal translation, if so this is how we will assign.
                            $original_lang_postID = self::get_post_id_by_importedID( $imported_post['translation']['original_id'], $this->import_post_type );
                            if (empty($original_lang_postID)) {
                                do_action('fppi_importer_exception', "SitePress cannot find original EN version post, skipping language assignment");
                                continue;
                            }
                            $get_language_args = array('element_id' => $original_lang_postID, 'element_type' => $this->import_post_type );
                            $original_post_language_info = apply_filters( 'wpml_element_language_details', null, $get_language_args );
                            
                            $set_language_args = array(
                                'element_id'    => $post_id,
                                'element_type'  => $wpml_element_type,
                                'trid'   => $original_post_language_info->trid,
                                'language_code'   => $this->import_lang,
                                'source_language_code' => $original_post_language_info->language_code,
                            );
                            // Assign here.
                            do_action( 'wpml_set_element_language_details', $set_language_args );
                        }
                        // End WPML/SitePress import.
                    }    
                }
            }          

        }

        do_action('fppi_importer_post_log', true);
    }

    /**
     * Recursively create/or add terms to the category list.
     *
     * @param WP_Term $term
     * @param string $taxonomy
     * @param boolean $is_hierarchical
     * @param boolean $should_create_terms
     * @return WP_Term
     */
    public function create_or_get_term( $term = false, $taxonomy = false, $is_hierarchical = false, $should_create_terms = false ) {
		// Ignore WPML taxonomy on wpcontent client
		if ( $taxonomy !== 'translation_priority' ) {
			if (!empty($term['parent_term'])) {
				$new_parent_term = $this->create_or_get_term( $term['parent_term'], $taxonomy, $is_hierarchical, $should_create_terms );
			}
			$exists = term_exists( $term['slug'], $taxonomy );
			$category_term = false;
			if ( $exists ) {
				$category_term = $is_hierarchical ? $exists['term_id'] : $term['slug'];
				//$category_term = $exists['term_id'];

				// Set our term language with Polylang no matter.
				if (!empty($this->import_lang) && function_exists('pll_set_term_language')) {
					// Set our language first
					pll_set_term_language( (int) $exists['term_id'], $this->import_lang);
				}
			} else if ( $should_create_terms ) { // If we have creating new terms disabled.
				$term_name = $term['name'];
				$args = array(
					'slug' => $term['slug'],
					'description' => $term['description'],
				);
				if (!empty($new_parent_term)) {
					$args['parent'] = $new_parent_term;
				}
				$new_term = wp_insert_term(
					$term_name, // the term 
					$taxonomy, // the taxonomy
					$args
				);
				if ( ! is_wp_error($new_term) ) {
					$category_term = $is_hierarchical ? $new_term['term_id'] : $term['slug'];
					//$category_term = $new_term['term_id'];
					// Set our term language with Polylang no matter.
					if (!empty($this->import_lang) && function_exists('pll_set_term_language')) {
						// Set our language first
						pll_set_term_language( (int) $new_term['term_id'], $this->import_lang);
					}
				} else {
					$error_message = $new_term->get_error_message();
					do_action('fppi_importer_exception', "Taxonomy {$taxonomy}, failed to create term {$term_name}, error: {$error_message}");
				}
			}
		}
        return $category_term;
    }
    
    /**
	 * Try to fetch any previously-imported post by it's original post id.
	 * @param int $post_ID
	 * @return WP_Post
	 */
	static function get_post_id_by_importedID( $originalPostID = null, $post_type = "post", $fields = 'ids' ) {
		$args = array(
            'numberposts' => 1,
            'post_type' => $post_type,
            'post_status' => 'any',
            'meta_key' => self::IMPORTED_META_POST,
            'meta_value' => $originalPostID,
            'fields'      => $fields,
            'suppress_filters' => true,
            'lang'  => '',
        );
		$original_post = get_posts($args);
		return reset($original_post);
    }

    /**
	 * Try to fetch any previously-imported post by it's original post name.
	 * @param int $post_ID
	 * @return WP_Post
	 */
	static function get_post_id_by_name( $originalPostName = null, $post_type = "post", $fields = 'ids' ) {
		$args = array(
            'numberposts' => 1,
            'post_type' => $post_type,
            'post_status' => 'any',
            'name' => $originalPostName,
            'fields'      => $fields,
            'suppress_filters' => true,
            'lang'  => '',
        );
		$original_post = get_posts($args);
		return reset($original_post);
    }
    
    /**
	 * Try to fetch any previously-imported attachment by it's original URL.
	 * @param string Attachment URL
	 * @return int|null
	 */
	static function get_attachment_id_by_URL( $originalURL = null, $post_type = "attachment", $fields = 'ids' ) {
		$args = array(
            'numberposts' => 1,
            'post_type' => $post_type,
            'post_status' => 'any',
            'meta_key' => self::IMPORTED_META_ATTACHMENT,
            'meta_value' => $originalURL,
            'fields'      => $fields,
        );
		$original_post = get_posts($args);
		return array_shift($original_post);
	}

	/**
	 * Add Featured Image
	 *
	 * Add a featured image to the post from the post.
	 * @param integer $postID The post ID
	 * @param object  $featuredImage The image object.
	 */
	public static function add_featured_image($postID = 0, $featuredImage = array(), $assignFeatured = true) {
		// Check that $postID isset() and is_numeric()
		if (!isset($postID) || !is_numeric($postID)) {
			return;
		}

		if (empty($featuredImage['src'])) {
			return;
        }
        
        $featuredImageURL = $featuredImage['src'];

        // Ensure we are not inserting duplicates of this attachment.
        $existing_attachmentID = self::get_attachment_id_by_URL( $featuredImageURL );
        $attachment_exists = (bool) (!empty($existing_attachmentID));
        if ($attachment_exists) {
            // Set post thumbnail
            if ($assignFeatured) {
                $setPostThumbnail = set_post_thumbnail($postID, $existing_attachmentID);
            }
            do_action('fppi_importer_debug', "Attachment exists, ID: {$existing_attachmentID}, assigning but skipping the download...");
            return $existing_attachmentID;
        }

        // Debugging locally
        // $options = array(
        //     "ssl"=>array(
        //         "verify_peer"=>false,
        //         "verify_peer_name"=>false,
        //     ),
        // );
        // $featuredImagedData    = file_get_contents($featuredImageURL, false, stream_context_create($options));
        $featuredImagedData    = file_get_contents($featuredImageURL);
        if (empty($featuredImagedData)) {
            do_action('fppi_importer_exception', "Failed to download image at URL {$featuredImageURL}, skipping...");
            return false;
        }
        
        $featuredImageFilename = explode("?", basename($featuredImageURL));
		$featuredImageFilename = $featuredImageFilename[0];
                
		// Set $uploadDir
		$uploadDir = wp_upload_dir();

		// Get upload dir
		if (wp_mkdir_p($uploadDir['path'])) {
			$featuredImagePath = $uploadDir['path'] . "/" . $featuredImageFilename;
		} else {
			$featuredImagePath = $uploadDir['basedir'] . "/" . $featuredImageFilename;
		}

		// Upload file to upload dir
		file_put_contents($featuredImagePath, $featuredImagedData);

		// Set file type
		$featuredImageFileType = wp_check_filetype($featuredImagePath, null);

		// Attachment properties
		$attachment = array(
			'post_mime_type' => $featuredImageFileType['type'],
			'post_title'     => !empty( $featuredImage['title'] ) ? $featuredImage['title'] : sanitize_file_name($featuredImageFilename),
            'post_content'   => !empty( $featuredImage['description'] ) ? $featuredImage['description'] : '',
            'post_excerpt'   => !empty( $featuredImage['caption'] ) ? $featuredImage['caption'] : '',
			'post_status'    => 'inherit',
		);

		// Insert featured image as attachment
        $attachmentID = wp_insert_attachment($attachment, $featuredImagePath, $postID);
        
        // Add the alt attribute.
        if ( !empty( $featuredImage['alt'] ) ) {
            update_post_meta($attachmentID, '_wp_attachment_image_alt', $featuredImage['alt']);
        }

        update_post_meta($attachmentID, self::IMPORTED_META_ATTACHMENT, $featuredImageURL );

		// Require WordPress image manipulation library
		require_once(ABSPATH . 'wp-admin/includes/image.php');

		// Generate attachment metadata
		$attachmentMetaData = wp_generate_attachment_metadata($attachmentID, $featuredImagePath);

		// Update attachment metadata
		wp_update_attachment_metadata($attachmentID, $attachmentMetaData);

        // Set post thumbnail
        if ($assignFeatured) {
            $setPostThumbnail = set_post_thumbnail($postID, $attachmentID);
        }
        return $attachmentID;
    }

	/**
	 * Add Default Image
	 *
	 * Add a default image to the post.
	 * @param integer $postID The post ID
	 */
	function add_default_image($postID = 0) {

        if (!isset($postID) || !is_numeric($postID)) {
			return;
		}
        
        $site = get_site_url();

        $defaultImage = FP_POST_IMPORTER_PLUGIN_DIR . '/assets/img/Default-Sobeys.png';

        if(str_contains($site, 'safeway')){
            $defaultImage = FP_POST_IMPORTER_PLUGIN_DIR . '/assets/img/Default-Safeway.png'; 
        }

        if(str_contains($site, 'boni')){
            $defaultImage = FP_POST_IMPORTER_PLUGIN_DIR . '/assets/img/Default-BoniChoix.png'; 
        }        

        if(str_contains($site, 'rachelle')){
            $defaultImage = FP_POST_IMPORTER_PLUGIN_DIR . '/assets/img/Default-Rachelle.png'; 
        }

        if(str_contains($site, 'marches')){
            $defaultImage = FP_POST_IMPORTER_PLUGIN_DIR . '/assets/img/Default-Tradition.png'; 
        }        


        if(str_contains($site, 'foodland')){
            $defaultImage = FP_POST_IMPORTER_PLUGIN_DIR . '/assets/img/Default-Foodland.png'; 
        }        


        if(str_contains($site, 'iga')){
            $defaultImage = FP_POST_IMPORTER_PLUGIN_DIR . '/assets/img/Default-IGA.png'; 
        }        
        
        $filename = basename( $defaultImage );
        $upload_file = wp_upload_bits( $filename, null, file_get_contents( $defaultImage ) );

        if ( !$upload_file['error'] ){
            $wp_filetype = wp_check_filetype( $filename, null );
            $attachment = array(
              'post_mime_type' => $wp_filetype['type'],
              'post_title' => preg_replace('/\.[^.]+$/', '', $filename),
              'post_content' => '',
              'post_status' => 'inherit'
            );
            $attachment_id = wp_insert_attachment( $attachment, $upload_file['file'], $parent );
        
            if ( !is_wp_error( $attachment_id ) ) {
                $data = wp_generate_attachment_metadata( $attachment_id, $upload_file['file'] );
                wp_update_attachment_metadata( $attachment_id,  $data );
            }

            set_post_thumbnail( $postID, $attachment_id );            
            
            do_action('fppi_importer_exception', "Default image set");
        
        }
        else{
            do_action('fppi_importer_exception', "Failed to download image at URL {$defaultImage}, skipping...");
            return false;            
        }

        return $attachment_id;              
    
    }    

    /**
     * Helper function to log output from our action hooks, this will also keep a toll of posts inserted/updated/deleted...
     *
     * @param [WP_Post] $post
     * @param [string] $message
     * @return void
     */
    public function logger($post, $message = null) {
        ob_start();
        assert(preg_match('/fppi_importer_(.+)/', current_filter(), $matches));
        $type = $matches[1];
        if (!array_key_exists($type, $this->action_counts)) {
            $this->action_counts[$type] = 0;
        }
        $post_id = $post['post_id'];
        $post_name = wp_strip_all_tags($post['post_data']['post_title']);
        $post_status = wp_strip_all_tags($post['post_data']['post_status']);
        $this->action_counts[$type] += 1;
        $nice_typename = str_ireplace("_", " ", $type);
        $nice_typename = ucwords($nice_typename);
        printf("<li class='%s'>%s Post: %s%s (%s)</li>\n",
            esc_attr($type),
            esc_html($nice_typename),
            esc_html($post_id),
            $message ? ": $message" : " $post_name",
            esc_html($post_status)
        );
        $contents = ob_get_clean();
        $this->update_log( $contents );
    }
    
    /**
     * Error logger.
     *
     * @param [string] $message
     * @return void
     */ 
    public function error_logger($message) {
        ob_start();
        printf("<p class='error'>Error: %s</p>\n", esc_html($message));
        $contents = ob_get_clean();
        $this->update_log( $contents );
    }

    /**
     * Debug logger.
     *
     * @param [string] $message
     * @return void
     */
    public function debug_logger($message) {
        ob_start();
        printf("<p class='debug'>%s</p>\n", esc_html($message));
        $contents = ob_get_clean();
        $this->update_log( $contents );
    }

    /**
     * Summary logger.
     *
     * @return void
     */
    public function summary_logger() {
        ob_start();
        $language_desc = !empty($this->import_lang) ? sprintf("(%s):", strtoupper($this->import_lang) ) : "";
        ?>
        <div id="summary">
            <p class="title"><?php printf('Import Summary for %s %s', $this->import_post_type, $language_desc); ?></p>
            <?php if (!empty($this->action_counts)) : ?>
                <?php foreach ($this->action_counts as $action => $count): ?>
                    <p><?php print esc_html("$action: $count") ?></p>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="title">No posts imported.</p>
            <?php endif; ?>
        </div>
        <?php
        $contents = ob_get_clean();
        $this->update_log( $contents );
    }

    /**
     * Summary logger.
     *
     * @return void
     */
    public function delete_summary_logger() {
        ob_start();
        ?>
        <div id="summary">
            <p class="title"><?php printf('Delete Summary for post type %s:', $this->import_post_type); ?></p>
            <?php if (!empty($this->action_counts)) : ?>
                <?php foreach ($this->action_counts as $action => $count): ?>
                    <p><?php print esc_html("$action: $count") ?></p>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="title">No posts deleted.</p>
            <?php endif; ?>
        </div>
        <?php
        $contents = ob_get_clean();
        $this->update_log( $contents );
    }

    /**
     * Footer logger.
     *
     * @param [string] $message
     * @return void
     */
    public function footer_logger($message) {
        ob_start();
        ?>
        <div id="summary">
            <p class="title"><?php echo $message ?></p>
        </div>
        <?php
        $contents = ob_get_clean();
        $this->update_log( $contents );
    }

    /**
     * Header logger. will log any heading formatting.
     *
     * @param [string] $message
     * @return void
     */
    public function header_logger ($message) {
        ob_start();
        if (defined( 'DOING_CRON' ) && DOING_CRON) { ?>
        <!DOCTYPE html>
        <html>
        <?php } ?>     
        <p class="title"><?php echo $message ?></p>
        <?php
        $contents = ob_get_clean();
        $this->update_log( $contents );
    }

    /**
     * Format logger.
     *
     * @param [bool] $end
     * @return void
     */
    public function ol_logger( $end = false ) {
        ob_start();
        if (!$end) {
            echo "<ol>";
        } else {
            echo "</ol>";
        }
        $contents = ob_get_clean();
        $this->update_log( $contents );
    }

    /**
     * Set the action counts for the importer.
     *
     * @return bool
     */
    public function set_action_counts() {
        return update_option('fppi_client_import_action_counts', $this->action_counts, 'no');
    }

    /**
     * Fetch the action counts for the importer. 
     *
     * @return array
     */
    public function fetch_action_counts() {
        return get_option('fppi_client_import_action_counts', array());
    }

    /**
     * Clear the action counts.
     *
     * @return bool
     */
    public function clear_action_counts() {
        $this->action_counts = [];
        return delete_option('fppi_client_import_action_counts');
    }

    /**
     * Helper function to fetch the log AJAX callback.
     *
     * @return void
     */
    public static function fetch_log_ajax() {
        $response = [
            'log' => self::fetch_log(),
            'percent' => 0,
        ];
        if (empty( $response['log'] )) {
            $response['percent'] = 0;
        } else {
            $total_job_count = (int) get_option('fppi_total_job_count', 0);
            if (0 !== ($total_job_count)) {
                $total_pending = count( self::get_pending_importer_jobs() );
                $jobs_left = $total_job_count - $total_pending;
                if ($jobs_left === 0) {
                    $response['percent'] = 100;
                } else {
                    $response['percent'] = ceil( ($jobs_left / $total_job_count) * 100 );
                    $response['percent'] = min( 100, $response['percent'] ); // ensure we don't go past 100
                }
                //$response['total'] = $total_job_count;
                //$response['jobs'] = $jobs_left;
            }
        }
        wp_send_json_success($response);
    }
    
    /**
     * Helper function to fetch the log.
     *
     * @return void
     */
    public static function fetch_log() {
        if ( apply_filters('fppi_log_to_file', true) ) {
            $log_filename = self::get_log_file( 'fppi' );
            if (file_exists($log_filename)) {
                $log_output = file_get_contents( $log_filename );
            } else {
                $log_output = '';
            }
        } else {
            $log_output = get_transient('fppi_output_log');
        }
        return $log_output;
    }

    /**
     * Helper function to update the log.
     *
     * @return void
     */
    public function update_log( $message = '', $append = true ) {
        $log = $append ? self::fetch_log() . $message : $message;
        if ( apply_filters('fppi_log_to_file', true) ) {
            $log_filename = self::get_log_file( 'fppi' );
            return file_put_contents( $log_filename, $log );
        } else {
            return set_transient('fppi_output_log', $log);
        }
    }

    /**
     * Helper function to flush the log.
     *
     * @return void
     */
    public function flush_log() {
        if ( apply_filters('fppi_log_to_file', true) ) {
            $log_filename = self::get_log_file( 'fppi' );
            if (file_exists($log_filename)) {
                unlink($log_filename);
            }    
        } else {
            delete_transient('fppi_output_log');
        }
    }

    /**
     * Get the datetime formatted and in the default timezone.
     *
     * @return void
     */
    public static function get_datetime_formatted( $date_format = false ){
        $timezone = get_option('timezone_string');
        if ( !empty($timezone) ) {
            date_default_timezone_set( $timezone );
        }
        $format = empty($date_format) ? 'Y-m-d\TH:i:s' : $date_format;
        $datetime = date($format);
        return $datetime;
    }

}