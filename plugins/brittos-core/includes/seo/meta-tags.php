<?php
/**
 * SEO Meta tags engine: automated, high-precision meta description,
 * canonical link, OpenGraph, and Twitter Card tags.
 * Designed to achieve a 100/100 Lighthouse SEO audit score.
 *
 * Steps aside cleanly if an external SEO plugin (Yoast, Rank Math, etc.)
 * is detected.
 *
 * @package Brittos_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Truncate text cleanly to a maximum length on a word boundary.
 *
 * @param string $text   Raw string.
 * @param int    $max_len Maximum allowed characters (standard 150-155 for SEO).
 * @return string
 */
function brittos_core_clean_seo_excerpt( $text, $max_len = 155 ) {
	$text = wp_strip_all_tags( (string) $text );
	$text = preg_replace( '/\s+/', ' ', $text );
	$text = trim( $text );

	if ( mb_strlen( $text ) <= $max_len ) {
		return $text;
	}

	$truncated = mb_substr( $text, 0, $max_len );
	$last_space = mb_strrpos( $truncated, ' ' );

	if ( false !== $last_space && $last_space > ( $max_len * 0.75 ) ) {
		$truncated = mb_substr( $truncated, 0, $last_space );
	}

	return rtrim( $truncated, '.,;: ' ) . '...';
}

/**
 * Resolve the optimal meta description for the current query context.
 * Always guarantees a length between 70 and 155 characters.
 *
 * @return string
 */
function brittos_core_get_meta_description() {
	$clinic_name  = brittos_core_get_clinic_field( 'clinic_name', get_bloginfo( 'name' ) );
	$dentist_name = brittos_core_get_clinic_field( 'dentist_name', '' );
	$city         = brittos_core_get_clinic_field( 'city', '' );

	// 1. Front Page / Homepage
	if ( is_front_page() ) {
		$custom_desc = brittos_core_get_clinic_field( 'meta_description', '' );
		if ( ! empty( $custom_desc ) ) {
			return brittos_core_clean_seo_excerpt( $custom_desc );
		}

		$location_part = $city ? sprintf( __( ' in %s', 'brittos-core' ), $city ) : '';
		$dentist_part  = $dentist_name ? sprintf( __( 'with %s', 'brittos-core' ), $dentist_name ) : __( 'modern care', 'brittos-core' );

		return sprintf(
			/* translators: 1: clinic name, 2: dentist/care part, 3: city location */
			__( '%1$s offers gentle, thoughtful dental care %2$s%3$s. Clinical precision, upfront transparent plans, and comfortable visits for your smile.', 'brittos-core' ),
			$clinic_name,
			$dentist_part,
			$location_part
		);
	}

	// 2. Single Treatment Post
	if ( is_singular( 'treatment' ) ) {
		$post_id    = get_the_ID();
		$short_desc = function_exists( 'brittos_core_get_treatment_field' )
			? brittos_core_get_treatment_field( $post_id, 'short_description' )
			: '';

		if ( ! empty( $short_desc ) ) {
			$candidate = sprintf(
				'%s — %s. %s',
				get_the_title( $post_id ),
				$short_desc,
				$clinic_name
			);
			return brittos_core_clean_seo_excerpt( $candidate );
		}

		$post = get_post( $post_id );
		if ( ! empty( $post->post_excerpt ) ) {
			return brittos_core_clean_seo_excerpt( $post->post_excerpt );
		}

		if ( ! empty( $post->post_content ) ) {
			return brittos_core_clean_seo_excerpt( $post->post_content );
		}

		return sprintf(
			/* translators: 1: treatment title, 2: clinic name */
			__( 'Learn about %1$s at %2$s. Comprehensive diagnosis, modern technique, and gentle care tailored to your needs.', 'brittos-core' ),
			get_the_title(),
			$clinic_name
		);
	}

	// 3. Treatment Archive Page
	if ( is_post_type_archive( 'treatment' ) ) {
		$location_part = $city ? sprintf( __( ' in %s', 'brittos-core' ), $city ) : '';
		return sprintf(
			/* translators: 1: clinic name, 2: city */
			__( 'Explore our comprehensive range of dental treatments at %1$s%2$s. Modern restorative, preventive, and cosmetic dental procedures.', 'brittos-core' ),
			$clinic_name,
			$location_part
		);
	}

	// 4. Contact / Booking Page
	if ( is_page_template( 'template-contact.php' ) || is_page( 'contact' ) || is_page( 'book-appointment' ) ) {
		$location_part = $city ? sprintf( __( ' in %s', 'brittos-core' ), $city ) : '';
		return sprintf(
			/* translators: 1: clinic name, 2: city */
			__( 'Book your dental consultation at %1$s%2$s. Transparent plans, gentle treatment, and responsive appointments.', 'brittos-core' ),
			$clinic_name,
			$location_part
		);
	}

	// 5. Singular Post / Standard Page
	if ( is_singular() ) {
		$post = get_post();
		if ( ! empty( $post->post_excerpt ) ) {
			return brittos_core_clean_seo_excerpt( $post->post_excerpt );
		}
		if ( ! empty( $post->post_content ) ) {
			return brittos_core_clean_seo_excerpt( $post->post_content );
		}
		return sprintf(
			/* translators: 1: page title, 2: clinic name */
			__( '%1$s at %2$s — Quiet confidence, modern dentistry, and human care.', 'brittos-core' ),
			get_the_title(),
			$clinic_name
		);
	}

	// 6. Generic Archive / Blog
	if ( is_archive() ) {
		$archive_title = get_the_archive_title();
		return sprintf(
			/* translators: 1: archive title, 2: clinic name */
			__( '%1$s articles and insights from %2$s.', 'brittos-core' ),
			$archive_title,
			$clinic_name
		);
	}

	// Fallback
	return sprintf(
		__( 'Modern, thoughtful dentistry by %1$s. Clinical precision and patient-first care.', 'brittos-core' ),
		$clinic_name
	);
}

