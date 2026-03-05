/**
 * Frontend JavaScript for SessionQuota 
 */

import './frontend.css';

(function($) {
	'use strict';

	const SessionQuotaFrontend = {
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
			$(document).on('click', '#sessionquota-logout-others-btn', this.handleLogoutOthers.bind(this));
			$(document).on('click', '.sessionquota-destroy-session', this.handleDestroySession.bind(this));
		},

		/**
		 * Handle logout others button click
		 */
		handleLogoutOthers: function(e) {
			e.preventDefault();

			const $button = $(e.currentTarget);
			const $wrapper = $button.closest('.sessionquota-logout-wrapper, .sessionquota-sessions-wrapper');
			const $message = $wrapper.find('.sessionquota-message');

			// Confirm action
			if (!confirm(SessionQuotaFrontend.i18n.confirm)) {
				return;
			}

			// Disable button
			$button.prop('disabled', true).text(SessionQuotaFrontend.i18n.loggingOut);
			$message.hide();

			// Send AJAX request
			$.ajax({
				url: SessionQuotaFrontend.ajaxUrl,
				type: 'POST',
				data: {
					action: 'sessionquota_logout_others',
					nonce: SessionQuotaFrontend.nonce
				},
				success: function(response) {
					if (response.success) {
						$message
							.removeClass('sessionquota-error')
							.addClass('sessionquota-success')
							.text(response.data.message)
							.fadeIn();

						// Update count
						const $count = $button.find('.sessionquota-count');
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
							$button.text(SessionQuotaFrontend.i18n.success);
						}, 100);

						// Reload page after 2 seconds to update session list
						setTimeout(function() {
							window.location.reload();
						}, 2000);
					} else {
						$message
							.removeClass('sessionquota-success')
							.addClass('sessionquota-error')
							.text(response.data.message || SessionQuotaFrontend.i18n.error)
							.fadeIn();

						$button.prop('disabled', false);
					}
				},
				error: function() {
					$message
						.removeClass('sessionquota-success')
						.addClass('sessionquota-error')
						.text(SessionQuotaFrontend.i18n.error)
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
			const $wrapper = $button.closest('.sessionquota-sessions-wrapper');
			const $message = $wrapper.find('.sessionquota-message');
			const token = $button.data('token');

			// Confirm action
			if (!confirm(SessionQuotaFrontend.i18n.confirmSingle)) {
				return;
			}

			// Disable button
			const originalText = $button.text();
			$button.prop('disabled', true).text(SessionQuotaFrontend.i18n.loggingOutSingle);
			$message.hide();

			// Send AJAX request
			$.ajax({
				url: SessionQuotaFrontend.ajaxUrl,
				type: 'POST',
				data: {
					action: 'sessionquota_destroy_session',
					nonce: SessionQuotaFrontend.nonce,
					token: token
				},
				success: function(response) {
					if (response.success) {
						// Fade out the row
						$row.fadeOut(300, function() {
							$(this).remove();
							
							// Update session count in any displayed info
							const $table = $wrapper.find('.sessionquota-table tbody');
							const remainingRows = $table.find('tr').length;
							
							// Update logout others button count if present
							const $logoutBtn = $wrapper.find('#sessionquota-logout-others-btn');
							if ($logoutBtn.length) {
								const $count = $logoutBtn.find('.sessionquota-count');
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
							.removeClass('sessionquota-error')
							.addClass('sessionquota-success')
							.text(response.data.message)
							.fadeIn();

						// Hide success message after 3 seconds
						setTimeout(function() {
							$message.fadeOut();
						}, 3000);
					} else {
						$message
							.removeClass('sessionquota-success')
							.addClass('sessionquota-error')
							.text(response.data.message || SessionQuotaFrontend.i18n.error)
							.fadeIn();

						$button.prop('disabled', false).text(originalText);
					}
				},
				error: function() {
					$message
						.removeClass('sessionquota-success')
						.addClass('sessionquota-error')
						.text(SessionQuotaFrontend.i18n.error)
						.fadeIn();

					$button.prop('disabled', false).text(originalText);
				}
			});
		}
	};

	// Initialize on document ready
	$(document).ready(function() {
		SessionQuotaFrontend.init();
	});

})(jQuery);

