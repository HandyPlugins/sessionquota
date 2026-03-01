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
	 * Filter to provide membership-based session limit.
	 */
	const FILTER_MEMBERSHIP_LIMIT = 'sessionquota_membership_limit';

	/**
	 * Filter to provide user-meta key used for per-user limit override.
	 */
	const FILTER_USER_LIMIT_META_KEY = 'sessionquota_user_limit_meta_key';
}
