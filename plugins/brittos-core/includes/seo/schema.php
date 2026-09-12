<?php
/**
 * Structured data (schema.org) for a local dental clinic. Kept modular
 * and easy to disable: if a major SEO plugin (Yoast, Rank Math, etc.) is
 * later installed and detected, this module steps aside to avoid
 * duplicate schema output.
 *
 * Only outputs fields the clinic owner has actually filled in via
 * Settings > Clinic Info — nothing here is invented.
 *
 * @package Brittos_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Detect a handful of common SEO plugins that already emit their own
 * schema, so we don't duplicate it.
 *
 * @return bool
 */
function brittos_core_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' )      // Yoast SEO.
		|| defined( 'RANK_MATH_VERSION' )   // Rank Math.
		|| class_exists( 'All_in_One_SEO_Pack' )
		|| defined( 'AIOSEO_VERSION' );
}

/**
 * Build the LocalBusiness/Dentist schema graph from clinic settings.
 *
 * @return array|null Null when there isn't enough real data to justify output.
 */
function brittos_core_build_local_business_schema() {
	$clinic_name = brittos_core_get_clinic_field( 'clinic_name' );
	$phone       = brittos_core_get_clinic_field( 'phone' );
	$address     = brittos_core_get_clinic_field( 'address' );
	$city        = brittos_core_get_clinic_field( 'city' );

	// Require at least a name and one contact/location detail before emitting anything.
	if ( ! $clinic_name || ( ! $phone && ! $address ) ) {
		return null;
	}

	$schema = array(
		'@context' => 'https://schema.org',
		'@type'    => 'Dentist',
		'name'     => $clinic_name,
		'url'      => home_url( '/' ),
	);

	$logo = get_template_directory_uri() . '/assets/images/logo.png';
	if ( $logo ) {
		$schema['image'] = $logo;
		$schema['logo']  = $logo;
	}

	if ( $phone ) {
		$schema['telephone'] = $phone;
	}

	$email = brittos_core_get_clinic_field( 'email' );
	if ( $email ) {
		$schema['email'] = $email;
	}

	if ( $address || $city ) {
		$postal_address = array( '@type' => 'PostalAddress' );
		if ( $address ) {
			$postal_address['streetAddress'] = $address;
		}
		if ( $city ) {
			$postal_address['addressLocality'] = $city;
		}
		$postal_code = brittos_core_get_clinic_field( 'postal_code' );
		if ( $postal_code ) {
			$postal_address['postalCode'] = $postal_code;
		}
		$schema['address'] = $postal_address;
	}

	$dentist_name = brittos_core_get_clinic_field( 'dentist_name' );
	if ( $dentist_name ) {
		$schema['employee'] = array(
			'@type' => 'Person',
			'name'  => $dentist_name,
		);
	}

	$opening_hours = brittos_core_get_clinic_field( 'opening_hours' );
	if ( $opening_hours ) {
		// Stored as free text (one line per day) since hours vary in format;
		// exposed as a plain description rather than guessed openingHoursSpecification.
		$schema['description'] = wp_strip_all_tags( $opening_hours );
	}

	return $schema;
}

/**
 * Build FAQPage schema from currently published, site-wide FAQs.
 *
 * @return array|null
 */
function brittos_core_build_faq_schema() {
	if ( ! post_type_exists( 'faq' ) ) {
		return null;
	}

	$faqs = get_posts( array(
		'post_type'      => 'faq',
		'posts_per_page' => 8,
		'post_status'    => 'publish',
		'no_found_rows'  => true,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	) );

	if ( ! $faqs ) {
		return null;
	}

	$entities = array();
	foreach ( $faqs as $faq ) {
		$answer = apply_filters( 'the_content', $faq->post_content );
		$entities[] = array(
			'@type'          => 'Question',
			'name'           => wp_strip_all_tags( get_the_title( $faq ) ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => wp_strip_all_tags( $answer ),
			),
		);
	}

	return array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $entities,
	);
}

