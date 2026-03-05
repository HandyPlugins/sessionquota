<?php
/**
 * Shared hook contracts.
 *
 * @package SessionQuota\Core
 */

namespace SessionQuota\Core\Contracts;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Hook names used by the shared core engine.
 */
class Hooks {

	/**
	 * Fired when login is blocked by session limits.
	 */
	const ACTION_LOGIN_BLOCKED = 'sessionquota_login_blocked';

	/**
	 * Fired when one or more sessions are destroyed by enforcement.
	 */
	const ACTION_SESSION_DESTROYED = 'sessionquota_session_destroyed';

	/**
	 * Filter to provide settings array to core.
	 */
	const FILTER_SETTINGS = 'sessionquota_settings_provider';

	/**
	 * Filter to resolve the effective limit for a user.
	 */
	const FILTER_RESOLVED_LIMIT = 'sessionquota_resolved_limit';

	/**
	 * Filter to resolve limit info payload for a user.
	 */
	const FILTER_LIMIT_INFO = 'sessionquota_limit_info';
}
