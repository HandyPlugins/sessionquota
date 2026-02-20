<?php
/**
 * Uninstall Session Limiter.
 *
 * @package SessionLimiter
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

const SESSION_LIMITER_FREE_UNINSTALL_PRO_PLUGIN_FILE = WP_PLUGIN_DIR . '/session-limiter-pro/session-limiter-pro.php';

/**
 * Check whether PRO edition is still installed (active or inactive).
 *
 * @return bool
 */
function session_limiter_free_uninstall_other_edition_exists() {
	return file_exists( SESSION_LIMITER_FREE_UNINSTALL_PRO_PLUGIN_FILE );
}

/**
 * Delete an option/site option according to install type.
 *
 * @param string $option_name Option name.
 * @return void
 */
function session_limiter_free_uninstall_delete_option( $option_name ) {
	if ( is_multisite() ) {
		delete_site_option( $option_name );
		return;
	}

	delete_option( $option_name );
}

/**
 * Delete a transient/site transient according to install type.
 *
 * @param string $transient_name Transient name.
 * @return void
 */
function session_limiter_free_uninstall_delete_transient( $transient_name ) {
	if ( is_multisite() ) {
		delete_site_transient( $transient_name );
		return;
	}

	delete_transient( $transient_name );
}

/**
 * Delete plugin transients by SQL pattern.
 *
 * @return void
 */
function session_limiter_free_uninstall_delete_transient_patterns() {
	global $wpdb;

	if ( is_multisite() ) {
		$patterns = array(
			'_site_transient_session_limiter_geoip_%',
			'_site_transient_timeout_session_limiter_geoip_%',
			'_site_transient_session_limiter_blocked_alert_%',
			'_site_transient_timeout_session_limiter_blocked_alert_%',
			'_site_transient_session_limiter_recovery_token_%',
			'_site_transient_timeout_session_limiter_recovery_token_%',
			'_site_transient_session_limiter_recovery_cooldown_%',
			'_site_transient_timeout_session_limiter_recovery_cooldown_%',
		);

		foreach ( $patterns as $pattern ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Uninstall cleanup.
			$wpdb->query(
				$wpdb->prepare(
					"DELETE FROM {$wpdb->sitemeta} WHERE meta_key LIKE %s",
					$pattern
				)
			);
		}

		return;
	}

	$patterns = array(
		'_transient_session_limiter_geoip_%',
		'_transient_timeout_session_limiter_geoip_%',
		'_transient_session_limiter_blocked_alert_%',
		'_transient_timeout_session_limiter_blocked_alert_%',
		'_transient_session_limiter_recovery_token_%',
		'_transient_timeout_session_limiter_recovery_token_%',
		'_transient_session_limiter_recovery_cooldown_%',
		'_transient_timeout_session_limiter_recovery_cooldown_%',
	);

	foreach ( $patterns as $pattern ) {
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Uninstall cleanup.
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
				$pattern
			)
		);
	}
}

/**
 * Delete all session limiter user meta.
 *
 * @return void
 */
function session_limiter_free_uninstall_delete_user_meta() {
	global $wpdb;

	$meta_keys = array(
		'_session_limiter_limit',
		'_session_limiter_last_country',
		'_session_limiter_last_ip',
	);

	foreach ( $meta_keys as $meta_key ) {
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching -- Uninstall cleanup.
		$wpdb->query(
			$wpdb->prepare(
				"DELETE FROM {$wpdb->usermeta} WHERE meta_key = %s",
				$meta_key
			)
		);
	}
}

/**
 * Recursively remove a directory.
 *
 * @param string $directory Directory path.
 * @return void
 */
