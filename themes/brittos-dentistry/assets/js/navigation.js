/**
 * Accessible mobile navigation toggle.
 * Progressive enhancement: the menu is a normal, visible list without JS
 * (see components.css desktop breakpoint); this only adds the mobile
 * open/close behaviour, focus trapping and Escape-to-close.
 */
( function () {
	'use strict';

	var toggle = document.querySelector( '.nav-toggle' );
	var nav = document.getElementById( 'primary-navigation' );

	if ( ! toggle || ! nav ) {
		return;
	}

	function closeNav() {
		nav.classList.remove( 'is-open' );
		toggle.setAttribute( 'aria-expanded', 'false' );
	}

	function openNav() {
		nav.classList.add( 'is-open' );
		toggle.setAttribute( 'aria-expanded', 'true' );
		var firstLink = nav.querySelector( 'a, button' );
		if ( firstLink ) {
			firstLink.focus();
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

	document.addEventListener( 'keydown', function ( event ) {
		if ( event.key === 'Escape' && nav.classList.contains( 'is-open' ) ) {
			closeNav();
			toggle.focus();
		}
	} );

	// Close the mobile menu automatically once the viewport is wide enough
	// that navigation is displayed inline (avoids a stuck open state).
	var desktopQuery = window.matchMedia( '(min-width: 960px)' );
	function handleViewportChange( event ) {
		if ( event.matches ) {
			closeNav();
		}
	}
	if ( typeof desktopQuery.addEventListener === 'function' ) {
		desktopQuery.addEventListener( 'change', handleViewportChange );
	}

	// Close after activating a link (single-page anchor links especially).
	nav.addEventListener( 'click', function ( event ) {
		if ( event.target.tagName === 'A' && ! desktopQuery.matches ) {
			closeNav();
		}
	} );
} )();
