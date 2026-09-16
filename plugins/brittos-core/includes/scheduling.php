<?php
/**
 * Working-hours-driven appointment availability: turns the structured
 * `working_hours` clinic field (see includes/fields/clinic-fields.php)
 * into bookable time slots, and validates a submitted date/time against
 * it. Used by both the appointment form renderer/validator and the
 * theme's client-side slot picker.
 *
 * When the clinic hasn't configured any working hours yet, every helper
 * here stays permissive (no date/time is rejected) so existing sites
 * behave exactly as before until the schedule is filled in.
 *
 * @package Brittos_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The raw sanitized working-hours schedule, keyed by ISO-8601 weekday
 * number (1 = Monday ... 7 = Sunday).
 *
 * @return array
 */
function brittos_core_get_working_hours() {
	$data = brittos_core_get_clinic_field( 'working_hours', array() );
	return is_array( $data ) ? $data : array();
}

/**
 * Whether the clinic has actually configured a schedule (at least one
 * day marked closed or given a time range). Used to decide whether to
 * enforce day/time restrictions at all.
 *
 * @return bool
 */
function brittos_core_working_hours_active() {
	foreach ( brittos_core_get_working_hours() as $day ) {
		if ( ! empty( $day['closed'] ) || ! empty( $day['ranges'] ) ) {
			return true;
		}
	}
	return false;
}

/**
 * The configured schedule for one ISO weekday.
 *
 * @param int $iso_weekday 1 (Monday) through 7 (Sunday).
 * @return array{closed: bool, ranges: array}
 */
function brittos_core_get_day_schedule( $iso_weekday ) {
	$hours   = brittos_core_get_working_hours();
	$day_key = (string) absint( $iso_weekday );
	$day     = isset( $hours[ $day_key ] ) && is_array( $hours[ $day_key ] ) ? $hours[ $day_key ] : array();

	return array(
		'closed' => ! empty( $day['closed'] ),
		'ranges' => isset( $day['ranges'] ) && is_array( $day['ranges'] ) ? $day['ranges'] : array(),
	);
}

/**
 * Configured slot length in minutes, defaulting to 30.
 *
 * @return int
 */
function brittos_core_get_slot_duration() {
	$duration = absint( brittos_core_get_clinic_field( 'appointment_slot_duration', 30 ) );
	return $duration > 0 ? $duration : 30;
}

/**
 * Convert a "HH:MM" string to minutes since midnight.
 *
 * @param string $time 24-hour "HH:MM" string.
 * @return int|false
 */
function brittos_core_time_to_minutes( $time ) {
	if ( ! is_string( $time ) || ! preg_match( '/^([01]\d|2[0-3]):([0-5]\d)$/', $time, $matches ) ) {
		return false;
	}
	return ( (int) $matches[1] ) * 60 + (int) $matches[2];
}

/**
 * Convert minutes since midnight back to a "HH:MM" string.
 *
 * @param int $minutes Minutes since midnight.
 * @return string
 */
function brittos_core_minutes_to_time( $minutes ) {
	return sprintf( '%02d:%02d', (int) floor( $minutes / 60 ), $minutes % 60 );
}

/**
 * Whether a given "Y-m-d" date is bookable: not in the past, and (when
 * a schedule is configured) not a day the clinic is closed.
 *
 * @param string $date "Y-m-d" date string.
 * @return bool
 */
function brittos_core_is_date_open( $date ) {
	$timestamp = strtotime( $date . ' 00:00:00' );
	if ( false === $timestamp ) {
		return false;
	}

	$today = strtotime( current_time( 'Y-m-d' ) . ' 00:00:00' );
	if ( $timestamp < $today ) {
		return false;
	}

	if ( ! brittos_core_working_hours_active() ) {
		return true;
	}

	$day_schedule = brittos_core_get_day_schedule( (int) gmdate( 'N', $timestamp ) );
	return ! $day_schedule['closed'] && ! empty( $day_schedule['ranges'] );
}

/**
 * Bookable "HH:MM" start times for one specific date, split across all
 * of that weekday's time ranges and, for today, excluding times that
 * have already passed.
 *
 * @param string $date "Y-m-d" date string.
 * @return string[]
 */
