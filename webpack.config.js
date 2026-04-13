const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

module.exports = {
	...defaultConfig,
	entry: async () => {
		const defaultEntry = await defaultConfig.entry();
		return {
			...defaultEntry,
			view: './src/view.js',
		};
	},
};
