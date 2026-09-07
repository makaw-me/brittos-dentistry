<?php
/**
 * Clinic gallery. Reads an array of attachment IDs from the
 * `brittos_gallery_ids` clinic option (managed in the core plugin's
 * clinic settings screen). Renders nothing if empty.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$gallery_ids = brittos_clinic_field( 'gallery_ids', array() );
if ( is_string( $gallery_ids ) ) {
	$gallery_ids = array_filter( array_map( 'absint', explode( ',', $gallery_ids ) ) );
}

if ( empty( $gallery_ids ) || ! is_array( $gallery_ids ) ) {
	return;
}
?>
<section class="gallery" aria-labelledby="gallery-heading">
	<div class="container">

		<?php get_template_part( 'template-parts/components/section-heading', null, array(
			'eyebrow' => __( 'The clinic', 'brittos-dentistry' ),
			'heading' => __( 'A calm space to visit', 'brittos-dentistry' ),
			'align'   => 'center',
			'id'      => 'gallery-heading',
		) ); ?>

		<ul class="gallery__grid">
			<?php foreach ( $gallery_ids as $attachment_id ) : ?>
				<?php if ( wp_attachment_is_image( $attachment_id ) ) : ?>
					<li class="gallery__item">
						<?php echo wp_get_attachment_image( $attachment_id, 'brittos-card', false, array(
							'class'    => 'gallery__image',
							'loading'  => 'lazy',
							'decoding' => 'async',
						) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>

	</div>
</section>
