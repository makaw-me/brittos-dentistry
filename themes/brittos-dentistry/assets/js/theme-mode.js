/**
 * Persist and announce the explicit Light/Dark choice.
 */
( function () {
	'use strict';

	var root = document.documentElement;
	var toggles = document.querySelectorAll( '[data-theme-toggle]' );

	if ( ! toggles.length ) {
		return;
	}

	function updateToggle( theme ) {
		var nextTheme = 'dark' === theme ? 'light' : 'dark';
		toggles.forEach( function ( toggle ) {
			toggle.setAttribute( 'aria-label', 'dark' === theme ? 'Switch to light mode' : 'Switch to dark mode' );
			toggle.setAttribute( 'title', 'dark' === theme ? 'Switch to light mode' : 'Switch to dark mode' );
			toggle.setAttribute( 'aria-pressed', 'dark' === theme ? 'true' : 'false' );
			toggle.setAttribute( 'data-next-theme', nextTheme );
		} );
	}

	updateToggle( root.getAttribute( 'data-theme' ) || 'light' );

	toggles.forEach( function ( toggle ) {
		toggle.addEventListener( 'click', function () {
			var nextTheme = toggle.getAttribute( 'data-next-theme' ) || 'dark';
			root.setAttribute( 'data-theme', nextTheme );
			root.classList.add( 'theme-ready' );
			try {
				window.localStorage.setItem( 'brittos-theme', nextTheme );
			} catch ( error ) {
				// The visual state still works when storage is unavailable.
			}
			updateToggle( nextTheme );
		} );
	} );

	if ( window.matchMedia ) {
		var systemQuery = window.matchMedia( '(prefers-color-scheme: dark)' );
		var syncSystemTheme = function ( event ) {
			var stored = null;
			try {
				stored = window.localStorage.getItem( 'brittos-theme' );
			} catch ( error ) {
				// Follow the system when browser storage is unavailable.
			}
			if ( 'light' !== stored && 'dark' !== stored ) {
				var theme = event.matches ? 'dark' : 'light';
				root.setAttribute( 'data-theme', theme );
				updateToggle( theme );
			}
		};
		if ( typeof systemQuery.addEventListener === 'function' ) {
			systemQuery.addEventListener( 'change', syncSystemTheme );
		}
	}

	window.addEventListener( 'storage', function ( event ) {
		if ( 'brittos-theme' === event.key && ( 'light' === event.newValue || 'dark' === event.newValue ) ) {
			root.setAttribute( 'data-theme', event.newValue );
			updateToggle( event.newValue );
		}
	} );
}() );