<?php
/**
 * Baseline security hardening and WP head cleanup.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove version number and generic identifiers that make fingerprinting easier.
 */
remove_action( 'wp_head', 'wp_generator' );
add_filter( 'the_generator', '__return_empty_string' );

/**
 * Disable the file editor in wp-admin for defense in depth.
 * (Can also be set via DISALLOW_FILE_EDIT in wp-config.php.)
 */
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
	define( 'DISALLOW_FILE_EDIT', true );
}

/**
 * Remove RSD, wlwmanifest and shortlink noise from <head>.
 */
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

/**
 * Send a conservative set of security-related response headers.
 * These are safe defaults for a brochure/marketing site; adjust
 * Content-Security-Policy if third-party embeds are added later.
 */
function brittos_security_headers() {
	if ( is_admin() ) {
		return;
	}
	header( 'X-Content-Type-Options: nosniff' );
	header( 'Referrer-Policy: strict-origin-when-cross-origin' );
	header( 'X-Frame-Options: SAMEORIGIN' );
	header( 'Permissions-Policy: geolocation=(), microphone=(), camera=()' );
}
add_action( 'send_headers', 'brittos_security_headers' );

/**
 * Never trust raw REQUEST superglobals directly in templates. Small
 * escaping helper used by template parts instead of touching $_GET/$_POST.
 *
 * @param string $key     Query var name.
 * @param string $default Fallback value.
 * @return string
 */
function brittos_get_query_var( $key, $default = '' ) {
	$value = isset( $_GET[ $key ] ) ? wp_unslash( $_GET[ $key ] ) : $default; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	return sanitize_text_field( $value );
}
