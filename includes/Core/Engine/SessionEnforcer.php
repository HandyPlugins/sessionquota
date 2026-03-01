<?php
/**
 * Session Enforcer
 *
 * Applies enforcement strategies when session limit is exceeded.
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
 * SessionEnforcer class
 */
class SessionEnforcer {

	/**
	 * Enforcement mode: Block new login.
	 */
	const MODE_BLOCK = 'block';

	/**
	 * One-time cookie name used to show a blocked-login notice on wp-login.php.
	 */
	const BLOCKED_LOGIN_NOTICE_COOKIE = 'sessionquota_login_blocked';

	/**
	 * Cookie name used to identify the blocked user for one-click recovery email.
	 */
	const BLOCKED_LOGIN_RECOVERY_COOKIE = 'sessionquota_login_blocked_recovery';

	/**
	 * Lifetime in seconds for the blocked-login recovery cookie.
	 */
	const BLOCKED_LOGIN_RECOVERY_COOKIE_TTL = 600;

	/**
	 * Global flag key used to mark blocked login state for current request.
	 */
	const BLOCKED_LOGIN_PROMPT_GLOBAL = 'sessionquota_blocked_login_prompt';

	/**
	 * Enforcement mode: Logout oldest session.
	 */
	const MODE_LOGOUT_OLDEST = 'logout_oldest';

	/**
	 * Enforcement mode: Logout all other sessions.
	 */
	const MODE_LOGOUT_ALL_OTHERS = 'logout_all_others';

	/**
	 * Token repository instance.
	 *
	 * @var TokenRepository
	 */
	private $token_repository;

	/**
	 * Limit resolver instance.
	 *
	 * @var LimitResolver
	 */
	private $limit_resolver;

	/**
	 * Session tokens already handled during this request.
	 *
	 * @var array<string, bool>
	 */
	private $processed_auth_tokens = array();

	/**
	 * Session tokens that should not be sent to the browser.
	 *
	 * @var array<string, bool>
	 */
	private $blocked_auth_tokens = array();

	/**
	 * Constructor.
	 *
	 * @param TokenRepository $token_repository Token repository instance.
	 * @param LimitResolver   $limit_resolver Limit resolver instance.
	 */
	public function __construct( TokenRepository $token_repository, LimitResolver $limit_resolver ) {
		$this->token_repository = $token_repository;
		$this->limit_resolver   = $limit_resolver;
	}

	/**
	 * Initialize enforcement hooks.
	 *
	 * @return void
	 */
	public function init() {
		// Use wp_authenticate_user for blocking mode.
		add_filter( 'wp_authenticate_user', array( $this, 'enforce_limit' ), 10, 1 );
		// Enforce for all successful logins, including non-standard/passwordless flows.
		add_action( 'set_auth_cookie', array( $this, 'enforce_on_auth_cookie' ), 20, 6 );
		// Prevent sending blocked cookies (fallback when login bypasses wp_authenticate_user).
		add_filter( 'send_auth_cookies', array( $this, 'maybe_prevent_auth_cookie_send' ), 10, 6 );
		// Show blocked-login error notice on wp-login.php (including reauth flows).
		add_filter( 'wp_login_errors', array( $this, 'maybe_add_blocked_login_wp_error' ), 10, 2 );
	}

