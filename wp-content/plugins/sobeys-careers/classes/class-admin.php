<?php
namespace TRU\SOBYES_CAREERS\Classes;

use TRU\SOBYES_CAREERS\SOBYES_CAREERS;

class TRU_SOBYES_CAREERS_ADMIN {

    private $baseurl;
    private $username;
    private $password;

    public function __construct() {
        $this->username = defined('API_USERNAME') ? API_USERNAME : '';
        $this->password = defined('API_PASSWORD') ? API_PASSWORD : '';
        $this->baseurl  = defined('API_BASEURL') ? API_BASEURL : '';

        add_action('admin_menu', [$this, 'register_admin_menu']);
        add_action('wp_ajax_Sobeys_Api_Json_Filter', [$this, 'handle_api_json_filter']);
    }

    /** 
     * Functions to register admin menus 
     */
    public function register_admin_menu() {
        add_menu_page(
            'Sobeys Careers',
            'Sobeys Careers',
            'manage_options',
            'sobeys-careers',
            [$this, 'render_careers_settings_page']
        );

        add_submenu_page(
            'sobeys-careers',
            'Sobeys Slider Settings',
            'Sobeys Slider',
            'manage_options',
            'sobeys-slider-settings',
            [$this, 'render_slider_settings_page']
        );

        add_submenu_page(
            'sobeys-careers',
            'Sobeys Categories Settings',
            'Sobeys Categories',
            'manage_options',
            'sobeys-cats-settings',
            [$this, 'render_cats_settings_page']
        );
    }
    
