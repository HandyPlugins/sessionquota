<?php
/**
 * Advanced settings tab template with paywall overlay
 *
 * Shows advanced settings preview with PRO upgrade overlay.
 *
 * @package SessionLimiter
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="session-limiter-pro-feature-container relative">
	<!-- PRO Overlay -->
	<div class="session-limiter-pro-overlay absolute inset-0 z-10 flex items-start justify-center pt-12" style="background: rgba(255, 255, 255, 0.6); backdrop-filter: blur(1px);">
		<div class="session-limiter-pro-overlay-content text-center p-6 max-w-lg bg-white rounded-lg shadow-xl">
			<div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-indigo-100 mb-4">
				<svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
				</svg>
			</div>
			<h3 class="text-xl font-bold text-gray-900 mb-2"><?php esc_html_e( 'PRO Feature', 'session-limiter' ); ?></h3>
			<p class="text-gray-600 mb-4"><?php esc_html_e( 'This feature is available in Session Limiter Pro. Upgrade to unlock advanced session management features.', 'session-limiter' ); ?></p>
			
			<div class="bg-gray-50 rounded-lg p-4 mb-4 text-left">
				<h4 class="font-semibold text-gray-900 mb-3 text-sm"><?php esc_html_e( 'PRO Features Include:', 'session-limiter' ); ?></h4>
				<ul class="text-gray-600 space-y-2 text-sm">
					<li class="flex items-start">
						<svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
						</svg>
						<span><?php esc_html_e( 'Role-based session limits', 'session-limiter' ); ?></span>
					</li>
					<li class="flex items-start">
						<svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
						</svg>
						<span><?php esc_html_e( 'Membership plugin integration', 'session-limiter' ); ?></span>
					</li>
					<li class="flex items-start">
						<svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
						</svg>
						<span><?php esc_html_e( 'Per-user session limit overrides', 'session-limiter' ); ?></span>
					</li>
					<li class="flex items-start">
						<svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
						</svg>
						<span><?php esc_html_e( 'Force logout & bulk session management', 'session-limiter' ); ?></span>
					</li>
					<li class="flex items-start">
						<svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
						</svg>
						<span><?php esc_html_e( 'WP-CLI commands & Export/Import', 'session-limiter' ); ?></span>
					</li>
					<li class="flex items-start">
						<svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
						</svg>
						<span><?php esc_html_e( 'Priority support', 'session-limiter' ); ?></span>
					</li>
				</ul>
			</div>
			
			<a href="https://handyplugins.co/session-limiter-pro/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-5 py-2.5 border border-transparent text-sm font-medium rounded-md shadow-sm !text-white !no-underline bg-indigo-600 hover:!bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
				<?php esc_html_e( 'Upgrade to PRO', 'session-limiter' ); ?>
				<svg class="ml-2 -mr-0.5 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
					<path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
				</svg>
			</a>
		</div>
	</div>

	<!-- Preview Content (Disabled) - Matches PRO design -->
	<div class="space-y-6 pointer-events-none select-none" aria-hidden="true">
		<!-- Role-based Limits Section -->
		<div class="bg-white shadow sm:rounded-lg">
			<div class="px-4 py-5 sm:p-6">
				<h3 class="text-lg leading-6 font-medium text-gray-900">
					<?php esc_html_e( 'Role-based Limits', 'session-limiter' ); ?>
				</h3>
				<div class="mt-2 max-w-xl text-sm text-gray-500">
					<p><?php esc_html_e( 'Set session limits for specific user roles. Leave empty to use the global default.', 'session-limiter' ); ?></p>
					<p class="mt-1"><?php esc_html_e( 'Includes custom roles from third-party plugins, such as Ultimate Member roles (um_*).', 'session-limiter' ); ?></p>
				</div>

				<div class="mt-6">
					<div class="overflow-x-auto">
						<table class="min-w-full divide-y divide-gray-200">
							<thead class="bg-gray-50">
								<tr>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
										<?php esc_html_e( 'Role', 'session-limiter' ); ?>
									</th>
									<th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
										<?php esc_html_e( 'Session Limit', 'session-limiter' ); ?>
									</th>
								</tr>
							</thead>
							<tbody class="bg-white divide-y divide-gray-200">
								<tr>
									<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
										<?php esc_html_e( 'Administrator', 'session-limiter' ); ?>
										<span class="text-gray-400 text-xs">(administrator)</span>
									</td>
									<td class="px-6 py-4 whitespace-nowrap">
										<input type="number" value="" min="0" placeholder="<?php esc_attr_e( 'Use global', 'session-limiter' ); ?>" class="w-32 rounded-md border-gray-300 shadow-sm sm:text-sm opacity-50" disabled aria-label="<?php esc_attr_e( 'Administrator session limit', 'session-limiter' ); ?>">
										<span class="ml-2 text-xs text-gray-400"><?php esc_html_e( '0 = unlimited', 'session-limiter' ); ?></span>
									</td>
								</tr>
								<tr>
									<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
										<?php esc_html_e( 'Editor', 'session-limiter' ); ?>
										<span class="text-gray-400 text-xs">(editor)</span>
									</td>
									<td class="px-6 py-4 whitespace-nowrap">
										<input type="number" value="3" min="0" placeholder="<?php esc_attr_e( 'Use global', 'session-limiter' ); ?>" class="w-32 rounded-md border-gray-300 shadow-sm sm:text-sm opacity-50" disabled aria-label="<?php esc_attr_e( 'Editor session limit', 'session-limiter' ); ?>">
										<span class="ml-2 text-xs text-gray-400"><?php esc_html_e( '0 = unlimited', 'session-limiter' ); ?></span>
									</td>
								</tr>
								<tr>
									<td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
										<?php esc_html_e( 'Subscriber', 'session-limiter' ); ?>
										<span class="text-gray-400 text-xs">(subscriber)</span>
									</td>
									<td class="px-6 py-4 whitespace-nowrap">
										<input type="number" value="1" min="0" placeholder="<?php esc_attr_e( 'Use global', 'session-limiter' ); ?>" class="w-32 rounded-md border-gray-300 shadow-sm sm:text-sm opacity-50" disabled aria-label="<?php esc_attr_e( 'Subscriber session limit', 'session-limiter' ); ?>">
										<span class="ml-2 text-xs text-gray-400"><?php esc_html_e( '0 = unlimited', 'session-limiter' ); ?></span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<!-- Membership Limits Section -->
		<div class="bg-white shadow sm:rounded-lg">
			<div class="px-4 py-5 sm:p-6">
				<h3 class="text-lg leading-6 font-medium text-gray-900">
					<?php esc_html_e( 'Membership-based Limits', 'session-limiter' ); ?>
				</h3>
				<div class="mt-2 max-w-xl text-sm text-gray-500">
					<p><?php esc_html_e( 'Set session limits based on membership levels. Requires a supported membership plugin.', 'session-limiter' ); ?></p>
				</div>

				<div class="mt-4 rounded-md bg-blue-50 p-4">
					<div class="flex">
						<div class="flex-shrink-0">
							<svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
								<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
							</svg>
						</div>
						<div class="ml-3">
							<p class="text-sm text-blue-700">
								<?php esc_html_e( 'Supported plugins:', 'session-limiter' ); ?>
								<strong>MemberPress, Paid Memberships Pro</strong>
							</p>
						</div>
					</div>
				</div>

				<div class="mt-4">
					<label class="inline-flex items-center cursor-not-allowed opacity-50">
						<input type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm" disabled>
						<span class="ml-2 text-sm text-gray-600"><?php esc_html_e( 'Enable membership-based limits', 'session-limiter' ); ?></span>
					</label>
				</div>
			</div>
		</div>

		<!-- Per-User Overrides Section -->
		<div class="bg-white shadow sm:rounded-lg">
			<div class="px-4 py-5 sm:p-6">
				<h3 class="text-lg leading-6 font-medium text-gray-900">
					<?php esc_html_e( 'Per-User Overrides', 'session-limiter' ); ?>
				</h3>
				<div class="mt-2 max-w-xl text-sm text-gray-500">
					<p><?php esc_html_e( 'Override session limits for specific users regardless of their role.', 'session-limiter' ); ?></p>
				</div>

				

				<div class="mt-4 bg-gray-50 rounded-lg p-4 text-center text-gray-500 text-sm">
					<?php esc_html_e( 'No user overrides configured.', 'session-limiter' ); ?>
				</div>
			</div>
		</div>
	</div>
</div>
