<?php
/**
 * Structured meta fields for the `treatment` post type: short description,
 * benefits, process steps, related FAQs and an optional custom CTA.
 * Stored as individual post meta keys, each with its own sanitization.
 *
 * @package Brittos_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BRITTOS_CORE_TREATMENT_NONCE', 'brittos_core_treatment_nonce' );

/**
 * Read one structured treatment field.
 *
 * @param int    $post_id Treatment post ID.
 * @param string $key     One of: short_description, benefits, process, faq_ids, cta_text, cta_url.
 * @return mixed
 */
function brittos_core_get_treatment_field( $post_id, $key ) {
	$meta_key = 'brittos_treatment_' . $key;
	$value    = get_post_meta( $post_id, $meta_key, true );

	if ( in_array( $key, array( 'benefits', 'process' ), true ) ) {
		return is_array( $value ) ? $value : array();
	}

	if ( 'faq_ids' === $key ) {
		return is_array( $value ) ? array_map( 'absint', $value ) : array();
	}

	return is_string( $value ) ? $value : '';
}

/**
 * Register the meta box.
 */
function brittos_core_add_treatment_meta_box() {
	add_meta_box(
		'brittos_treatment_details',
		__( 'Treatment Details', 'brittos-core' ),
		'brittos_core_render_treatment_meta_box',
		'treatment',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'brittos_core_add_treatment_meta_box' );

/**
 * Render the meta box fields.
 *
 * @param WP_Post $post Current post object.
 */
function brittos_core_render_treatment_meta_box( $post ) {
	wp_nonce_field( 'brittos_core_save_treatment', BRITTOS_CORE_TREATMENT_NONCE );

	$short_description = brittos_core_get_treatment_field( $post->ID, 'short_description' );
	$benefits           = brittos_core_get_treatment_field( $post->ID, 'benefits' );
	$process            = brittos_core_get_treatment_field( $post->ID, 'process' );
	$faq_ids            = brittos_core_get_treatment_field( $post->ID, 'faq_ids' );
	$cta_text           = brittos_core_get_treatment_field( $post->ID, 'cta_text' );
	$cta_url            = brittos_core_get_treatment_field( $post->ID, 'cta_url' );

	$all_faqs = get_posts( array(
		'post_type'      => 'faq',
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	) );
	?>
	<p>
		<label for="brittos_treatment_short_description"><strong><?php esc_html_e( 'Short Description', 'brittos-core' ); ?></strong></label><br>
		<textarea id="brittos_treatment_short_description" name="brittos_treatment_short_description" rows="2" class="large-text"><?php echo esc_textarea( $short_description ); ?></textarea>
		<span class="description"><?php esc_html_e( 'One or two sentences shown on cards and archive listings.', 'brittos-core' ); ?></span>
	</p>

	<p>
		<label for="brittos_treatment_benefits"><strong><?php esc_html_e( 'Benefits (one per line, optional)', 'brittos-core' ); ?></strong></label><br>
		<textarea id="brittos_treatment_benefits" name="brittos_treatment_benefits" rows="4" class="large-text"><?php echo esc_textarea( brittos_core_array_to_lines( $benefits ) ); ?></textarea>
	</p>

	<p>
		<label for="brittos_treatment_process"><strong><?php esc_html_e( 'What to Expect / Process Steps (one per line, optional)', 'brittos-core' ); ?></strong></label><br>
		<textarea id="brittos_treatment_process" name="brittos_treatment_process" rows="4" class="large-text"><?php echo esc_textarea( brittos_core_array_to_lines( $process ) ); ?></textarea>
	</p>

	<p>
		<strong><?php esc_html_e( 'Related FAQs (optional)', 'brittos-core' ); ?></strong><br>
		<?php if ( $all_faqs ) : ?>
			<?php foreach ( $all_faqs as $faq ) : ?>
				<label style="display:inline-block;margin:2px 12px 2px 0;">
					<input type="checkbox" name="brittos_treatment_faq_ids[]" value="<?php echo esc_attr( $faq->ID ); ?>" <?php checked( in_array( $faq->ID, $faq_ids, true ) ); ?>>
					<?php echo esc_html( get_the_title( $faq ) ); ?>
				</label>
			<?php endforeach; ?>
		<?php else : ?>
			<span class="description"><?php esc_html_e( 'No FAQs have been created yet.', 'brittos-core' ); ?></span>
		<?php endif; ?>
	</p>

	<p>
		<label for="brittos_treatment_cta_text"><strong><?php esc_html_e( 'Custom CTA Label (optional)', 'brittos-core' ); ?></strong></label><br>
		<input type="text" id="brittos_treatment_cta_text" name="brittos_treatment_cta_text" value="<?php echo esc_attr( $cta_text ); ?>" class="regular-text">
	</p>

	<p>
		<label for="brittos_treatment_cta_url"><strong><?php esc_html_e( 'Custom CTA URL (optional)', 'brittos-core' ); ?></strong></label><br>
		<input type="url" id="brittos_treatment_cta_url" name="brittos_treatment_cta_url" value="<?php echo esc_attr( $cta_url ); ?>" class="regular-text">
		<span class="description"><?php esc_html_e( 'Leave blank to use the default "Book an appointment" CTA.', 'brittos-core' ); ?></span>
	</p>
	<?php
}

/**
 * Save the meta box on post save, with full nonce/capability/autosave checks.
 *
 * @param int $post_id Post ID being saved.
 */
function brittos_core_save_treatment_meta( $post_id ) {

	if ( ! isset( $_POST[ BRITTOS_CORE_TREATMENT_NONCE ] )
		|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ BRITTOS_CORE_TREATMENT_NONCE ] ) ), 'brittos_core_save_treatment' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( 'treatment' !== get_post_type( $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['brittos_treatment_short_description'] ) ) {
		update_post_meta(
			$post_id,
			'brittos_treatment_short_description',
			sanitize_textarea_field( wp_unslash( $_POST['brittos_treatment_short_description'] ) )
		);
	}

	if ( isset( $_POST['brittos_treatment_benefits'] ) ) {
		update_post_meta(
			$post_id,
			'brittos_treatment_benefits',
			brittos_core_lines_to_array( wp_unslash( $_POST['brittos_treatment_benefits'] ) )
		);
	}

	if ( isset( $_POST['brittos_treatment_process'] ) ) {
		update_post_meta(
			$post_id,
			'brittos_treatment_process',
			brittos_core_lines_to_array( wp_unslash( $_POST['brittos_treatment_process'] ) )
		);
	}

	$faq_ids = isset( $_POST['brittos_treatment_faq_ids'] ) && is_array( $_POST['brittos_treatment_faq_ids'] )
		? array_map( 'absint', wp_unslash( $_POST['brittos_treatment_faq_ids'] ) )
		: array();
	update_post_meta( $post_id, 'brittos_treatment_faq_ids', $faq_ids );

	if ( isset( $_POST['brittos_treatment_cta_text'] ) ) {
		update_post_meta(
			$post_id,
			'brittos_treatment_cta_text',
			sanitize_text_field( wp_unslash( $_POST['brittos_treatment_cta_text'] ) )
		);
	}

	if ( isset( $_POST['brittos_treatment_cta_url'] ) ) {
		update_post_meta(
			$post_id,
			'brittos_treatment_cta_url',
			esc_url_raw( wp_unslash( $_POST['brittos_treatment_cta_url'] ) )
		);
	}
}
add_action( 'save_post', 'brittos_core_save_treatment_meta' );

