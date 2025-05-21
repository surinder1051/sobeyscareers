<?php
namespace TRU\SOBYES_CAREERS\Classes;
use TRU\SOBYES_CAREERS\SOBYES_CAREERS;
class TRU_SOBYES_CAREERS_FRONTEND{
    public $per_page;
    public $tru_ajax;
    public function __construct(){
        $this->per_page =  defined('PER_PAGE') ? PER_PAGE : 30;
        add_shortcode('Sobeys-Search', [$this, 'sobeys_search_form']);
        add_shortcode('Sobeys-Filter', [$this, 'sobeys_filter_form']);
        add_shortcode('Sobeys-Category', [$this, 'sobeys_category_slider']);
        add_action('wp_footer', [ $this, 'tmpl_ajax_json_data'], 10);
        add_action('wp_footer', [ $this, 'tmpl_job_ajax_json_data'], 10);

        $this->tru_ajax = array(
            'Sobeys_Search_Filter',
            'Sobeys_Apply_Filter',
            'Sobeys_Job_Details'
        );
        foreach ($this->tru_ajax as $ajax) {
            add_action("wp_ajax_" . $ajax, array($this, $ajax));
            add_action("wp_ajax_nopriv_" . $ajax, array($this, $ajax));
        }
    }
    /** 
     * Get the json data 
    */
    public function Sobeys_API_Json_Data(){
        $Api_data = [];
        $directory = TRU_PLUGIN_PATH . '/json/fetchData.json';               
        if( file_exists($directory) ){
            $Api_data_Json = file_get_contents($directory);
            $Api_data = json_decode($Api_data_Json, true);
        }
        return $Api_data;
    }

    /** 
     * Display the search form. 
    */
    public function sobeys_search_form(){
        ob_start();
        $file_path = TRU_PLUGIN_PATH . '/view/sobeys-search-form.php';
        if (file_exists($file_path)) {
            include $file_path;
        } else {
            return '<p>Error: Search form not found.</p>';
        }
        return ob_get_clean();
    }
    /** 
     * Display the filter form. 
     */
    public function sobeys_filter_form(){
        ob_start();
        $file_path = TRU_PLUGIN_PATH . '/view/sobeys-filter-form.php';
        if (file_exists($file_path)) {
            include $file_path;
        } else {
            return '<p>Error: Search form not found.</p>';
        }
        return ob_get_clean();
    }
    /** 
     * Display the category slider
     */
    public function sobeys_category_slider(){
        ob_start();
        $file_path = TRU_PLUGIN_PATH . '/view/sobeys-category.php';
        if (file_exists($file_path)) {
            include $file_path;
        } else {
            return '<p>Error: Category slider not found.</p>';
        }
        return ob_get_clean();
    }
    /**
     * Get Json File Data Function 
     */
    public static function Get_Sobeys_API_Json_Data($slug){
        $Api_data = [];
        $directory = TRU_PLUGIN_PATH . '/json/fetchData.json';               
        if( file_exists($directory) ){
             $Api_data_Json = file_get_contents($directory);
             $Api_data = json_decode($Api_data_Json, true);
        }

        $json = get_option('sobeys_categories_data', '{}');
        $data = json_decode($json, true);
        if (isset($data[$slug])) {
            $allowed_categories = $data[$slug];
            $filtered_data = array_filter($Api_data, function($job) use ($allowed_categories) {
                return in_array(trim($job['Category']), $allowed_categories);
            });
        } else {
            $filtered_data = $Api_data;
        }
        return $filtered_data;
    }
    /**
     * Get Showing results count label
    */
    public function Api_Data_Result_Count( $total, $current = 1  ) {
		$per_page = $this->per_page;
		$from = ( $per_page * $current ) - ( $per_page - 1 );
		if(( $per_page * $current ) <= $total ){
			$to = ( $per_page * $current );
		} else {
			$to = $total;
		}
		if ($total > 0) { 
            return '<div class="result-counter" role="status">
                <span class="total-item-count" tabindex="0">' . __('Results', '') . ' ' . $from . ' - ' . $to . ' ' . __('of', '') . ' ' . $total . '</span>
            </div>';
        }
	}
    /**
     * Get Showing filter pagination 
     */
	public static function Sobeys_Filter_Pagination( $args = array() ){
		$data = '';
		$current = $args['current']; 
		$total_pages = $args['total'];
		$mid_size = $args['mid_size'] ?? 3;
		$rmid_size = $args['rmid_size'] ?? 2;
		if($current > 1){  
			$data .= '<li class="prev"><a class="prev page-number" href="#" data-number="'.(  $current - 1).'"><img src="'.TRU_PLUGIN_URL.'/assets/image/pagination-left-arrow.svg"></a></li>';
        }
		$links =  ( $current > $mid_size ) ? $current - $rmid_size : 1; 
		if($current >= $mid_size+1) {
			$data .= '<li><a class="next page-number" href="#" data-number="1">1</a></li>'; 
		} if ($links != 1 && $current > $mid_size+1) {                        
			$data .= '<li><a>…</a></li>';
		}
		for ($index = $links; $index <= $current + $rmid_size && $index <= $total_pages; $index++) { 
		    if ( $index == $current ) {
				$data .= '<li class="current"><span aria-current="page" class="page-numbers">'.$index.'</span></li>';
		    } else { 
				$data .= '<li><a class="next page-number" href="#" data-number="'.$index.'">'.$index.'</a></li>';
		    }                
		}
		if($current + $mid_size < $total_pages){
			$data .= '<li><a>…</a></li>'; 
		}
		if($current + $rmid_size < $total_pages){ 
			$data .= '<li><a class="next page-number" href="#" data-number="'.$total_pages.'">'.$total_pages.'</a></li>';
		}
		if($current < $total_pages){            
			$data .= '<li class="next"><a class="next page-number" href="#" data-number="'.($current + 1).'"><img src="'.TRU_PLUGIN_URL.'/assets/image/pagination-right-arrow.svg"></a></li>';
		} 
		return $data;
	}

