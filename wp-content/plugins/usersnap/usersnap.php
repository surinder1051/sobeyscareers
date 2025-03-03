<?php
/*
Plugin Name: Usersnap
Plugin URI: http://www.usersnap.com
Description: Usersnap helps website owners to get feedback in form of screenshots from their customers, readers or users.
Version: 4.20
Author: Usersnap
Author URI: http://usersnap.com
License: GPL v2
*/

define('USERSNAP_VERSION', '4.20');
define('USERSNAP_POINTER_VERSION', '0_1');
define('USERSNAP_PLUGIN_URL', plugin_dir_url( __FILE__ ));

if ( is_admin() ){ // admin actions
	add_action( 'admin_init', 'us_register_settings' );
	add_action( 'admin_menu', 'us_plugin_menu' );
	add_action( 'admin_head', 'us_add_js_admin');
} else {
	add_action('wp_head', 'us_add_js');
}

/**
* add js code to webpage
**/
function us_add_js() {
	$options = get_option('usersnap_options');
	//check if we should display usersnap
	$dispUS = false;
	if (isset($options['api-key']) && strlen($options['api-key'])>0) {
		if (!isset($options['visible-for'])) {
			$options['visible-for']="all";
		}
		if ($options['visible-for']=="users") {
			if (is_user_logged_in()) {
				$dispUS = true;
			}
		} else if ($options['visible-for']=="roles") {
			if ( is_user_logged_in() ) {
				$user = new WP_User(get_current_user_id());
				if (!empty($user->roles) && is_array($user->roles)) {
					foreach($user->roles as $role ) {
						if ($dispUS) {
							break;
						}
						foreach($options['visible-for-roles'] as $chrole) {
							if ($chrole == $role) {
								$dispUS = true;
							}
						}
					}
				}
			}
		} else {
			$dispUS = true;
		}
	}

	if ($dispUS) {
		?>
		<script type="text/javascript" data-cfasync="false">
		<?php
			if ( is_user_logged_in() ) {
				$userObj = get_userdata(get_current_user_id());
				?>
				window['_usersnapconfig'] = {emailBoxValue: '<?php echo $userObj->user_email; ?>'};
				<?php
			}
			if (!isset($options['widget_url'])) {
				$options['widget_url'] = get_widget_url($options['api-key']);
				update_option('usersnap_options', $options);
			}
			if (isset($options['widget_url']) && !is_null($options['widget_url'])) {
			?>
				(function() {
					var s = document.createElement('script');
					s.type = 'text/javascript';
					s.async = true;
					s.src = "<?php echo $options['widget_url'] ?>";
					var x = document.getElementsByTagName('head')[0];
					x.appendChild(s);
				})();
				<?php
			}
			?>
		</script>
		<?php
	}
}

/**
 * Determine which widget type to load.
 * returns the correct URL as string
 */
function get_widget_url($apiKey) {
	$apiUrl = null;
	// try platform
	$headers = get_headers("https://widget.usersnap.com/load/" . $apiKey, 1);
	if ($headers[0] == 'HTTP/1.1 200 OK') {
		$apiUrl = "//widget.usersnap.com/load/" . $apiKey;
	} else
	{
		// try global snippet
		$headers = get_headers("https://widget.usersnap.com/global/load/" . $apiKey, 1);
		if ($headers[0] == 'HTTP/1.1 200 OK') {
			$apiUrl = "//widget.usersnap.com/global/load/" . $apiKey;
		} else {
			// invalidate widget_url since API key cannot be resolved by Usersnap endpoints
			$apiUrl = null;
		}
	}
	return $apiUrl;
}

