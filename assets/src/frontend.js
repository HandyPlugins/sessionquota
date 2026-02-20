/**
 * Frontend JavaScript for Session Limiter 
 */

import './frontend.css';

(function($) {
	'use strict';

	const SessionLimiterFrontend = {
		/**
		 * Initialize
		 */
		init: function() {
			this.bindEvents();
		},

		/**
		 * Bind events
		 */
		bindEvents: function() {
			$(document).on('click', '#session-limiter-logout-others-btn', this.handleLogoutOthers.bind(this));
			$(document).on('click', '.session-limiter-destroy-session', this.handleDestroySession.bind(this));
		},

		/**
		 * Handle logout others button click
		 */
		handleLogoutOthers: function(e) {
			e.preventDefault();

			const $button = $(e.currentTarget);
			const $wrapper = $button.closest('.session-limiter-logout-wrapper, .session-limiter-sessions-wrapper');
			const $message = $wrapper.find('.session-limiter-message');

			// Confirm action
			if (!confirm(sessionLimiterProFrontend.i18n.confirm)) {
				return;
			}

			// Disable button
			$button.prop('disabled', true).text(sessionLimiterProFrontend.i18n.loggingOut);
			$message.hide();

			// Send AJAX request
			$.ajax({
				url: sessionLimiterProFrontend.ajaxUrl,
				type: 'POST',
				data: {
					action: 'session_limiter_logout_others',
					nonce: sessionLimiterProFrontend.nonce
				},
				success: function(response) {
					if (response.success) {
						$message
							.removeClass('session-limiter-error')
							.addClass('session-limiter-success')
							.text(response.data.message)
							.fadeIn();

						// Update count
						const $count = $button.find('.session-limiter-count');
						if ($count.length) {
							const remainingSessions = Math.max(0, response.data.remaining_sessions - 1);
							$count.text('(' + remainingSessions + ')');
						}

						// Keep button disabled if no other sessions
						if (response.data.remaining_sessions <= 1) {
							$button.prop('disabled', true);
						} else {
							$button.prop('disabled', false);
						}

						// Restore button text
						setTimeout(function() {
							$button.text(sessionLimiterProFrontend.i18n.success);
						}, 100);

						// Reload page after 2 seconds to update session list
						setTimeout(function() {
							window.location.reload();
						}, 2000);
					} else {
						$message
							.removeClass('session-limiter-success')
							.addClass('session-limiter-error')
							.text(response.data.message || sessionLimiterProFrontend.i18n.error)
							.fadeIn();

						$button.prop('disabled', false);
					}
				},
				error: function() {
					$message
						.removeClass('session-limiter-success')
						.addClass('session-limiter-error')
						.text(sessionLimiterProFrontend.i18n.error)
						.fadeIn();

					$button.prop('disabled', false);
				}
			});
		},

		/**
		 * Handle destroy single session button click
		 */
		handleDestroySession: function(e) {
			e.preventDefault();

			const $button = $(e.currentTarget);
			const $row = $button.closest('tr');
			const $wrapper = $button.closest('.session-limiter-sessions-wrapper');
			const $message = $wrapper.find('.session-limiter-message');
			const token = $button.data('token');

			// Confirm action
			if (!confirm(sessionLimiterProFrontend.i18n.confirmSingle)) {
				return;
			}

			// Disable button
			const originalText = $button.text();
			$button.prop('disabled', true).text(sessionLimiterProFrontend.i18n.loggingOutSingle);
			$message.hide();

			// Send AJAX request
			$.ajax({
				url: sessionLimiterProFrontend.ajaxUrl,
				type: 'POST',
				data: {
					action: 'session_limiter_destroy_session',
					nonce: sessionLimiterProFrontend.nonce,
					token: token
				},
				success: function(response) {
					if (response.success) {
						// Fade out the row
						$row.fadeOut(300, function() {
							$(this).remove();
							
							// Update session count in any displayed info
							const $table = $wrapper.find('.session-limiter-table tbody');
							const remainingRows = $table.find('tr').length;
							
							// Update logout others button count if present
							const $logoutBtn = $wrapper.find('#session-limiter-logout-others-btn');
							if ($logoutBtn.length) {
								const $count = $logoutBtn.find('.session-limiter-count');
								if ($count.length) {
									$count.text('(' + Math.max(0, remainingRows - 1) + ')');
								}
								
								// Disable if only current session remains
								if (remainingRows <= 1) {
									$logoutBtn.prop('disabled', true);
								}
							}
						});

						$message
							.removeClass('session-limiter-error')
							.addClass('session-limiter-success')
							.text(response.data.message)
							.fadeIn();

						// Hide success message after 3 seconds
						setTimeout(function() {
							$message.fadeOut();
						}, 3000);
					} else {
						$message
							.removeClass('session-limiter-success')
							.addClass('session-limiter-error')
							.text(response.data.message || sessionLimiterProFrontend.i18n.error)
							.fadeIn();

						$button.prop('disabled', false).text(originalText);
					}
				},
				error: function() {
					$message
						.removeClass('session-limiter-success')
						.addClass('session-limiter-error')
						.text(sessionLimiterProFrontend.i18n.error)
						.fadeIn();

					$button.prop('disabled', false).text(originalText);
				}
			});
		}
	};

	// Initialize on document ready
	$(document).ready(function() {
		SessionLimiterFrontend.init();
	});

})(jQuery);