function session_limiter_free_uninstall_remove_directory( $directory ) {
	$entries = scandir( $directory );
	if ( ! is_array( $entries ) ) {
		return;
	}

	foreach ( $entries as $entry ) {
		if ( '.' === $entry || '..' === $entry ) {
			continue;
		}

		$path = $directory . DIRECTORY_SEPARATOR . $entry;

		if ( is_dir( $path ) ) {
			session_limiter_free_uninstall_remove_directory( $path );
			if ( is_dir( $path ) ) {
				// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_rmdir -- wp_delete_file() does not remove directories.
				rmdir( $path );
			}
			continue;
		}

		if ( is_file( $path ) || is_link( $path ) ) {
			wp_delete_file( $path );
		}
	}

	if ( is_dir( $directory ) ) {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_rmdir -- wp_delete_file() does not remove directories.
		rmdir( $directory );
	}
}

/**
 * Delete upload artifacts from uploads/session-limiter.
 *
 * @return void
 */
function session_limiter_free_uninstall_cleanup_uploads() {
	$switched = false;

	if ( is_multisite() && function_exists( 'get_main_site_id' ) ) {
		$current_blog_id = function_exists( 'get_current_blog_id' ) ? (int) get_current_blog_id() : 0;
		$main_site_id    = (int) get_main_site_id();

		if ( $main_site_id > 0 && $main_site_id !== $current_blog_id ) {
			switch_to_blog( $main_site_id );
			$switched = true;
		}
	}

	$uploads = wp_upload_dir( null, false, false );

	if ( $switched ) {
		restore_current_blog();
	}

	if ( empty( $uploads['basedir'] ) ) {
		return;
	}

	$plugin_upload_dir = trailingslashit( $uploads['basedir'] ) . 'session-limiter';

	if ( ! is_dir( $plugin_upload_dir ) ) {
		return;
	}

	session_limiter_free_uninstall_remove_directory( $plugin_upload_dir );
}

/**
 * Clear scheduled hooks.
 *
 * @return void
 */
function session_limiter_free_uninstall_clear_scheduled_hooks() {
	$hooks = array(
		'session_limiter_cleanup_logs',
		'session_limiter_update_geoip_database',
	);

	$switched = false;

	if ( is_multisite() && function_exists( 'get_main_site_id' ) ) {
		$current_blog_id = function_exists( 'get_current_blog_id' ) ? (int) get_current_blog_id() : 0;
		$main_site_id    = (int) get_main_site_id();

		if ( $main_site_id > 0 && $main_site_id !== $current_blog_id ) {
			switch_to_blog( $main_site_id );
			$switched = true;
		}
	}

	foreach ( $hooks as $hook ) {
		wp_clear_scheduled_hook( $hook );
	}

	if ( $switched ) {
		restore_current_blog();
	}
}

/**
 * Perform full data cleanup when no edition remains installed.
 *
 * @return void
 */
function session_limiter_free_uninstall_full_cleanup() {
	global $wpdb;

	$option_names = array(
		'session_limiter_settings',
		'session_limiter_monitoring_settings',
		'session_limiter_admin_alerts',
		'session_limiter_maxmind_settings',
		'session_limiter_license_key',
		'session_limiter_db_version',
		'session_limiter_delete_shared_settings_on_uninstall',
	);

	foreach ( $option_names as $option_name ) {
		session_limiter_free_uninstall_delete_option( $option_name );
	}

	$transient_names = array(
		'session_limiter_license_info',
		'session_limiter_site_activation_notice',
	);

	foreach ( $transient_names as $transient_name ) {
		session_limiter_free_uninstall_delete_transient( $transient_name );
	}

	session_limiter_free_uninstall_delete_transient_patterns();
	session_limiter_free_uninstall_delete_user_meta();

	session_limiter_free_uninstall_clear_scheduled_hooks();

	$table_name         = ( is_multisite() ? $wpdb->base_prefix : $wpdb->prefix ) . 'session_limiter_security_logs';
	$escaped_table_name = esc_sql( $table_name );
	// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange, WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- Uninstall cleanup.
	$wpdb->query( "DROP TABLE IF EXISTS `{$escaped_table_name}`" );

	session_limiter_free_uninstall_cleanup_uploads();
}

if ( session_limiter_free_uninstall_other_edition_exists() ) {
	return;
}

session_limiter_free_uninstall_full_cleanup();
