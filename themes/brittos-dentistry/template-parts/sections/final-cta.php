<?php
/**
 * Final CTA banner. Deliberately a slim prompt — not an embedded form —
 * so the full appointment form lives in exactly one place (the
 * Contact/Book Appointment page). Every "Book an appointment" surface
 * across the site points to the same booking URL, so nothing gets
 * out of sync. Fully configurable from Settings > Clinic Info.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow = brittos_clinic_field( 'cta_eyebrow', __( 'Ready when you are', 'brittos-dentistry' ) );
$heading = brittos_clinic_field( 'cta_heading', __( 'Request an appointment', 'brittos-dentistry' ) );
$lede    = brittos_clinic_field( 'cta_lede', __( 'Send a few details and the clinic will get back to you to confirm a time.', 'brittos-dentistry' ) );

$phone        = brittos_clinic_field( 'phone' );
$booking_url  = function_exists( 'brittos_core_get_booking_url' ) ? brittos_core_get_booking_url() : home_url( '/' );
?>
<section class="final-cta" aria-labelledby="final-cta-heading">
	<div class="final-cta__glow" aria-hidden="true"></div>
	<div class="container final-cta__inner">

		<?php get_template_part( 'template-parts/components/section-heading', null, array(
			'eyebrow' => $eyebrow,
			'heading' => $heading,
			'lede'    => $lede,
			'align'   => 'center',
			'id'      => 'final-cta-heading',
		) ); ?>

		<div class="final-cta__actions" data-reveal>
			<?php brittos_button( array(
				'text'  => __( 'Book an appointment', 'brittos-dentistry' ),
				'url'   => $booking_url,
				'style' => 'primary',
				'icon'  => 'arrow',
			) ); ?>

			<?php if ( $phone ) : ?>
				<a class="button button--ghost" href="<?php echo esc_url( brittos_tel_href( $phone ) ); ?>">
					<span class="button__text"><?php echo esc_html( $phone ); ?></span>
				</a>
			<?php endif; ?>
		</div>

	</div>
</section>
