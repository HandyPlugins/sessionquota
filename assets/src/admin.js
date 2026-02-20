/**
 * Admin JavaScript
 *
 * @package SessionLimiterPro
 */

import { __, sprintf } from '@wordpress/i18n';
import './admin.css';

// Initialize admin functionality
document.addEventListener('DOMContentLoaded', () => {
	// Tab navigation functionality
	initTabNavigation();

	// General settings page functionality
	initEnforcementModeToggle();

	// Tools page functionality
	initUserSearch();
	initForceLogout();
	initLogoutAllSessions();
	initResetSettings();
	initExportSettings();
	initImportSettings();
	initModal();
	initToast();

	// License page functionality
	initLicenseActivation();
});

/**
 * Initialize tab navigation with accessibility support
 */
function initTabNavigation() {
	const tabButtons = document.querySelectorAll('.session-limiter-tab-button');
	const tabPanels = document.querySelectorAll('.session-limiter-tab-panel');

	if (!tabButtons.length || !tabPanels.length) {
		return;
	}

	/**
	 * Switch to a specific tab
	 * @param {string} tabId - The tab ID to switch to
	 * @param {boolean} updateUrl - Whether to update the browser URL
	 */
	function switchTab(tabId, updateUrl = true) {
		// Update tab buttons
		tabButtons.forEach(btn => {
			const isActive = btn.dataset.tab === tabId;
			btn.classList.toggle('session-limiter-tab-active', isActive);
			btn.setAttribute('aria-selected', isActive ? 'true' : 'false');
			btn.setAttribute('tabindex', isActive ? '0' : '-1');
		});

		// Update tab panels
		tabPanels.forEach(panel => {
			const panelId = panel.id.replace('session-limiter-panel-', '');
			const isActive = panelId === tabId;
			panel.classList.toggle('hidden', !isActive);
		});

		// Update URL without page reload
		if (updateUrl) {
			const url = new URL(window.location);
			url.searchParams.set('tab', tabId);
			window.history.pushState({ tab: tabId }, '', url);
		}
	}

	// Handle tab click
	tabButtons.forEach(btn => {
		btn.addEventListener('click', () => {
			switchTab(btn.dataset.tab);
		});
	});

	// Handle keyboard navigation
	tabButtons.forEach((btn, index) => {
		btn.addEventListener('keydown', (e) => {
			let targetIndex = index;
			const tabCount = tabButtons.length;

			switch (e.key) {
				case 'ArrowRight':
					e.preventDefault();
					targetIndex = (index + 1) % tabCount;
					break;
				case 'ArrowLeft':
					e.preventDefault();
					targetIndex = (index - 1 + tabCount) % tabCount;
					break;
				case 'Home':
					e.preventDefault();
					targetIndex = 0;
					break;
				case 'End':
					e.preventDefault();
					targetIndex = tabCount - 1;
					break;
				default:
					return;
			}

			const targetButton = tabButtons[targetIndex];
			targetButton.focus();
			switchTab(targetButton.dataset.tab);
		});
	});

	// Handle browser back/forward navigation
	window.addEventListener('popstate', (e) => {
		if (e.state && e.state.tab) {
			switchTab(e.state.tab, false);
		} else {
			// Get tab from URL on popstate
			const url = new URL(window.location);
			const tabId = url.searchParams.get('tab') || 'general';
			switchTab(tabId, false);
		}
	});

	// Set initial state in history
	const currentUrl = new URL(window.location);
	const currentTab = currentUrl.searchParams.get('tab') || 'general';
	window.history.replaceState({ tab: currentTab }, '', window.location);
}

/**
 * Initialize enforcement mode toggle
 * Hides session limit field when "Logout all other sessions" is selected
 * Disables non-strict modes when session limit is 0
 */
