<?php
/**
 * Final appointment CTA. Renders the enquiry form registered by the
 * brittos-core plugin (via `brittos_core_render_appointment_form()`),
 * or a graceful fallback with a phone/email contact if the plugin is
 * inactive.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$phone = brittos_clinic_field( 'phone' );
$email = brittos_clinic_field( 'email' );
?>
<section id="appointment-form" class="final-cta" aria-labelledby="final-cta-heading">
	<div class="container final-cta__inner">

		<?php get_template_part( 'template-parts/components/section-heading', null, array(
			'eyebrow' => __( 'Ready when you are', 'brittos-dentistry' ),
			'heading' => __( 'Request an appointment', 'brittos-dentistry' ),
			'lede'    => __( 'Send a few details and the clinic will get back to you to confirm a time.', 'brittos-dentistry' ),
			'align'   => 'center',
			'id'      => 'final-cta-heading',
		) ); ?>

		<div class="final-cta__form">
			<?php if ( function_exists( 'brittos_core_render_appointment_form' ) ) : ?>
				<?php brittos_core_render_appointment_form(); ?>
			<?php else : ?>
				<p class="empty-state">
					<?php esc_html_e( 'The appointment form is temporarily unavailable.', 'brittos-dentistry' ); ?>
					<?php if ( $phone ) : ?>
						<?php
						printf(
							/* translators: %s: phone number */
							esc_html__( 'Please call %s to book.', 'brittos-dentistry' ),
							esc_html( $phone )
						);
						?>
					<?php elseif ( $email ) : ?>
						<?php
						printf(
							/* translators: %s: email address */
							esc_html__( 'Please email %s to book.', 'brittos-dentistry' ),
							esc_html( $email )
						);
						?>
					<?php endif; ?>
				</p>
			<?php endif; ?>
		</div>

	</div>
</section>
