<?php
/**
 * Structured meta field for the `faq` post type: which treatment(s) a
 * given FAQ applies to. Ownership of this many-to-many relationship
 * lives here (on the FAQ) rather than on the treatment, so one FAQ can
 * be tagged against several treatments — and a general/site-wide FAQ
 * simply has none selected.
 *
 * Stored as repeating post meta rows (one `brittos_faq_related_treatment_id`
 * row per selected treatment) rather than a single serialized array, so
 * it can be queried directly via meta_query without LIKE-matching a
 * serialized string.
 *
 * @package Brittos_Core
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BRITTOS_CORE_FAQ_NONCE', 'brittos_core_faq_nonce' );

/**
 * Read a structured FAQ field.
 *
 * @param int    $post_id FAQ post ID.
 * @param string $key     Supported keys: related_treatments, show_on_home.
 * @return array
 */
function brittos_core_get_faq_field( $post_id, $key ) {
	if ( 'related_treatments' === $key ) {
		$ids = get_post_meta( $post_id, 'brittos_faq_related_treatment_id' );
		return array_map( 'absint', (array) $ids );
	}
	if ( 'show_on_home' === $key ) {
		$value = get_post_meta( $post_id, 'brittos_faq_show_on_home', true );
		return '' === $value ? '1' : ( '1' === $value ? '1' : '' );
	}
	return array();
}

function brittos_core_add_faq_meta_box() {
	add_meta_box(
		'brittos_faq_related_treatments',
		__( 'Related Treatments', 'brittos-core' ),
		'brittos_core_render_faq_meta_box',
		'faq',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'brittos_core_add_faq_meta_box' );

/**
 * Render the checkbox list of treatments this FAQ applies to.
 *
 * @param WP_Post $post Current FAQ post object.
 */
function brittos_core_render_faq_meta_box( $post ) {
	wp_nonce_field( 'brittos_core_save_faq', BRITTOS_CORE_FAQ_NONCE );

	$related = brittos_core_get_faq_field( $post->ID, 'related_treatments' );
	$show_on_home = brittos_core_get_faq_field( $post->ID, 'show_on_home' );

	$treatments = get_posts( array(
		'post_type'      => 'treatment',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'post_status'    => array( 'publish', 'draft', 'pending' ),
	) );
	?>
	<p class="description">
		<?php esc_html_e( 'Leave all unchecked for a general FAQ shown site-wide. Check one or more to also show it on those specific treatment pages.', 'brittos-core' ); ?>
	</p>
	<p>
		<label>
			<input type="checkbox" name="brittos_faq_show_on_home" value="1" <?php checked( '1', $show_on_home ); ?>>
			<strong><?php esc_html_e( 'Show this FAQ on the homepage', 'brittos-core' ); ?></strong>
		</label>
	</p>
	<?php if ( $treatments ) : ?>
		<div style="max-height:220px;overflow-y:auto;">
			<?php foreach ( $treatments as $treatment ) : ?>
				<label style="display:block;margin:2px 0;">
					<input type="checkbox" name="brittos_faq_related_treatments[]" value="<?php echo esc_attr( $treatment->ID ); ?>" <?php checked( in_array( $treatment->ID, $related, true ) ); ?>>
					<?php echo esc_html( get_the_title( $treatment ) ); ?>
				</label>
			<?php endforeach; ?>
		</div>
	<?php else : ?>
		<span class="description"><?php esc_html_e( 'No treatments have been created yet.', 'brittos-core' ); ?></span>
	<?php endif; ?>
	<?php
}

/**
 * Save the related-treatments checkboxes as repeating meta rows.
 *
 * @param int $post_id FAQ post ID being saved.
 */
function brittos_core_save_faq_meta( $post_id ) {
	if ( ! isset( $_POST[ BRITTOS_CORE_FAQ_NONCE ] )
		|| ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ BRITTOS_CORE_FAQ_NONCE ] ) ), 'brittos_core_save_faq' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( 'faq' !== get_post_type( $post_id ) || ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$submitted = isset( $_POST['brittos_faq_related_treatments'] ) && is_array( $_POST['brittos_faq_related_treatments'] )
		? array_unique( array_map( 'absint', wp_unslash( $_POST['brittos_faq_related_treatments'] ) ) )
		: array();

	// Replace the full set of repeating rows rather than diffing, since
	// the checkbox list always represents the complete desired state.
	delete_post_meta( $post_id, 'brittos_faq_related_treatment_id' );
	foreach ( $submitted as $treatment_id ) {
		if ( $treatment_id && 'treatment' === get_post_type( $treatment_id ) ) {
			add_post_meta( $post_id, 'brittos_faq_related_treatment_id', $treatment_id, false );
		}
	}

	update_post_meta(
		$post_id,
		'brittos_faq_show_on_home',
		! empty( $_POST['brittos_faq_show_on_home'] ) ? '1' : ''
	);
}
add_action( 'save_post', 'brittos_core_save_faq_meta' );
