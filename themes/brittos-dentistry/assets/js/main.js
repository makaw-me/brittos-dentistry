/**
 * Lightweight progressive enhancement for the appointment enquiry form.
 * The form works perfectly as a normal POST without this script (see
 * brittos-core/includes/forms/appointment.php); when JS is available we
 * submit via fetch so the visitor gets an inline, accessible result
 * instead of a full page reload.
 */
( function () {
	'use strict';

	var form = document.querySelector( '.appointment-form' );
	if ( ! form ) {
		return;
	}

	var responseRegion = document.getElementById( 'appointment-form-response' );

	function setNotice( message, type ) {
		if ( ! responseRegion ) {
			return;
		}
		responseRegion.textContent = message;
		responseRegion.className = 'appointment-form__notice appointment-form__notice--' + type;
		responseRegion.hidden = false;
	}

	var isEnhanced = true;

	function handleSubmit( event ) {
		// Only intercept when the browser supports fetch/FormData, and only
		// while the enhancement hasn't already stepped aside after a
		// network failure; otherwise let the normal form POST proceed.
		if ( ! isEnhanced || typeof window.fetch !== 'function' || typeof window.FormData !== 'function' ) {
			return;
		}

		event.preventDefault();

		var submitButton = form.querySelector( '[type="submit"]' );
		if ( submitButton ) {
			submitButton.disabled = true;
		}

		var formData = new FormData( form );

		fetch( form.getAttribute( 'action' ), {
			method: 'POST',
			credentials: 'same-origin',
			headers: { 'X-Requested-With': 'XMLHttpRequest' },
			body: formData,
		} )
			.then( function ( response ) {
				return response.json().catch( function () {
					return { success: false, data: { message: '' } };
				} );
			} )
			.then( function ( result ) {
				var message = ( result && result.data && result.data.message ) || '';

				if ( result && result.success ) {
					setNotice( message || 'Thank you — the clinic will be in touch shortly.', 'success' );
					form.reset();
				} else {
					setNotice( message || 'Something went wrong. Please check the form and try again.', 'error' );
				}
			} )
			.catch( function () {
				// Network or server failure: step aside and fall back to a
				// plain submit so the enquiry is never silently lost.
				isEnhanced = false;
				if ( submitButton ) {
					submitButton.disabled = false;
				}
				form.submit();
			} )
			.then( function () {
				if ( submitButton ) {
					submitButton.disabled = false;
				}
			} );
	}

	form.addEventListener( 'submit', handleSubmit );
} )();
