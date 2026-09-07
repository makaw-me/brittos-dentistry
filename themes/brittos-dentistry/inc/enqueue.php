<?php
/**
 * Asset enqueueing. Minimal, dependency-free, deferred where possible.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue theme styles and scripts.
 */
function brittos_enqueue_assets() {

	$main_css_path = BRITTOS_THEME_DIR . '/assets/css/main.css';
	$components_css_path = BRITTOS_THEME_DIR . '/assets/css/components.css';

	wp_enqueue_style(
		'brittos-main',
		BRITTOS_THEME_URI . '/assets/css/main.css',
		array(),
		file_exists( $main_css_path ) ? filemtime( $main_css_path ) : BRITTOS_THEME_VERSION
	);

	wp_enqueue_style(
		'brittos-components',
		BRITTOS_THEME_URI . '/assets/css/components.css',
		array( 'brittos-main' ),
		file_exists( $components_css_path ) ? filemtime( $components_css_path ) : BRITTOS_THEME_VERSION
	);

	$nav_js_path  = BRITTOS_THEME_DIR . '/assets/js/navigation.js';
	$main_js_path = BRITTOS_THEME_DIR . '/assets/js/main.js';

	wp_enqueue_script(
		'brittos-navigation',
		BRITTOS_THEME_URI . '/assets/js/navigation.js',
		array(),
		file_exists( $nav_js_path ) ? filemtime( $nav_js_path ) : BRITTOS_THEME_VERSION,
		array( 'strategy' => 'defer', 'in_footer' => true )
	);

	wp_enqueue_script(
		'brittos-main',
		BRITTOS_THEME_URI . '/assets/js/main.js',
		array(),
		file_exists( $main_js_path ) ? filemtime( $main_js_path ) : BRITTOS_THEME_VERSION,
		array( 'strategy' => 'defer', 'in_footer' => true )
	);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'brittos_enqueue_assets' );