    /** 
     * render settings api josn html
     */
    public function render_careers_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('Jobs List Json Data'); ?></h1>
            <div class="updated" style="display:none"><p><strong><?php _e('API Settings Json Data Updated', 'sobeys-careers'); ?></strong></p></div>
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <td>
                            <p><?php _e('API Settings Data for Sobeys Careers.', 'sobeys-careers'); ?></p>
                        </td>
                        <td>
                            <p class="submit">
                                <button class="button button-primary updateJsonData"><?php _e('Submit', 'sobeys-careers'); ?></button>
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php
    }
    /**
     * get lat & long through postal code
     */
    public function get_lat_long_google($postal_code, $country = 'CA') {
        $api_key = 'AIzaSyAguxNy-O2Rppn4myO-PTJHyyV-UHbCTXk';
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($postal_code . "," . $country) . "&key=" . $api_key;

        $response = file_get_contents($url);
        $json = json_decode($response, true);

        if ($json['status'] === 'OK') {
            return [
                'latitude'  => $json['results'][0]['geometry']['location']['lat'],
                'longitude' => $json['results'][0]['geometry']['location']['lng'],
            ];
        }

        return false;
    }
    /**
     * Handle all request of sobeys
     */
    public function fetch_api_data() {
        if (empty($this->username) || empty($this->password) || empty($this->baseurl)) {
            error_log('API credentials are missing.');
            return [];
        }

        $args = [
            'method'  => 'GET',
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password),
                'Content-Type'  => 'application/json'
            ]
        ];

        $response = wp_remote_request($this->baseurl, $args);

        if (is_wp_error($response)) {
            error_log('API Request Error: ' . $response->get_error_message());
            return [];
        }

        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (!isset($data['d']['results'])) {
            error_log('Invalid API Response: ' . $body);
            return [];
        }

        return $data['d']['results'];
    }
    /**
     * Create API Json Handler 
    */
    public function Sobeys_API_Data_Results() {
        $api_data = $this->fetch_api_data();
        $results = [];
    
        if (!empty($api_data)) {
            foreach ($api_data as $entry) {
                $postal_code = $entry['jobRequisition']['postalcode'] ?? '';
                $lat_long = $this->get_lat_long_google($postal_code);
                $date = $this->convertDateFormat($entry['jobRequisition']['createdDateTime']);
    
                $results[] = [
                    'ID'          => $entry['jobPostingId'] ?? '',
                    'ReqId'       => $entry['jobReqId'] ?? '',
                    'Language'    => $entry['jobRequisition']['defaultLanguage'] ?? '',
                    'City'        => $entry['jobRequisition']['city'] ?? '',
                    'State'       => $entry['jobRequisition']['stateProvince'] ?? '',
                    'Country'     => $entry['jobRequisition']['country'] ?? '',
                    'PostalCode'  => $postal_code,
                    'Latitude'    => $lat_long['latitude'] ?? '',
                    'Longitude'   => $lat_long['longitude'] ?? '',
                    'PostDate'    => $date,
                    'Category'    => $entry['jobRequisition']['filter2']['results'][0]['localeLabel'] ?? '',
                    'Banner'      => $entry['jobRequisition']['position_obj']['cust_bannerNav']['externalName_en_GB'] ?? '',
                    'Banner_FR'   => $entry['jobRequisition']['position_obj']['cust_bannerNav']['externalName_fr_CA'] ?? '',
                    'Travel'      => $entry['jobRequisition']['customTravel']['results'][0]['localeLabel'] ?? '',
                    'BusinessUnit'    => $entry['jobRequisition']['position_obj']['businessUnitNav']['name_en_GB'] ?? '',
                    'BusinessUnit_FR' => $entry['jobRequisition']['position_obj']['businessUnitNav']['name_fr_CA'] ?? '',
                    'JobType'    => $entry['jobRequisition']['position_obj']['cust_fulltimeparttimecontractNav']['label_en_GB'] ?? '',
                    'JobType_FR' => $entry['jobRequisition']['position_obj']['cust_fulltimeparttimecontractNav']['label_fr_CA'] ?? '',
                    'JobCategory'    => $entry['jobRequisition']['position_obj']['cust_OperationalDepartmentNav']['externalName_en_GB'] ?? '',
                    'JobCategory_FR' => $entry['jobRequisition']['position_obj']['cust_OperationalDepartmentNav']['externalName_fr_CA'] ?? '',
                    'Title'       => $entry['jobRequisition']['jobReqLocale']['results'][0]['externalTitle'] ?? '',
                    'Description' => $entry['jobRequisition']['jobReqLocale']['results'][0]['externalJobDescription'] ?? ''
                ];
            }
        }
    
        return $results;
    }
    // Date format function
    public function convertDateFormat($apiDate) {
        if (empty($apiDate)) {
            return null;
        }
        if (preg_match('/\/Date\((\d+)([+-]\d+)?\)\//', $apiDate, $matches)) {
            $timestampMs = $matches[1];
            $timestampSec = $timestampMs / 1000; 
            return date('Y-m-d', $timestampSec);
        }
        return null;
    }
    /**
     * API Json Ajax Function
    */
    public function handle_api_json_filter() {
        if(!check_ajax_referer('sobeys', 'nonce', false) ){
			wp_send_json_error( 'bad nonce ');
		}
        $data = $this->Sobeys_API_Data_Results();
        $json_path = TRU_PLUGIN_PATH . '/json/fetchData.json';

        if (!file_exists(dirname($json_path))) {
            mkdir(dirname($json_path), 0777, true);
        }

        file_put_contents($json_path, json_encode($data));
        wp_send_json_success(['result' => 'success', 'data' => $data]);
    }
    
    /** 
     * Render the Slider Settings Page 
     */
    public function render_slider_settings_page() {
        $show_updated_notice = false;
        $lang = 'en';
        if( function_exists('pll_current_language' )) {
            $lang = pll_current_language(); 
        }
        $field = 'sobeys_slider_entries';
        if ($lang === 'fr') {
            $field = $field . '_FR';
        }
        if (isset($_POST['sobeys_slider_nonce']) && wp_verify_nonce($_POST['sobeys_slider_nonce'], 'save_sobeys_slider')) {
            $entries = $this->process_slider_form();
            update_option($field, $entries);
            $show_updated_notice = true;
        }

        $saved_entries = get_option($field, []);
        ?>
        <div class="wrap">
            <h1><?php _e('Sobeys Category Slider Repeater', 'sobeys-careers'); ?></h1>
            <?php if ($show_updated_notice) : ?>
                <div class="notice notice-success is-dismissible">
                    <p><strong><?php _e('Settings saved successfully.', 'sobeys-careers'); ?></strong></p>
                </div>
            <?php endif; ?>
            <form method="post">
                <?php wp_nonce_field('save_sobeys_slider', 'sobeys_slider_nonce'); ?>
                <table class="form-table" id="slider-entries-table">
                    <?php
                        if (!empty($saved_entries)) {
                            foreach ($saved_entries as $entry) {
                                $this->render_slider_row($entry['title'], $entry['image'], $entry['link'], $entry['description']);
                            }
                        } else {
                            $this->render_slider_row();
                        }
                    ?>
                </table>
                <p>
                    <button type="button" class="button add-row"><?php _e('Add Row', 'sobeys-careers'); ?></button>
                    <input type="submit" class="button button-primary" value="<?php _e('Save Changes', 'sobeys-careers'); ?>" />
                </p>
            </form>
        </div>
        <?php
    }
    /**
     * slider repeater fields
     */
    private function render_slider_row($title = '', $image = '', $link ='', $description = '') {
        static $editor_id = 0;
        $editor_id++;
        ?>
        <tbody class="slider-row">
            <tr>
                <th scope="row">
                    <label for="slider_title_<?php echo $editor_id; ?>"><?php _e('Title', 'sobeys-careers'); ?></label>
                </th>
                <td>
                    <input type="text" name="slider_titles[]" id="slider_title_<?php echo $editor_id; ?>" value="<?php echo esc_attr($title); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="slider_description_<?php echo $editor_id; ?>"><?php _e('Description', 'sobeys-careers'); ?></label>
                </th>
                <td>
                    <?php
                    wp_editor($description, 'slider_description_' . $editor_id, [
                        'textarea_name' => 'slider_descriptions[]',
                        'textarea_rows' => 5,
                        'media_buttons' => false,
                        'teeny' => true,
                    ]);
                    ?>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="slider_link_<?php echo $editor_id; ?>"><?php _e('Link', 'sobeys-careers'); ?></label>
                </th>
                <td>
                    <input type="text" name="slider_link[]" id="slider_link_<?php echo $editor_id; ?>" value="<?php echo esc_attr($link); ?>" class="regular-text" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="slider_image_<?php echo $editor_id; ?>"><?php _e('Upload Image', 'sobeys-careers'); ?></label>
                </th>
                <td>
                    <input type="hidden" name="slider_images[]" id="slider_image_<?php echo $editor_id; ?>" class="regular-text slider-image-url" value="<?php echo esc_url($image); ?>" />
                    <button type="button" class="button upload-image-button"><?php _e('Upload', 'sobeys-careers'); ?></button>
                    <br><br>
                    <img src="<?php echo esc_url($image); ?>" class="slider-preview" style="width:100px;<?php echo $image ? 'display:block;' : 'display:none;'; ?>">
                    <br>
                    <button type="button" class="button remove-row"><?php _e('Remove Row', 'sobeys-careers'); ?></button>
                </td>
            </tr>
        </tbody>
        <?php
    }
    /**
     * Slider form submit values
     */
    private function process_slider_form() {
        $entries = [];

        if (!empty($_POST['slider_titles']) && is_array($_POST['slider_titles'])) {
            foreach ($_POST['slider_titles'] as $index => $title) {
                $entries[] = [
                    'title'       => sanitize_text_field($title),
                    'image'       => esc_url_raw($_POST['slider_images'][$index] ?? ''),
                    'description' => wp_kses_post($_POST['slider_descriptions'][$index] ?? ''),
                    'link'       => esc_url_raw($_POST['slider_link'][$index] ?? ''),
                ];
            }
        }
        return $entries;
    }
    /**
     * Category Json array functions
     */
    public function render_cats_settings_page() {
        $show_updated_notice = false;
    
        if (isset($_POST['sobeys_cats_nonce']) && wp_verify_nonce($_POST['sobeys_cats_nonce'], 'save_sobeys_cats')) {
            $raw_input = stripslashes($_POST['sobeys_categories_data']); 
            $decoded = json_decode($raw_input, true);
    
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                update_option('sobeys_categories_data', $raw_input); 
                $show_updated_notice = true;
            } else {
                $show_updated_notice = 'error';
            }
        }
    
        $value = get_option('sobeys_categories_data', '');
        ?>
        <div class="wrap">
            <h1><?php _e('Sobeys Categories Settings', 'sobeys-careers'); ?></h1>
    
            <?php if ($show_updated_notice === true) : ?>
                <div class="notice notice-success is-dismissible">
                    <p><strong><?php _e('Settings saved successfully.', 'sobeys-careers'); ?></strong></p>
                </div>
            <?php elseif ($show_updated_notice === 'error') : ?>
                <div class="notice notice-error is-dismissible">
                    <p><strong><?php _e('Invalid JSON. Please check your format.', 'sobeys-careers'); ?></strong></p>
                </div>
            <?php endif; ?>
    
            <form method="post">
                <?php wp_nonce_field('save_sobeys_cats', 'sobeys_cats_nonce'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php esc_html_e('Categories JSON', 'sobeys-careers'); ?></th>
                        <td>
                            <textarea name="sobeys_categories_data" rows="10" cols="60"><?php echo esc_textarea($value); ?></textarea>
                            <p class="description"><?php _e('Enter your category mapping in JSON format.', 'sobeys-careers'); ?></p>
                        </td>
                    </tr>
                </table>
                <input type="submit" class="button button-primary" value="<?php _e('Save Changes', 'sobeys-careers'); ?>" />
            </form>
        </div>
        <?php
    }
}

new TRU_SOBYES_CAREERS_ADMIN();