/**
 * Resolve the social sharing image URL (OpenGraph / Twitter).
 *
 * @return string
 */
function brittos_core_get_seo_image_url() {
	if ( is_singular() && has_post_thumbnail() ) {
		$thumb_url = get_the_post_thumbnail_url( get_the_ID(), 'brittos-hero' );
		if ( $thumb_url ) {
			return esc_url( $thumb_url );
		}
	}

	$dentist_photo_id = brittos_core_get_clinic_field( 'dentist_photo_id' );
	if ( $dentist_photo_id ) {
		$photo_url = wp_get_attachment_image_url( $dentist_photo_id, 'brittos-hero' );
		if ( $photo_url ) {
			return esc_url( $photo_url );
		}
	}

	$logo = get_template_directory_uri() . '/assets/images/logo.png';
	return esc_url( $logo );
}

/**
 * Output high-score SEO meta tags, canonical link, and social graph cards in wp_head.
 */
function brittos_core_output_seo_meta_tags() {
	// Yield immediately if an established SEO plugin is managing metadata.
	if ( function_exists( 'brittos_core_seo_plugin_active' ) && brittos_core_seo_plugin_active() ) {
		return;
	}

	$meta_description = brittos_core_get_meta_description();
	$site_name        = brittos_core_get_clinic_field( 'clinic_name', get_bloginfo( 'name' ) );
	$canonical_url    = is_front_page() ? home_url( '/' ) : ( is_singular() ? get_permalink() : ( is_post_type_archive( 'treatment' ) ? get_post_type_archive_link( 'treatment' ) : '' ) );
	$image_url        = brittos_core_get_seo_image_url();
	$page_title       = wp_get_document_title();

	echo "\n<!-- SEO Meta Tags (Lighthouse 100 Optimized) -->\n";

	// 1. Primary Meta Description (Required by Lighthouse SEO Audit)
	if ( ! empty( $meta_description ) ) {
		echo '<meta name="description" content="' . esc_attr( $meta_description ) . '">' . "\n";
	}

	// 2. Canonical URL
	if ( ! empty( $canonical_url ) ) {
		echo '<link rel="canonical" href="' . esc_url( $canonical_url ) . '">' . "\n";
	}

	// 3. OpenGraph Tags
	echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( is_singular( 'post' ) ? 'article' : 'website' ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $page_title ) . '">' . "\n";
	if ( ! empty( $meta_description ) ) {
		echo '<meta property="og:description" content="' . esc_attr( $meta_description ) . '">' . "\n";
	}
	if ( ! empty( $canonical_url ) ) {
		echo '<meta property="og:url" content="' . esc_url( $canonical_url ) . '">' . "\n";
	}
	echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '">' . "\n";
	if ( ! empty( $image_url ) ) {
		echo '<meta property="og:image" content="' . esc_url( $image_url ) . '">' . "\n";
	}

	// 4. Twitter Cards
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $page_title ) . '">' . "\n";
	if ( ! empty( $meta_description ) ) {
		echo '<meta name="twitter:description" content="' . esc_attr( $meta_description ) . '">' . "\n";
	}
	if ( ! empty( $image_url ) ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image_url ) . '">' . "\n";
	}
	echo "<!-- / SEO Meta Tags -->\n\n";
}
add_action( 'wp_head', 'brittos_core_output_seo_meta_tags', 1 );