	/**
	 * Enforce session limit when a new auth cookie/session token is created.
	 *
	 * This runs for all successful login methods that call wp_set_auth_cookie(),
	 * including passwordless flows that may skip wp_login/wp_authenticate_user.
	 *
	 * @param string $auth_cookie Authentication cookie value.
	 * @param int    $expire      The time the login grace period expires.
	 * @param int    $expiration  The time when the authentication cookie expires.
	 * @param int    $user_id     User ID.
	 * @param string $scheme      Authentication scheme.
	 * @param string $token       User's session token.
	 * @return void
	 */
	public function enforce_on_auth_cookie( $auth_cookie, $expire, $expiration, $user_id, $scheme, $token ) {
		$user_id = (int) $user_id;

		if ( $user_id <= 0 || empty( $token ) ) {
			return;
		}

		if ( $this->should_skip_auth_cookie_enforcement( $user_id, $token ) ) {
			return;
		}

		$strict_single_session = EngineSettings::is_strict_mode();
		if ( $strict_single_session ) {
			$this->enforce_strict_single_session( $user_id, $token );
			return;
		}

		// Check if limiting is enabled.
		if ( ! $this->limit_resolver->is_enabled() ) {
			return;
		}

		$mode  = $this->get_enforcement_mode();
		$limit = $this->limit_resolver->get_limit( $user_id );

		// If limit is 0, unlimited sessions are allowed.
		if ( $limit <= 0 ) {
			return;
		}

		// Includes the newly created session token.
		$current_sessions = $this->token_repository->count_sessions( $user_id );

		switch ( $mode ) {
			case self::MODE_BLOCK:
				// At this point the new token already exists, so ">" means the new login exceeded limit.
				if ( $current_sessions <= $limit ) {
					return;
				}

				$session_manager = \WP_Session_Tokens::get_instance( $user_id );
				$session_manager->destroy( $token );

				$token_key                               = $this->get_auth_cookie_token_key( $user_id, $token );
				$this->blocked_auth_tokens[ $token_key ] = true;
				$this->set_blocked_login_notice_cookie();
				$this->set_blocked_login_recovery_cookie( $user_id );

				/**
				 * Fires when a login is blocked due to session limit.
				 *
				 * @param int    $user_id User ID.
				 * @param string $reason  Block reason.
				 */
					// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound -- Hook constant is prefixed in Contracts\Hooks.
					do_action( Hooks::ACTION_LOGIN_BLOCKED, $user_id, 'session_limit_exceeded' );
				break;

			case self::MODE_LOGOUT_OLDEST:
				if ( $current_sessions <= $limit ) {
					return;
				}

				// Remove just enough old sessions to get back to the limit.
				$sessions_to_remove = $current_sessions - $limit;
				$this->logout_oldest_sessions( $user_id, $token, $sessions_to_remove );
				break;

			case self::MODE_LOGOUT_ALL_OTHERS:
				// Always destroy all other sessions, keeping only the current one.
				$other_sessions_count = $current_sessions - 1; // Excluding current.
				$this->token_repository->destroy_other_sessions( $user_id, $token );

				if ( $other_sessions_count > 0 ) {
					/**
					 * Fires when sessions are terminated due to logout_all_others mode.
					 *
					 * @param int    $user_id User ID.
					 * @param string $reason  Termination reason.
					 * @param array  $context Additional context.
					 */
					do_action(
						// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound -- Hook constant is prefixed in Contracts\Hooks.
						Hooks::ACTION_SESSION_DESTROYED,
						$user_id,
						'logout_all_others',
						array( 'sessions_removed' => $other_sessions_count )
					);
				}
				break;
		}
	}

	/**
	 * Apply strict single-session enforcement.
	 *
	 * @param int    $user_id User ID.
	 * @param string $token   Current session token.
	 * @return void
	 */
	private function enforce_strict_single_session( $user_id, $token ) {
		$current_sessions     = $this->token_repository->count_sessions( $user_id );
		$other_sessions_count = max( 0, $current_sessions - 1 );

		$this->token_repository->destroy_other_sessions( $user_id, $token );

		if ( $other_sessions_count <= 0 ) {
			return;
		}

		/**
		 * Fires when sessions are terminated by strict single-session mode.
		 *
		 * @param int    $user_id User ID.
		 * @param string $reason  Termination reason.
		 * @param array  $context Additional context.
		 */
		do_action(
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound -- Hook constant is prefixed in Contracts\Hooks.
			Hooks::ACTION_SESSION_DESTROYED,
			$user_id,
			'strict_single_session',
			array(
				'sessions_removed' => $other_sessions_count,
				'strict_mode'      => true,
			)
		);
	}

