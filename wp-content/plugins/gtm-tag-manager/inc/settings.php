<?php if ( ! defined( 'ABSPATH' ) ) exit;
$opt = get_option('gtm_tag_manager_tru');?>
<div class="wrap">
<h1><?php _e('General Settings','gtm-tag-manager'); ?></h1>
<?php if(isset($_GET['msg']) && $_GET['msg'] == '1'):?>
<div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated"> 
<p><strong><?php _e('Settings saved.','gtm-tag-manager'); ?></strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text"><?php _e('Dismiss this notice.','gtm-tag-manager'); ?></span></button></div>
<?php elseif(isset($_GET['msg']) && $_GET['msg'] == '2'):?>
<div class="error updated settings-error notice is-dismissible" id="setting-error-settings_updated"> 
<p><strong><?php _e('Settings not saved.','gtm-tag-manager'); ?></strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text"><?php _e('Dismiss this notice.','gtm-tag-manager'); ?></span></button></div>
<?php endif; 
$gtm_tag_manager_tru_options = array();
if(isset($_POST['submit']) && wp_verify_nonce( $_POST['gtm_tag_manager_tru_nonce_field'], 'gtm_tag_manager_tru_action' )):
	_e("<strong>Saving Please wait...</strong>", 'gtm-tag-manager');
		foreach($_POST as $key => $val):
		$gtm_tag_manager_tru_options[$key] = stripslashes($val);
		endforeach;
		 $saveSettings = update_option('gtm_tag_manager_tru', $gtm_tag_manager_tru_options );
		if($saveSettings){
		   $this->redirect('?page=gtm_tag_manager&msg=1');	
		}
		else {
		  $this->redirect('?page=gtm_tag_manager&msg=2');	
		}
endif;
?>

<form method="post" action="">
<?php wp_nonce_field( 'gtm_tag_manager_tru_action', 'gtm_tag_manager_tru_nonce_field' ); ?>
<table class="form-table">

<tbody>

<tr>
<th scope="row"><?php _e('Enable ?','gtm-tag-manager'); ?></th>
<td>
<label for="enable_gtm_tru">
<input name="enable_gtm_tru" id="enable_gtm_tru" value="1" type="checkbox" <?php echo( isset($opt['enable_gtm_tru']) && $opt['enable_gtm_tru'] == '1') ? 'checked="checked"' : '';?>><?php _e('Enable GTM ?','gtm-tag-manager'); ?></label></td>
</tr>
<tr>
<th scope="row"><?php _e('GTM Code','gtm-tag-manager'); ?></th>
<td>
<label for="gtm_code_head_tru"><?php _e('Insert GTM Code here, it will auto inserted in &lt;head&gt; tag.','gtm-tag-manager'); ?></label>
<textarea name="gtm_code_head_tru" rows="10" cols="50" id="gtm_code_head_tru" class="large-text code"><?php echo !empty($opt['gtm_code_head_tru']) ? stripslashes($opt['gtm_code_head_tru']) : '';?></textarea></td>
</tr>
<tr>
<th scope="row"><?php _e('No Script Code','gtm-tag-manager'); ?></th>
<td>
<label for="gtm_code_body_tru"><?php _e('Insert No Script Code here, it will inserted under &lt;body&gt; tag. Use <code>&lt?php do_action("gtm_print_noscript_tag"); ?&gt;</code> under &lt;body&gt; tag.','gtm-tag-manager'); ?></label>
<textarea name="gtm_code_body_tru" rows="10" cols="50" id="gtm_code_body_tru" class="large-text code"><?php echo !empty($opt['gtm_code_body_tru']) ? stripslashes($opt['gtm_code_body_tru']) : '';?></textarea></td>
</tr>
</tbody>

</table>

<p class="submit"><input name="submit" id="submit" class="button button-primary" value="Save Changes" type="submit"></p></form>

</div>