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
 * Ensure below-the-fold content images lazy-load via the native
 * `loading="lazy"` attribute (WordPress core already applies this from
 * 5.5+; this filter simply guarantees it stays on for post content).
 */
add_filter( 'wp_lazy_loading_enabled', '__return_true' );

/**
 * Add efficient defaults to attachment images without overriding component
 * decisions such as eager loading and high fetch priority for hero media.
 *
 * @param array  $attr       Image attributes.
 * @param object $attachment Attachment object.
 * @param string $size       Requested image size.
 * @return array
 */
function brittos_attachment_image_attributes( $attr, $attachment, $size ) {
	if ( empty( $attr['decoding'] ) ) {
		$attr['decoding'] = 'async';
	}

	if ( ! isset( $attr['loading'] ) && empty( $attr['fetchpriority'] ) ) {
		$attr['loading'] = 'lazy';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'brittos_attachment_image_attributes', 10, 3 );

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

// ---------------------------------------------------------------------------
// WebP sub-size generation
// ---------------------------------------------------------------------------

/**
 * Prefer Imagick over GD where both are available — Imagick produces
 * significantly better WebP quality and respects ICC colour profiles.
 * Falls back gracefully to GD if Imagick is absent.
 *
 * @param string[] $editors Ordered list of editor class names.
 * @return string[]
 */
function brittos_prefer_imagick( $editors ) {
	// Move WP_Image_Editor_Imagick to the front if present.
	$imagick_key = array_search( 'WP_Image_Editor_Imagick', $editors, true );
	if ( false !== $imagick_key && $imagick_key > 0 ) {
		unset( $editors[ $imagick_key ] );
		array_unshift( $editors, 'WP_Image_Editor_Imagick' );
		$editors = array_values( $editors );
	}
	return $editors;
}
add_filter( 'wp_image_editors', 'brittos_prefer_imagick' );

/**
 * Convert JPEG and PNG sub-sizes to WebP during upload, but only when the
 * active image editor actually supports the WebP MIME type. Falls back
 * silently to the original format so uploads never fail on hosts that lack
 * WebP encode support (e.g. GD without libwebp).
 *
 * This filter runs exclusively during sub-size generation (Media Library
 * upload pipeline). It does not touch existing attachments, does not change
 * the original full-size file, and does not alter any attachment metadata
 * already stored in the database.
 *
 * To generate WebP sub-sizes for existing uploads, run Regenerate Thumbnails
 * (or WP-CLI: `wp media regenerate`) — this is an optional admin action.
 *
 * @param array  $mappings  Current source-MIME → output-MIME map.
 * @param string $filename  Full path to the image being processed.
 * @param string $mime_type MIME type of the source image.
 * @return array
 */
function brittos_webp_subsizes( $mappings, $filename, $mime_type ) {
	// Only apply to the source MIME types we want to convert.
	if ( ! in_array( $mime_type, array( 'image/jpeg', 'image/png' ), true ) ) {
		return $mappings;
	}

	// Check runtime WebP support in the available WordPress image editors.
	$webp_supported = false;
	foreach ( array( 'WP_Image_Editor_Imagick', 'WP_Image_Editor_GD' ) as $editor_class ) {
		if (
			class_exists( $editor_class ) &&
			is_callable( array( $editor_class, 'supports_mime_type' ) ) &&
			$editor_class::supports_mime_type( 'image/webp' )
		) {
			$webp_supported = true;
			break;
		}
	}

	if ( ! $webp_supported ) {
		return $mappings; // Graceful no-op on hosts without WebP encode.
	}

	$mappings['image/jpeg'] = 'image/webp';
	$mappings['image/png']  = 'image/webp';

	return $mappings;
}
add_filter( 'wp_image_editor_output_format', 'brittos_webp_subsizes', 10, 3 );

/**
 * Set a sensible JPEG/WebP quality (82) for sub-sizes. WordPress defaults
 * to 82 for JPEG and 80 for WebP; making this explicit ensures the value
 * is not accidentally changed by other plugins.
 *
 * @param int    $quality   Current quality (0-100).
 * @param string $mime_type MIME type of the image being saved.
 * @return int
 */
function brittos_image_quality( $quality, $mime_type ) {
	if ( 'image/webp' === $mime_type || 'image/jpeg' === $mime_type ) {
		return 82;
	}
	return $quality;
}
add_filter( 'wp_editor_set_quality', 'brittos_image_quality', 10, 2 );
