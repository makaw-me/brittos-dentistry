<?php
/**
 * Plugin Name:       Britto's Core
 * Plugin URI:        https://example.com/brittos-dentistry
 * Description:       Functionality and structured clinic data for Dr. Britto's Dentistry: treatments, testimonials, FAQs, clinic settings, the appointment enquiry form and local-business schema. Presentation lives in the theme.
 * Version:           1.0.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * Author:            Britto's Dentistry
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       brittos-core
 *
 * @package Brittos_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BRITTOS_CORE_VERSION', '1.0.0' );
define( 'BRITTOS_CORE_DIR', plugin_dir_path( __FILE__ ) );
define( 'BRITTOS_CORE_URL', plugin_dir_url( __FILE__ ) );
define( 'BRITTOS_CORE_FILE', __FILE__ );

/**
 * Load translations.
 */
function brittos_core_load_textdomain() {
	load_plugin_textdomain( 'brittos-core', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'brittos_core_load_textdomain' );

/**
 * Require all plugin modules. Each file is self-contained and hooks
 * itself into WordPress; this file only orders the includes.
 */
function brittos_core_includes() {
	$files = array(
		'includes/helpers.php',
		'includes/post-types/treatment.php',
		'includes/post-types/testimonial.php',
		'includes/post-types/faq.php',
		'includes/taxonomies/treatment-category.php',
		'includes/fields/clinic-fields.php',
		'includes/fields/treatment-fields.php',
		'includes/forms/appointment.php',
		'includes/seo/schema.php',
	);

	foreach ( $files as $file ) {
		$path = BRITTOS_CORE_DIR . $file;
		if ( file_exists( $path ) ) {
			require_once $path;
		}
	}
}
brittos_core_includes();

/**
 * Flush rewrite rules once after activation, after CPTs/taxonomies have
 * been registered on the following `init`, so pretty permalinks for
 * treatments work immediately without a manual re-save.
 */
function brittos_core_activate() {
	update_option( 'brittos_core_flush_rewrites', 1 );
}
register_activation_hook( __FILE__, 'brittos_core_activate' );

function brittos_core_maybe_flush_rewrites() {
	if ( get_option( 'brittos_core_flush_rewrites' ) ) {
		flush_rewrite_rules();
		delete_option( 'brittos_core_flush_rewrites' );
	}
}
add_action( 'init', 'brittos_core_maybe_flush_rewrites', 20 );

/**
 * Plain rewrite flush on deactivation; no data is deleted here.
 * Destructive cleanup (if the user opts in via Settings) happens only
 * in uninstall.php, and only when the plugin is deleted, not deactivated.
 */
function brittos_core_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'brittos_core_deactivate' );
