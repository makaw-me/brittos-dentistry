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

	// Scroll position preserved across open/close so the page doesn't jump.
	var savedScrollY = 0;

	function lockScroll() {
		// Applying overflow:hidden to <html> corrupts the fixed-positioning
		// containing block in iOS Safari and some Chromium builds — the drawer
		// then resolves top:0 against the *document* scroll offset rather than
		// the viewport, so it appears off-screen when the page is scrolled down.
		// The position:fixed body trick avoids this entirely.
		savedScrollY = window.scrollY;
		document.body.style.position = 'fixed';
		document.body.style.top      = '-' + savedScrollY + 'px';
		document.body.style.left     = '0';
		document.body.style.right    = '0';
		document.body.style.width    = '100%';
	}

	function unlockScroll() {
		// Restore scroll position in the same paint as removing the body lock.
		// Calling scrollTo() AFTER clearing body styles causes a single-frame
		// flash where the page jumps to y=0 before snapping back. Setting
		// scrollTo first (while body is still position:fixed) then immediately
		// clearing the lock means both changes land in one composite frame.
		var y = savedScrollY;
		window.scrollTo( { top: y, behavior: 'instant' } );
		document.body.style.position = '';
		document.body.style.top      = '';
		document.body.style.left     = '';
		document.body.style.right    = '';
		document.body.style.width    = '';
	}

	function closeNav() {
		nav.classList.remove( 'is-open' );
		if ( backdrop ) {
			backdrop.classList.remove( 'is-active' );
		}
		toggle.setAttribute( 'aria-expanded', 'false' );
		toggle.setAttribute( 'aria-label', 'Open menu' );
		unlockScroll();
	}

	function openNav() {
		lockScroll();
		nav.classList.add( 'is-open' );
		if ( backdrop ) {
			backdrop.classList.add( 'is-active' );
		}
		toggle.setAttribute( 'aria-expanded', 'true' );
		toggle.setAttribute( 'aria-label', 'Close menu' );

		// Move focus inside the drawer only AFTER the slide-in transition
		// has fully completed. Calling focus() on a visibility:hidden (or
		// mid-transition) element causes the browser to scroll the page to
		// make the element visible — producing the unwanted scroll-to-top.
		// preventScroll:true is an extra safety net for browsers that still
		// try to scroll even on a visible element.
		var focusMoved = false;
		function handleTransitionEnd( event ) {
			// Only act on the transform transition of the nav panel itself.
			if ( event.target !== nav || event.propertyName !== 'transform' ) {
				return;
			}
			if ( focusMoved ) {
				return;
			}
			focusMoved = true;
			nav.removeEventListener( 'transitionend', handleTransitionEnd );
			var firstFocusable = nav.querySelector( '.nav-close, a, button' );
			if ( firstFocusable ) {
				firstFocusable.focus( { preventScroll: true } );
			}
		}
		nav.addEventListener( 'transitionend', handleTransitionEnd );
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

	// Outside-click / outside-tap to close.
	// The backdrop element lives inside a flex container, which causes
	// pointer-events to silently fail on iOS Safari. This document-level
	// pointerdown listener is the cross-browser / cross-device fallback.
	document.addEventListener( 'pointerdown', function ( event ) {
		if (
			nav.classList.contains( 'is-open' ) &&
			! nav.contains( event.target ) &&
			! toggle.contains( event.target )
		) {
			closeNav();
		}
	} );

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
	 * ─── DESKTOP DROPDOWN / MEGA-PANEL VIEWPORT CLAMPING ────────────────────
	 * Submenus and mega-panels use `left:50%; transform:translateX(-50%)` to
	 * centre under their parent item. When that parent sits near either edge
	 * of the header the panel overflows the viewport. We measure the panel's
	 * bounding rect after it has been made temporarily visible (visibility:
	 * hidden, display: block) and compute an offset that keeps it inside the
	 * safe viewport area. The offset is applied via a CSS custom property
	 * --_panel-shift so the existing transition still works normally.
	 */
	function clampDropdown( parentItem, panelEl ) {
		if ( ! panelEl || window.innerWidth < 960 ) {
			return;
		}
		// Temporarily expose the panel so we can measure it without layout
		// thrash. We do this in the hidden state to avoid a flash.
		var prevVisibility = panelEl.style.visibility;
		var prevOpacity    = panelEl.style.opacity;
		panelEl.style.visibility = 'hidden';
		panelEl.style.opacity    = '0';
		panelEl.style.display    = 'block';

		var panelRect  = panelEl.getBoundingClientRect();
		var margin     = 12; // min gap from viewport edge, px
		var safeRight  = window.innerWidth - margin;
		var navActions = document.querySelector( '.primary-nav__actions' );
		if ( navActions && navActions.getBoundingClientRect().width > 0 ) {
			safeRight = Math.min( safeRight, navActions.getBoundingClientRect().left - margin );
		}
		var overflow   = 0;

		if ( panelRect.right > safeRight ) {
			overflow = -( panelRect.right - safeRight );
		} else if ( panelRect.left < margin ) {
			overflow = margin - panelRect.left;
		}

		// Reset temporary styles
		panelEl.style.display    = '';
		panelEl.style.visibility = prevVisibility;
		panelEl.style.opacity    = prevOpacity;

		panelEl.style.setProperty( '--_panel-shift', overflow + 'px' );
	}

	// Run clamping on all desktop dropdown parents
	if ( desktopQuery.matches ) {
		document.querySelectorAll( '.menu-item-has-children' ).forEach( function ( parentItem ) {
			var subMenu = parentItem.querySelector( ':scope > .sub-menu' );
			if ( subMenu ) {
				parentItem.addEventListener( 'mouseenter', function () {
					clampDropdown( parentItem, subMenu );
				} );
				parentItem.addEventListener( 'focusin', function () {
					clampDropdown( parentItem, subMenu );
				} );
			}
		} );
	}

	/**
	 * ─── MEGA MENU ENHANCEMENT ───────────────────────────────────────────────
	 * The panel already works with zero JavaScript via :hover/:focus-within
	 * in CSS — this adds a disclosure button for touch users, explicit
	 * click/tap control, Escape, outside-click handling, and viewport
	 * clamping. Removing this script entirely still leaves a fully working
	 * (if less refined) menu.
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

		// Clamp mega-panel on desktop hover/focus
		if ( desktopQuery.matches ) {
			item.addEventListener( 'mouseenter', function () {
				clampDropdown( item, panel );
			} );
			item.addEventListener( 'focusin', function () {
				clampDropdown( item, panel );
			} );
		}

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
			clampDropdown( item, panel );
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
