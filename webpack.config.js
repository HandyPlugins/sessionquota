const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
	...defaultConfig,
	entry: {
		admin: path.resolve( process.cwd(), 'assets/src', 'admin.js' ),
	},
	output: {
		filename: '[name].js',
		path: path.resolve( process.cwd(), 'assets/build' ),
		clean: true,
	},
};
