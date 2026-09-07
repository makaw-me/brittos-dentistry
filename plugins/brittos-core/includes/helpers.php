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
