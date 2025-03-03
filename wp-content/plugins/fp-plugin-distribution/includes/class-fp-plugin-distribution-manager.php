<?php

/**
 * The manager-specific functionality of the plugin.
 *
 * @link       www.flowpress.com
 * @since      1.0.0
 *
 * @package    FP_Plugin_Distribution
 * @subpackage FP_Plugin_Distribution/manager
 */

/**
 * The manager-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the manager-specific stylesheet and JavaScript.
 *
 * @package    FP_Plugin_Distribution
 * @subpackage FP_Plugin_Distribution/manager
 * @author     Jonathan Bouganim <jonathan@flowpress.com>
 */
class FP_Plugin_Distribution_Manager {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * Singleton instance.
	 *
	 * @var self
	 */
	private static $instance;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;
	private $slug;
	private $shortSlug;
	private $folderName;
    private $pluginData;
    private $username;
    private $repo;
    private $pluginFile;
    private $pluginFilePath;
    private $githubAPIResult;
    private $githubAPIResults;
    private $accessToken;
    private $pluginActivated;
	private $topVersion;

	private $plugin;

    private $versionExpression;
    private $core;
    private $auto_update;

	private $remove = false;

	/**
	 * Our plugin manager constructor.
	 *
	 * @param array $package_settings
	 * @param object $plugin
	 */
	public function __construct( $package_settings, $plugin = null ) {
		$this->folderName 		= $package_settings['folder_name'];
		$this->shortSlug 		= !empty( $package_settings['plugin-slug'] ) ? $package_settings['plugin-slug'] : $this->folderName;
		
		$this->pluginFile       = $this->get_plugin_file( $this->shortSlug, $this->folderName );
		if ( $this->pluginFile === false ) {
			fp_dist_log("FP Plugin Manager, cannot find plugin `{$this->folderName}/{$this->shortSlug}.php`.", 'error');
			return;
		}

		$this->plugin = $plugin;

		$this->username         	= $package_settings['github-username'];
        $this->repo             	= $package_settings['github-project-name'];
		$this->accessToken      	= FP_PLUGIN_GITHUB_TOKEN;
		if ( !empty( $package_settings['access_token'] ) ) {
			$this->accessToken = defined( $package_settings['access_token'] ) ? constant( $package_settings['access_token'] ) : getenv( $package_settings['access_token'] );
		}
        $this->versionExpression	= $package_settings['version'];
        $this->core             	= $package_settings['core'];
        $this->auto_update      	= $package_settings['auto-update'];

        $this->define_hooks();
	}

	/**
	 * Singleton get.
	 * @return this
	 */
	public static function getInstance( $package_settings ) {
        if (self::$instance === null) {
            self::$instance = new self( $package_settings );
        }
        return self::$instance;
    }

	/**
	 * Define our hooks and filters to override searching in WP Core Repos.
	 * @return void 
	 */
	public function define_hooks() {
		// Hook before the transient is set.
		add_filter( 'pre_set_site_transient_update_plugins', array( $this, 'setTransient' ), 99, 1 );

		// Hook after the transient is set, but before it's returned from 'get', used by wp-db-migrate-pro,...
		// Moved to main plugin, does not need to be called for each plugin.
		// add_filter( 'site_transient_update_plugins', array( $this, 'setTransientPost' ), 99 );

        add_filter( 'plugins_api', array( $this, 'setPluginInfo' ), 99, 3 );
        add_filter( 'upgrader_pre_install', array( $this, 'preInstall' ), 99, 3 );
        add_filter( 'upgrader_post_install', array( $this, 'postInstall' ), 99, 3 );
        add_action( 'upgrader_process_complete', array( $this, 'upgradeComplete' ), 99, 2 );

		add_filter( 'http_request_args', array( $this, 'add_authorization_headers' ), 99, 2 );
		
        //add_filter( 'all_plugins', array( $this, 'filter_cli_plugin_list' ), 10, 3 );
	}

	/**
	 * Add notice if there is an update available but we are not updating due to semvar mismatch.
	 *
	 * @param array $plugin_data
	 * @param array $response
	 * @return void
	 */
	public function modify_plugin_update_message($plugin_data, $response) {
		echo '<br />' . __('FlowPress Plugin Distribution: Semvar mismatch.', 'fp-plugin-distribution');
	}

