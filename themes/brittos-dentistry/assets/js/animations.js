/**
 * Lightweight, dependency-free scroll-reveal for elements marked
 * `data-reveal`. Fades/lifts content into place the first time it
 * enters the viewport, then stops observing it (no repeat replay).
 *
 * Respects `prefers-reduced-motion`: reduced-motion visitors see
 * everything in its final state immediately, with no animation at all.
 */
( function () {
	'use strict';

	var elements = document.querySelectorAll( '[data-reveal]' );
	if ( ! elements.length ) {
		return;
	}

	var prefersReducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;

	if ( prefersReducedMotion || typeof window.IntersectionObserver !== 'function' ) {
		elements.forEach( function ( el ) {
			el.classList.add( 'is-visible' );
		} );
		return;
	}

	// Stagger siblings that share a reveal group so cards/rows step in
	// one after another rather than all firing at once.
	var groupCounters = {};

	elements.forEach( function ( el ) {
		var group = el.getAttribute( 'data-reveal-group' ) || '';
		if ( group ) {
			groupCounters[ group ] = groupCounters[ group ] || 0;
			var delay = Math.min( groupCounters[ group ] * 70, 420 );
			el.style.setProperty( '--reveal-delay', delay + 'ms' );
			groupCounters[ group ] += 1;
		}
	} );

	var observer = new IntersectionObserver(
		function ( entries, obs ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-visible' );
					obs.unobserve( entry.target );
				}
			} );
		},
		{ threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
	);

	elements.forEach( function ( el ) {
		observer.observe( el );
	} );
} )();
