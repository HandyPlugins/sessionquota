<?php
/**
 * Monitoring tab template with paywall overlay
 *
 * Shows monitoring preview with PRO upgrade overlay.
 * Mirrors the Pro version's Security Logging & Monitoring structure.
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

	<!-- Preview Content (Disabled) - Mirrors PRO structure -->
	<div class="pointer-events-none select-none" aria-hidden="true">
		<!-- Security Logging & Monitoring Settings -->
		<div class="bg-white shadow overflow-hidden sm:rounded-lg">
			<div class="px-4 py-5 sm:p-6">
				<h2 class="text-lg font-medium text-gray-900 mb-4">
					<?php esc_html_e( 'Security Logging & Monitoring', 'session-limiter' ); ?>
				</h2>
				<p class="text-sm text-gray-600 mb-6">
					<?php esc_html_e( 'Track session-related security events and get alerts for suspicious activity. All logging is disabled by default for privacy.', 'session-limiter' ); ?>
				</p>

				<!-- Settings Form Preview -->
				<div class="space-y-6">
					<!-- Logging Settings -->
					<div class="border border-gray-200 rounded-lg p-4">
						<h3 class="text-base font-medium text-gray-900 mb-4">
							<span class="dashicons dashicons-visibility mr-2"></span>
							<?php esc_html_e( 'Logging Settings', 'session-limiter' ); ?>
						</h3>

						<div class="space-y-4">
							<!-- Enable Logging -->
							<label class="flex items-start opacity-50">
								<input type="checkbox" disabled class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm">
								<span class="ml-3">
									<span class="block text-sm font-medium text-gray-700"><?php esc_html_e( 'Enable Security Logging', 'session-limiter' ); ?></span>
									<span class="block text-sm text-gray-500"><?php esc_html_e( 'Log session events like logins, blocks, and terminations.', 'session-limiter' ); ?></span>
								</span>
							</label>

							<div class="ml-6 space-y-4 opacity-50">
								<!-- Log IP Address -->
								<label class="flex items-start">
									<input type="checkbox" disabled class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm">
									<span class="ml-3">
										<span class="block text-sm font-medium text-gray-700"><?php esc_html_e( 'Log IP Addresses', 'session-limiter' ); ?></span>
										<span class="block text-sm text-gray-500"><?php esc_html_e( 'Record the IP address for each event.', 'session-limiter' ); ?></span>
									</span>
								</label>

								<!-- Log User Agent -->
								<label class="flex items-start">
									<input type="checkbox" disabled class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm">
									<span class="ml-3">
										<span class="block text-sm font-medium text-gray-700"><?php esc_html_e( 'Log User Agent', 'session-limiter' ); ?></span>
										<span class="block text-sm text-gray-500"><?php esc_html_e( 'Record browser/device information.', 'session-limiter' ); ?></span>
									</span>
								</label>

								<!-- Country Detection -->
								<label class="flex items-start">
									<input type="checkbox" disabled class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm">
									<span class="ml-3">
										<span class="block text-sm font-medium text-gray-700"><?php esc_html_e( 'Enable Country Detection', 'session-limiter' ); ?></span>
										<span class="block text-sm text-gray-500"><?php esc_html_e( 'Detect country from IP address. Requires IP logging.', 'session-limiter' ); ?></span>
									</span>
								</label>

								<!-- Retention Period -->
								<div class="mt-4">
									<label class="block text-sm font-medium text-gray-700">
										<?php esc_html_e( 'Log Retention Period', 'session-limiter' ); ?>
									</label>
									<select disabled class="mt-1 block w-48 rounded-md border-gray-300 shadow-sm opacity-50">
										<option><?php esc_html_e( '30 days', 'session-limiter' ); ?></option>
									</select>
									<p class="mt-1 text-sm text-gray-500">
										<?php esc_html_e( 'Older logs will be automatically deleted.', 'session-limiter' ); ?>
									</p>
								</div>
							</div>
						</div>
					</div>

					<!-- Alert Settings -->
					<div class="border border-gray-200 rounded-lg p-4">
						<h3 class="text-base font-medium text-gray-900 mb-4">
							<span class="dashicons dashicons-bell mr-2"></span>
							<?php esc_html_e( 'Alert Settings', 'session-limiter' ); ?>
						</h3>

						<div class="space-y-4">
							<!-- Enable Alerts -->
							<label class="flex items-start opacity-50">
								<input type="checkbox" disabled class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm">
								<span class="ml-3">
									<span class="block text-sm font-medium text-gray-700"><?php esc_html_e( 'Enable Alerts', 'session-limiter' ); ?></span>
									<span class="block text-sm text-gray-500"><?php esc_html_e( 'Get notified about suspicious activity.', 'session-limiter' ); ?></span>
								</span>
							</label>

							<div class="ml-6 space-y-4 opacity-50">
								<!-- Blocked Login Threshold -->
								<div class="flex items-center gap-4">
									<label class="text-sm font-medium text-gray-700">
										<?php esc_html_e( 'Alert when login blocked', 'session-limiter' ); ?>
									</label>
									<input type="number" value="3" disabled class="w-20 rounded-md border-gray-300 shadow-sm">
									<span class="text-sm text-gray-700"><?php esc_html_e( 'times in', 'session-limiter' ); ?></span>
									<input type="number" value="60" disabled class="w-20 rounded-md border-gray-300 shadow-sm">
									<span class="text-sm text-gray-700"><?php esc_html_e( 'minutes', 'session-limiter' ); ?></span>
								</div>

								<!-- Country Change Alert -->
								<label class="flex items-start">
									<input type="checkbox" disabled class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm">
									<span class="ml-3">
										<span class="block text-sm font-medium text-gray-700"><?php esc_html_e( 'Alert on Country Change', 'session-limiter' ); ?></span>
										<span class="block text-sm text-gray-500"><?php esc_html_e( 'Get notified when a user logs in from a different country.', 'session-limiter' ); ?></span>
									</span>
								</label>

								<!-- Admin Notice -->
								<label class="flex items-start">
									<input type="checkbox" disabled class="mt-1 rounded border-gray-300 text-indigo-600 shadow-sm">
									<span class="ml-3">
										<span class="block text-sm font-medium text-gray-700"><?php esc_html_e( 'Show Admin Dashboard Notices', 'session-limiter' ); ?></span>
										<span class="block text-sm text-gray-500"><?php esc_html_e( 'Display alerts in WordPress admin dashboard.', 'session-limiter' ); ?></span>
									</span>
								</label>

								<!-- Alert Email -->
								<div>
									<label class="block text-sm font-medium text-gray-700">
										<?php esc_html_e( 'Alert Email Address', 'session-limiter' ); ?>
									</label>
									<input type="email" disabled placeholder="<?php echo esc_attr( get_option( 'admin_email' ) ); ?>" class="mt-1 block w-80 rounded-md border-gray-300 shadow-sm">
									<p class="mt-1 text-sm text-gray-500">
										<?php esc_html_e( 'Leave empty to use the site admin email.', 'session-limiter' ); ?>
									</p>
								</div>
							</div>
						</div>
					</div>

					<!-- GeoIP Settings -->
					<div class="border border-gray-200 rounded-lg p-4">
						<h3 class="text-base font-medium text-gray-900 mb-4">
							<span class="dashicons dashicons-location-alt mr-2"></span>
							<?php esc_html_e( 'GeoIP Country Detection', 'session-limiter' ); ?>
						</h3>

						<div class="space-y-4">
							<!-- MaxMind info box -->
							<div class="rounded-md bg-yellow-50 p-4">
								<div class="flex">
									<div class="flex-shrink-0">
										<span class="dashicons dashicons-info text-yellow-400"></span>
									</div>
									<div class="ml-3">
										<h3 class="text-sm font-medium text-yellow-800">
											<?php esc_html_e( 'MaxMind Database Not Configured', 'session-limiter' ); ?>
										</h3>
										<p class="mt-2 text-sm text-yellow-700">
											<?php esc_html_e( 'To enable country detection, you need a MaxMind GeoLite2 database. If your site is behind Cloudflare, country detection will work automatically using the CF-IPCountry header.', 'session-limiter' ); ?>
										</p>
									</div>
								</div>
							</div>

							<!-- MaxMind License Key -->
							<div class="mt-4">
								<label class="block text-sm font-medium text-gray-700">
									<?php esc_html_e( 'MaxMind License Key', 'session-limiter' ); ?>
								</label>
								<p class="text-sm text-gray-500 mb-2">
									<?php
									printf(
										wp_kses(
											/* translators: %s: MaxMind signup URL link */
											__( 'Get a free license key from %s to download the GeoLite2 Country database.', 'session-limiter' ),
											array(
												'a' => array(
													'href' => array(),
													'target' => array(),
													'rel'  => array(),
													'class' => array(),
												),
											)
										),
										'<a href="https://www.maxmind.com/en/geolite2/signup" target="_blank" rel="noopener noreferrer" class="text-indigo-600 hover:text-indigo-500">MaxMind</a>'
									);
									?>
								</p>
								<div class="flex gap-2">
									<input type="text" disabled placeholder="<?php esc_attr_e( 'Enter your MaxMind license key', 'session-limiter' ); ?>" class="flex-1 max-w-md rounded-md border-gray-300 shadow-sm opacity-50">
									<button type="button" disabled class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 opacity-50 cursor-not-allowed">
										<span class="dashicons dashicons-download mr-1.5" style="margin-top: 2px;"></span>
										<?php esc_html_e( 'Download Database', 'session-limiter' ); ?>
									</button>
								</div>
							</div>
						</div>
					</div>

					<!-- Save Button -->
					<div class="flex justify-end">
						<button type="button" disabled class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 opacity-50 cursor-not-allowed">
							<?php esc_html_e( 'Save Settings', 'session-limiter' ); ?>
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Log Viewer Section -->
		<div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
			<div class="px-4 py-5 sm:p-6">
				<div class="flex justify-between items-center mb-4">
					<h2 class="text-lg font-medium text-gray-900">
						<?php esc_html_e( 'Security Event Logs', 'session-limiter' ); ?>
					</h2>
					<div class="flex gap-2">
						<button type="button" disabled class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white opacity-50 cursor-not-allowed">
							<span class="dashicons dashicons-download mr-1.5" style="margin-top: 2px;"></span>
							<?php esc_html_e( 'Export CSV', 'session-limiter' ); ?>
						</button>
						<button type="button" disabled class="inline-flex items-center px-3 py-2 border border-red-300 shadow-sm text-sm font-medium rounded-md text-red-700 bg-white opacity-50 cursor-not-allowed">
							<span class="dashicons dashicons-trash mr-1.5" style="margin-top: 2px;"></span>
							<?php esc_html_e( 'Purge All Logs', 'session-limiter' ); ?>
						</button>
					</div>
				</div>

				<!-- Filters -->
				<div class="mb-4 p-4 bg-gray-50 rounded-lg">
					<div class="grid grid-cols-1 md:grid-cols-4 gap-4">
						<div>
							<label class="block text-sm font-medium text-gray-700 mb-1">
								<?php esc_html_e( 'Event Type', 'session-limiter' ); ?>
							</label>
							<select disabled class="block w-full rounded-md border-gray-300 shadow-sm opacity-50">
								<option><?php esc_html_e( 'All Events', 'session-limiter' ); ?></option>
								<option><?php esc_html_e( 'Login Success', 'session-limiter' ); ?></option>
								<option><?php esc_html_e( 'Login Blocked', 'session-limiter' ); ?></option>
								<option><?php esc_html_e( 'Session Terminated', 'session-limiter' ); ?></option>
								<option><?php esc_html_e( 'Logout', 'session-limiter' ); ?></option>
							</select>
						</div>
						<div>
							<label class="block text-sm font-medium text-gray-700 mb-1">
								<?php esc_html_e( 'From Date', 'session-limiter' ); ?>
							</label>
							<input type="date" disabled class="block w-full rounded-md border-gray-300 shadow-sm opacity-50">
						</div>
						<div>
							<label class="block text-sm font-medium text-gray-700 mb-1">
								<?php esc_html_e( 'To Date', 'session-limiter' ); ?>
							</label>
							<input type="date" disabled class="block w-full rounded-md border-gray-300 shadow-sm opacity-50">
						</div>
						<div>
							<label class="block text-sm font-medium text-gray-700 mb-1">
								<?php esc_html_e( 'Search IP/User Agent', 'session-limiter' ); ?>
							</label>
							<input type="text" disabled placeholder="<?php esc_attr_e( 'Search...', 'session-limiter' ); ?>" class="block w-full rounded-md border-gray-300 shadow-sm opacity-50">
						</div>
					</div>
					<div class="mt-3 flex justify-end gap-2">
						<button type="button" disabled class="inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white opacity-50 cursor-not-allowed">
							<?php esc_html_e( 'Reset', 'session-limiter' ); ?>
						</button>
						<button type="button" disabled class="inline-flex items-center px-3 py-1.5 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 opacity-50 cursor-not-allowed">
							<?php esc_html_e( 'Apply Filters', 'session-limiter' ); ?>
						</button>
					</div>
				</div>

				<!-- Logs Table -->
				<div class="overflow-x-auto">
					<table class="min-w-full divide-y divide-gray-200">
						<thead class="bg-gray-50">
							<tr>
								<th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
									<?php esc_html_e( 'Date/Time', 'session-limiter' ); ?>
								</th>
								<th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
									<?php esc_html_e( 'User', 'session-limiter' ); ?>
								</th>
								<th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
									<?php esc_html_e( 'Event', 'session-limiter' ); ?>
								</th>
								<th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
									<?php esc_html_e( 'IP Address', 'session-limiter' ); ?>
								</th>
								<th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
									<?php esc_html_e( 'Country', 'session-limiter' ); ?>
								</th>
								<th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
									<?php esc_html_e( 'Details', 'session-limiter' ); ?>
								</th>
							</tr>
						</thead>
						<tbody class="bg-white divide-y divide-gray-200">
							<!-- Sample Row 1 -->
							<tr>
								<td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
									2026-02-09 14:32:15
								</td>
								<td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
									john@example.com
								</td>
								<td class="px-4 py-4 whitespace-nowrap">
									<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
										<?php esc_html_e( 'Login Success', 'session-limiter' ); ?>
									</span>
								</td>
								<td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
									192.168.1.100
								</td>
								<td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
									🇺🇸 US
								</td>
								<td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
									Chrome / macOS
								</td>
							</tr>
							<!-- Sample Row 2 -->
							<tr>
								<td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
									2026-02-09 14:28:42
								</td>
								<td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
									user@example.com
								</td>
								<td class="px-4 py-4 whitespace-nowrap">
									<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
										<?php esc_html_e( 'Login Blocked', 'session-limiter' ); ?>
									</span>
								</td>
								<td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
									203.0.113.50
								</td>
								<td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
									🇬🇧 GB
								</td>
								<td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
									<?php esc_html_e( 'Session limit reached', 'session-limiter' ); ?>
								</td>
							</tr>
							<!-- Sample Row 3 -->
							<tr>
								<td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
									2026-02-09 14:15:08
								</td>
								<td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
									jane@example.com
								</td>
								<td class="px-4 py-4 whitespace-nowrap">
									<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
										<?php esc_html_e( 'Session Terminated', 'session-limiter' ); ?>
									</span>
								</td>
								<td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
									192.168.1.105
								</td>
								<td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
									🇩🇪 DE
								</td>
								<td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
									<?php esc_html_e( 'Oldest session removed', 'session-limiter' ); ?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<!-- Pagination -->
				<div class="mt-4 flex items-center justify-between">
					<div class="text-sm text-gray-700">
						<?php
						printf(
							/* translators: 1: First item number, 2: Last item number, 3: Total items */
							esc_html__( 'Showing %1$s to %2$s of %3$s results', 'session-limiter' ),
							'<span class="font-medium">1</span>',
							'<span class="font-medium">3</span>',
							'<span class="font-medium">156</span>'
						);
						?>
					</div>
					<div class="flex gap-2">
						<button type="button" disabled class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white opacity-50 cursor-not-allowed">
							<?php esc_html_e( 'Previous', 'session-limiter' ); ?>
						</button>
						<button type="button" disabled class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white opacity-50 cursor-not-allowed">
							<?php esc_html_e( 'Next', 'session-limiter' ); ?>
						</button>
					</div>
				</div>
			</div>
		</div>

		<!-- Privacy Section -->
		<div class="mt-6 bg-white shadow overflow-hidden sm:rounded-lg">
			<div class="px-4 py-5 sm:p-6">
				<h2 class="text-lg font-medium text-gray-900 mb-4">
					<span class="dashicons dashicons-shield mr-2"></span>
					<?php esc_html_e( 'Privacy', 'session-limiter' ); ?>
				</h2>
				<p class="text-sm text-gray-600 mb-4">
					<?php esc_html_e( 'Session Limiter Pro integrates with WordPress Privacy Tools for GDPR compliance. User data can be exported and erased through the standard WordPress privacy request system.', 'session-limiter' ); ?>
				</p>

				<div class="flex flex-wrap gap-4">
					<button type="button" disabled class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white opacity-50 cursor-not-allowed">
						<span class="dashicons dashicons-download mr-2"></span>
						<?php esc_html_e( 'Export Personal Data', 'session-limiter' ); ?>
					</button>
					<button type="button" disabled class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white opacity-50 cursor-not-allowed">
						<span class="dashicons dashicons-trash mr-2"></span>
						<?php esc_html_e( 'Erase Personal Data', 'session-limiter' ); ?>
					</button>
					<button type="button" disabled class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white opacity-50 cursor-not-allowed">
						<span class="dashicons dashicons-admin-page mr-2"></span>
						<?php esc_html_e( 'Privacy Policy Guide', 'session-limiter' ); ?>
					</button>
				</div>

				<div class="mt-6">
					<h3 class="text-sm font-medium text-gray-700 mb-2">
						<?php esc_html_e( 'Privacy Policy Draft', 'session-limiter' ); ?>
					</h3>
					<p class="text-sm text-gray-500 mb-3">
						<?php esc_html_e( 'Copy this text to include in your site\'s privacy policy. The content is automatically generated based on your current settings.', 'session-limiter' ); ?>
					</p>
					<div class="relative">
						<textarea rows="6" readonly disabled class="block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm text-sm font-mono opacity-50">
						<?php
						esc_html_e(
							'This website uses Session Limiter to manage user sessions and enhance security. When you log in, we may collect and process the following information:

- Session data: We track active login sessions to enforce concurrent session limits.
- IP addresses: We may log your IP address for security monitoring purposes.
- Browser information: We may record your browser type and device for session identification.

This data is processed based on our legitimate interest in maintaining the security of our website. Session data is automatically deleted according to our retention policy.',
							'session-limiter'
						);
						?>
						</textarea>
						<button type="button" disabled class="absolute top-2 right-2 inline-flex items-center px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white shadow-sm opacity-50 cursor-not-allowed">
							<span class="dashicons dashicons-clipboard mr-1" style="margin-top: 2px;"></span>
							<?php esc_html_e( 'Copy', 'session-limiter' ); ?>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
