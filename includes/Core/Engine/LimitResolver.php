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
	 * Priority order:
	 * 1. User-specific override
	 * 2. Membership-based limit (highest among active memberships)
	 * 3. Role-based limit (highest among user roles)
	 * 4. Global default limit
	 *
	 * @param int $user_id User ID.
	 * @return int Maximum number of allowed sessions. Returns 0 for unlimited.
	 */
	public function get_limit( $user_id ) {
		$strict_single_session = EngineSettings::is_strict_mode();
		if ( $strict_single_session ) {
			return 1;
		}

		// 1. Check user-specific override first.
		$user_limit = $this->get_user_limit( $user_id );
		if ( null !== $user_limit ) {
			return $user_limit;
		}

		// 2. Check membership-based limit.
		$membership_limit = $this->get_membership_limit( $user_id );
		if ( null !== $membership_limit ) {
			return $membership_limit;
		}

		// 3. Check role-based limit.
		$role_limit = $this->get_role_limit( $user_id );
		if ( null !== $role_limit ) {
			return $role_limit;
		}

		// 4. Fall back to global limit.
		return $this->get_global_limit();
	}

	/**
	 * Get user-specific session limit override.
	 *
	 * @param int $user_id User ID.
	 * @return int|null Session limit or null if not set.
	 */
	public function get_user_limit( $user_id ) {
		$limit = get_user_meta( $user_id, EngineSettings::get_user_limit_meta_key(), true );

		// Empty string means no override.
		if ( '' === $limit || false === $limit ) {
			return null;
		}

		return (int) $limit;
	}

	/**
	 * Set user-specific session limit override.
	 *
	 * @param int      $user_id User ID.
	 * @param int|null $limit   Session limit or null to remove override.
	 * @return bool True on success.
	 */
	public function set_user_limit( $user_id, $limit ) {
		$meta_key = EngineSettings::get_user_limit_meta_key();

		if ( null === $limit || '' === $limit ) {
			return delete_user_meta( $user_id, $meta_key );
		}

		return update_user_meta( $user_id, $meta_key, (int) $limit );
	}

	/**
	 * Get membership-based session limit.
	 *
	 * @param int $user_id User ID.
	 * @return int|null Session limit or null if no membership limit applies.
	 */
	public function get_membership_limit( $user_id ) {
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound -- Hook constant is prefixed in Contracts\Hooks.
		$membership_limit = apply_filters( Hooks::FILTER_MEMBERSHIP_LIMIT, null, (int) $user_id );

		if ( null === $membership_limit || '' === $membership_limit ) {
			return null;
		}

		return (int) $membership_limit;
	}

	/**
	 * Get role-based session limit.
	 *
	 * Returns the highest limit among all user roles.
	 *
	 * @param int $user_id User ID.
	 * @return int|null Session limit or null if no role limit is set.
	 */
	public function get_role_limit( $user_id ) {
		$user = get_userdata( $user_id );

		if ( ! $user || empty( $user->roles ) ) {
			return null;
		}

		$settings    = EngineSettings::get_settings();
		$role_limits = isset( $settings['role_limits'] ) ? $settings['role_limits'] : array();

		if ( empty( $role_limits ) ) {
			return null;
		}

		$highest_limit = null;

		foreach ( $user->roles as $role ) {
			if ( isset( $role_limits[ $role ] ) && '' !== $role_limits[ $role ] ) {
				$limit = (int) $role_limits[ $role ];

				// 0 means unlimited - that's the highest possible.
				if ( 0 === $limit ) {
					return 0;
				}

				if ( null === $highest_limit || $limit > $highest_limit ) {
					$highest_limit = $limit;
				}
			}
		}

		return $highest_limit;
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
	 * Useful for debugging and displaying in admin.
	 *
	 * @param int $user_id User ID.
	 * @return array Array with resolved limit and source.
	 */
	public function get_limit_info( $user_id ) {
		if ( EngineSettings::is_strict_mode() ) {
			return array(
				'limit'  => 1,
				'source' => 'strict',
				'label'  => __( 'Strict Single Session', 'sessionquota' ),
			);
		}

		$user_limit = $this->get_user_limit( $user_id );
		if ( null !== $user_limit ) {
			return array(
				'limit'  => $user_limit,
				'source' => 'user',
				'label'  => __( 'User Override', 'sessionquota' ),
			);
		}

		$membership_limit = $this->get_membership_limit( $user_id );
		if ( null !== $membership_limit ) {
			return array(
				'limit'  => $membership_limit,
				'source' => 'membership',
				'label'  => __( 'Membership Level', 'sessionquota' ),
			);
		}

		$role_limit = $this->get_role_limit( $user_id );
		if ( null !== $role_limit ) {
			return array(
				'limit'  => $role_limit,
				'source' => 'role',
				'label'  => __( 'User Role', 'sessionquota' ),
			);
		}

		return array(
			'limit'  => $this->get_global_limit(),
			'source' => 'global',
			'label'  => __( 'Global Default', 'sessionquota' ),
		);
	}
}
