<?php
/**
 * Limit Resolver
 *
 * Resolves the session limit for a user.
 *
 * @package SessionQuota\Core
 */

namespace SessionQuota\Core\Engine;

use SessionQuota\Core\Contracts\Hooks;
use SessionQuota\Core\Settings\EngineSettings;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * LimitResolver class
 */
class LimitResolver {

	/**
	 * Get the session limit for a user.
	 *
	 * Base shared behavior resolves strict/global only.
	 * External integrations can extend this via FILTER_RESOLVED_LIMIT.
	 *
	 * @param int $user_id User ID.
	 * @return int Maximum number of allowed sessions. Returns 0 for unlimited.
	 */
	public function get_limit( $user_id ) {
		$user_id = (int) $user_id;
		$limit   = EngineSettings::is_strict_mode() ? 1 : $this->get_global_limit();

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound -- Hook constant is prefixed in Contracts\Hooks.
		$filtered = apply_filters( Hooks::FILTER_RESOLVED_LIMIT, $limit, $user_id, $this );
		if ( is_numeric( $filtered ) ) {
			return (int) $filtered;
		}

		return (int) $limit;
	}

	/**
	 * Get global session limit.
	 *
	 * @return int Global session limit.
	 */
	public function get_global_limit() {
		$settings = EngineSettings::get_settings();
		return isset( $settings['session_limit'] ) ? (int) $settings['session_limit'] : 1;
	}

	/**
	 * Check if limiting is enabled.
	 *
	 * Session limiting is enabled when the global limit is >= 1.
	 * A limit of 0 means unlimited sessions (effectively disabled).
	 *
	 * @return bool True if limiting is enabled.
	 */
	public function is_enabled() {
		if ( EngineSettings::is_strict_mode() ) {
			return true;
		}

		return $this->get_global_limit() >= 1;
	}

	/**
	 * Get limit resolution info for a user.
	 *
	 * Base shared behavior resolves strict/global only.
	 * External integrations can extend this via FILTER_LIMIT_INFO.
	 *
	 * @param int $user_id User ID.
	 * @return array Array with resolved limit and source.
	 */
	public function get_limit_info( $user_id ) {
		$user_id = (int) $user_id;

		if ( EngineSettings::is_strict_mode() ) {
			$info = array(
				'limit'  => 1,
				'source' => 'strict',
				'label'  => __( 'Strict Single Session', 'sessionquota' ),
			);
		} else {
			$info = array(
				'limit'  => $this->get_global_limit(),
				'source' => 'global',
				'label'  => __( 'Global Default', 'sessionquota' ),
			);
		}

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound -- Hook constant is prefixed in Contracts\Hooks.
		$filtered = apply_filters( Hooks::FILTER_LIMIT_INFO, $info, $user_id, $this );

		if ( is_array( $filtered ) && isset( $filtered['limit'], $filtered['source'], $filtered['label'] ) ) {
			return $filtered;
		}

		return $info;
	}
}