    /** 
     * Get distance between two location function 
     */
    public function earthRadiusDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; 
    
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);
    
        $dLat = $lat2 - $lat1;
        $dLon = $lon2 - $lon1;
    
        $a = sin($dLat / 2) * sin($dLat / 2) +
                cos($lat1) * cos($lat2) *
                sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    
        return $earthRadius * $c; 
    }
    /*
     *get any json entry unique list
    */
    public function get_Unique_Entry_List( $entry, $code ){
        $Api_data = [];
        $directory = TRU_PLUGIN_PATH . '/json/fetchData.json';               
        if( file_exists($directory) ){
             $Api_data_Json = file_get_contents($directory);
             $Api_data = json_decode($Api_data_Json, true);
        }
        $field_name = $entry;
        if ($code === 'fr') {
            $field_name = $entry . '_FR';
        }
        $jobsData = array_filter(array_column($Api_data, $field_name), function($val) {
            return !empty($val);
        });
        $uniqueData = array_unique($jobsData);
        return $uniqueData;
    }
    /** 
     * get current ip address function
      */
    public function get_current_user_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
    /*
    * get current ip long & lat function 
    */
    public function get_user_coordinates_from_ip($ip) {
        $default = ['lat' => '', 'lon' => '']; 
        $response = @file_get_contents("http://ip-api.com/json/{$ip}");
        if ($response) {
            $geo = json_decode($response);
            if ($geo && $geo->status === 'success') {
                return ['lat' => $geo->lat, 'lon' => $geo->lon];
            }
        }
        return $default;
    }
    /*
     *Filter form ajax function
    */
    public function Sobeys_Apply_Filter(){
        $per_page = $this->per_page;
        $slug = sanitize_text_field($_POST['page_slug']);
        $data = $this->Get_Sobeys_API_Json_Data($slug);
        $current = sanitize_text_field($_POST['current']);
        $distance = sanitize_text_field($_POST['distance']);
        $title = sanitize_text_field($_POST['title']);
        $ip = $this->get_current_user_ip();
        $location = sanitize_text_field($_POST['location']);
        $banner = sanitize_text_field($_POST['banner']);
        $businessUnit = sanitize_text_field($_POST['businessUnit']);
        $jobType = sanitize_text_field($_POST['jobType']);
        $userLat = '';
        $userLon = '';
        $geo = @json_decode(file_get_contents("http://ip-api.com/json/{$ip}"));
        if ($geo && $geo->status === 'success') {
            $userLat = $geo->lat;
            $userLon = $geo->lon;
        }
        $daysMap = [
            '7 Days ago' => 7,
            '15 Days ago' => 15,
            '30 Days ago' => 30,
        ];
        $languageList = [
            'English' => 'en_GB',
            'French' => 'fr_CA',
        ];
        $datePosted = $daysMap[$_POST['datePosted']] ?? '';
        $lang = $languageList[$_POST['langValue']] ?? '';
        $language = $_POST['language'];
        $filter_data = [];
        if(!check_ajax_referer('sobeys', 'nonce', false) ){
			wp_send_json_error( 'bad nonce ');
		}
        $filter_data = !empty($title) 
        ? array_filter($data, function ($val) use ($title) {					
            return preg_match('/' . preg_quote($title, "/") . '/i', $val['Title']);
        }) 
        : $data;
        if (!empty($datePosted)) {
            $cutoffTimestamp = strtotime("-$datePosted days");
            $todayTimestamp = strtotime(date('Y-m-d'));
            $filter_data = array_filter($filter_data, function($val) use ($cutoffTimestamp, $todayTimestamp) {
                if (empty($val['PostDate'])) return false;
        
                $jobTimestamp = strtotime($val['PostDate']);
                return $jobTimestamp >= $cutoffTimestamp && $jobTimestamp <= $todayTimestamp;
            });
        }
        if (!empty($distance)) {
            $filter_data = array_filter($filter_data, function($val) use ($distance, $userLat, $userLon) {
                if (!isset($val['Latitude'], $val['Longitude'], $userLat, $userLon )) {
                    return false;
                }
                $lat = floatval($val['Latitude']);
                $lon = floatval($val['Longitude']);
                $jobDistance = $this->earthRadiusDistance($userLat, $userLon, $lat, $lon);
                return $jobDistance <= $distance;
            });
        }
        if (!empty($location)) {
            $filter_data = array_filter($filter_data, function ($val) use ($location) {
                return (isset($val['City']) && $val['City'] === $location) ||
                    (isset($val['State']) && $val['State'] === $location) ||
                    (isset($val['Country']) && $val['Country'] === $location) ||
                    (isset($val['PostalCode']) && $val['PostalCode'] === $location);
            });
        }
        if (!empty($banner)) {
            $field_banner = 'Banner';
            if ($language === 'fr') {
                $field_banner = $field_banner . '_FR';
            }
            $filter_data = array_filter($filter_data, function ($val) use ($banner, $field_banner) {
                return ($val[$field_banner] === $banner);
            });
        }
        if (!empty($businessUnit)) {
            $field_business = 'BusinessUnit';
            if ($language === 'fr') {
                $field_business = 'BusinessUnit_FR';
            }
            $filter_data = array_filter($filter_data, function ($val) use ($businessUnit, $field_business) {
                return ($val[$field_business] === $businessUnit);
            });
        }
        if (!empty($jobType)) {
            $field_job = 'jobType';
            if ($language === 'fr') {
                $field_job = 'jobType_FR';
            }
            $filter_data = array_filter($filter_data, function ($val) use ($businessUnit, $field_job) {
                return ($val[$field_job] === $jobType);
            });
        }
        if (!empty($lang)) {
            $filter_data = array_filter($filter_data, function ($val) use ($lang) {
                return ($val['Language'] === $lang);
            });
        }
        $filter_data = array_values($filter_data);
        $total_count = count($filter_data);
        $show_count_label = $this->Api_Data_Result_Count( $total_count, $current);
        $total_pages = ( $total_count > $per_page ) ? ceil( $total_count / $per_page ) : '1';
        $pagination_label = $this->Sobeys_Filter_Pagination(array('total' => $total_pages, 'current' => $current  ));
        $offset = 0;
        if( $current > 1 ){
            $page = $current - 1;
            $offset = $per_page * $page;
        }
        $result = '';
        $All_Data = array_slice( $filter_data, $offset, $per_page);
        wp_send_json_success( array( 'result' => $All_Data, 'count' => $show_count_label, 'pagination' => $pagination_label, 'pages' => $total_pages, 'total' => $total_count ) );
    }
    /*
     *Search form ajax function
     */
    public function Sobeys_Search_Filter(){
        $per_page = $this->per_page;
        $slug = sanitize_text_field($_POST['page_slug']);
        $data = $this->Get_Sobeys_API_Json_Data($slug);
        $current = sanitize_text_field($_POST['current']);
        $keyword = sanitize_text_field($_POST['keyword']) ?? '';
        $category = sanitize_text_field($_POST['category']) ?? '';
        $location = sanitize_text_field($_POST['location']) ?? '';
        $filter_data = [];
        if(!check_ajax_referer('sobeys', 'nonce', false) ){
			wp_send_json_error( 'bad nonce ');
		}
        $filter_data = !empty($keyword) 
        ? array_filter($data, function ($val) use ($keyword) {					
            return preg_match('/' . preg_quote($keyword, "/") . '/i', $val['Title']);
        }) 
        : $data;
        if (!empty($category)) {
            $filter_data = array_filter($filter_data, function ($val) use ($category) {
                return isset($val['Category']) && $val['Category'] === $category;
            });
        }
        if (!empty($location)) {
            $filter_data = array_filter($filter_data, function ($val) use ($location) {
                return (isset($val['City']) && $val['City'] === $location) ||
                    (isset($val['State']) && $val['State'] === $location) ||
                    (isset($val['Country']) && $val['Country'] === $location) ||
                    (isset($val['PostalCode']) && $val['PostalCode'] === $location);
            });
        }
        $filter_data = array_values($filter_data);
        $total_count = count($filter_data);
        $show_count_label = $this->Api_Data_Result_Count( $total_count, $current);
        $total_pages = ( $total_count > $per_page ) ? ceil( $total_count / $per_page ) : '1';
        $pagination_label = $this->Sobeys_Filter_Pagination(array('total' => $total_pages, 'current' => $current  ));
        $offset = 0;
        if( $current > 1 ){
            $page = $current - 1;
            $offset = $per_page * $page;
        }
        $result = '';
        $All_Data = array_slice( $filter_data, $offset, $per_page);
        wp_send_json_success( array( 'result' => $All_Data, 'count' => $show_count_label, 'pagination' => $pagination_label, 'pages' => $total_pages, 'total' => $total_count ) );
    }
    /**
     *  Show single job data ajax function
     */
    public function Sobeys_Job_Details(){
        $id = sanitize_text_field($_POST['id']);
        $data = $this->Get_Sobeys_API_Json_Data($slug);

        $filtered = array_filter($data, function ($item) use ($id) {
            return $item['ID'] === $id;
        });
        $result = array_values($filtered)[0] ?? [];

        wp_send_json_success( array( 'result' => $result  ) );
    }
    /**
    *show job listing html
    */
    public function tmpl_ajax_json_data(){
		?>
		<script type="text/html" id="tmpl-job_list_rows">
        <# _.each(data.result, function(val) { #>
            <# 
            var Banner = data.lang === 'fr' ? val.Banner_FR : val.Banner;
            #>
            <tr>
                <td>{{{ val.Title }}}</td>
                <td>
                    <# 
                    var locations = [];
                    if (val.City) locations.push(val.City);
                    if (val.State) locations.push(val.State);
                    if (val.Country) locations.push(val.Country);
                    if (val.PostalCode) locations.push(val.PostalCode);
                    #>
                    {{ locations.join(', ') }}
                </td>
                <td>{{ val.Category || '' }}</td>
                <td>{{ Banner }}</td>
                <td>
                    <a href="#" data-id="{{ val.ID || '' }}" class="job_view_detail">{{ sobeys.view_details }}</a>
                </td>
            </tr>
        <# }); #>
        </script>
	    <?php 
    }
    /**
    *show single job html
    */ 
    public function tmpl_job_ajax_json_data(){
		?>
		<script type="text/html" id="tmpl-job_list_text">
        <a href="#" class="btn-close" aria-label="Close"><img src="<?php echo TRU_PLUGIN_URL.'/assets/image/close.svg' ?>"></a>
        <# 
        var JobCategory = data.lang === 'fr' ? data.result.JobCategory_FR : data.result.JobCategory;
        var JobType = data.lang === 'fr' ? data.result.JobType_FR : data.result.JobType;
        #>
        <div class="single-job-content">
            <div class="row">
                <div class="col-md-8">
                    <div class="single-content-left-col">
                        <div class="popup-heading-section row">
                            <div class="popup-heading col-xl-7">
                                <h3>{{{ data.result.Title }}}</h3>
                            </div>
                            <div class="popup-buttons col-xl-5">
                                <button class="share-job-btn career-button"><span>{{ sobeys.share }}</span></button>
                                <button class="intertested-job-btn career-button"><span>{{ sobeys.interested }}</span></button>
                            </div>
                        </div>
                        <div class="popup-content-section">
                            {{{ data.result.Description }}}
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="single-content-sidebar">
                    <h3>{{ sobeys.information }}</h3>
                    <# if (data.result.ReqId) { #>
                        <div class="job-meta">
                            <div class="col-6">
                                <h4 class="job-text">{{{ sobeys.requisition }}}:</h4>
                            </div>
                            <div class="col-6">
                                <p class="job-desc">{{{ data.result.ReqId }}}</p>
                            </div> 
                        </div>
                        <# } #>
                        <# if (data.result.Category) { #>
                        <div class="job-meta">
                            <div class="col-6">
                                <h4 class="job-text">{{{ sobeys.career_group }}}:</h4>
                            </div>
                            <div class="col-6">
                                <p class="job-desc">{{{ data.result.Category }}}</p>
                            </div> 
                        </div>
                        <# } #>
                        <# if (JobCategory) { #>
                        <div class="job-meta">
                            <div class="col-6">
                                <h4 class="job-text">{{{ sobeys.job_category }}}:</h4>
                            </div>
                            <div class="col-6">
                                <p class="job-desc">{{{ JobCategory }}}</p>
                            </div> 
                        </div>
                        <# } #>
                        <# if (data.result.Travel) { #>
                        <div class="job-meta">
                            <div class="col-6">
                                <h4 class="job-text">{{{ sobeys.travel }}}:</h4>
                            </div>
                            <div class="col-6">
                                <p class="job-desc">{{{ data.result.Travel }}}</p>
                            </div> 
                        </div>
                        <# } #>
                        <# if (JobType) { #>
                        <div class="job-meta">
                            <div class="col-6">
                                <h4 class="job-text">{{{ sobeys.job_type }}}:</h4>
                            </div>
                            <div class="col-6">
                                <p class="job-desc">{{{ JobType}}}</p>
                            </div> 
                        </div>
                        <# } #>
                        <# if (data.result.Country) { #>
                        <div class="job-meta">
                            <div class="col-6">
                                <h4 class="job-text">{{{ sobeys.country }}}:</h4>
                            </div>
                            <div class="col-6">
                                <p class="job-desc">{{{ data.result.Country }}}</p>
                            </div> 
                        </div>
                        <# } #>
                        <# if (data.result.State) { #>
                        <div class="job-meta">
                            <div class="col-6">
                                <h4 class="job-text">{{{ sobeys.state }}}:</h4>
                            </div>
                            <div class="col-6">
                                <p class="job-desc">{{{ data.result.State }}}</p>
                            </div> 
                        </div>
                        <# } #>
                        <# if (data.result.City) { #>
                        <div class="job-meta">
                            <div class="col-6">
                                <h4 class="job-text">{{ sobeys.city }}:</h4>
                            </div>
                            <div class="col-6">
                                <p class="job-desc">{{{ data.result.City}}}</p>
                            </div> 
                        </div>
                        <# } #>
                    </div>
                </div>
            </div>
        </div>
        </script>
	    <?php 
    }
    
}
$tru_sobeys_data = new TRU_SOBYES_CAREERS_FRONTEND();
