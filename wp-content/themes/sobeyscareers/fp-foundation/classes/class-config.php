<?php
/**
 * FP Foundation configuration
 *
 * @package fp-foundation
 */

namespace fp;

if ( ! defined( 'FP_TD' ) ) {
	define( 'FP_TD', 'FlowPress' );
}

if ( ! defined( 'FP_PREFIX' ) ) {
	define( 'FP_PREFIX', 'fp' );
}

/**
 * Load PHP & JS configuration files, and enable a page to show all constants available and which are loaded.
 */
class Config {

	/**
	 * Create an array of configuration available vs loaded.
	 *
	 * @var $files;
	 */
	public $files = array();

	/**
	 * An array of theme related constants
	 *
	 * @var $theme_constants
	 */
	public $theme_constants = array();

	/**
	 * An array of webops related constants
	 *
	 * @var $webops_constants
	 */
	public $webops_constants = array();

	/**
	 * An array of testing framework related constants
	 *
	 * @var $testing_constants
	 */
	public $testing_constants = array();

	/**
	 * The constructor. Does nothing.
	 */
	public function __construct() {

		$this->theme_constants = array(
			'ALLOWED_ADMIN_BAR_ROLES'     => __( 'Enable non-admin roles to access the admin toolbar. Array.', 'fp' ),
			'ALLOWED_DASHBOARD_ROLES'     => __( 'Enable non-admin roles to access the dashboard. Default admin only. Array.', 'fp' ),
			'BB_ALLOW_CORE_MODULES'       => __( 'Re-able some core BB modules blocked by foundation. Array', 'fp' ),
			'ENABLE_GUTENBERG_THEME'      => __( 'Enable Gutenberg - will create theme options stylesheets.', 'fp' ),
			'EXCLUDE_THEME_ACF_OPTIONALS' => __( 'Exclude an array of theme options: module_background_colour, chevrons, accent_bar_options', 'fp' ),
			'FACEBOOK_SITE_VERIFICATION'  => __( 'Add a Facebook site verification meta tag to the header. Key.', 'fp' ),
			'FP_ALLOW_POSTS'              => __( 'Re-enable the built in POST post type', 'fp' ),
			'GOOGLE_SITE_VERIFICATION'    => __( 'Add a Google site verification meta tag to the header. Key.', 'fp' ),
			'GUTENBERG_ALLOWED_BLOCKS'    => __( 'Add the allowed Gutenberg blocks (core and custom) in an array', 'fp' ),
			'LOAD_THEME_ACF_OPTIONALS'    => __( 'Load theme options: body_background_colour and heading_font_settings. Array.', 'fp' ),
			'THEME_CUSTOM_FONTS'          => __( 'Add an array of additional theme fonts: eg typekit, to select in theme options', 'fp' ),
		);

		$this->webops_constants = array(
			'AUTO_LOGIN_ENABLE'                            => __( 'Enable auto login on localhost via WP Config.', 'fp' ),
			'AUTO_LOGIN_USER'                              => __( 'Auto Login User via WP Config. Key.', 'fp' ),
			'AUTO_LOGIN_PASS'                              => __( 'Auto Login Password via WP Config. Key.', 'fp' ),
			'FP_PLUGIN_GITHUB_TOKEN'                       => __( 'Github token for creating monitored URLs and auto update branches. Key.', 'fp' ),
			'FP_ENABLED_WILDCARD_CORS_IN_VERSION_TRACKING' => __( 'Allow any URL to make the plugin autoupdate package.', 'fp' ),
			'NR_APP_NAME'                                  => __( 'New Relic App Name. Key.', 'fp' ),
			'WP_CLI'                                       => __( 'Enable WP Cli. Used for sending module and plugin data to the dashboard.', 'fp' ),
		);
	}

