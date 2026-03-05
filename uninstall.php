<?php
/**
 * Uninstall SessionQuota.
 *
 * @package SessionQuota
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Remove only plugin settings.
delete_option( 'sessionquota_settings' );
