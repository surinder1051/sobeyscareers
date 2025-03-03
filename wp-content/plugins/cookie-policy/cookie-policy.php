<?php 
/*
Plugin Name: Cookie Policy
Author: Truinc
Version: 1.1.1
*/

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*Define Contants*/
if (!defined("SCP_DIR")) define("SCP_DIR",  plugin_dir_path(__FILE__));
defined( 'SCP_URL' ) or define( 'SCP_URL', plugin_dir_url( __FILE__ ));
if (!defined("SCP")) define("SCP", "SCP");
//if (!defined("ICL_LANGUAGE_CODE")) define("ICL_LANGUAGE_CODE", "en");
// Hook
register_activation_hook(__FILE__, "scp_plugin_install");
add_action('wp_footer', 'scp_html');
add_action('admin_menu','scp_options_page');

add_action("admin_init", 'scp_backend_scripts');

//Functions
function scp_plugin_install(){
	
 $cookie_policy_desc_value = "We use Cookies to create the most secure and effective Website experience for our customers. For more information about Cookies and how you can disable Cookies, visit our privacy policy page.";
 $cookie_policy_desc_value_fr = "Nous utilisons des cookies pour créer l'expérience de site Web la plus sécurisée et la plus efficace pour nos clients. Pour plus d'informations sur les cookies et comment les désactiver, consultez notre page de politique de confidentialité.";

 $settings = array(
				   'enable_cookie_policy' => 'Yes',
                   'cookie_policy_background_color'=>'#fff',
				   'cookie_policy_title_value' => 'Cookie & Privacy Policy',
				   'cookie_policy_title_value_fr' => 'Cookie et politique de confidentialité',
				   'cookie_policy_title_color' => '#01603e',
				   'cookie_policy_desc_value' => $cookie_policy_desc_value,
				   'cookie_policy_desc_value_fr' => $cookie_policy_desc_value_fr,
				   'cookie_policy_desc_color' =>'#404044',
				   'cookie_policy_learn_more'=> 'Learn More',
				   'cookie_policy_learn_more_fr'=> 'Apprendre encore plus',
				   'cookie_policy_learn_more_link'=> '#',
				   'cookie_policy_learn_more_link_fr'=> '#',
				   'cookie_policy_learn_more_target'=> '_self',
				   'cookie_policy_learn_more_color'=> '#48a548',				   
				   'cookie_policy_btn_background_color' =>'#48a548',
				   'cookie_policy_btn_text_color' =>'#fff',
				   'cookie_policy_btn_text_value' =>'Ok',
				   'cookie_policy_btn_text_value_fr' =>"D'accord",
				   'cookie_policy_ran_no'=>rand(10,100000),
				   'cookie_policy_time'=>365
					);
 $opt = get_option('cookie_policy_settings'); 
 if(empty($opt)){
   update_option('cookie_policy_settings', $settings);
 }
}
//Frontend Html
function scp_html(){
	
	$cookie_policy = get_option('cookie_policy_settings');	
	include_once SCP_DIR."view/scp_cookie_policy_frontend.php";	
	if(is_array($cookie_policy) && $cookie_policy['enable_cookie_policy'] == 'Yes'):
		wp_enqueue_style( 'cookie_policy_style',  plugin_dir_url( __FILE__ ) . 'css/cookie_policy_style.css', array(),date("Y-m-d---G-i-s", filemtime( plugin_dir_path( __FILE__ ). 'css/cookie_policy_style.css' )));
		wp_enqueue_script( 'cookie_policy_script', plugin_dir_url( __FILE__ ) . 'js/cookie_policy_script.js', false, date("Y-m-d---G-i-s", filemtime(plugin_dir_path( __FILE__ ). 'js/cookie_policy_script.js')) , false); 
	endif;
	
}
//Menu
function scp_options_page(){
	add_options_page('Cookie Policy', 'Cookie Policy', 'manage_options', 'scp_settings','scp_settings');
}
//Backend Form Html
function scp_settings(){
	include_once SCP_DIR."view/scp_cookie_policy_backend.php";
}

function scp_backend_scripts(){
	wp_enqueue_style("cookie_policy_backend", plugins_url("css/cookie_policy_backend.css",__FILE__));
}
?>