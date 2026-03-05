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
	<h1 class="wp-heading-inline"><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<?php settings_errors( 'sessionquota_settings' ); ?>

	<div class="sessionquota-settings">
		<div class="max-w-5xl mx-auto py-6">
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
	</div>
</div>
