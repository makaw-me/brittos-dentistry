<?php
/**
 * About the dentist section. Pulls from clinic fields where available and
 * falls back to clearly-labelled placeholder copy otherwise.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dentist      = brittos_clinic_field( 'dentist_name' );
$credentials  = brittos_clinic_field( 'credentials' );
$about_photo  = brittos_clinic_field( 'dentist_photo_id' );
?>
<section class="about-doctor" aria-labelledby="about-doctor-heading">
	<div class="container about-doctor__inner">

		<div class="about-doctor__media">
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

		<div class="about-doctor__content">
			<?php get_template_part( 'template-parts/components/section-heading', null, array(
				'eyebrow' => __( 'About your dentist', 'brittos-dentistry' ),
				'heading' => $dentist ? $dentist : __( 'A steady, familiar face at every visit', 'brittos-dentistry' ),
				'id'      => 'about-doctor-heading',
			) ); ?>

			<?php if ( $credentials ) : ?>
				<p class="about-doctor__credentials"><?php echo esc_html( $credentials ); ?></p>
			<?php endif; ?>

			<p>
				<?php
				esc_html_e(
					'Every patient is seen personally, start to finish — no hand-offs, no guesswork. Treatment plans are explained plainly, timelines are realistic, and there is never any pressure to say yes on the spot.',
					'brittos-dentistry'
				);
				?>
			</p>
			<p>
				<?php
				esc_html_e(
					'This is a small, independently run clinic by design: fewer chairs, more attention, and the same dentist you saw last time.',
					'brittos-dentistry'
				);
				?>
			</p>
		</div>

	</div>
</section>