function initEnforcementModeToggle() {
	const enforcementModeInputs = document.querySelectorAll('input[name="session_limiter_settings[enforcement_mode]"]');
	const sessionLimitInput = document.querySelector('input[name="session_limiter_settings[session_limit]"]');
	const sessionLimitRow = sessionLimitInput?.closest('tr');
	const enforcementModeContainer = document.getElementById('enforcement-mode-container');
	const blockModeOptions = document.getElementById('session-limiter-block-mode-options');

	if (!enforcementModeInputs.length || !sessionLimitRow) {
		return;
	}

	const toggleSessionLimitVisibility = () => {
		const selectedMode = document.querySelector('input[name="session_limiter_settings[enforcement_mode]"]:checked')?.value;
		
		if (selectedMode === 'logout_all_others') {
			sessionLimitRow.style.display = 'none';
		} else {
			sessionLimitRow.style.display = '';
		}
	};

	const toggleBlockModeOptionsVisibility = () => {
		if (!blockModeOptions) {
			return;
		}

		const selectedMode = document.querySelector('input[name="session_limiter_settings[enforcement_mode]"]:checked')?.value;
		blockModeOptions.classList.toggle('hidden', selectedMode !== 'block');
	};

	const toggleEnforcementModeState = () => {
		const limitValue = parseInt(sessionLimitInput?.value, 10) || 0;
		const selectedMode = document.querySelector('input[name="session_limiter_settings[enforcement_mode]"]:checked')?.value;
		const isStrictMode = selectedMode === 'logout_all_others';
		const disableByLimit = limitValue === 0 && !isStrictMode;

		// Toggle disabled state on all radio inputs
		enforcementModeInputs.forEach(input => {
			const isStrictOption = input.value === 'logout_all_others';
			input.disabled = disableByLimit && !isStrictOption;
			const label = input.closest('label');
			if (label) {
				if (input.disabled) {
					label.classList.add('opacity-50');
				} else {
					label.classList.remove('opacity-50');
				}
			}
		});

		// Toggle hint message
		let hintElement = document.getElementById('enforcement-disabled-hint');
		if (disableByLimit) {
			if (!hintElement && enforcementModeContainer) {
				hintElement = document.createElement('p');
				hintElement.id = 'enforcement-disabled-hint';
				hintElement.className = 'mt-3 text-sm text-amber-600';
				hintElement.innerHTML = '<span class="dashicons dashicons-info" style="font-size: 16px; width: 16px; height: 16px; margin-right: 4px;"></span>' +
					(window.sessionLimiter?.i18n?.enforcementHint || window.sessionLimiterPro?.i18n?.enforcementHint || 'Set the session limit to 1 or higher to enable block and logout-oldest modes. Strict mode remains available.');
				enforcementModeContainer.parentNode.insertBefore(hintElement, enforcementModeContainer.nextSibling);
			}
		} else if (hintElement) {
			hintElement.remove();
		}
	};

	// Initial state
	toggleSessionLimitVisibility();
	toggleEnforcementModeState();
	toggleBlockModeOptionsVisibility();

	// Listen for enforcement mode changes
	enforcementModeInputs.forEach(input => {
		input.addEventListener('change', () => {
			toggleSessionLimitVisibility();
			toggleEnforcementModeState();
			toggleBlockModeOptionsVisibility();
		});
	});

	// Listen for session limit changes
	if (sessionLimitInput) {
		sessionLimitInput.addEventListener('input', toggleEnforcementModeState);
		sessionLimitInput.addEventListener('change', toggleEnforcementModeState);
	}
}

/**
 * Debounce function
 */
function debounce(func, wait) {
	let timeout;
	return function executedFunction(...args) {
		const later = () => {
			clearTimeout(timeout);
			func(...args);
		};
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
	};
}

/**
 * Initialize user search functionality
 */
