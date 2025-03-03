<?php

/**
 * The file that defines the core component class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       www.flowpress.com
 * @since      1.0.0
 *
 * @package    FP_Component_Distribution
 * @subpackage FP_Component_Distribution/includes
 */

/**
 * The core component class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this component as well as the current
 * version of the component.
 *
 * @since      1.0.0
 * @package    FP_Component_Distribution
 * @subpackage FP_Component_Distribution/includes
 * @author     Jonathan Bouganim <jonathan@flowpress.com>
 */
class FP_Component_Distribution {

	protected $accessToken;
	protected $githubUser;

	protected $is_critical = false;
	protected $critical_components = [];

	public $fp_foundation_theme_folder = 'fp-foundation';

	public $action_counts = [];

	/**
	 * The unique identifier of this component.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $component_name    The string used to uniquely identify this component.
	 */
	protected $component_name;

	/**
	 * The current version of the component.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the component.
	 */
	protected $version;

	/**
	 * The listing of all the packages this component manages.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $packages    The listing of all the packages this component manages.
	 */
	protected $packages;

	/**
	 * The listing of all the package settings this component manages.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $packages    The listing of all the package settings this component manages.
	 */
	protected $settings;

	/**
	 * Singleton instance.
	 *
	 * @var self
	 */
	private static $instance;
    
    /**
	 * Singleton get.
	 * @return this
	 */
	public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

	/**
	 * Define the core functionality of the component.
	 *
	 * Set the component name and the component version that can be used throughout the component.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'FP_COMPONENT_DIST_VERSION' ) ) {
			$this->version = FP_COMPONENT_DIST_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->component_name = 'fp-component-distribution';

		if ( !defined( 'FP_COMPONENT_PACKAGE_NAME' ) || empty(FP_COMPONENT_PACKAGE_NAME) ) {
			$error_msg = "Constant - FP_COMPONENT_PACKAGE_NAME not defined.";
			fp_comp_dist_log($error_msg, 'error');
			return;
		}	

		$error_msg = "Constant - FP_PLUGIN_GITHUB_TOKEN not defined.";
		if ( !defined( 'FP_PLUGIN_GITHUB_TOKEN' ) || empty(FP_PLUGIN_GITHUB_TOKEN) ) {
			fp_comp_dist_log($error_msg, 'error');
			return;
		} else {
			$this->accessToken = FP_PLUGIN_GITHUB_TOKEN;
		}

		$this->githubUser = defined( 'FP_PLUGIN_GITHUB_USER') && !empty(FP_PLUGIN_GITHUB_USER) ? FP_PLUGIN_GITHUB_USER : false;
		
		$package_json = apply_filters('fp_component_dist_json_path', WP_CONTENT_DIR . "/" . FP_COMPONENT_PACKAGE_NAME . ".json" );
		if ( ! file_exists($package_json) ) {
			$error_msg = "Could not find component package manager JSON with path `{$package_json}`, attempting to create a sample...";
			fp_comp_dist_log($error_msg, 'error');

			// Create sample JSON if it doesn't exist
			$blank_json_path = FP_PLUGIN_PATH . FP_COMPONENT_PACKAGE_NAME . '.json';
			$blank_json = file_get_contents( $blank_json_path );
			file_put_contents( $package_json, $blank_json );
			if ( ! file_exists($package_json) ) {
				$error_msg = "Could not create blank JSON in wp-content: `{$package_json}`.";
				fp_comp_dist_log($error_msg, 'error');
				return;
			}
		}

		// Quitting if we still don't have a JSON file to read.
		$error_msg = "Could not find component package manager JSON with path `{$package_json}`...";
		if ( ! file_exists($package_json) ) {
			fp_comp_dist_log($error_msg, 'error');
			return;
		}

		// If we are in CLI and we want to specify a version, let's see if the releases list it
		if ( (defined('WP_CLI') && WP_CLI) ) {
			foreach ( $_SERVER['argv'] as $argument ) {
				if ( preg_match('#^\-\-'.FP_Plugin_Distribution::CRITICAL_FLAG.'#i', $argument, $matches) ) {
					fp_dist_log("Specifying critical releases only", 'debug');
					$this->is_critical = true;
					
				} 
			}
		}

		$this->settings = json_decode( file_get_contents( $package_json ), true );
		$validation_failed = false;
		$validation_fields = ['packages', 'platforms', 'name'];
		foreach($validation_fields as $validation_field) {
			if ( ! isset( $this->settings[ $validation_field ] ) ) {
				$error_msg = "Missing '{$validation_field}' field in package JSON path `{$package_json}`.";
				fp_comp_dist_log($error_msg, 'error');
				$validation_failed[] = $validation_field;
			}
		}
		
		if (!empty($validation_failed)) {
			$fields = implode(", ", $validation_failed);
			$error_msg = "Package JSON validation failed, invalid fields: {$fields}.";
			fp_comp_dist_log($error_msg, 'error');
			return;
		}
		$this->packages = $this->settings['packages'];
	}

	/**
	 * Get critical status.
	 *
	 * @return boolean
	 */
	public function is_critical() {
		return apply_filters('fp_plugin_dist_is_critical', $this->is_critical);
	}

