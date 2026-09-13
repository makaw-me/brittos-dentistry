/**
 * Accessible, non-autoplaying before/after carousel.
 */
( function () {
	'use strict';

	var carousels = document.querySelectorAll( '[data-before-after-carousel]' );

	carousels.forEach( function ( carousel ) {
		var slides = Array.prototype.slice.call( carousel.querySelectorAll( '[data-before-after-slide]' ) );
		var dots = Array.prototype.slice.call( carousel.querySelectorAll( '[data-before-after-dot]' ) );
		var previousButtons = carousel.querySelectorAll( '[data-before-after-previous]' );
		var nextButtons = carousel.querySelectorAll( '[data-before-after-next]' );
		var status = carousel.querySelector( '[data-before-after-status]' );
		var slidesWrap = carousel.querySelector( '[data-before-after-slides]' );
		var current = 0;

		if ( slides.length < 2 ) {
			previousButtons.forEach( function ( button ) { button.hidden = true; } );
			nextButtons.forEach( function ( button ) { button.hidden = true; } );
			if ( dots[ 0 ] ) dots[ 0 ].hidden = true;
			return;
		}

		slidesWrap.classList.add( 'is-enhanced' );

		function show( index, moveFocus ) {
			current = ( index + slides.length ) % slides.length;
			slides.forEach( function ( slide, slideIndex ) {
				var active = slideIndex === current;
				slide.hidden = ! active;
				slide.setAttribute( 'aria-hidden', active ? 'false' : 'true' );
			} );
			dots.forEach( function ( dot, dotIndex ) {
				var active = dotIndex === current;
				dot.setAttribute( 'aria-pressed', active ? 'true' : 'false' );
				dot.tabIndex = active ? 0 : -1;
			} );
			if ( status ) {
				status.textContent = ( current + 1 ) + ' / ' + slides.length;
			}
			if ( moveFocus && dots[ current ] ) {
				dots[ current ].focus();
			}
		}

		previousButtons.forEach( function ( button ) {
			button.addEventListener( 'click', function () { show( current - 1, false ); } );
		} );
		nextButtons.forEach( function ( button ) {
			button.addEventListener( 'click', function () { show( current + 1, false ); } );
		} );
		dots.forEach( function ( dot ) {
			dot.addEventListener( 'click', function () { show( Number( dot.dataset.beforeAfterDot ), false ); } );
		} );
		carousel.addEventListener( 'keydown', function ( event ) {
			if ( 'ArrowLeft' === event.key ) {
				event.preventDefault();
				show( current - 1, true );
			} else if ( 'ArrowRight' === event.key ) {
				event.preventDefault();
				show( current + 1, true );
			} else if ( 'Home' === event.key ) {
				event.preventDefault();
				show( 0, true );
			} else if ( 'End' === event.key ) {
				event.preventDefault();
				show( slides.length - 1, true );
			}
		} );

		show( 0, false );
	} );
} )();