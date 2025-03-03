<?php

defined('ABSPATH') or die('Access forbidden!');

class FP_Post_Importer_Admin
{
    const PERMISSION_LEVEL      = 'manage_options';
    const SETTING_NAME_HOST     = 'fppi_settings_group_host';
    const SETTING_NAME_CLIENT   = 'fppi_settings_group_client';
    const SETTINGS_PAGE_SLUG    = 'fppi_admin';
    const SCHEDULED_HOOK        = 'fppi_schedule_import';

    /**
     * Setup action hooks/filters for admin.
     *
     * @return void
     */
    public function __construct()
    {
        add_action('admin_menu', array(__CLASS__, 'register_menu_page'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_enqueue_scripts'));
        add_action('admin_init', array($this, 'register_settings'));
        
        // if (empty($this->regions)) {
        // 	add_action('admin_notices', array(__CLASS__, 'add_admin_notice'));
        // 	return;
        // }
    }

    /**
     * Register our plugin settings.
     *
     * @return void
     */
    public function register_settings()
    { // whitelist options
        // Both
        register_setting(self::SETTING_NAME_HOST, 'fppi_type');
        register_setting(self::SETTING_NAME_CLIENT, 'fppi_type');
        register_setting(self::SETTING_NAME_HOST, 'fppi_host_token');
        register_setting(self::SETTING_NAME_CLIENT, 'fppi_host_token');
        
        // Host.
        register_setting(self::SETTING_NAME_HOST, 'fppi_host_exportable_cpt');
        // Client.
        register_setting(self::SETTING_NAME_CLIENT, 'fppi_client_importable_cpt');
        register_setting(self::SETTING_NAME_CLIENT, 'fppi_client_import_notification_email');
        register_setting(self::SETTING_NAME_CLIENT, 'fppi_client_importable_lang');
        register_setting(self::SETTING_NAME_CLIENT, 'fppi_client_importable_cpt_tags');
        register_setting(self::SETTING_NAME_CLIENT, 'fppi_client_import_cursor');
        register_setting(self::SETTING_NAME_CLIENT, 'fppi_client_include_filter_client');
        register_setting(self::SETTING_NAME_CLIENT, 'fppi_client_download_attachments');
        register_setting(self::SETTING_NAME_CLIENT, 'fppi_client_import_lock');
        register_setting(self::SETTING_NAME_CLIENT, 'fppi_client_append_terms');
        register_setting(self::SETTING_NAME_CLIENT, 'fppi_client_skip_unmodified');
        register_setting(self::SETTING_NAME_CLIENT, 'fppi_host_url');
        register_setting(self::SETTING_NAME_CLIENT, 'fppi_client_import_schedule', array('sanitize_callback' => array($this, 'set_import_schedule') ));
        register_setting(self::SETTING_NAME_CLIENT, 'fppi_client_manual_sync_type');
    }

    public function set_import_schedule($schedule)
    {
        // Disable
        if ($schedule == 'off') {
            as_unschedule_all_actions(self::SCHEDULED_HOOK.'-cron', array(), self::SCHEDULED_HOOK."-cron");
        }
        // Otherwise
        elseif (false === as_next_scheduled_action(self::SCHEDULED_HOOK.'-cron', array(), self::SCHEDULED_HOOK."-cron")) {
            $schedules = wp_get_schedules();
            $interval = $schedules[ $schedule ]['interval'];
            date_default_timezone_set(get_option('timezone_string'));
            as_schedule_recurring_action(strtotime('12pm'), $interval, self::SCHEDULED_HOOK.'-cron', array(), self::SCHEDULED_HOOK."-cron");
        }

        return $schedule;
    }

    /**
     * Enqueue and localize our javascripts.
     *
     * @param string $hook
     * @return void
     */
    public static function admin_enqueue_scripts($hook)
    {
        if ($hook != 'tools_page_'.self::SETTINGS_PAGE_SLUG) {
            return;
        }

        //wp_enqueue_style( 'custom_wp_admin_css', plugins_url('admin-style.css', __FILE__) );
        wp_register_script('fppi-adminjs', FP_POST_IMPORTER_ASSETS . '/js/admin.js', array('jquery','wp-api','wp-api-request'));
        $localized_vars = array(
            'defaults'   => array(
                
            ),
            'client'    => FP_Post_Importer_Client::get_localized_vars(),
            'host'    => FP_Post_Importer_Host::get_localized_vars(),
        );
        wp_localize_script('fppi-adminjs', 'fppi', $localized_vars);
        wp_enqueue_script('fppi-adminjs');
    }

    /**
     * Register a custom menu page.
     */
    public static function register_menu_page()
    {
        add_management_page(__('Post Importer', FP_POST_IMPORTER_LOCALE), __('Post Importer', FP_POST_IMPORTER_LOCALE), self::PERMISSION_LEVEL, self::SETTINGS_PAGE_SLUG, array(__CLASS__, 'menu_page_output'));
    }

    /**
     * Set plugin defaults, defaults to host if not yet set.
     *
     * @return void
     */
    public static function set_default_options()
    {
        $type = get_option('fppi_type');
        if (empty($type)) {
            add_option('fppi_type', 'host', false, 'no');
        }
    }

    /**
     * Getter/Setter methods for settings API.
     *
     * @param array $settings
     * @return void
     */
    public static function set_client_host_settings($settings = array())
    {
        update_option('fppi_client_host_settings', $settings, 'no');
    }
    public static function get_client_host_settings()
    {
        return get_option('fppi_client_host_settings', array());
    }
    public static function get_exportable_cpt()
    {
        return is_array($exportable_cpt = get_option('fppi_host_exportable_cpt', array())) ? $exportable_cpt : array();
    }
    public static function get_importable_cpt_tags()
    {
        return get_option('fppi_client_importable_cpt_tags', array());
    }
    public static function get_importable_cpt()
    {
        return is_array($importable_cpt = get_option('fppi_client_importable_cpt', array())) ? $importable_cpt : array();
    }
    public static function get_available_lang()
    {
        $available_lang = apply_filters('wpml_active_languages', array(), 'orderby=code&order=asc');
        if (empty($available_lang)) {
            $available_lang = [
                'en' => [
                    'language_code' => 'en'
                ]
            ];
        }
        return apply_filters('wpml_active_languages', $available_lang, 'orderby=code&order=asc');
    }
    public static function get_importable_lang()
    {
        return is_array($importable_lang = get_option('fppi_client_importable_lang', array())) ? $importable_lang : array();
    }
    public static function get_include_filter_method()
    {
        return get_option('fppi_client_include_filter_client', false);
    }
    public static function get_download_attachment_option()
    {
        return get_option('fppi_client_download_attachments', false);
    }
    public static function get_append_terms_option()
    {
        return get_option('fppi_client_append_terms', true);
    }
    public static function get_skip_unmodified_option()
    {
        return get_option('fppi_client_skip_unmodified', true);
    }
    public static function get_notification_email()
    {
        return get_option('fppi_client_import_notification_email', '');
    }
    public static function get_import_schedule()
    {
        return get_option('fppi_client_import_schedule', 'daily');
    }
    public static function get_plugin_type()
    {
        return get_option('fppi_type', 'host');
    }
    public static function get_host_url()
    {
        return get_option('fppi_host_url', '');
    }
    public static function get_host_token($generate = false)
    {
        $token = get_option('fppi_host_token', '');
        if (empty($token) && $generate) {
            $token = md5('fp-post-importer' . strtotime('now'));
        }
        return $token;
    }
    public static function set_client_import_cursor($cursor = '')
    {
        update_option('fppi_client_import_cursor', $cursor, 'no');
    }
    public static function get_client_import_cursor()
    {
        date_default_timezone_set(get_option('timezone_string'));
        // Default for get_option only works if option doesn't exist, since we registered the settings they already exist.
        $cursor = get_option('fppi_client_import_cursor');
        return !empty($cursor) ? $cursor : '2010-01-01T00:00:00';
    }
    public static function set_client_import_lock($lock = true)
    {
        if ($lock) {
            return update_option('fppi_client_import_lock', microtime(true), 'no');
        } else {
            return delete_option('fppi_client_import_lock');
        }
    }
    public static function get_client_import_lock()
    {	
		// if exists, will return microtime, false if not exists
        return get_option('fppi_client_import_lock');
    }
    
    /**
     * Call back for Admin menu page output.
     *
     * @return void
     */
    public static function menu_page_output()
    {
        ?>
        <style type="text/css">
            .default-hidden {
                display: none;
            }
			.fppi input[type="text"], .fppi input[type="password"], .fppi input[type="url"], .fppi input[type="date"], .fppi input[type="datetime-local"] 
			{ min-width: 400px; padding: 10px 10px; border-radius: 6px; }
            .fppi .generate-token-btn {
                vertical-align: baseline;
            }
            #triggered-import-results-log {
                width: 100%;
                overflow-y: scroll;
                height: 500px;
                border: 2px solid lightgray;
                background-color: #ececec;
                padding: 9px 5px 5px 12px;
                transition: background-color ease-in 400ms;
            }
            #triggered-import-results-log .skipped { color: gray; }
            #triggered-import-results-log .deleted { color: black; }
            #triggered-import-results-log .inserted { color: green; }
            #triggered-import-results-log .debug { color: black; }
            #triggered-import-results-log .adding_featured_image { color: #65091d; }
            #triggered-import-results-log .updated { color: blue; }
            #triggered-import-results-log .failed { color: red; font-weight: bold; font-size: 16px; line-height: 18px; }
            #triggered-import-results-log .error { color: red; font-weight: bold; font-size: 16px; line-height: 18px; }
            #triggered-import-results-log p, #triggered-import-results-log li, #triggered-import-results-log {
                margin: 0;
                margin-bottom: 0.1em;
                font-size: 14px;
                line-height: 16px;
                font-family: sans-serif;
                font-style: italic;
            }
            #triggered-import-results-log .line {
                display: block;
            }
            #triggered-import-results-log ol {
                margin: 0 0 10px 0;
                list-style-position: inside;

            }
            #triggered-import-results-log #summary {
                margin: 10px 0;
                font-size: 15px;
                color: black;
            }
            #triggered-import-results-log .title {
                font-weight: bold;
            }
            .fppi .pad-right {
                margin: 8px 10px 8px 0;
                display: inline-block;
                width: 110px;
            }
            .fppi .term-string {
                width: 210px;
                border: 1px solid #ddd;
                box-shadow: none;
                border-radius: 6px;
                padding: 8px 6px;
                outline: none;
            }
            #fppi-status-text {
                font-style: italic;
                font-size: 12px;
                margin: 10px 0 0 5px;
                color: #5d5d5d;
            }
            .fppi-progress-bar {
                display: none;
            }
            .noselect {
                -webkit-touch-callout: none; /* iOS Safari */
                    -webkit-user-select: none; /* Safari */
                    -khtml-user-select: none; /* Konqueror HTML */
                    -moz-user-select: none; /* Old versions of Firefox */
                        -ms-user-select: none; /* Internet Explorer/Edge */
                            user-select: none; /* Non-prefixed version, currently
                                                supported by Chrome, Opera and Firefox */
            }
            /* Bootstrap Progress Bar CSS */
            @-webkit-keyframes progress-bar-stripes{from{background-position:40px 0}to{background-position:0 0}}@-o-keyframes progress-bar-stripes{from{background-position:40px 0}to{background-position:0 0}}@keyframes progress-bar-stripes{from{background-position:40px 0}to{background-position:0 0}}.progress{height:20px;margin-bottom:20px;overflow:hidden;background-color:#f5f5f5;border-radius:4px;-webkit-box-shadow:inset 0 1px 2px rgba(0,0,0,.1);box-shadow:inset 0 1px 2px rgba(0,0,0,.1)}.progress-bar{float:left;width:0%;height:100%;font-size:12px;line-height:20px;color:#fff;text-align:center;background-color:#337ab7;-webkit-box-shadow:inset 0 -1px 0 rgba(0,0,0,.15);box-shadow:inset 0 -1px 0 rgba(0,0,0,.15);-webkit-transition:width .6s ease;-o-transition:width .6s ease;transition:width .6s ease}.progress-bar-striped,.progress-striped .progress-bar{background-image:-webkit-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:-o-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);-webkit-background-size:40px 40px;background-size:40px 40px}.progress-bar.active,.progress.active .progress-bar{-webkit-animation:progress-bar-stripes 2s linear infinite;-o-animation:progress-bar-stripes 2s linear infinite;animation:progress-bar-stripes 2s linear infinite}.progress-bar-success{background-color:#5cb85c}.progress-striped .progress-bar-success{background-image:-webkit-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:-o-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent)}.progress-bar-info{background-color:#5bc0de}.progress-striped .progress-bar-info{background-image:-webkit-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:-o-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent)}.progress-bar-warning{background-color:#f0ad4e}.progress-striped .progress-bar-warning{background-image:-webkit-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:-o-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent)}.progress-bar-danger{background-color:#d9534f}.progress-striped .progress-bar-danger{background-image:-webkit-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:-o-linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent);background-image:linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent)}
		</style>

		<div class="wrap fppi">
		<h1>Post Importer</h1>

		<form id="update-settings" method="POST" action="options.php" class="default-hiddens">
		    <table id="fppi-site-options" class="form-table">
				<tr valign="top">
		        <th scope="row">Site Setup</th>
		        <td>
		        	<input type="radio" class="wpas_type" name="fppi_type" value="host"<?php checked(self::get_plugin_type(), 'host'); ?>> Host</br>
					<input type="radio" class="wpas_type" name="fppi_type" value="client"<?php checked(self::get_plugin_type(), 'client'); ?>> Client</br>
		        </td>
                </tr>

				<?php $post_types = get_post_types(array('public' => true)); ?>

                <?php if (self::get_plugin_type() == 'host') : ?>
                    <?php settings_fields(self::SETTING_NAME_HOST); ?>
                    <tr valign="top" class="host-setting">
                    <th scope="row">Host URL</th>
                    <td><input type="text" name="fppi_api_key" value="<?php echo site_url(); ?>" disabled="disabled" /></td>
                    </tr>

                    <tr valign="top" class="host-setting client-setting">
                    <th scope="row">API Token</th>
                    <?php $host_url_token = self::get_host_token(); ?>
                    <td>
                        <input type="text" id="fppi_host_token" name="fppi_host_token" value="<?php echo $host_url_token; ?>" />
                        <?php if (empty($host_url_token)) : ?>
                        <button class="generate-token-btn button-secondary" type="button">Generate Token</button>
                        <?php $random_token = self::get_host_token(true); ?>
                        <input type="hidden" id="fppi_host_token_random" name="fppi_host_token_random" value="<?php echo $random_token; ?>" />
                    <?php endif; ?>
                    </td>
                    </tr>

					<tr valign="top" class="host-setting">
                    <th scope="row">Exportable Post Types</th>
                    <td>
                    <?php $exportable_cpt = self::get_exportable_cpt(); ?>
                    <?php $exportable_cpt = is_array($exportable_cpt) ? $exportable_cpt : array(); ?>
                    <?php foreach ($post_types as $post_type) : ?>
                    <?php $rest_url = FP_Post_Importer::get_post_type_rest_url($post_type); ?>
                    <?php printf('<input type="checkbox" name="fppi_host_exportable_cpt[]" value="%s"%s> <a href="%s" target="_blank">%s</a></br>', $post_type, checked(in_array($post_type, $exportable_cpt), true, false), $rest_url, $post_type); ?>
                    <?php endforeach; ?>
                    </td>
                    </tr>
                <?php endif; ?>
                
                <?php if (self::get_plugin_type() == 'client') : ?>
                    <?php settings_fields(self::SETTING_NAME_CLIENT); ?>
                    <tr valign="top" class="client-setting">
                    <th scope="row">Host URL</th>
                    <?php $host_url_endpoint = self::get_host_url(); ?>
                    <td><input type="url" id="fppi_host_url" name="fppi_host_url" placeholder="https://www.sobeys.com" value="<?php echo esc_attr($host_url_endpoint); ?>" /></td>
                    </tr>

                    <tr valign="top" class="client-setting">
                    <th scope="row">API Token</th>
                    <?php $host_url_token = self::get_host_token(); ?>
                    <td><input type="password" id="fppi_host_token" name="fppi_host_token" value="<?php echo esc_attr($host_url_token); ?>" /></td>
                    </tr>
                    
                    <?php $client_host_settings = self::get_client_host_settings(); ?>
                    <?php $show_client_settings_class = empty($client_host_settings) || (self::get_plugin_type() == 'host') ? ' default-hidden' : ''; ?>
                    <?php $show_client_settings_connection_status = empty($client_host_settings) || (self::get_plugin_type() == 'host') ? 'N/A' : 'Connected.'; ?>
                    <tr valign="top" class="client-setting">
                    <th scope="row">Status:  <span class="connection-status"><?php echo $show_client_settings_connection_status ?></span></th>
                    <td>
                        <button class="test-connection-btn button-secondary" type="button">Test Connection</button>
                        <p class="description">Test host connection and fetch settings.</p>
                    </td>
                    </tr>
                    
                    <?php if (!empty($client_host_settings)) : ?>
					<tr valign="top" class="client-setting">
                    <th scope="row">Importable Post Types</th>
                    <td>
                    <?php $importable_cpt = self::get_importable_cpt(); ?>
                    <?php $client_cpt = array_intersect($post_types, $client_host_settings['exportable_cpt']); ?>
                    <?php foreach ($client_cpt as $post_type) : ?>
                    <?php $rest_url = FP_Post_Importer::get_post_type_rest_url($post_type, $host_url_endpoint); ?>
                    <?php printf('<input type="checkbox" name="fppi_client_importable_cpt[]" value="%s"%s> <a href="%s" target="_blank">%s</a></br>', $post_type, checked(in_array($post_type, $importable_cpt), true, false), $rest_url, $post_type); ?>
                    <?php
                        $import_tags = self::get_importable_cpt_tags();
        				$include_tags = !empty($import_tags[ $post_type ]['include']) ? $import_tags[ $post_type ]['include'] : "";
        				$exclude_tags = !empty($import_tags[ $post_type ]['exclude']) ? $import_tags[ $post_type ]['exclude'] : ""; 
					?>
                    <?php printf('<label class="pad-right">Include Terms:</label><input class="term-string" type="input" width="50" name="fppi_client_importable_cpt_tags[%s][include]" value="%s"></br>', $post_type, $include_tags); ?>
                    <?php printf('<label class="pad-right">Exclude Terms:</label><input class="term-string" type="input" width="50" name="fppi_client_importable_cpt_tags[%s][exclude]" value="%s"></br>', $post_type, $exclude_tags); ?>
                    <?php endforeach; ?>
                    <p class="description">Only post types set as exportable on the host which also exist on this site will be displayed. To refresh this list, press `Test Connection` and refresh the page.</p>
                    <p class="description">Format for the include and exclude terms are as follows `taxonomy_name1=taxonomy_term1,taxonomy_name1=taxonomy_term1`... i.e. `recipe_tags=desserts`.</p>
                    </td>
                    </tr>

                    <?php $include_filter_clientside = self::get_include_filter_method(); ?>
                    <tr valign="top" class="hidden client-setting-on<?php echo $show_client_settings_class ?>">
                    <th scope="row">Include Tags Filter Method</th>
                    <td>
                    <?php printf('<input type="checkbox" name="fppi_client_include_filter_client" value="%s"%s> %s</br>', 'client', checked($include_filter_clientside, 'client', false), 'Client-Side'); ?>
                    <p class="description">Client side will import all posts and filter on the client. Host will apply the include tags to the REST URL and filter on the host.</p>
                    </td>
                    </tr>

                    <?php $should_download_attachments = self::get_download_attachment_option(); ?>
                    <tr valign="top" class="client-setting-on<?php echo $show_client_settings_class ?>">
                    <th scope="row">Download Attachments</th>
                    <td>
                    <?php printf('<input type="checkbox" name="fppi_client_download_attachments" value="%s"%s> %s</br>', 'true', checked($should_download_attachments, 'true', false), 'Enabled'); ?>
                    <p class="description">Download and link the featured image to this client site.</p>
                    </td>
                    </tr>

                    <?php $should_append_terms = self::get_append_terms_option(); ?>
                    <tr valign="top" class="client-setting-on<?php echo $show_client_settings_class ?>">
                    <th scope="row">Apppend Terms</th>
                    <td>
                    <?php printf('<input type="checkbox" name="fppi_client_append_terms" value="%s"%s> %s</br>', 'true', checked($should_append_terms, 'true', false), 'Enabled'); ?>
                    <p class="description">Default is to overwrite all terms, selecting this option will append the terms instead.</p>
                    </td>
                    </tr>

                    <?php $should_skip_unmodified = self::get_skip_unmodified_option(); ?>
                    <tr valign="top" class="client-setting-on<?php echo $show_client_settings_class ?>">
                    <th scope="row">Skip Existing Non-Modified Posts</th>
                    <td>
                    <?php printf('<input type="checkbox" name="fppi_client_skip_unmodified" value="%s"%s> %s</br>', 'true', checked($should_skip_unmodified, 'true', false), 'Enabled'); ?>
                    <p class="description">Default will re-import posts if the cursor is set before the post modified date. If this is checked, the importer will skip re-importing an existing post with a unchaned modified post date.</p>
                    </td>
                    </tr>

                    <?php $notification_email = self::get_notification_email(); ?>
                    <tr valign="top" class="client-setting-on<?php echo $show_client_settings_class ?>">
                    <th scope="row">Notification Email</th>
                    <td>
                    <?php printf('<input type="text" name="fppi_client_import_notification_email" value="%s"></br>', $notification_email); ?>
                    <p class="description">To be notified when a import is started.</p>
                    </td>
                    </tr>

                    <?php $importable_lang = self::get_importable_lang(); ?>
                    <?php $available_lang = self::get_available_lang(); ?>
                    <?php
                    $client_lang = array_intersect(array_keys($client_host_settings['languages']), array_keys($available_lang) ); ?>
                    <tr valign="top" class="client-setting-on<?php echo $show_client_settings_class ?>">
                    <th scope="row">Importable Languages</th>
                    <td>
                    <?php if (!empty($client_lang)) : ?>    
                        <?php foreach ($client_lang as $lang_id) : ?>
                        <?php printf('<input type="checkbox" name="fppi_client_importable_lang[]" value="%s"%s> %s</br>', $lang_id, checked(in_array($lang_id, $importable_lang), true, false), strtoupper($lang_id)); ?>
                        <?php endforeach; ?>
                    <?php else : ?>
                        Not Supported.
                    <?php endif; ?>
                    </td>
                    </tr>

                    <tr valign="top" class="client-setting">
                    <th scope="row">Import Schedule</th>
                    <td>
                    <?php
                   		$schedules = wp_get_schedules();
        				$saved_schedule = self::get_import_schedule();
        				$schedules['off'] = array('display' => 'Off');
       					$schedules = array_reverse($schedules); 
					?>
                    <select id="fppi_import_schedule" name="fppi_client_import_schedule">
					<?php foreach ($schedules as $schedule => $schedule_info): ?>
						<option value="<?php echo esc_attr($schedule) ?>" <?php selected($schedule, $saved_schedule) ?>>
							<?php echo esc_html($schedule_info['display']) ?>
						</option>
					<?php endforeach; ?>
				    </select>
                    <p class="description">Sets the import schedule to run in the background.</p>
                    </td>
                    </tr>

                    <tr valign="top" class="client-setting">
                    <th scope="row">Last modified import date</th>
                    <td>
                    <input type="datetime-local" value="<?php echo self::get_client_import_cursor() ?>" class="fppi_start_date" name="fppi_client_import_cursor">
                    <p class="description">Sets the date the import fetches posts from for the cron job.</p>
                    </td>
                    </tr>	
                    
                    <?php endif; ?>
                
                <?php endif; ?>
                </td>
                </tr>
                
		       
		    </table>
            <?php submit_button(); ?>
        </form>
       
		<!-- Client Site Only: Manual Sync Options -->
        <?php if (self::get_plugin_type() == 'client') : ?>
		<div class="fppi-client client-setting-on<?php echo $show_client_settings_class ?>">
			<hr>
            <h2>Client Manual Sync</h2>
			<form name="get_posts_options" id="get_posts_options">
                <table id="fppi-ping" class="form-table">

				<tr valign="top">
                    <th scope="row">Manual Sync Type</th>
                    <td>
						<select name="manual_sync_type" id="client-manual-sync-type">
							<option value="date" selected>By Date</option>
							<option value="post_id">By Post ID</option>
						</select>
                    </td>
                    </tr>

                    <tr valign="top" id="client-modified-date" style="display:none">
                    <th scope="row">Last modified import date</th>
                    <td>
                        <input type="datetime-local" value="<?php echo self::get_client_import_cursor(); ?>" step="1" id="fppi_start_date" class="fppi_start_date" name="fppi_client_import_cursor">
                    </td>
                    </tr>

                    <tr valign="top" class="hidden">
                    <th scope="row">Number of posts</th>
                    <td>
                        <input type="number" name="per_page" max="100" min="0" value="10" id="fppi_post_count">
                    </td>
                    </tr>

                    <tr valign="top" id="client-import-by-post-ids" style="display:none">
                    <th scope="row">Import by Post IDs</th>
                    <td>
                        <input type="text" name="post_ids" value="" id="fppi_post_ids">
                        <p class="description">Comma separated (i.e. 1,2,3)</p>
                    </td>
                    </tr>

                    <tr valign="top" class="client-setting">
						<th scope="row">Importable Post Types</th>
						<td>
							<?php foreach ( $client_cpt as $post_type ) : ?>
								<?php $rest_url = FP_Post_Importer::get_post_type_rest_url($post_type, $host_url_endpoint); ?>
								<?php printf('<p><input type="checkbox" name="fppi_client_manual_cpt[]" value="%s"> <a href="%s" target="_blank">%s</a></p>', $post_type, $rest_url, $post_type); ?>
							<?php endforeach; ?>
							<p class="description">*Only post types set as exportable on the host which also exist on this site will be displayed.</p>
						</td>
                    </tr>

                    <tr valign="top">
                    <th scope="row">Download Attachments</th>
                    <td>
                        <input type="checkbox" name="download_attachments" id="download_attachments" value="true" <?php checked(true, true) ?>>
                    </td>
                    </tr>

                    <tr valign="top">
                    <th scope="row">Disable Log Tail</th>
                    <td>
                        <input type="checkbox" id="disable_log_tail" value="false">
                    </td>
                    </tr>

                    <tr valign="top" class="hidden">
                    <th scope="row">Page Number</th>
                    <td>
                        <input type="number" min="1" step="1" max="999" name="start_page" id="start_page" value="1">
                    </td>
                    </tr>

                    <tr valign="top">
                    <th scope="row">
                        <button class="trigger-brightcove-import-btn button-primary" data-default-text="<?php _e('Run Import Now', 'fppi') ?>" type="button"><?php _e('Run Import Now', 'fppi') ?></button>
                        <div id="fppi-status-text" class="noselect">Ready...</div>
                    </th>
                    <td>
                        <?php $lock = FP_Post_Importer_Admin::get_client_import_lock(); if ( !empty($lock) ) : ?>
                            <button class="trigger-brightcove-clear-process-btn button-small" type="button"><?php _e('Clear process lock', 'fppi') ?></button>
                        <?php endif; ?>
                    </td>
                    </tr>
                </table>
			</form>

		    <div class="wrap fppi-importer">
                <div class="progress fppi-progress-bar">
                    <div class="progress-bar progress-bar-success progress-bar-striped active" role="progressbar"><span class="progress-value">0</span>% Complete</div>
                </div>
                <div id="triggered-import-results-log">
                    <?php echo FP_Post_Importer_Client::fetch_log(); ?>
                </div>
			</div> <!-- end wrap iframe -->
		</div> <!-- end client-setting -->
        <?php endif; ?>
		<br /><br />
        <hr>
		<p><?php echo 'Post Importer v' . FP_POST_IMPORTER_PLUGIN_VERSION; ?></p>
        </div>
		<?php
    }
}