	/**
	 * Enforce session limit on login (blocking mode only).
	 *
	 * @param \WP_User|\WP_Error $user User object or WP_Error.
	 * @return \WP_User|\WP_Error User object or WP_Error if blocked.
	 */
	public function enforce_limit( $user ) {
		// If already an error, pass it through.
		if ( is_wp_error( $user ) ) {
			return $user;
		}

		// Check if limiting is enabled.
		if ( ! $this->limit_resolver->is_enabled() ) {
			return $user;
		}

		// Only handle blocking mode here.
		$mode = $this->get_enforcement_mode();
		if ( self::MODE_BLOCK !== $mode ) {
			return $user;
		}

		// Get the limit for this user.
		$limit = $this->limit_resolver->get_limit( $user->ID );

		// If limit is 0, unlimited sessions are allowed.
		if ( $limit <= 0 ) {
			return $user;
		}

		// Get current session count.
		$current_sessions = $this->token_repository->count_sessions( $user->ID );

		// If under the limit, allow login.
		if ( $current_sessions < $limit ) {
			return $user;
		}

		// Limit exceeded - block login.
		return $this->block_login( $user );
	}

	/**
	 * Prevent setting auth cookies for tokens that were blocked post-authentication.
	 *
	 * @param bool   $send       Whether to send auth cookies.
	 * @param int    $expire     The time the login grace period expires.
	 * @param int    $expiration The time when the authentication cookie expires.
	 * @param int    $user_id    User ID.
	 * @param string $scheme     Authentication scheme.
	 * @param string $token      User's session token.
	 * @return bool
	 */
	public function maybe_prevent_auth_cookie_send( $send, $expire, $expiration, $user_id, $scheme, $token ) {
		$token_key = $this->get_auth_cookie_token_key( (int) $user_id, (string) $token );

		if ( ! isset( $this->blocked_auth_tokens[ $token_key ] ) ) {
			return $send;
		}

		unset( $this->blocked_auth_tokens[ $token_key ] );
		return false;
	}

	/**
	 * Add login error when a fallback block occurred during a previous request.
	 *
	 * @param \WP_Error $errors              Existing login errors.
	 * @param string    $_unused_redirect_to Redirect URL.
	 * @return \WP_Error
	 */
	public function maybe_add_blocked_login_wp_error( $errors, $_unused_redirect_to ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed -- Hook signature requires this parameter.
		if ( empty( $_COOKIE[ self::BLOCKED_LOGIN_NOTICE_COOKIE ] ) ) {
			return $errors;
		}

		$this->mark_blocked_login_prompt();
		$this->clear_blocked_login_notice_cookie();

		if ( ! ( $errors instanceof \WP_Error ) ) {
			$errors = new \WP_Error();
		}

		if ( ! $errors->get_error_message( 'session_limit_exceeded' ) ) {
			$errors->add(
				'session_limit_exceeded',
				__( 'Session limit exceeded. Please log out from another device first.', 'sessionquota' )
			);
		}

		return $errors;
	}

	/**
	 * Block login and return error.
	 *
	 * @param \WP_User $user User object.
	 * @return \WP_Error
	 */
	private function block_login( $user ) {
		$this->mark_blocked_login_prompt();
		$this->set_blocked_login_recovery_cookie( $user->ID );

		/**
		 * Fires when a login is blocked due to session limit.
		 *
		 * @param int    $user_id User ID.
		 * @param string $reason  Block reason.
		 */
			// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound -- Hook constant is prefixed in Contracts\Hooks.
			do_action( Hooks::ACTION_LOGIN_BLOCKED, $user->ID, 'session_limit_exceeded' );

		return new \WP_Error(
			'session_limit_exceeded',
			__( 'Session limit exceeded. Please log out from another device first.', 'sessionquota' )
		);
	}

	/**
	 * Mark current request as blocked by session limit.
	 *
	 * @return void
	 */
	private function mark_blocked_login_prompt() {
		// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Dynamic global key is prefixed by BLOCKED_LOGIN_PROMPT_GLOBAL.
		$GLOBALS[ self::BLOCKED_LOGIN_PROMPT_GLOBAL ] = true;
	}

