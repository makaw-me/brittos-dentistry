<?php
/**
 * `testimonial` custom post type. The post content is the quote itself;
 * the patient's display name is a structured field (allows first-name-only
 * or initials for privacy, kept separate from the WP "author").
 *
 * @package Brittos_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function brittos_core_register_testimonial_cpt() {

	$labels = array(
		'name'               => _x( 'Testimonials', 'Post type general name', 'brittos-core' ),
		'singular_name'      => _x( 'Testimonial', 'Post type singular name', 'brittos-core' ),
		'menu_name'          => _x( 'Testimonials', 'Admin Menu text', 'brittos-core' ),
		'add_new'            => __( 'Add New Testimonial', 'brittos-core' ),
		'add_new_item'       => __( 'Add New Testimonial', 'brittos-core' ),
		'edit_item'          => __( 'Edit Testimonial', 'brittos-core' ),
		'new_item'           => __( 'New Testimonial', 'brittos-core' ),
		'view_item'          => __( 'View Testimonial', 'brittos-core' ),
		'search_items'       => __( 'Search Testimonials', 'brittos-core' ),
		'not_found'          => __( 'No testimonials found', 'brittos-core' ),
		'not_found_in_trash' => __( 'No testimonials found in Trash', 'brittos-core' ),
		'all_items'          => __( 'All Testimonials', 'brittos-core' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'show_in_rest'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'testimonials', 'with_front' => false ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => 21,
		'menu_icon'          => 'dashicons-format-quote',
		'supports'           => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		'exclude_from_search' => true,
	);

	register_post_type( 'testimonial', $args );
}
add_action( 'init', 'brittos_core_register_testimonial_cpt' );