	/**
	 * We only want to add the authorization headers for github.com URL with zipball format and part of our plugin update.
	 *
	 * @param array $parsed_args
	 * @param string $url
	 * @return array
	 */
	public function add_authorization_headers($parsed_args = [], $url = '' ) {
		if (empty($url)) {
			return $parsed_args;
		}
		
		$parsed_url = parse_url($url);
		if (empty($this->accessToken)) {
			return $parsed_args;
		}
		if ( empty($parsed_url['host']) || empty($parsed_url['path']) || empty($parsed_url['query']) ) {
			return $parsed_args;
		}
		if ( preg_match('#github\.com#i', $parsed_url['host']) && preg_match('#zipball#i', $parsed_url['path']) && preg_match('#fpaccesstoken#i', $parsed_url['query']) ) {
			$parsed_args['headers']['Authorization'] = "token {$this->accessToken}";
		}
		return $parsed_args;
	}

	/**
	 * Returns the plugin file name based on the provided slug.
	 * @param  string $plugin_slug plugin slug
	 * @return string plugin path
	 */
	public function get_plugin_file( $plugin_slug = '', $folderName = '' ) {
		// If plugin slug is same as folder or not.
		$folderName = empty($folderName) ? $plugin_slug : $folderName;
		$file_path = WP_PLUGIN_DIR . "/{$folderName}/{$plugin_slug}.php";
		return file_exists($file_path) ? $file_path : false;
	}

	public function filter_cli_plugin_list( $plugins = array() ) {
		return $plugins;
	}

	/**
	* Get information regarding our plugin from WordPress
	*
	* @return null
	*/
	private function initPluginData()
	{	
		$this->slug = plugin_basename( $this->pluginFile );
		$this->pluginData = get_plugin_data( $this->pluginFile );
		fp_dist_log("initPluginData start for slug: {$this->slug}", 'debug');
	}

