<?php
/**
 * Template Name: Contact / Book Appointment
 *
 * The single home for the appointment enquiry form. Every "Book an
 * appointment" CTA across the site (hero, mobile bar, treatment pages,
 * final CTA banners) points here via brittos_core_get_booking_url(),
 * so the form itself only has to be built — and maintained — once.
 *
 * To use: create a Page in wp-admin, assign this template under Page
 * Attributes, then select that page as the "Booking / Contact Page" in
 * Settings > Clinic Info so every CTA links to it automatically.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$phone         = brittos_clinic_field( 'phone' );
$whatsapp      = brittos_clinic_field( 'whatsapp_number' );
$email         = brittos_clinic_field( 'email' );
$address       = brittos_clinic_field( 'address' );
$city          = brittos_clinic_field( 'city' );
$opening_hours = brittos_clinic_field( 'opening_hours' );

while ( have_posts() ) :
	the_post();
	?>

	<section class="contact-hero">
		<div class="container">
			<?php get_template_part( 'template-parts/components/section-heading', null, array(
				'eyebrow' => __( 'Get in touch', 'brittos-dentistry' ),
				'heading' => get_the_title() ? get_the_title() : __( 'Book your appointment', 'brittos-dentistry' ),
				'lede'    => __( 'Share a few details below and the clinic will confirm a time that works for you.', 'brittos-dentistry' ),
				'align'   => 'center',
			) ); ?>
		</div>
	</section>

	<div class="container contact-layout">

		<aside class="contact-info" data-reveal>

			<?php if ( $phone || $whatsapp || $email ) : ?>
				<div class="contact-info__card">
					<?php if ( $phone ) : ?>
						<div class="contact-info__row">
							<span class="contact-info__icon" aria-hidden="true">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
							</span>
							<div>
								<p class="contact-info__label"><?php esc_html_e( 'Phone', 'brittos-dentistry' ); ?></p>
								<p class="contact-info__value"><a href="<?php echo esc_url( brittos_tel_href( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( $whatsapp && function_exists( 'brittos_whatsapp_href' ) ) : ?>
						<div class="contact-info__row">
							<span class="contact-info__icon" aria-hidden="true">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
							</span>
							<div>
								<p class="contact-info__label"><?php esc_html_e( 'WhatsApp', 'brittos-dentistry' ); ?></p>
								<p class="contact-info__value"><a href="<?php echo esc_url( brittos_whatsapp_href( $whatsapp ) ); ?>"><?php echo esc_html( $whatsapp ); ?></a></p>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( $email ) : ?>
						<div class="contact-info__row">
							<span class="contact-info__icon" aria-hidden="true">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z" fill="none" stroke="none"></path><path d="M22 6l-10 7L2 6"></path><rect x="2" y="4" width="20" height="16" rx="2"></rect></svg>
							</span>
							<div>
								<p class="contact-info__label"><?php esc_html_e( 'Email', 'brittos-dentistry' ); ?></p>
								<p class="contact-info__value"><a href="<?php echo esc_url( 'mailto:' . antispambot( $email ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a></p>
							</div>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $address || $city || $opening_hours ) : ?>
				<div class="contact-info__card">
					<?php if ( $address || $city ) : ?>
						<div class="contact-info__row">
							<span class="contact-info__icon" aria-hidden="true">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
							</span>
							<div>
								<p class="contact-info__label"><?php esc_html_e( 'Address', 'brittos-dentistry' ); ?></p>
								<p class="contact-info__value"><?php echo esc_html( trim( $address . ( $address && $city ? ', ' : '' ) . $city ) ); ?></p>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( $opening_hours ) : ?>
						<div class="contact-info__row">
							<span class="contact-info__icon" aria-hidden="true">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
							</span>
							<div>
								<p class="contact-info__label"><?php esc_html_e( 'Hours', 'brittos-dentistry' ); ?></p>
								<p class="contact-info__value"><?php echo wp_kses_post( nl2br( esc_html( $opening_hours ) ) ); ?></p>
							</div>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( trim( get_the_content() ) !== '' ) : ?>
				<div class="contact-info__card contact-info__editorial">
					<?php the_content(); ?>
				</div>
			<?php endif; ?>

		</aside>

		<div id="appointment-form" class="contact-form-card" data-reveal>
			<?php if ( function_exists( 'brittos_core_render_appointment_form' ) ) : ?>
				<?php brittos_core_render_appointment_form(); ?>
				<p class="contact-form-card__required-note">
					<?php esc_html_e( '* Required fields', 'brittos-dentistry' ); ?>
				</p>
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
					<?php endif; ?>
				</p>
			<?php endif; ?>
		</div>

	</div>

<?php endwhile; ?>

<?php get_footer(); ?>