/**
* add js code to admin page
**/
function us_add_js_admin() {
	$options = get_option('usersnap_options');
	//check if we should display usersnap
	if (isset($options['api-key']) &&
	(strlen($options['api-key'])>0) &&
	isset($options['visible-for-backend']) &&
	($options['visible-for-backend']=='backend')) {
		?>
		<script type="text/javascript" data-cfasync="false">
		<?php
			if ( is_user_logged_in() ) {
				$userObj = get_userdata(get_current_user_id());
				?>
				window['_usersnapconfig'] = {emailBoxValue: '<?php echo $userObj->user_email; ?>'};
				<?php
			}

			if (!isset($options['widget_url'])) {
				$options['widget_url'] = get_widget_url($options['api-key']);
				update_option('usersnap_options', $options);
			}
			if (isset($options['widget_url']) && !is_null($options['widget_url'])) {
				?>
				(function() {
					var s = document.createElement('script');
					s.type = 'text/javascript';
					s.async = true;
					s.src = "<?php echo $options['widget_url'] ?>";
					var x = document.getElementsByTagName('head')[0];
					x.appendChild(s);
				})();
				<?php
			}
			?>
		</script>
		<?php
	}
}

/**
* build settings menu
**/

function us_plugin_menu() {
	$page = add_submenu_page('options-general.php', 'Usersnap Settings', 'Usersnap', 'administrator', __FILE__, 'us_option_page');

	add_action('admin_print_styles-'. $page, 'us_add_admin_styles');
}

function us_add_admin_styles() {
	wp_enqueue_style('usersnapAdminStyle');
}

function us_register_settings() {
	register_setting( 'usersnap_options', 'usersnap_options', 'usersnap_options_validate');
	add_settings_section('usersnap_main', '', 'usersnap_section_text', 'usersnap');
	add_settings_field('us-api-key', 'Enter your Usersnap API key', 'usersnap_input_text', 'usersnap', 'usersnap_main');

	//page usersnap_pg_new
	add_settings_section('usersnap_new', 'Create your Usersnap account', 'usersnap_section_new', 'usersnap_pg_new');

	//add css
	wp_register_style('usersnapAdminStyle', plugins_url('style.css', __FILE__));
}

function usersnap_section_text() {
	?>
	<div class="us-box">Manage and configure the button theme and settings on your <a href="https://app.usersnap.com" target="_blank">Usersnap site configuration</a>.</div>
	<?php
}

function usersnap_section_new() {
	?>
	<div class="us-box">Screenshots of your WordPress site will help you improve your site and communicate with your readers. Promised.<br/><a href="https://usersnap.com/wordpress?gat=wpplugin" target="_blank">Learn more about Usersnap here</a> and <a href="https://usersnap.com/signup?gat=wpplugin" target="_blank">try it for free!</a></div>
	<?php
}

function usersnap_input_text() {
	$options = get_option('usersnap_options');
	$key = "";
	if (isset($options['api-key'])) {
		$key = $options['api-key'];
	}
	?>
	<input id="us-api-key" style="width:300px;" name="usersnap_options[api-key]" size="40" type="text" value="<?php echo esc_attr($key); ?>" />
	<?php
	if (strlen($key) > 0) {
		$usProjectUrl = usersnap_project_url($key);
		// do not show link for unresolvable API key
		if (!is_null($usProjectUrl)) {
			?>&nbsp;<a href="<?php echo $usProjectUrl; ?>" target="_blank" class="button">Configure Widget</a><?php
		}
	}
}

function usersnap_project_url($apikey) {
	$options = get_option('usersnap_options');
	if (!isset($options['widget_url'])) {
		$options['widget_url'] = get_widget_url($apikey);
	}
	// choose the correct url depending on the widget_url
	if (is_null($options['widget_url'])) {
		// invalid widget_url because of unresolvable API key
		return null;
	} elseif (strpos($options['widget_url'], '/global')) {
		// global snippet, link to dashboard
		return "https://app.usersnap.com/#/";
	} elseif (strpos($options['widget_url'], 'widget.')) {
		// platform, link to configuration page
		return "https://app.usersnap.com/l/projects/" . $apikey . "/configuration";
	}
}