	/**
	* Get information regarding our plugin from GitHub
	*
	* @return null
	*/
	private function getRepoReleaseInfo()
	{	
		$this->remove = false;
		$method_name = __FUNCTION__;
		
		fp_dist_log("[{$this->folderName}]{$method_name} start githubResult: " . json_encode($this->githubAPIResult), 'debug');
		if ( ! empty( $this->githubAPIResult ) )
		{
			fp_dist_log("[{$this->folderName}]{$method_name}: githubAPIResult already filled.", 'debug');
			return;
		}

		// If this is a core plugin, let's replace check against 
		if ( $this->core ) {
			$url = "https://api.wordpress.org/plugins/info/1.0/{$this->folderName}.json"; //wp_repo_plugin_data

			fp_dist_log("[{$this->folderName}]{$method_name}: core plugin query WP repository, {$url}", 'debug');
			// Get the results
			$plugin_results= wp_remote_retrieve_body( wp_remote_get( $url ) );
			if ( ! empty( $plugin_results ) )
			{
				$plugin_results = @json_decode( $plugin_results, true );
			}
			fp_dist_log("[{$this->folderName}]{$method_name}: looping through core results", 'debug');

			// Use only the latest release
			if ( is_array( $plugin_results ) && isset($plugin_results['versions']) )
			{
				// If auto-update is off for core, the default WP plugin update will work fine, don't need to do 
				// anything here.
				if ( ! $this->auto_update ) {
					return;
				}
				
				// sort the versions for core top down
				//krsort( $plugin_results['versions'] );
				uasort( $plugin_results['versions'], [__CLASS__,'version_compare'] );
				$plugin_results['versions'] = array_reverse($plugin_results['versions']);
				if ( isset( $plugin_results['versions']['trunk'] ) ) {
					unset( $plugin_results['versions']['trunk'] );
				}

				fp_dist_log("[{$this->folderName}]{$method_name}: autoupdate is on, looping through all versions from WP repo", 'update');
				$count = 0;
				// Otherwise let's compare all the versions in the results
				foreach ( $plugin_results['versions'] as $version => $result ) {
					$count++;
					// Just in case we get a version in the wrong semvar format, let's reformat to ensure it will work with our library.
					// Removes any suffix at the end of the patch unit.
					// Cannot have any leading zeros in minor or patch units.
					if ( preg_match('#^(\d+)\.?(\d*)\.?(\d*)\.?(\d*)#', $version, $matches) ) {
						$major = $matches[1];
						// Remove leading zeroes
						$minor = ! empty( $matches[2] ) ? ltrim($matches[2], '0') : '0';
						$patch = ! empty( $matches[3] ) ? ltrim($matches[3], '0') : '0';
						$beta = ! empty( $matches[4] ) ? ltrim($matches[4], '0') : '';
						$githubVersion = "{$major}.{$minor}.{$patch}{$beta}";
					} else {
						fp_dist_log("[{$this->folderName}]Version {$version} pattern from GitHub is not SemVar formatted for plugin `{$this->slug}`");
						continue;
					}
					fp_dist_log("[{$this->folderName}]{$method_name}: autoupdate is on, check version from github {$githubVersion} vs. {$this->versionExpression}");

					try {
						// Let's do version checking
						$semver = new vierbergenlars\SemVer\version( $githubVersion );
						// based on https://packagist.org/packages/vierbergenlars/php-semver
						$satisfies = $semver->satisfies(new vierbergenlars\SemVer\expression( $this->versionExpression ));
					} catch(vierbergenlars\SemVer\SemVerException $e) {
						fp_dist_log("[{$this->folderName}]{$method_name}: autoupdate is on, catch semver check exception");
						fp_dist_log( $e->getMessage(), 'debug' );
						continue;
					}
					if ($satisfies) {

						if ( $this->plugin->is_critical() ) {
							$critical_version = $this->plugin->get_critical_plugin( $this->folderName );
							if ( empty($critical_version) || (self::version_compare( $critical_version, $githubVersion ) !== 0)) {
								$this->remove = true;
								return;
							}
						}

						fp_dist_log("[{$this->folderName}]{$method_name}: autoupdate is on, satisifies expression, saving this version");
						$this->githubAPIResult = new stdClass();
						$this->githubAPIResult->zipball_url = $result;
						$this->githubAPIResult->tag_name = $version;
						$this->githubAPIResult->body = !empty($plugin_results['sections']['changelog']) ? $plugin_results['sections']['changelog'] : "";
						$this->githubAPIResult->published_at = $plugin_results['last_updated'];
						return;
					}
				}
			}
			return;
		}
		
		// Get the results from our private repos
		$this->githubAPIResults = [];
		$response = apply_filters('fp_plugin_dist_releases_github_api_results', [], $this->accessToken, $this->repo, $this->username );
		if (is_wp_error($response)) {
			fp_dist_log("{$method_name}: cannot fetch releases at {$this->repo}, response: " . $response->get_error_message(), 'error');
			return;
		} 
		
		$this->githubAPIResults = $response;
			
		fp_dist_log("[{$this->folderName}]{$method_name}: looping through results", 'debug');
		// Use only the latest release
		if ( is_array( $this->githubAPIResults ) )
		{
			// If auto-update is off, when we run the update command it will update to highest version
			// auto-update allows us to filter the versioning format
			if ( ! $this->auto_update ) {
				$this->githubAPIResult = $this->githubAPIResults[0];
				fp_dist_log("[{$this->folderName}]{$method_name}: autoupdate is off, using latest version of {$this->repo} which is {$this->githubAPIResult->tag_name}");
				return;
			}
			
			fp_dist_log("[{$this->folderName}]{$method_name}: autoupdate is on, loop through all versions", 'update');
			// Otherwise let's compare all the versions in the results
			$this->topVersion = false;
			foreach ( $this->githubAPIResults as $result ) {

				if ($result->draft) {
					continue;
				}

				// Useless if we don't have a ZIP or it's not from GitHub API
				if (empty($result->zipball_url)) {
					continue;
				}

				if (!preg_match('#^http(s)?:\/\/api\.github\.com#i', $result->zipball_url)) {
					continue;
				}
				
				// Just in case we get a version in the wrong semvar format, let's reformat to ensure it will work with our library.
				// Removes any suffix at the end of the patch unit.
				// Cannot have any leading zeros in minor or patch units.
				if ( preg_match('#^(\d+)\.?(\d*)\.?(\d*)\.?(\d*)#', $result->tag_name, $matches) ) {
					$major = $matches[1];
					// Remove leading zeroes
					$minor = ! empty( $matches[2] ) ? ltrim($matches[2], '0') : '0';
					$patch = ! empty( $matches[3] ) ? ltrim($matches[3], '0') : '0';
					$beta = ! empty( $matches[4] ) ? ltrim($matches[4], '0') : '';
					$githubVersion = "{$major}.{$minor}.{$patch}{$beta}";
					$this->topVersion = empty($this->topVersion) ? $result->tag_name : $this->topVersion;
				} else {
					fp_dist_log("[{$this->folderName}]Version {$result->tag_name} pattern from GitHub is not SemVar formatted for plugin `{$this->slug}`");
					continue;
				}
				fp_dist_log("[{$this->folderName}]{$method_name}: autoupdate is on, check version from github {$githubVersion} vs. {$this->versionExpression}");
				try {
					// Let's do version checking
					$semver = new vierbergenlars\SemVer\version( $githubVersion );
					// based on https://packagist.org/packages/vierbergenlars/php-semver
					$satisfies = $semver->satisfies(new vierbergenlars\SemVer\expression( $this->versionExpression ));
				} catch(vierbergenlars\SemVer\SemVerException $e) {
					fp_dist_log("[{$this->folderName}]{$method_name}: autoupdate is on, catch semver check exception");
					fp_dist_log( $e->getMessage(), 'debug' );
					continue;
				}
				if ($satisfies) {
					if ( $this->plugin->is_critical() ) {
						if ( ! preg_match('#\['.FP_Plugin_Distribution::CRITICAL_FLAG.'\]#i', $result->name) ) {
							$this->remove = true;
							return;
						}
					}

					fp_dist_log("[{$this->folderName}]{$method_name}: autoupdate is on, satisifies expression, saving this version");
					$this->githubAPIResult = $result;
					return;
				}
			}
		} else {
			fp_dist_log("[{$this->folderName}]{$method_name}: cannot find releases at {$this->repo}, response: " . json_encode($this->githubAPIResults), 'error');
		}
	}
 
