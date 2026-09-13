<?php
/**
 * `treatment` custom post type: the clinic's services (e.g. cleanings,
 * fillings, cosmetic work). Structured fields live in
 * includes/fields/treatment-fields.php.
 *
 * @package Brittos_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function brittos_core_register_treatment_cpt() {

	$labels = array(
		'name'                  => _x( 'Treatments', 'Post type general name', 'brittos-core' ),
		'singular_name'         => _x( 'Treatment', 'Post type singular name', 'brittos-core' ),
		'menu_name'             => _x( 'Treatments', 'Admin Menu text', 'brittos-core' ),
		'add_new'               => __( 'Add New Treatment', 'brittos-core' ),
		'add_new_item'          => __( 'Add New Treatment', 'brittos-core' ),
		'edit_item'             => __( 'Edit Treatment', 'brittos-core' ),
		'new_item'              => __( 'New Treatment', 'brittos-core' ),
		'view_item'             => __( 'View Treatment', 'brittos-core' ),
		'view_items'            => __( 'View Treatments', 'brittos-core' ),
		'search_items'          => __( 'Search Treatments', 'brittos-core' ),
		'not_found'             => __( 'No treatments found', 'brittos-core' ),
		'not_found_in_trash'    => __( 'No treatments found in Trash', 'brittos-core' ),
		'all_items'             => __( 'All Treatments', 'brittos-core' ),
		'archives'              => __( 'Treatments', 'brittos-core' ),
		'featured_image'        => __( 'Treatment Image', 'brittos-core' ),
		'set_featured_image'    => __( 'Set treatment image', 'brittos-core' ),
		'remove_featured_image' => __( 'Remove treatment image', 'brittos-core' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'treatments', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => 20,
		'menu_icon'          => 'dashicons-heart',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes', 'custom-fields' ),
		'taxonomies'         => array( 'treatment_category' ),
	);

	register_post_type( 'treatment', $args );
}
add_action( 'init', 'brittos_core_register_treatment_cpt' );

/**
 * Determine whether a treatment should have its own public page.
 *
 * Missing values remain enabled so existing treatments keep their URLs until
 * an editor explicitly turns this option off.
 *
 * @param int $treatment_id Treatment post ID.
 * @return bool
 */
function brittos_core_treatment_has_single_page( $treatment_id ) {
	$value = get_post_meta( $treatment_id, 'brittos_treatment_single_page', true );

	return '' === $value || '1' === $value;
}

/**
 * Replace disabled treatment permalinks with the public treatment archive.
 *
 * @param string  $post_link The generated post permalink.
 * @param WP_Post $post      The post object.
 * @return string
 */
function brittos_core_filter_treatment_permalink( $post_link, $post ) {
	if ( 'treatment' !== $post->post_type || brittos_core_treatment_has_single_page( $post->ID ) ) {
		return $post_link;
	}

	$archive_link = get_post_type_archive_link( 'treatment' );
	return $archive_link ? $archive_link : home_url( '/' );
}
add_filter( 'post_type_link', 'brittos_core_filter_treatment_permalink', 10, 2 );

/**
 * Prevent direct requests from rendering a disabled treatment page.
 */
function brittos_core_redirect_disabled_treatment() {
	if ( ! is_singular( 'treatment' ) || brittos_core_treatment_has_single_page( get_queried_object_id() ) ) {
		return;
	}

	$archive_link = get_post_type_archive_link( 'treatment' );
	wp_safe_redirect( $archive_link ? $archive_link : home_url( '/' ), 301 );
	exit;
}
add_action( 'template_redirect', 'brittos_core_redirect_disabled_treatment' );

/**
 * Keep treatments without dedicated pages out of the WordPress post sitemap.
 * Missing legacy values remain included, matching the single-page fallback.
 *
 * @param array  $args      Sitemap query arguments.
 * @param string $post_type Post type being queried.
 * @return array
 */
function brittos_core_filter_treatment_sitemap_query( $args, $post_type ) {
	if ( 'treatment' !== $post_type ) {
		return $args;
	}

	$meta_query   = isset( $args['meta_query'] ) && is_array( $args['meta_query'] ) ? $args['meta_query'] : array();
	$meta_query[] = array(
		'relation' => 'OR',
		array(
			'key'     => 'brittos_treatment_single_page',
			'compare' => 'NOT EXISTS',
		),
		array(
			'key'     => 'brittos_treatment_single_page',
			'value'   => '0',
			'compare' => '!=',
		),
	);
	$args['meta_query'] = $meta_query;

	return $args;
}
add_filter( 'wp_sitemaps_posts_query_args', 'brittos_core_filter_treatment_sitemap_query', 10, 2 );

/**
 * Get a treatment image, falling back to the shared clinic setting.
 *
 * @param int $treatment_id Treatment post ID.
 * @return int Attachment ID or 0 when no image is available.
 */
function brittos_core_get_treatment_image_id( $treatment_id ) {
	$thumbnail_id = get_post_thumbnail_id( $treatment_id );
	if ( $thumbnail_id && wp_attachment_is_image( $thumbnail_id ) ) {
		return $thumbnail_id;
	}

	$default_id = function_exists( 'brittos_core_get_clinic_field' )
		? absint( brittos_core_get_clinic_field( 'single_default_image_id' ) )
		: 0;

	return $default_id && wp_attachment_is_image( $default_id ) ? $default_id : 0;
}

/**
 * Force the "Treatments" post-type-archive menu item to always display
 * as "Treatments" — even if it was added to a menu before the CPT's
 * `archives` label was corrected, since WordPress snapshots a menu
 * item's title into the database at the moment it's added and never
 * re-reads the CPT label afterwards.
 *
 * @param array $items Nav menu item objects.
 * @return array
 */
function brittos_core_fix_treatment_archive_menu_label( $items ) {
	foreach ( $items as $item ) {
		if ( 'post_type_archive' === $item->type && 'treatment' === $item->object ) {
			$item->title = __( 'Treatments', 'brittos-core' );
		}
	}
	return $items;
}
add_filter( 'wp_nav_menu_objects', 'brittos_core_fix_treatment_archive_menu_label' );
