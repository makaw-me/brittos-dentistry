<?php
/**
 * Before/after image carousel for a single treatment. The complete pair
 * remains available without JavaScript; the enhancement shows one pair at a
 * time and provides keyboard-accessible controls.
 *
 * Expects $treatment_id in scope.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


$treatment_id = isset( $args['treatment_id'] ) ? absint( $args['treatment_id'] ) : get_the_ID();

if ( ! $treatment_id ) {
	return;
}

$pairs = function_exists( 'brittos_core_get_treatment_field' )
	? brittos_core_get_treatment_field( $treatment_id, 'before_after' )
	: array();

if ( empty( $pairs ) ) {
	return;
}

$eyebrow = function_exists( 'brittos_core_get_clinic_field' ) ? brittos_core_get_clinic_field( 'single_before_after_eyebrow' ) : '';
$heading = function_exists( 'brittos_core_get_clinic_field' ) ? brittos_core_get_clinic_field( 'single_before_after_heading' ) : '';
$disclaimer = function_exists( 'brittos_core_get_clinic_field' ) ? brittos_core_get_clinic_field( 'single_before_after_disclaimer' ) : '';
?>
<section class="before-after" aria-labelledby="before-after-heading">
	<div class="container">

		<?php get_template_part( 'template-parts/components/section-heading', null, array(
			'eyebrow' => $eyebrow ? $eyebrow : __( 'Real results', 'brittos-dentistry' ),
			'heading' => $heading ? $heading : __( 'Before &amp; after', 'brittos-dentistry' ),
			'align'   => 'center',
			'id'      => 'before-after-heading',
		) ); ?>

		<div class="before-after__carousel<?php echo count( $pairs ) > 1 ? ' before-after__carousel--has-navigation' : ''; ?>" data-before-after-carousel>
			<div class="before-after__stage">
			<?php if ( count( $pairs ) > 1 ) : ?>
				<div class="before-after__controls before-after__controls--stage" aria-label="<?php esc_attr_e( 'Carousel controls', 'brittos-dentistry' ); ?>">
					<button class="before-after__arrow before-after__arrow--previous" type="button" data-before-after-previous aria-label="<?php esc_attr_e( 'Previous before and after pair', 'brittos-dentistry' ); ?>">
						<svg width="15" height="15" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3.33337 8H12.6667M12.6667 8L8.66671 4M12.6667 8L8.66671 12" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</button>
					<button class="before-after__arrow" type="button" data-before-after-next aria-label="<?php esc_attr_e( 'Next before and after pair', 'brittos-dentistry' ); ?>">
						<svg width="15" height="15" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3.33337 8H12.6667M12.6667 8L8.66671 4M12.6667 8L8.66671 12" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/></svg>
					</button>
				</div>
			<?php endif; ?>
			<div class="before-after__grid" data-before-after-slides>
			<?php foreach ( $pairs as $index => $pair ) : ?>
				<figure id="before-after-slide-<?php echo esc_attr( $treatment_id . '-' . $index ); ?>" class="before-after__pair" data-before-after-slide data-index="<?php echo esc_attr( $index ); ?>" data-reveal data-reveal-group="before-after" aria-roledescription="slide" aria-label="<?php echo esc_attr( sprintf( /* translators: 1: current slide, 2: total slides */ __( 'Pair %1$d of %2$d', 'brittos-dentistry' ), $index + 1, count( $pairs ) ) ); ?>">
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

			<?php if ( count( $pairs ) > 1 ) : ?>
				<div class="before-after__navigation">
					<div class="before-after__controls before-after__controls--navigation" aria-label="<?php esc_attr_e( 'Carousel controls', 'brittos-dentistry' ); ?>">
						<button class="before-after__arrow before-after__arrow--previous" type="button" data-before-after-previous aria-label="<?php esc_attr_e( 'Previous before and after pair', 'brittos-dentistry' ); ?>">
							<svg width="15" height="15" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3.33337 8H12.6667M12.6667 8L8.66671 4M12.6667 8L8.66671 12" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</button>
						<button class="before-after__arrow" type="button" data-before-after-next aria-label="<?php esc_attr_e( 'Next before and after pair', 'brittos-dentistry' ); ?>">
							<svg width="15" height="15" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3.33337 8H12.6667M12.6667 8L8.66671 4M12.6667 8L8.66671 12" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"/></svg>
						</button>
					</div>
					<div class="before-after__indicators">
						<p class="before-after__status" aria-live="polite" data-before-after-status></p>
						<div class="before-after__dots" role="group" aria-label="<?php esc_attr_e( 'Before and after pairs', 'brittos-dentistry' ); ?>">
						<?php foreach ( $pairs as $index => $pair ) : ?>
							<button type="button" class="before-after__dot" data-before-after-dot="<?php echo esc_attr( $index ); ?>" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: pair number */ __( 'Show pair %s', 'brittos-dentistry' ), $index + 1 ) ); ?>" aria-controls="before-after-slide-<?php echo esc_attr( $treatment_id . '-' . $index ); ?>" aria-pressed="<?php echo 0 === $index ? 'true' : 'false'; ?>"></button>
						<?php endforeach; ?>
						</div>
					</div>
				</div>
			<?php endif; ?>
			</div>
		</div>

		<p class="before-after__disclaimer">
			<?php echo esc_html( $disclaimer ? $disclaimer : __( 'Individual results vary. Shown with patient consent.', 'brittos-dentistry' ) ); ?>
		</p>

	</div>
</section>
