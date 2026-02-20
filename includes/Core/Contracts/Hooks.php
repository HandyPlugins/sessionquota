<?php
/**
 * Shared hook contracts.
 *
 * @package SessionLimiter\Core
 */

namespace SessionLimiter\Core\Contracts;

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
	const ACTION_LOGIN_BLOCKED = 'session_limiter_login_blocked';

	/**
	 * Fired when one or more sessions are destroyed by enforcement.
	 */
	const ACTION_SESSION_DESTROYED = 'session_limiter_session_destroyed';

	/**
	 * Filter to provide settings array to core.
	 */
	const FILTER_SETTINGS = 'session_limiter_settings_provider';

	/**
	 * Filter to provide membership-based session limit.
	 */
	const FILTER_MEMBERSHIP_LIMIT = 'session_limiter_membership_limit';

	/**
	 * Filter to provide user-meta key used for per-user limit override.
	 */
	const FILTER_USER_LIMIT_META_KEY = 'session_limiter_user_limit_meta_key';
}
