<?php 
 
$cookie_policy = get_option('cookie_policy_settings');
$cookie_policy_css_value =  get_option('cookie_policy_css');
$cookie_policy_ad_choice =  get_option('cookie_policy_choice_setting');
$ad_choice_css_value =  get_option('ad_choice_css');

if (isset($_REQUEST['save_settings'])) {
	echo 'Saving please wait...';
    $cookie_policy_data = array();	
	$cookie_policy_data['enable_cookie_policy'] = isset($_REQUEST['enable_cookie_policy'])?$_REQUEST['enable_cookie_policy']:'No';
	$cookie_policy_data['cookie_policy_background_color'] = $_REQUEST['cookie_policy_background_color'];
	$cookie_policy_data['cookie_policy_title_value'] = stripslashes($_REQUEST['cookie_policy_title_value']);
	$cookie_policy_data['cookie_policy_title_value_fr'] = stripslashes($_REQUEST['cookie_policy_title_value_fr']);
	$cookie_policy_data['cookie_policy_title_color'] = $_REQUEST['cookie_policy_title_color'];
	$cookie_policy_data['cookie_policy_desc_value'] = stripslashes($_REQUEST['cookie_policy_desc_value']);
	$cookie_policy_data['cookie_policy_desc_value_fr'] = stripslashes($_REQUEST['cookie_policy_desc_value_fr']);
	$cookie_policy_data['cookie_policy_desc_color'] = $_REQUEST['cookie_policy_desc_color'];
	$cookie_policy_data['cookie_policy_learn_more'] = stripslashes($_REQUEST['cookie_policy_learn_more']);
	$cookie_policy_data['cookie_policy_learn_more_link'] = $_REQUEST['cookie_policy_learn_more_link'];
	$cookie_policy_data['cookie_policy_learn_more_fr'] = stripslashes($_REQUEST['cookie_policy_learn_more_fr']);
	$cookie_policy_data['cookie_policy_learn_more_link_fr'] = $_REQUEST['cookie_policy_learn_more_link_fr'];
	$cookie_policy_data['cookie_policy_learn_more_target'] = $_REQUEST['cookie_policy_learn_more_target'];
	$cookie_policy_data['cookie_policy_learn_more_color'] = $_REQUEST['cookie_policy_learn_more_color'];
	$cookie_policy_data['cookie_policy_btn_background_color'] = $_REQUEST['cookie_policy_btn_background_color'];
	$cookie_policy_data['cookie_policy_btn_text_color'] = $_REQUEST['cookie_policy_btn_text_color'];
	$cookie_policy_data['cookie_policy_btn_text_value'] = stripslashes($_REQUEST['cookie_policy_btn_text_value']);
	$cookie_policy_data['cookie_policy_btn_text_value_fr'] = stripslashes($_REQUEST['cookie_policy_btn_text_value_fr']);
	$cookie_policy_data['cookie_policy_time'] = $_REQUEST['cookie_policy_time'];
	$cookie_policy_data['cookie_policy_ran_no'] = isset($_REQUEST['cookie_policy_ran_no']) && !empty($_REQUEST['cookie_policy_ran_no']) ?$_REQUEST['cookie_policy_ran_no']:rand(10,100000);
	$cookie_policy = update_option('cookie_policy_settings', $cookie_policy_data);
    
    
    if($cookie_policy){
		echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
<p><strong>Cookie Policy settings are saved.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
    echo '<script language="javascript">';
	echo 'window.location.reload();';
	echo '</script>'; 
	}
	else{
		echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
<p><strong>Error! Cookie Policy settings are not saved.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
    echo '<script language="javascript">';
	echo 'window.location.reload();';
	echo '</script>';
	}	 
}
if (isset($_REQUEST['reset_settings'])) {
	echo 'Resting please wait...';
	$cookie_policy_data = $cookie_policy;	
	$cookie_policy_data['cookie_policy_ran_no'] = rand(10,100000);
	$cookie_policy = update_option('cookie_policy_settings', $cookie_policy_data);
	if($cookie_policy){
		echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
<p><strong>Cookie Policy settings are saved.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
    echo '<script language="javascript">';
	echo 'window.location.reload();';
	echo '</script>'; 
	}
	else{
		echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
<p><strong>Error! Cookie Policy settings are not saved.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
    echo '<script language="javascript">';
	echo 'window.location.reload();';
	echo '</script>';
	}	
}