	/**
	 * Get critical plugin version.
	 *
	 * @return string|boolean
	 */
	public function get_critical_component( $slug = '' ) {
		return isset( $this->critical_components[ $slug ] )	? $this->critical_components[ $slug ] : false;
	}

	/**
	 * List all components and available updates
	 *
	 * @param boolean $check_update
	 * @param boolean $show_available_only
	 * @return array|boolean
	 */
	public function list_components( $check_update = false, $show_available_only = true ) {
		if ( (defined('WP_CLI') && WP_CLI) && ! in_array('cli', $this->settings['platforms'] ) )
			return;
		
		if ( ! (defined('WP_CLI') && WP_CLI) ) {
			fp_comp_dist_log("load_packages: not defined CLI, breaking...", 'error');
			return;	
		}
		
		$all_components = $this->get_components();
		$component_list = [];
		
		foreach($all_components as $component_slug => $component) :
			if (!empty($single_component_name) && $single_component_name !== $component_slug) {
				continue;
			}
			$folder_name = !empty( $component['basedir'] ) ? $component['basedir'] : '';
			$doUpdate = false;
			if (empty($folder_name)) {
				fp_comp_dist_log("Cannot find component basedir {$component_slug}, skipping...", 'error');
				continue;
			}
			if ( empty( $component['version'] ) ) {
				fp_comp_dist_log("Component {$component_slug} is missing version data, update fp-foundation/component class or add version details in component config, skipping...", 'error');
				continue;
			}
			
			// Loop through each component
			// Check if the git repo exists, get the releases, get latest version
			// If it's in the list of packages, compare versions until we find one that matches semvar regex
			// If it's not, do version compare and update if greater on github
			if ($check_update) {
				$release = $this->getRepoReleases($component_slug);
			} else {
				$release = [];
			}
			
			if (empty($release)) {
				$this->update_action_count('no releases');
				fp_comp_dist_log("No viable release found for {$component_slug}, skipping...", 'debug');
			}

			if (!empty($release->zipball_url)) {
				// If it's no empty, it means it already passed our version expression, so we want to update.
				$doUpdate = FP_Plugin_Distribution_Manager::version_compare( $release->tag_name, $component['version'] );

				// If there is an update from our repo...
				if ( $doUpdate > 0 ) {
					
				} else {
					$this->update_action_count('no update');
				}
			}
			if ($check_update) {
				if ($show_available_only) {
					if ($doUpdate > 0) {
						$component_list[] = [
							'name' => $component_slug,
							'version' => $component['version'],
							'update' => $doUpdate > 0 ? 'available' : 'none',
							'update_version' => $doUpdate > 0 ? $release->tag_name : 'none',
						];
					}
				} else {
					$component_list[] = [
						'name' => $component_slug,
						'version' => $component['version'],
						'update' => $doUpdate > 0 ? 'available' : 'none',
						'update_version' => $doUpdate > 0 ? $release->tag_name : 'none',
					];
				}
				
			} else {
				$component_list[] = [
					'name' => $component_slug,
					'version' => $component['version'],
					'update' => 'n/a',
					'update_version' => 'n/a',
				];
			}
			
		endforeach;
		
		return $component_list;
	}

