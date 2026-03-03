<?php
/**
 * Uninstall SessionQuota.
 *
 * @package SessionQuota
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

if ( defined( 'SESSIONQUOTA_PRO_FILE' ) ) {
	return;
}

// remove only plugin settings.
delete_option( 'sessionquota_settings' );