if(isset($_REQUEST['css_save_settings'])){
	$cookie_policy_css = array();	
	$cookie_policy_css['cookie_policy_css'] = isset($_REQUEST['cookie_policy_css'])?stripslashes($_REQUEST['cookie_policy_css']):'';
	
	$cookie_policy_bfc = update_option('cookie_policy_css', $cookie_policy_css);
	
	if($cookie_policy_bfc){
		
		echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
<p><strong>Cookie Policy Css are saved.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
    echo '<script language="javascript">';
	echo 'window.location.reload();';
	echo '</script>'; 
	}
}
// ad choice settings
if (isset($_REQUEST['ad_save_settings'])) {
	
	echo 'Saving please wait...';
    $cookie_policy_choice = array();	
	$cookie_policy_choice['enable_ad_choice'] = isset($_REQUEST['enable_ad_choice'])?$_REQUEST['enable_ad_choice']:'No';
	$cookie_policy_choice['image_upload_value'] = $_REQUEST['image_upload_value'];
	$cookie_policy_choice['link_text_value'] = stripslashes($_REQUEST['link_text_value']);
	$cookie_policy_choice['link_target_value'] = stripslashes($_REQUEST['link_target_value']);
	$cookie_policy_choice['close_icon_value'] = $_REQUEST['close_icon_value'];
	
	$cookie_policy_choice_setting = update_option('cookie_policy_choice_setting', $cookie_policy_choice);
   
   if($cookie_policy_choice_setting){
		echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
<p><strong>Ad Choice settings are saved.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
    echo '<script language="javascript">';
	echo 'window.location.reload();';
	echo '</script>'; 
	}
	else{
		echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
<p><strong>Error! Ad Choice settings are not saved.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
    echo '<script language="javascript">';
	echo 'window.location.reload();';
	echo '</script>';
	}
}


//ad choice css
if(isset($_REQUEST['ad_css_save_settings'])){
	$ad_choice_form_css = array();	
	$ad_choice_form_css['ad_choice_css'] = isset($_REQUEST['ad_choice_css'])?stripslashes($_REQUEST['ad_choice_css']):'';
	
	$ad_choice_css= update_option('ad_choice_css', $ad_choice_form_css);
	
	if($ad_choice_css){
		
		echo '<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
<p><strong>Ad Choices CSS are saved.</strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
    echo '<script language="javascript">';
	echo 'window.location.reload();';
	echo '</script>'; 
	}
}