function initUserSearch() {
	const searchInput = document.getElementById('session-limiter-user-search');
	const resultsContainer = document.getElementById('session-limiter-user-search-results');
	const selectedUserContainer = document.getElementById('session-limiter-selected-user');
	const clearSelectionBtn = document.getElementById('session-limiter-clear-selection');

	if (!searchInput || !resultsContainer) {
		return;
	}

	const handleSearch = debounce(async (searchTerm) => {
		if (searchTerm.length < 2) {
			resultsContainer.classList.add('hidden');
			searchInput.setAttribute('aria-expanded', 'false');
			return;
		}

		try {
			const formData = new FormData();
			formData.append('action', 'session_limiter_search_users');
			formData.append('nonce', window.sessionLimiterPro.nonce);
			formData.append('search', searchTerm);

			const response = await fetch(window.sessionLimiterPro.ajaxUrl, {
				method: 'POST',
				body: formData,
			});

			const data = await response.json();

			if (data.success && data.data.users.length > 0) {
				renderSearchResults(data.data.users);
				resultsContainer.classList.remove('hidden');
				searchInput.setAttribute('aria-expanded', 'true');
			} else {
				resultsContainer.innerHTML = `<div class="p-3 text-sm text-gray-500" role="status">${__('No users found', 'session-limiter')}</div>`;
				resultsContainer.classList.remove('hidden');
				searchInput.setAttribute('aria-expanded', 'true');
			}
		} catch (error) {
			console.error('Search error:', error);
		}
	}, 300);

	searchInput.addEventListener('input', (e) => {
		handleSearch(e.target.value);
	});

	// Hide results when clicking outside
	document.addEventListener('click', (e) => {
		if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
			resultsContainer.classList.add('hidden');
			searchInput.setAttribute('aria-expanded', 'false');
		}
	});

	// Clear selection
	if (clearSelectionBtn) {
		clearSelectionBtn.addEventListener('click', () => {
			selectedUserContainer.classList.add('hidden');
			searchInput.value = '';
			document.getElementById('session-limiter-selected-user-id').value = '';
		});
	}
}

/**
 * Render search results
 */
function renderSearchResults(users) {
	const resultsContainer = document.getElementById('session-limiter-user-search-results');
	const sessionsLabel = __('Sessions:', 'session-limiter');

	resultsContainer.innerHTML = users.map((user, index) => `
		<div class="session-limiter-user-result p-3 hover:bg-gray-100 cursor-pointer border-b last:border-b-0"
			 role="option"
			 id="session-limiter-user-result-${user.id}"
			 aria-selected="false"
			 tabindex="-1"
			 data-user-id="${user.id}"
			 data-user-login="${user.login}"
			 data-user-email="${user.email}"
			 data-user-name="${user.display_name}"
			 data-user-sessions="${user.session_count}">
			<div class="text-sm font-medium text-gray-900">${user.display_name} (${user.login})</div>
			<div class="text-xs text-gray-500">${user.email}</div>
			<div class="text-xs text-gray-400">${sessionsLabel} ${user.session_count}</div>
		</div>
	`).join('');

	// Add click handlers to results
	resultsContainer.querySelectorAll('.session-limiter-user-result').forEach(result => {
		result.addEventListener('click', () => selectUser(result.dataset));
	});
}

/**
 * Select a user
 */
function selectUser(userData) {
	const searchInput = document.getElementById('session-limiter-user-search');
	const resultsContainer = document.getElementById('session-limiter-user-search-results');
	const selectedUserContainer = document.getElementById('session-limiter-selected-user');

	document.getElementById('session-limiter-selected-user-id').value = userData.userId;
	document.getElementById('session-limiter-selected-user-name').textContent = `${userData.userName} (${userData.userLogin})`;
	document.getElementById('session-limiter-selected-user-email').textContent = userData.userEmail;
	document.getElementById('session-limiter-selected-user-sessions').textContent = userData.userSessions;

	selectedUserContainer.classList.remove('hidden');
	resultsContainer.classList.add('hidden');
	searchInput.setAttribute('aria-expanded', 'false');
	searchInput.value = '';
}

/**
 * Initialize force logout functionality
 */
function initForceLogout() {
	const forceLogoutBtn = document.getElementById('session-limiter-force-logout-btn');

	if (!forceLogoutBtn) {
		return;
	}

	forceLogoutBtn.addEventListener('click', () => {
		const userId = document.getElementById('session-limiter-selected-user-id').value;
		const userName = document.getElementById('session-limiter-selected-user-name').textContent;

		if (!userId) {
			showToast(__('Please select a user first.', 'session-limiter'), 'error');
			return;
		}

		showModal(
			__('Force Logout User', 'session-limiter'),
			sprintf(
				/* translators: %s: username */
				__('Are you sure you want to terminate all sessions for %s? They will be logged out immediately.', 'session-limiter'),
				userName
			),
			async () => {
				try {
					const formData = new FormData();
					formData.append('action', 'session_limiter_force_logout_user');
					formData.append('nonce', window.sessionLimiterPro.nonce);
					formData.append('user_id', userId);

					const response = await fetch(window.sessionLimiterPro.ajaxUrl, {
						method: 'POST',
						body: formData,
					});

					const data = await response.json();

					if (data.success) {
						showToast(data.data.message, 'success');
						// Update session count
						document.getElementById('session-limiter-selected-user-sessions').textContent = '0';
					} else {
						showToast(data.data.message || __('An error occurred.', 'session-limiter'), 'error');
					}
				} catch (error) {
					showToast(__('An error occurred while processing the request.', 'session-limiter'), 'error');
					console.error('Force logout error:', error);
				}
			}
		);
	});
}

