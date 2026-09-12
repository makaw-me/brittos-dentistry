<?php
/**
 * Shared utility functions for the plugin.
 *
 * @package Brittos_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Consistent capability check for the plugin's admin-only actions.
 *
 * @return bool
 */
function brittos_core_current_user_can_manage() {
	return current_user_can( 'manage_options' );
}

/**
 * Sanitize a simple multi-line textarea into an array of trimmed,
 * non-empty lines. Used for benefits / process steps stored as text.
 *
 * @param string $raw Raw textarea value.
 * @return array
 */
function brittos_core_lines_to_array( $raw ) {
	$raw   = (string) $raw;
	$lines = preg_split( '/\r\n|\r|\n/', $raw );
	$lines = array_map( 'trim', $lines );
	$lines = array_map( 'sanitize_text_field', $lines );
	return array_values( array_filter( $lines, 'strlen' ) );
}

/**
 * Render an array of lines back into a textarea-friendly string.
 *
 * @param array $lines Array of strings.
 * @return string
 */
function brittos_core_array_to_lines( $lines ) {
	if ( ! is_array( $lines ) ) {
		return '';
	}
	return implode( "\n", array_map( 'sanitize_text_field', $lines ) );
}

/**
 * Sanitize a "Label: Value" per-line textarea into an array of
 * `[ 'label' => ..., 'value' => ... ]` pairs. Used for the treatment
 * "Quick Facts" strip (visits required, session duration, recovery
 * time, etc.) without needing a bespoke repeater UI.
 *
 * @param string $raw Raw textarea value.
 * @return array
 */
function brittos_core_facts_lines_to_array( $raw ) {
	$lines = brittos_core_lines_to_array( $raw );
	$facts = array();

	foreach ( $lines as $line ) {
		$parts = explode( ':', $line, 2 );
		if ( 2 !== count( $parts ) ) {
			continue;
		}
		$label = trim( $parts[0] );
		$value = trim( $parts[1] );
		if ( '' === $label || '' === $value ) {
			continue;
		}
		$facts[] = array(
			'label' => sanitize_text_field( $label ),
			'value' => sanitize_text_field( $value ),
		);
	}

	return $facts;
}

/**
 * Render an array of fact pairs back into "Label: Value" lines for the
 * textarea.
 *
 * @param array $facts Array of [ 'label' => ..., 'value' => ... ].
 * @return string
 */
function brittos_core_facts_array_to_lines( $facts ) {
	if ( ! is_array( $facts ) ) {
		return '';
	}
	$lines = array();
	foreach ( $facts as $fact ) {
		if ( empty( $fact['label'] ) || ! isset( $fact['value'] ) || '' === $fact['value'] ) {
			continue;
		}
		$lines[] = sanitize_text_field( $fact['label'] ) . ': ' . sanitize_text_field( $fact['value'] );
	}
	return implode( "\n", $lines );
}

/**
 * Validate and sanitize a before/after gallery payload posted as JSON
 * from the admin repeater UI (assets/admin-treatment-fields.js).
 *
 * @param string $raw_json Raw JSON string from the hidden field.
 * @return array Array of [ 'before' => int, 'after' => int, 'caption' => string ].
 */
function brittos_core_sanitize_before_after_json( $raw_json ) {
	$decoded = json_decode( (string) $raw_json, true );
	if ( ! is_array( $decoded ) ) {
		return array();
	}

	$clean = array();
	foreach ( $decoded as $pair ) {
		if ( ! is_array( $pair ) ) {
			continue;
		}
		$before = isset( $pair['before'] ) ? absint( $pair['before'] ) : 0;
		$after  = isset( $pair['after'] ) ? absint( $pair['after'] ) : 0;

		// Require both images, and that they're genuinely images, before storing the pair.
		if ( ! $before || ! $after || ! wp_attachment_is_image( $before ) || ! wp_attachment_is_image( $after ) ) {
			continue;
		}

		$clean[] = array(
			'before'  => $before,
			'after'   => $after,
			'caption' => isset( $pair['caption'] ) ? sanitize_text_field( $pair['caption'] ) : '',
		);
	}

	return $clean;
}

/**
 * FAQs related to a given treatment, via the repeating
 * `brittos_faq_related_treatment_id` meta row stored on each FAQ (see
 * includes/fields/faq-fields.php). Ownership of the relationship lives
 * on the FAQ side, so one FAQ can serve multiple treatments and a
 * content editor manages it from a single screen.
 *
 * @param int $treatment_id Treatment post ID.
 * @param int $limit        Max FAQs to return.
 * @return WP_Query
 */
function brittos_core_get_related_faqs_for_treatment( $treatment_id, $limit = 10 ) {
	return new WP_Query( array(
		'post_type'      => 'faq',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
		'meta_query'     => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
			array(
				'key'   => 'brittos_faq_related_treatment_id',
				'value' => absint( $treatment_id ),
			),
		),
	) );
}

/**
 * Other treatments sharing at least one treatment_category term with the
 * given treatment, for the "Related Treatments" section. Falls back to
 * recent treatments (excluding the current one) if there's no shared
 * category, so the section never renders empty-handed on a small catalog.
 *
 * @param int $treatment_id Current treatment post ID.
 * @param int $limit        Max treatments to return.
 * @return WP_Query
 */
function brittos_core_get_related_treatments( $treatment_id, $limit = 3 ) {
	$terms = wp_get_post_terms( $treatment_id, 'treatment_category', array( 'fields' => 'ids' ) );

	$args = array(
		'post_type'      => 'treatment',
		'post_status'    => 'publish',
		'posts_per_page' => $limit,
		'post__not_in'   => array( $treatment_id ),
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'no_found_rows'  => true,
	);

	if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
		$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => 'treatment_category',
				'field'    => 'term_id',
				'terms'    => $terms,
			),
		);
	}

	$query = new WP_Query( $args );

	// No shared-category matches: fall back to any other published treatments.
	if ( ! $query->have_posts() && ! empty( $args['tax_query'] ) ) {
		unset( $args['tax_query'] );
		$query = new WP_Query( $args );
	}

	return $query;
}
