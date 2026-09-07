<?php
/**
 * Uninstall handler. Runs only when the plugin is deleted from
 * wp-admin (not on simple deactivation), and only removes data this
 * plugin itself created.
 *
 * @package Brittos_Core
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

/**
 * Delete the clinic settings option.
 */
delete_option( 'brittos_core_clinic' );
delete_option( 'brittos_core_flush_rewrites' );

/**
 * Remove custom post types' content (treatments, testimonials, FAQs)
 * along with their meta, since this data has no meaning outside this
 * plugin's context. Regular pages/posts are left untouched.
 */
$post_types = array( 'treatment', 'testimonial', 'faq' );

foreach ( $post_types as $post_type ) {
	$post_ids = get_posts( array(
		'post_type'      => $post_type,
		'post_status'    => 'any',
		'numberposts'    => -1,
		'fields'         => 'ids',
	) );

	foreach ( $post_ids as $post_id ) {
		wp_delete_post( $post_id, true );
	}
}

/**
 * Remove the custom taxonomy terms.
 */
$terms = get_terms( array(
	'taxonomy'   => 'treatment_category',
	'hide_empty' => false,
	'fields'     => 'ids',
) );

if ( ! is_wp_error( $terms ) ) {
	foreach ( $terms as $term_id ) {
		wp_delete_term( $term_id, 'treatment_category' );
	}
}

flush_rewrite_rules();
