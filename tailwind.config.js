/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [
		'./includes/**/*.php',
		'./assets/src/**/*.js',
		'./assets/src/**/*.css',
	],
	theme: {
		extend: {
			keyframes: {
				'sessionquota-fadeIn': {
					'from': { opacity: '0', transform: 'translateY(4px)' },
					'to': { opacity: '1', transform: 'translateY(0)' },
				},
				'sessionquota-slideIn': {
					'from': { opacity: '0', transform: 'translateY(-0.5rem)' },
					'to': { opacity: '1', transform: 'translateY(0)' },
				},
				'sessionquota-spin': {
					'from': { transform: 'rotate(0deg)' },
					'to': { transform: 'rotate(360deg)' },
				},
			},
			animation: {
				'sessionquota-fadeIn': 'sessionquota-fadeIn 0.2s ease-out',
				'sessionquota-slideIn': 'sessionquota-slideIn 0.3s ease-out',
				'sessionquota-spin': 'sessionquota-spin 1s linear infinite',
			},
		},
	},
	plugins: [],
	corePlugins: {
		preflight: false, // Disable Tailwind's reset for WordPress admin compatibility
	},
};
