<?php

/**
 * * Description: Log user's last login as user meta and adds column to Users table to display.
 */

class FP_User_Last_Login {

	public function __construct() {
		if ( defined( 'FP_TRACK_LAST_LOGIN' ) && true === defined( 'FP_TRACK_LAST_LOGIN' ) ) {
			$this->hooks();
		}
	}

	public function hooks() {
		// Activation Hook
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
		// Save user meta on new registration.
		add_action( 'user_register', array( $this, 'registration_create_usermeta' ), 10, 1 );
		// Update usermeta on login
		add_action( 'wp_login', array( $this, 'last_login' ), 12, 3 );
		// Filter applied to the columns on the manage users screen.
		add_filter( 'manage_users_columns', array( $this, 'add_column' ), 12, 3 );
		// Hook to manage custom column on manage users screen.
		add_action( 'manage_users_custom_column', array( $this, 'custom_column_last_login' ), 12, 3 );
		// Filter to add column as sortable column
		add_filter( 'manage_users_sortable_columns', array( $this, 'add_sortable_last_login' ), 12, 3 );
		// Hook for request
		add_filter( 'request', array( $this, 'last_login_orderby' ) );
		// Hook for run a query before fetch users
		add_action( 'pre_user_query', array( $this, 'fp_pre_user_query' ) );
	}

	/**
	 * Callback Function for activation hook
	 * Add user meta of all users if not added
	 *
	 * @author FlowPress (DAC)
	 * @since 1.0
	 */
	public function activation() {
		$users = get_users();
		if ( is_array( $users ) && sizeof( $users ) > 0 ) {
			foreach ( $users as $key => $user ) {
				$meta = get_user_meta( $user->ID, 'last_login', true );
				if ( ! $meta ) {
					update_user_meta( $user->ID, 'last_login', 0 );
				}
			}
		}
	}

	/**
	 * Register usermeta key 'last_login' to zero.
	 *
	 * @author FlowPress (DAC)
	 * @since 1.0
	 * @param int $user_id
	 **/
	public function registration_create_usermeta( $user_id ) {
		update_user_meta( $user_id, 'last_login', 0 );
	}

	/**
	 * Update the login timestamp.
	 *
	 * @author FlowPress (DAC)
	 * @since 1.0
	 * @param string $user_login The user's login name.
	 */
	public function last_login( $user_login ) {
		$user = get_user_by( 'login', $user_login );
		update_user_meta( $user->ID, 'last_login', time() );
	}

	/**
	 * Adds the last login column to the network admin user list.
	 *
	 * @author FlowPress (DAC)
	 * @since  1.0
	 * @param  array $cols The default columns.
	 * @return array
	 */
	public function add_column( $cols ) {
		$cols['fp-last-login'] = __( 'Last Login', 'fp-last-login' );
		return $cols;
	}

	/**
	 * Adds the last login column to the network admin user list.
	 *
	 * @author FlowPress (DAC)
	 * @since 1.0
	 * @param string $value
	 * @param string $column_name
	 * @param int    $user_id
	 * @return string
	 */
	public function custom_column_last_login( $value, $column_name, $user_id ) {
		if ( 'fp-last-login' === $column_name ) {
			$value      = __( '-', 'fp-last-login' );
			$last_login = (int) get_user_meta( $user_id, 'last_login', true );

			if ( $last_login ) {
				$value = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $last_login );
			}
		}

		return $value;
	}

	/**
	 * Register the column as sortable.
	 *
	 * @author FlowPress (DAC)
	 * @since  1.0
	 * @param  array $columns
	 * @return array
	 */
	public function add_sortable_last_login( $columns ) {
		$columns['fp-last-login'] = 'fp-last-login';
		return $columns;
	}

	/**
	 * Handle ordering by last login.
	 *
	 * @author FlowPress (DAC)
	 * @since  1.0
	 * @param  array $vars
	 * @return array
	 */
	public function last_login_orderby( $vars ) {
		if ( isset( $vars['orderby'] ) && 'fp-last-login' == $vars['orderby'] ) {
			$vars = array_merge(
				$vars,
				array(
					'meta_key' => 'last_login', // WPCS: slow query ok.
					'orderby'  => 'meta_value',
					'order'    => 'asc',
				)
			);
		}
		return $vars;
	}

	/**
	 * Handle query for sorting before listing.
	 *
	 * @author FlowPress (DAC)
	 * @since  1.0
	 * @param  string $user_search
	 * @return mixed
	 */
	public function fp_pre_user_query( $user_search ) {
		global $wpdb,$pagenow;
		if ( 'users' !== $pagenow ) {
			return;
		}
		$vars = $user_search->query_vars;
		if ( 'fp-last-login' === $vars['orderby'] ) {
			$user_search->query_from   .= " INNER JOIN {$wpdb->usermeta} m1 ON {$wpdb->users}.ID=m1.user_id AND (m1.meta_key='last_login')";
			$user_search->query_orderby = ' ORDER BY UPPER(m1.meta_value) ' . $vars['order'];
		}
	}
}

$fp_last_login = new FP_User_Last_Login();