function usersnap_options_validate($input) {
	if (!isset($input["usersnap-api-requ"])) {
		$input["usersnap-api-requ"] = false;
	}

	if (!isset($input["message"])) {
		$input["message"] = "";
	}

	if (!isset($input["error"])) {
		$input["error"] = false;
	}

	if (!isset($input['visible-for-backend'])) {
		$input['visible-for-backend']="no";
	}
	return $input;
}

function us_option_tab_menu($tabs, $current = "newusersnap") {
	?>
	<div id="icon-usersnap" class="icon32"><br></div>
	<h2 class="nav-tab-wrapper">
	<?php
	foreach( $tabs as $tab => $name ){
		$class = ( $tab == $current ) ? ' nav-tab-active' : '';
		?>
		<a class='nav-tab<?php echo $class; ?>' href='?page=usersnap/usersnap.php&tab=<?php echo $tab; ?>'><?php echo $name; ?></a>
		<?php
	}
	?>
   	</h2>
   	<?php
}

function us_create_visibility_form() {
	$options = get_option('usersnap_options');
	if (!isset($options['visible-for'])) {
		$options['visible-for']="all";
	}
	if (!isset($options['visible-for-roles'])) {
		$options['visible-for-roles']=array();
	}
	if (!isset($options['visible-for-backend'])) {
		$options['visible-for-backend']="backend";
	}
	?>
	<table class="form-table">
		<tr>
		<th scope="row">
			   Enable Usersnap for:
		</th>
		<td>
		  <fieldset>
			  <label for="us-visible-for-all">
			  	<input type="radio" <?php echo ($options['visible-for']=="all"?"checked":"")?> name="usersnap_options[visible-for]" value="all" id="us-visible-for-all"/> <span>All Visitors</span>
			  </label>
			  <br>
			  <label for="us-visible-for-users">
			  	<input type="radio" <?php echo ($options['visible-for']=="users"?"checked":"")?> name="usersnap_options[visible-for]" value="users" id="us-visible-for-users"/> <span>Only users who are signed in</span>
			  </label>
			  <br>
			  <label for="us-visible-for-roles">
			  	<input type="radio" <?php echo ($options['visible-for']=="roles"?"checked":"")?> name="usersnap_options[visible-for]" value="roles" id="us-visible-for-roles"/> <span>Only users with a specific role</span>
			  </label>
		  </fieldset>

		  <div class="form-table" id="us-visible-roles">
			<?php
			$wp_roles = new WP_Roles();
			$roles = $wp_roles->get_names();
			$ctn = 0;
			$check = false;

			foreach ($roles as $role_value => $role_name) {
				$check = false;
				foreach($options['visible-for-roles'] as $lurole) {
					if ($lurole === $role_value) {
						$check = true;
						break;
					}
				}
				?>
				<p>
				  <input type="checkbox" <?php echo ($check?"checked":"")?> name="usersnap_options[visible-for-roles][]" value="<?php echo $role_value; ?>" id="us-visible-for-role-<?php echo $ctn;?>"/>
				  <label for="us-visible-for-role-<?php echo $ctn;?>"><?php echo $role_name; ?></label>
				</p>
				<?php
				$ctn++;
		  	}
			?>
			</div>

		</td>
		<tr>
			<th scope="row">
				   Visibility Settings:
			</th>
			<td>
				<p>
					<input type="checkbox" <?php echo ($options['visible-for-backend']=="backend"?"checked":"")?> name="usersnap_options[visible-for-backend]" value="backend" id="us-visible-for-backend"/>
					<label for="us-visible-for-backend">Visible in Administration Backend</label>
				</p>
			</td>
		</tr>
	</table>
	<?php
}


