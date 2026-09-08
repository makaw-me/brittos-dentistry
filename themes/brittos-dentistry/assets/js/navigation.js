/**
 * Accessible mobile navigation drawer & backdrop controller.
 * Progressive enhancement: the menu is a normal inline row on desktop;
 * on mobile, it provides a smooth off-canvas drawer with backdrop,
 * focus trapping, touch/scroll lock, and Escape-to-close.
 */
( function () {
	'use strict';

	var toggle = document.querySelector( '.nav-toggle' );
	var nav = document.getElementById( 'primary-navigation' );
	var backdrop = document.querySelector( '[data-nav-backdrop]' );
	var close = nav ? nav.querySelector( '.nav-close' ) : null;

	if ( ! toggle || ! nav ) {
		return;
	}

	function closeNav() {
		nav.classList.remove( 'is-open' );
		if ( backdrop ) {
			backdrop.classList.remove( 'is-active' );
		}
		toggle.setAttribute( 'aria-expanded', 'false' );
		toggle.setAttribute( 'aria-label', 'Open menu' );
		document.body.classList.remove( 'menu-is-open' );
		document.documentElement.classList.remove( 'menu-is-open' );
	}

	function openNav() {
		nav.classList.add( 'is-open' );
		if ( backdrop ) {
			backdrop.classList.add( 'is-active' );
		}
		toggle.setAttribute( 'aria-expanded', 'true' );
		toggle.setAttribute( 'aria-label', 'Close menu' );
		document.body.classList.add( 'menu-is-open' );
		document.documentElement.classList.add( 'menu-is-open' );

		// Move focus inside drawer
		var firstFocusable = nav.querySelector( '.nav-close, a, button' );
		if ( firstFocusable ) {
			setTimeout( function () {
				firstFocusable.focus();
			}, 50 );
		}
	}

	toggle.addEventListener( 'click', function () {
		var isOpen = toggle.getAttribute( 'aria-expanded' ) === 'true';
		if ( isOpen ) {
			closeNav();
		} else {
			openNav();
		}
	} );

	if ( close ) {
		close.addEventListener( 'click', function () {
			closeNav();
			toggle.focus();
		} );
	}

	if ( backdrop ) {
		backdrop.addEventListener( 'click', function () {
			closeNav();
			toggle.focus();
		} );
	}

	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key === 'Escape' && nav.classList.contains( 'is-open' ) ) {
			closeNav();
			toggle.focus();
		}

		if ( 'Tab' === event.key && nav.classList.contains( 'is-open' ) ) {
			var focusable = nav.querySelectorAll( 'a, button, input, [tabindex]:not([tabindex="-1"])' );
			if ( ! focusable.length ) {
				return;
			}
			var first = focusable[ 0 ];
			var last = focusable[ focusable.length - 1 ];

			if ( event.shiftKey && document.activeElement === first ) {
				event.preventDefault();
				last.focus();
			} else if ( ! event.shiftKey && document.activeElement === last ) {
				event.preventDefault();
				first.focus();
			}
		}
	} );

	// Close the mobile menu automatically once the viewport is wide enough
	var desktopQuery = window.matchMedia( '(min-width: 960px)' );
	function handleViewportChange( event ) {
		if ( event.matches && nav.classList.contains( 'is-open' ) ) {
			closeNav();
		}
	}
	if ( typeof desktopQuery.addEventListener === 'function' ) {
		desktopQuery.addEventListener( 'change', handleViewportChange );
	}

	// Close after clicking any link inside the nav (smooth scroll navigation)
	nav.addEventListener( 'click', function ( event ) {
		var targetLink = event.target.closest( 'a' );
		if ( targetLink && ! desktopQuery.matches ) {
			closeNav();
		}
	} );
} )();