	/**
	* Push in plugin version information to get the update notification
	*
	* @param  object $transient
	* @return object
	*/
	public function setTransient( $transient )
	{	
		$method_name = __FUNCTION__;
		fp_dist_log("[{$this->folderName}]{$method_name} start", 'debug');

		if ( !$transient ) {
			return $transient;
		}

		// Get plugin & GitHub release information
		$this->initPluginData();
		$this->getRepoReleaseInfo();

		if ($this->remove) {
			$response = $transient->response;
			unset($response[$this->slug]);
			$transient->response = $response;
			set_transient('fp_update_plugins', $transient, 1 * HOUR_IN_SECONDS);
			return $transient;
		}

		// if we got no results back then let's bypass this
		if (  empty( $this->githubAPIResult ) )
		{
			fp_dist_log("[{$this->folderName}]{$method_name}: no gitHub Result, breaking...", 'debug');
			set_transient('fp_update_plugins', $transient, 1 * HOUR_IN_SECONDS);
			return $transient;
		}

		if ( ! isset($this->pluginData['Version']) ) {
			fp_dist_log("[{$this->folderName}]{$method_name}: no pluginData version set, breaking...", 'debug'); 
			set_transient('fp_update_plugins', $transient, 1 * HOUR_IN_SECONDS);
			return $transient;
		}

		$doUpdate = self::version_compare( $this->githubAPIResult->tag_name, $this->pluginData['Version'] );
		fp_dist_log("[{$this->folderName}]{$method_name}: version_compare {$this->githubAPIResult->tag_name} vs. {$this->pluginData['Version']}", 'debug');

		// If there is an update from our repo...
		if ( $doUpdate > 0 )
		{	
			fp_dist_log("[{$this->folderName}]{$method_name}: doUpdate is true, plugin update is available");
			$package = $this->githubAPIResult->zipball_url;

			if ( ! $this->core && ! empty( $this->accessToken ) )
			{
				$package = add_query_arg( array( "fpaccesstoken" => 'header_token' ), $package );
			}
			else {
				fp_dist_log("[{$this->folderName}]{$method_name}: missing GitHub AccessToken");
			}

			// Plugin object
			$obj = new stdClass();
			$obj->slug = $this->slug;
			$obj->new_version = $this->githubAPIResult->tag_name;
			$obj->url = $this->pluginData["PluginURI"];
			$obj->package = $package;

			$transient->response[$this->slug] = $obj;

		}
		// If the versions are the same, less, or it's possible there is a premium update saved here, let's stop it.
		else if ( isset($transient->response[$this->slug]) ) {
			if (!empty($this->topVersion) && ($this->githubAPIResult->tag_name !== $this->topVersion)) {
				// If we have a response and there is a topVersion
				// Lets check if the topversion and saved response is the same, if so, no actual update.
				$doUpdateTop = self::version_compare( $this->topVersion, $this->pluginData['Version'] );
				if ($doUpdate === 0 && $doUpdateTop === 0) {
					unset($transient->response[$this->slug]);
				} else {
					// Plugin object
					$obj = new stdClass();
					$obj->slug = $this->slug;
					$obj->new_version = $this->topVersion;
					$obj->url = $this->pluginData["PluginURI"];
					// Add notice about why it's not being updated in admin.
					add_action("in_plugin_update_message-{$this->slug}", array($this, 'modify_plugin_update_message'), 10, 2 );
					fp_dist_log("[semvar-mismatch][{$this->slug}]: plugin update available but no Semvar match.", 'error');
					//$obj->package = "plugin_update_no_match:".$this->folderName;
					$obj->package = null;
					$transient->response[$this->slug] = $obj;
				}
			} 
			else if ($doUpdate <= 0) {
				$response = $transient->response;
				unset($response[$this->slug]);
				$transient->response = $response;
			}
		}
		set_transient('fp_update_plugins', $transient, 1 * HOUR_IN_SECONDS);
		return $transient;
	}

