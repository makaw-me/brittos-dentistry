<?php
/**
 * Google Places reviews integration for the homepage.
 *
 * The API key must be defined in wp-config.php as
 * BRITTOS_GOOGLE_PLACES_API_KEY. The Place ID is configured in Clinic Info.
 *
 * @package Brittos_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fetch and cache the configured Google Place details.
 *
 * @return array|null
 */
function brittos_core_get_google_reviews() {
	$place_id = trim( (string) brittos_core_get_clinic_field( 'google_place_id' ) );
	$api_key  = defined( 'BRITTOS_GOOGLE_PLACES_API_KEY' ) ? trim( (string) BRITTOS_GOOGLE_PLACES_API_KEY ) : '';

	if ( ! $place_id || ! $api_key || '1' !== brittos_core_get_clinic_field( 'google_reviews_enabled' ) ) {
		return null;
	}

	$cache_key = 'brittos_google_reviews_v3_' . md5( $place_id );
	$cached    = get_transient( $cache_key );
	if ( false !== $cached ) {
		return is_array( $cached ) ? $cached : null;
	}

	$response = wp_safe_remote_get(
		'https://places.googleapis.com/v1/places/' . rawurlencode( $place_id ),
		array(
			'timeout' => 8,
			'headers' => array(
				'X-Goog-Api-Key'  => $api_key,
				'X-Goog-FieldMask' => 'displayName,rating,userRatingCount,reviews,googleMapsUri',
			),
		)
	);

	if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
		set_transient( $cache_key, array(), DAY_IN_SECONDS );
		return null;
	}

	$data = json_decode( wp_remote_retrieve_body( $response ), true );
	if ( ! is_array( $data ) ) {
		set_transient( $cache_key, array(), DAY_IN_SECONDS );
		return null;
	}

	$reviews = array();
	foreach ( isset( $data['reviews'] ) && is_array( $data['reviews'] ) ? $data['reviews'] : array() as $review ) {
		$text = isset( $review['originalText']['text'] ) ? trim( (string) $review['originalText']['text'] ) : '';
		if ( ! $text ) {
			continue;
		}
		$author = isset( $review['authorAttribution']['displayName'] ) ? trim( (string) $review['authorAttribution']['displayName'] ) : brittos_core_get_clinic_field( 'google_reviews_author_fallback', __( 'Google reviewer', 'brittos-core' ) );
		$reviews[] = array(
			'text'       => $text,
			'author'     => $author,
			'author_url' => isset( $review['authorAttribution']['uri'] ) ? esc_url_raw( $review['authorAttribution']['uri'] ) : '',
			'rating'     => isset( $review['rating'] ) ? (float) $review['rating'] : 0,
		);
	}

	$result = array(
		'name'        => isset( $data['displayName']['text'] ) ? sanitize_text_field( $data['displayName']['text'] ) : '',
		'rating'      => isset( $data['rating'] ) ? (float) $data['rating'] : 0,
		'rating_count' => isset( $data['userRatingCount'] ) ? absint( $data['userRatingCount'] ) : 0,
		'maps_url'    => isset( $data['googleMapsUri'] ) ? esc_url_raw( $data['googleMapsUri'] ) : '',
		'reviews'     => array_slice( $reviews, 0, 5 ),
	);

	set_transient( $cache_key, $result, DAY_IN_SECONDS );
	return $result;
}
