/**
 * Client-side refinement of the appointment time-slot <select>: narrows
 * the server-rendered full-week option list down to the exact slots
 * available for whichever date the visitor picks, and blocks past
 * times on the current day. Only enqueued when the clinic has actually
 * configured working hours (see brittos_core_get_appointment_schedule_for_js());
 * without it, the form keeps working exactly as a plain date/time input pair.
 *
 * If this script fails to load, the date/time fields still submit the
 * server-rendered full-week option list, and brittos-core validates the
 * chosen date/time server-side regardless.
 */
( function () {
	'use strict';

	var schedule = window.brittosAppointmentSchedule;
	if ( ! schedule || ! schedule.active ) {
		return;
	}

	var dateInput  = document.getElementById( 'brittos_preferred_date' );
	var timeSelect = document.getElementById( 'brittos_preferred_time' );
	var statusEl   = document.getElementById( 'brittos_preferred_time_status' );

	if ( ! dateInput || ! timeSelect || 'SELECT' !== timeSelect.tagName ) {
		return;
	}

	function pad( value ) {
		return value < 10 ? '0' + value : '' + value;
	}

	// Always derived from the visitor's own device clock — never the
	// server's "today", which can be a day off if WP's configured site
	// timezone doesn't match the visitor's, wrongly zeroing out a future
	// day's slots.
	function todayString() {
		var now = new Date();
		return now.getFullYear() + '-' + pad( now.getMonth() + 1 ) + '-' + pad( now.getDate() );
	}

	dateInput.setAttribute( 'min', todayString() );

	function toMinutes( hhmm ) {
		var parts = hhmm.split( ':' );
		return parseInt( parts[ 0 ], 10 ) * 60 + parseInt( parts[ 1 ], 10 );
	}

	function toHHMM( minutes ) {
		return pad( Math.floor( minutes / 60 ) ) + ':' + pad( minutes % 60 );
	}

	// Local calendar weekday for a "YYYY-MM-DD" string, mapped to ISO-8601
	// (1 = Monday ... 7 = Sunday) to match the PHP-side schedule keys.
	function isoWeekday( dateString ) {
		var parts = dateString.split( '-' );
		var date  = new Date( parseInt( parts[ 0 ], 10 ), parseInt( parts[ 1 ], 10 ) - 1, parseInt( parts[ 2 ], 10 ) );
		var day   = date.getDay();
		return 0 === day ? 7 : day;
	}

	function setStatus( message ) {
		if ( statusEl ) {
			statusEl.textContent = message || '';
		}
	}

	function setOptions( options ) {
		timeSelect.innerHTML = '';
		options.forEach( function ( option ) {
			var el = document.createElement( 'option' );
			el.value = option.value;
			el.textContent = option.label;
			timeSelect.appendChild( el );
		} );
	}

	function refresh() {
		setStatus( '' );

		var dateValue = dateInput.value;
		if ( ! dateValue ) {
			timeSelect.disabled = true;
			setOptions( [ { value: '', label: schedule.i18n.selectDate } ] );
			return;
		}

		var day = schedule.days[ String( isoWeekday( dateValue ) ) ];
		if ( ! day || day.closed || ! day.ranges || ! day.ranges.length ) {
			timeSelect.disabled = true;
			setOptions( [ { value: '', label: schedule.i18n.closed } ] );
			setStatus( schedule.i18n.closed );
			return;
		}

		var isToday = ( dateValue === todayString() );
		var now = new Date();
		var nowMinutes = isToday ? ( now.getHours() * 60 + now.getMinutes() ) : -1;

		var slots = [];
		day.ranges.forEach( function ( range ) {
			var start = toMinutes( range.start );
			var end = toMinutes( range.end );
			for ( var t = start; ( t + schedule.duration ) <= end; t += schedule.duration ) {
				if ( isToday && t <= nowMinutes ) {
					continue;
				}
				slots.push( t );
			}
		} );

		if ( ! slots.length ) {
			timeSelect.disabled = true;
			setOptions( [ { value: '', label: schedule.i18n.noSlots } ] );
			setStatus( schedule.i18n.noSlots );
			return;
		}

		timeSelect.disabled = false;
		setOptions( [ { value: '', label: schedule.i18n.selectTime } ].concat(
			slots.map( function ( minutes ) {
				var value = toHHMM( minutes );
				return { value: value, label: value };
			} )
		) );
	}

	dateInput.addEventListener( 'change', refresh );
	refresh();
} )();
