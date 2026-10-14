const js = require('@eslint/js');
const globals = require('globals');

module.exports = [
	{
		ignores: ['assets/js/vendors/**', 'assets/js/copy/plyr.min.js', 'dist/**', 'node_modules/**']
	},
	js.configs.recommended,
	{
		// theme scripts (browser, bundled by Webpack)
		files: ['assets/js/**/*.js'],
		languageOptions: {
			ecmaVersion: 'latest',
			sourceType: 'module',
			globals: {
				...globals.browser,
				jQuery: 'readonly',
				itSettings: 'readonly', // wp_localize_script (inc/scripts-styles.php)
				wpAdminSettings: 'readonly'
			}
		},
		rules: {
			'no-unused-vars': ['warn', {args: 'none', caughtErrors: 'none'}],
			'no-empty': ['error', {allowEmptyCatch: true}]
		}
	},
	{
		// build tools (Node.js, CommonJS)
		files: ['gulpfile.js', 'eslint.config.js'],
		languageOptions: {
			ecmaVersion: 'latest',
			sourceType: 'commonjs',
			globals: globals.node
		}
	}
];
