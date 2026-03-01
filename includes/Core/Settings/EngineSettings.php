<?php
/**
 * Shared settings schema/helper for engine.
 *
 * @package SessionQuota\Core
 */

namespace SessionQuota\Core\Settings;

use SessionQuota\Core\Contracts\Hooks;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Shared settings helper used by engine classes.
 */
class EngineSettings {

	/**
	 * Shared option key used by both Free and PRO.
	 */
	const OPTION_NAME = 'sessionquota_settings';

	/**
	 * Default user meta key for per-user session limit override.
	 */
	const DEFAULT_USER_LIMIT_META_KEY = '_sessionquota_limit';

	/**
	 * Get settings option name.
	 *
	 * @return string
	 */
	public static function get_option_name() {
		return self::OPTION_NAME;
	}

	/**
	 * Get shared default settings schema.
	 *
	 * @return array
	 */
	public static function get_default_settings() {
		return array(
			'session_limit'                        => 1,
			'enforcement_mode'                     => 'logout_oldest',
			'role_limits'                          => array(),
			'membership_enabled'                   => false,
			'membership_limits'                    => array(),
			'frontend_integration_enabled'         => true,
			'blocked_login_email_recovery_enabled' => false,
			'blocked_login_email_cooldown_minutes' => 5,
			'blocked_login_email_link_ttl_minutes' => 30,
		);
	}

	/**
	 * Get shared settings for engine usage.
	 *
	 * Pro can override retrieval (for network options) via filter.
	 *
	 * @param mixed $default_value Default value.
	 * @return array
	 */
	public static function get_settings( $default_value = false ) {
		if ( false === $default_value ) {
			$default_value = self::get_default_settings();
		}

		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound -- Hook constant is prefixed in Contracts\Hooks.
		$filtered = apply_filters( Hooks::FILTER_SETTINGS, null, $default_value );
		if ( is_array( $filtered ) ) {
			return $filtered;
		}

		return get_option( self::get_option_name(), $default_value );
	}

	/**
	 * Check whether strict single-session mode is enabled.
	 *
	 * @param array|null $settings Optional settings payload.
	 * @return bool
	 */
	public static function is_strict_mode( $settings = null ) {
		if ( null === $settings ) {
			$settings = self::get_settings();
		}

		if ( ! is_array( $settings ) ) {
			return false;
		}

		$mode = isset( $settings['enforcement_mode'] ) ? sanitize_key( $settings['enforcement_mode'] ) : '';

		return 'logout_all_others' === $mode;
	}

	/**
	 * Get user meta key used for per-user override.
	 *
	 * @return string
	 */
	public static function get_user_limit_meta_key() {
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound -- Hook constant is prefixed in Contracts\Hooks.
		return (string) apply_filters( Hooks::FILTER_USER_LIMIT_META_KEY, self::DEFAULT_USER_LIMIT_META_KEY );
	}
}