	/**
	 * Logout the oldest sessions, excluding the current one.
	 *
	 * @param int    $user_id          User ID.
	 * @param string $current_token    The current session token to preserve.
	 * @param int    $count            Number of sessions to remove.
	 * @return void
	 */
	private function logout_oldest_sessions( $user_id, $current_token, $count = 1 ) {
		$sessions = get_user_meta( $user_id, 'session_tokens', true );

		if ( ! is_array( $sessions ) || empty( $sessions ) ) {
			return;
		}

		// Hash the current token to get the verifier for comparison.
		$current_verifier = hash( 'sha256', $current_token );

		// Build array of sessions with their login times, excluding current.
		$other_sessions = array();
		foreach ( $sessions as $verifier => $session ) {
			if ( $verifier === $current_verifier ) {
				continue;
			}
			$other_sessions[ $verifier ] = isset( $session['login'] ) ? $session['login'] : 0;
		}

		// Sort by login time (oldest first).
		asort( $other_sessions );

		// Remove the oldest sessions up to $count.
		$removed = 0;
		foreach ( $other_sessions as $verifier => $login_time ) {
			if ( $removed >= $count ) {
				break;
			}
			unset( $sessions[ $verifier ] );
			++$removed;
		}

		if ( $removed > 0 ) {
			update_user_meta( $user_id, 'session_tokens', $sessions );

			/**
			 * Fires when sessions are terminated due to session limit enforcement.
			 *
			 * @param int    $user_id User ID.
			 * @param string $reason  Termination reason.
			 * @param array  $context Additional context.
			 */
			do_action(
				// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.DynamicHooknameFound -- Hook constant is prefixed in Contracts\Hooks.
				Hooks::ACTION_SESSION_DESTROYED,
				$user_id,
				'oldest_session_terminated',
				array( 'sessions_removed' => $removed )
			);
		}
	}

	/**
	 * Get the current enforcement mode.
	 *
	 * @return string Enforcement mode.
	 */
	private function get_enforcement_mode() {
		$settings = EngineSettings::get_settings();
		if ( EngineSettings::is_strict_mode( $settings ) ) {
			return self::MODE_LOGOUT_ALL_OTHERS;
		}

		$mode = isset( $settings['enforcement_mode'] ) ? $settings['enforcement_mode'] : self::MODE_LOGOUT_OLDEST;

		// Validate the mode.
		$valid_modes = array( self::MODE_BLOCK, self::MODE_LOGOUT_OLDEST, self::MODE_LOGOUT_ALL_OTHERS );

		if ( ! in_array( $mode, $valid_modes, true ) ) {
			return self::MODE_LOGOUT_OLDEST;
		}

		return $mode;
	}

	/**
	 * Determine whether auth-cookie enforcement should be skipped.
	 *
	 * @param int    $user_id User ID.
	 * @param string $token   Session token.
	 * @return bool
	 */
	private function should_skip_auth_cookie_enforcement( $user_id, $token ) {
		$token_key = $this->get_auth_cookie_token_key( $user_id, $token );

		// Avoid duplicate processing when multiple callbacks run for the same token.
		if ( isset( $this->processed_auth_tokens[ $token_key ] ) ) {
			return true;
		}

		$this->processed_auth_tokens[ $token_key ] = true;

		// Ignore auth cookie refreshes for the current request/session token.
		$current_request_token = wp_get_session_token();
		if ( empty( $current_request_token ) ) {
			return false;
		}

		$current_user = wp_get_current_user();
		if ( ! ( $current_user instanceof \WP_User ) ) {
			return false;
		}

		return (int) $current_user->ID === (int) $user_id && hash_equals( $current_request_token, $token );
	}

	/**
	 * Build a stable in-request array key for auth-cookie token tracking.
	 *
	 * @param int    $user_id User ID.
	 * @param string $token   Session token.
	 * @return string
	 */
	private function get_auth_cookie_token_key( $user_id, $token ) {
		return $user_id . ':' . $token;
	}

	/**
	 * Set one-time cookie for blocked-login notice.
	 *
	 * @return void
	 */
	private function set_blocked_login_notice_cookie() {
		$this->set_blocked_login_cookie(
			self::BLOCKED_LOGIN_NOTICE_COOKIE,
			'1',
			time() + MINUTE_IN_SECONDS
		);
	}

	/**
	 * Set one-time cookie carrying blocked user identity for recovery action.
	 *
	 * @param int $user_id User ID.
	 * @return void
	 */
	private function set_blocked_login_recovery_cookie( $user_id ) {
		$user_id = absint( $user_id );
		if ( $user_id <= 0 ) {
			return;
		}

		$expires = time() + self::BLOCKED_LOGIN_RECOVERY_COOKIE_TTL;
		$value   = self::build_blocked_login_recovery_cookie_value( $user_id, $expires );

		$this->set_blocked_login_cookie(
			self::BLOCKED_LOGIN_RECOVERY_COOKIE,
			$value,
			$expires
		);
	}

