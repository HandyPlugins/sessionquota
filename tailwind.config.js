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
				'session-limiter-fadeIn': {
					'from': { opacity: '0', transform: 'translateY(4px)' },
					'to': { opacity: '1', transform: 'translateY(0)' },
				},
				'session-limiter-slideIn': {
					'from': { opacity: '0', transform: 'translateY(-0.5rem)' },
					'to': { opacity: '1', transform: 'translateY(0)' },
				},
				'session-limiter-spin': {
					'from': { transform: 'rotate(0deg)' },
					'to': { transform: 'rotate(360deg)' },
				},
			},
			animation: {
				'session-limiter-fadeIn': 'session-limiter-fadeIn 0.2s ease-out',
				'session-limiter-slideIn': 'session-limiter-slideIn 0.3s ease-out',
				'session-limiter-spin': 'session-limiter-spin 1s linear infinite',
			},
		},
	},
	plugins: [],
	corePlugins: {
		preflight: false, // Disable Tailwind's reset for WordPress admin compatibility
	},
};
