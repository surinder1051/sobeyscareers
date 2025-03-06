<?php
/**
 * Generate Plugin Packages
 *
 * @package fp-foundation
 * @since 1.8.71
 */

if ( ! class_exists( 'GeneratePluginPackages' ) ) {
	/**
	 * Create a json package file listing plugins used in a project to be used for generating auto update branches in git.
	 */
	class GeneratePluginPackages {

		/**
		 * The config output file.
		 *
		 * @var string $file
		 */
		public $file;
		/**
		 * An array of deprecated, custom plugins.
		 *
		 * @var array $old_internal_plugins
		 */
		public $old_internal_plugins;
		/**
		 * An array storing Github URLs for plugins with repositories.
		 *
		 * @var array $repos
		 */
		public $repos;

		/**
		 * Initialize the class.
		 * Requires a github constant: FP_PLUGIN_GITHUB_TOKEN.
		 *
		 * @see self::set_old_internal_plugins()
		 * @see self::update()
		 *
		 * @return void if the Github token is missing
		 */
		public function __construct() {

			$this->file = ABSPATH . 'wp-content/fp-plugin-packages.json';
			$this->set_old_internal_plugins();
			do_action( 'add_item_fp_menu', 'Update Plugin package.json', '?update_plugin_packages=1' );
			add_action( 'update_plugin_packages', array( $this, 'update' ) );

			if ( isset( $_GET['update_plugin_packages'] ) ) { //phpcs:ignore

				if ( ! defined( 'FP_PLUGIN_GITHUB_TOKEN' ) || empty( FP_PLUGIN_GITHUB_TOKEN ) ) {
					add_action(
						'admin_notices',
						function () {
							printf( '<div class="notice notice-error"><p>Error: Missing FP_PLUGIN_GITHUB_TOKEN constant in wp-config.php (in 1Pass)</p></div>' );
						}
					);
					return;
				}

				if ( file_exists( $this->file ) ) {
					add_action( 'init', array( $this, 'update' ) );
				} else {
					add_action( 'init', array( $this, 'create_new' ) );
				}
			}
		}

		/**
		 * Create a new json file with basic configurations.
		 *
		 * @see self::update()
		 */
		public function create_new() {

			$data = array(
				'name'         => 'fp-plugin-manager-settings',
				'last-updated' => 'null',
				'description'  => 'Plugin Manager for FlowPress managed repos. [ Auto Generated ]',
				'log-core'     => true,
				'platforms'    => array( 'cli', 'admin' ),
				'packages'     => (object) array(),
			);

			file_put_contents( $this->file, json_encode( $data, JSON_PRETTY_PRINT ) ); //phpcs:ignore

			$this->update();

		}

		/**
		 * Get the config file contents
		 */
		public function read_file() {
			return json_decode( file_get_contents( $this->file ) ); //phpcs:ignore
		}

		/**
		 * Create a constant array of deprecated internal plugins to check against.
		 * Sets the class property old_internal_plugins
		 */
		public function set_old_internal_plugins() {
			$this->old_internal_plugins = array(
				'bb-regionalization',
				'bffa_subscription_form',
				'cookie-policy',
				'globalized-store-importer',
				'tru-travel-details-form',
				'gtm-tag-manager',
				'sobeys_recipes_api',
			);
		}

		/**
		 * Ping Github to see if a plugin has a repository
		 *
		 * @param string $repo is the git repo for the plugin.
		 *
		 * @return bool
		 */
		public function check_if_git_repo_exists( $repo ) {

			if ( empty( $this->repos ) ) {
				$url  = 'https://api.github.com/orgs/flowpress/repos';
				$url  = add_query_arg( array( 'access_token' => FP_PLUGIN_GITHUB_TOKEN ), $url );
				$url  = add_query_arg( 'per_page', 100, $url );
				$page = 1;

				// Get the results.
				$args    = array(
					'headers' => array(
						'Authorization' => 'Bearer ' . FP_PLUGIN_GITHUB_TOKEN,
					),
				);
				$results = json_decode( wp_remote_retrieve_body( wp_remote_get( "{$url}&page=$page", $args ) ) );

				$new_results = array();

				while ( $page ) {
					if ( empty( $new_results ) || 100 === count( $new_results ) ) {
						$page++;
						$new_results = json_decode( wp_remote_retrieve_body( wp_remote_get( "{$url}&page=$page", $args ) ) );
						$results     = array_merge( $results, $new_results );
					} else {
						break;
					}
				}
				$this->repos = array_column( $results, 'name' );
			}

			if ( in_array( $repo, $this->repos, true ) ) {
				return true;
			}
			return false;
		}

		/**
		 * Check if the plugin has a repo in WordPress.com
		 *
		 * @param string $slug is the plugin dirname.
		 *
		 * @return integer http code
		 */
		public function is_core_plugin( $slug ) {
			$url = "https://wordpress.org/plugins/{$slug}/";
			$ch  = curl_init( $url ); //phpcs:ignore
			curl_setopt( $ch, CURLOPT_HEADER, true );    // phpcs:ignore
			curl_setopt( $ch, CURLOPT_NOBODY, true );    // phpcs:ignore
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 ); // phpcs:ignore
			curl_setopt( $ch, CURLOPT_TIMEOUT, 10 ); // phpcs:ignore
			$httpcode = curl_getinfo( $ch, CURLINFO_HTTP_CODE ); //phpcs:ignore
			curl_close( $ch ); //phpcs:ignore

			return intval( $httpcode );
		}

		/**
		 * Write basic json data to the file named in class property $file.
		 *
		 * @param object $json is the basic object to write.
		 *
		 * @return void
		 */
		public function write_json( $json ) {

			$json->{'last-updated'} = date( 'Y-m-j H:i:s' ); //phpcs:ignore
			$json->{'name'}         = 'fp-plugin-manager-settings';
			$json->{'description'}  = 'Plugin Manager for FlowPress managed repos. [ Auto Generated ]';
			$json->{'log-core'}     = true;
			$json->{'platforms'}    = array( 'cli', 'admin' );

			file_put_contents( $this->file, json_encode( $json, JSON_PRETTY_PRINT ) ); //phpcs:ignore
		}


		/**
		 * Update the base json file with plugin data.
		 *
		 * @see self::read_file()
		 */
		public function update() {

			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				WP_CLI::log( "Updating {$this->file}" );
			} else {
				echo wp_kses( "<h1>Updating {$this->file}</h1>", 'post' );
			}

			$json                    = $this->read_file();
			$packages                = $json->packages;
			$plugin_keys_in_packages = array_keys( (array) $packages );

			$all_plugins = get_plugins();

			$update_plugins = get_site_transient( 'update_plugins' );
			$active_plugins = get_option( 'active_plugins', array() );

			foreach ( $all_plugins as $key => $value ) {
				list( $slug, $file ) = explode( '/', $key );
				if ( in_array( $key, $active_plugins, true ) ) {
					// Active Plugin.

					$type = null;
					$core = false;

					if ( false !== strpos( $slug, 'fp-' ) ) {
						$type = 'internal';
					} elseif ( false !== strpos( $slug, 'sobeys' ) ) {
						$type = 'internal';
					} elseif ( in_array( $slug, $this->old_internal_plugins, true ) ) {
						$type = 'internal';
					} else {
						$type = 'unknown';

						if ( 200 === $this->is_core_plugin( $slug ) ) {
							$type = 'core';
							$core = true;
						} else {
							$type = 'premium';
							if ( defined( 'WP_CLI' ) && WP_CLI ) {
								WP_CLI::log( 'Confirm $slug is Premium?' );
							} else {
								echo wp_kses( "<h2>Confirm {$slug} is Premium?</h2>", 'post' );
							}
						}
					}

					if ( $internal === $type || 'premium' === $type ) {
						if ( ! in_array( $slug, $plugin_keys_in_packages, true ) ) {
							if ( $this->check_if_git_repo_exists( "plugin-{$slug}" ) ) {
								$json->packages->$slug = array(
									'version'             => '*.*',
									'access_token'        => '',
									'core'                => $core,
									'branch'              => 'master',
									'auto-update'         => true,
									'github-project-name' => "plugin-{$slug}",
									'github-username'     => 'flowpress',
									'plugin-slug'         => "{$slug}",
								);
							} else {
								if ( defined( 'WP_CLI' ) && WP_CLI ) {
									WP_CLI::warning( "Github Repo missing for plugin-{$slug}" );
								} else {
									echo wp_kses( "<h2 style='color: red;'>Github Repo missing for plugin-{$slug}</h2>", 'post' );
								}
							}
						}
					}
				}
			}
			$this->write_json( $json );
			if ( defined( 'WP_CLI' ) && WP_CLI ) {
				WP_CLI::success( 'Done generating json file.' );
			} else {
				die( 'Done generating json file.' );
			}

		}
	}
	new GeneratePluginPackages();
}