/**
 * Initialize logout all sessions functionality
 */
function initLogoutAllSessions() {
	const logoutAllBtn = document.getElementById('session-limiter-logout-all-btn');

	if (!logoutAllBtn) {
		return;
	}

	logoutAllBtn.addEventListener('click', () => {
		showModal(
			__('Logout All Sessions', 'session-limiter'),
			__('Are you sure you want to terminate all sessions for all users? This action cannot be undone. Your current session will be preserved.', 'session-limiter'),
			async () => {
				try {
					const formData = new FormData();
					formData.append('action', 'session_limiter_logout_all_sessions');
					formData.append('nonce', window.sessionLimiterPro.nonce);
					formData.append('confirm', 'confirm');

					const response = await fetch(window.sessionLimiterPro.ajaxUrl, {
						method: 'POST',
						body: formData,
					});

					const data = await response.json();

					if (data.success) {
						showToast(data.data.message, 'success');
					} else {
						showToast(data.data.message || __('An error occurred.', 'session-limiter'), 'error');
					}
				} catch (error) {
					showToast(__('An error occurred while processing the request.', 'session-limiter'), 'error');
					console.error('Logout all error:', error);
				}
			}
		);
	});
}

/**
 * Initialize reset settings functionality
 */
function initResetSettings() {
	const resetBtn = document.getElementById('session-limiter-reset-data-btn');

	if (!resetBtn) {
		return;
	}

	resetBtn.addEventListener('click', () => {
		showModal(
			__('Reset Plugin Settings', 'session-limiter'),
			__('Are you sure you want to reset all plugin settings to their default values? This action cannot be undone.', 'session-limiter'),
			async () => {
				try {
					const formData = new FormData();
					formData.append('action', 'session_limiter_reset_plugin_data');
					formData.append('nonce', window.sessionLimiterPro.nonce);
					formData.append('confirm', 'confirm');

					const response = await fetch(window.sessionLimiterPro.ajaxUrl, {
						method: 'POST',
						body: formData,
					});

					const data = await response.json();

					if (data.success) {
						showToast(data.data.message, 'success');
						// Reload after a short delay
						setTimeout(() => {
							window.location.href = window.location.href.split('?')[0] + '?page=session-limiter';
						}, 1500);
					} else {
						showToast(data.data.message || __('An error occurred.', 'session-limiter'), 'error');
					}
				} catch (error) {
					showToast(__('An error occurred while processing the request.', 'session-limiter'), 'error');
					console.error('Reset error:', error);
				}
			}
		);
	});
}

/**
 * Initialize export settings functionality
 */
function initExportSettings() {
	const exportBtn = document.getElementById('session-limiter-export-settings-btn');

	if (!exportBtn) {
		return;
	}

	exportBtn.addEventListener('click', async () => {
		exportBtn.disabled = true;
		const originalText = exportBtn.innerHTML;
		exportBtn.innerHTML = `<svg class="animate-spin h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>${__('Exporting...', 'session-limiter')}`;

		try {
			const formData = new FormData();
			formData.append('action', 'session_limiter_export_settings');
			formData.append('nonce', window.sessionLimiterPro.nonce);

			const response = await fetch(window.sessionLimiterPro.ajaxUrl, {
				method: 'POST',
				body: formData,
			});

			const data = await response.json();

			if (data.success) {
				// Create and download JSON file
				const jsonStr = JSON.stringify(data.data.export_data, null, 2);
				const blob = new Blob([jsonStr], { type: 'application/json' });
				const url = URL.createObjectURL(blob);
				
				const a = document.createElement('a');
				a.href = url;
				a.download = data.data.filename;
				document.body.appendChild(a);
				a.click();
				document.body.removeChild(a);
				URL.revokeObjectURL(url);

				showToast(__('Settings exported successfully.', 'session-limiter'), 'success');
			} else {
				showToast(data.data.message || __('Export failed.', 'session-limiter'), 'error');
			}
		} catch (error) {
			showToast(__('An error occurred while exporting settings.', 'session-limiter'), 'error');
			console.error('Export error:', error);
		} finally {
			exportBtn.disabled = false;
			exportBtn.innerHTML = originalText;
		}
	});
}

