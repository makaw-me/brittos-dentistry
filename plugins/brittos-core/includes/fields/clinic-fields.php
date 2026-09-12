<?php
/**
 * Site-wide clinic information and homepage content controls.
 * Features a tabbed administration interface with fallbacks for all sections.
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
	return array_key_exists( $key, $data ) && '' !== $data[ $key ] && null !== $data[ $key ] ? $data[ $key ] : $default;
}

/**
 * Register the settings page under Settings > Clinic Info.
 */
function brittos_core_register_clinic_settings_page() {
	add_options_page(
		__( 'Clinic & Homepage Settings', 'brittos-core' ),
		__( 'Clinic Info', 'brittos-core' ),
		'manage_options',
		'brittos-clinic-info',
		'brittos_core_render_clinic_settings_page'
	);
}
add_action( 'admin_menu', 'brittos_core_register_clinic_settings_page' );

/**
 * Tabs definition for the settings screen.
 *
 * @return array
 */
function brittos_core_clinic_tabs() {
	return array(
		'general'        => __( 'General & Contact', 'brittos-core' ),
		'hero'           => __( 'Hero Section', 'brittos-core' ),
		'trust_strip'    => __( 'Trust Strip', 'brittos-core' ),
		'about_doctor'   => __( 'About Doctor', 'brittos-core' ),
		'why_us'         => __( 'Why Choose Us', 'brittos-core' ),
		'treatments_cta' => __( 'Treatments & CTA', 'brittos-core' ),
		'treatments_hero' => __( 'Treatments Page Hero', 'brittos-core' ),
		'gallery'        => __( 'Gallery', 'brittos-core' ),
	);
}

/**
 * Central definition list for every clinic and homepage field.
 * Organized by tab.
 *
 * @return array
 */