	/**
	 * Create an output page to view which config options are available and which are loaded.
	 */
	public function show_config() {
		$allowed_html          = wp_kses_allowed_html( 'post' );
		$allowed_html['input'] = array(
			'type'     => true,
			'name'     => true,
			'value'    => true,
			'style'    => true,
			'readonly' => true,
		);
		$allowed_html['style'] = array(
			'type' => true,
		);
		$output                = '<style type="text/css">';
		$output               .= 'body{background:#f8f8f8;color: #333333;font-family: sans-serif;}.wrap{background: #fff;border:1px solid #f0f0f0;margin-left:auto;margin-right:auto;max-width:800px;padding:20px 40px 40px;width: 90%; }input{border:1px solid #d5d5d5;padding: 5px 3px;width:100%;}th{background-color:#c0ebf9;padding: 10px 5px;text-align:left;}table{border: 1px solid #f0f0f0;}thead,tbody,table{width:100%;}thead,tbody{display:table;}tbody tr:nth-child(odd){background-color:#edf8fc;}td{padding: 5px;}h1{border-bottom:2px solid #e5e5e5;padding-bottom: 10px;margin-bottom:20px;}h2{margin:40px 0 20px;}';
		$output               .= '</style>';
		$output               .= '<div class="wrap"><h1>FP Foundation Config</h1>';
		$output               .= '<p>Place the following define statements above the FP Foundation autoload statement in functions.php.</p>';
		if ( is_array( $this->files['php'] ) ) {
			$output .= '<h2>PHP Classes Configuration</h2>';
			$output .= '<table>';
			$output .= '<tbody>';
			$output .= '<tr><th style="width: 390px">Constant (prefix: LOAD_)</th><th>Enabled</th><th>Copy the Definition</th></tr>';
			foreach ( $this->files['php'] as $type => $type_files ) {
				foreach ( $type_files as $file ) {
					$bool    = ( 'not_loaded' === $type ) ? 'False' : 'True';
					$style   = ( 'not_loaded' === $type ) ? 'red' : 'green';
					$output .= '<tr style="color: ' . $style . '">';
					$output .= '<td style="width: 390px">' . str_replace( 'LOAD_', '', $file ) . '</td><td style="padding-left: 10px;width:20%">' . $bool . '</td><td style="width:40%"><input readonly type="text" value="define(\'' . $file . '\', true);" /></td>';
					$output .= '</tr>';
				}
			}
			$output .= '</tbody></table>';
		}
		if ( is_array( $this->files['js'] ) ) {
			$output .= '<h2>JS Assets Configuration</h2>';
			$output .= '<table style="margin-top: 40px;">';
			$output .= '<tbody>';
			$output .= '<tr><th style="width:390px">Constant (prefix: LOAD_JS_)</th><th>Enabled</th><th style="width:190px">Copy the Definition</th></tr>';
			foreach ( $this->files['js'] as $type => $type_files ) {
				foreach ( $type_files as $file ) {
					$bool    = ( 'not_loaded' === $type ) ? 'False' : 'True';
					$style   = ( 'not_loaded' === $type ) ? 'red' : 'green';
					$output .= '<tr style="color: ' . $style . '">';
					$output .= '<td>' . str_replace( 'LOAD_JS_', '', $file ) . '</td><td style="padding-left: 10px;">' . $bool . '</td><td><input readonly type="text" value="define(\'' . $file . '\', true);" /></td>';
					$output .= '</tr>';
				}
			}
			$output .= '</tbody></table>';
		}

		$output .= '<h2>Theming Constants</h2>';
		$output .= '<table style="margin-top: 40px;">';
		$output .= '<tbody>';
		$output .= '<tr><th style="width:390px">Constant</th><th>Description</th><th style="width:190px">Definition</th></tr>';

		foreach ( $this->theme_constants as $t_constant => $t_dxn ) {
			$t_type  = ( stristr( $t_dxn, 'array' ) !== false ) ? 'array()' : ( stristr( $t_dxn, 'Key' ) !== false ? '\'key\'' : 'true' );
			$t_dxn   = str_replace( 'Key.', '', str_replace( 'Array.', '', $t_dxn ) );
			$output .= '<tr>';
			$output .= '<td>' . $t_constant . '</td><td style="padding-left: 10px;">' . $t_dxn . '</td><td><input readonly type="text" value="define( \'' . $t_constant . '\', ' . $t_type . ' );" /></td>';
			$output .= '</tr>';
		}
		$output .= '</tbody></table>';

		$output .= '<h2>WebOps Constants - Define in wp-config.php</h2>';
		$output .= '<table style="margin-top: 40px;">';
		$output .= '<tbody>';
		$output .= '<tr><th style="width:390px">Constant</th><th>Description</th><th style="width:190px">Definition</th></tr>';

		foreach ( $this->webops_constants as $w_constant => $w_dxn ) {
			$w_type  = ( stristr( $w_dxn, 'array' ) !== false ) ? 'array()' : ( stristr( $w_dxn, 'Key' ) !== false ? '\'key\'' : 'true' );
			$w_dxn   = str_replace( 'Key.', '', str_replace( 'Array.', '', $w_dxn ) );
			$output .= '<tr>';
			$output .= '<td>' . $w_constant . '</td><td style="padding-left: 10px;">' . $w_dxn . '</td><td><input readonly type="text" value="define( \'' . $w_constant . '\', ' . $w_type . ' );" /></td>';
			$output .= '</tr>';
		}
		$output .= '</tbody></table>';

		$output .= '</div>'; // End wrap.
		echo wp_kses( $output, $allowed_html );
		exit;
	}

	/**
	 * Add php files to the class property when they're loaded.
	 *
	 * @param string $file is the config file name.
	 * @param string $type is the file type. Optional. Default: php.
	 */
	public function track_loaded( $file, $type = 'php' ) {
		$this->files[ $type ]['loaded'][] = $file;
	}

	/**
	 * Add js files to the class property if they're not loaded.
	 *
	 * @param string $file is the config file name.
	 * @param string $type is the file type. Optional. Default: php.
	 */
	public function track_not_loaded( $file, $type = 'php' ) {
		$this->files[ $type ]['not_loaded'][] = $file;
	}
}

new Config();
