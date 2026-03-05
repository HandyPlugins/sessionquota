/**
 * Admin JavaScript.
 *
 * @package SessionQuota
 */

import './admin.css';

document.addEventListener('DOMContentLoaded', () => {
	initEnforcementModeToggle();
});

/**
 * Initialize enforcement mode UI behavior.
 *
 * Hides session limit field when "Logout all other sessions" is selected and
 * disables non-strict modes when session limit is 0.
 */
function initEnforcementModeToggle() {
	const enforcementModeInputs = document.querySelectorAll('input[name="sessionquota_settings[enforcement_mode]"]');
	const sessionLimitInput = document.querySelector('input[name="sessionquota_settings[session_limit]"]');
	const sessionLimitRow = sessionLimitInput?.closest('tr');
	const enforcementModeContainer = document.getElementById('enforcement-mode-container');

	if (!enforcementModeInputs.length || !sessionLimitRow) {
		return;
	}

	const toggleSessionLimitVisibility = () => {
		const selectedMode = document.querySelector('input[name="sessionquota_settings[enforcement_mode]"]:checked')?.value;
		sessionLimitRow.style.display = selectedMode === 'logout_all_others' ? 'none' : '';
	};

	const toggleEnforcementModeState = () => {
		const limitValue = parseInt(sessionLimitInput?.value, 10) || 0;
		const selectedMode = document.querySelector('input[name="sessionquota_settings[enforcement_mode]"]:checked')?.value;
		const isStrictMode = selectedMode === 'logout_all_others';
		const disableByLimit = limitValue === 0 && !isStrictMode;

		enforcementModeInputs.forEach((input) => {
			const isStrictOption = input.value === 'logout_all_others';
			input.disabled = disableByLimit && !isStrictOption;
			const label = input.closest('label');
			if (label) {
				label.classList.toggle('opacity-50', input.disabled);
			}
		});

		let hintElement = document.getElementById('enforcement-disabled-hint');
		if (disableByLimit) {
			if (!hintElement && enforcementModeContainer) {
				hintElement = document.createElement('p');
				hintElement.id = 'enforcement-disabled-hint';
				hintElement.className = 'mt-3 text-sm text-amber-600';
				hintElement.innerHTML = '<span class="dashicons dashicons-info" style="font-size: 16px; width: 16px; height: 16px; margin-right: 4px;"></span>'
					+ (window.SessionQuota?.i18n?.enforcementHint || 'Set the session limit to 1 or higher to enable block and logout-oldest modes. Strict mode remains available.');
				enforcementModeContainer.parentNode.insertBefore(hintElement, enforcementModeContainer.nextSibling);
			}
		} else if (hintElement) {
			hintElement.remove();
		}
	};

	toggleSessionLimitVisibility();
	toggleEnforcementModeState();

	enforcementModeInputs.forEach((input) => {
		input.addEventListener('change', () => {
			toggleSessionLimitVisibility();
			toggleEnforcementModeState();
		});
	});

	if (sessionLimitInput) {
		sessionLimitInput.addEventListener('input', toggleEnforcementModeState);
		sessionLimitInput.addEventListener('change', toggleEnforcementModeState);
	}
}
