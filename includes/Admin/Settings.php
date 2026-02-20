<?php
/**
 * Admin Settings
 *
 * @package SessionLimiter
 */

namespace SessionLimiter\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Settings class
 */
class Settings {

	/**
	 * Instance of this class.
	 *
	 * @var Settings
	 */
	private static $instance = null;

	/**
	 * Settings page slug.
	 *
	 * @var string
	 */
	private $page_slug = 'session-limiter';

	/**
	 * Current tab.
	 *
	 * @var string
	 */
	private $current_tab = 'general';

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_filter( 'wp_redirect', array( $this, 'preserve_tab_on_redirect' ), 10, 2 );

		// Set current tab.
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$this->current_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general';
	}

	/**
	 * Get instance.
	 *
	 * @return Settings
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Get plugin management capability.
	 *
	 * @return string
	 */
	public static function get_required_capability() {
		return 'manage_options';
	}

	/**
	 * Check if current user can manage plugin settings.
	 *
	 * @return bool
	 */
	public static function current_user_can_manage() {
		return current_user_can( self::get_required_capability() );
	}

	/**
	 * Add admin menu.
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		add_options_page(
			__( 'Session Limiter', 'session-limiter' ),
			__( 'Session Limiter', 'session-limiter' ),
			self::get_required_capability(),
			$this->page_slug,
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Enqueue admin assets.
	 *
	 * @param string $hook Current admin page hook.
	 * @return void
	 */
	public function enqueue_admin_assets( $hook ) {
		// Only load on plugin pages.
		if ( false === strpos( $hook, $this->page_slug ) ) {
			return;
		}

		$asset_file = SESSION_LIMITER_PATH . 'assets/build/admin.asset.php';

		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset = include $asset_file;

		wp_enqueue_style(
			'session-limiter-admin',
			SESSION_LIMITER_URL . 'assets/build/admin.css',
			array(),
			$asset['version']
		);

		wp_enqueue_script(
			'session-limiter-admin',
			SESSION_LIMITER_URL . 'assets/build/admin.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		wp_localize_script(
			'session-limiter-admin',
			'sessionLimiter',
			array(
				'version' => SESSION_LIMITER_VERSION,
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'session_limiter_nonce' ),
			)
		);

		wp_set_script_translations(
			'session-limiter-admin',
			'session-limiter',
			SESSION_LIMITER_PATH . 'languages'
		);
	}

	/**
	 * Get settings option name.
	 *
	 * @return string
	 */
	public static function get_option_name() {
		return \SessionLimiter\Core\Settings\EngineSettings::get_option_name();
	}

	/**
	 * Get default settings.
	 *
	 * Keep shared keys aligned with PRO and preserve PRO-only keys during Free saves.
	 *
	 * @return array Default settings.
	 */
	public static function get_default_settings() {
		return \SessionLimiter\Core\Settings\EngineSettings::get_default_settings();
	}

	/**
	 * Get settings from storage.
	 *
	 * @param mixed $default_value Default value if option doesn't exist.
	 * @return array
	 */
	public static function get_settings( $default_value = false ) {
		return \SessionLimiter\Core\Settings\EngineSettings::get_settings( $default_value );
	}

	/**
	 * Update settings in storage.
	 *
	 * @param array $value Settings array.
	 * @return bool
	 */
	public static function update_settings( $value ) {
		return update_option( self::get_option_name(), $value );
	}

	/**
	 * Delete stored settings.
	 *
	 * @return bool
	 */
	public static function delete_settings() {
		return delete_option( self::get_option_name() );
	}

	/**
	 * Check whether strict single-session mode is enabled.
	 *
	 * @param array|null $settings Optional settings payload.
	 * @return bool
	 */
	public static function is_strict_mode( $settings = null ) {
		return \SessionLimiter\Core\Settings\EngineSettings::is_strict_mode( $settings );
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			'session_limiter_settings',
			self::get_option_name(),
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
				'default'           => self::get_default_settings(),
			)
		);

		add_settings_section(
			'session_limiter_general',
			__( 'General Settings', 'session-limiter' ),
			array( $this, 'render_general_section' ),
			'session-limiter'
		);

		add_settings_field(
			'session_limiter_limit',
			__( 'Concurrent Session Limit', 'session-limiter' ),
			array( $this, 'render_limit_field' ),
			'session-limiter',
			'session_limiter_general'
		);

		add_settings_field(
			'session_limiter_enforcement_mode',
			__( 'Enforcement Mode', 'session-limiter' ),
			array( $this, 'render_enforcement_mode_field' ),
			'session-limiter',
			'session_limiter_general'
		);
	}

	/**
	 * Sanitize settings array.
	 *
	 * @param mixed $settings Settings to sanitize.
	 * @return array Sanitized settings.
	 */
	public function sanitize_settings( $settings ) {
		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		$defaults = self::get_default_settings();

		$existing_raw = self::get_settings( array() );
		if ( ! is_array( $existing_raw ) ) {
			$existing_raw = array();
		}

		$existing  = wp_parse_args( $existing_raw, $defaults );
		$sanitized = wp_parse_args( $existing_raw, $defaults );

		$session_limit              = isset( $settings['session_limit'] ) ? $settings['session_limit'] : $existing['session_limit'];
		$sanitized['session_limit'] = ( is_numeric( $session_limit ) && intval( $session_limit ) >= 0 )
			? absint( $session_limit )
			: $defaults['session_limit'];

		$sanitized['enforcement_mode'] = isset( $settings['enforcement_mode'] )
			? $this->sanitize_enforcement_mode( $settings['enforcement_mode'] )
			: $existing['enforcement_mode'];

		// Preserve shared core settings that are not editable in Free UI.
		$sanitized['frontend_integration_enabled'] = isset( $existing['frontend_integration_enabled'] )
			? (bool) $existing['frontend_integration_enabled']
			: $defaults['frontend_integration_enabled'];

		// Guard against crafted requests: blocked-login recovery settings are PRO-only.
		$sanitized['blocked_login_email_recovery_enabled'] = isset( $existing['blocked_login_email_recovery_enabled'] )
			? (bool) $existing['blocked_login_email_recovery_enabled']
			: (bool) $defaults['blocked_login_email_recovery_enabled'];
		$sanitized['blocked_login_email_cooldown_minutes'] = isset( $existing['blocked_login_email_cooldown_minutes'] )
			? max( 1, min( 60, absint( $existing['blocked_login_email_cooldown_minutes'] ) ) )
			: max( 1, min( 60, absint( $defaults['blocked_login_email_cooldown_minutes'] ) ) );
		$sanitized['blocked_login_email_link_ttl_minutes'] = isset( $existing['blocked_login_email_link_ttl_minutes'] )
			? max( 5, min( 120, absint( $existing['blocked_login_email_link_ttl_minutes'] ) ) )
			: max( 5, min( 120, absint( $defaults['blocked_login_email_link_ttl_minutes'] ) ) );

		$sanitized['role_limits']        = ( isset( $existing['role_limits'] ) && is_array( $existing['role_limits'] ) )
			? $existing['role_limits']
			: array();
		$sanitized['membership_enabled'] = isset( $existing['membership_enabled'] )
			? (bool) $existing['membership_enabled']
			: false;
		$sanitized['membership_limits']  = ( isset( $existing['membership_limits'] ) && is_array( $existing['membership_limits'] ) )
			? $existing['membership_limits']
			: array();

		unset( $sanitized['_tab'] );

		return $sanitized;
	}

	/**
	 * Sanitize enforcement mode.
	 *
	 * @param string $value Value to sanitize.
	 * @return string
	 */
	public function sanitize_enforcement_mode( $value ) {
		$valid_modes = array( 'block', 'logout_oldest', 'logout_all_others' );
		return in_array( $value, $valid_modes, true ) ? $value : 'logout_oldest';
	}

	/**
	 * Preserve tab parameter on settings save redirect.
	 *
	 * @param string $location The redirect URL.
	 * @param int    $status   The HTTP response status code.
	 * @return string
	 */
	public function preserve_tab_on_redirect( $location, $status ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed -- Required by wp_redirect filter.
		if ( false === strpos( $location, 'options-general.php' ) ) {
			return $location;
		}

		if ( false === strpos( $location, 'page=' . $this->page_slug ) ) {
			return $location;
		}

		// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified by WordPress Options API.
		if ( isset( $_POST['session_limiter_settings']['_tab'] ) ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Missing -- Verified by WordPress Options API.
			$tab      = sanitize_key( wp_unslash( $_POST['session_limiter_settings']['_tab'] ) );
			$location = add_query_arg( 'tab', $tab, $location );
		}

		return $location;
	}

	/**
	 * Render general section description.
	 *
	 * @return void
	 */
	public function render_general_section() {
		echo '<p class="text-sm text-gray-600">' . esc_html__( 'Configure how Session Limiter handles concurrent user sessions.', 'session-limiter' ) . '</p>';
	}

	/**
	 * Render limit field.
	 *
	 * @return void
	 */
	public function render_limit_field() {
		$settings = self::get_settings();
		$limit    = isset( $settings['session_limit'] ) ? absint( $settings['session_limit'] ) : 1;
		?>
		<input type="number" name="session_limiter_settings[session_limit]" value="<?php echo esc_attr( $limit ); ?>" min="0" class="w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="session-limit-input">
		<p class="mt-2 text-sm text-gray-600"><?php esc_html_e( 'Maximum number of concurrent sessions allowed per user. Set to 0 for unlimited sessions (session limiting disabled).', 'session-limiter' ); ?></p>
		<?php
	}

	/**
	 * Render enforcement mode field.
	 *
	 * @return void
	 */
	public function render_enforcement_mode_field() {
		$settings     = self::get_settings();
		$current_mode = isset( $settings['enforcement_mode'] ) ? $settings['enforcement_mode'] : 'logout_oldest';
		$limit        = isset( $settings['session_limit'] ) ? absint( $settings['session_limit'] ) : 1;
		$is_disabled  = ( 0 === $limit && 'logout_all_others' !== $current_mode );
		$modes        = \SessionLimiter\Core\Engine\SessionEnforcer::get_enforcement_modes();
		?>
		<div class="space-y-3" id="enforcement-mode-container">
			<?php foreach ( $modes as $mode => $label ) : ?>
				<?php $mode_disabled = ( $is_disabled && 'logout_all_others' !== $mode ); ?>
				<label class="flex items-start <?php echo $mode_disabled ? 'opacity-50' : ''; ?>">
					<input type="radio" name="session_limiter_settings[enforcement_mode]" value="<?php echo esc_attr( $mode ); ?>" <?php checked( $current_mode, $mode ); ?> <?php disabled( $mode_disabled ); ?> class="mt-1 rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
					<span class="ml-3">
						<span class="block text-sm font-medium text-gray-700"><?php echo esc_html( $label ); ?></span>
						<span class="block text-sm text-gray-500"><?php echo esc_html( $this->get_enforcement_mode_description( $mode ) ); ?></span>
					</span>
				</label>
			<?php endforeach; ?>
		</div>
		<?php if ( $is_disabled ) : ?>
			<p class="mt-3 text-sm text-amber-600" id="enforcement-disabled-hint">
				<span class="dashicons dashicons-info" style="font-size: 16px; width: 16px; height: 16px; margin-right: 4px;"></span>
				<?php esc_html_e( 'Set the session limit to 1 or higher to enable block and logout-oldest modes. Strict mode remains available.', 'session-limiter' ); ?>
			</p>
		<?php endif; ?>
		<?php $this->render_block_mode_options_locked_field(); ?>
		<?php
	}

	/**
	 * Render blocked login recovery controls as locked upsell-only UI.
	 *
	 * @return void
	 */
	public function render_block_mode_options_locked_field() {
		$defaults         = self::get_default_settings();
		$settings         = self::get_settings();
		$current_mode     = isset( $settings['enforcement_mode'] ) ? $settings['enforcement_mode'] : 'logout_oldest';
		$show_options     = ( 'block' === $current_mode );
		$cooldown_minutes = isset( $defaults['blocked_login_email_cooldown_minutes'] ) ? max( 1, min( 60, absint( $defaults['blocked_login_email_cooldown_minutes'] ) ) ) : 5;
		$link_ttl_minutes = isset( $defaults['blocked_login_email_link_ttl_minutes'] ) ? max( 5, min( 120, absint( $defaults['blocked_login_email_link_ttl_minutes'] ) ) ) : 30;
		$upgrade_url      = 'https://handyplugins.co/session-limiter-pro/';
		?>
		<div id="session-limiter-block-mode-options" class="mt-4 ml-6 pl-4 border-l-2 border-gray-200 space-y-3 <?php echo $show_options ? '' : 'hidden'; ?>">
			<p class="text-sm font-semibold text-gray-700"><?php esc_html_e( 'Block mode options', 'session-limiter' ); ?></p>
			<label class="inline-flex items-center cursor-not-allowed opacity-60">
				<input type="checkbox" id="session-limiter-blocked-login-email-recovery-enabled" disabled class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
				<span class="ml-2 text-sm text-gray-700"><?php esc_html_e( 'Allow blocked users to recover access via email', 'session-limiter' ); ?></span>
			</label>
			<p class="text-sm text-gray-500">
				<?php esc_html_e( 'When a login is blocked due to the session limit, the user can request a one-time email link to log out other active sessions and try again.', 'session-limiter' ); ?>
			</p>

			<div id="session-limiter-blocked-login-recovery-fields" class="grid grid-cols-1 md:grid-cols-2 gap-4 opacity-60">
				<div>
					<label for="session-limiter-blocked-login-email-cooldown" class="block text-sm font-medium text-gray-700"><?php esc_html_e( 'Email Cooldown (minutes)', 'session-limiter' ); ?></label>
					<input type="number" id="session-limiter-blocked-login-email-cooldown" value="<?php echo esc_attr( $cooldown_minutes ); ?>" min="1" max="60" disabled class="mt-1 w-28 rounded-md border-gray-300 shadow-sm">
				</div>
				<div>
					<label for="session-limiter-blocked-login-email-ttl" class="block text-sm font-medium text-gray-700"><?php esc_html_e( 'Recovery Link Expiry (minutes)', 'session-limiter' ); ?></label>
					<input type="number" id="session-limiter-blocked-login-email-ttl" value="<?php echo esc_attr( $link_ttl_minutes ); ?>" min="5" max="120" disabled class="mt-1 w-28 rounded-md border-gray-300 shadow-sm">
				</div>
			</div>

			<div class="flex flex-wrap items-center justify-between gap-3 rounded-md border border-indigo-100 bg-indigo-50 px-3 py-2.5">
				<span class="inline-flex items-center text-sm font-medium text-indigo-700">
					<span class="dashicons dashicons-lock mr-1.5" style="font-size: 16px; width: 16px; height: 16px;" aria-hidden="true"></span>
					<?php esc_html_e( 'Available in Pro.', 'session-limiter' ); ?>
				</span>
				<a href="<?php echo esc_url( $upgrade_url ); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md shadow-sm !text-white !no-underline bg-indigo-600 hover:!bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
					<?php esc_html_e( 'Upgrade to Pro', 'session-limiter' ); ?>
				</a>
			</div>
		</div>
		<?php
	}

	/**
	 * Get enforcement mode description.
	 *
	 * @param string $mode Enforcement mode.
	 * @return string
	 */
	private function get_enforcement_mode_description( $mode ) {
		$descriptions = array(
			'block'             => __( 'Prevent new login when limit is reached. User must logout from another device first.', 'session-limiter' ),
			'logout_oldest'     => __( 'Remove only the oldest session(s) needed to stay within the limit. Respects the session limit setting.', 'session-limiter' ),
			'logout_all_others' => __( 'Always logout ALL other sessions on new login, keeping only the current session. Ignores the session limit.', 'session-limiter' ),
		);

		return isset( $descriptions[ $mode ] ) ? $descriptions[ $mode ] : '';
	}

	/**
	 * Render settings page.
	 *
	 * @return void
	 */
	public function render_settings_page() {
		if ( ! self::current_user_can_manage() ) {
			return;
		}

		$tabs = $this->get_tabs();

		require_once SESSION_LIMITER_PATH . 'includes/Admin/views/settings.php';
	}

	/**
	 * Get available tabs.
	 *
	 * @return array
	 */
	private function get_tabs() {
		return array(
			'general'    => array(
				'label' => __( 'General', 'session-limiter' ),
				'icon'  => 'dashicons-admin-generic',
			),
			'advanced'   => array(
				'label' => __( 'Advanced', 'session-limiter' ),
				'icon'  => 'dashicons-admin-settings',
				'pro'   => true,
			),
			'tools'      => array(
				'label' => __( 'Tools', 'session-limiter' ),
				'icon'  => 'dashicons-admin-tools',
				'pro'   => true,
			),
			'monitoring' => array(
				'label' => __( 'Monitoring', 'session-limiter' ),
				'icon'  => 'dashicons-chart-area',
				'pro'   => true,
			),
		);
	}

	/**
	 * Get current tab.
	 *
	 * @return string
	 */
	public function get_current_tab() {
		return $this->current_tab;
	}
}
