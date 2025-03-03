<?php
$cookie_policy = get_option('cookie_policy_settings');
$cookie_policy_css_value =  get_option('cookie_policy_css');
$cookie_policy_ad_choice =  get_option('cookie_policy_choice_setting');
$ad_choice_css_value =  get_option('ad_choice_css');

if (!defined("ICL_LANGUAGE_CODE")){
    $cookie_policy_lang ='';
	$cookie_policy_lang_cls ='';
} 
else if ((ICL_LANGUAGE_CODE =='en')){
	$cookie_policy_lang ='';
	$cookie_policy_lang_cls ='';
} 
else if ((ICL_LANGUAGE_CODE =='fr')){
	$cookie_policy_lang ='_fr';
	$cookie_policy_lang_cls ='lang_fr';
	
}
if(is_array($cookie_policy) && $cookie_policy['enable_cookie_policy'] == 'Yes'):?>
<div style="<?php if(isset($cookie_policy['cookie_policy_background_color']) && !empty($cookie_policy['cookie_policy_background_color'])){ echo "background-color:".$cookie_policy['cookie_policy_background_color'];}?>" class="cookie_policy <?php echo $cookie_policy_lang_cls; ?>" role="complementary">
	<input type="hidden" id="cookie_policy_cookie_time" value="<?php if(isset($cookie_policy['cookie_policy_time']) && !empty($cookie_policy['cookie_policy_time'])){ echo $cookie_policy['cookie_policy_time'];}?>">
	<input type="hidden" id="cookie_policy_random_no" value="<?php if(isset($cookie_policy['cookie_policy_ran_no']) && !empty($cookie_policy['cookie_policy_ran_no'])){ echo $cookie_policy['cookie_policy_ran_no'];}?>">
	<div class="sobeys_container">
		<div class="col_left">
			<?php
			if(isset($cookie_policy['cookie_policy_title_value'.$cookie_policy_lang]) && !empty($cookie_policy['cookie_policy_title_value'.$cookie_policy_lang])):
				?>
				<h2 style="<?php if(isset($cookie_policy['cookie_policy_title_color']) && !empty($cookie_policy['cookie_policy_title_color'])){ echo "color:".$cookie_policy['cookie_policy_title_color'];}?>"  class="cookie_policy_title"><?php echo $cookie_policy['cookie_policy_title_value'.$cookie_policy_lang];?></h2>
			<?php	
			endif;
			?>
			<div class="scp_desc">
				<?php if(isset($cookie_policy['cookie_policy_desc_value'.$cookie_policy_lang]) && !empty($cookie_policy['cookie_policy_desc_value'.$cookie_policy_lang])):
					?>
					<span style="<?php if(isset($cookie_policy['cookie_policy_desc_color']) && !empty($cookie_policy['cookie_policy_desc_color'])){ echo "color:".$cookie_policy['cookie_policy_desc_color'];}?>" class="cookie_policy_desc"><?php echo $cookie_policy['cookie_policy_desc_value'.$cookie_policy_lang];?></span>
				<?php	
				endif;
				if(isset($cookie_policy['cookie_policy_learn_more'.$cookie_policy_lang]) && !empty($cookie_policy['cookie_policy_learn_more'.$cookie_policy_lang])):
					?>
					<span class="cookie_policy_lmore"><a class="tabpress_1" style="<?php if(isset($cookie_policy['cookie_policy_learn_more_color']) && !empty($cookie_policy['cookie_policy_learn_more_color'])){ echo "color:".$cookie_policy['cookie_policy_learn_more_color'];}?>" href="<?php if(isset($cookie_policy['cookie_policy_learn_more_link'.$cookie_policy_lang]) && !empty($cookie_policy['cookie_policy_learn_more_link'.$cookie_policy_lang])){ echo $cookie_policy['cookie_policy_learn_more_link'.$cookie_policy_lang];}?> "
					target="<?php if(isset($cookie_policy['cookie_policy_learn_more_target']) && !empty($cookie_policy['cookie_policy_learn_more_target'])){ echo $cookie_policy['cookie_policy_learn_more_target'];} else{ echo '_self';}?>"><?php echo $cookie_policy['cookie_policy_learn_more'.$cookie_policy_lang];?></a></span>
				<?php	
				endif; 
				?>
			</div>
		</div> 
	    
	    <div class="col_right">
		<?php if(isset($cookie_policy['cookie_policy_btn_text_value'.$cookie_policy_lang]) && !empty($cookie_policy['cookie_policy_btn_text_value'.$cookie_policy_lang])):
		?>
		<a href="#" style="<?php if(isset($cookie_policy['cookie_policy_btn_background_color']) && !empty($cookie_policy['cookie_policy_btn_background_color'])){ echo "background-color:".$cookie_policy['cookie_policy_btn_background_color'].';';}?><?php if(isset($cookie_policy['cookie_policy_btn_text_color']) && !empty($cookie_policy['cookie_policy_btn_text_color'])){ echo "color:".$cookie_policy['cookie_policy_btn_text_color'].';';}?>" class="cookie_policy_btn tabpress_2"><?php echo $cookie_policy['cookie_policy_btn_text_value'.$cookie_policy_lang];?></a>
		<?php	
		endif;?>
	    </div>
	</div>
</div>
<style>
<?php 
if(isset($cookie_policy_css_value['cookie_policy_css'])){
 echo stripslashes($cookie_policy_css_value['cookie_policy_css']);
}
?>
</style>
<?php endif; ?>

<?php if(is_array($cookie_policy_ad_choice) && $cookie_policy_ad_choice['enable_ad_choice'] == 'Yes'):
		$close_icon = $cookie_policy_ad_choice['close_icon_value'];
		if(empty($close_icon)){
			$close_icon  = SCP_URL.'images/close.svg';
		}
?>
<div class="cookie_ad_chocie">
	<span class="cookie_ad_close"><img src="<?php echo $close_icon;?>"></span>
	<a href="<?php echo $cookie_policy_ad_choice['link_text_value'];?>" target="<?php echo $close_icon;?>">
	   <img src="<?php echo $cookie_policy_ad_choice['image_upload_value'];?>"/>
	</a>
</div>
<style>
<?php

	if(isset($ad_choice_css_value['ad_choice_css'])){
	 echo stripslashes($ad_choice_css_value['ad_choice_css']);
	}
?>
</style>
<?php endif;?>