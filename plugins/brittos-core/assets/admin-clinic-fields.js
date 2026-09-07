/**
 * Media + gallery picker for the Clinic Info settings screen.
 * Uses the core wp.media frame only — no extra dependencies.
 */
( function ( $ ) {
	'use strict';

	$( function () {
		// Single image field (dentist photo).
		$( '.brittos-media-field__select' ).on( 'click', function ( event ) {
			event.preventDefault();
			var wrapper = $( this ).closest( '.brittos-media-field' );
			var input = wrapper.find( '.brittos-media-field__id' );
			var preview = wrapper.find( '.brittos-media-field__preview' );
			var removeBtn = wrapper.find( '.brittos-media-field__remove' );

			var frame = wp.media( { title: 'Select Image', multiple: false } );
			frame.on( 'select', function () {
				var attachment = frame.state().get( 'selection' ).first().toJSON();
				input.val( attachment.id );
				preview.html( '<img src="' + attachment.url + '" style="max-width:150px;height:auto;">' );
				removeBtn.show();
			} );
			frame.open();
		} );

		$( '.brittos-media-field__remove' ).on( 'click', function ( event ) {
			event.preventDefault();
			var wrapper = $( this ).closest( '.brittos-media-field' );
			wrapper.find( '.brittos-media-field__id' ).val( '' );
			wrapper.find( '.brittos-media-field__preview' ).empty();
			$( this ).hide();
		} );

		// Multi-image gallery field.
		$( '.brittos-gallery-field__select' ).on( 'click', function ( event ) {
			event.preventDefault();
			var wrapper = $( this ).closest( '.brittos-gallery-field' );
			var input = wrapper.find( '.brittos-gallery-field__ids' );
			var preview = wrapper.find( '.brittos-gallery-field__preview' );

			var frame = wp.media( { title: 'Select Images', multiple: true } );
			frame.on( 'select', function () {
				var attachments = frame.state().get( 'selection' ).toJSON();
				var ids = [];
				preview.empty();
				attachments.forEach( function ( attachment ) {
					ids.push( attachment.id );
					preview.append( '<img src="' + attachment.url + '" style="max-width:100px;height:auto;margin:2px;">' );
				} );
				input.val( ids.join( ',' ) );
			} );
			frame.open();
		} );
	} );
} )( jQuery );
