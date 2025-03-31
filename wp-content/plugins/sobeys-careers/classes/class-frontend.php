<?php
namespace TRU\SOBYES_CAREERS\Classes;
use TRU\SOBYES_CAREERS\SOBYES_CAREERS;
class TRU_SOBYES_CAREERS_FRONTEND{
    public $per_page;
    public function __construct(){
        $this->per_page =  defined('PER_PAGE') ? PER_PAGE : 30;
        add_shortcode('Sobeys-Search', [$this, 'sobeys_search_form']);
        add_shortcode('Sobeys-Filter', [$this, 'sobeys_filter_form']);
        add_action( 'wp_ajax_Sobeys_Form_Filter', [$this, 'Sobeys_Form_Filter'] );
		add_action( 'wp_ajax_nopriv_SobeysFormFilter', [$this, 'Sobeys_Form_Filter'] );
    }
   
    public function Sobeys_API_Json_Data(){
        $Api_data = [];
        $directory = TRU_PLUGIN_PATH . '/json/fetchData.json';               
        if( file_exists($directory) ){
            $Api_data_Json = file_get_contents($directory);
            $Api_data = json_decode($Api_data_Json, true);
        }
        return $Api_data;
    }

    /**  Display the search form. */
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
    /**  Display the filter form. */
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
    /***** Get Json File Data Function *****/
    public static function Get_Sobeys_API_Json_Data(){
        $Api_data = [];
        $directory = TRU_PLUGIN_PATH . '/json/fetchData.json';               
        if( file_exists($directory) ){
             $Api_data_Json = file_get_contents($directory);
             $Api_data = json_decode($Api_data_Json, true);
        }
        return $Api_data;
    }
    /***** Get Showing results count label *****/
    public static function Api_Data_Result_Count( $args = array() ) {
        $of_label = $args['of'] ?? 'of';
		$total = $args['total'];
		$current = $args['current'] ?? 1;
		$limit = 10;
		$from = ( $limit * $current ) - ( $limit - 1 );
		if(( $limit * $current ) <= $total ){
			$to = ( $limit * $current );
		} else {
			$to = $total;
		}
		if ($total > 0) { 
            return '<div class="result-counter" role="status">
                <span class="total-item-count" tabindex="0">' . __('Results', '') . ' ' . $from . ' - ' . $to . ' ' . $of_label . ' ' . $total . '</span>
            </div>';
        }
	}
    /***** Get Showing filter pagination *****/
	public static function Sobeys_Filter_Pagination( $args = array() ){
		$data = '';
		$prev = (!empty($args['prev'] )) ? $args['prev'] : 'Prev'; 
		$next = (!empty($args['next'] )) ? $args['next'] : 'Next'; 
		$current = $args['current']; 
		$total_pages = $args['total'];
		$mid_size = $args['mid_size'] ?? 3;
		$rmid_size = $args['rmid_size'] ?? 2;
		if($current > 1){  
			$data .= '<li class="prev"><a class="prev page-number" href="#" data-number="'.(  $current - 1).'">'.$prev.'</a></li>';
}
		$links =  ( $current > $mid_size ) ? $current - $rmid_size : 1; 
		if($current >= $mid_size+1) {
			$data .= '<li><a class="next page-number" href="#" data-number="1">1</a></li>'; 
		} if ($links != 1 && $current > $mid_size+1) {                        
			$data .= '<li><a>…</a></li>';
		}
		for ($index = $links; $index <= $current + $rmid_size && $index <= $total_pages; $index++) { 
		    if ( $index == $current ) {
				$data .= '<li><span aria-current="page" class="page-numbers current">'.$index.'</span></li>';
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
			$data .= '<li class="next"><a class="next page-number" href="#" data-number="'.($current + 1).'">'.$next.'</a></li>';
		} 
		return $data;
	}
    /***** Job Listing Html *****/
    public function get_Job_API_Results_View( $data){
        $result = '';
        foreach ($data as $value){
            $result .= '<tr>
                <td>'.htmlspecialchars($value['Title']).'</td>
                <td>'.htmlspecialchars($value['Location'] ?? 'N/A').'</td>
                <td>'.htmlspecialchars($value['Category'] ?? 'N/A').'</td>
                <td>'.htmlspecialchars($value['Banner'] ?? 'N/A').'</td>
            </tr>';
        }
        return $result;
    }
    /***** Form Filter Ajax Action Triiger *****/
    public function Sobeys_Form_Filter(){
        $per_page = $this->per_page;
        $data = $this->Get_Sobeys_API_Json_Data();
        $current = $_POST['current'];
        $show_count_label = $this->Api_Data_Result_Count(array('total' => count($data), 'current' => $current, 'limit' => $per_page));
        $total_pages = ( $data > $per_page ) ? ceil( $data / $per_page ) : '1';
        $pagination_label = $this->Sobeys_Filter_Pagination(array('total' => $total_pages, 'current' => $current  ));
        $offset = 0;
        if( $current > 1 ){
            $page = $current - 1;
            $offset = $per_page * $page;
        }
        $result = '';
        $All_Data = array_slice( $data, $offset, $per_page);
        foreach($All_Data as $data){
            $result .= $this->get_Job_API_Results_View($data );
        }
        wp_send_json_success( array( 'result' => $result, 'count' => $show_count_label, 'pagination' => $pagination_label  ) );
    }

}
$tru_sobeys_data = new TRU_SOBYES_CAREERS_FRONTEND();