	/**
	 * Update all components.
	 *
	 * @param string $single_component_name
	 * @param boolean $dry_run
	 * @return array|boolean
	 */
	public function update_components( $single_component_name = '', $dry_run = false ) {
		if ( (defined('WP_CLI') && WP_CLI) && ! in_array('cli', $this->settings['platforms'] ) )
			return;
		
		if ( ! (defined('WP_CLI') && WP_CLI) ) {
			fp_comp_dist_log("load_packages: not defined CLI, breaking...", 'error');
			return;	
		}
		
		$update_list = [];
		$all_components = $this->get_components();
		$total_components = count($all_components);
		if (empty($single_component_name)) {
			fp_comp_dist_log("Looping through {$total_components} components", 'line');
		} else {
			if ( ! isset($all_components[ $single_component_name ]) ) {
				return null;
			}
		}

		$index = 0;
		foreach($all_components as $component_slug => $component) :
			$index++;
			if (!empty($single_component_name) && $single_component_name !== $component_slug) {
				continue;
			}

			$folder_name = !empty( $component['basedir'] ) ? $component['basedir'] : '';
			$did_update = false;
			if (empty($folder_name)) {
				$this->update_action_count('skipped');
				fp_comp_dist_log("Cannot find component basedir {$component_slug}, skipping...", 'error');
				continue;
			}
			if ( empty( $component['version'] ) ) {
				$this->update_action_count('skipped');
				fp_comp_dist_log("Component {$component_slug} is missing version data, update fp-foundation/component class or add version details in component config, skipping...", 'error');
				continue;
			}
			
			// Loop through each component
			// Check if the git repo exists, get the releases, get latest version
			// If it's in the list of packages, compare versions until we find one that matches semvar regex
			// If it's not, do version compare and update if greater on github
			fp_comp_dist_log("Checking releases for {$component_slug} {$index}/{$total_components}.", 'line');
			$release = $this->getRepoReleases($component_slug);
			if (empty($release)) {
				$this->update_action_count('no releases');
				fp_comp_dist_log("No viable release found for {$component_slug}, skipping...", 'debug');
				continue;
			}

			if (!empty($release->zipball_url)) {
				
				// If it's no empty, it means it already passed our version expression, so we want to update.
				$doUpdate = FP_Plugin_Distribution_Manager::version_compare( $release->tag_name, $component['version'] );

				fp_comp_dist_log( sprintf("[%s] found release from version %s to version %s...", $component_slug, $component['version'], $release->tag_name) , 'line');
				// If there is an update from our repo...
				if ( $doUpdate > 0 ) {

					if ( $this->is_critical() ) {
						if ( preg_match('#\['.FP_Plugin_Distribution::CRITICAL_FLAG.'\]#i', $release->name) ) {
							// do nothing
						} else {
							continue;
						}
					}

					$status = 'Available';
					fp_comp_dist_log( sprintf("[%s] found update from version %s to version %s, attempting to update...", $component_slug, $component['version'], $release->tag_name) , 'line');
					if (!$dry_run) {
						$did_update = $this->update_component($component_slug, $folder_name, $release->zipball_url);
						if ( is_wp_error( $did_update ) ) {
							$status = 'Failed';
							$this->update_action_count('failed');
							fp_comp_dist_log( sprintf("Component Update Failed `%s`: %s", $component_slug, $did_update->get_error_message() ) , 'error');
							continue;
						} else {
							$status = 'Updated';
							$this->update_action_count('updated');
							fp_comp_dist_log( sprintf("[%s]successfully updated from version %s to version %s", $component_slug, $component['version'], $release->tag_name), 'update');
						}
					}

					$relative_path = preg_match('#(\/wp\-content\/.*)#i', $folder_name, $matches) ? $matches[1] : $folder_name;

					$update_list[] = [
						'name' => $component_slug,
						'old_version' => $component['version'],
						'new_version' => $release->tag_name,
						'status' => $status,
						'path' => $relative_path,
					];
				} else {
					$this->update_action_count('no update');
				}
				
			}
		endforeach;
		
		return $update_list;
	}

