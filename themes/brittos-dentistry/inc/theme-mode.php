<?php
/**
 * Browser-local color mode resolution and toggle support.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Print the synchronous resolver before WordPress prints visual assets.
 * The value is constrained to the two supported modes and never enters HTML.
 */
function brittos_theme_color_mode_bootstrap() {
	?>
	<script>
	( function () {
		try {
			var stored = window.localStorage.getItem( 'brittos-theme' );
			var theme = 'light';
			if ( 'light' === stored || 'dark' === stored ) {
				theme = stored;
			} else if ( window.matchMedia && window.matchMedia( '(prefers-color-scheme: dark)' ).matches ) {
				theme = 'dark';
			}
			document.documentElement.setAttribute( 'data-theme', theme );
		} catch ( error ) {
			document.documentElement.setAttribute(
				'data-theme',
				window.matchMedia && window.matchMedia( '(prefers-color-scheme: dark)' ).matches ? 'dark' : 'light'
			);
		}
	}() );
	</script>
	<?php
}

/**
 * Enqueue the interaction layer after the page is parsed.
 */
function brittos_theme_mode_assets() {
	$path = BRITTOS_THEME_DIR . '/assets/js/theme-mode.js';

	wp_enqueue_script(
		'brittos-theme-mode',
		BRITTOS_THEME_URI . '/assets/js/theme-mode.js',
		array(),
		file_exists( $path ) ? filemtime( $path ) : BRITTOS_THEME_VERSION,
		array( 'strategy' => 'defer', 'in_footer' => true )
	);
}
add_action( 'wp_enqueue_scripts', 'brittos_theme_mode_assets' );