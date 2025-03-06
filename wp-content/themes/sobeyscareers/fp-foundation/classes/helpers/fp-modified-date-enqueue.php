<?php //phpcs:ignore
/**
 * Plugin Name: Modified Date Enqueue
 * Plugin Author: FlowPress (MD, DAC)
 * Plugin URI: https://github.com/FlowPress/mu-modified-date-enqueue
 * Plugin Version: 1.0.9
 *
 * @package fp-foundation
 */

/**
 * Enqueue local assets using file modify dates and avoid CDN caching issues.
 */
class ModifiedDateEnqueue {

	/**
	 * Sets up our plugin.
	 *
	 * @since 1.0.4
	 *
	 * @see self::add_enqueue_dates()
	 * @see self::reset_dates()
	 * @see self::setup_menu()
	 * @see self::delete_dates()
	 */
	public function __construct() {
		add_action( ' wp_enqueue_scripts', array( $this, 'add_enqueue_dates' ), 1000 );
		add_action( 'admin_head', array( $this, 'reset_dates' ) );
		add_action( 'admin_menu', array( $this, 'setup_menu' ) );
		add_action( 'upgrader_process_complete', array( $this, 'delete_dates' ) );
		add_action( 'fl_builder_after_save_layout', array( $this, 'delete_dates' ) );
		add_action( 'fl_builder_after_save_user_template', array( $this, 'delete_dates' ) );
		add_action( 'fl_builder_cache_cleared', array( $this, 'delete_dates' ) );

		add_filter( 'script_loader_src', array( $this, 'add_enqueue_dates' ) );
		add_filter( 'style_loader_src', array( $this, 'add_enqueue_dates' ) );
	}

	/**
	 * Loop through all registered styles and scripts and append dates.
	 *
	 * @since 1.0.4
	 *
	 * @param array $src is the WP registered css and js file.
	 *
	 * @see self::convert_ms_links()
	 * @see self::add_date_to_enqueue_url()
	 *
	 * @return string
	 */
	public function add_enqueue_dates( $src ) {

		// Don't touch admin scripts.
		if ( is_admin() ) {
			return $src;
		}

		// Get modifiy date for file.
		$src     = $this->convert_ms_links( $src );
		$new_src = $this->add_date_to_enqueue_url( $src );
		return $new_src;
	}

	/**
	 * Convert MS /files links to blog.dir links.
	 *
	 * @since 1.0.6
	 *
	 * @param string $src are the WP registered js and css file.
	 *
	 * @return string
	 */
	public function convert_ms_links( $src ) {
		if ( is_multisite() && strpos( $src, '/files' ) !== false ) {
			$blog_id = get_current_blog_id();
			$src     = str_replace( '/files/', "/wp-content/blogs.dir/$blog_id/files/", $src );
		}
		return $src;
	}

	/**
	 * Add a submenu item to the 'Tools' menu in WP Admin.
	 *
	 * @since 1.0.2
	 *
	 * @return void
	 */
	public function setup_menu() {
		global $submenu;
		$permalink              = admin_url( '?reset_enqueue_dates=true' );
		$submenu['tools.php'][] = array( 'Reset Enqueue Transient', 'manage_options', $permalink ); //phpcs:ignore
	}

	/**
	 * Append modified date to end of file.
	 *
	 * @param string $src is the WP registered js or css file.
	 * @return void
	 */
	public function add_date_to_enqueue_url( $src ) {
		// Return if no $file defined.
		if ( false === $src ) {
			return;
		}

		// Split source file name.
		$parts = wp_parse_url( $src );

		// Something went wrong with url parsing.
		if ( ! isset( $parts['path'] ) ) {
			return $src;
		}

		// Assign handle for cache.
		$name = preg_replace( '/[^\w]/', '', $parts['path'] );

		// Set ID based on if file is css or js.
		$id = ( strpos( $src, '.css' ) > 0 ) ? 'css' : 'js';

		// If src starts with // add http(s): back in.
		if ( '//' === substr( $src, 0, 2 ) ) {
			if ( is_ssl() ) {
				$src = 'https:' . $src;
			} else {
				$src = 'http:' . $src;
			}
		}

		// Return false for any non local files (Google Fonts, GA, etc...).

		// Put a fix in when resources don't use /en /fr in url but home_url does.
		$home_url = str_replace( array( '/en', '/fr' ), array( '', '' ), home_url() );

		if ( false === strpos( $src, $home_url ) ) {
			return $src;
		}

		// Remove home url prefix.
		$src = str_replace( $home_url, '', $src );

		// Return if no $src left.
		if ( empty( $src ) ) {
			return;
		}

		// Assign file location on server.
		$full_file_name = substr( ABSPATH, 0, -1 ) . $src;

		// Get modifid date for file.
		$modified = $this->lookup_file_modified_date( $name, $full_file_name, $id );

		// Return new enqueue location with modified date attached.
		if ( strpos( $src, '?' ) > 0 ) {
			return $src . '&m=' . $modified;
		} else {
			return $src . '?m=' . $modified;
		}
	}

	/**
	 * Check for ?reset_enqueue_dates query string
	 * and delete transient.
	 *
	 * @since 1.0.2
	 *
	 * @param bool $force Optional. Force the transient to be deleted even if the GET parameter is not set.
	 *
	 * @see self::delete_dates()
	 *
	 * @return void
	 */
	public function reset_dates( $force = false ) {
		if ( ( isset( $_GET['reset_enqueue_dates'] ) && $_GET['reset_enqueue_dates'] ) || $force ) { //phpcs:ignore
			$this->delete_dates();
		}
	}

	/**
	 * Delete stored dates for BB hooks
	 *
	 * @since 1.0.3
	 *
	 * @return void
	 */
	public function delete_dates() {
		delete_transient( 'css_enqueue_date' );
		delete_transient( 'js_enqueue_date' );
	}

	/**
	 * Look up modified date transient.
	 *
	 * @since 1.0.2
	 *
	 * @param string  $name is the handle assigned to the js or css file to create the transient.
	 * @param string  $file_name is the absolute path of the file.
	 * @param integer $id is the file type ( js | css ).
	 *
	 * @return string $modified Date modified.
	 */
	public function lookup_file_modified_date( $name, $file_name, $id ) {

		// Get enqueue transient.
		$modified_dates = get_transient( $id . '_enqueue_date' );

		// Check to see if file already exists in cache.

		if ( empty( $file_name ) || empty( $id ) ) {
			return $modified_dates[ $name ];
		} elseif ( isset( $modified_dates[ $name ] ) ) {
			// Return previously stored modified date.
			return $modified_dates[ $name ];
		} else {
			// Get file modified date.

			if ( false === $modified_dates ) {
				$modified_dates = array();
			}

			if ( false !== strpos( $file_name, '?' ) ) {
				$file_name = explode( '?', $file_name );
				$file_name = $file_name[0];
			}

			if ( file_exists( $file_name ) ) {

				$file_posted             = filemtime( $file_name );
				$modified                = date( 'M-d-Y-H-i', $file_posted ); //phpcs:ignore
				$modified_dates[ $name ] = $modified;

				// Update cache.
				set_transient( $id . '_enqueue_date', $modified_dates, 3600 );

				// Return modified date.
				return $modified;
			}
		}
		return '';
	}
}

new ModifiedDateEnqueue();