	/**
	 * Update the fp-foundation library.
	 *
	 * @param boolean $dry_run
	 * @return array|boolean
	 */
	public function update_fp_foundation( $dry_run = false ) {
		if ( (defined('WP_CLI') && WP_CLI) && ! in_array('cli', $this->settings['platforms'] ) )
			return;
		
		if ( ! (defined('WP_CLI') && WP_CLI) ) {
			fp_comp_dist_log("load_packages: not defined CLI, breaking...", 'error');
			return;	
		}

		$current_version = defined('FP_FOUNDATION_VERSION') && !empty(FP_FOUNDATION_VERSION) ? FP_FOUNDATION_VERSION : false;

		fp_comp_dist_log("Checking releases for {$this->fp_foundation_theme_folder}.", 'line');
		if (empty($current_version)) {
			fp_comp_dist_log("Cannot update {$this->fp_foundation_theme_folder}, no version constant found", 'error');
			return;
		}

		$basedir = get_stylesheet_directory() . "/{$this->fp_foundation_theme_folder}";
		$release = $this->getRepoReleases($this->fp_foundation_theme_folder);
		$update_list = [];

		if (empty($release)) {
			$this->update_action_count('no releases');
			fp_comp_dist_log("No viable release found for {$this->fp_foundation_theme_folder}, skipping...", 'debug');
			return $update_list;
		}

		if (!empty($release->zipball_url)) {
			
			// If it's no empty, it means it already passed our version expression, so we want to update.
			$doUpdate = FP_Plugin_Distribution_Manager::version_compare( $release->tag_name, $current_version );

			fp_comp_dist_log( sprintf("[%s] found release from version %s to version %s...", $this->fp_foundation_theme_folder, $current_version, $release->tag_name) , 'line');
			// If there is an update from our repo...
			if ( $doUpdate > 0 ) {
				fp_comp_dist_log( sprintf("[%s] found update from version %s to version %s, attempting to update...", $this->fp_foundation_theme_folder, $current_version, $release->tag_name) , 'line');
				$status = 'Available';
				if (!$dry_run) {
					$did_update = $this->update_component($this->fp_foundation_theme_folder, $basedir, $release->zipball_url, false);

					if ( is_wp_error( $did_update ) ) {
						$status = 'Failed';
						$this->update_action_count('failed');
						fp_comp_dist_log( sprintf("Component Update Failed `%s`: %s", $this->fp_foundation_theme_folder, $did_update->get_error_message() ) , 'error');
						return $update_list;
					} else {
						$status = 'Updated';
						$this->update_action_count('updated');
						fp_comp_dist_log( sprintf("[%s]successfully updated from version %s to version %s", $this->fp_foundation_theme_folder, $current_version, $release->tag_name), 'update');
					}
				}

				$relative_path = preg_match('#(\/wp\-content\/.*)#i', $basedir, $matches) ? $matches[1] : $basedir;
				
				$update_list[] = [
					'name' => $this->fp_foundation_theme_folder,
					'old_version' => $current_version,
					'new_version' => $release->tag_name,
					'status' => $status,
					'path' => $relative_path,
				];
			} else {
				$this->update_action_count('no update');
			}
		}	
		
		return $update_list;
	}

	/**
	 * Helper function to update action count.
	 *
	 * @param string $type
	 * @return void
	 */
	public function update_action_count( $type = 'skipped' ) {
		if ( ! isset( $this->action_counts[ $type ] ) ) {
			$this->action_counts[ $type ] = 1;
		} else {
			$this->action_counts[ $type ] += 1;
		}
	}