/**
 * Initialize import settings functionality
 */
function initImportSettings() {
	const importBtn = document.getElementById('session-limiter-import-settings-btn');
	const fileInput = document.getElementById('session-limiter-import-file');
	const fileNameDisplay = document.getElementById('session-limiter-import-file-name');

	if (!importBtn || !fileInput) {
		return;
	}

	let selectedFileContent = null;

	// Handle file selection
	fileInput.addEventListener('change', (e) => {
		const file = e.target.files[0];

		if (!file) {
			importBtn.disabled = true;
			fileNameDisplay?.classList.add('hidden');
			selectedFileContent = null;
			return;
		}

		// Validate file type
		if (file.type !== 'application/json' && !file.name.endsWith('.json')) {
			showToast(__('Please select a valid JSON file.', 'session-limiter'), 'error');
			fileInput.value = '';
			importBtn.disabled = true;
			fileNameDisplay?.classList.add('hidden');
			selectedFileContent = null;
			return;
		}

		// Read the file
		const reader = new FileReader();
		reader.onload = (event) => {
			try {
				// Validate JSON structure
				const jsonData = JSON.parse(event.target.result);
				
				if (!jsonData.plugin || jsonData.plugin !== 'session-limiter') {
					throw new Error('Invalid plugin identifier');
				}

				selectedFileContent = event.target.result;
				importBtn.disabled = false;
				
				if (fileNameDisplay) {
					fileNameDisplay.textContent = sprintf(
						/* translators: %1$s: filename, %2$s: date */
						__('Selected: %1$s (exported: %2$s)', 'session-limiter'),
						file.name,
						jsonData.exported || __('Unknown', 'session-limiter')
					);
					fileNameDisplay.classList.remove('hidden');
				}
			} catch (error) {
				showToast(__('Invalid settings file. Please select a valid Session Limiter PRO export file.', 'session-limiter'), 'error');
				fileInput.value = '';
				importBtn.disabled = true;
				fileNameDisplay?.classList.add('hidden');
				selectedFileContent = null;
			}
		};

		reader.onerror = () => {
			showToast(__('Error reading file.', 'session-limiter'), 'error');
			fileInput.value = '';
			importBtn.disabled = true;
			selectedFileContent = null;
		};

		reader.readAsText(file);
	});

	// Handle import
	importBtn.addEventListener('click', () => {
		if (!selectedFileContent) {
			showToast(__('Please select a file first.', 'session-limiter'), 'error');
			return;
		}

		showModal(
			__('Import Settings', 'session-limiter'),
			__('Are you sure you want to import these settings? This will overwrite your current settings and cannot be undone.', 'session-limiter'),
			async () => {
				importBtn.disabled = true;
				const originalText = importBtn.innerHTML;
				importBtn.innerHTML = `<svg class="animate-spin h-4 w-4 mr-1.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>${__('Importing...', 'session-limiter')}`;

				try {
					const formData = new FormData();
					formData.append('action', 'session_limiter_import_settings');
					formData.append('nonce', window.sessionLimiterPro.nonce);
					formData.append('import_data', selectedFileContent);

					const response = await fetch(window.sessionLimiterPro.ajaxUrl, {
						method: 'POST',
						body: formData,
					});

					const data = await response.json();

					if (data.success) {
						showToast(data.data.message, 'success');
						// Reload after a short delay
						setTimeout(() => {
							window.location.href = window.location.href.split('?')[0] + '?page=session-limiter';
						}, 1500);
					} else {
						showToast(data.data.message || __('Import failed.', 'session-limiter'), 'error');
						importBtn.disabled = false;
						importBtn.innerHTML = originalText;
					}
				} catch (error) {
					showToast(__('An error occurred while importing settings.', 'session-limiter'), 'error');
					console.error('Import error:', error);
					importBtn.disabled = false;
					importBtn.innerHTML = originalText;
				}
			}
		);
	});
}

