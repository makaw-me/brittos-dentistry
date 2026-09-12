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

	$fonts_css_path       = BRITTOS_THEME_DIR . '/assets/css/fonts.css';
	$main_css_path        = BRITTOS_THEME_DIR . '/assets/css/main.css';
	$components_css_path  = BRITTOS_THEME_DIR . '/assets/css/components.css';
	$dist_css_path        = BRITTOS_THEME_DIR . '/assets/dist/css/site.min.css';
	$dist_js_path         = BRITTOS_THEME_DIR . '/assets/dist/js/site.min.js';
	$navigation_js_path   = BRITTOS_THEME_DIR . '/assets/js/navigation.js';
	$main_js_path         = BRITTOS_THEME_DIR . '/assets/js/main.js';
	$animations_js_path   = BRITTOS_THEME_DIR . '/assets/js/animations.js';
	$theme_mode_js_path   = BRITTOS_THEME_DIR . '/assets/js/theme-mode.js';
	$use_dist_assets      = file_exists( $dist_css_path ) && file_exists( $dist_js_path );

	wp_enqueue_style(
		'brittos-fonts',
		BRITTOS_THEME_URI . '/assets/css/fonts.css',
		array(),
		file_exists( $fonts_css_path ) ? filemtime( $fonts_css_path ) : BRITTOS_THEME_VERSION
	);

	if ( $use_dist_assets ) {
		wp_enqueue_style(
			'brittos-site',
			BRITTOS_THEME_URI . '/assets/dist/css/site.min.css',
			array( 'brittos-fonts' ),
			filemtime( $dist_css_path )
		);

		wp_enqueue_script(
			'brittos-site',
			BRITTOS_THEME_URI . '/assets/dist/js/site.min.js',
			array(),
			filemtime( $dist_js_path ),
			array( 'strategy' => 'defer', 'in_footer' => true )
		);
	} else {
		wp_enqueue_style(
			'brittos-main',
			BRITTOS_THEME_URI . '/assets/css/main.css',
			array( 'brittos-fonts' ),
			file_exists( $main_css_path ) ? filemtime( $main_css_path ) : BRITTOS_THEME_VERSION
		);

		wp_enqueue_style(
			'brittos-components',
			BRITTOS_THEME_URI . '/assets/css/components.css',
			array( 'brittos-main' ),
			file_exists( $components_css_path ) ? filemtime( $components_css_path ) : BRITTOS_THEME_VERSION
		);

		wp_enqueue_script(
			'brittos-navigation',
			BRITTOS_THEME_URI . '/assets/js/navigation.js',
			array(),
			file_exists( $navigation_js_path ) ? filemtime( $navigation_js_path ) : BRITTOS_THEME_VERSION,
			array( 'strategy' => 'defer', 'in_footer' => true )
		);

		wp_enqueue_script(
			'brittos-main',
			BRITTOS_THEME_URI . '/assets/js/main.js',
			array(),
			file_exists( $main_js_path ) ? filemtime( $main_js_path ) : BRITTOS_THEME_VERSION,
			array( 'strategy' => 'defer', 'in_footer' => true )
		);

		wp_enqueue_script(
			'brittos-animations',
			BRITTOS_THEME_URI . '/assets/js/animations.js',
			array(),
			file_exists( $animations_js_path ) ? filemtime( $animations_js_path ) : BRITTOS_THEME_VERSION,
			array( 'strategy' => 'defer', 'in_footer' => true )
		);

		wp_enqueue_script(
			'brittos-theme-mode',
			BRITTOS_THEME_URI . '/assets/js/theme-mode.js',
			array(),
			file_exists( $theme_mode_js_path ) ? filemtime( $theme_mode_js_path ) : BRITTOS_THEME_VERSION,
			array( 'strategy' => 'defer', 'in_footer' => true )
		);
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'brittos_enqueue_assets' );