	/**
	 * Update a component folder.
	 *
	 * @param string $component_name
	 * @param string $basedir - current component directory.
	 * @param string $package - ZIP of component.
	 * @param boolean $should_include_skip_files - include component skip files or not.
	 * @return boolean|WP_Error
	 */
	public function update_component( $component_name = '', $basedir = '', $package = '', $should_include_skip_files = true ) {
		
		require_once ABSPATH . 'wp-admin/includes/file.php';
		//require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
		WP_Filesystem();
		global $wp_filesystem;
		$upgrade_folder = $wp_filesystem->wp_content_dir() . 'upgrade/';

		// Clean up contents of upgrade directory beforehand.
		$upgrade_files = $wp_filesystem->dirlist( $upgrade_folder );
		if ( ! empty( $upgrade_files ) ) {
			foreach ( $upgrade_files as $file ) {
				$wp_filesystem->delete( $upgrade_folder . $file['name'], true );
			}
		}
		// Create our working dir for new component files
		$working_dir = $upgrade_folder . "components/{$component_name}";

		// Clean up working directory.
		if ( $wp_filesystem->is_dir( $working_dir ) ) {
			$wp_filesystem->delete( $working_dir, true );
		}
		fp_comp_dist_log( sprintf("[%s] Downloading the package from %s...", $component_name, $package), 'line');
		// Download it to tmp location
		$download_file = $this->download_package( $package );

		if ( is_wp_error( $download_file ) ) {
			return new WP_Error( 'download_failed', $this->strings['download_failed'], $download_file->get_error_message() );
		}

		fp_comp_dist_log( sprintf("[%s] Unpacking the update to %s...", $component_name, $working_dir), 'line');
		// Unzip package to working directory and delete tmp zip.
		$result = unzip_file( $download_file, $working_dir );
		unlink( $download_file );

		if ( is_wp_error( $result ) ) {
			$wp_filesystem->delete( $working_dir, true );
			if ( 'incompatible_archive' === $result->get_error_code() ) {
				return new WP_Error( 'incompatible_archive', $this->strings['incompatible_archive'], $result->get_error_data() );
			}
			return $result;
		}
		// ZIP usually comes with a parent folder of the release details, let's go down a level.
		$folder_list = $wp_filesystem->dirlist( $working_dir );
		// Should only be one parent folder for the release...
		if ( empty($folder_list) || count($folder_list) !== 1 ) {
			return new WP_Error( 'missing_folder_parent', $this->strings['missing_folder_parent'], "Missing parent folder from release" );	
		}

		$main_folder_name = array_key_first($folder_list);
		// Exclude a few files from copying over.
		$component_release_folder = "{$working_dir}/{$main_folder_name}";
		$skip_files = $should_include_skip_files ? ["{$component_name}_theme.scss", '.gitignore'] : [];
		fp_comp_dist_log( sprintf("[%s] Installing the latest version to %s...", $component_name, $basedir), 'line');
		$did_copy = copy_dir($component_release_folder, $basedir, $skip_files );
		if ($did_copy) {
			$wp_filesystem->delete( $upgrade_folder . "components", true );
		}
		return $did_copy;
	}

	/**
	 * Helper to download the GitHub release ZIP with adding an accessToken header.
	 *
	 * @param string $url
	 * @return string|WP_Error
	 */
	public function download_package( $url = '' ) {
		// WARNING: The file is not automatically deleted, the script must unlink() the file.
		if ( ! $url ) {
			return new WP_Error( 'http_no_url', __( 'Invalid URL Provided.' ) );
		}

		$url_filename = basename( parse_url( $url, PHP_URL_PATH ) );

		$tmpfname = wp_tempnam( $url_filename );
		if ( ! $tmpfname ) {
			return new WP_Error( 'http_no_file', __( 'Could not create Temporary file.' ) );
		}
		
		// Make sure we get permission to download this file.
		$headers = [
			'Authorization' => "token {$this->accessToken}"
		];

		$response = wp_safe_remote_get(
			$url,
			array(
				'timeout'  => 300,
				'stream'   => true,
				'filename' => $tmpfname,
				'headers'  => $headers,
			)
		);

		if ( is_wp_error( $response ) ) {
			unlink( $tmpfname );
			return $response;
		}

		$response_code = wp_remote_retrieve_response_code( $response );
		if ( 200 != $response_code ) {
			unlink( $tmpfname );
			return new WP_Error( 'http_404', trim( wp_remote_retrieve_response_message( $response ) ), [] );
		}
		return $tmpfname;
	}