/**
 * Modal functionality with accessibility
 */
let modalCallback = null;
let previousActiveElement = null;

function initModal() {
	// Use event delegation on document for modal buttons
	// This ensures the handlers work even when elements are in hidden panels
	document.addEventListener('click', (e) => {
		const confirmBtn = e.target.closest('#session-limiter-modal-confirm');
		const cancelBtn = e.target.closest('#session-limiter-modal-cancel');
		const modal = document.getElementById('session-limiter-confirm-modal');

		if (confirmBtn && modal && !modal.classList.contains('hidden')) {
			e.preventDefault();
			// Save callback before hideModal clears it
			const callback = modalCallback;
			hideModal();
			if (callback) {
				callback();
			}
		}

		if (cancelBtn && modal && !modal.classList.contains('hidden')) {
			e.preventDefault();
			hideModal();
		}

		// Close on backdrop click
		if (modal && e.target === modal) {
			hideModal();
		}

		// Check for backdrop overlay click
		if (e.target.classList.contains('bg-gray-500') && e.target.classList.contains('bg-opacity-75')) {
			hideModal();
		}
	});

	// Close on Escape key
	document.addEventListener('keydown', (e) => {
		const modal = document.getElementById('session-limiter-confirm-modal');
		if (!modal || modal.classList.contains('hidden')) {
			return;
		}

		if (e.key === 'Escape') {
			hideModal();
		}

		// Focus trapping
		if (e.key === 'Tab') {
			const focusableElements = modal.querySelectorAll(
				'button:not([disabled]), [href], input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
			);
			const firstElement = focusableElements[0];
			const lastElement = focusableElements[focusableElements.length - 1];

			if (e.shiftKey && document.activeElement === firstElement) {
				e.preventDefault();
				lastElement?.focus();
			} else if (!e.shiftKey && document.activeElement === lastElement) {
				e.preventDefault();
				firstElement?.focus();
			}
		}
	});
}

function showModal(title, message, callback) {
	const modal = document.getElementById('session-limiter-confirm-modal');
	const titleEl = document.getElementById('session-limiter-modal-title');
	const messageEl = document.getElementById('session-limiter-modal-message');

	if (!modal) {
		// Fallback to custom confirm dialog instead of native
		if (confirm(message)) {
			callback();
		}
		return;
	}

	// Store the currently focused element to restore later
	previousActiveElement = document.activeElement;

	titleEl.textContent = title;
	messageEl.textContent = message;
	modalCallback = callback;
	modal.classList.remove('hidden');

	// Announce to screen readers
	modal.setAttribute('aria-hidden', 'false');
	document.body.style.overflow = 'hidden';

	// Focus the cancel button (safer default)
	const cancelBtn = document.getElementById('session-limiter-modal-cancel');
	setTimeout(() => cancelBtn?.focus(), 50);
}

function hideModal() {
	const modal = document.getElementById('session-limiter-confirm-modal');
	modal?.classList.add('hidden');
	modal?.setAttribute('aria-hidden', 'true');
	document.body.style.overflow = '';
	modalCallback = null;

	// Restore focus to the previous element
	if (previousActiveElement) {
		previousActiveElement.focus();
		previousActiveElement = null;
	}
}

/**
 * Toast functionality
 */
let toastTimeout = null;

function initToast() {
	// Use event delegation for toast close button
	document.addEventListener('click', (e) => {
		if (e.target.closest('#session-limiter-toast-close')) {
			hideToast();
		}
	});
}

