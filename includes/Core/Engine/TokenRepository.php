<?php
/**
 * Token Repository
 *
 * Wrapper around WordPress session tokens functionality.
 *
 * @package SessionQuota\Core
 */

namespace SessionQuota\Core\Engine;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * TokenRepository class
 */
class TokenRepository {

	/**
	 * Get session tokens for a user.
	 *
	 * @param int $user_id User ID.
	 * @return array Array of session tokens keyed by verifier hash.
	 */
	public function get_sessions( $user_id ) {
		$sessions = get_user_meta( $user_id, 'session_tokens', true );

		if ( ! is_array( $sessions ) || empty( $sessions ) ) {
			return array();
		}

		$now = time();

		foreach ( $sessions as $verifier => $session ) {
			if ( ! is_array( $session ) ) {
				unset( $sessions[ $verifier ] );
				continue;
			}

			$expires = isset( $session['expiration'] ) ? absint( $session['expiration'] ) : 0;
			if ( $expires < $now ) {
				unset( $sessions[ $verifier ] );
			}
		}

		return $sessions;
	}

	/**
	 * Get count of active sessions for a user.
	 *
	 * @param int $user_id User ID.
	 * @return int Number of active sessions.
	 */
	public function count_sessions( $user_id ) {
		return count( $this->get_sessions( $user_id ) );
	}

	/**
	 * Destroy a specific session by its verifier (token hash).
	 *
	 * Note: WP_Session_Tokens::destroy() expects the raw token, but get_all()
	 * returns verifier hashes. So we need to directly manipulate the user meta.
	 *
	 * @param int    $user_id User ID.
	 * @param string $verifier Session verifier (token hash) to destroy.
	 * @return void
	 */
	public function destroy_session( $user_id, $verifier ) {
		$sessions = get_user_meta( $user_id, 'session_tokens', true );

		if ( ! is_array( $sessions ) || empty( $sessions ) ) {
			return;
		}

		if ( isset( $sessions[ $verifier ] ) ) {
			unset( $sessions[ $verifier ] );
			update_user_meta( $user_id, 'session_tokens', $sessions );
		}
	}

	/**
	 * Destroy all sessions for a user except the specified token.
	 *
	 * @param int    $user_id User ID.
	 * @param string $keep_token Token to keep active.
	 * @return void
	 */
	public function destroy_other_sessions( $user_id, $keep_token ) {
		$sessions = \WP_Session_Tokens::get_instance( $user_id );
		$sessions->destroy_others( $keep_token );
	}

	/**
	 * Get the oldest session token for a user.
	 *
	 * @param int $user_id User ID.
	 * @return string|null Token hash of the oldest session, or null if no sessions.
	 */
	public function get_oldest_session( $user_id ) {
		$sessions = $this->get_sessions( $user_id );

		if ( empty( $sessions ) ) {
			return null;
		}

		$oldest_token = null;
		$oldest_time  = PHP_INT_MAX;

		foreach ( $sessions as $token => $session ) {
			if ( isset( $session['login'] ) && $session['login'] < $oldest_time ) {
				$oldest_time  = $session['login'];
				$oldest_token = $token;
			}
		}

		return $oldest_token;
	}
}
