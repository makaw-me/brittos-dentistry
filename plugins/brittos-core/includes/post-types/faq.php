<?php
/**
 * `faq` custom post type. Title is the question, editor content is the
 * answer. Can be shown site-wide (final FAQ section) or attached to a
 * specific treatment via its `faq_ids` field.
 *
 * @package Brittos_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function brittos_core_register_faq_cpt() {

	$labels = array(
		'name'               => _x( 'FAQs', 'Post type general name', 'brittos-core' ),
		'singular_name'      => _x( 'FAQ', 'Post type singular name', 'brittos-core' ),
		'menu_name'          => _x( 'FAQs', 'Admin Menu text', 'brittos-core' ),
		'add_new'            => __( 'Add New FAQ', 'brittos-core' ),
		'add_new_item'       => __( 'Add New FAQ', 'brittos-core' ),
		'edit_item'          => __( 'Edit FAQ', 'brittos-core' ),
		'new_item'           => __( 'New FAQ', 'brittos-core' ),
		'view_item'          => __( 'View FAQ', 'brittos-core' ),
		'search_items'       => __( 'Search FAQs', 'brittos-core' ),
		'not_found'          => __( 'No FAQs found', 'brittos-core' ),
		'not_found_in_trash' => __( 'No FAQs found in Trash', 'brittos-core' ),
		'all_items'          => __( 'All FAQs', 'brittos-core' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_rest'        => true,
		'query_var'           => true,
		'rewrite'             => array( 'slug' => 'faq', 'with_front' => false ),
		'capability_type'     => 'post',
		'has_archive'         => false,
		'hierarchical'        => false,
		'menu_position'       => 22,
		'menu_icon'           => 'dashicons-editor-help',
		'supports'            => array( 'title', 'editor', 'page-attributes' ),
		'exclude_from_search' => true,
	);

	register_post_type( 'faq', $args );
}
add_action( 'init', 'brittos_core_register_faq_cpt' );
