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
        register_activation_hook(__FILE__, array($this, 'activate'));
        add_action('admin_menu', [$this, 'Sobeys_Admin_Cookie_Callback'], 10);
        add_action('admin_init', [$this, 'register_cookie_settings']);

        add_shortcode('sobeys_cookie_shortcode', array($this, 'render_cookie_banner'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    public function activate() {
        add_option('sobeys_plugin_activated', true);
    }
    public function Sobeys_Admin_Cookie_Callback(){
        add_menu_page(
            'Careers Cookie',
            'Careers Cookie',
            'manage_options',
            'Sobeys_Careers_Cookie_Settings_Callback',
            [$this, 'Sobeys_Careers_Cookie_Settings_Callback']
       );
       }

       public function register_cookie_settings() {
        register_setting('sobeys_cookie_settings_group', 'sobeys_cookie_policy_description');
        register_setting('sobeys_cookie_settings_group', 'required_cookies_description');
        register_setting('sobeys_cookie_settings_group', 'cookie_show_more_detail');
        register_setting('sobeys_cookie_settings_group', 'functional_cookies_description');
        register_setting('sobeys_cookie_settings_group', 'cookie_consent_manager_heading');
        register_setting('sobeys_cookie_settings_group', 'cookie_consent_manager_description');
        register_setting('sobeys_cookie_settings_group', 'sobeys_consent_required_cookie');
        register_setting('sobeys_cookie_settings_group', 'sobeys_consent_functional_cookie');
        register_setting('sobeys_cookie_settings_group', 'sobeys_modify_cookie_btn');
        register_setting('sobeys_cookie_settings_group', 'sobeys_confirm_cookie_btn');
        register_setting('sobeys_cookie_settings_group', 'sobeys_accept_all_btn');
        register_setting('sobeys_cookie_settings_group', 'sobeys_reject_all_btn');
        register_setting('sobeys_cookie_settings_group', 'cookie_vimeo_description');
        register_setting('sobeys_cookie_settings_group', 'cookie_description_text');
        register_setting('sobeys_cookie_settings_group', 'cookie_enabled_text');
        register_setting('sobeys_cookie_settings_group', 'cookie_provider_text');
        register_setting('sobeys_cookie_settings_group', 'cookie_sap_text');
        register_setting('sobeys_cookie_settings_group', 'function_provier_vimeo');
        register_setting('sobeys_cookie_settings_group', 'function_provier_youtube');
        register_setting('sobeys_cookie_settings_group', 'required_provider_description');
        register_setting('sobeys_cookie_settings_group', 'cookie_policy_text');
        register_setting('sobeys_cookie_settings_group', 'privacy_policy_text');
        register_setting('sobeys_cookie_settings_group', 'term_condition_text');
        register_setting('sobeys_cookie_settings_group', 'cookie_youtube_description');
    }
       public function Sobeys_Careers_Cookie_Settings_Callback() {
        ?>
        <div class="wrap">
            <h1>Cookie Management</h1>
    
            <form method="post" action="options.php">
                <?php
                settings_fields('sobeys_cookie_settings_group');
                do_settings_sections('sobeys_cookie_settings_group');
    
                // Get saved values
                $consent_heading   = get_option('cookie_consent_manager_heading', '');
                $show_more_detail   = get_option('cookie_show_more_detail', '');
                $consent_description  = get_option('cookie_consent_manager_description', '');
                $cookie_description   = get_option('sobeys_cookie_policy_description', '');
                $required_cookie   = get_option('sobeys_consent_required_cookie', '');
                $functional_cookie   = get_option('sobeys_consent_functional_cookie', '');
                $vimeo_description   = get_option('cookie_vimeo_description', '');
                $enabled_text   = get_option('cookie_enabled_text', '');
                $description_text   = get_option('cookie_description_text', '');
                $provider_text   = get_option('cookie_provider_text', '');
                $sap_text   = get_option('cookie_sap_text', '');
                $required_provider_description   = get_option('required_provider_description', '');
                $vimeo_text   = get_option('function_provier_vimeo', '');
                $youtube_text   = get_option('function_provier_youtube', '');
                $required_cookie_description   = get_option('required_cookies_description', '');
                $functional_cookie_description   = get_option('functional_cookies_description', '');
                $modify_btn_label     = get_option('sobeys_modify_cookie_btn', 'Modify Cookie Preferences');
                $accept_btn_label     = get_option('sobeys_accept_all_btn', 'Accept All');
                $confirm_btn_label     = get_option('sobeys_confirm_cookie_btn', 'Confirm My Choices');
                $reject_btn_label     = get_option('sobeys_reject_all_btn', 'Reject All');
                $youtube_description     = get_option('cookie_youtube_description', '');
                $cookie_policy_text     = get_option('cookie_policy_text', '');
                $privacy_policy_text     = get_option('privacy_policy_text', '');
                $term_condition_text     = get_option('term_condition_text', '');
                ?>
    
                <table class="form-table">
            
                    <tr>
                        <th scope="row">
                            <label for="cookie_sap_text">SAP Text</label>
                        </th>
                        <td>
                            <input type="text" name="cookie_sap_text" id="cookie_sap_text" value="<?php echo esc_attr($sap_text); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="cookie_consent_manager_heading">Cookie Consent Manager</label>
                        </th>
                        <td>
                            <input type="text" name="cookie_consent_manager_heading" id="cookie_consent_manager_heading" value="<?php echo esc_attr($consent_heading); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="cookie_policy_text">Cookie Policy Text</label>
                        </th>
                        <td>
                            <input type="text" name="cookie_policy_text" id="cookie_policy_text" value="<?php echo esc_attr($cookie_policy_text); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="privacy_policy_text">Privacy Policy Text</label>
                        </th>
                        <td>
                            <input type="text" name="privacy_policy_text" id="privacy_policy_text" value="<?php echo esc_attr($privacy_policy_text); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="term_condition_text">Term Condition Text</label>
                        </th>
                        <td>
                            <input type="text" name="term_condition_text" id="term_condition_text" value="<?php echo esc_attr($term_condition_text); ?>" class="regular-text">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="cookie_show_more_detail">Show More Details</label>
                        </th>
                        <td>
                            <input type="text" name="cookie_show_more_detail" id="cookie_show_more_detail" value="<?php echo esc_attr($show_more_detail); ?>" class="regular-text">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="cookie_provider_text">Provider Text</label>
                        </th>
                        <td>
                            <input type="text" name="cookie_provider_text" id="cookie_provider_text" value="<?php echo esc_attr($provider_text); ?>" class="regular-text">
                        </td>
                    </tr>
                
                    <tr>
                        <th scope="row">
                            <label for="cookie_enabled_text">Enabled Text</label>
                        </th>
                        <td>
                            <input type="text" name="cookie_enabled_text" id="cookie_enabled_text" value="<?php echo esc_attr($enabled_text); ?>" class="regular-text">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="cookie_consent_manager_description">Cookie Consent Description</label>
                        </th>
                        <td>
                            <textarea name="cookie_consent_manager_description" id="cookie_consent_manager_description" rows="5" cols="60"><?php echo esc_textarea($consent_description); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="required_provider_description">Required Provider Description</label>
                        </th>
                        <td>
                            <textarea name="required_provider_description" id="required_provider_description" rows="5" cols="60"><?php echo esc_textarea($required_provider_description); ?></textarea>
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="cookie_description_text">Description Text</label>
                        </th>
                        <td>
                            <input type="text" name="cookie_description_text" id="cookie_description_text" value="<?php echo esc_attr($provider_text); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="function_provier_vimeo">Vimeo Text</label>
                        </th>
                        <td>
                            <input type="text" name="function_provier_vimeo" id="function_provier_vimeo" value="<?php echo esc_attr($vimeo_text); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="function_provier_youtube">Youtube Text</label>
                        </th>
                        <td>
                            <input type="text" name="function_provier_youtube" id="function_provier_youtube" value="<?php echo esc_attr($youtube_text); ?>" class="regular-text">
                        </td>
                    </tr>

                    <tr>
                        <th scope="row">
                            <label for="cookie_vimeo_description">Vimeo Description</label>
                        </th>
                        <td>
                            <textarea name="cookie_vimeo_description" id="cookie_vimeo_description" rows="5" cols="60"><?php echo esc_textarea($vimeo_description); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="cookie_youtube_description">Youtube Description</label>
                        </th>
                        <td>
                            <textarea name="cookie_youtube_description" id="cookie_youtube_description" rows="5" cols="60"><?php echo esc_textarea($youtube_description); ?></textarea>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="sobeys_cookie_policy_description">Cookie Policy Description</label>
                        </th>
                        <td>
                            <textarea name="sobeys_cookie_policy_description" id="sobeys_cookie_policy_description" rows="5" cols="60"><?php echo esc_textarea($cookie_description); ?></textarea>
                        </td>
                    </tr>
    
                    <tr>
                        <th scope="row">
                            <label for="sobeys_consent_required_cookie">Required Cookies </label>
                        </th>
                        <td>
                            <input type="text" name="sobeys_consent_required_cookie" id="sobeys_consent_required_cookie" value="<?php echo esc_attr($required_cookie); ?>" class="regular-text">
                        </td>
                    </tr>
    
                    <tr>
                        <th scope="row">
                            <label for="required_cookies_description">Required Cookie Description</label>
                        </th>
                        <td>
                            <textarea name="required_cookies_description" id="required_cookies_description" rows="5" cols="60"><?php echo esc_textarea($required_cookie_description); ?></textarea>
                        </td>
                    </tr>
    
                    <tr>
                        <th scope="row">
                            <label for="sobeys_consent_functional_cookie">Functional Cookies </label>
                        </th>
                        <td>
                            <input type="text" name="sobeys_consent_functional_cookie" id="sobeys_consent_functional_cookie" value="<?php echo esc_attr($functional_cookie); ?>" class="regular-text">
                        </td>
                    </tr>
    
                    <tr>
                        <th scope="row">
                            <label for="functional_cookies_description">Required Cookie Description</label>
                        </th>
                        <td>
                            <textarea name="functional_cookies_description" id="functional_cookies_description" rows="5" cols="60"><?php echo esc_textarea($functional_cookie_description); ?></textarea>
                        </td>
                    </tr>
    
                    <tr>
                        <th scope="row">
                            <label for="sobeys_modify_cookie_btn">Modify Cookie Preferences Button Label</label>
                        </th>
                        <td>
                            <input type="text" name="sobeys_modify_cookie_btn" id="sobeys_modify_cookie_btn" value="<?php echo esc_attr($modify_btn_label); ?>" class="regular-text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="sobeys_confirm_cookie_btn">Confirm My Choices</label>
                        </th>
                        <td>
                            <input type="text" name="sobeys_confirm_cookie_btn" id="sobeys_confirm_cookie_btn" value="<?php echo esc_attr($confirm_btn_label); ?>" class="regular-text">
                        </td>
                    </tr>
    
                    <tr>
                        <th scope="row">
                            <label for="sobeys_accept_all_btn">Accept All Button Label</label>
                        </th>
                        <td>
                            <input type="text" name="sobeys_accept_all_btn" id="sobeys_accept_all_btn" value="<?php echo esc_attr($accept_btn_label); ?>" class="regular-text">
                        </td>
                    </tr>
    
                    <tr>
                        <th scope="row">
                            <label for="sobeys_reject_all_btn">Reject All Button Label</label>
                        </th>
                        <td>
                            <input type="text" name="sobeys_reject_all_btn" id="sobeys_reject_all_btn" value="<?php echo esc_attr($reject_btn_label); ?>" class="regular-text">
                        </td>
                    </tr>
                </table>
    
                <?php submit_button('Save Cookie Settings'); ?>
            </form>
  
        </div>
        <?php
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
    