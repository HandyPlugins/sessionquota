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
	 * Settings option key used by plugin editions.
	 */
	const OPTION_NAME = 'sessionquota_settings';

	/**
	 * Get settings option name.
	 *
	 * @return string
	 */
	public static function get_option_name() {
		return self::OPTION_NAME;
	}

	/**
	 * Get base shared settings schema.
	 *
	 * @return array
	 */
	public static function get_default_settings() {
		return array(
			'session_limit'    => 1,
			'enforcement_mode' => 'logout_oldest',
		);
	}

	/**
	 * Get settings for engine usage.
	 *
	 * @param mixed $default_value Default value.
	 * @return array
	 */
	public static function get_settings( $default_value = false ) {
		if ( false === $default_value ) {
			$default_value = self::get_default_settings();
		}

		// Allow external integrations to provide storage source (for example network options).
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
}
