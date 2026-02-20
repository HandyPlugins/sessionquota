<?php
/**
 * PRO Upgrade page template
 *
 * Shows a locked screen with upgrade message for PRO-only features.
 *
 * @package SessionLimiter
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div class="bg-white shadow sm:rounded-lg">
	<div class="px-4 py-5 sm:p-6">
		<div class="text-center py-12">
			<div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 mb-6">
				<svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
				</svg>
			</div>
			
			<h3 class="text-2xl font-bold text-gray-900 mb-4">
				<?php esc_html_e( 'PRO Feature', 'session-limiter' ); ?>
			</h3>
			
			<p class="text-gray-600 mb-8 max-w-md mx-auto">
				<?php esc_html_e( 'This feature is available in Session Limiter Pro. Upgrade to unlock advanced session management features.', 'session-limiter' ); ?>
			</p>

			<div class="bg-gray-50 rounded-lg p-6 mb-8 max-w-lg mx-auto">
				<h4 class="font-semibold text-gray-900 mb-4"><?php esc_html_e( 'PRO Features Include:', 'session-limiter' ); ?></h4>
				<ul class="text-left text-gray-600 space-y-3">
					<li class="flex items-start">
						<svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
						</svg>
						<span><?php esc_html_e( 'Role-based session limits', 'session-limiter' ); ?></span>
					</li>
					<li class="flex items-start">
						<svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
						</svg>
						<span><?php esc_html_e( 'Membership plugin integration (MemberPress, Paid Memberships Pro)', 'session-limiter' ); ?></span>
					</li>
					<li class="flex items-start">
						<svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
						</svg>
						<span><?php esc_html_e( 'Per-user session limit overrides', 'session-limiter' ); ?></span>
					</li>
					<li class="flex items-start">
						<svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
						</svg>
						<span><?php esc_html_e( 'Force logout users and bulk session management', 'session-limiter' ); ?></span>
					</li>
					<li class="flex items-start">
						<svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
						</svg>
						<span><?php esc_html_e( 'Frontend session management for users', 'session-limiter' ); ?></span>
					</li>
					<li class="flex items-start">
						<svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
						</svg>
						<span><?php esc_html_e( 'WordPress Multisite support', 'session-limiter' ); ?></span>
					</li>
					<li class="flex items-start">
						<svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
						</svg>
						<span><?php esc_html_e( 'WP-CLI commands for automation', 'session-limiter' ); ?></span>
					</li>
					<li class="flex items-start">
						<svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
						</svg>
						<span><?php esc_html_e( 'Export/Import settings', 'session-limiter' ); ?></span>
					</li>
					<li class="flex items-start">
						<svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
							<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
						</svg>
						<span><?php esc_html_e( 'Priority support', 'session-limiter' ); ?></span>
					</li>
				</ul>
			</div>

			<a href="https://handyplugins.co/session-limiter-pro/" target="_blank" rel="noopener noreferrer" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
				<?php esc_html_e( 'Upgrade to PRO', 'session-limiter' ); ?>
				<svg class="ml-2 -mr-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
					<path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
				</svg>
			</a>
		</div>
	</div>
</div>
