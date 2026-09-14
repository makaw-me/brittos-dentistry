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
 * Return a content-based asset version for reliable browser cache busting.
 *
 * @param string $path Absolute asset path.
 * @return string
 */
function brittos_asset_version( $path ) {
	static $versions = array();

	if ( isset( $versions[ $path ] ) ) {
		return $versions[ $path ];
	}

	if ( file_exists( $path ) ) {
		$hash = md5_file( $path );
		if ( false !== $hash ) {
			$versions[ $path ] = substr( $hash, 0, 12 );
			return $versions[ $path ];
		}
	}

	$versions[ $path ] = BRITTOS_THEME_VERSION;
	return $versions[ $path ];
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
	$before_after_js_path = BRITTOS_THEME_DIR . '/assets/js/before-after-carousel.js';
	$use_dist_css        = file_exists( $dist_css_path );
	$use_dist_js         = file_exists( $dist_js_path ) && ! is_page( 'privacy-policy' );

	wp_enqueue_style(
		'brittos-fonts',
		BRITTOS_THEME_URI . '/assets/css/fonts.css',
		array(),
		brittos_asset_version( $fonts_css_path )
	);

	if ( $use_dist_css ) {
		wp_enqueue_style(
			'brittos-site',
			BRITTOS_THEME_URI . '/assets/dist/css/site.min.css',
			array( 'brittos-fonts' ),
			brittos_asset_version( $dist_css_path )
		);

	} else {
		wp_enqueue_style(
			'brittos-main',
			BRITTOS_THEME_URI . '/assets/css/main.css',
			array( 'brittos-fonts' ),
			brittos_asset_version( $main_css_path )
		);

		wp_enqueue_style(
			'brittos-components',
			BRITTOS_THEME_URI . '/assets/css/components.css',
			array( 'brittos-main' ),
			brittos_asset_version( $components_css_path )
		);

	}

	if ( $use_dist_js ) {
		wp_enqueue_script(
			'brittos-site',
			BRITTOS_THEME_URI . '/assets/dist/js/site.min.js',
			array(),
			brittos_asset_version( $dist_js_path ),
			array( 'strategy' => 'defer', 'in_footer' => true )
		);
	} else {
		wp_enqueue_script(
			'brittos-navigation',
			BRITTOS_THEME_URI . '/assets/js/navigation.js',
			array(),
			brittos_asset_version( $navigation_js_path ),
			array( 'strategy' => 'defer', 'in_footer' => true )
		);

		wp_enqueue_script(
			'brittos-main',
			BRITTOS_THEME_URI . '/assets/js/main.js',
			array(),
			brittos_asset_version( $main_js_path ),
			array( 'strategy' => 'defer', 'in_footer' => true )
		);

		wp_enqueue_script(
			'brittos-animations',
			BRITTOS_THEME_URI . '/assets/js/animations.js',
			array(),
			brittos_asset_version( $animations_js_path ),
			array( 'strategy' => 'defer', 'in_footer' => true )
		);

		wp_enqueue_script(
			'brittos-theme-mode',
			BRITTOS_THEME_URI . '/assets/js/theme-mode.js',
			array(),
			brittos_asset_version( $theme_mode_js_path ),
			array( 'strategy' => 'defer', 'in_footer' => true )
		);

		if ( is_page( 'privacy-policy' ) ) {
			wp_dequeue_script( 'brittos-main' );
			wp_dequeue_script( 'brittos-animations' );
		}
	}

	if ( is_singular( 'treatment' ) && file_exists( $before_after_js_path ) ) {
		wp_enqueue_script(
			'brittos-before-after-carousel',
			BRITTOS_THEME_URI . '/assets/js/before-after-carousel.js',
			array(),
			brittos_asset_version( $before_after_js_path ),
			array( 'strategy' => 'defer', 'in_footer' => true )
		);
	}

	// Promotional popup: global, but only enqueued when actually enabled
	// with something to show — a disabled/unconfigured popup adds zero
	// CSS/JS to any page.
	$popup_enabled = function_exists( 'brittos_core_get_clinic_field' )
		&& '1' === brittos_core_get_clinic_field( 'popup_enabled' )
		&& (
			brittos_core_get_clinic_field( 'popup_heading' )
			|| brittos_core_get_clinic_field( 'popup_text' )
			|| absint( brittos_core_get_clinic_field( 'popup_image_id' ) )
			|| brittos_core_get_clinic_field( 'popup_cta_text' )
		);

	if ( $popup_enabled ) {
		$popup_css_path = BRITTOS_THEME_DIR . '/assets/css/components/promo-popup.css';
		$popup_js_path  = BRITTOS_THEME_DIR . '/assets/js/promo-popup.js';

		if ( file_exists( $popup_css_path ) ) {
			wp_enqueue_style(
				'brittos-promo-popup',
				BRITTOS_THEME_URI . '/assets/css/components/promo-popup.css',
				array(),
				brittos_asset_version( $popup_css_path )
			);
		}

		if ( file_exists( $popup_js_path ) ) {
			wp_enqueue_script(
				'brittos-promo-popup',
				BRITTOS_THEME_URI . '/assets/js/promo-popup.js',
				array(),
				brittos_asset_version( $popup_js_path ),
				array( 'strategy' => 'defer', 'in_footer' => true )
			);
		}
	}

	// Google Maps (Contact page only): a plain, key-free iframe embed, so
	// there is no Maps JavaScript/API to load anywhere, ever. Only the
	// small container/styling CSS is enqueued, and only on the Contact
	// page template, and only when a map is actually enabled/configured.
	if ( is_page_template( 'template-contact.php' )
		&& function_exists( 'brittos_core_get_clinic_field' )
		&& '1' === brittos_core_get_clinic_field( 'contact_map_enabled' )
		&& function_exists( 'brittos_core_get_map_embed_url' )
		&& brittos_core_get_map_embed_url()
	) {
		$map_css_path = BRITTOS_THEME_DIR . '/assets/css/components/contact-map.css';
		if ( file_exists( $map_css_path ) ) {
			wp_enqueue_style(
				'brittos-contact-map',
				BRITTOS_THEME_URI . '/assets/css/components/contact-map.css',
				array(),
				brittos_asset_version( $map_css_path )
			);
		}
	}

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'brittos_enqueue_assets' );