/**
 * Build Service schema for a single treatment, nested under the clinic.
 * Only ever uses data the clinic owner actually entered (title, short
 * description, category) — never invents pricing, duration claims, or
 * outcomes that weren't explicitly provided.
 *
 * @param int $treatment_id Treatment post ID.
 * @return array|null
 */
function brittos_core_build_treatment_service_schema( $treatment_id ) {
	$clinic_name = brittos_core_get_clinic_field( 'clinic_name' );
	if ( ! $clinic_name ) {
		return null;
	}

	$schema = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Service',
		'name'        => get_the_title( $treatment_id ),
		'url'         => get_permalink( $treatment_id ),
		'serviceType' => get_the_title( $treatment_id ),
		'provider'    => array(
			'@type' => 'Dentist',
			'name'  => $clinic_name,
			'url'   => home_url( '/' ),
		),
	);

	$description = brittos_core_get_treatment_field( $treatment_id, 'short_description' );
	if ( ! $description ) {
		$description = get_the_excerpt( $treatment_id );
	}
	if ( $description ) {
		$schema['description'] = wp_strip_all_tags( $description );
	}

	$categories = get_the_terms( $treatment_id, 'treatment_category' );
	if ( $categories && ! is_wp_error( $categories ) ) {
		$schema['category'] = wp_list_pluck( $categories, 'name' );
	}

	$city = brittos_core_get_clinic_field( 'city' );
	if ( $city ) {
		$schema['areaServed'] = $city;
	}

	return $schema;
}

/**
 * Build FAQPage schema scoped to a single treatment's related FAQs
 * (as tagged on the FAQ side — see includes/fields/faq-fields.php).
 *
 * @param int $treatment_id Treatment post ID.
 * @return array|null
 */
function brittos_core_build_treatment_faq_schema( $treatment_id ) {
	if ( ! function_exists( 'brittos_core_get_related_faqs_for_treatment' ) ) {
		return null;
	}

	$query = brittos_core_get_related_faqs_for_treatment( $treatment_id, 20 );
	if ( ! $query->have_posts() ) {
		return null;
	}

	$entities = array();
	foreach ( $query->posts as $faq ) {
		$answer     = apply_filters( 'the_content', $faq->post_content );
		$entities[] = array(
			'@type'          => 'Question',
			'name'           => wp_strip_all_tags( get_the_title( $faq ) ),
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text'  => wp_strip_all_tags( $answer ),
			),
		);
	}

	return array(
		'@context'   => 'https://schema.org',
		'@type'      => 'FAQPage',
		'mainEntity' => $entities,
	);
}

/**
 * Print schema as a single JSON-LD script tag in wp_head, only on the
 * front page (business schema) and wherever FAQs are actually rendered.
 */
function brittos_core_output_schema() {
	if ( brittos_core_seo_plugin_active() ) {
		return;
	}

	$graphs = array();

	if ( is_front_page() ) {
		$business = brittos_core_build_local_business_schema();
		if ( $business ) {
			$graphs[] = $business;
		}

		$faq_schema = brittos_core_build_faq_schema();
		if ( $faq_schema ) {
			$graphs[] = $faq_schema;
		}
	} elseif ( is_singular( 'treatment' ) ) {
		$business = brittos_core_build_local_business_schema();
		if ( $business ) {
			$graphs[] = $business;
		}

		$service = brittos_core_build_treatment_service_schema( get_the_ID() );
		if ( $service ) {
			$graphs[] = $service;
		}

		$treatment_faqs = brittos_core_build_treatment_faq_schema( get_the_ID() );
		if ( $treatment_faqs ) {
			$graphs[] = $treatment_faqs;
		}
	}

	if ( empty( $graphs ) ) {
		return;
	}

	foreach ( $graphs as $graph ) {
		echo '<script type="application/ld+json">' . wp_json_encode( $graph ) . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}
add_action( 'wp_head', 'brittos_core_output_schema' );