function showToast(message, type = 'info') {
	const toast = document.getElementById('session-limiter-toast');
	const messageEl = document.getElementById('session-limiter-toast-message');
	const iconEl = document.getElementById('session-limiter-toast-icon');

	if (!toast) {
		// Fallback: Create an inline notification if toast element doesn't exist
		showInlineNotification(message, type);
		return;
	}

	// Clear existing timeout
	if (toastTimeout) {
		clearTimeout(toastTimeout);
	}

	// Set message
	messageEl.textContent = message;

	// Set styles based on type
	const toastInner = toast.querySelector('div');
	toastInner.classList.remove('bg-green-50', 'bg-red-50', 'bg-blue-50');
	messageEl.classList.remove('text-green-800', 'text-red-800', 'text-blue-800');

	let iconHtml = '';
	switch (type) {
		case 'success':
			toastInner.classList.add('bg-green-50');
			messageEl.classList.add('text-green-800');
			iconHtml = `<svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
				<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
			</svg>`;
			break;
		case 'error':
			toastInner.classList.add('bg-red-50');
			messageEl.classList.add('text-red-800');
			iconHtml = `<svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
				<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
			</svg>`;
			break;
		default:
			toastInner.classList.add('bg-blue-50');
			messageEl.classList.add('text-blue-800');
			iconHtml = `<svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
				<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
			</svg>`;
	}

	iconEl.innerHTML = iconHtml;

	// Show toast
	toast.classList.remove('hidden');

	// Auto-hide after 5 seconds
	toastTimeout = setTimeout(hideToast, 5000);
}

function hideToast() {
	const toast = document.getElementById('session-limiter-toast');
	toast?.classList.add('hidden');
	if (toastTimeout) {
		clearTimeout(toastTimeout);
		toastTimeout = null;
	}
}

/**
 * Show inline notification (fallback when toast element doesn't exist)
 * Also used for persistent notifications like license errors
 */
function showInlineNotification(message, type = 'info', options = {}) {
	const { 
		container = document.querySelector('.session-limiter-pro-settings .max-w-5xl'),
		persistent = false,
		title = ''
	} = options;

	if (!container) {
		// Ultimate fallback - never use native alert
		console.warn(`[Session Limiter PRO] ${type}: ${message}`);
		return;
	}

	// Create notification element
	const notification = document.createElement('div');
	notification.className = `session-limiter-notification session-limiter-notification-${type}`;
	notification.setAttribute('role', 'alert');
	notification.setAttribute('aria-live', 'polite');

	// Icon based on type
	const icons = {
		error: `<svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
			<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
		</svg>`,
		success: `<svg class="h-5 w-5 text-green-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
			<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
		</svg>`,
		warning: `<svg class="h-5 w-5 text-yellow-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
			<path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
		</svg>`,
		info: `<svg class="h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
			<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
		</svg>`
	};

	const titleHtml = title ? `<p class="session-limiter-notification-title">${title}</p>` : '';

	notification.innerHTML = `
		<div class="session-limiter-notification-icon">
			${icons[type] || icons.info}
		</div>
		<div class="session-limiter-notification-content">
			${titleHtml}
			<p class="session-limiter-notification-message">${message}</p>
		</div>
		<button type="button" class="session-limiter-notification-dismiss" aria-label="${__('Dismiss notification', 'session-limiter')}">
			<svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
				<path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
			</svg>
		</button>
	`;

	// Insert at top of container
	const tabsContainer = container.querySelector('.session-limiter-tabs-container');
	if (tabsContainer) {
		tabsContainer.parentNode.insertBefore(notification, tabsContainer);
	} else {
		container.insertBefore(notification, container.firstChild);
	}

	// Setup dismiss button
	const dismissBtn = notification.querySelector('.session-limiter-notification-dismiss');
	dismissBtn?.addEventListener('click', () => {
		notification.remove();
	});

	// Auto-dismiss after 8 seconds (unless persistent)
	if (!persistent) {
		setTimeout(() => {
			notification.style.opacity = '0';
			notification.style.transform = 'translateY(-10px)';
			notification.style.transition = 'opacity 0.3s, transform 0.3s';
			setTimeout(() => notification.remove(), 300);
		}, 8000);
	}

	return notification;
}

/**
 * Initialize license activation functionality
 */
