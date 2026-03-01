<?php
/**
 * Settings page template
 *
 * @package SessionQuota
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
	<?php settings_errors( 'sessionquota_settings' ); ?>

	<div class="sessionquota-settings">
		<div class="max-w-5xl mx-auto py-6">

			<!-- Tab Panels -->
			<div class="sessionquota-tab-panels">
				<!-- General Settings Tab Panel -->
				<div id="sessionquota-panel-general"
					role="tabpanel"
					aria-labelledby="sessionquota-tab-general"
					class="sessionquota-tab-panel <?php echo 'general' !== $current_tab ? 'hidden' : ''; ?>"
					tabindex="0">
					<div class="bg-white shadow overflow-hidden sm:rounded-lg">
						<div class="px-4 py-5 sm:p-6">
							<form method="post" action="options.php">
								<?php
								settings_fields( 'sessionquota_settings' );
								?>
								<input type="hidden" name="sessionquota_settings[_tab]" value="general">
								<?php
								do_settings_sections( 'sessionquota' );
								submit_button( __( 'Save Settings', 'sessionquota' ) );
								?>
							</form>
						</div>
					</div
				</div>
			</div>
		</div>
	</div>
</div>