	/**
	* Push in plugin version information to display in the details lightbox
	*
	* @param  boolean $false
	* @param  string $action
	* @param  object $response
	* @return object
	*/
	public function setPluginInfo( $false, $action, $response )
	{	
		fp_dist_log("setPluginInfo start", 'debug');
		$this->initPluginData();
		$this->getRepoReleaseInfo();
		
		if ( empty( $response->slug ) || ( ( $response->slug != $this->slug ) && ( $response->slug != $this->shortSlug) ) )
		{
			$debug_response_slug = !empty( $response->slug ) ? $response->slug : '';
			$debug_slug = !empty( $this->slug ) ? $this->slug : '';
			$debug_short_slug = !empty( $this->shortSlug ) ? $this->shortSlug : '';
			fp_dist_log("setPluginInfo response->slug {$debug_response_slug} is empty or not same as this->slug {$debug_slug} or this->shortSlug {$debug_short_slug}, breaking...", 'debug');
			return $false;
		}

		$githubAPIResult = $this->githubAPIResult;

		// If we are in CLI and we want to specify a version, let's see if the releases list it
		if ( (defined('WP_CLI') && WP_CLI) ) {
			foreach ( $_SERVER['argv'] as $argument ) {
				if ( preg_match('#^\-\-version=(\d+\.*\d*\.*\d*\.*\d*)#i', $argument, $matches) ) {
					$version	=	$matches[1];
					fp_dist_log("setPluginInfo specifying specific version {$version}", 'debug');
					foreach ( $this->githubAPIResults as $item ) {
						if ($item->tag_name == $version) {
							$githubAPIResult = $item;
							break;
						}
					}
					break;
				} 
			}
		}

		// Add our plugin information
		$response->last_updated = $githubAPIResult->published_at;
		$response->slug = $this->slug;
		$response->plugin_name  = $this->pluginData["Name"];
		$response->version = $githubAPIResult->tag_name;
		$response->author = $this->pluginData["AuthorName"];
		$response->homepage = $this->pluginData["PluginURI"];
		$response->name = $this->pluginData["Name"];

		// This is our release download zip file
		$downloadLink = $githubAPIResult->zipball_url;

		if ( ! $this->core && !empty( $this->accessToken ) )
		{
			$downloadLink = add_query_arg(
				array( "fpaccesstoken" => "header_token" ),
				$downloadLink
			);
		} else {

			fp_dist_log("[{$response->plugin_name}]setPluginInfo: missing GitHub AccessToken");
		}

		$response->download_link = $downloadLink;

		// Create tabs in the lightbox
		$response->sections = array(
			'Description'   => $this->pluginData["Description"],
			'changelog'     => class_exists( "Parsedown" )
			? Parsedown::instance()->parse( $githubAPIResult->body )
			: $githubAPIResult->body
			);

		// Gets the required version of WP if available
		$matches = null;
		preg_match( "/requires:\s([\d\.]+)/i", $githubAPIResult->body, $matches );
		if ( ! empty( $matches ) ) {
			if ( is_array( $matches ) ) {
				if ( count( $matches ) > 1 ) {
					$response->requires = $matches[1];
				}
			}
		}

		// Gets the tested version of WP if available
		$matches = null;
		preg_match( "/tested:\s([\d\.]+)/i", $githubAPIResult->body, $matches );
		if ( ! empty( $matches ) ) {
			if ( is_array( $matches ) ) {
				if ( count( $matches ) > 1 ) {
					$response->tested = $matches[1];
				}
			}
		}

		return $response;
	}

