<?php
/**
 * Asset enqueueing — conditional, per-page loading.
 *
 * Strategy
 * ─────────
 * CSS is split into a mandatory global bundle (header, footer, buttons,
 * typography) plus per-page bundles that load only on the routes that
 * actually use those component styles. This avoids shipping all ~91 KB of
 * component CSS to pages that only need ~42 KB of it.
 *
 * JS is similarly scoped:
 *   • navigation.js + theme-mode.js  — global (every page needs the header).
 *   • animations.js                  — global except privacy-policy.
 *   • faq-accordion.js               — pages that render .faq__list.
 *   • main.js (form AJAX)            — contact template only (sole .appointment-form).
 *   • before-after-carousel.js       — singular treatment only.
 *   • promo-popup.js / promo-popup.css — when popup is actually configured.
 *   • contact-map.css                — contact page + map enabled + URL set.
 *
 * Dist bundles
 * ─────────────
 * When per-page minified dist bundles exist they take precedence over the
 * source manifests below. The helpers brittos_css_dist() and
 * brittos_js_dist() resolve the correct path for each handle name, falling
 * back transparently to the source manifest/file when no dist exists.
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
 * Resolve a CSS asset URI and its absolute path.
 *
 * Checks for a pre-built dist file named after the handle (e.g.
 * assets/dist/css/global.min.css for handle 'brittos-global'), falling
 * back to the source manifest file (e.g. assets/css/global.css).
 *
 * @param string $handle     WordPress style handle, e.g. 'brittos-global'.
 * @param string $source_rel Relative path from theme root to the source CSS.
 * @return array{ uri: string, path: string }
 */
function brittos_css_asset( $handle, $source_rel ) {
	// Strip 'brittos-' prefix to derive the dist filename.
	$slug     = str_replace( 'brittos-', '', $handle );
	$dist_rel = 'assets/dist/css/' . $slug . '.min.css';
	$dist_abs = BRITTOS_THEME_DIR . '/' . $dist_rel;

	if ( file_exists( $dist_abs ) ) {
		return array(
			'uri'  => BRITTOS_THEME_URI . '/' . $dist_rel,
			'path' => $dist_abs,
		);
	}

	$src_abs = BRITTOS_THEME_DIR . '/' . $source_rel;
	return array(
		'uri'  => BRITTOS_THEME_URI . '/' . $source_rel,
		'path' => $src_abs,
	);
}

/**
 * Resolve a JS asset URI and its absolute path.
 *
 * @param string $handle     WordPress script handle, e.g. 'brittos-navigation'.
 * @param string $source_rel Relative path from theme root to the source JS.
 * @return array{ uri: string, path: string }
 */
function brittos_js_asset( $handle, $source_rel ) {
	$slug     = str_replace( 'brittos-', '', $handle );
	$dist_rel = 'assets/dist/js/' . $slug . '.min.js';
	$dist_abs = BRITTOS_THEME_DIR . '/' . $dist_rel;

	if ( file_exists( $dist_abs ) ) {
		return array(
			'uri'  => BRITTOS_THEME_URI . '/' . $dist_rel,
			'path' => $dist_abs,
		);
	}

	$src_abs = BRITTOS_THEME_DIR . '/' . $source_rel;
	return array(
		'uri'  => BRITTOS_THEME_URI . '/' . $source_rel,
		'path' => $src_abs,
	);
}

/**
 * Enqueue theme styles and scripts.
 */
