<?php
/**
 * Admin Settings
 *
 * @package SessionQuota
 */

namespace SessionQuota\Admin;

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
	private $page_slug = 'sessionquota';

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
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
			__( 'SessionQuota', 'sessionquota' ),
			__( 'SessionQuota', 'sessionquota' ),
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

		$asset_file = SESSIONQUOTA_PATH . 'assets/build/admin.asset.php';

		if ( ! file_exists( $asset_file ) ) {
			return;
		}

		$asset = include $asset_file;

		wp_enqueue_style(
			'sessionquota-admin',
			SESSIONQUOTA_URL . 'assets/build/admin.css',
			array(),
			$asset['version']
		);

		wp_enqueue_script(
			'sessionquota-admin',
			SESSIONQUOTA_URL . 'assets/build/admin.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);

		wp_localize_script(
			'sessionquota-admin',
			'SessionQuota',
			array(
				'version' => SESSIONQUOTA_VERSION,
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'sessionquota_nonce' ),
			)
		);

		wp_set_script_translations(
			'sessionquota-admin',
			'sessionquota',
			SESSIONQUOTA_PATH . 'languages'
		);
	}

	/**
	 * Get settings option name.
	 *
	 * @return string
	 */
	public static function get_option_name() {
		return \SessionQuota\Core\Settings\EngineSettings::get_option_name();
	}

	/**
	 * Get default settings.
	 *
	 * @return array Default settings.
	 */
	public static function get_default_settings() {
		return \SessionQuota\Core\Settings\EngineSettings::get_default_settings();
	}

	/**
	 * Get settings from storage.
	 *
	 * @param mixed $default_value Default value if option doesn't exist.
	 * @return array
	 */
	public static function get_settings( $default_value = false ) {
		return \SessionQuota\Core\Settings\EngineSettings::get_settings( $default_value );
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
		return \SessionQuota\Core\Settings\EngineSettings::is_strict_mode( $settings );
	}

	/**
	 * Register settings.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting(
			'sessionquota_settings',
			self::get_option_name(),
			array(
				'type'              => 'array',
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
				'default'           => self::get_default_settings(),
			)
		);

		add_settings_section(
			'sessionquota_general',
			__( 'General Settings', 'sessionquota' ),
			array( $this, 'render_general_section' ),
			'sessionquota'
		);

		add_settings_field(
			'sessionquota_limit',
			__( 'Concurrent Session Limit', 'sessionquota' ),
			array( $this, 'render_limit_field' ),
			'sessionquota',
			'sessionquota_general'
		);

		add_settings_field(
			'sessionquota_enforcement_mode',
			__( 'Enforcement Mode', 'sessionquota' ),
			array( $this, 'render_enforcement_mode_field' ),
			'sessionquota',
			'sessionquota_general'
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
		$existing  = self::get_settings();
		$sanitized = $defaults;
		if ( ! is_array( $existing ) ) {
			$existing = $defaults;
		}

		$session_limit              = isset( $settings['session_limit'] ) ? $settings['session_limit'] : $existing['session_limit'];
		$sanitized['session_limit'] = ( is_numeric( $session_limit ) && intval( $session_limit ) >= 0 )
			? absint( $session_limit )
			: $defaults['session_limit'];

		$sanitized['enforcement_mode'] = isset( $settings['enforcement_mode'] )
			? $this->sanitize_enforcement_mode( $settings['enforcement_mode'] )
			: $existing['enforcement_mode'];

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
	 * Render general section description.
	 *
	 * @return void
	 */
	public function render_general_section() {
		echo '<p class="text-sm text-gray-600">' . esc_html__( 'Configure how SessionQuota handles concurrent user sessions.', 'sessionquota' ) . '</p>';
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
		<input type="number" name="sessionquota_settings[session_limit]" value="<?php echo esc_attr( $limit ); ?>" min="0" class="w-24 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="session-limit-input">
		<p class="mt-2 text-sm text-gray-600"><?php esc_html_e( 'Maximum number of concurrent sessions allowed per user. Set to 0 for unlimited sessions (session limiting disabled).', 'sessionquota' ); ?></p>
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
		$modes        = \SessionQuota\Core\Engine\SessionEnforcer::get_enforcement_modes();
		?>
		<div class="space-y-3" id="enforcement-mode-container">
			<?php foreach ( $modes as $mode => $label ) : ?>
				<?php $mode_disabled = ( $is_disabled && 'logout_all_others' !== $mode ); ?>
				<label class="flex items-start <?php echo $mode_disabled ? 'opacity-50' : ''; ?>" style="gap: 0.75rem;">
					<input type="radio" name="sessionquota_settings[enforcement_mode]" value="<?php echo esc_attr( $mode ); ?>" <?php checked( $current_mode, $mode ); ?> <?php disabled( $mode_disabled ); ?> class="flex-shrink-0 rounded-full border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" style="margin-top: 0.125rem;">
					<span>
						<span class="block text-sm font-medium text-gray-700"><?php echo esc_html( $label ); ?></span>
						<span class="block text-sm text-gray-500"><?php echo esc_html( $this->get_enforcement_mode_description( $mode ) ); ?></span>
					</span>
				</label>
			<?php endforeach; ?>
		</div>
		<?php if ( $is_disabled ) : ?>
			<p class="mt-3 text-sm text-amber-600" id="enforcement-disabled-hint">
				<span class="dashicons dashicons-info" style="font-size: 16px; width: 16px; height: 16px; margin-right: 4px;"></span>
				<?php esc_html_e( 'Set the session limit to 1 or higher to enable block and logout-oldest modes. Strict mode remains available.', 'sessionquota' ); ?>
			</p>
		<?php endif; ?>
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
			'block'             => __( 'Prevent new login when limit is reached. User must logout from another device first.', 'sessionquota' ),
			'logout_oldest'     => __( 'Remove only the oldest session(s) needed to stay within the limit. Respects the session limit setting.', 'sessionquota' ),
			'logout_all_others' => __( 'Always logout ALL other sessions on new login, keeping only the current session. Ignores the session limit.', 'sessionquota' ),
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

		require_once SESSIONQUOTA_PATH . 'includes/Admin/views/settings.php';
	}
}