function initLicenseActivation() {
	const activateBtn = document.getElementById('session-limiter-activate-license');
	const deactivateBtn = document.getElementById('session-limiter-deactivate-license');
	const licenseInput = document.getElementById('session-limiter-license-key');

	// License error messages mapping for better user feedback
	const licenseErrorTitles = {
		'missing_license_key': __('License Key Not Found', 'session-limiter'),
		'expired_license_key': __('License Expired', 'session-limiter'),
		'can_not_add_new_domain': __('Activation Limit Reached', 'session-limiter'),
		'invalid_license_or_domain': __('Invalid License', 'session-limiter'),
		'unregistered_license_domain': __('Domain Not Registered', 'session-limiter'),
	};

	if (activateBtn) {
		activateBtn.addEventListener('click', async () => {
			const licenseKey = licenseInput?.value?.trim();

			if (!licenseKey) {
				showInlineNotification(
					__('Please enter your license key to activate the plugin.', 'session-limiter'),
					'warning',
					{ title: __('License Key Required', 'session-limiter') }
				);
				licenseInput?.focus();
				return;
			}

			activateBtn.disabled = true;
			activateBtn.textContent = __('Activating...', 'session-limiter');

			try {
				const formData = new FormData();
				formData.append('action', 'session_limiter_activate_license');
				formData.append('nonce', window.sessionLimiterPro.nonce);
				formData.append('license_key', licenseKey);

				const response = await fetch(window.sessionLimiterPro.ajaxUrl, {
					method: 'POST',
					body: formData,
				});

				const data = await response.json();

				if (data.success) {
					showInlineNotification(
						data.data.message,
						'success',
						{ title: __('License Activated', 'session-limiter') }
					);
					// Reload to show updated license status
					setTimeout(() => window.location.reload(), 1500);
				} else {
					const errorMessage = data.data.message || __('Activation failed. Please check your license key and try again.', 'session-limiter');
					const errorCode = data.data.error_code || '';
					const errorTitle = licenseErrorTitles[errorCode] || __('Activation Failed', 'session-limiter');

					showInlineNotification(errorMessage, 'error', { title: errorTitle });
					
					activateBtn.disabled = false;
					activateBtn.textContent = __('Activate License', 'session-limiter');
				}
			} catch (error) {
				showInlineNotification(
					__('Could not connect to the license server. Please check your internet connection and try again.', 'session-limiter'),
					'error',
					{ title: __('Connection Error', 'session-limiter') }
				);
				console.error('License activation error:', error);
				activateBtn.disabled = false;
				activateBtn.textContent = __('Activate License', 'session-limiter');
			}
		});
	}

	if (deactivateBtn) {
		deactivateBtn.addEventListener('click', () => {
			showModal(
				__('Deactivate License', 'session-limiter'),
				__('Are you sure you want to deactivate your license? You will no longer receive updates.', 'session-limiter'),
				async () => {
					deactivateBtn.disabled = true;
					deactivateBtn.textContent = __('Deactivating...', 'session-limiter');

					try {
						const formData = new FormData();
						formData.append('action', 'session_limiter_deactivate_license');
						formData.append('nonce', window.sessionLimiterPro.nonce);

						const response = await fetch(window.sessionLimiterPro.ajaxUrl, {
							method: 'POST',
							body: formData,
						});

						const data = await response.json();

						if (data.success) {
							showInlineNotification(
								data.data.message,
								'success',
								{ title: __('License Deactivated', 'session-limiter') }
							);
							// Reload to show updated license status
							setTimeout(() => window.location.reload(), 1500);
						} else {
							showInlineNotification(
								data.data.message || __('Deactivation failed. Please try again.', 'session-limiter'),
								'error',
								{ title: __('Deactivation Failed', 'session-limiter') }
							);
							deactivateBtn.disabled = false;
							deactivateBtn.textContent = __('Deactivate License', 'session-limiter');
						}
					} catch (error) {
						showInlineNotification(
							__('Could not connect to the license server. Please try again.', 'session-limiter'),
							'error',
							{ title: __('Connection Error', 'session-limiter') }
						);
						console.error('License deactivation error:', error);
						deactivateBtn.disabled = false;
						deactivateBtn.textContent = __('Deactivate License', 'session-limiter');
					}
				}
			);
		});
	}
}