if (!defined("ICL_LANGUAGE_CODE")){
    $cookie_policy_lang_en = ''; 
    $cookie_policy_lang_fr = 'scp_none'; 
} 
else if ((ICL_LANGUAGE_CODE =='en')){
    $cookie_policy_lang_en = ''; 
    $cookie_policy_lang_fr = 'scp_none';
} 
else if ((ICL_LANGUAGE_CODE =='fr')){
    $cookie_policy_lang_en = 'scp_none'; 
    $cookie_policy_lang_fr = '';

}
?>
<style>
<?php 
if(isset($cookie_policy_css_value['cookie_policy_css'])){
?>
<?php echo $cookie_policy_css_value['cookie_policy_css'];?>
<?php
}
?>
</style>
<div class="frm_scp backend_site_type">
	<h2 class="main_scp">Cookie Policy - Settings</h2>
	<form name="cookie_policy_form" method="post" action="">
		<p class="scp_enable"><span>Enable ?</span>
		<input type="checkbox" name="enable_cookie_policy" value="Yes" <?php if(isset($cookie_policy['enable_cookie_policy'])&& $cookie_policy['enable_cookie_policy'] == 'Yes'){ echo "checked=checked";}?>>
		</p>
		<div class="tbl_scp"><div class="scp_col"><p>Cookie Time( In Days )</p></div><div class="scp_col"> <input type="text" name="cookie_policy_time" value="<?php if(isset($cookie_policy['cookie_policy_time']) && !empty($cookie_policy['cookie_policy_time'])){ echo $cookie_policy['cookie_policy_time'];} else{ echo "365";}?>"/></div></div>		
		<div class="tbl_scp"><div class="scp_col"><p>Main Background Color</p></div><div class="scp_col"> <input type="text" name="cookie_policy_background_color" value="<?php if(isset($cookie_policy['cookie_policy_background_color']) && !empty($cookie_policy['cookie_policy_background_color'])){ echo $cookie_policy['cookie_policy_background_color'];}?>"/></div></div>
		<div class="tbl_scp <?php echo  $cookie_policy_lang_en;?>"><div class="scp_col"><p>Title </p></div><div class="scp_col"><input type="text" name="cookie_policy_title_value" value="<?php if(isset($cookie_policy['cookie_policy_title_value']) && !empty($cookie_policy['cookie_policy_title_value'])){ echo stripslashes($cookie_policy['cookie_policy_title_value']);}?>"/></div></div>
		<div class="tbl_scp <?php echo  $cookie_policy_lang_fr;?>"><div class="scp_col"><p>Title </p></div><div class="scp_col"><input type="text" name="cookie_policy_title_value_fr" value="<?php if(isset($cookie_policy['cookie_policy_title_value_fr']) && !empty($cookie_policy['cookie_policy_title_value_fr'])){ echo stripslashes($cookie_policy['cookie_policy_title_value_fr']);}?>"/></div></div>
       
		
        <div class="tbl_scp"><div class="scp_col"><p>Title Color</p></div><div class="scp_col"> <input type="text" name="cookie_policy_title_color" value="<?php if(isset($cookie_policy['cookie_policy_title_color']) && !empty($cookie_policy['cookie_policy_title_color'])){ echo $cookie_policy['cookie_policy_title_color'];}?>"/></div></div>		
		
        <div class="tbl_scp <?php echo  $cookie_policy_lang_en;?>"><div class="scp_col"><p class="scp_desx">Description </p></div><div class="scp_col"><textarea  name="cookie_policy_desc_value" rows="4" cols="50"><?php if(isset($cookie_policy['cookie_policy_desc_value']) && !empty($cookie_policy['cookie_policy_desc_value'])){ echo stripslashes($cookie_policy['cookie_policy_desc_value']);}?></textarea></div></div>
		<div class="tbl_scp <?php echo  $cookie_policy_lang_fr;?>"><div class="scp_col"><p class="scp_desx">Description </p></div><div class="scp_col"><textarea  name="cookie_policy_desc_value_fr" rows="4" cols="50"><?php if(isset($cookie_policy['cookie_policy_desc_value_fr']) && !empty($cookie_policy['cookie_policy_desc_value_fr'])){ echo stripslashes($cookie_policy['cookie_policy_desc_value_fr']);}?></textarea></div></div>		
		
        <div class="tbl_scp"><div class="scp_col"><p>Description Color</p></div><div class="scp_col"> <input type="text" name="cookie_policy_desc_color" value="<?php if(isset($cookie_policy['cookie_policy_desc_color']) && !empty($cookie_policy['cookie_policy_desc_color'])){ echo $cookie_policy['cookie_policy_desc_color'];}?>"/></div></div>
		
        <div class="tbl_scp <?php echo  $cookie_policy_lang_en;?>"><div class="scp_col"><p>CTA Text </p></div><div class="scp_col"><input type="text" name="cookie_policy_learn_more" value="<?php if(isset($cookie_policy['cookie_policy_learn_more']) && !empty($cookie_policy['cookie_policy_learn_more'])){ echo stripslashes($cookie_policy['cookie_policy_learn_more']);}?>"/></div></div>
		<div class="tbl_scp <?php echo  $cookie_policy_lang_fr;?>"><div class="scp_col"><p>CTA Text </p></div><div class="scp_col"><input type="text" name="cookie_policy_learn_more_fr" value="<?php if(isset($cookie_policy['cookie_policy_learn_more_fr']) && !empty($cookie_policy['cookie_policy_learn_more_fr'])){ echo stripslashes($cookie_policy['cookie_policy_learn_more_fr']);}?>"/></div></div>
		
        <div class="tbl_scp <?php echo  $cookie_policy_lang_en;?>"><div class="scp_col"><p>CTA Link </p></div><div class="scp_col"><input type="text" name="cookie_policy_learn_more_link" value="<?php if(isset($cookie_policy['cookie_policy_learn_more_link']) && !empty($cookie_policy['cookie_policy_learn_more_link'])){ echo $cookie_policy['cookie_policy_learn_more_link'];}?>"/></div></div>
		<div class="tbl_scp <?php echo  $cookie_policy_lang_fr;?>"><div class="scp_col"><p>CTA Link </p></div><div class="scp_col"><input type="text" name="cookie_policy_learn_more_link_fr" value="<?php if(isset($cookie_policy['cookie_policy_learn_more_link_fr']) && !empty($cookie_policy['cookie_policy_learn_more_link_fr'])){ echo $cookie_policy['cookie_policy_learn_more_link_fr'];}?>"/></div></div>
		
        <div class="tbl_scp"><div class="scp_col"><p>CTA Target</p></div><div class="scp_col"> <input type="text" name="cookie_policy_learn_more_target" value="<?php if(isset($cookie_policy['cookie_policy_learn_more_target']) && !empty($cookie_policy['cookie_policy_learn_more_target'])){ echo $cookie_policy['cookie_policy_learn_more_target'];}?>"/></div></div>
		<div class="tbl_scp"><div class="scp_col"><p>CTA Text Color</p></div><div class="scp_col"> <input type="text" name="cookie_policy_learn_more_color" value="<?php if(isset($cookie_policy['cookie_policy_learn_more_color']) && !empty($cookie_policy['cookie_policy_learn_more_color'])){ echo $cookie_policy['cookie_policy_learn_more_color'];}?>"/></div></div>
		<div class="tbl_scp"><div class="scp_col"><p>Button Background Color </p></div><div class="scp_col"><input type="text" name="cookie_policy_btn_background_color" value="<?php if(isset($cookie_policy['cookie_policy_btn_background_color']) && !empty($cookie_policy['cookie_policy_btn_background_color'])){ echo $cookie_policy['cookie_policy_btn_background_color'];}?>"/></div></div>
		<div class="tbl_scp"><div class="scp_col"><p>Button Text Color</p></div><div class="scp_col"> <input type="text" name="cookie_policy_btn_text_color" value="<?php if(isset($cookie_policy['cookie_policy_btn_text_color']) && !empty($cookie_policy['cookie_policy_btn_text_color'])){ echo $cookie_policy['cookie_policy_btn_text_color'];}?>"/></div></div>
		
        <div class="tbl_scp <?php echo  $cookie_policy_lang_en;?>"><div class="scp_col"><p>Button Text</p></div><div class="scp_col"> <input type="text" name="cookie_policy_btn_text_value" value="<?php if(isset($cookie_policy['cookie_policy_btn_text_value']) && !empty($cookie_policy['cookie_policy_btn_text_value'])){ echo stripslashes($cookie_policy['cookie_policy_btn_text_value']);}?>"/></div></div>
		<div class="tbl_scp <?php echo  $cookie_policy_lang_fr;?>"><div class="scp_col"><p>Button Text</p></div><div class="scp_col"> <input type="text" name="cookie_policy_btn_text_value_fr" value="<?php if(isset($cookie_policy['cookie_policy_btn_text_value_fr']) && !empty($cookie_policy['cookie_policy_btn_text_value_fr'])){ echo stripslashes($cookie_policy['cookie_policy_btn_text_value_fr']);}?>"/></div></div>
		
        <input type="hidden" name="cookie_policy_ran_no" value="<?php if(isset($cookie_policy['cookie_policy_ran_no']) && !empty($cookie_policy['cookie_policy_ran_no'])){ echo $cookie_policy['cookie_policy_ran_no'];}?>">
		<div class="tbl_scp_btn"><input type="Submit" id="save_settings" class="v_btn_save" name="save_settings" value="Save"/></div>
		<div class="tbl_scp_btn tbl_reset_btn"><input type="Submit" id="reset_settings" class="v_btn_save" name="reset_settings" value="Reset"/></div>
	</form>
