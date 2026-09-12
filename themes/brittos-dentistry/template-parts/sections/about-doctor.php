<?php
/**
 * About the dentist section. Pulls from clinic fields where available and
 * falls back to clearly-labelled placeholder copy otherwise.
 * Fully configurable from Settings > Clinic Info.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dentist      = brittos_clinic_field( 'dentist_name' );
$credentials  = brittos_clinic_field( 'credentials' );
$about_photo  = brittos_clinic_field( 'dentist_photo_id' );

$eyebrow      = brittos_clinic_field( 'about_eyebrow', __( 'About your dentist', 'brittos-dentistry' ) );
$heading      = brittos_clinic_field( 'about_heading' );
if ( ! $heading ) {
	$heading = $dentist ? $dentist : __( 'A steady, familiar face at every visit', 'brittos-dentistry' );
}

$para_1       = brittos_clinic_field(
	'about_para_1',
	__( 'Every patient is seen personally, start to finish — no hand-offs, no guesswork. Treatment plans are explained plainly, timelines are realistic, and there is never any pressure to say yes on the spot.', 'brittos-dentistry' )
);

$para_2       = brittos_clinic_field(
	'about_para_2',
	__( 'This is a small, independently run clinic by design: fewer chairs, more attention, and the same dentist you saw last time.', 'brittos-dentistry' )
);
?>
<section class="about-doctor" aria-labelledby="about-doctor-heading">
	<div class="container about-doctor__inner">

		<div class="about-doctor__media" data-reveal>
			<?php if ( $about_photo && wp_attachment_is_image( $about_photo ) ) : ?>
				<?php echo wp_get_attachment_image( $about_photo, 'brittos-card', false, array(
					'class'    => 'about-doctor__image',
					'loading'  => 'lazy',
					'decoding' => 'async',
					'alt'      => esc_attr( $dentist ? $dentist : __( 'The dentist', 'brittos-dentistry' ) ),
				) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<div class="about-doctor__image about-doctor__image--placeholder" aria-hidden="true"></div>
			<?php endif; ?>
		</div>

		<div class="about-doctor__content" data-reveal>
			<?php get_template_part( 'template-parts/components/section-heading', null, array(
				'eyebrow' => $eyebrow,
				'heading' => $heading,
				'id'      => 'about-doctor-heading',
			) ); ?>

			<?php if ( $credentials ) : ?>
				<p class="about-doctor__credentials"><?php echo esc_html( $credentials ); ?></p>
			<?php endif; ?>

			<?php if ( $para_1 ) : ?>
				<p><?php echo wp_kses_post( nl2br( $para_1 ) ); ?></p>
			<?php endif; ?>

			<?php if ( $para_2 ) : ?>
				<p><?php echo wp_kses_post( nl2br( $para_2 ) ); ?></p>
			<?php endif; ?>
		</div>

	</div>
</section>