function brittos_core_clinic_field_definitions() {
	return array(
		// TAB: General & Contact
		'clinic_name'           => array( 'tab' => 'general', 'label' => __( 'Clinic Name', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Dr. Britto\'s Dentistry', 'brittos-core' ) ),
		'dentist_name'          => array( 'tab' => 'general', 'label' => __( 'Dentist Name', 'brittos-core' ), 'type' => 'text', 'help' => __( 'e.g. Dr. Britto', 'brittos-core' ) ),
		'credentials'           => array( 'tab' => 'general', 'label' => __( 'Professional Credentials', 'brittos-core' ), 'type' => 'text', 'help' => __( 'e.g. BDS, MDS — Dental Surgeon & Implantologist. Only enter verifiable credentials.', 'brittos-core' ) ),
		'phone'                 => array( 'tab' => 'general', 'label' => __( 'Phone Number', 'brittos-core' ), 'type' => 'text' ),
		'whatsapp_number'        => array( 'tab' => 'general', 'label' => __( 'WhatsApp Number', 'brittos-core' ), 'type' => 'text' ),
		'email'                 => array( 'tab' => 'general', 'label' => __( 'Email Address', 'brittos-core' ), 'type' => 'email' ),
		'address'               => array( 'tab' => 'general', 'label' => __( 'Street Address', 'brittos-core' ), 'type' => 'text' ),
		'city'                  => array( 'tab' => 'general', 'label' => __( 'City', 'brittos-core' ), 'type' => 'text' ),
		'postal_code'           => array( 'tab' => 'general', 'label' => __( 'Postal Code', 'brittos-core' ), 'type' => 'text' ),
		'opening_hours'         => array( 'tab' => 'general', 'label' => __( 'Opening Hours', 'brittos-core' ), 'type' => 'textarea', 'help' => __( 'One line per schedule item, e.g. "Mon–Sat: 9:00 AM – 7:00 PM".', 'brittos-core' ) ),
		'appointment_url'       => array( 'tab' => 'general', 'label' => __( 'External Booking URL (optional)', 'brittos-core' ), 'type' => 'url', 'help' => __( 'If set, this takes priority over the Booking Page below for all CTA buttons.', 'brittos-core' ) ),
		'booking_page_id'       => array( 'tab' => 'general', 'label' => __( 'Booking / Contact Page', 'brittos-core' ), 'type' => 'page', 'help' => __( 'The page "Book an appointment" CTAs link to. Assign the "Contact / Book Appointment" page template to a page, then select it here.', 'brittos-core' ) ),
		'notification_email'    => array( 'tab' => 'general', 'label' => __( 'Appointment Notification Email', 'brittos-core' ), 'type' => 'email', 'help' => __( 'Where enquiries are sent. Defaults to the site admin email if left blank.', 'brittos-core' ) ),

		// TAB: Hero Section
		'hero_badge_text'       => array( 'tab' => 'hero', 'label' => __( 'Badge Text', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Independent Private Dental Practice', 'brittos-core' ) ),
		'hero_title'            => array( 'tab' => 'hero', 'label' => __( 'Hero Main Title / H1', 'brittos-core' ), 'type' => 'textarea', 'help' => __( 'Default: Gentle, unhurried dentistry from someone who knows your name.', 'brittos-core' ) ),
		'hero_lede'             => array( 'tab' => 'hero', 'label' => __( 'Hero Subtitle / Description', 'brittos-core' ), 'type' => 'textarea', 'help' => __( 'Main introductory statement. Fallback uses dentist name if left empty.', 'brittos-core' ) ),
		'hero_cta_primary_text' => array( 'tab' => 'hero', 'label' => __( 'Primary CTA Button Text', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Book an appointment', 'brittos-core' ) ),
		'hero_cta_primary_url'  => array( 'tab' => 'hero', 'label' => __( 'Primary CTA Button URL', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Leave blank to use the Booking/Contact page set under General & Contact.', 'brittos-core' ) ),
		'hero_bg_image_id'      => array( 'tab' => 'hero', 'label' => __( 'Hero Background Image', 'brittos-core' ), 'type' => 'media', 'help' => __( 'Atmospheric background image / video poster.', 'brittos-core' ) ),
		'hero_bg_video_url'     => array( 'tab' => 'hero', 'label' => __( 'Hero Background Video URL', 'brittos-core' ), 'type' => 'video', 'help' => __( 'MP4 / WebM video. Autoplays muted, looped with contrast overlay.', 'brittos-core' ) ),
		'hero_stat1_val'        => array( 'tab' => 'hero', 'label' => __( 'Stat 1 Value', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: 1:1', 'brittos-core' ) ),
		'hero_stat1_label'      => array( 'tab' => 'hero', 'label' => __( 'Stat 1 Label', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Direct Dentist Care', 'brittos-core' ) ),
		'hero_stat2_val'        => array( 'tab' => 'hero', 'label' => __( 'Stat 2 Value', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: 100%', 'brittos-core' ) ),
		'hero_stat2_label'      => array( 'tab' => 'hero', 'label' => __( 'Stat 2 Label', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Transparent Plans', 'brittos-core' ) ),
		'hero_stat3_val'        => array( 'tab' => 'hero', 'label' => __( 'Stat 3 Value', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: 0%', 'brittos-core' ) ),
		'hero_stat3_label'      => array( 'tab' => 'hero', 'label' => __( 'Stat 3 Label', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Rushed Visits', 'brittos-core' ) ),
		'hero_floating_title'   => array( 'tab' => 'hero', 'label' => __( 'Floating Badge Title', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Dedicated Continuity', 'brittos-core' ) ),
		'hero_floating_sub'     => array( 'tab' => 'hero', 'label' => __( 'Floating Badge Subtitle', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Same trusted dentist every visit', 'brittos-core' ) ),

		// TAB: Trust Strip
		'trust_point_1'         => array( 'tab' => 'trust_strip', 'label' => __( 'Trust Point 1', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Thoughtful, unhurried care', 'brittos-core' ) ),
		'trust_point_2'         => array( 'tab' => 'trust_strip', 'label' => __( 'Trust Point 2', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Modern clinical precision', 'brittos-core' ) ),
		'trust_point_3'         => array( 'tab' => 'trust_strip', 'label' => __( 'Trust Point 3', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Upfront transparent pricing', 'brittos-core' ) ),
		'trust_point_4'         => array( 'tab' => 'trust_strip', 'label' => __( 'Trust Point 4', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Calm, comfortable visits', 'brittos-core' ) ),

		// TAB: About Doctor
		'about_eyebrow'         => array( 'tab' => 'about_doctor', 'label' => __( 'Eyebrow', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: About your dentist', 'brittos-core' ) ),
		'about_heading'         => array( 'tab' => 'about_doctor', 'label' => __( 'Heading', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: A steady, familiar face at every visit', 'brittos-core' ) ),
		'dentist_photo_id'      => array( 'tab' => 'about_doctor', 'label' => __( 'Dentist Photo', 'brittos-core' ), 'type' => 'media', 'help' => __( 'Portrait photography of the doctor/clinic.', 'brittos-core' ) ),
		'about_para_1'          => array( 'tab' => 'about_doctor', 'label' => __( 'Bio Paragraph 1', 'brittos-core' ), 'type' => 'textarea', 'help' => __( 'Leave empty for default clinic story copy.', 'brittos-core' ) ),
		'about_para_2'          => array( 'tab' => 'about_doctor', 'label' => __( 'Bio Paragraph 2', 'brittos-core' ), 'type' => 'textarea', 'help' => __( 'Leave empty for default clinic story copy.', 'brittos-core' ) ),

		// TAB: Why Choose Us
		'why_eyebrow'           => array( 'tab' => 'why_us', 'label' => __( 'Eyebrow', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: The Britto Difference', 'brittos-core' ) ),
		'why_heading'           => array( 'tab' => 'why_us', 'label' => __( 'Heading', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Dentistry designed around your comfort and trust', 'brittos-core' ) ),
		'why_lede'              => array( 'tab' => 'why_us', 'label' => __( 'Lede Description', 'brittos-core' ), 'type' => 'textarea', 'help' => __( 'Default: We believe modern dental care should be calm, clinically rigorous, and completely respectful of your time.', 'brittos-core' ) ),
		'why_f1_title'          => array( 'tab' => 'why_us', 'label' => __( 'Pillar 1 Title', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: One dentist, every visit', 'brittos-core' ) ),
		'why_f1_text'           => array( 'tab' => 'why_us', 'label' => __( 'Pillar 1 Description', 'brittos-core' ), 'type' => 'textarea' ),
		'why_f2_title'          => array( 'tab' => 'why_us', 'label' => __( 'Pillar 2 Title', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Unhurried appointments', 'brittos-core' ) ),
		'why_f2_text'           => array( 'tab' => 'why_us', 'label' => __( 'Pillar 2 Description', 'brittos-core' ), 'type' => 'textarea' ),
		'why_f3_title'          => array( 'tab' => 'why_us', 'label' => __( 'Pillar 3 Title', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Transparent treatment plans', 'brittos-core' ) ),
		'why_f3_text'           => array( 'tab' => 'why_us', 'label' => __( 'Pillar 3 Description', 'brittos-core' ), 'type' => 'textarea' ),
		'why_f4_title'          => array( 'tab' => 'why_us', 'label' => __( 'Pillar 4 Title', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Calm, modern environment', 'brittos-core' ) ),
		'why_f4_text'           => array( 'tab' => 'why_us', 'label' => __( 'Pillar 4 Description', 'brittos-core' ), 'type' => 'textarea' ),

		// TAB: Treatments & CTA
		'treatments_eyebrow'    => array( 'tab' => 'treatments_cta', 'label' => __( 'Treatments Eyebrow', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Treatments', 'brittos-core' ) ),
		'treatments_heading'    => array( 'tab' => 'treatments_cta', 'label' => __( 'Treatments Heading', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Care built around what you actually need', 'brittos-core' ) ),
		'treatments_lede'       => array( 'tab' => 'treatments_cta', 'label' => __( 'Treatments Description', 'brittos-core' ), 'type' => 'textarea', 'help' => __( 'Default: A focused range of general and cosmetic treatments — explained clearly, with no upselling.', 'brittos-core' ) ),
		'treatments_btn_text'   => array( 'tab' => 'treatments_cta', 'label' => __( 'Treatments Button Text', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: View all treatments', 'brittos-core' ) ),
		'cta_eyebrow'           => array( 'tab' => 'treatments_cta', 'label' => __( 'Final CTA Eyebrow', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Ready when you are', 'brittos-core' ) ),
		'cta_heading'           => array( 'tab' => 'treatments_cta', 'label' => __( 'Final CTA Heading', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Request an appointment', 'brittos-core' ) ),
		'cta_lede'              => array( 'tab' => 'treatments_cta', 'label' => __( 'Final CTA Description', 'brittos-core' ), 'type' => 'textarea', 'help' => __( 'Default: Send a few details and the clinic will get back to you to confirm a time.', 'brittos-core' ) ),

		'treatments_hero_badge_text' => array( 'tab' => 'treatments_hero', 'label' => __( 'Badge Text', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: Our Treatments', 'brittos-core' ) ),
		'treatments_hero_title'      => array( 'tab' => 'treatments_hero', 'label' => __( 'Hero Title / H1', 'brittos-core' ), 'type' => 'textarea', 'help' => __( 'Default: Care built around what you actually need', 'brittos-core' ) ),
		'treatments_hero_lede'       => array( 'tab' => 'treatments_hero', 'label' => __( 'Hero Description', 'brittos-core' ), 'type' => 'textarea', 'help' => __( 'Default: Explore every treatment we offer, grouped by the kind of care you need.', 'brittos-core' ) ),
		'treatments_hero_bg_image_id' => array( 'tab' => 'treatments_hero', 'label' => __( 'Hero Background Image (optional)', 'brittos-core' ), 'type' => 'media', 'help' => __( 'Leave empty for the plain hero style used elsewhere when no image is set.', 'brittos-core' ) ),

		// TAB: Gallery
		'gallery_eyebrow'       => array( 'tab' => 'gallery', 'label' => __( 'Gallery Eyebrow', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: The clinic', 'brittos-core' ) ),
		'gallery_heading'       => array( 'tab' => 'gallery', 'label' => __( 'Gallery Heading', 'brittos-core' ), 'type' => 'text', 'help' => __( 'Default: A calm space to visit', 'brittos-core' ) ),
		'gallery_ids'           => array( 'tab' => 'gallery', 'label' => __( 'Clinic Gallery Images', 'brittos-core' ), 'type' => 'gallery', 'help' => __( 'Select photos of the clinic, equipment, and treatment spaces.', 'brittos-core' ) ),
	);
}

/**
 * Register the option with the Settings API.
 */
function brittos_core_register_clinic_settings() {
	register_setting( 'brittos_core_clinic_group', BRITTOS_CORE_CLINIC_OPTION, array(
		'type'              => 'array',
		'sanitize_callback' => 'brittos_core_sanitize_clinic_fields',
		'default'           => array(),
	) );
}
add_action( 'admin_init', 'brittos_core_register_clinic_settings' );

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
			case 'video':
				$clean[ $key ] = esc_url_raw( $raw );
				break;
			case 'textarea':
				$clean[ $key ] = sanitize_textarea_field( $raw );
				break;
			case 'media':
				$clean[ $key ] = absint( $raw );
				break;
			case 'page':
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
 * @param string $key Field identifier.
 * @param array  $field Field definition.
 */
function brittos_core_render_single_field( $key, $field ) {
	$value = brittos_core_get_clinic_field( $key, '' );
	$name  = BRITTOS_CORE_CLINIC_OPTION . '[' . $key . ']';
	?>
	<tr>
		<th scope="row">
			<label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field['label'] ); ?></label>
		</th>
		<td>
			<?php
			switch ( $field['type'] ) {
				case 'textarea':
					printf(
						'<textarea id="%1$s" name="%2$s" rows="3" class="large-text">%3$s</textarea>',
						esc_attr( $key ),
						esc_attr( $name ),
						esc_textarea( $value )
					);
					break;

				case 'video':
					printf(
						'<div class="brittos-video-field">
							<input type="url" class="regular-text brittos-video-field__url" id="%1$s" name="%2$s" value="%3$s" placeholder="https://.../video.mp4">
							<button type="button" class="button brittos-video-field__select">%4$s</button>
						</div>',
						esc_attr( $key ),
						esc_attr( $name ),
						esc_url( $value ),
						esc_html__( 'Choose Video', 'brittos-core' )
					);
					break;

				case 'media':
					$image_html = $value ? wp_get_attachment_image( $value, 'medium', false, array( 'style' => 'max-width:180px;height:auto;border-radius:4px;border:1px solid #ccc;' ) ) : '';
					printf(
						'<div class="brittos-media-field">
							<input type="hidden" class="brittos-media-field__id" id="%1$s" name="%2$s" value="%3$s">
							<div class="brittos-media-field__preview" style="margin-bottom:8px;">%4$s</div>
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

				case 'page':
					echo '<select id="' . esc_attr( $key ) . '" name="' . esc_attr( $name ) . '">';
					echo '<option value="0">' . esc_html__( '— Select a page —', 'brittos-core' ) . '</option>';
					$pages = get_pages( array( 'sort_column' => 'post_title' ) );
					foreach ( $pages as $page_option ) {
						printf(
							'<option value="%1$d" %2$s>%3$s</option>',
							esc_attr( $page_option->ID ),
							selected( absint( $value ), $page_option->ID, false ),
							esc_html( $page_option->post_title )
						);
					}
					echo '</select>';
					break;

				case 'gallery':
					$ids = is_array( $value ) ? $value : array();
					$previews = '';
					foreach ( $ids as $id ) {
						$previews .= wp_get_attachment_image( $id, 'thumbnail', false, array( 'style' => 'max-width:80px;height:auto;margin:3px;border-radius:4px;border:1px solid #ccc;' ) );
					}
					printf(
						'<div class="brittos-gallery-field">
							<input type="hidden" class="brittos-gallery-field__ids" id="%1$s" name="%2$s" value="%3$s">
							<div class="brittos-gallery-field__preview" style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:8px;">%4$s</div>
							<button type="button" class="button brittos-gallery-field__select">%5$s</button>
							<button type="button" class="button brittos-gallery-field__clear" %6$s>%7$s</button>
						</div>',
						esc_attr( $key ),
						esc_attr( $name ),
						esc_attr( implode( ',', $ids ) ),
						wp_kses_post( $previews ),
						esc_html__( 'Select Gallery Images', 'brittos-core' ),
						! empty( $ids ) ? '' : 'style="display:none"',
						esc_html__( 'Clear Gallery', 'brittos-core' )
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
			?>
		</td>
	</tr>
	<?php
}

/**
 * Render the settings page wrapper with responsive tabs.
 */
function brittos_core_render_clinic_settings_page() {
	if ( ! brittos_core_current_user_can_manage() ) {
		return;
	}

	$tabs        = brittos_core_clinic_tabs();
	$fields      = brittos_core_clinic_field_definitions();
	$active_tab  = isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], $tabs ) ? sanitize_key( $_GET['tab'] ) : 'general';
	?>
	<div class="wrap brittos-settings-wrap">
		<h1><?php esc_html_e( 'Clinic & Homepage Settings', 'brittos-core' ); ?></h1>
		<p class="description">
			<?php esc_html_e( 'Customize all clinic information, headlines, copy, trust metrics, images, and background videos across the site. All fields have polished fallbacks if left blank.', 'brittos-core' ); ?>
		</p>

		<h2 class="nav-tab-wrapper" style="margin-bottom: 20px;">
			<?php foreach ( $tabs as $tab_key => $tab_label ) : ?>
				<a
					href="#tab-<?php echo esc_attr( $tab_key ); ?>"
					class="nav-tab <?php echo $tab_key === $active_tab ? 'nav-tab-active' : ''; ?> brittos-tab-trigger"
					data-tab="<?php echo esc_attr( $tab_key ); ?>"
				>
					<?php echo esc_html( $tab_label ); ?>
				</a>
			<?php endforeach; ?>
		</h2>

		<form action="options.php" method="post">
			<?php settings_fields( 'brittos_core_clinic_group' ); ?>

			<?php foreach ( $tabs as $tab_key => $tab_label ) : ?>
				<div
					id="tab-<?php echo esc_attr( $tab_key ); ?>"
					class="brittos-tab-panel <?php echo $tab_key === $active_tab ? 'is-active' : ''; ?>"
					style="<?php echo $tab_key === $active_tab ? '' : 'display:none;'; ?>"
				>
					<table class="form-table" role="presentation">
						<tbody>
							<?php
							foreach ( $fields as $field_key => $field ) {
								if ( $field['tab'] === $tab_key ) {
									brittos_core_render_single_field( $field_key, $field );
								}
							}
							?>
						</tbody>
					</table>
				</div>
			<?php endforeach; ?>

			<?php submit_button( __( 'Save All Changes', 'brittos-core' ) ); ?>
		</form>
	</div>
	<?php
}

/**
 * Enqueue the WordPress media library and settings scripts.
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

/**
 * The URL every "Book an appointment" CTA across the site should point
 * to. Priority: an explicit external booking URL (e.g. a third-party
 * scheduling tool), then the assigned Booking/Contact page, then a
 * same-page anchor fallback so nothing ever links nowhere.
 *
 * @return string
 */
function brittos_core_get_booking_url() {
	$external = brittos_core_get_clinic_field( 'appointment_url' );
	if ( $external ) {
		return $external;
	}

	$page_id = absint( brittos_core_get_clinic_field( 'booking_page_id' ) );
	if ( $page_id && 'publish' === get_post_status( $page_id ) ) {
		return get_permalink( $page_id );
	}

	return home_url( '/#appointment-form' );
}
