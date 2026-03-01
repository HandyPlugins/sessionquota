<?php
/**
 * Plugin Name: SessionQuota
 * Plugin URI: https://handyplugins.co/sessionquota/
 * Description: Limit concurrent user sessions in WordPress with simple session management.
 * Version: 1.0.0
 * Author: HandyPlugins
 * Author URI: https://handyplugins.co/
 * Text Domain: sessionquota
 * Domain Path: /languages
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Requires at least: 5.9
 * Requires PHP: 7.4
 *
 * @package SessionQuota
 */

namespace SessionQuota;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define plugin constants.
define( 'SESSIONQUOTA_VERSION', '1.0.0' );
define( 'SESSIONQUOTA_FILE', __FILE__ );
define( 'SESSIONQUOTA_PATH', plugin_dir_path( __FILE__ ) );
define( 'SESSIONQUOTA_URL', plugin_dir_url( __FILE__ ) );
define( 'SESSIONQUOTA_BASENAME', plugin_basename( __FILE__ ) );

/**
 * Shared request-level winner marker used to avoid ping-pong deactivation.
 */
const SESSIONQUOTA_CONFLICT_WINNER_MARKER = 'SESSIONQUOTA_ACTIVE_EDITION';

/**
 * Transient key for conflict admin notice.
 */
const SESSIONQUOTA_CONFLICT_NOTICE_TRANSIENT = 'sessionquota_conflict_notice';

/**
 * Determine if current user can manage plugins.
 *
 * @return bool
 */
function can_manage_plugins() {
	if ( function_exists( 'wp_get_current_user' ) && function_exists( 'current_user_can' ) ) {
		return current_user_can( 'activate_plugins' ) && current_user_can( 'deactivate_plugins' );
	}

	// During early bootstrap (before pluggable functions), only allow this on plugin admin screens.
	$pagenow = isset( $GLOBALS['pagenow'] ) ? (string) $GLOBALS['pagenow'] : '';
	return in_array( $pagenow, array( 'plugins.php', 'plugins-network.php', 'update.php' ), true );
}

/**
 * Queue a one-time admin notice after auto-deactivation.
 *
 * @param string $message Notice message.
 * @return void
 */
function queue_conflict_notice( $message ) {
	set_transient(
		SESSIONQUOTA_CONFLICT_NOTICE_TRANSIENT,
		array(
			'message' => (string) $message,
			'type'    => 'warning',
		),
		5 * MINUTE_IN_SECONDS
	);
}

/**
 * Render auto-deactivation admin notice.
 *
 * @return void
 */
function render_conflict_notice() {
	$notice = get_transient( SESSIONQUOTA_CONFLICT_NOTICE_TRANSIENT );
	if ( empty( $notice ) || empty( $notice['message'] ) ) {
		return;
	}

	delete_transient( SESSIONQUOTA_CONFLICT_NOTICE_TRANSIENT );

	$type = ! empty( $notice['type'] ) ? sanitize_html_class( $notice['type'] ) : 'warning';
	?>
	<div class="notice notice-<?php echo esc_attr( $type ); ?> is-dismissible">
		<p><?php echo esc_html( $notice['message'] ); ?></p>
	</div>
	<?php
}

add_action( 'admin_notices', __NAMESPACE__ . '\\render_conflict_notice' );

/**
 * Auto-deactivate PRO edition when both are active.
 *
 * @return bool True when bootstrap should bail for this request.
 */
function maybe_handle_edition_conflict() {
	if ( ! is_admin() || ! can_manage_plugins() ) {
		return false;
	}

	if ( defined( SESSIONQUOTA_CONFLICT_WINNER_MARKER ) ) {
		return 'free' !== constant( SESSIONQUOTA_CONFLICT_WINNER_MARKER );
	}

	if ( ! defined( 'SESSIONQUOTA_PRO_FILE' ) ) {
		return false;
	}

	if ( ! function_exists( 'deactivate_plugins' ) || ! function_exists( 'is_plugin_active' ) ) {
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	$pro_plugin_file = plugin_basename( SESSIONQUOTA_PRO_FILE );
	if ( ! is_plugin_active( $pro_plugin_file ) ) {
		return false;
	}

	deactivate_plugins( $pro_plugin_file );

	define( SESSIONQUOTA_CONFLICT_WINNER_MARKER, 'free' );
	queue_conflict_notice( __( 'SessionQuota Pro was automatically deactivated to prevent conflicts. Only one SessionQuota edition can be active at a time.', 'sessionquota' ) );

	return true;
}

if ( maybe_handle_edition_conflict() ) {
	return;
}

/**
 * PSR-4-ish autoloading
 *
 * @since 1.0.0
 */
spl_autoload_register(
	function ( $class_name ) {
		$prefixes = array(
			'SessionQuota\\Core\\' => __DIR__ . '/includes/Core/',
			'SessionQuota\\'       => __DIR__ . '/includes/',
		);

		foreach ( $prefixes as $prefix => $base_dir ) {
			$len = strlen( $prefix );

			if ( strncmp( $prefix, $class_name, $len ) !== 0 ) {
				continue;
			}

			$relative_class = substr( $class_name, $len );
			$file           = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

			if ( file_exists( $file ) ) {
				require $file;
			}

			return;
		}
	}
);

/**
 * Initialize the plugin.
 *
 * @return void
 */
function init() {
	// Initialize admin functionality.
	if ( is_admin() ) {
		Admin\Settings::get_instance();
	}

	// Initialize session enforcement.
	$token_repository = new \SessionQuota\Core\Engine\TokenRepository();
	$limit_resolver   = new \SessionQuota\Core\Engine\LimitResolver();
	$enforcer         = new \SessionQuota\Core\Engine\SessionEnforcer( $token_repository, $limit_resolver );
	$enforcer->init();
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\\init' );

/**
 * Plugin activation hook.
 *
 * @return void
 */
function activate() {
	// Activation logic here if needed.
	flush_rewrite_rules();
}

register_activation_hook( __FILE__, __NAMESPACE__ . '\\activate' );

/**
 * Plugin deactivation hook.
 *
 * @return void
 */
function deactivate() {
	// Deactivation logic here if needed.
	flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, __NAMESPACE__ . '\\deactivate' );
