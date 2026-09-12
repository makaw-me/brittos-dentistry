<?php
/**
 * Before/after image gallery for a single treatment. Renders as clear,
 * labelled side-by-side pairs rather than an interactive draggable
 * slider, so it works with zero JavaScript, is fully keyboard/
 * screen-reader accessible, and doesn't cost an extra script dependency.
 *
 * Expects $treatment_id in scope.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( empty( $treatment_id ) ) {
	return;
}

$pairs = function_exists( 'brittos_core_get_treatment_field' )
	? brittos_core_get_treatment_field( $treatment_id, 'before_after' )
	: array();

if ( empty( $pairs ) ) {
	return;
}
?>
<section class="before-after" aria-labelledby="before-after-heading">
	<div class="container">

		<?php get_template_part( 'template-parts/components/section-heading', null, array(
			'eyebrow' => __( 'Real results', 'brittos-dentistry' ),
			'heading' => __( 'Before &amp; after', 'brittos-dentistry' ),
			'align'   => 'center',
			'id'      => 'before-after-heading',
		) ); ?>

		<div class="before-after__grid">
			<?php foreach ( $pairs as $index => $pair ) : ?>
				<figure class="before-after__pair" data-reveal data-reveal-group="before-after">
					<div class="before-after__images">
						<div class="before-after__image-wrap">
							<?php echo wp_get_attachment_image( $pair['before'], 'brittos-card', false, array(
								'class'    => 'before-after__image',
								'loading'  => 'lazy',
								'decoding' => 'async',
								'alt'      => esc_attr(
									$pair['caption']
										? sprintf(
											/* translators: %s: before/after caption */
											__( 'Before: %s', 'brittos-dentistry' ),
											$pair['caption']
										)
										: __( 'Before treatment', 'brittos-dentistry' )
								),
							) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span class="before-after__tag"><?php esc_html_e( 'Before', 'brittos-dentistry' ); ?></span>
						</div>
						<div class="before-after__image-wrap">
							<?php echo wp_get_attachment_image( $pair['after'], 'brittos-card', false, array(
								'class'    => 'before-after__image',
								'loading'  => 'lazy',
								'decoding' => 'async',
								'alt'      => esc_attr(
									$pair['caption']
										? sprintf(
											/* translators: %s: before/after caption */
											__( 'After: %s', 'brittos-dentistry' ),
											$pair['caption']
										)
										: __( 'After treatment', 'brittos-dentistry' )
								),
							) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span class="before-after__tag before-after__tag--after"><?php esc_html_e( 'After', 'brittos-dentistry' ); ?></span>
						</div>
					</div>
					<?php if ( ! empty( $pair['caption'] ) ) : ?>
						<figcaption class="before-after__caption"><?php echo esc_html( $pair['caption'] ); ?></figcaption>
					<?php endif; ?>
				</figure>
			<?php endforeach; ?>
		</div>

		<p class="before-after__disclaimer">
			<?php esc_html_e( 'Individual results vary. Shown with patient consent.', 'brittos-dentistry' ); ?>
		</p>

	</div>
</section>