	/**
	 * Read blocked-login recovery cookie and return valid user ID.
	 *
	 * @return int User ID or 0 when missing/invalid/expired.
	 */
	public static function get_blocked_login_recovery_user_id_from_cookie() {
		if ( empty( $_COOKIE[ self::BLOCKED_LOGIN_RECOVERY_COOKIE ] ) ) {
			return 0;
		}

		return self::parse_blocked_login_recovery_cookie_value(
			sanitize_text_field( wp_unslash( $_COOKIE[ self::BLOCKED_LOGIN_RECOVERY_COOKIE ] ) )
		);
	}

	/**
	 * Build signed blocked-login recovery cookie value.
	 *
	 * @param int $user_id User ID.
	 * @param int $expires Expiration timestamp.
	 * @return string
	 */
	private static function build_blocked_login_recovery_cookie_value( $user_id, $expires ) {
		$data      = absint( $user_id ) . '|' . absint( $expires );
		$signature = hash_hmac( 'sha256', $data, wp_salt( 'auth' ) );

		return $data . '|' . $signature;
	}

	/**
	 * Parse and validate signed blocked-login recovery cookie value.
	 *
	 * @param string $value Cookie value.
	 * @return int User ID or 0 when invalid.
	 */
	private static function parse_blocked_login_recovery_cookie_value( $value ) {
		$parts = explode( '|', (string) $value );
		if ( 3 !== count( $parts ) ) {
			return 0;
		}

		$user_id   = absint( $parts[0] );
		$expires   = absint( $parts[1] );
		$signature = sanitize_text_field( $parts[2] );

		if ( $user_id <= 0 || $expires <= 0 || $expires < time() ) {
			return 0;
		}

		$data     = $user_id . '|' . $expires;
		$expected = hash_hmac( 'sha256', $data, wp_salt( 'auth' ) );

		if ( ! hash_equals( $expected, $signature ) ) {
			return 0;
		}

		return $user_id;
	}

	/**
	 * Clear blocked-login notice cookie.
	 *
	 * @return void
	 */
	private function clear_blocked_login_notice_cookie() {
		$this->set_blocked_login_cookie(
			self::BLOCKED_LOGIN_NOTICE_COOKIE,
			'',
			time() - HOUR_IN_SECONDS
		);

		unset( $_COOKIE[ self::BLOCKED_LOGIN_NOTICE_COOKIE ] );
	}

	/**
	 * Set blocked-login cookie across WordPress cookie paths.
	 *
	 * @param string $name    Cookie name.
	 * @param string $value   Cookie value.
	 * @param int    $expires Cookie expiration timestamp.
	 * @return void
	 */
	private function set_blocked_login_cookie( $name, $value, $expires ) {
		if ( headers_sent() ) {
			return;
		}

		$paths = array();

		if ( defined( 'COOKIEPATH' ) && COOKIEPATH ) {
			$paths[] = COOKIEPATH;
		}

		if ( defined( 'SITECOOKIEPATH' ) && SITECOOKIEPATH ) {
			$paths[] = SITECOOKIEPATH;
		}

		if ( empty( $paths ) ) {
			$paths[] = '/';
		}

		$paths = array_unique( $paths );

		foreach ( $paths as $path ) {
			setcookie(
				$name,
				$value,
				array(
					'expires'  => $expires,
					'path'     => $path,
					'domain'   => defined( 'COOKIE_DOMAIN' ) ? COOKIE_DOMAIN : '',
					'secure'   => is_ssl(),
					'httponly' => true,
					'samesite' => 'Lax',
				)
			);
		}

		$_COOKIE[ $name ] = $value;
	}

	/**
	 * Get available enforcement modes.
	 *
	 * @return array Array of mode key => label pairs.
	 */
	public static function get_enforcement_modes() {
		return array(
			self::MODE_BLOCK             => __( 'Block new login', 'sessionquota' ),
			self::MODE_LOGOUT_OLDEST     => __( 'Logout oldest session', 'sessionquota' ),
			self::MODE_LOGOUT_ALL_OTHERS => __( 'Logout all other sessions', 'sessionquota' ),
		);
	}
}
