<?php
/**
 * Performance-related trims: fewer requests, no emoji script, no oEmbed
 * discovery bloat, and helpers for responsible image loading.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Disable the emoji script/styles — not needed for this site and it
 * costs an extra request + inline CSS on every page load.
 */
function brittos_disable_emojis() {
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );
	remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
	remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'brittos_disable_emojis' );

/**
 * Trim oEmbed discovery links/JS — this is a brochure site, not embedding
 * remote content, and it isn't embedded elsewhere.
 */
remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
remove_action( 'wp_head', 'wp_oembed_add_host_js' );

/**
 * Remove default block library CSS duplication when a block isn't used;
 * keep it otherwise since the editor may add core blocks to page content.
 * (No-op filter placeholder kept intentionally minimal — avoid stripping
 * styles that core content relies on.)
 */

/**
 * Add async/defer where relevant for any future third-party scripts
 * registered with a `brittos-defer` data flag, without breaking scripts
 * that don't opt in.
 *
 * @param string $tag    Script tag markup.
 * @param string $handle Script handle.
 * @return string
 */
function brittos_filter_script_tag( $tag, $handle ) {
	$defer_handles = array( 'brittos-main', 'brittos-navigation' );
	if ( in_array( $handle, $defer_handles, true ) && false === strpos( $tag, 'defer' ) ) {
		$tag = str_replace( ' src', ' defer src', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'brittos_filter_script_tag', 10, 2 );

/**
 * Ensure below-the-fold content images lazy-load via the native
 * `loading="lazy"` attribute (WordPress core already applies this from
 * 5.5+; this filter simply guarantees it stays on for post content).
 */
add_filter( 'wp_lazy_loading_enabled', '__return_true' );

/**
 * Helper: render an <img> for the hero/LCP image with fetchpriority=high
 * and no lazy-loading, sized to avoid layout shift.
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $size          Registered image size.
 * @param array  $attr          Extra attributes to merge.
 * @return string
 */
function brittos_lcp_image( $attachment_id, $size = 'brittos-hero', $attr = array() ) {
	if ( ! $attachment_id ) {
		return '';
	}
	$attr = wp_parse_args( $attr, array(
		'loading'       => false, // Explicitly not lazy — this is the LCP candidate.
		'fetchpriority' => 'high',
		'decoding'      => 'async',
	) );
	return wp_get_attachment_image( $attachment_id, $size, false, $attr );
}
