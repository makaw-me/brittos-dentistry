/**
 * Global promotional popup: shows once per visitor on first arrival,
 * gated by a namespaced cookie set on dismissal. Only runs at all when
 * the feature is enabled server-side (see inc/enqueue.php) — this file
 * is never enqueued otherwise, so a disabled popup costs nothing.
 *
 * Mirrors the same accessible-dialog idioms already used for the mobile
 * nav drawer (assets/js/navigation.js): backdrop, focus trap, Escape to
 * close, and a scroll lock that avoids the iOS fixed-positioning bug.
 */
( function () {
	'use strict';

	var popup = document.querySelector( '[data-promo-popup]' );
	if ( ! popup ) {
		return;
	}

	var cookieName = popup.getAttribute( 'data-cookie-name' ) || 'brittos_promo_popup_dismissed';
	var cookieDays = parseInt( popup.getAttribute( 'data-cookie-days' ), 10 );
	if ( ! cookieDays || cookieDays < 1 ) {
		cookieDays = 30;
	}

	function getCookie( name ) {
		var match = document.cookie.match( '(?:^|; )' + name.replace( /([.$?*|{}()[\]\\/+^])/g, '\\$1' ) + '=([^;]*)' );
		return match ? decodeURIComponent( match[ 1 ] ) : null;
	}

	function setCookie( name, value, days ) {
		var expires = new Date( Date.now() + days * 24 * 60 * 60 * 1000 ).toUTCString();
		var secure = 'https:' === window.location.protocol ? '; Secure' : '';
		document.cookie = name + '=' + encodeURIComponent( value ) + '; expires=' + expires + '; path=/; SameSite=Lax' + secure;
	}

	// Already dismissed within the configured window — never show it.
	if ( getCookie( cookieName ) ) {
		return;
	}

	var dialog = popup.querySelector( '.promo-popup__dialog' );
	var backdrop = popup.querySelector( '[data-promo-popup-backdrop]' );
	var closeButtons = popup.querySelectorAll( '[data-promo-popup-close]' );
	var lastFocused = null;
	var savedScrollY = 0;

	function lockScroll() {
		// position:fixed body trick avoids the iOS Safari fixed-positioning
		// bug that overflow:hidden on <html> triggers (same approach as
		// the mobile nav drawer in navigation.js).
		savedScrollY = window.scrollY;
		document.body.style.position = 'fixed';
		document.body.style.top      = '-' + savedScrollY + 'px';
		document.body.style.left     = '0';
		document.body.style.right    = '0';
		document.body.style.width    = '100%';
	}

	function unlockScroll() {
		var y = savedScrollY;
		window.scrollTo( { top: y, behavior: 'instant' } );
		document.body.style.position = '';
		document.body.style.top      = '';
		document.body.style.left     = '';
		document.body.style.right    = '';
		document.body.style.width    = '';
	}

	function getFocusable() {
		return Array.prototype.slice.call(
			dialog.querySelectorAll( 'a[href], button:not([disabled]), input, select, textarea, [tabindex]:not([tabindex="-1"])' )
		);
	}

	function closePopup( dismiss ) {
		popup.classList.remove( 'is-open' );
		unlockScroll();
		if ( dismiss ) {
			setCookie( cookieName, '1', cookieDays );
		}
		if ( lastFocused && typeof lastFocused.focus === 'function' ) {
			lastFocused.focus();
		}
		window.removeEventListener( 'keydown', handleKeydown );
		// Fully remove after the close transition so it can't be tabbed
		// into or read by assistive tech while invisible.
		window.setTimeout( function () {
			popup.hidden = true;
		}, 300 );
	}

	function handleKeydown( event ) {
		if ( event.key === 'Escape' ) {
			closePopup( true );
			return;
		}
		if ( event.key !== 'Tab' ) {
			return;
		}
		var focusable = getFocusable();
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

	function openPopup() {
		lastFocused = document.activeElement;
		popup.hidden = false;
		// Next frame, so the transition (if any) actually runs instead of
		// jumping straight to the open state.
		window.requestAnimationFrame( function () {
			popup.classList.add( 'is-open' );
		} );
		lockScroll();
		window.addEventListener( 'keydown', handleKeydown );
		var focusable = getFocusable();
		if ( focusable.length ) {
			focusable[ 0 ].focus();
		} else {
			dialog.setAttribute( 'tabindex', '-1' );
			dialog.focus();
		}
	}

	closeButtons.forEach( function ( button ) {
		button.addEventListener( 'click', function () {
			closePopup( true );
		} );
	} );

	if ( backdrop ) {
		backdrop.addEventListener( 'click', function () {
			closePopup( true );
		} );
	}

	// A CTA click means the visitor took the action being offered — no
	// need to keep nagging them, so treat it the same as a dismissal.
	var cta = popup.querySelector( '.promo-popup__cta' );
	if ( cta ) {
		cta.addEventListener( 'click', function () {
			setCookie( cookieName, '1', cookieDays );
		} );
	}

	// Wait for the page to finish loading before showing anything, so the
	// popup never competes with — or delays perceived readiness of — the
	// page's own primary content.
	if ( document.readyState === 'complete' ) {
		openPopup();
	} else {
		window.addEventListener( 'load', openPopup, { once: true } );
	}
} )();
