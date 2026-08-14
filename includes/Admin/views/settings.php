<?php
/**
 * Settings page template.
 *
 * @package SessionQuota
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="wrap">
	<div class="sessionquota-page-header">
		<div>
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<p><?php esc_html_e( 'Control concurrent WordPress logins and reduce account sharing.', 'sessionquota' ); ?></p>
		</div>
	</div>

	<?php settings_errors( 'sessionquota_settings' ); ?>

	<div class="sessionquota-settings">
		<div class="max-w-5xl mx-auto py-6">
			<div class="sessionquota-tabs-container">
				<nav class="sessionquota-tabs-nav" role="tablist" aria-label="<?php esc_attr_e( 'SessionQuota settings', 'sessionquota' ); ?>">
					<?php foreach ( $sessionquota_tabs as $sessionquota_tab_key => $sessionquota_tab_data ) : ?>
						<?php
						$sessionquota_is_active = $sessionquota_current_tab === $sessionquota_tab_key;
						$sessionquota_is_pro    = ! empty( $sessionquota_tab_data['pro'] );
						?>
						<button
							type="button"
							id="sessionquota-tab-<?php echo esc_attr( $sessionquota_tab_key ); ?>"
							class="sessionquota-tab-button <?php echo $sessionquota_is_active ? 'sessionquota-tab-active' : ''; ?>"
							role="tab"
							aria-selected="<?php echo $sessionquota_is_active ? 'true' : 'false'; ?>"
							aria-controls="sessionquota-panel-<?php echo esc_attr( $sessionquota_tab_key ); ?>"
							data-tab="<?php echo esc_attr( $sessionquota_tab_key ); ?>"
							tabindex="<?php echo $sessionquota_is_active ? '0' : '-1'; ?>"
						>
							<span class="dashicons <?php echo esc_attr( $sessionquota_tab_data['icon'] ); ?>" aria-hidden="true"></span>
							<span><?php echo esc_html( $sessionquota_tab_data['label'] ); ?></span>
							<?php if ( $sessionquota_is_pro ) : ?>
								<span class="sessionquota-pro-badge"><?php esc_html_e( 'PRO', 'sessionquota' ); ?></span>
							<?php endif; ?>
						</button>
					<?php endforeach; ?>
				</nav>
			</div>

			<div class="sessionquota-tab-panels">
				<div
					id="sessionquota-panel-general"
					class="sessionquota-tab-panel <?php echo 'general' !== $sessionquota_current_tab ? 'hidden' : ''; ?>"
					role="tabpanel"
					aria-labelledby="sessionquota-tab-general"
					tabindex="0"
				>
					<div class="bg-white shadow overflow-hidden sm:rounded-lg">
						<div class="px-4 py-5 sm:p-6">
							<form method="post" action="options.php">
								<?php
								settings_fields( 'sessionquota_settings' );
								do_settings_sections( 'sessionquota' );
								submit_button( __( 'Save Settings', 'sessionquota' ) );
								?>
							</form>
						</div>
					</div>
				</div>

				<?php foreach ( array( 'advanced', 'tools', 'monitoring' ) as $sessionquota_pro_tab ) : ?>
					<div
						id="sessionquota-panel-<?php echo esc_attr( $sessionquota_pro_tab ); ?>"
						class="sessionquota-tab-panel <?php echo $sessionquota_pro_tab !== $sessionquota_current_tab ? 'hidden' : ''; ?>"
						role="tabpanel"
						aria-labelledby="sessionquota-tab-<?php echo esc_attr( $sessionquota_pro_tab ); ?>"
						tabindex="0"
					>
						<?php require SESSIONQUOTA_PATH . 'includes/Admin/views/pro-feature.php'; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>
