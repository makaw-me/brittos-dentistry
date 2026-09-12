/**
 * Progressive FAQ accordion enhancement. Native details remains the source
 * of truth; this only closes sibling answers for a calmer reading flow.
 */
( function () {
	'use strict';

	document.querySelectorAll( '.faq__list' ).forEach( function ( list ) {
		list.addEventListener( 'toggle', function ( event ) {
			if ( ! event.target.matches( '.faq-item__details[open]' ) ) {
				return;
			}

			list.querySelectorAll( '.faq-item__details[open]' ).forEach( function ( detail ) {
				if ( detail !== event.target ) {
					detail.removeAttribute( 'open' );
				}
			} );
		}, true );
	} );
} )();