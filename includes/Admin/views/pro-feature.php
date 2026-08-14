<?php
/**
 * Locked SessionQuota Pro feature preview.
 *
 * @package SessionQuota
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$sessionquota_pro_content = array(
	'advanced'   => array(
		'icon'        => 'dashicons-filter',
		'title'       => __( 'Advanced session limits', 'sessionquota' ),
		'description' => __( 'Apply the right concurrent login limit to each role, membership, or individual user.', 'sessionquota' ),
		'features'    => array(
			array( 'dashicons-groups', __( 'Role-based limits', 'sessionquota' ), __( 'Give administrators, members, and customers different session allowances.', 'sessionquota' ) ),
			array( 'dashicons-tickets-alt', __( 'Membership limits', 'sessionquota' ), __( 'Set limits for supported MemberPress and Paid Memberships Pro levels.', 'sessionquota' ) ),
			array( 'dashicons-admin-users', __( 'Per-user overrides', 'sessionquota' ), __( 'Make exceptions for individual users without changing the global policy.', 'sessionquota' ) ),
			array( 'dashicons-email-alt', __( 'Blocked login recovery', 'sessionquota' ), __( 'Let blocked users securely end other sessions through a one-time email link.', 'sessionquota' ) ),
		),
	),
	'tools'      => array(
		'icon'        => 'dashicons-admin-tools',
		'title'       => __( 'Session management tools', 'sessionquota' ),
		'description' => __( 'Resolve account and security issues quickly from wp-admin or the command line.', 'sessionquota' ),
		'features'    => array(
			array( 'dashicons-dismiss', __( 'Force logout a user', 'sessionquota' ), __( 'Find a user and immediately terminate their active sessions.', 'sessionquota' ) ),
			array( 'dashicons-admin-site-alt3', __( 'Site-wide session cleanup', 'sessionquota' ), __( 'End sessions across the site while preserving your current administrator session.', 'sessionquota' ) ),
			array( 'dashicons-database-export', __( 'Export and import settings', 'sessionquota' ), __( 'Move a known configuration between sites or keep a backup.', 'sessionquota' ) ),
			array( 'dashicons-editor-code', __( 'WP-CLI commands', 'sessionquota' ), __( 'Inspect limits, review sessions, and automate session management.', 'sessionquota' ) ),
		),
	),
	'monitoring' => array(
		'icon'        => 'dashicons-chart-area',
		'title'       => __( 'Security monitoring and alerts', 'sessionquota' ),
		'description' => __( 'Understand when session rules are triggered and respond to suspicious activity.', 'sessionquota' ),
		'features'    => array(
			array( 'dashicons-list-view', __( 'Session event logs', 'sessionquota' ), __( 'Review logins, blocked attempts, and forced session terminations.', 'sessionquota' ) ),
			array( 'dashicons-location-alt', __( 'Optional device and country data', 'sessionquota' ), __( 'Add IP, browser, device, and country context when your privacy policy allows it.', 'sessionquota' ) ),
			array( 'dashicons-bell', __( 'Dashboard and email alerts', 'sessionquota' ), __( 'Stay informed when configured session activity thresholds are reached.', 'sessionquota' ) ),
			array( 'dashicons-shield', __( 'Privacy controls', 'sessionquota' ), __( 'Configure data collection and retention for your monitoring workflow.', 'sessionquota' ) ),
		),
	),
);

$sessionquota_content     = $sessionquota_pro_content[ $sessionquota_pro_tab ];
$sessionquota_upgrade_url = add_query_arg(
	array(
		'utm_source'   => 'sessionquota',
		'utm_medium'   => 'plugin',
		'utm_campaign' => 'free_to_pro',
		'utm_content'  => $sessionquota_pro_tab . '_tab',
	),
	'https://handyplugins.co/sessionquota-pro/'
);
?>

<div class="sessionquota-pro-preview">
	<div class="sessionquota-pro-preview-header">
		<div class="sessionquota-pro-preview-icon">
			<span class="dashicons <?php echo esc_attr( $sessionquota_content['icon'] ); ?>" aria-hidden="true"></span>
		</div>
		<div>
			<span class="sessionquota-pro-eyebrow"><span class="dashicons dashicons-lock" aria-hidden="true"></span><?php esc_html_e( 'SessionQuota Pro', 'sessionquota' ); ?></span>
			<h2><?php echo esc_html( $sessionquota_content['title'] ); ?></h2>
			<p><?php echo esc_html( $sessionquota_content['description'] ); ?></p>
		</div>
	</div>

	<div class="sessionquota-pro-feature-grid">
		<?php foreach ( $sessionquota_content['features'] as $sessionquota_feature ) : ?>
			<div class="sessionquota-pro-feature-card">
				<span class="dashicons <?php echo esc_attr( $sessionquota_feature[0] ); ?>" aria-hidden="true"></span>
				<div>
					<h3><?php echo esc_html( $sessionquota_feature[1] ); ?></h3>
					<p><?php echo esc_html( $sessionquota_feature[2] ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="sessionquota-pro-callout">
		<div>
			<h3><?php esc_html_e( 'Ready for more control?', 'sessionquota' ); ?></h3>
			<p><?php esc_html_e( 'These features are a preview and are not active in the free edition.', 'sessionquota' ); ?></p>
		</div>
		<a href="<?php echo esc_url( $sessionquota_upgrade_url ); ?>" target="_blank" rel="noopener noreferrer" class="sessionquota-upgrade-button">
			<?php esc_html_e( 'Unlock with Pro', 'sessionquota' ); ?>
			<span class="dashicons dashicons-arrow-right-alt2" aria-hidden="true"></span>
		</a>
	</div>
</div>