</div>
<div class="frm_scp backend_site_type ad_choice">
	<h2 class="main_scp">Ad Choices - Settings</h2>
	<form name="cookie_policy_form" method="post" action="">
		<p class="scp_enable"><span>Enable ?</span>
		<input type="checkbox" name="enable_ad_choice" value="Yes" <?php if(isset($cookie_policy_ad_choice['enable_ad_choice'])&& $cookie_policy_ad_choice['enable_ad_choice'] == 'Yes'){ echo "checked=checked";}?>>
		</p>
		<div class="tbl_scp"><div class="scp_col"><p>Image</p></div><div class="scp_col"> <input type="text" name="image_upload_value" value="<?php if(isset($cookie_policy_ad_choice['image_upload_value']) && !empty($cookie_policy_ad_choice['image_upload_value'])){ echo stripslashes($cookie_policy_ad_choice['image_upload_value']);}?>"/></div></div>
		<div class="tbl_scp"><div class="scp_col"><p>Link Url</p></div><div class="scp_col"> <input type="text" name="link_text_value" value="<?php if(isset($cookie_policy_ad_choice['link_text_value']) && !empty($cookie_policy_ad_choice['link_text_value'])){ echo stripslashes($cookie_policy_ad_choice['link_text_value']);}?>"/></div></div>
		<div class="tbl_scp"><div class="scp_col"><p>Link Target </p></div><div class="scp_col"> <input type="text" name="link_target_value" value="<?php if(isset($cookie_policy_ad_choice['link_target_value']) && !empty($cookie_policy_ad_choice['link_target_value'])){ echo stripslashes($cookie_policy_ad_choice['link_target_value']);}?>"/></div></div>
		<div class="tbl_scp"><div class="scp_col"><p>Close Icon</p></div><div class="scp_col"> <input type="text" name="close_icon_value" value="<?php if(isset($cookie_policy_ad_choice['close_icon_value']) && !empty($cookie_policy_ad_choice['close_icon_value'])){ echo stripslashes($cookie_policy_ad_choice['close_icon_value']);}?>"/></div></div>
		<div class="tbl_scp_btn"><input type="Submit" id="ad_save_settings" class="v_btn_save" name="ad_save_settings" value="Save Ad Choice"/></div>
	</form>
</div>
<div class="frm_scp">
<h2 class="main_scp">Banner Based CSS</h2>
<form name="cookie_policy_form_css" method="post" action="">
<textarea id="cookie_policy_css" name="cookie_policy_css" cols="" rows="15"><?php if(isset($cookie_policy_css_value['cookie_policy_css'])){echo stripslashes($cookie_policy_css_value['cookie_policy_css']);}?></textarea>
	<div class="tbl_scp_btn"><input type="Submit" id="css_save_settings" class="v_btn_save" name="css_save_settings" value="Save"/></div>
	</form>
</div>


<div class="frm_scp">
<h2 class="main_scp">Ad Choices CSS</h2>
<form name="ad_choice_form_css" method="post" action="">
<textarea id="cookie_policy_css" name="ad_choice_css" cols="" rows="15"><?php if(isset($ad_choice_css_value['ad_choice_css'])){echo stripslashes($ad_choice_css_value['ad_choice_css']);}?></textarea>
	<div class="tbl_scp_btn"><input type="Submit" id="css_save_settings" class="v_btn_save" name="ad_css_save_settings" value="Save"/></div>
	</form>
</div>