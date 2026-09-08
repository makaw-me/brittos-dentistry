/**
 * Tab switching & Media/Video/Gallery picker for the Clinic Info settings screen.
 * Uses native WordPress admin scripts and wp.media frames.
 */
( function ( $ ) {
	'use strict';

	$( function () {
		// Tab Switching
		$( '.brittos-tab-trigger' ).on( 'click', function ( event ) {
			event.preventDefault();
			var targetTab = $( this ).data( 'tab' );

			$( '.brittos-tab-trigger' ).removeClass( 'nav-tab-active' );
			$( this ).addClass( 'nav-tab-active' );

			$( '.brittos-tab-panel' ).hide().removeClass( 'is-active' );
			$( '#tab-' + targetTab ).show().addClass( 'is-active' );

			// Update URL hash without jumping
			if ( window.history && window.history.replaceState ) {
				window.history.replaceState( null, null, '#tab-' + targetTab );
			}
		} );

		// Restore tab from URL hash if available
		var initialHash = window.location.hash;
		if ( initialHash && initialHash.indexOf( '#tab-' ) === 0 ) {
			var tabName = initialHash.replace( '#tab-', '' );
			var trigger = $( '.brittos-tab-trigger[data-tab="' + tabName + '"]' );
			if ( trigger.length ) {
				trigger.trigger( 'click' );
			}
		}

		// Single image picker (dentist photo / hero background image)
		$( document ).on( 'click', '.brittos-media-field__select', function ( event ) {
			event.preventDefault();
			var wrapper = $( this ).closest( '.brittos-media-field' );
			var input = wrapper.find( '.brittos-media-field__id' );
			var preview = wrapper.find( '.brittos-media-field__preview' );
			var removeBtn = wrapper.find( '.brittos-media-field__remove' );

			var frame = wp.media( {
				title: 'Select Image',
				library: { type: 'image' },
				multiple: false,
				button: { text: 'Use this Image' }
			} );

			frame.on( 'select', function () {
				var attachment = frame.state().get( 'selection' ).first().toJSON();
				input.val( attachment.id );
				preview.html( '<img src="' + attachment.url + '" style="max-width:180px;height:auto;border-radius:4px;border:1px solid #ccc;">' );
				removeBtn.show();
			} );
			frame.open();
		} );

		// Single image removal
		$( document ).on( 'click', '.brittos-media-field__remove', function ( event ) {
			event.preventDefault();
			var wrapper = $( this ).closest( '.brittos-media-field' );
			wrapper.find( '.brittos-media-field__id' ).val( '' );
			wrapper.find( '.brittos-media-field__preview' ).empty();
			$( this ).hide();
		} );

		// Video picker
		$( document ).on( 'click', '.brittos-video-field__select', function ( event ) {
			event.preventDefault();
			var wrapper = $( this ).closest( '.brittos-video-field' );
			var input = wrapper.find( '.brittos-video-field__url' );

			var frame = wp.media( {
				title: 'Select Video',
				library: { type: 'video' },
				multiple: false,
				button: { text: 'Use this Video' }
			} );

			frame.on( 'select', function () {
				var attachment = frame.state().get( 'selection' ).first().toJSON();
				input.val( attachment.url );
			} );
			frame.open();
		} );

		// Multi-image gallery picker
		$( document ).on( 'click', '.brittos-gallery-field__select', function ( event ) {
			event.preventDefault();
			var wrapper = $( this ).closest( '.brittos-gallery-field' );
			var input = wrapper.find( '.brittos-gallery-field__ids' );
			var preview = wrapper.find( '.brittos-gallery-field__preview' );
			var clearBtn = wrapper.find( '.brittos-gallery-field__clear' );

			var frame = wp.media( {
				title: 'Select Gallery Images',
				library: { type: 'image' },
				multiple: true,
				button: { text: 'Add to Gallery' }
			} );

			frame.on( 'select', function () {
				var attachments = frame.state().get( 'selection' ).toJSON();
				var ids = [];
				preview.empty();
				attachments.forEach( function ( attachment ) {
					ids.push( attachment.id );
					preview.append( '<img src="' + attachment.url + '" style="max-width:80px;height:auto;margin:3px;border-radius:4px;border:1px solid #ccc;">' );
				} );
				input.val( ids.join( ',' ) );
				if ( ids.length ) {
					clearBtn.show();
				}
			} );
			frame.open();
		} );

		// Clear gallery
		$( document ).on( 'click', '.brittos-gallery-field__clear', function ( event ) {
			event.preventDefault();
			var wrapper = $( this ).closest( '.brittos-gallery-field' );
			wrapper.find( '.brittos-gallery-field__ids' ).val( '' );
			wrapper.find( '.brittos-gallery-field__preview' ).empty();
			$( this ).hide();
		} );
	} );
} )( jQuery );
