<?php
/**
 * Core theme setup: supports, menus, image sizes.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register theme support and navigation menus.
 */
function brittos_setup() {

	load_theme_textdomain( 'brittos-dentistry', BRITTOS_THEME_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
		'navigation-widgets',
	) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'align-wide' );

	// Editor already gets its palette/sizes from theme.json; disable the legacy custom picker duplication.
	add_theme_support( 'editor-color-palette' );

	set_post_thumbnail_size( 1200, 800, true );
	add_image_size( 'brittos-card', 640, 480, true );
	add_image_size( 'brittos-hero', 1600, 1200, true );
	add_image_size( 'brittos-avatar', 160, 160, true );

	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'brittos-dentistry' ),
		'footer'  => __( 'Footer Navigation', 'brittos-dentistry' ),
	) );
}
add_action( 'after_setup_theme', 'brittos_setup' );

/**
 * Sensible excerpt length for cards/archives.
 */
function brittos_excerpt_length( $length ) {
	return 24;
}
add_filter( 'excerpt_length', 'brittos_excerpt_length' );

function brittos_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'brittos_excerpt_more' );
