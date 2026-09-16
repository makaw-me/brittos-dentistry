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

/**
 * Disable XML-RPC entirely. This is a brochure site with no mobile-app,
 * Jetpack, or remote-publishing integration relying on it, and the
 * endpoint is a common brute-force / pingback-amplification target on
 * shared hosting.
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Remove the X-Pingback header and pingback/trackback discovery link
 * that normally advertise the (now disabled) XML-RPC endpoint.
 *
 * @param array $headers Response headers.
 * @return array
 */
function brittos_remove_pingback_header( $headers ) {
	unset( $headers['X-Pingback'] );
	return $headers;
}
add_filter( 'wp_headers', 'brittos_remove_pingback_header' );
remove_action( 'wp_head', 'rsd_link' );

/**
 * Prevent self-pingbacks so a post that links to another post on the
 * same site doesn't generate a spurious pingback comment.
 *
 * @param array $links List of URLs to be pinged.
 */
function brittos_disable_self_pingbacks( &$links ) {
	$home = home_url( '/' );
	foreach ( $links as $index => $link ) {
		if ( 0 === strpos( $link, $home ) ) {
			unset( $links[ $index ] );
		}
	}
}
add_action( 'pre_ping', 'brittos_disable_self_pingbacks' );

/**
 * Prevent username enumeration via ?author=<id> query strings — a
 * common reconnaissance step before a brute-force login attempt.
 * Logged-in users (who already know usernames) are unaffected.
 */
function brittos_block_author_enumeration() {
	if ( is_admin() || is_user_logged_in() ) {
		return;
	}
	if ( isset( $_GET['author'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		wp_safe_redirect( home_url( '/' ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'brittos_block_author_enumeration' );

/**
 * Hide the public REST `users` endpoint from unauthenticated requests so
 * author usernames/slugs can't be harvested for brute-force targeting.
 * Logged-in requests (needed by wp-admin) are left untouched.
 *
 * @param WP_Error|null|bool $result Existing error/result, if any.
 * @return WP_Error|null|bool
 */
function brittos_restrict_rest_users_endpoint( $result ) {
	if ( ! empty( $result ) || is_user_logged_in() ) {
		return $result;
	}

	global $wp;
	$route = isset( $wp->query_vars['rest_route'] ) ? $wp->query_vars['rest_route'] : '';
	if ( $route && preg_match( '#^/wp/v2/users#', $route ) ) {
		return new WP_Error(
			'rest_forbidden',
			__( 'Sorry, you are not allowed to do that.', 'brittos-dentistry' ),
			array( 'status' => rest_authorization_required_code() )
		);
	}

	return $result;
}
add_filter( 'rest_authentication_errors', 'brittos_restrict_rest_users_endpoint' );
