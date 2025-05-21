<?php
if (!class_exists('Sobeys_WP_Filters')) {
    class Sobeys_WP_Filters {
        public $baseurl;
        public $base_api_endpoint;
        private $username = 'api_reporting@sobeysT1';
        private $password = 'FiGuVJ8FcAhXHiP'; 

        public function __construct() {
            add_action('wp_head', [$this, 'site_scripts']);
            $this->csa_api_endpoint = [
                'baseurl' => 'https://api4preview.sapsf.com/odata/v2/JobRequisition?$format=json&$top=30',
            ];
            add_action('init', [$this, 'Sobeys_Json_Update_Callback'], 10);
        }

        /* Site scripts */
        public function site_scripts() { ?>
            <script type="text/javascript">
                var AjaxUrl = "<?php echo admin_url('admin-ajax.php'); ?>";
            </script>
        <?php }

        /* Function to Make API Request with Basic Authentication */
        public function get_sobeys_api_data_request() {
            $args = [
                'method'    => 'GET',
                'headers'   => [
                    'Authorization' => 'Basic ' . base64_encode($this->username . ':' . $this->password),
                    'Content-Type'  => 'application/json'
                ],
            ];

            $response = wp_remote_request($this->csa_api_endpoint['baseurl'], $args);
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

        public function Sobeys_Json_Update_Callback() {
            add_options_page(
                'Jobs List',
                'Jobs List',
                'manage_options',
                'Custom_Job_List_Callback',
                [$this, 'Custom_Job_List_Callback']
           );
        }
        public function Custom_Job_List_Callback(){
            ?>
            <div class="wrap">
                <h1><?php _e('Jobs List Json Data'); ?></h1>  
                <div class="updated" style="display:none"><p><strong>Jobs List Json Data Updated</strong></p></div>
                <table class="form-table" role="presentation">
                    <tbody>
                        <tr>
                            <td>
                                <p>Job Searching Json API Data for Sobeys Carerrs.</p>
                            </td>
                            <td>
                                <p class="submit">
                                    <button class="button button-primary updateJsonData">Updates</button>
                                </p>  
                            </td>
                        </tr>
                    </tbody>
                </table>  
            </div>
        <?php
        }
        public function Sobeys_Jobs_api_Json_Data(){
            $data = $this->Sobeys_jobs_api_data() ?? [];
            //$json_data = $data['d'] ?? [];
            $json_filename = get_template_directory() . '/json/jobPosting.json';
            if (!file_exists(dirname($json_filename))) {
                mkdir(dirname($json_filename), 0777, true);
            }
            file_put_contents($json_filename, $data);               
        }
    }

    $sobeys_action = new Sobeys_WP_Filters();
}
