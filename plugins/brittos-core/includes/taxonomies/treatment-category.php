<?php
/**
 * `treatment_category` taxonomy for grouping treatments
 * (e.g. General, Cosmetic, Restorative, Preventive).
 *
 * @package Brittos_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function brittos_core_register_treatment_category_taxonomy() {

	$labels = array(
		'name'              => _x( 'Treatment Categories', 'taxonomy general name', 'brittos-core' ),
		'singular_name'     => _x( 'Treatment Category', 'taxonomy singular name', 'brittos-core' ),
		'search_items'      => __( 'Search Treatment Categories', 'brittos-core' ),
		'all_items'         => __( 'All Treatment Categories', 'brittos-core' ),
		'parent_item'       => __( 'Parent Category', 'brittos-core' ),
		'parent_item_colon' => __( 'Parent Category:', 'brittos-core' ),
		'edit_item'         => __( 'Edit Treatment Category', 'brittos-core' ),
		'update_item'       => __( 'Update Treatment Category', 'brittos-core' ),
		'add_new_item'      => __( 'Add New Treatment Category', 'brittos-core' ),
		'new_item_name'     => __( 'New Treatment Category Name', 'brittos-core' ),
		'menu_name'         => __( 'Categories', 'brittos-core' ),
	);

	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => false,
		'publicly_queryable'=> false,
		'show_ui'           => true,
		'show_in_rest'      => true,
		'show_admin_column' => true,
		'query_var'         => false,
		'rewrite'           => false,
	);

	register_taxonomy( 'treatment_category', array( 'treatment' ), $args );
}
add_action( 'init', 'brittos_core_register_treatment_category_taxonomy' );

/**
 * Defensive redirect: ensure any direct requests to legacy category URLs
 * permanently 301 redirect to the main Treatments catalog archive.
 */
function brittos_core_redirect_treatment_category_archives() {
	if ( is_tax( 'treatment_category' ) ) {
		$archive_url = get_post_type_archive_link( 'treatment' );
		if ( ! $archive_url ) {
			$archive_url = home_url( '/' );
		}
		wp_safe_redirect( $archive_url, 301 );
		exit;
	}
}
add_action( 'template_redirect', 'brittos_core_redirect_treatment_category_archives' );
