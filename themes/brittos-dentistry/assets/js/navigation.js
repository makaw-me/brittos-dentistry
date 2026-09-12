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

	// Homepage-only: the header starts fully transparent over the hero and
	// solidifies once the visitor scrolls, so nav links stay legible over
	// ordinary page content further down.
	var transparentHeader = document.querySelector( '.site-header--transparent' );
	if ( transparentHeader ) {
		var scrollThreshold = 24;
		var ticking = false;

		function updateHeaderState() {
			var isScrolled = window.scrollY > scrollThreshold;
			transparentHeader.classList.toggle( 'is-scrolled', isScrolled );
			ticking = false;
		}

		function requestHeaderUpdate() {
			if ( ! ticking ) {
				window.requestAnimationFrame( updateHeaderState );
				ticking = true;
			}
		}

		updateHeaderState();
		window.addEventListener( 'scroll', requestHeaderUpdate, { passive: true } );
	}

	/**
	 * Treatments mega menu enhancement. The panel already works with
	 * zero JavaScript via :hover/:focus-within in CSS (see
	 * assets/css/components.css) — this only adds a proper disclosure
	 * button for touch users, explicit click/tap control, Escape, and
	 * outside-click handling. Removing this script entirely still
	 * leaves a fully working (if less refined) menu.
	 */
	var megaItems = document.querySelectorAll( '.primary-nav__item--mega' );

	megaItems.forEach( function ( item ) {
		var link = item.querySelector( '.primary-nav__link--mega' );
		var panel = item.querySelector( '.mega-panel' );
		if ( ! link || ! panel ) {
			return;
		}

		var toggleButton = document.createElement( 'button' );
		toggleButton.type = 'button';
		toggleButton.className = 'mega-panel-toggle';
		toggleButton.setAttribute( 'aria-expanded', 'false' );
		toggleButton.setAttribute( 'aria-controls', panel.id );
		toggleButton.setAttribute( 'aria-label', link.textContent.trim() + ' menu' );
		toggleButton.innerHTML = '<svg class="mega-panel-toggle__chevron" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>';
		item.appendChild( toggleButton );

		function closeMegaPanel() {
			item.classList.remove( 'is-open' );
			toggleButton.setAttribute( 'aria-expanded', 'false' );
		}

		function openMegaPanel() {
			megaItems.forEach( function ( other ) {
				if ( other !== item ) {
					other.classList.remove( 'is-open' );
					var otherToggle = other.querySelector( '.mega-panel-toggle' );
					if ( otherToggle ) {
						otherToggle.setAttribute( 'aria-expanded', 'false' );
					}
				}
			} );
			item.classList.add( 'is-open' );
			toggleButton.setAttribute( 'aria-expanded', 'true' );
		}

		toggleButton.addEventListener( 'click', function ( event ) {
			event.stopPropagation();
			if ( item.classList.contains( 'is-open' ) ) {
				closeMegaPanel();
			} else {
				openMegaPanel();
			}
		} );

		item.addEventListener( 'keydown', function ( event ) {
			if ( event.key === 'Escape' && item.classList.contains( 'is-open' ) ) {
				closeMegaPanel();
				link.focus();
			}
		} );

		// Outside click (desktop hover-dropdown left open via a prior click).
		document.addEventListener( 'click', function ( event ) {
			if ( item.classList.contains( 'is-open' ) && ! item.contains( event.target ) ) {
				closeMegaPanel();
			}
		} );
	} );
} )();
