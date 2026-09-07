<?php
/**
 * Theme bootstrap for Dr. Britto's Dentistry.
 *
 * Presentation only. All clinic data / CPTs / forms live in the
 * brittos-core plugin. This file just wires up the theme's own
 * concerns (setup, assets, security headers, performance, a11y helpers).
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BRITTOS_THEME_VERSION', '1.0.0' );
define( 'BRITTOS_THEME_DIR', get_template_directory() );
define( 'BRITTOS_THEME_URI', get_template_directory_uri() );

$brittos_theme_includes = array(
	'/inc/setup.php',
	'/inc/enqueue.php',
	'/inc/theme-mode.php',
	'/inc/security.php',
	'/inc/performance.php',
	'/inc/accessibility.php',
	'/inc/helpers.php',
);

foreach ( $brittos_theme_includes as $brittos_theme_file ) {
	$brittos_theme_path = BRITTOS_THEME_DIR . $brittos_theme_file;
	if ( file_exists( $brittos_theme_path ) ) {
		require_once $brittos_theme_path;
	}
}
unset( $brittos_theme_includes, $brittos_theme_file, $brittos_theme_path );
