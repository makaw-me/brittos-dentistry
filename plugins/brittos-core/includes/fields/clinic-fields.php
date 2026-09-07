<?php
/**
 * Site-wide clinic information: a single settings screen storing one
 * option array, with a small helper (`brittos_core_get_clinic_field()`)
 * so templates never scatter raw option lookups.
 *
 * @package Brittos_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BRITTOS_CORE_CLINIC_OPTION', 'brittos_core_clinic' );

/**
 * Retrieve one clinic field, or the whole array when $key is empty.
 *
 * @param string $key     Field key (e.g. 'phone', 'clinic_name').
 * @param mixed  $default Fallback value.
 * @return mixed
 */
function brittos_core_get_clinic_field( $key = '', $default = '' ) {
	$data = get_option( BRITTOS_CORE_CLINIC_OPTION, array() );
	if ( ! is_array( $data ) ) {
		$data = array();
	}
	if ( '' === $key ) {
		return $data;
	}
	return array_key_exists( $key, $data ) && '' !== $data[ $key ] ? $data[ $key ] : $default;
}

/**
 * Register the settings page under Settings > Clinic Info.
 */
function brittos_core_register_clinic_settings_page() {
	add_options_page(
		__( 'Clinic Info', 'brittos-core' ),
		__( 'Clinic Info', 'brittos-core' ),
		'manage_options',
		'brittos-clinic-info',
		'brittos_core_render_clinic_settings_page'
	);
}
add_action( 'admin_menu', 'brittos_core_register_clinic_settings_page' );

/**
 * Register the option + fields with the Settings API.
 */
function brittos_core_register_clinic_settings() {
	register_setting( 'brittos_core_clinic_group', BRITTOS_CORE_CLINIC_OPTION, array(
		'type'              => 'array',
		'sanitize_callback' => 'brittos_core_sanitize_clinic_fields',
		'default'           => array(),
	) );

	add_settings_section(
		'brittos_core_clinic_section',
		__( 'Clinic Information', 'brittos-core' ),
		'__return_false',
		'brittos-clinic-info'
	);

	$fields = brittos_core_clinic_field_definitions();
	foreach ( $fields as $key => $field ) {
		add_settings_field(
			$key,
			$field['label'],
			'brittos_core_render_clinic_field',
			'brittos-clinic-info',
			'brittos_core_clinic_section',
			array( 'key' => $key, 'field' => $field )
		);
	}
}
add_action( 'admin_init', 'brittos_core_register_clinic_settings' );

/**
 * Central definition list for every clinic field: type + label + help text.
 * Keeping this in one place means the settings form and the sanitizer
 * always agree on what fields exist.
 *
 * @return array
 */
function brittos_core_clinic_field_definitions() {
	return array(
		'clinic_name'    => array( 'label' => __( 'Clinic Name', 'brittos-core' ), 'type' => 'text' ),
		'dentist_name'   => array( 'label' => __( 'Dentist Name', 'brittos-core' ), 'type' => 'text' ),
		'credentials'    => array( 'label' => __( 'Professional Credentials', 'brittos-core' ), 'type' => 'text', 'help' => __( 'e.g. degree and registration details. Only enter what is true and verifiable.', 'brittos-core' ) ),
		'phone'          => array( 'label' => __( 'Phone Number', 'brittos-core' ), 'type' => 'text' ),
		'whatsapp_number' => array( 'label' => __( 'WhatsApp Number', 'brittos-core' ), 'type' => 'text' ),
		'email'          => array( 'label' => __( 'Email Address', 'brittos-core' ), 'type' => 'email' ),
		'address'        => array( 'label' => __( 'Street Address', 'brittos-core' ), 'type' => 'text' ),
		'city'           => array( 'label' => __( 'City', 'brittos-core' ), 'type' => 'text' ),
		'postal_code'    => array( 'label' => __( 'Postal Code', 'brittos-core' ), 'type' => 'text' ),
		'opening_hours'  => array( 'label' => __( 'Opening Hours', 'brittos-core' ), 'type' => 'textarea', 'help' => __( 'One line per day, e.g. "Mon–Fri: 10am–6pm".', 'brittos-core' ) ),
		'appointment_url' => array( 'label' => __( 'External Booking URL (optional)', 'brittos-core' ), 'type' => 'url', 'help' => __( 'If set, the header/CTA buttons can link here instead of the on-page enquiry form.', 'brittos-core' ) ),
		'notification_email' => array( 'label' => __( 'Appointment Notification Email', 'brittos-core' ), 'type' => 'email', 'help' => __( 'Where new appointment enquiries are sent. Defaults to the site admin email if left blank.', 'brittos-core' ) ),
		'dentist_photo_id' => array( 'label' => __( 'Dentist Photo', 'brittos-core' ), 'type' => 'media' ),
		'gallery_ids'    => array( 'label' => __( 'Clinic Gallery', 'brittos-core' ), 'type' => 'gallery' ),
	);
}