	/**
	* Perform check before installation starts.
	*
	* @param  boolean $true
	* @param  array   $args
	* @return null
	*/
	public function preInstall( $true, $args )
	{	
		fp_dist_log("preInstall start", 'debug');
		// Get plugin information
		$this->initPluginData();

		// Check if the plugin was installed before...
		$this->pluginActivated = is_plugin_active( $this->slug );
	}

	/**
	* Perform additional actions to successfully install our plugin
	*
	* @param  boolean $true
	* @param  string $hook_extra
	* @param  object $result
	* @return object
	*/
	public function postInstall( $true, $hook_extra, $result )
	{
		global $wp_filesystem;

		// Since we are hosted in GitHub, our plugin folder would have a dirname of
		// reponame-tagname change it to our original one:
		$pluginFolder = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . dirname( $this->slug );
		$wp_filesystem->move( $result['destination'], $pluginFolder );
		$result['destination'] = $pluginFolder;

		// Re-activate plugin if needed
		if ( $this->pluginActivated )
		{
			$activate = activate_plugin( $this->slug );
		}
		

		return $result;
	}

	/**
	* Close our log after upgrades are complete.
	*
	* @param  boolean $true
	* @param  string $hook_extra
	* @param  object $result
	* @return object
	*/
	public function upgradeComplete( $wp_upgrader, $hook_extra )
	{

		$result = $wp_upgrader->result;
		fp_dist_log("[{$this->folderName}]postInstall upgradeComplete: update start...", 'update');

		// global $wp_filesystem;

		// // Since we are hosted in GitHub, our plugin folder would have a dirname of
		// // reponame-tagname change it to our original one:
		// $pluginFolder = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . dirname( $this->slug );
		// $wp_filesystem->move( $result['destination'], $pluginFolder );
		// $result['destination'] = $pluginFolder;

		// // Re-activate plugin if needed
		// if ( $this->pluginActivated )
		// {
		// 	$activate = activate_plugin( $this->slug );
		// }

		fp_dist_log("[{$this->folderName}]postInstall upgradeComplete: update complete", 'update');
		fp_dist_log_write_out();
		fp_dist_log_close();

		return $result;
	}

	/**
	 * Since PHP `version_compare` function considers 1 < 1.0 < 1.0.0, we compare two sets of versions, where major/minor/etc. releases are separated by dots.
	 * @param  string $a First version number.
	 * @param  string $b Second version number.
	 * @return integer 
	 */
	public static function version_compare($a, $b) 
	{ 	
		
		// clean up .0 or .0.0 - // we do it twice to clean up 1.0.0 if its there.
		$a = preg_replace('#\.0$#', '', $a );
		$a = preg_replace('#\.0$#', '', $a );
		
		$b = preg_replace('#\.0$#', '', $b );
		$b = preg_replace('#\.0$#', '', $b ); 

	    $a = explode(".", $a); //Split version into pieces and remove trailing .0 
	    $b = explode(".", $b); //Split version into pieces and remove trailing .0 
	    foreach ($a as $depth => $aVal) 
	    { //Iterate over each piece of A 
	        if (isset($b[$depth])) 
	        { //If B matches A to this depth, compare the values 
	            if ($aVal > $b[$depth]) return 1; //Return A > B 
	            else if ($aVal < $b[$depth]) return -1; //Return B > A 
	            //An equal result is inconclusive at this point 
	        } 
	        else 
	        { //If B does not match A to this depth, then A comes after B in sort order 
	            return 1; //so return A > B 
	        } 
	    } 
	    //At this point, we know that to the depth that A and B extend to, they are equivalent. 
	    //Either the loop ended because A is shorter than B, or both are equal. 
	    return (count($a) < count($b)) ? -1 : 0; 
	} 

}