/**
 * Structured field for testimonials: the patient display name, kept
 * separate from the WP post author so a clinic can post on their behalf
 * while still crediting the right name (e.g. "Anjali R." for privacy).
 *
 * @param int $post_id Testimonial post ID.
 * @return string
 */
function brittos_core_get_testimonial_field( $post_id, $key ) {
	if ( 'patient_name' === $key ) {
		$value = get_post_meta( $post_id, 'brittos_testimonial_patient_name', true );
		return is_string( $value ) ? $value : '';
	}
	return '';
}

function brittos_core_add_testimonial_meta_box() {
	add_meta_box(
		'brittos_testimonial_details',
		__( 'Testimonial Details', 'brittos-core' ),
		'brittos_core_render_testimonial_meta_box',
		'testimonial',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'brittos_core_add_testimonial_meta_box' );

function brittos_core_render_testimonial_meta_box( $post ) {
	wp_nonce_field( 'brittos_core_save_testimonial', 'brittos_core_testimonial_nonce' );
	$patient_name = brittos_core_get_testimonial_field( $post->ID, 'patient_name' );
	?>
	<p>
		<label for="brittos_testimonial_patient_name"><strong><?php esc_html_e( 'Patient Display Name', 'brittos-core' ); ?></strong></label><br>
		<input type="text" id="brittos_testimonial_patient_name" name="brittos_testimonial_patient_name" value="<?php echo esc_attr( $patient_name ); ?>" class="widefat">
		<span class="description"><?php esc_html_e( 'Shown under the quote, e.g. "Anjali R." Falls back to the post title if left blank.', 'brittos-core' ); ?></span>
	</p>
	<?php
}

function brittos_core_save_testimonial_meta( $post_id ) {
	if ( ! isset( $_POST['brittos_core_testimonial_nonce'] )
		|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['brittos_core_testimonial_nonce'] ) ), 'brittos_core_save_testimonial' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( 'testimonial' !== get_post_type( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( isset( $_POST['brittos_testimonial_patient_name'] ) ) {
		update_post_meta(
			$post_id,
			'brittos_testimonial_patient_name',
			sanitize_text_field( wp_unslash( $_POST['brittos_testimonial_patient_name'] ) )
		);
	}
}
add_action( 'save_post', 'brittos_core_save_testimonial_meta' );