function us_option_page() {
	if (!current_user_can('administrator'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	$options = get_option('usersnap_options');
	$tabs = array();
	if (isset($options['api-key']) && strlen($options['api-key'])>0) {
		$tabs = array(
			'configure' => 'Configure'
		);
		$currenttab = "configure";
		if (isset($_GET['tab']) && $_GET['tab'] == "newusersnap") {
			$_GET['tab'] = $currenttab;
		}
	} else {
		$tabs = array(
			'newusersnap' => 'Setup Usersnap',
			'configure' => 'Configure'
		);
		$currenttab = "newusersnap";
	}
	?>
	<div class="wrap">

	<h2 class="us-headline"><?php _e( 'Settings' ); ?> › Usersnap</h2>

	<?php
	if (isset($_GET['tab'])) {
		$currenttab = $_GET['tab'];
	}

	if(count($tabs) > 1) us_option_tab_menu($tabs, $currenttab);

	?>
	<?php
	if (isset($options["error"]) && $options["error"] == true) {
		?><div class="error below-h2"><p><?php echo $options["message"]; ?></p></div><?php
	} ?>
	<form method="post" action="options.php" id="us-settings-form">
	<?php settings_fields( 'usersnap_options' ); ?>
	<?php
	switch($currenttab) {
		case 'newusersnap':
			?>
			<h3>Already have a Usersnap account?</h3>
			<p>Click the configure tab above.</p>
			<?php
			do_settings_sections('usersnap_pg_new');
			?>
			<?php
			break;
		case 'configure':
			do_settings_sections('usersnap');
			us_create_visibility_form();
			?>
			<p class="submit">
				<input type="submit" id="us-btn-save" name="us_btn_save" class="button-primary" value="<?php _e('Save Changes') ?>" />
				<input type="button" class="button" id="us-reset-settings" value="<?php _e('Reset Settings') ?>" />
			</p>
			<script type="text/javascript">
			function domReady(fn) {
				document.addEventListener("DOMContentLoaded", fn);
				if (document.readyState === "interactive" || document.readyState === "complete" ) {
					fn();
				}
			};

			function renderErrorMessageBanner() {
				// create the error message and add it into the DOM
				var h2El = document.querySelector('.wrap h2.us-headline');
				var divEl = document.createElement('div');
				var pEl = document.createElement('p');
				var textNode = document.createTextNode('<?php _e('Your API key is not valid, please check again!') ?>');
				pEl.appendChild(textNode);
				divEl.appendChild(pEl);
				divEl.classList.add("error");
				divEl.classList.add("below-h2");
				divEl.style.marginTop = "1em";
				var parentNode = h2El.parentNode;
				parentNode.insertBefore(divEl, h2El.nextSibling);
			};

			domReady(function() {
				// validate settings form API key input and handle error display
				document.querySelector('#us-settings-form').addEventListener('submit', function(evt) {
					var apiKeyInputField = document.querySelector('#us-api-key');
					if (apiKeyInputField.value !== '') {
						var s = /^[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i;
						if (!s.test(apiKeyInputField.value)) {
							apiKeyInputField.focus();
							evt.preventDefault();
							renderErrorMessageBanner();
							return false;
						}
					}
				})

				// reset all settings form inputs to their defaults
				document.querySelector('#us-reset-settings').addEventListener('click', function() {
					document.querySelector('#us-api-key').value = '';
					document.querySelector('#us-visible-for-all').checked = true;
					document.querySelector('#us-visible-for-backend').checked = true;
					document.querySelectorAll('#us-visible-roles input[type=checkbox]:checked').forEach(function(item) {item.checked = false})
					document.querySelector('#us-btn-save').click();
				})

				// show user roles checkboxes in case "specific roles" option is selectet, otherwise hide
				document.querySelectorAll('#us-settings-form input[type=radio]').forEach(function(item) { arguments
					item.addEventListener('change', function(evt) {
						var radio = document.querySelector('#us-visible-for-roles')
						if (radio.checked === true) {
							document.querySelector('#us-visible-roles').style.display = 'block';
						} else {
							document.querySelector('#us-visible-roles').style.display = 'none';
						}
					})
				})

				// show user roles by default in case the "specific roles" option is preselected
				var radio = document.querySelector('#us-visible-for-roles')
				if (radio.checked === true) {
					document.querySelector('#us-visible-roles').style.display = 'block';
				}

				<?php
				$options = get_option('usersnap_options');
				if (isset($options['api-key'])) {
					$key = $options['api-key'];
					if (!empty($key) && is_null(usersnap_project_url($key))) {
						?>
						// show permanent error message for invalid widget_url because of unresolvable API key
						renderErrorMessageBanner();
						<?php
					}
				}
				?>
			});

			</script>
			<?php
			break;
	}
	?>
	</form>
	</div>
	<?php
}



//Show Setup bubble and Info Bubble

add_action( 'admin_enqueue_scripts', 'usersnap_admin_pointer_header' );

function usersnap_admin_pointer_header() {
	if ( usersnap_admin_pointer_check() ) {
		add_action( 'admin_print_footer_scripts', 'usersnap_admin_pointer_footer' );

		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_style( 'wp-pointer' );
	}
}

function usersnap_admin_pointer_check() {
	$pointer = 'usersnap_admin_pointer' . USERSNAP_POINTER_VERSION . '_new_items';
	$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );

	//don't show the pointe if we are in the usersnap settings page
	if(!is_admin()) return false;
	if(get_current_screen()->base == 'settings_page_usersnap/usersnap') {
		$options = get_option('usersnap_options');

		//remove the pointer if usersnap has been set up
		if (isset($options['api-key']) && strlen($options['api-key']) > 0 ) {
			$pointer = 'usersnap_admin_pointer' . USERSNAP_POINTER_VERSION . '_new_items';
			$dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
			if(! in_array( $pointer, $dismissed ) ) {
				array_push($dismissed, $pointer);
				$dismissed = implode( ',', $dismissed );
				$users = get_users();
				update_user_meta( get_current_user_id(), 'dismissed_wp_pointers', $dismissed );
			}

		}

		return false;
	}


	$admin_pointers = usersnap_admin_pointer();
	foreach ( $admin_pointers as $pointer => $array ) {
		if ( $array['active'] ) {
			return true;
		}
	}
}

function usersnap_admin_pointer_footer() {
   $admin_pointers = usersnap_admin_pointer();
   ?>
<script type="text/javascript">
/* <![CDATA[ */
( function($) {
	<?php
	foreach ( $admin_pointers as $pointer => $array ) {
		if ( $array['active'] ) {
		?>
			$( '<?php echo $array['anchor_id']; ?>' ).pointer( {
				content: '<?php echo $array['content']; ?>',
				position: {
				edge: '<?php echo $array['edge']; ?>',
				align: '<?php echo $array['align']; ?>'
			},
			close: function() {
				$.post( ajaxurl, {
					pointer: '<?php echo $pointer; ?>',
					action: 'dismiss-wp-pointer'
				} );
			}
			} ).pointer( 'open' );
		<?php
	}
   }
   ?>
} )(jQuery);
/* ]]> */
</script>
   <?php
}

function usersnap_admin_pointer() {
	$options = get_option('usersnap_options');
   $dismissed = explode( ',', (string) get_user_meta( get_current_user_id(), 'dismissed_wp_pointers', true ) );
   $version = USERSNAP_POINTER_VERSION; // version of this pointer - change to view again after update
   $prefix = 'usersnap_admin_pointer' . $version . '_';

   if (isset($options['api-key']) && strlen($options['api-key'])>0) {
   		$new_pointer_content = '<h3>' . __( 'Usersnap Settings have moved.' ) . '</h3>';
   		$new_pointer_content .= '<p>' . __( 'You can now find your Usersnap settings in the settings menu.' ) . '</p>';
   } else {
	   $new_pointer_content = '<h3>' . __( 'Set up Usersnap!' ) . '</h3>';
	   $new_pointer_content .= '<p>' . __( 'Set up your account and API key to get Usersnap up and running!' ) . '</p>';
   }

   return array(
		$prefix . 'new_items' => array(
			'content' => $new_pointer_content,
			'anchor_id' => '#menu-settings',
			'edge' => 'left',
			'align' => 'left',
			'active' => ( ! in_array( $prefix . 'new_items', $dismissed ) )
		),
   );
}
