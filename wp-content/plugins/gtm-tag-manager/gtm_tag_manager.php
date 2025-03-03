<?php 
/**
  Plugin Name: GTM Tag Manager by Truinc
  Plugin URI: http://tru.agency/
  Description: Insert GTM code in header
  Author: Truinc
  Version: 1.1
  Author URI: http://tru.agency/
  License: GPLv2
**/
if(!class_exists('gtm_tag_manager_tru')) {
	
class gtm_tag_manager_tru {
	
	public function __construct() {		
		add_action('admin_menu', array(&$this, 'gtm_tru_menu_page'));
		add_action( 'wp_head',  array( &$this, 'print_tag' ) );
		add_action( 'gtm_print_noscript_tag',  array( &$this, 'print_noscript_tag' ) );
	}
	// menu
	public function gtm_tru_menu_page() {
		add_menu_page(
			__( 'GTM', 'gtm-tag-manager' ),
			__( 'GTM', 'gtm-tag-manager' ),
			'manage_options',
			'gtm_tag_manager',
			array(&$this, 'gtm_tag_manager_callback'),
			'dashicons-admin-site'
			);
	}
	// menu call bacl
	public function gtm_tag_manager_callback() {
	 if(is_admin() && current_user_can('manage_options')) {
		 include('inc/settings.php');	 
	 }
	}
	// print tag
	public function print_tag() {
	 $opt = get_option('gtm_tag_manager_tru');
	  if($this->enable_gtm()) {
		  if(isset($opt['gtm_code_head_tru']) && !empty($opt['gtm_code_head_tru'])) {
			  echo stripslashes($opt['gtm_code_head_tru']);
		  }
	  }
	}
	// use do_action('gtm_print_noscript_tag'); 
	public function print_noscript_tag() {
	  $opt = get_option('gtm_tag_manager_tru');
	  if($this->enable_gtm()) {
		  if(isset($opt['gtm_code_body_tru']) && !empty($opt['gtm_code_body_tru'])) {
			  echo stripslashes($opt['gtm_code_body_tru']);
		  }
	  }
	}
	// check gtm is enabled or not
	public function enable_gtm() {
	  $opt = get_option('gtm_tag_manager_tru');
	  if(isset($opt['enable_gtm_tru']) && !empty($opt['enable_gtm_tru']) &&$opt['enable_gtm_tru'] == '1') {
		 return true; 
	  } else {
		 return false;
	  }
	}
	// js redirection
	public function redirect($url)
		{
			echo '<script>window.location.href="'.$url.'"</script>';
			die;
		}
	
}

new gtm_tag_manager_tru;	
}