function brittos_enqueue_assets() {

	$is_privacy   = is_page( 'privacy-policy' );
	$is_front     = is_front_page();
	$is_singular_treatment = is_singular( 'treatment' );
	$is_treatment_archive  = is_post_type_archive( 'treatment' );
	$is_about     = is_page_template( 'page-about.php' );
	$is_contact   = is_page_template( 'template-contact.php' );

	// Pages that show a .faq__list.
	$has_faq = $is_front || $is_singular_treatment || $is_treatment_archive;

	// -------------------------------------------------------------------------
	// Fonts — always, no dependencies.
	// -------------------------------------------------------------------------
	$fonts_path = BRITTOS_THEME_DIR . '/assets/css/fonts.css';
	wp_enqueue_style(
		'brittos-fonts',
		BRITTOS_THEME_URI . '/assets/css/fonts.css',
		array(),
		brittos_asset_version( $fonts_path )
	);

	// -------------------------------------------------------------------------
	// Base tokens / reset / layout primitives — always.
	// -------------------------------------------------------------------------
	$main_css = brittos_css_asset( 'brittos-main', 'assets/css/main.css' );
	wp_enqueue_style(
		'brittos-main',
		$main_css['uri'],
		array( 'brittos-fonts' ),
		brittos_asset_version( $main_css['path'] )
	);

	// -------------------------------------------------------------------------
	// Global components — always (header, footer, buttons, typography, etc.).
	// -------------------------------------------------------------------------
	$global_css = brittos_css_asset( 'brittos-global', 'assets/css/global.css' );
	wp_enqueue_style(
		'brittos-global',
		$global_css['uri'],
		array( 'brittos-main' ),
		brittos_asset_version( $global_css['path'] )
	);

	// -------------------------------------------------------------------------
	// Per-page CSS — only the route that needs it pays for it.
	// -------------------------------------------------------------------------

	if ( $is_front ) {
		$page_css = brittos_css_asset( 'brittos-page-home', 'assets/css/page-home.css' );
		wp_enqueue_style(
			'brittos-page-home',
			$page_css['uri'],
			array( 'brittos-global' ),
			brittos_asset_version( $page_css['path'] )
		);
	}

	if ( $is_treatment_archive ) {
		$page_css = brittos_css_asset( 'brittos-page-treatments-archive', 'assets/css/page-treatments-archive.css' );
		wp_enqueue_style(
			'brittos-page-treatments-archive',
			$page_css['uri'],
			array( 'brittos-global' ),
			brittos_asset_version( $page_css['path'] )
		);
	}

	if ( $is_singular_treatment ) {
		$page_css = brittos_css_asset( 'brittos-page-treatment-single', 'assets/css/page-treatment-single.css' );
		wp_enqueue_style(
			'brittos-page-treatment-single',
			$page_css['uri'],
			array( 'brittos-global' ),
			brittos_asset_version( $page_css['path'] )
		);
	}

	if ( $is_about ) {
		$page_css = brittos_css_asset( 'brittos-page-about', 'assets/css/page-about.css' );
		wp_enqueue_style(
			'brittos-page-about',
			$page_css['uri'],
			array( 'brittos-global' ),
			brittos_asset_version( $page_css['path'] )
		);
	}

	if ( $is_contact ) {
		$page_css = brittos_css_asset( 'brittos-page-contact', 'assets/css/page-contact.css' );
		wp_enqueue_style(
			'brittos-page-contact',
			$page_css['uri'],
			array( 'brittos-global' ),
			brittos_asset_version( $page_css['path'] )
		);
	}

	if ( $is_privacy ) {
		$page_css = brittos_css_asset( 'brittos-page-privacy', 'assets/css/page-privacy.css' );
		wp_enqueue_style(
			'brittos-page-privacy',
			$page_css['uri'],
			array( 'brittos-global' ),
			brittos_asset_version( $page_css['path'] )
		);
	}

	// -------------------------------------------------------------------------
	// Promotional popup — global but zero-cost when disabled/unconfigured.
	// -------------------------------------------------------------------------
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
		$popup_js       = brittos_js_asset( 'brittos-promo-popup', 'assets/js/promo-popup.js' );

		if ( file_exists( $popup_css_path ) ) {
			wp_enqueue_style(
				'brittos-promo-popup',
				BRITTOS_THEME_URI . '/assets/css/components/promo-popup.css',
				array( 'brittos-global' ),
				brittos_asset_version( $popup_css_path )
			);
		}

		if ( file_exists( $popup_js['path'] ) ) {
			wp_enqueue_script(
				'brittos-promo-popup',
				$popup_js['uri'],
				array(),
				brittos_asset_version( $popup_js['path'] ),
				array( 'strategy' => 'defer', 'in_footer' => true )
			);
		}
	}

	// -------------------------------------------------------------------------
	// Google Maps (contact page + map enabled + embed URL set).
	// -------------------------------------------------------------------------
	if (
		$is_contact
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
				array( 'brittos-global' ),
				brittos_asset_version( $map_css_path )
			);
		}
	}

	// =========================================================================
	// JavaScript
	// =========================================================================

	// -------------------------------------------------------------------------
	// navigation.js — global; handles mobile drawer, desktop mega-menu,
	// dropdown clamping, and transparent-header scroll behaviour.
	// -------------------------------------------------------------------------
	$nav_js = brittos_js_asset( 'brittos-navigation', 'assets/js/navigation.js' );
	wp_enqueue_script(
		'brittos-navigation',
		$nav_js['uri'],
		array(),
		brittos_asset_version( $nav_js['path'] ),
		array( 'strategy' => 'defer', 'in_footer' => true )
	);

	// -------------------------------------------------------------------------
	// theme-mode.js — global; persists light/dark preference and syncs the
	// toggle buttons; must run on every page where the toggle is rendered.
	// -------------------------------------------------------------------------
	$theme_js = brittos_js_asset( 'brittos-theme-mode', 'assets/js/theme-mode.js' );
	wp_enqueue_script(
		'brittos-theme-mode',
		$theme_js['uri'],
		array(),
		brittos_asset_version( $theme_js['path'] ),
		array( 'strategy' => 'defer', 'in_footer' => true )
	);

	// -------------------------------------------------------------------------
	// animations.js — global scroll-reveal; not needed on the static privacy
	// policy page (no [data-reveal] elements there).
	// -------------------------------------------------------------------------
	if ( ! $is_privacy ) {
		$anim_js = brittos_js_asset( 'brittos-animations', 'assets/js/animations.js' );
		wp_enqueue_script(
			'brittos-animations',
			$anim_js['uri'],
			array(),
			brittos_asset_version( $anim_js['path'] ),
			array( 'strategy' => 'defer', 'in_footer' => true )
		);
	}

	// -------------------------------------------------------------------------
	// faq-accordion.js — closes sibling <details> elements on pages that
	// render a .faq__list: homepage, treatments archive, treatment singles.
	// -------------------------------------------------------------------------
	if ( $has_faq ) {
		$faq_js = brittos_js_asset( 'brittos-faq-accordion', 'assets/js/faq-accordion.js' );
		wp_enqueue_script(
			'brittos-faq-accordion',
			$faq_js['uri'],
			array(),
			brittos_asset_version( $faq_js['path'] ),
			array( 'strategy' => 'defer', 'in_footer' => true )
		);
	}

	// -------------------------------------------------------------------------
	// main.js — AJAX enhancement for the appointment enquiry form.
	// The .appointment-form element only exists on the Contact template.
	// -------------------------------------------------------------------------
	if ( $is_contact ) {
		$main_js = brittos_js_asset( 'brittos-main', 'assets/js/main.js' );
		wp_enqueue_script(
			'brittos-main',
			$main_js['uri'],
			array(),
			brittos_asset_version( $main_js['path'] ),
			array( 'strategy' => 'defer', 'in_footer' => true )
		);
	}

	// -------------------------------------------------------------------------
	// before-after-carousel.js — treatment singles only.
	// -------------------------------------------------------------------------
	if ( $is_singular_treatment ) {
		$carousel_js = brittos_js_asset( 'brittos-before-after-carousel', 'assets/js/before-after-carousel.js' );
		if ( file_exists( $carousel_js['path'] ) ) {
			wp_enqueue_script(
				'brittos-before-after-carousel',
				$carousel_js['uri'],
				array(),
				brittos_asset_version( $carousel_js['path'] ),
				array( 'strategy' => 'defer', 'in_footer' => true )
			);
		}
	}

	// -------------------------------------------------------------------------
	// comment-reply — WordPress core; only on singular posts with open comments.
	// -------------------------------------------------------------------------
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'brittos_enqueue_assets' );