function brittos_core_generate_time_slots( $date ) {
	if ( ! brittos_core_working_hours_active() || ! brittos_core_is_date_open( $date ) ) {
		return array();
	}

	$timestamp    = strtotime( $date . ' 00:00:00' );
	$day_schedule = brittos_core_get_day_schedule( (int) gmdate( 'N', $timestamp ) );
	$duration     = brittos_core_get_slot_duration();

	$is_today    = ( $date === current_time( 'Y-m-d' ) );
	$now_minutes = $is_today ? ( (int) current_time( 'H' ) * 60 + (int) current_time( 'i' ) ) : -1;

	$slots = array();
	foreach ( $day_schedule['ranges'] as $range ) {
		$start = isset( $range['start'] ) ? brittos_core_time_to_minutes( $range['start'] ) : false;
		$end   = isset( $range['end'] ) ? brittos_core_time_to_minutes( $range['end'] ) : false;
		if ( false === $start || false === $end ) {
			continue;
		}
		for ( $t = $start; ( $t + $duration ) <= $end; $t += $duration ) {
			if ( $is_today && $t <= $now_minutes ) {
				continue;
			}
			$slots[] = brittos_core_minutes_to_time( $t );
		}
	}

	return $slots;
}

/**
 * Every possible "HH:MM" slot across the whole configured week,
 * deduplicated and sorted. Used as the no-JavaScript baseline option
 * list for the appointment form's time field (see
 * includes/forms/appointment.php) before it's narrowed down to the
 * exact selected date on the client.
 *
 * @return string[]
 */
function brittos_core_get_all_possible_slots() {
	$duration = brittos_core_get_slot_duration();
	$slots    = array();

	foreach ( brittos_core_get_working_hours() as $day ) {
		if ( empty( $day['ranges'] ) || ! empty( $day['closed'] ) ) {
			continue;
		}
		foreach ( $day['ranges'] as $range ) {
			$start = isset( $range['start'] ) ? brittos_core_time_to_minutes( $range['start'] ) : false;
			$end   = isset( $range['end'] ) ? brittos_core_time_to_minutes( $range['end'] ) : false;
			if ( false === $start || false === $end ) {
				continue;
			}
			for ( $t = $start; ( $t + $duration ) <= $end; $t += $duration ) {
				$slots[ $t ] = brittos_core_minutes_to_time( $t );
			}
		}
	}

	ksort( $slots );
	return array_values( $slots );
}

/**
 * Whether a specific date + time combination is actually bookable.
 * Authoritative server-side check backing the client-side picker.
 *
 * @param string $date "Y-m-d" date string.
 * @param string $time "HH:MM" time string.
 * @return bool
 */
function brittos_core_is_slot_available( $date, $time ) {
	if ( ! brittos_core_working_hours_active() ) {
		return true;
	}
	return in_array( $time, brittos_core_generate_time_slots( $date ), true );
}

/**
 * Data handed to the theme's client-side scheduler script so it can
 * generate/refresh time options as the visitor picks a date, without
 * an extra request. Only meaningful (and only enqueued) when a
 * schedule has actually been configured.
 *
 * @return array
 */
function brittos_core_get_appointment_schedule_for_js() {
	$days = array();
	foreach ( range( 1, 7 ) as $iso_weekday ) {
		$days[ (string) $iso_weekday ] = brittos_core_get_day_schedule( $iso_weekday );
	}

	return array(
		'active'   => brittos_core_working_hours_active(),
		'duration' => brittos_core_get_slot_duration(),
		'today'    => current_time( 'Y-m-d' ),
		'days'     => $days,
		'i18n'     => array(
			'selectDate' => __( 'Select a date first', 'brittos-core' ),
			'selectTime' => __( 'Select a time (optional)', 'brittos-core' ),
			'closed'     => __( 'Clinic closed on this day — please choose another date.', 'brittos-core' ),
			'noSlots'    => __( 'No remaining time slots for this day — please choose another date.', 'brittos-core' ),
		),
	);
}