	/**
	 * Helper to fetch the releases from the GitHub repo.
	 *
	 * @param string $component_name
	 * @return object|boolean
	 */
	public function getRepoReleases( $component_name = '' ) {
		$method_name = __FUNCTION__;

		// Query the FP Dash/GitHub API
		$package = isset( $this->packages[$component_name] ) ? $this->packages[$component_name] : [];
		$repo_name = !empty( $package['github-project-name'] ) ? $package['github-project-name'] : $this->get_component_repo_name($component_name);
		$github_user = !empty( $package['github-username'] ) ? $package['github-username'] : $this->githubUser;
		
		$auto_update = isset( $package['auto-update'] ) ? $package['auto-update'] : true;
		$versionExpression = isset( $package['version'] ) ? $package['version'] : '*.*.*';

		// Get the results from our private repos
		$githubAPIResults = [];
		$response = apply_filters('fp_plugin_dist_releases_github_api_results', [], $this->accessToken, $repo_name, $github_user );
		if (is_wp_error($response)) {
			fp_dist_log("{$method_name}: cannot fetch releases at {$this->repo}, response: " . $response->get_error_message(), 'error');
			return;
		} 
		
		$githubAPIResults = $response;

		fp_comp_dist_log("[{$component_name}] getRepoReleaseInfo: looping through results", 'debug');
		// Use only the latest release
		if ( is_array( $githubAPIResults ) )
		{
			// If auto-update is off, when we run the update command it will update to highest version
			// auto-update allows us to filter the versioning format
			if ( ! $auto_update ) {
				$githubAPIResult = $githubAPIResults[0];
				fp_comp_dist_log("[{$component_name}] getRepoReleaseInfo: autoupdate is off, using latest version of {$repo_name} which is {$githubAPIResult->tag_name}");
				return $githubAPIResult;
			}
			
			fp_comp_dist_log("[{$component_name}] getRepoReleaseInfo: autoupdate is on, loop through all versions", 'debug');
			// Otherwise let's compare all the versions in the results
			$topVersion = false;
			foreach ( $githubAPIResults as $result ) {

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
				if ( preg_match('#^(\d+)\.?(\d*)\.?(\d*)#', $result->tag_name, $matches) ) {
					$major = $matches[1];
					// Remove leading zeroes
					$minor = ! empty( $matches[2] ) ? ltrim($matches[2], '0') : '0';
					$patch = ! empty( $matches[3] ) ? ltrim($matches[3], '0') : '0';
					$githubVersion = "{$major}.{$minor}.{$patch}";
					$topVersion = empty($topVersion) ? $githubVersion : $topVersion;
				} else {
					fp_comp_dist_log("[{$component_name}] Version {$result->tag_name} pattern from GitHub is not SemVar formatted for component `{$repo_name}`");
					continue;
				}
				fp_comp_dist_log("[{$component_name}] getRepoReleaseInfo: autoupdate is on, check version from github {$githubVersion} vs. {$versionExpression}");
				try {
					// Let's do version checking
					$semver = new vierbergenlars\SemVer\version( $githubVersion );
					// based on https://packagist.org/packages/vierbergenlars/php-semver
					$satisfies = $semver->satisfies(new vierbergenlars\SemVer\expression( $versionExpression ));
				} catch(vierbergenlars\SemVer\SemVerException $e) {
					fp_comp_dist_log("[{$component_name}] getRepoReleaseInfo: autoupdate is on, catch semver check exception");
					fp_comp_dist_log( $e->getMessage(), 'debug' );
					continue;
				}
				if ($satisfies) {
					fp_comp_dist_log("[{$component_name}] getRepoReleaseInfo: autoupdate is on, satisifies expression, saving this version");
					$githubAPIResult = $result;
					return $githubAPIResult;
				} else {
					fp_comp_dist_log("[{$component_name}] getRepoReleaseInfo: semvar expression ( {$versionExpression} ) in package.json does not satisfy release version ( {$githubVersion} ), skipping...", 'line');
				}
			}
		}
		return false;
	}

	/**
	 * The name of the component used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the component.
	 */
	public function get_component_repo_name( $component_name = '' ) {
		// if it starts with fp-, we know it's the theme, no need to prefix.
		if ( preg_match('#^fp\-#i', $component_name) ) {
			return $component_name;
		} else {
			return "component-{$component_name}";
		}
	}

	/**
	 * Get all the components.
	 *
	 * @since     1.0.0
	 * @return    array  list of components
	 */
	public function get_components() {
		global $fp_components;
		if ( !empty( $fp_components ) && is_array($fp_components) ) {
			$components = $fp_components;
			ksort($components);
			return $components;
		}
        return false;
	}

	/**
	 * Retrieve the version number of the component.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the component.
	 */
	public function get_version() {
		return $this->version;
	}

}
