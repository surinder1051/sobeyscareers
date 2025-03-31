<?php
namespace TRU\SOBYES_CAREERS\Classes;
use TRU\SOBYES_CAREERS\SOBYES_CAREERS;
print_r($sobeys_api_credentials);
class TRU_SOBYES_CAREERS_ADMIN{

    public $baseurl;
    private $username;
    private $password;

    public function __construct(){
        $this->username =  defined('API_USERNAME') ? API_USERNAME : '';
        $this->password = defined('API_PASSWORD') ? API_PASSWORD : '';
        $this->baseurl  = defined('API_BASEURL') ? API_BASEURL : '';
        add_action('init', [$this, 'Sobeys_Admin_Menu_Callback'], 10);
        add_action( 'wp_ajax_Sobeys_Api_Json_Filter', [$this, 'Sobeys_Api_Json_Filter'] );
    }

    /* Function to Make API Request with Basic Authentication */
    public function Sobeys_Api_Data_Request() {
        if (empty($this->username) || empty($this->password) || empty($this->baseurl)) {
            error_log('API credentials are missing.');
            return [];
        }
        $args = [
            'method'    => 'GET',
            'headers'   => [
                'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password),
                'Content-Type'  => 'application/json'
            ],
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

    public function Sobeys_Admin_Menu_Callback() {
        add_options_page(
            'Sobeys Careers',
            'Sobeys Careers',
            'manage_options',
            'Sobeys_Careers_Settings_Callback',
            [$this, 'Sobeys_Careers_Settings_Callback']
       );
    }

    public function Sobeys_Careers_Settings_Callback(){
        ?>
        <div class="wrap">
            <h1><?php _e('Jobs List Json Data'); ?></h1>  
            <div class="updated" style="display:none"><p><strong><?php _e('API Settings Json Data Updated', 'sobeys-careers'); ?></strong></p></div>
            <table class="form-table" role="presentation">
                <tbody>
                    <tr>
                        <td>
                            <p><?php _e('API Settings Data for Sobeys Carerrs.', 'sobeys-careers'); ?></p>
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

     /***** Json Result Functions *****/
     public function Sobeys_API_Data_Results(){   
        $Api_Data = $this->Sobeys_Api_Data_Request() ?? [];  
        $Api_filter_data = [];   
        $index = 0;        
        
        if (!empty($Api_Data)) {
            foreach ($Api_Data as $value) { 
                $Api_filter_data[] = [
                    'ID' => $value['jobReqId'], 
                    'location' => $value['location'] ?? '', 
                ];
                $index++;
            }
        }
    
        $Api_filter_data[] = ['total' => $index]; 
        return $Api_filter_data;
   }
    public function Sobeys_Api_Json_Filter(){
        $data = $this->Sobeys_API_Data_Results();
        $json_filename = TRU_PLUGIN_PATH . '/json/fetchData.json';
        if (!file_exists(dirname($json_filename))) {
            mkdir(dirname($json_filename), 0777, true);
        }
        file_put_contents($json_filename, json_encode($data));
        wp_send_json_success( array('result' => 'success', 'data' => $data) );
    }

}
new TRU_SOBYES_CAREERS_ADMIN();