/**
 * Sanitize the full clinic option array against the field definitions.
 *
 * @param array $input Raw posted values.
 * @return array
 */
function brittos_core_sanitize_clinic_fields( $input ) {
	$clean = array();
	$input = is_array( $input ) ? $input : array();

	foreach ( brittos_core_clinic_field_definitions() as $key => $field ) {
		$raw = isset( $input[ $key ] ) ? $input[ $key ] : '';

		switch ( $field['type'] ) {
			case 'email':
				$clean[ $key ] = sanitize_email( $raw );
				break;
			case 'url':
				$clean[ $key ] = esc_url_raw( $raw );
				break;
			case 'textarea':
				$clean[ $key ] = sanitize_textarea_field( $raw );
				break;
			case 'media':
				$clean[ $key ] = absint( $raw );
				break;
			case 'gallery':
				if ( is_array( $raw ) ) {
					$clean[ $key ] = array_values( array_filter( array_map( 'absint', $raw ) ) );
				} else {
					$clean[ $key ] = array_values( array_filter( array_map( 'absint', explode( ',', (string) $raw ) ) ) );
				}
				break;
			default:
				$clean[ $key ] = sanitize_text_field( $raw );
		}
	}

	return $clean;
}

/**
 * Render one settings field row.
 *
 * @param array $args Contains 'key' and 'field' definition.
 */
function brittos_core_render_clinic_field( $args ) {
	$key   = $args['key'];
	$field = $args['field'];
	$value = brittos_core_get_clinic_field( $key, '' );
	$name  = BRITTOS_CORE_CLINIC_OPTION . '[' . $key . ']';

	switch ( $field['type'] ) {
		case 'textarea':
			printf(
				'<textarea id="%1$s" name="%2$s" rows="4" class="large-text">%3$s</textarea>',
				esc_attr( $key ),
				esc_attr( $name ),
				esc_textarea( $value )
			);
			break;

		case 'media':
			$image_html = $value ? wp_get_attachment_image( $value, 'thumbnail' ) : '';
			printf(
				'<div class="brittos-media-field">
					<input type="hidden" class="brittos-media-field__id" id="%1$s" name="%2$s" value="%3$s">
					<div class="brittos-media-field__preview">%4$s</div>
					<button type="button" class="button brittos-media-field__select">%5$s</button>
					<button type="button" class="button brittos-media-field__remove" %6$s>%7$s</button>
				</div>',
				esc_attr( $key ),
				esc_attr( $name ),
				esc_attr( $value ),
				wp_kses_post( $image_html ),
				esc_html__( 'Select Image', 'brittos-core' ),
				$value ? '' : 'style="display:none"',
				esc_html__( 'Remove', 'brittos-core' )
			);
			break;

		case 'gallery':
			$ids = is_array( $value ) ? $value : array();
			$previews = '';
			foreach ( $ids as $id ) {
				$previews .= wp_get_attachment_image( $id, 'thumbnail' );
			}
			printf(
				'<div class="brittos-gallery-field">
					<input type="hidden" class="brittos-gallery-field__ids" id="%1$s" name="%2$s" value="%3$s">
					<div class="brittos-gallery-field__preview">%4$s</div>
					<button type="button" class="button brittos-gallery-field__select">%5$s</button>
				</div>',
				esc_attr( $key ),
				esc_attr( $name ),
				esc_attr( implode( ',', $ids ) ),
				wp_kses_post( $previews ),
				esc_html__( 'Select Images', 'brittos-core' )
			);
			break;

		default:
			printf(
				'<input type="%1$s" id="%2$s" name="%3$s" value="%4$s" class="regular-text">',
				esc_attr( $field['type'] ),
				esc_attr( $key ),
				esc_attr( $name ),
				esc_attr( $value )
			);
	}

	if ( ! empty( $field['help'] ) ) {
		printf( '<p class="description">%s</p>', esc_html( $field['help'] ) );
	}
}

/**
 * Render the settings page wrapper.
 */
function brittos_core_render_clinic_settings_page() {
	if ( ! brittos_core_current_user_can_manage() ) {
		return;
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Clinic Info', 'brittos-core' ); ?></h1>
		<p><?php esc_html_e( 'This information powers the header, footer, structured data and appointment notifications across the site. Only enter details that are true and current.', 'brittos-core' ); ?></p>
		<form action="options.php" method="post">
			<?php
			settings_fields( 'brittos_core_clinic_group' );
			do_settings_sections( 'brittos-clinic-info' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

/**
 * Enqueue the WordPress media library on the clinic settings screen only.
 *
 * @param string $hook Current admin page hook.
 */
function brittos_core_clinic_settings_assets( $hook ) {
	if ( 'settings_page_brittos-clinic-info' !== $hook ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script(
		'brittos-core-clinic-fields',
		BRITTOS_CORE_URL . 'assets/admin-clinic-fields.js',
		array( 'jquery' ),
		BRITTOS_CORE_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'brittos_core_clinic_settings_assets' );
