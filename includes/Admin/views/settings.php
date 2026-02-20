<?php
/**
 * Settings page template
 *
 * @package SessionLimiter
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template variable.
$current_tab = isset( $this ) ? $this->get_current_tab() : 'general';
?>

<div class="wrap">
	<h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<?php settings_errors( 'session_limiter_settings' ); ?>

	<div class="session-limiter-settings">
		<div class="max-w-5xl mx-auto py-6">
			<!-- Tabs Navigation -->
			<div class="session-limiter-tabs-container bg-white rounded-lg shadow mb-6">
				<nav class="session-limiter-tabs-nav" role="tablist" aria-label="<?php esc_attr_e( 'Settings tabs', 'session-limiter' ); ?>">
					<?php // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template loop variables. ?>
					<?php foreach ( $tabs as $tab_key => $tab_data ) : ?>
						<?php
						// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template loop variables.
						$is_active = $current_tab === $tab_key;
						// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template loop variables.
						$tab_label = is_array( $tab_data ) ? $tab_data['label'] : $tab_data;
						// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template loop variables.
						$tab_icon = is_array( $tab_data ) && isset( $tab_data['icon'] ) ? $tab_data['icon'] : '';
						// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound -- Template loop variables.
						$is_pro = is_array( $tab_data ) && isset( $tab_data['pro'] ) && $tab_data['pro'];
						?>
						<button type="button"
							id="session-limiter-tab-<?php echo esc_attr( $tab_key ); ?>"
							class="session-limiter-tab-button <?php echo $is_active ? 'session-limiter-tab-active' : ''; ?>"
							role="tab"
							aria-selected="<?php echo $is_active ? 'true' : 'false'; ?>"
							aria-controls="session-limiter-panel-<?php echo esc_attr( $tab_key ); ?>"
							data-tab="<?php echo esc_attr( $tab_key ); ?>"
							tabindex="<?php echo $is_active ? '0' : '-1'; ?>">
							<?php if ( $tab_icon ) : ?>
								<span class="dashicons <?php echo esc_attr( $tab_icon ); ?>" aria-hidden="true"></span>
							<?php endif; ?>
							<span class="session-limiter-tab-label"><?php echo esc_html( $tab_label ); ?></span>
							<?php if ( $is_pro ) : ?>
								<span class="session-limiter-pro-badge">PRO</span>
							<?php endif; ?>
						</button>
					<?php endforeach; ?>
				</nav>
			</div>

			<!-- Tab Panels -->
			<div class="session-limiter-tab-panels">
				<!-- General Settings Tab Panel -->
				<div id="session-limiter-panel-general"
					role="tabpanel"
					aria-labelledby="session-limiter-tab-general"
					class="session-limiter-tab-panel <?php echo 'general' !== $current_tab ? 'hidden' : ''; ?>"
					tabindex="0">
					<div class="bg-white shadow overflow-hidden sm:rounded-lg">
						<div class="px-4 py-5 sm:p-6">
							<form method="post" action="options.php">
								<?php
								settings_fields( 'session_limiter_settings' );
								?>
								<input type="hidden" name="session_limiter_settings[_tab]" value="general">
								<?php
								do_settings_sections( 'session-limiter' );
								submit_button( __( 'Save Settings', 'session-limiter' ) );
								?>
							</form>
						</div>
					</div>

				</div>

				<!-- Advanced Tab Panel (PRO Feature - Locked) -->
				<div id="session-limiter-panel-advanced"
					role="tabpanel"
					aria-labelledby="session-limiter-tab-advanced"
					class="session-limiter-tab-panel <?php echo 'advanced' !== $current_tab ? 'hidden' : ''; ?>"
					tabindex="0">
					<?php require_once SESSION_LIMITER_PATH . 'includes/Admin/views/advanced.php'; ?>
				</div>

				<!-- Tools Tab Panel (PRO Feature - Locked) -->
				<div id="session-limiter-panel-tools"
					role="tabpanel"
					aria-labelledby="session-limiter-tab-tools"
					class="session-limiter-tab-panel <?php echo 'tools' !== $current_tab ? 'hidden' : ''; ?>"
					tabindex="0">
					<?php require_once SESSION_LIMITER_PATH . 'includes/Admin/views/tools.php'; ?>
				</div>

				<!-- Monitoring Tab Panel (PRO Feature - Locked) -->
				<div id="session-limiter-panel-monitoring"
					role="tabpanel"
					aria-labelledby="session-limiter-tab-monitoring"
					class="session-limiter-tab-panel <?php echo 'monitoring' !== $current_tab ? 'hidden' : ''; ?>"
					tabindex="0">
					<?php require_once SESSION_LIMITER_PATH . 'includes/Admin/views/monitoring.php'; ?>
				</div>
			</div>
		</div>
	</div>
</div>
