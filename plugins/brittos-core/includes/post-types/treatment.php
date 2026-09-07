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
		'archives'              => __( 'Treatment Archives', 'brittos-core' ),
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
