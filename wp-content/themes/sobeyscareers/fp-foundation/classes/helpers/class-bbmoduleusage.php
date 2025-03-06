<?php
/**
 * Plugin Name: FP - Beaver Builder Module Usage
 * Description: Export a CSV list of used modules throughout all post types on the site.
 * Version: 1.0.1
 * Author: Mario Dabek ( Flowpress )
 * Author URI: https://flowpress.com
 * Plugin URI: https://flowpress.com/
 *
 * @package fp-foundation
 */

if ( ! class_exists( 'BBModuleUsage' ) ) {
	/**
	 * Main class to run the plugin.
	 */
	class BBModuleUsage {

		/**
		 * This plugin gets extended, so the hooks were added to an init function to avoid duplicate menu items.
		 */
		public function __construct() {}

		/**
		 * Initialize the plugin
		 *
		 * @see self::setup_menu()
		 * @see self::activate(())
		 */
		public function init() {
			add_action( 'admin_menu', array( $this, 'setup_menu' ) );
			register_activation_hook( __FILE__, array( $this, 'activate' ) );
		}

		/**
		 * Create a WP admin menu item under BB templates post type.
		 *
		 * @see self::settings_page()
		 *
		 * @return void
		 */
		public function setup_menu() {

			add_submenu_page(
				'edit.php?post_type=fl-builder-template',
				'Module Usage',
				'Module Usage',
				'manage_options',
				'bb-module-usage',
				array( $this, 'settings_page' ),
			);

		}

		/**
		 * On activate, check to see if the uploads dir exists. Create it if it doesn't.
		 *
		 * @return void
		 */
		public function activate() {
			$upload_dir = $this->get_upload_dir();
			if ( ! is_dir( $upload_dir ) ) {
				mkdir( $upload_dir, 0700 );
			}
		}

		/**
		 * Return the path of the module usage directory.
		 *
		 * @return string $upload_dir
		 */
		public function get_upload_dir() {
			$upload     = wp_upload_dir();
			$upload_dir = $upload['basedir'];
			$upload_dir = $upload_dir . '/fp-bb-module-usage';
			return $upload_dir;
		}

		/**
		 * Return the url of the module usage directory.
		 *
		 * @return string $upload_url
		 */
		public function get_upload_url() {
			$upload     = wp_upload_dir();
			$upload_url = $upload['baseurl'];
			$upload_url = $upload_url . '/fp-bb-module-usage';
			return $upload_url;
		}

		/**
		 * Get a list of post types that are BB enabled. Exclude ACF and ninja forms.
		 *
		 * @return object WP_Query $query;
		 */
		public function get_post_types_with_bb_data() {
			global $wp_post_types, $wpdb;
			$post_types = array_keys( $wp_post_types );

			foreach ( $post_types as $type ) {
				if ( strpos( $type, 'acf' ) > -1 ) {
					continue;
				}
				if ( strpos( $type, 'nf_sub' ) > -1 ) {
					continue;
				}
				$post_types_kept[] = $type;
			}

			$args = array(
				'orderby'        => 'title',
				'order'          => 'ASC',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'post_type'      => $post_types_kept,
				'meta_key'       => '_fl_builder_data',
			);

			$query = new WP_Query( $args );
			return $query;
		}

		/**
		 * Create a settings option field.
		 *
		 * @return void
		 */
		public function cq_callback() {
			$options = get_option( 'fp_bb_module_usage_emails' );
			?>
			<textarea cols='40' rows='5' name='fp_bb_module_usage_emails'><?php echo esc_attr( $options ); ?></textarea>
			<?php
		}

		/**
		 * Display module usage on the admin settings page.
		 */
		public function send_reports() {

			$query              = $this->get_post_types_with_bb_data();
			$filename           = '/export_' . date( 'd-m-Y_H_i_s' ) . '.csv'; // phpcs:ignore.
			$module_count       = 0;
			$body               = '';
			$module_usage_count = array();
			$module_usage_urls  = array();

			while ( $query->have_posts() ) :

				$query->the_post();

				$id           = get_the_ID();
				$builder_data = get_post_meta( $id, '_fl_builder_data', true );

				if ( ! $builder_data ) {
					continue;
				}

				$col1              = get_post_type();
				$col2              = $this->clean_string( get_the_title() );
				$col3              = get_the_permalink();
				$module_col        = false;
				$page_module_count = 0;

				foreach ( $builder_data as $key => $value ) {
					if ( 'module' !== $value->type ) {
						continue;
					}
					$page_module_count++;
					if ( false === strpos( $module_col, '[' . $value->settings->type . ']' ) ) {
						if ( ! isset( $module_usage_urls[ $value->settings->type ] ) || ! in_array( $col3, $module_usage_urls[ $value->settings->type ], true ) ) {
							$module_usage_urls[ $value->settings->type ][] = $col3;
						}
						$module_col .= '[' . $value->settings->type . '], ';
						if ( ! empty( $module_usage_count[ $value->settings->type ] ) ) {
							$module_usage_count[ $value->settings->type ]++;
						} else {
							$module_usage_count[ $value->settings->type ] = 1;
						}
					}
				}

				if ( $module_col ) {
					$module_col = substr( $module_col, 0, -2 );
				}

				if ( $page_module_count > $module_count ) {
					$module_count = $page_module_count;
				}

				$post_type_data[ $col1 ][] = $col1 . ', ' . $col2 . ', ' . $col3 . ', ' . $module_col . "\n";

				$module_test_url = '/?tpl=module-test&module=animated_images';

				$body .= "<tr><td><a href='$col3'>$col2 [</a><a href='/wp-admin/post.php?post=$id&action=edit'>Edit] </a><a href='{$col3}?fl_builder'>[Edit BB] </a></td><td>$col1</td><td>$module_col</td></tr>";

			endwhile;

			$body_module_count = '';

			asort( $module_usage_count );
			$module_usage_count = array_reverse( $module_usage_count );

			foreach ( $module_usage_count as $type => $count ) {
				$urls = '';
				foreach ( $module_usage_urls[ $type ] as $key => $url ) {
					$urls .= "<br/><a href='$url'>$url</a>";
				}
				$body_module_count .= "<tr><td>$type</td><td>$count<div class='urls'>$urls</div></td></tr>";
			}

			$output = "
				<h1>Beaver Builder - Module Usage</h1>
				<table class='widefat fixed'>
					<thead>
						<th class='manage-column'>Page</th>
						<th class='manage-column'>Post Type</th>
						<th class='manage-column'>Modules</th>
					</thead>
					<tbody>
						{$body}
					</tbody>
				</table>
				<br/><br/>
				<table class='widefat fixed'>
					<thead>
						<th class='manage-column'>Module</th>
						<th class='manage-column'>Count</th>
					</thead>
					<tbody>
						{$body_module_count}
					</tbody>
				</table>
			";
			echo wp_kses( $output, 'post' );

			// Create csv file in the uploads folder.

			if ( ! empty( $_GET['create_report'] ) ) { // phpcs:ignore.
				$csv = 'Post Type, Title, URL';

				for ( $i = 0; $i < $module_count; $i++ ) {
					$csv .= ', Module ' . ( $i + 1 );
				}

				$csv .= "\n";

				foreach ( $post_type_data as $type => $lines ) {
					foreach ( $lines as $line ) {
						$csv .= $line;
					}
				}
				file_put_contents( $this->get_upload_dir() . $filename, $csv ); // phpcs:ignore.
				if ( isset( $_GET['page'] ) && 'bb-module-usage' == $_GET['page'] ) { // phpcs:ignore.
					echo '<h1 style="color:red;">The report has been generated.</h1>';
					echo '<a target="_blank" href="' . esc_url( $this->get_upload_url() . $filename ) . '">Click here to download manually.</a>';
				}
			}

			die();

			// Email results.

			/**
			 * $email_list = get_option( 'fp_bb_module_usage_emails' );
			 * // Send notification emails with csv file
			 * if( $email_list != '' ) {
			 * $headers = 'From: Wattco Website <info@wattco.com>' . "\r\n";
			 * $file = $this->get_upload_dir() . $filename;
			 * $email_list = json_decode($email_list);
			 * wp_mail($email_list, 'Wattco Form Lead Generation Report', 'Here is the report for the last week of your form submissions.', $headers, $file);
			 * }
			 */
		}

		/**
		 * Clean string by removing new lines, commas and replacing double quotes with backticks.
		 *
		 * @param string $string is the content to clean.
		 *
		 * @return string
		 */
		public function clean_string( $string ) {
			$string = str_replace( "\n", ' ', $string );
			$string = str_replace( ',', ' ', $string );
			$string = str_replace( '"', '``', $string );
			return $string;
		}

		/**
		 * Create the settings page.
		 *
		 * @param bool $ajax (Optional) Is the page being called via ajax.
		 *
		 * @see self::send_reports()
		 */
		public function settings_page( $ajax = false ) {
				$this->send_reports();
			?>
		<div id="nfer_settings">
			<!-- <form action='options.php' method='post'>
				<h2>Ninja Forms Email Reports Options</h2>
				<?php
				/**
				 * Uncomment to use NF email options.
				 * settings_fields( 'bb-module-usage-settings-section' );
				 * do_settings_sections( 'fp-bb-module-usage-settings' );
				 * submit_button();
				 */
				?>
				<div class="save_warning">unsaved changes present, make sure you save your changes</div>
			</form> -->
			<h2>Module Usage</h2>
			<p>Welcome to the Beaver Builder Module Usage Page</p>
			<p>Click the action button below to create a CSV report of all modules used on this site.</p>
			<div>
				<a href="?post_type=fl-builder-template&page=bb-module-usage&create_report" class="button button-danger send_reports">Create Report</a>
			</div>
			<!-- <h2>Email List</h2>
			<table class="emails">
				<tr>
					<td></td>
					<td></td>
				</tr>
			</table>
			<div>
				<label>
					Email
				</label>
				<input class="arg_1" value=''/>
			</div>
			<div>
				<div class="button button-primary add_email">Add New Email</div>
			</div> -->
		</div>
<?php
		}
	}
	$bb_module_usage = new BBModuleUsage();
	$bb_module_usage->init();
}
