/**
 * Before/after gallery repeater for the Treatment edit screen.
 * Keeps a single hidden JSON field in sync with a set of rendered rows,
 * each with its own "Before" and "After" wp.media pickers and a caption.
 */
( function ( $ ) {
	'use strict';

	$( function () {
		var field = $( '.brittos-before-after-field' );
		if ( ! field.length ) {
			return;
		}

		var hiddenInput = field.find( '#brittos_treatment_before_after' );
		var rowsWrap = field.find( '.brittos-before-after-field__rows' );
		var addButton = field.find( '.brittos-before-after-field__add' );

		var pairs = [];
		try {
			pairs = JSON.parse( hiddenInput.val() || '[]' );
			if ( ! Array.isArray( pairs ) ) {
				pairs = [];
			}
		} catch ( e ) {
			pairs = [];
		}

		function sync() {
			hiddenInput.val( JSON.stringify( pairs ) );
		}

		function pickerMarkup( label, id ) {
			return '<div class="brittos-before-after-field__picker">' +
				'<p class="description">' + label + '</p>' +
				'<div class="brittos-before-after-field__preview" data-role="preview"></div>' +
				'<button type="button" class="button" data-role="select">Select Image</button>' +
				'</div>';
		}

		function renderRow( pair, index ) {
			var row = $( '<div class="brittos-before-after-field__row"></div>' );
			row.attr( 'data-index', index );

			var beforeCol = $( pickerMarkup( 'Before', 'before-' + index ) );
			var afterCol = $( pickerMarkup( 'After', 'after-' + index ) );

			if ( pair.before ) {
				beforeCol.find( '[data-role="preview"]' ).html( '<img src="' + pair.beforeUrl + '" style="max-width:100px;height:auto;">' );
			}
			if ( pair.after ) {
				afterCol.find( '[data-role="preview"]' ).html( '<img src="' + pair.afterUrl + '" style="max-width:100px;height:auto;">' );
			}

			beforeCol.find( '[data-role="select"]' ).on( 'click', function ( event ) {
				event.preventDefault();
				openPicker( function ( attachment ) {
					pairs[ index ].before = attachment.id;
					pairs[ index ].beforeUrl = attachment.url;
					beforeCol.find( '[data-role="preview"]' ).html( '<img src="' + attachment.url + '" style="max-width:100px;height:auto;">' );
					sync();
				} );
			} );

			afterCol.find( '[data-role="select"]' ).on( 'click', function ( event ) {
				event.preventDefault();
				openPicker( function ( attachment ) {
					pairs[ index ].after = attachment.id;
					pairs[ index ].afterUrl = attachment.url;
					afterCol.find( '[data-role="preview"]' ).html( '<img src="' + attachment.url + '" style="max-width:100px;height:auto;">' );
					sync();
				} );
			} );

			var captionInput = $( '<input type="text" placeholder="Caption (optional)" style="width:100%;margin-top:6px;">' );
			captionInput.val( pair.caption || '' );
			captionInput.on( 'input', function () {
				pairs[ index ].caption = $( this ).val();
				sync();
			} );

			var removeButton = $( '<button type="button" class="button-link-delete" style="margin-top:6px;">Remove pair</button>' );
			removeButton.on( 'click', function ( event ) {
				event.preventDefault();
				pairs.splice( index, 1 );
				sync();
				renderAll();
			} );

			var pickers = $( '<div style="display:flex;gap:12px;"></div>' ).append( beforeCol, afterCol );
			row.append( pickers, captionInput, removeButton );
			row.css( { border: '1px solid #dcdcde', padding: '10px', marginBottom: '10px', borderRadius: '4px' } );

			return row;
		}

		function openPicker( onSelect ) {
			var frame = wp.media( { title: 'Select Image', multiple: false } );
			frame.on( 'select', function () {
				var attachment = frame.state().get( 'selection' ).first().toJSON();
				onSelect( attachment );
			} );
			frame.open();
		}

		function renderAll() {
			rowsWrap.empty();
			pairs.forEach( function ( pair, index ) {
				rowsWrap.append( renderRow( pair, index ) );
			} );
		}

		addButton.on( 'click', function ( event ) {
			event.preventDefault();
			pairs.push( { before: 0, after: 0, caption: '' } );
			sync();
			renderAll();
		} );

		renderAll();
	} );
} )( jQuery );
