<?php

if ( ! function_exists( 'fp_client_data' ) ) {
	class fp_client_data {

		function __construct() {
			add_action( 'admin_footer', array( $this, 'check_admin_performance' ) );
			add_action( 'wp_footer', array( $this, 'check_front_performance' ), 100 );
		}

		/**
		 * [check to make sure API call matches one of the pre-approved functions]
		 *
		 * @param  [string] $id [ API function to run ]
		 * @return boolean     [ return true if match found, false if not found ]
		 */

		function is_approved_function( $id ) {
			$approved = array(
				'all',
				'site_url',
				'wp_version',
				'object_cache',
				'object_cache_ready',
				'https',
				'multisite',
				'theme_name',
				'theme_version',
				'theme_directory',
				'parent_theme_name',
				'templates',
				'admins',
				'number_of_users',
				'active_users',
				'new_users',
				'new_content',
				'number_of_comments',
				'taxonomies',
				'number_of_taxonomies',
				'number_of_terms',
				'post_types',
				'number_of_post_types',
				'db_tables',
				'number_of_db_tables',
				'flowpress_tools_installed',
				'number_of_uploads',
				'website_size',
				'uploads_dir_size',
				'memory',
				'memory_used',
				'thumbnails',
				'thumbnail_size_count',
				'performance_admin_render_time',
				'performance_admin_number_of_queries',
				'performance_homepage_render_time',
				'performance_homepage_number_of_queries',
				'phpversion',
				'get_new_comments',
				'plugins',
				'outdated_plugin_count',
			);
			return in_array( $id, $approved );
		}

		/**
		 * [ 'all' key which returns data by running a preset amount functions ]
		 *
		 * @return [type] [description]
		 */

		function all() {
			$json['site_url']                               = $this->site_url();
			$json['wp_version']                             = $this->wp_version();
			$json['object_cache']                           = $this->object_cache();
			$json['object_cache_ready']                     = $this->object_cache_ready();
			$json['multisite']                              = $this->https();
			$json['https']                                  = $this->https();
			$json['theme_name']                             = $this->theme_name();
			$json['theme_version']                          = $this->theme_version();
			$json['theme_directory']                        = $this->theme_directory();
			$json['parent_theme_name']                      = $this->parent_theme_name();
			$json['templates']                              = $this->templates();
			$json['admins']                                 = $this->admins();
			$json['number_of_users']                        = $this->number_of_users();
			$json['active_users']                           = $this->active_users();
			$json['new_users']                              = $this->new_users();
			$json['new_content']                            = $this->new_content();
			$json['number_of_comments']                     = $this->number_of_comments();
			$json['taxonomies']                             = $this->taxonomies();
			$json['number_of_taxonomies']                   = $this->number_of_taxonomies();
			$json['number_of_terms']                        = $this->number_of_terms();
			$json['post_types']                             = $this->post_types();
			$json['number_of_post_types']                   = $this->number_of_post_types();
			$json['db_tables']                              = $this->db_tables();
			$json['number_of_db_tables']                    = $this->number_of_db_tables();
			$json['flowpress_tools_installed']              = $this->flowpress_tools_installed();
			$json['number_of_uploads']                      = $this->number_of_uploads();
			$json['website_size']                           = $this->website_size();
			$json['uploads_dir_size']                       = $this->uploads_dir_size();
			$json['memory']                                 = $this->memory();
			$json['memory_used']                            = $this->memory_used();
			$json['thumbnails']                             = $this->thumbnails();
			$json['thumbnail_size_count']                   = $this->thumbnail_size_count();
			$json['performance_admin_render_time']          = $this->performance_admin_render_time();
			$json['performance_admin_number_of_queries']    = $this->performance_admin_number_of_queries();
			$json['performance_homepage_render_time']       = $this->performance_homepage_render_time();
			$json['performance_homepage_number_of_queries'] = $this->performance_homepage_number_of_queries();
			$json['phpversion']                             = $this->phpversion();
			$json['get_new_comments']                       = $this->get_new_comments();
			$json['plugins']                                = $this->plugins();
			$json['outdated_plugin_count']                  = $this->outdated_plugin_count();

			return $json;
		}

		/**
		 * [get list of all plugins]
		 *
		 * @return [json] [plugin list]
		 */

		function plugins() {
			$plugin_data = $this->process_plugins();
			return $plugin_data['plugins'];
		}

		/**
		 * [get list of outdated plugins]
		 *
		 * @return [json] [plugin list]
		 */

		function outdated_plugin_count() {
			$plugin_data = $this->process_plugins();
			return $plugin_data['outdated_plugin_count'];
		}

		/**
		 * [loop through plugin list + updates to generate arrays for API call]
		 *
		 * @return [array] [plugin list ( outdated, all ) ]
		 */

		function process_plugins() {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}
			if ( ! function_exists( 'get_plugins_updates' ) ) {
				require_once ABSPATH . 'wp-admin/includes/update.php';
			}

			$outdated_plugin_count  = 0;
			$udpate_count           = 0;
			$plugins                = get_plugins();
			$updates                = get_plugin_updates();
			$plugin_update_data     = '';
			$plugin_data            = '';
			$json_plugin_data       = array();
			$json_final_plugin_data = array();

			foreach ( $plugins as $key => $plugin ) {
				$index                      = str_replace( '.php', '', basename( $key ) );
				$json_plugin_data[ $index ] = array(
					'name'              => $plugin['Name'],
					'slug'              => $index,
					'uri'               => $plugin['PluginURI'],
					'installed_version' => $plugin['Version'],
				);
			}

			$active_plugins = get_option( 'active_plugins' );

			foreach ( $active_plugins as $key ) {
				$index                                = str_replace( '.php', '', basename( $key ) );
				$json_plugin_data[ $index ]['active'] = 1;
			}

			foreach ( $updates as $key => $plugin ) {
				$outdated_plugin_count++;
				$index                                = str_replace( '.php', '', basename( $key ) );
				$json_plugin_data[ $index ]['update'] = 1;
				$new_version                          = isset( $plugin->update->new_version ) ? $plugin->update->new_version : '';
				$json_plugin_data[ $index ]['current_version'] = $new_version;
			}

			foreach ( $json_plugin_data as $value ) {
				if ( isset( $value['update'] ) ) {
					if ( 1 == $value['update'] ) {
						$udpate_count++;
					}
				}
				$json_final_plugin_data[] = $value;
			}

			return array(
				'plugins'               => $json_final_plugin_data,
				'outdated_plugin_count' => $outdated_plugin_count,
			);
		}

		/**
		 * [get the site_url]
		 *
		 * @return [string]
		 */

		function site_url() {
			return site_url();
		}

		/**
		 * [get the WordPress version]
		 *
		 * @return [string]
		 */

		function wp_version() {
			return get_bloginfo( 'version' );
		}

		/**
		 * [check to see if object cache is enabled]
		 *
		 * @return [boolean]
		 */

		function object_cache() {
			$object_cache_ready = $this->object_cache_ready() ? 1 : 0;
			return $object_cache_ready;
		}

		/**
		 * [object_cache_ready checks to make sure WP_CACHE is set, memcache_servers are set and object-cache.php is present]
		 *
		 * @return [boolean] [ returns true if all criteria is met ]
		 */

		function object_cache_ready() {
			global $memcached_servers;
			if ( ! defined( 'WP_CACHE' ) ) {
				return false;
			}
			if ( ! WP_CACHE ) {
				return false;
			}
			if ( 0 == count( $memcached_servers ) ) {
				return false;
			}
			$path = WP_CONTENT_DIR . '/object-cache.php';
			return file_exists( $path );
		}

		/**
		 * [check to see if https is enabled]
		 *
		 * @return [boolean]
		 */

		function https() {
			$ssl = is_ssl() ? 1 : 0;
			return $ssl;
		}

		/**
		 * [check to see if WordPress is configured as a multisite]
		 *
		 * @return [boolean]
		 */

		function multisite() {
			$is_multisite = is_multisite() ? 1 : 0;
			return $is_multisite;
		}

		/**
		 * [get the theme name]
		 *
		 * @return [string]
		 */

		function theme_name() {
			$theme = wp_get_theme();
			return $theme->get( 'Name' );
		}

		/**
		 * [get the theme version]
		 *
		 * @return [string]
		 */

		function theme_version() {
			$theme = wp_get_theme();
			return $theme->get( 'Version' );
		}

		/**
		 * [get the theme directory]
		 * [enabled only for intensive scans]
		 *
		 * @return [string]
		 */

		function theme_directory() {
			if ( $this->intensive_check() ) {
				return $this->intensive_check();
			}
			return get_stylesheet_directory();
		}

		/**
		 * [get the parent theme name]
		 *
		 * @return [string]
		 */

		function parent_theme_name() {
			return get_template();
		}

		/**
		 * [get list of all theme template files]
		 * [enabled only for intensive scans]
		 *
		 * @return [array]
		 */

		function templates() {
			if ( $this->intensive_check() ) {
				return $this->intensive_check();
			}

			$all_theme_files = $this->get_theme_files();

			$template_array = array( 'index', 'home', 'content', 'frontpage', '404', 'search', 'page', 'date', 'author', 'category', 'tag', 'taxonomy', 'archive', 'single', 'attachment', 'single', 'template' );
			$templates      = array();

			foreach ( $all_theme_files as $name ) {
				$file_name = basename( $name );
				if ( ! strpos( $file_name, '.php' ) ) {
					continue;
				}
				foreach ( $template_array as $key ) {
					if ( strpos( $file_name, $key ) === 0 ) {
						if ( isset( $templates[ $key ] ) ) {
							if ( is_array( $templates[ $key ] ) ) {
								if ( ! in_array( $file_name, $templates[ $key ] ) ) {
									$templates[ $key ][] = $file_name;
								}
							} else {
								$templates[ $key ][] = $file_name;
							}
						} else {
							$templates[ $key ][] = $file_name;
						}
					}
				}
			}

			$template_list_json = false;

			foreach ( $templates as $key => $value ) {
				foreach ( $templates[ $key ] as $single ) {
					$template_list_json[] = array(
						'template' => $single,
					);
				}
			}

			return $template_list_json;
		}

		/**
		 * [get list of all admins]
		 *
		 * @return [array {email, name}]
		 */

		function admins() {
			$admins      = get_users( 'orderby=nicename&role=administrator' );
			$admin_array = array();
			foreach ( $admins as $admin ) {
				if ( defined( 'FP_ENABLE_CLIENT_ADMIN_EMAILS' ) ) {
					$admin_array[] = array(
						'name'  => $admin->display_name,
						'email' => $admin->user_email,
					);
				} else {
					$admin_array[] = array(
						'name' => $admin->display_name,
					);
				}
			}
			return $admin_array;
		}

		/**
		 * [get number of users on the site]
		 *
		 * @return [number]
		 */

		function number_of_users() {
			$users = get_users( 'orderby=nicename&number=999999' );
			return count( $users );
		}

		/**
		 * [get list of active users on the site in the last 30 days]
		 *
		 * @return [array {email, name}]
		 */

		function active_users() {
			$days       = 30;
			$users      = get_users( 'orderby=nicename&number=999999' );
			$user_array = array();
			foreach ( $users as $user ) {
				$last_login = get_the_author_meta( 'last_login', $user->ID );
				if ( $last_login ) {
					$the_login_date = human_time_diff( $last_login );
				}
				if ( $last_login < strtotime( '-30 days' ) ) {
					continue;
				}
				$user_array[] = array(
					'email' => $user->user_email,
					'name'  => $user->display_name,
				);
			}
			return $user_array;
		}

		/**
		 * [get list of new users on the site in the last 30 days]
		 *
		 * @return [array {email, name, registered, roles}]
		 */

		function new_users() {
			$days       = 30;
			$users      = get_users( 'orderby=nicename&number=999999' );
			$user_array = array();
			foreach ( $users as $user ) {
				$udata      = get_userdata( $user->ID );
				$registered = $udata->user_registered;
				if ( strtotime( $registered ) < strtotime( '-30 days' ) ) {
					continue;
				}
				$user_array[] = array(
					'email'      => $user->user_email,
					'name'       => $user->display_name,
					'registered' => $registered,
					'roles'      => implode( ', ', $udata->roles ) . "\n",
				);
			}
			return $user_array;
		}

		/**
		 * [get list of new content on the site in the last 30 days]
		 *
		 * @return [array {post_id, date, post_type, title, author}]
		 */

		function new_content() {
			$args    = array(
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'posts_per_page' => -1,
				'order'          => 'DESC',
				'date_query'     => array(
					array(
						'after' => '30 days ago',
					),
				),
			);
			$results = new WP_Query( $args );
			$return  = array();
			while ( $results->have_posts() ) :
				$results->the_post();
				$id       = get_the_ID();
				$return[] = array(
					'post_id'   => $id,
					'date'      => get_the_date( 'l F j, Y', $id ),
					'post_type' => get_post_type( $id ),
					'title'     => get_the_title( $id ),
					'author'    => get_the_author(),
				);
			endwhile;
			return $return;
		}

		/**
		 * [get number of comments on the site]
		 *
		 * @return [number]
		 */

		function number_of_comments() {
			global $wpdb;
			$comments = $wpdb->get_results( "SELECT COUNT(*) AS count FROM $wpdb->comments" );
			$comments[0]->count;
			return $comments[0]->count;
		}

		/**
		 * [get list of taxonomies and their term count]
		 *
		 * @return [array {taxonomy, term_count}]
		 */

		function taxonomies() {
			$taxonomies = get_taxonomies( false, 'objects' );
			$tax_array  = array();
			foreach ( $taxonomies as $taxonomy ) {
				if ( ! $taxonomy->rewrite['slug'] ) {
					continue;
				}
				$terms     = get_terms(
					array(
						'taxonomy' => $taxonomy->rewrite['slug'],
					)
				);
				$tax_count = 0;
				foreach ( $terms as $t ) {
					if ( isset( $t->name ) ) {
						if ( '' != $t->name ) {
							$allterms[] = $t->name;
							$tax_count++;
						}
					}
				}
				$tax_array[] = array(
					'taxonomy'   => $taxonomy->rewrite['slug'],
					'term_count' => strval( $tax_count ),
				);
			}
			return $tax_array;
		}

		/**
		 * [get number of taxonomies on the site]
		 *
		 * @return [number]
		 */

		function number_of_taxonomies() {
			$count = 0;
			foreach ( get_taxonomies( false, 'objects' ) as $taxonomy ) {
				if ( ! $taxonomy->rewrite['slug'] ) {
					continue;
				}
				$count++;
			}
			return $count;
		}

		/**
		 * [get number of terms on the site]
		 *
		 * @return [number]
		 */

		function number_of_terms() {
			$term_count = 0;
			$taxonomies = get_taxonomies( false, 'objects' );
			foreach ( $taxonomies as $taxonomy ) {
				if ( ! $taxonomy->rewrite['slug'] ) {
					continue;
				}
				$terms = get_terms(
					array(
						'taxonomy' => $taxonomy->rewrite['slug'],
					)
				);
				foreach ( $terms as $t ) {
					if ( isset( $t->name ) ) {
						if ( '' != $t->name ) {
							$term_count++;
						}
					}
				}
			}
			return $term_count;
		}

		/**
		 * [get post_types on the site]
		 *
		 * @return [array {post_type_name, count}]
		 */

		function post_types() {
			$post_types      = get_post_types( false, 'objects' );
			$post_types_json = array();
			foreach ( $post_types as $p ) {
				$count             = wp_count_posts( $p->name );
				$post_types_json[] = array(
					'post-type' => $p->name,
					'count'     => $count->publish,
				);
			}
			return $post_types_json;
		}

		/**
		 * [get number of post_types on the site]
		 *
		 * @return [number]
		 */

		function number_of_post_types() {
			$count = 0;
			foreach ( get_post_types( false, 'objects' ) as $taxonomy ) {
				if ( ! $taxonomy->rewrite['slug'] ) {
					continue;
				}
				$count++;
			}
			return $count;
		}

		/**
		 * [get db table list]
		 * [enabled only for intensive scans]
		 *
		 * @return [text]
		 */

		function db_tables() {
			global $wpdb;
			if ( $this->intensive_check() ) {
				return $this->intensive_check();
			}
			$tables      = $wpdb->get_results( 'SHOW TABLES', ARRAY_A );
			$tables_json = '';
			foreach ( $tables as $t ) {
				foreach ( $t as $value ) {
					$tables_json .= $value . "\n";
				}
			}
			return $tables_json;
		}

		/**
		 * [get number of db_tables on the site]
		 *
		 * @return [number]
		 */

		function number_of_db_tables() {
			global $wpdb;
			$tables = $wpdb->get_results( 'SHOW TABLES', ARRAY_A );
			return count( $tables );
		}

		/**
		 * [check to see if flowpress tools are installed]
		 *
		 * @return [true if installed]
		 */

		function flowpress_tools_installed() {
			$tools = file_exists( ABSPATH . '.tools' ) ? '' : 'not ';
			return 'Flowpress Tools ' . $tools . 'Installed';
		}

		/**
		 * [get number of uploads]
		 * [enabled only for intensive scans]
		 *
		 * @return [text]
		 */

		function number_of_uploads() {
			if ( $this->intensive_check() ) {
				return $this->intensive_check();
			}
			$up_dir = wp_upload_dir();
			$size   = $this->get_dir_size( $up_dir['basedir'] );
			return $size[1];
		}

		/**
		 * [get website size]
		 * [enabled only for intensive scans]
		 *
		 * @return [text]
		 */

		function website_size() {
			if ( $this->intensive_check() ) {
				return $this->intensive_check();
			}
			$size = $this->format_size_units( $this->get_dir_size( ABSPATH ) );
			return $size;
		}

		/**
		 * [get upload directory size]
		 * [enabled only for intensive scans]
		 *
		 * @return [text]
		 */

		function uploads_dir_size() {
			if ( $this->intensive_check() ) {
				return $this->intensive_check();
			}
			$up_dir = wp_upload_dir();
			$size   = $this->get_dir_size( $up_dir['basedir'] );
			return $size[0];
		}

		/**
		 * [get wp_memeory limit]
		 *
		 * @return [integer]
		 */

		function memory() {
			return intval( WP_MEMORY_LIMIT );
		}

		/**
		 * [get memory_get_usage]
		 *
		 * @return [integer]
		 */

		function memory_used() {
			return function_exists( 'memory_get_usage' ) ? round( memory_get_usage() / 1024 / 1024, 2 ) : 0;
		}

		/**
		 * [get list of thumbnails]
		 *
		 * @return [array { thumbnail name, size, crop }]
		 */

		function thumbnails() {
			$thumbnail_sizes      = get_intermediate_image_sizes();
			$thumbnail_sizes_json = array();
			foreach ( $thumbnail_sizes as $size ) {
				$dimentions = $this->get_image_sizes( $size );
				if ( ! $dimentions['width'] ) {
					continue;
				}
				$thumbnail_sizes_json[] = array(
					'thumbnail' => $size,
					'size'      => $dimentions['width'] . 'x' . $dimentions['height'],
					'crop'      => $dimentions['crop'],
				);
			}
			return $thumbnail_sizes_json;
		}

		/**
		 * [get thumbnails size count]
		 *
		 * @return [integer]
		 */

		function thumbnail_size_count() {
			$thumbnail_sizes = get_intermediate_image_sizes();
			return count( $thumbnail_sizes );
		}

		/**
		 * [get transient data for Admin Render ( backend ) Time]
		 *
		 * @return [string]
		 */

		function performance_admin_render_time() {
			$fp_client_admin_performance_data = get_transient( 'fp_client_admin_performance' );
			return $fp_client_admin_performance_data['Render Time'] ? $fp_client_admin_performance_data['Render Time'] : 0;
		}

		/**
		 * [get transient data for number of Admin Queries ( backend )]
		 *
		 * @return [string]
		 */

		function performance_admin_number_of_queries() {
			$fp_client_admin_performance_data = get_transient( 'fp_client_admin_performance' );
			return $fp_client_admin_performance_data['Number of Queries'] ? $fp_client_admin_performance_data['Number of Queries'] : 0;
		}

		/**
		 * [get transient data for Front Render Time]
		 *
		 * @return [string]
		 */

		function performance_homepage_render_time() {
			$fp_client_front_performance_data = get_transient( 'fp_client_front_performance' );
			return $fp_client_front_performance_data['Render Time'] ? $fp_client_front_performance_data['Render Time'] : 0;
		}

		/**
		 * [get transient data for number of Front End Queries ]
		 *
		 * @return [string]
		 */

		function performance_homepage_number_of_queries() {
			$fp_client_front_performance_data = get_transient( 'fp_client_front_performance' );
			return $fp_client_front_performance_data['Number of Queries'] ? $fp_client_front_performance_data['Number of Queries'] : 0;
		}

		/**
		 * [get the php version]
		 *
		 * @return [string]
		 */

		function phpversion() {
			return phpversion();
		}

		/**
		 * [get list of new comments in the last 30 days]
		 *
		 * @return [array] standard WordPress return format
		 */

		function get_new_comments() {
			$date = date( 'm/d/Y', strtotime( '-30 days', strtotime( 'now' ) ) ) . PHP_EOL;
			$args = array(
				'date_query' => array(
					array(
						'after' => $date,
					),
				),
			);

			$comments_query = new WP_Comment_Query();
			$comments       = $comments_query->query( $args );
			return $comments;
		}

		// helpers

		// Get theme files


		/**
		 * [get a list of all theme files (parent/child)]
		 *
		 * @return [array] [theme file list]
		 */

		function get_theme_files() {
			// child
			$directory       = get_stylesheet_directory();
			$all_theme_files = array();
			$theme_files     = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $directory ), RecursiveIteratorIterator::SELF_FIRST );
			foreach ( $theme_files as $name => $object ) {
				array_push( $all_theme_files, $name );
			}
			// Parent
			if ( is_child_theme() ) {
				$directory = get_template_directory();
				if ( $directory ) :
					$theme_files = new RecursiveIteratorIterator( new RecursiveDirectoryIterator( $directory ), RecursiveIteratorIterator::SELF_FIRST );
					foreach ( $theme_files as $name => $object ) {
						array_push( $all_theme_files, $name );
					}
				endif;
			}
			return $all_theme_files;
		}

		/**
		 * [Check WPConfig constant variables]
		 *
		 * @param  [string] $var [description]
		 * @return [type] [true if exists and set to true]
		 */

		function wpconfig_option( $var ) {
			if ( defined( $var ) ) {
				$variable = constant( $var );
				if ( true === $variable ) {
					return true;
				} else {
					return false;
				}
			}
			return false;
		}

		// Check admin performance and build cache

		function check_admin_performance() {
			$cache = get_transient( 'fp_client_admin_performance' );
			if ( ! $cache ) {
				global $wpdb;
				$data['Render Time']       = timer_stop( 0, 2 );
				$data['Number of Queries'] = $wpdb->num_queries;
				set_transient( 'fp_client_admin_performance', $data, 3600 * 24 );
			}
		}

		// Check front performance and build cache

		function check_front_performance() {
			global $wpdb;
			$data = array(
				'Number of Queries' => $wpdb->num_queries,
				'Render Time'       => timer_stop( 0, 2 ),
			);
			echo '<!-- Flowpress Performance Tracker: ' . json_encode( $data ) . ' -->';
			$cache = get_transient( 'fp_client_front_performance' );
			if ( ! $cache ) {
				set_transient( 'fp_client_front_performance', $data, 3600 * 24 );
			}
		}

		// get WordPress image sizes

		function get_image_sizes( $size = '' ) {
			global $_wp_additional_image_sizes;
			$sizes                        = array();
			$get_intermediate_image_sizes = get_intermediate_image_sizes();
			foreach ( $get_intermediate_image_sizes as $_size ) {
				if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
					$sizes[ $_size ]['width']  = get_option( $_size . '_size_w' );
					$sizes[ $_size ]['height'] = get_option( $_size . '_size_h' );
					$sizes[ $_size ]['crop']   = (bool) get_option( $_size . '_crop' );
				} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
					$sizes[ $_size ] = array(
						'width'  => $_wp_additional_image_sizes[ $_size ]['width'],
						'height' => $_wp_additional_image_sizes[ $_size ]['height'],
						'crop'   => $_wp_additional_image_sizes[ $_size ]['crop'],
					);
				}
			}
			if ( $size ) {
				if ( isset( $sizes[ $size ] ) ) {
					return $sizes[ $size ];
				} else {
					return false;
				}
			}
		}

		// Intensive scan check

		function intensive_check() {
			if ( ! $this->wpconfig_option( 'FP_ENABLE_CLIENT_INTENSIVE_SCAN' ) ) {
				return 'Disabled';
			}
		}

		// Process directory scanning

		function get_dir_size( $path ) {
			$files    = scandir( $path );
			$filesize = '';
			foreach ( $files as $key => $value ) {
				$test_path = realpath( $path . DIRECTORY_SEPARATOR . $value );
				if ( ! is_dir( $test_path ) ) {
					$filesize = $filesize + filesize( $test_path );
				} elseif ( '.' != $value && '..' != $value ) {
					if ( false === strpos( $value, 'node' ) ) {
						$filesize += $this->get_dir_size( $test_path );
					}
				}
			}
			return $filesize;
		}

		// Format file size output

		function format_size_units( $bytes ) {
			if ( $bytes >= 1073741824 ) {
				$bytes = number_format( $bytes / 1073741824, 2 ) . ' GB';
			} elseif ( $bytes >= 1048576 ) {
				$bytes = number_format( $bytes / 1048576, 2 ) . ' MB';
			} elseif ( $bytes >= 1024 ) {
				$bytes = number_format( $bytes / 1024, 2 ) . ' KB';
			} elseif ( $bytes > 1 ) {
				$bytes = $bytes . ' bytes';
			} elseif ( 1 == $bytes ) {
				$bytes = $bytes . ' byte';
			} else {
				$bytes = '0 bytes';
			}
			return $bytes;
		}

	}
}

