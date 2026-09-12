<?php
/**
 * Structured meta fields for the `treatment` post type: short description,
 * benefits, process steps, quick facts, a before/after gallery and an
 * optional custom CTA. Related FAQs are now owned by the FAQ side of the
 * relationship (see includes/fields/faq-fields.php), so a content editor
 * can tag one FAQ against several treatments from a single screen.
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
 * @param string $key     One of: short_description, benefits, process, facts, before_after, hero_overlay, cta_text, cta_url.
 * @return mixed
 */
function brittos_core_get_treatment_field( $post_id, $key ) {
	$meta_key = 'brittos_treatment_' . $key;
	$value    = get_post_meta( $post_id, $meta_key, true );

	if ( in_array( $key, array( 'benefits', 'process', 'facts', 'before_after' ), true ) ) {
		return is_array( $value ) ? $value : array();
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
	$facts              = brittos_core_get_treatment_field( $post->ID, 'facts' );
	$before_after       = brittos_core_get_treatment_field( $post->ID, 'before_after' );
	$hero_overlay       = brittos_core_get_treatment_field( $post->ID, 'hero_overlay' );
	$cta_text           = brittos_core_get_treatment_field( $post->ID, 'cta_text' );
	$cta_url            = brittos_core_get_treatment_field( $post->ID, 'cta_url' );
	?>
	<p>
		<label for="brittos_treatment_short_description"><strong><?php esc_html_e( 'Short Description', 'brittos-core' ); ?></strong></label><br>
		<textarea id="brittos_treatment_short_description" name="brittos_treatment_short_description" rows="2" class="large-text"><?php echo esc_textarea( $short_description ); ?></textarea>
		<span class="description"><?php esc_html_e( 'One or two sentences shown on cards, archive listings and the treatment hero.', 'brittos-core' ); ?></span>
	</p>

	<p>
		<label for="brittos_treatment_facts"><strong><?php esc_html_e( 'Quick Facts (one per line, "Label: Value")', 'brittos-core' ); ?></strong></label><br>
		<textarea id="brittos_treatment_facts" name="brittos_treatment_facts" rows="4" class="large-text" placeholder="<?php echo esc_attr( "Visits required: 1–2\nSession duration: 45 minutes\nRecovery time: 24–48 hours" ); ?>"><?php echo esc_textarea( brittos_core_facts_array_to_lines( $facts ) ); ?></textarea>
		<span class="description"><?php esc_html_e( 'Shown as a quick-facts strip on the treatment page. Only enter details that are actually true for this treatment.', 'brittos-core' ); ?></span>
	</p>

	<p>
		<label for="brittos_treatment_benefits"><strong><?php esc_html_e( 'Benefits (one per line, optional)', 'brittos-core' ); ?></strong></label><br>
		<textarea id="brittos_treatment_benefits" name="brittos_treatment_benefits" rows="4" class="large-text"><?php echo esc_textarea( brittos_core_array_to_lines( $benefits ) ); ?></textarea>
	</p>

	<p>
		<label for="brittos_treatment_process"><strong><?php esc_html_e( 'Treatment Journey / Procedure Steps (one per line, optional)', 'brittos-core' ); ?></strong></label><br>
		<textarea id="brittos_treatment_process" name="brittos_treatment_process" rows="4" class="large-text" placeholder="<?php echo esc_attr( "Initial consultation and X-ray\nLocal anaesthetic and preparation\nProcedure carried out\nFollow-up check" ); ?>"><?php echo esc_textarea( brittos_core_array_to_lines( $process ) ); ?></textarea>
		<span class="description"><?php esc_html_e( 'The actual clinical steps a patient goes through for this specific procedure — not generic reassurance text.', 'brittos-core' ); ?></span>
	</p>

	<div class="brittos-before-after-field">
		<p><strong><?php esc_html_e( 'Before &amp; After Gallery (optional)', 'brittos-core' ); ?></strong></p>
		<input
			type="hidden"
			id="brittos_treatment_before_after"
			name="brittos_treatment_before_after"
			value="<?php echo esc_attr( wp_json_encode( array_values( $before_after ) ) ); ?>"
		>
		<div class="brittos-before-after-field__rows"></div>
		<button type="button" class="button brittos-before-after-field__add"><?php esc_html_e( 'Add before/after pair', 'brittos-core' ); ?></button>
		<p class="description"><?php esc_html_e( 'Only publish images the patient has consented to share.', 'brittos-core' ); ?></p>
	</div>

	<p>
		<label>
			<input type="checkbox" name="brittos_treatment_hero_overlay" value="1" <?php checked( '1', $hero_overlay ); ?>>
			<strong><?php esc_html_e( 'Use featured image as an optional full-bleed hero background with a dark overlay', 'brittos-core' ); ?></strong>
		</label><br>
		<span class="description"><?php esc_html_e( 'Off by default (the featured image shows as a card beside the text instead). Requires a featured image to be set below.', 'brittos-core' ); ?></span>
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

	if ( isset( $_POST['brittos_treatment_facts'] ) ) {
		update_post_meta(
			$post_id,
			'brittos_treatment_facts',
			brittos_core_facts_lines_to_array( wp_unslash( $_POST['brittos_treatment_facts'] ) )
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

	if ( isset( $_POST['brittos_treatment_before_after'] ) ) {
		update_post_meta(
			$post_id,
			'brittos_treatment_before_after',
			brittos_core_sanitize_before_after_json( wp_unslash( $_POST['brittos_treatment_before_after'] ) )
		);
	}

	update_post_meta(
		$post_id,
		'brittos_treatment_hero_overlay',
		! empty( $_POST['brittos_treatment_hero_overlay'] ) ? '1' : ''
	);

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
 * Enqueue the before/after repeater UI only on the treatment edit screen.
 *
 * @param string $hook Current admin page hook.
 */
function brittos_core_treatment_fields_assets( $hook ) {
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	$screen = get_current_screen();
	if ( ! $screen || 'treatment' !== $screen->post_type ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_script(
		'brittos-core-treatment-fields',
		BRITTOS_CORE_URL . 'assets/admin-treatment-fields.js',
		array( 'jquery' ),
		BRITTOS_CORE_VERSION,
		true
	);
}
add_action( 'admin_enqueue_scripts', 'brittos_core_treatment_fields_assets' );

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
