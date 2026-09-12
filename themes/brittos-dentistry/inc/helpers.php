<?php
/**
 * Small template helpers shared across template-parts. These read data
 * exposed by the brittos-core plugin via its own helper functions and
 * degrade gracefully if the plugin is inactive.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Safely fetch a clinic field from the core plugin, with a fallback.
 *
 * @param string $key     Field key, e.g. 'phone', 'address'.
 * @param string $default Fallback string if plugin/field is unavailable.
 * @return string
 */
function brittos_clinic_field( $key, $default = '' ) {
	if ( function_exists( 'brittos_core_get_clinic_field' ) ) {
		$value = brittos_core_get_clinic_field( $key );
		if ( '' !== $value && null !== $value ) {
			return $value;
		}
	}
	return $default;
}

/**
 * Telephone-safe href for `tel:` links (strips everything but digits and +).
 *
 * @param string $phone Raw phone string.
 * @return string
 */
function brittos_tel_href( $phone ) {
	$digits = preg_replace( '/[^0-9+]/', '', (string) $phone );
	return 'tel:' . $digits;
}

/**
 * WhatsApp click-to-chat href.
 *
 * @param string $number Raw WhatsApp number.
 * @param string $message Optional prefilled message.
 * @return string
 */
function brittos_whatsapp_href( $number, $message = '' ) {
	$digits = preg_replace( '/[^0-9]/', '', (string) $number );
	if ( '' === $digits ) {
		return '';
	}
	$url = 'https://wa.me/' . $digits;
	if ( '' !== $message ) {
		$url .= '?text=' . rawurlencode( $message );
	}
	return $url;
}

/**
 * Render a template-part button component with consistent markup.
 *
 * @param array $args {
 *     @type string $text  Visible label (required).
 *     @type string $url   Destination URL (required).
 *     @type string $style 'primary'|'secondary'|'ghost'.
 *     @type string $icon  Optional inline SVG name handled inside the component.
 * }
 */
function brittos_button( $args ) {
	get_template_part( 'template-parts/components/button', null, $args );
}

/**
 * True when the current view has no meaningful content and a friendly
 * placeholder should be shown instead of empty markup.
 *
 * @param array $items Array of posts/items to check.
 * @return bool
 */
function brittos_is_empty_state( $items ) {
	return empty( $items );
}

/**
 * Build the breadcrumb trail for the current view. Rendered sitewide on
 * all non-front-page views via template-parts/global/breadcrumbs.php.
 *
 * @return array Array of [ 'label' => string, 'url' => string ], empty on the front page.
 */
function brittos_get_breadcrumb_trail() {
	if ( is_front_page() ) {
		return array();
	}

	$trail = array(
		array(
			'label' => __( 'Home', 'brittos-dentistry' ),
			'url'   => home_url( '/' ),
		),
	);

	if ( is_singular( 'treatment' ) ) {
		$archive_link = get_post_type_archive_link( 'treatment' );
		if ( $archive_link ) {
			$trail[] = array(
				'label' => __( 'Treatments', 'brittos-dentistry' ),
				'url'   => $archive_link,
			);
		}

		$trail[] = array(
			'label' => get_the_title(),
			'url'   => '',
		);
	} elseif ( is_post_type_archive( 'treatment' ) || is_tax( 'treatment_category' ) ) {
		$trail[] = array(
			'label' => __( 'Treatments', 'brittos-dentistry' ),
			'url'   => '',
		);
	} elseif ( is_page() ) {
		$trail[] = array(
			'label' => get_the_title(),
			'url'   => '',
		);
	} elseif ( is_singular() ) {
		$trail[] = array(
			'label' => get_the_title(),
			'url'   => '',
		);
	} elseif ( is_search() ) {
		$trail[] = array(
			'label' => __( 'Search results', 'brittos-dentistry' ),
			'url'   => '',
		);
	} elseif ( is_404() ) {
		$trail[] = array(
			'label' => __( 'Page not found', 'brittos-dentistry' ),
			'url'   => '',
		);
	} else {
		$trail[] = array(
			'label' => wp_get_document_title(),
			'url'   => '',
		);
	}

	return $trail;
}
