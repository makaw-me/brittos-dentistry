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

/*
 * Script loading for theme-mode interactions is handled in inc/enqueue.php,
 * either from the bundled dist asset or from source-file fallbacks.
 */