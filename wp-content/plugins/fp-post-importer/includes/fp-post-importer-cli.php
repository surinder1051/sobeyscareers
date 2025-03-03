<?php
if ( defined('WP_CLI') && WP_CLI ) {

	class FP_Post_Importer_CLI extends WP_CLI_Command {

        static $action_counts = array();
        
        var $post_importer_client;

        /**
         * Used to run of the post importer from CLI.
         *
         * ## OPTIONS
         *
         * [--page=<page_number>]
         * : Page number to start at.
         * 
         * 
         * [--download_attachments=<true|false>]
         * : Download attachments or not.
         * 
         * [--cursor=<datetime>]
         * : Formatted ISO 8601 datetime.
         * 
         * ## EXAMPLES
         *
         *     wp post-importer import --page=10 --download_attachments=false
         *
         * @subcommand import
		 * 
         */
		function import( $args, $assoc_args ) {
            set_time_limit ( 0 );
            $this->setup_logger_actions();
            $this->post_importer_client = FP_Post_Importer_Client::getInstance();
            // Options
            $this->post_importer_client->download_attachments = !empty($assoc_args['download_attachments']) ? (bool) $assoc_args['download_attachments'] : false;
            $this->post_importer_client->start_page = !empty($assoc_args['page']) ? (int) $assoc_args['page'] : 1;
            $this->post_importer_client->last_modified_cursor = !empty($assoc_args['cursor']) ? $assoc_args['cursor'] : $this->post_importer_client->last_modified_cursor;

            WP_CLI::line( "Cursor date:" . FP_Post_Importer_Admin::get_client_import_cursor() );
            $this->post_importer_client = FP_Post_Importer_Client::getInstance();
            WP_CLI::line('Loading actions into the job queue...');
            $this->post_importer_client->load_import_into_as();

            $options = array(
                //'return'     => true,   // Return 'STDOUT'; use 'all' for full object.
                //'parse'      => 'json', // Parse captured STDOUT to JSON array.
                'launch'     => false,  // Reuse the current process.
                'exit_error' => true,   // Halt script execution on error.
            );
            $cmd = sprintf('action-scheduler run --batches=0 --group=%s --force', FP_Post_Importer_Admin::SCHEDULED_HOOK);
            //$cmd = 'plugin list --status=active';
            $run_import = WP_CLI::runcommand( $cmd, $options );

        }

        /**
         * Get the import time cursor.
         *
         * ## EXAMPLES
         *
         *     wp post-importer get-cursor
         *
         * @subcommand get-cursor
		 * 
         */
        function get_client_import_cursor() {
            WP_CLI::line( FP_Post_Importer_Admin::get_client_import_cursor() );
        }

        /**
         * TESTING.
         *
         * ## EXAMPLES
         *
         *     wp post-importer testing
         *
         * @subcommand testing
		 * 
         */
        function test_call() {
            $this->setup_logger_actions();
            WP_CLI::line( "Cursor date:" . FP_Post_Importer_Admin::get_client_import_cursor() );
            $this->post_importer_client = FP_Post_Importer_Client::getInstance();
            $this->post_importer_client->load_import_into_as();
        }

        /**
         * Clear the process lock.
         *
         * ## EXAMPLES
         *
         *     wp post-importer clear-lock
         *
         * @subcommand clear-lock
		 * 
         */
        function clear_process_lock() {
            $this->post_importer_client = FP_Post_Importer_Client::getInstance();
            $this->post_importer_client->clear_process_lock_internal();
            WP_CLI::success("Cleared");
        }

        /**
         * Clear the AS jobs.
         *
         * ## EXAMPLES
         *
         *     wp post-importer clear-jobs
         *
         * @subcommand clear-jobs
		 * 
         */
        function clear_all_as_jobs() {
            $this->post_importer_client = FP_Post_Importer_Client::getInstance();
            $result = $this->post_importer_client->cancel_all_pending_jobs();
            $this->post_importer_client->clear_process_lock_internal();
            if (is_string($result)) {
                WP_CLI::warning("No jobs to clear");
            } else {
                WP_CLI::success("Cleared {$result} jobs.");
            }        
        }

        
        /**
         * Clear the AS jobs log for importing pages.
         *
         * ## EXAMPLES
         *
         *     wp post-importer flush-job-log
         *
         * @subcommand flush-job-log
		 * 
         */
        function flush_as_logs() {
            global $wpdb;
            $statuses = ['complete', 'canceled', 'failed'];
            $status_list = implode("', '", $statuses);
            $slug = FP_Post_Importer_Admin::SCHEDULED_HOOK;
            $results = $wpdb->get_row( "SELECT group_id FROM {$wpdb->prefix}actionscheduler_groups WHERE slug = '{$slug}'" );
            if (empty($results)) {
                WP_CLI::line('No jobs found');
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
            WP_CLI::success("Cleared");
        }

        /**
         * Clear the AS jobs log.
         *
         * ## EXAMPLES
         *
         *     wp post-importer flush-all-job-log
         *
         * @subcommand flush-all-job-log
		 * 
         */
        function flush_all_as_logs() {
            global $wpdb;
            $wpdb->get_results( "DELETE FROM {$wpdb->prefix}actionscheduler_logs" );
            $wpdb->get_results( "DELETE FROM {$wpdb->prefix}actionscheduler_actions" );
            WP_CLI::success("Cleared");
        }

        
        function setup_logger_actions() {
            // Load our actions to capture all import actions.
            //add_action('fppi_importer_header', array( 'WP_CLI', 'header_logger'));
            add_action('fppi_importer_header', array( $this, 'debug_logger'));
            add_action('fppi_importer_skipped', array( $this, 'post_logger'));
            add_action('fppi_importer_deleted', array( $this, 'post_logger'));
            add_action('fppi_importer_inserted', array( $this, 'post_logger'), 10, 2);
            add_action('fppi_importer_updated', array( $this, 'post_logger'));
            add_action('fppi_importer_failed', array( $this, 'post_logger'));

            add_action('fppi_importer_adding_featured_image', array( $this, 'debug_logger'));
            add_action('fppi_importer_debug', array( $this, 'debug_logger'));
            add_action('fppi_importer_footer', array( $this, 'debug_logger'));

            add_action('fppi_importer_exception', array( $this, 'error_logger'));
            
            add_action('fppi_importer_summary', array( $this, 'summary_logger'));
            //add_action('fppi_importer_post_log', array( 'WP_CLI', 'ol_logger'));
        }

        function post_logger($post, $message = null) {
            ob_start();
            assert(preg_match('/fppi_importer_(.+)/', current_filter(), $matches));
            $type = $matches[1];
            if (!array_key_exists($type, $this->post_importer_client->action_counts)) {
                $this->post_importer_client->action_counts[$type] = 0;
            }
            $post_id = $post['post_id'];
            $post_name = wp_strip_all_tags($post['post_data']['post_title']);
            $this->post_importer_client->action_counts[$type] += 1;
            $nice_typename = str_ireplace("_", " ", $type);
            $nice_typename = ucwords($nice_typename);
            printf("<li class='%s'>%s Post: %s%s</li>\n",
                esc_attr($type),
                esc_html($nice_typename),
                esc_html($post_id),
                $message ? ": $message" : " $post_name"
            );
            $contents = ob_get_clean();
            WP_CLI::line( strip_tags($contents) );
            $this->post_importer_client->update_log( $contents );
        }

        function summary_logger() {
            ob_start();
            $language_desc = !empty($this->post_importer_client->import_lang) ? sprintf("(%s):", strtoupper($this->post_importer_client->import_lang) ) : "";
            ?>
            <div id="summary">
                <p class="title"><?php printf('Import Summary for %s %s', $this->post_importer_client->import_post_type, $language_desc); ?></p>
                <?php if (!empty($this->post_importer_client->action_counts)) : ?>
                    <?php foreach ($this->post_importer_client->action_counts as $action => $count): ?>
                        <p><?php print esc_html("&#x2714; $action: $count") ?></p>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p class="title">No posts imported</p>
                <?php endif; ?>
            </div>
            <?php
            $contents = ob_get_clean();
            if (empty($this->post_importer_client->action_counts)) {
                WP_CLI::line("No posts imported.");
            } else {
                WP_CLI\Utils\format_items( 'table', array( $this->post_importer_client->action_counts ), array_keys( $this->post_importer_client->action_counts ) );
            }
            $this->post_importer_client->update_log( $contents );
        }

        function debug_logger( $message = '' ) {
            ob_start();
            printf("<p class='debug'>%s</p>\n", esc_html($message));
            $contents = ob_get_clean();
            WP_CLI::line( strip_tags($contents) );
            $this->post_importer_client->update_log( $contents );
        }

        function error_logger( $message = '' ) {
            ob_start();
            printf("<p class='error'>Error: %s</p>\n", esc_html($message));
            $contents = ob_get_clean();
            WP_CLI::warning( strip_tags($contents) );
            $this->post_importer_client->update_log( $contents );
        }

	}	
	WP_CLI::add_command( 'post-importer', 'FP_Post_Importer_CLI' );
}