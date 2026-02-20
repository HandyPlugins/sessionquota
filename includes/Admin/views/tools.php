<?php
/**
 * Tools tab template with paywall overlay
 *
 * Shows tools preview with PRO upgrade overlay.
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
		<!-- Force Logout User Section -->
		<div class="bg-white shadow sm:rounded-lg">
			<div class="px-4 py-5 sm:p-6">
				<h3 class="text-lg leading-6 font-medium text-gray-900">
					<?php esc_html_e( 'Force Logout User', 'session-limiter' ); ?>
				</h3>
				<div class="mt-2 max-w-xl text-sm text-gray-500">
					<p><?php esc_html_e( 'Search for a user and terminate all their active sessions.', 'session-limiter' ); ?></p>
				</div>
				<div class="mt-5">
					<div class="flex flex-col space-y-4">
						<div class="relative">
							<label for="session-limiter-user-search-preview" class="sr-only"><?php esc_html_e( 'Search for a user', 'session-limiter' ); ?></label>
							<input type="text" id="session-limiter-user-search-preview" class="w-full max-w-md rounded-md border-gray-300 shadow-sm sm:text-sm opacity-50" placeholder="<?php esc_attr_e( 'Search by username, email, or display name...', 'session-limiter' ); ?>" disabled aria-label="<?php esc_attr_e( 'Search users', 'session-limiter' ); ?>">
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Logout All Sessions Section -->
		<div class="bg-white shadow sm:rounded-lg">
			<div class="px-4 py-5 sm:p-6">
				<h3 class="text-lg leading-6 font-medium text-gray-900">
					<?php esc_html_e( 'Logout All Sessions', 'session-limiter' ); ?>
				</h3>
				<div class="mt-2 max-w-xl text-sm text-gray-500">
					<p><?php esc_html_e( 'Terminate all active sessions for all users on the site. Your current session will be preserved.', 'session-limiter' ); ?></p>
				</div>
				<div class="mt-5">
					<div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
						<div class="flex">
							<div class="flex-shrink-0">
								<svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
									<path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
								</svg>
							</div>
							<div class="ml-3">
								<p class="text-sm text-yellow-700">
									<?php esc_html_e( 'Warning: This action will log out all users from the site immediately.', 'session-limiter' ); ?>
								</p>
							</div>
						</div>
					</div>
					<button type="button" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 opacity-50 cursor-not-allowed" disabled>
						<?php esc_html_e( 'Logout All Sessions', 'session-limiter' ); ?>
					</button>
				</div>
			</div>
		</div>

		<!-- Reset Plugin Data Section -->
		<div class="bg-white shadow sm:rounded-lg">
			<div class="px-4 py-5 sm:p-6">
				<h3 class="text-lg leading-6 font-medium text-gray-900">
					<?php esc_html_e( 'Reset Plugin Data', 'session-limiter' ); ?>
				</h3>
				<div class="mt-2 max-w-xl text-sm text-gray-500">
					<p><?php esc_html_e( 'Reset all plugin settings to their default values. This will not affect user sessions.', 'session-limiter' ); ?></p>
				</div>
				<div class="mt-5">
					<button type="button" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white opacity-50 cursor-not-allowed" disabled>
						<?php esc_html_e( 'Reset Settings', 'session-limiter' ); ?>
					</button>
				</div>
			</div>
		</div>

		<!-- Export/Import Settings Section -->
		<div class="bg-white shadow sm:rounded-lg">
			<div class="px-4 py-5 sm:p-6">
				<h3 class="text-lg leading-6 font-medium text-gray-900">
					<?php esc_html_e( 'Export / Import Settings', 'session-limiter' ); ?>
				</h3>
				<div class="mt-2 max-w-xl text-sm text-gray-500">
					<p><?php esc_html_e( 'Export your current settings to a JSON file or import settings from a previously exported file.', 'session-limiter' ); ?></p>
				</div>

				<div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">
					<!-- Export -->
					<div class="bg-gray-50 rounded-lg p-4">
						<div class="flex items-center mb-3">
							<svg class="h-5 w-5 text-indigo-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
							</svg>
							<h4 class="text-sm font-medium text-gray-900"><?php esc_html_e( 'Export Settings', 'session-limiter' ); ?></h4>
						</div>
						<p class="text-xs text-gray-500 mb-3"><?php esc_html_e( 'Download a JSON file containing all your current plugin settings.', 'session-limiter' ); ?></p>
						<button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 opacity-50 cursor-not-allowed" disabled>
							<svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
							</svg>
							<?php esc_html_e( 'Export', 'session-limiter' ); ?>
						</button>
					</div>

					<!-- Import -->
					<div class="bg-gray-50 rounded-lg p-4">
						<div class="flex items-center mb-3">
							<svg class="h-5 w-5 text-green-500 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
							</svg>
							<h4 class="text-sm font-medium text-gray-900"><?php esc_html_e( 'Import Settings', 'session-limiter' ); ?></h4>
						</div>
						<p class="text-xs text-gray-500 mb-3"><?php esc_html_e( 'Upload a JSON file to restore previously exported settings.', 'session-limiter' ); ?></p>
						<button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 opacity-50 cursor-not-allowed" disabled>
							<svg class="h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
							</svg>
							<?php esc_html_e( 'Import', 'session-limiter' ); ?>
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- WP-CLI Commands Section -->
		<div class="bg-white shadow sm:rounded-lg">
			<div class="px-4 py-5 sm:p-6">
				<h3 class="text-lg leading-6 font-medium text-gray-900">
					<?php esc_html_e( 'WP-CLI Commands', 'session-limiter' ); ?>
				</h3>
				<div class="mt-2 max-w-xl text-sm text-gray-500">
					<p><?php esc_html_e( 'Manage sessions via command line for automation and bulk operations.', 'session-limiter' ); ?></p>
				</div>

				<div class="mt-4 bg-gray-900 rounded-lg p-4 overflow-x-auto">
					<pre class="text-sm font-mono"><code><span class="text-green-400"># <?php esc_html_e( 'Get session count for a specific user', 'session-limiter' ); ?></span>
<span class="text-gray-300">$ wp session-limiter list admin --format=count</span>

<span class="text-green-400"># <?php esc_html_e( 'List sessions for a specific user', 'session-limiter' ); ?></span>
<span class="text-gray-300">$ wp session-limiter list admin</span>

<span class="text-green-400"># <?php esc_html_e( 'Force logout a specific user', 'session-limiter' ); ?></span>
<span class="text-gray-300">$ wp session-limiter destroy admin</span>

<span class="text-green-400"># <?php esc_html_e( 'Check session limit and current usage for a user', 'session-limiter' ); ?></span>
<span class="text-gray-300">$ wp session-limiter limit admin</span>

<span class="text-green-400"># <?php esc_html_e( 'Set a user-specific session limit', 'session-limiter' ); ?></span>
<span class="text-gray-300">$ wp session-limiter set-limit admin 3</span>

<span class="text-green-400"># <?php esc_html_e( 'Remove a user-specific session limit override', 'session-limiter' ); ?></span>
<span class="text-gray-300">$ wp session-limiter remove-limit admin</span>

<span class="text-green-400"># <?php esc_html_e( 'Show session statistics', 'session-limiter' ); ?></span>
<span class="text-gray-300">$ wp session-limiter stats</span>

<span class="text-green-400"># <?php esc_html_e( 'Destroy all sessions for all users', 'session-limiter' ); ?></span>
<span class="text-gray-300">$ wp session-limiter destroy-all --yes</span></code></pre>
				</div>
			</div>
		</div>
	</div>
</div>
