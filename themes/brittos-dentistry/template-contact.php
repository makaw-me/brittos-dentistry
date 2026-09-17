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
$state         = brittos_clinic_field( 'state' );
$postal_code   = brittos_clinic_field( 'postal_code' );
$opening_hours = brittos_clinic_field( 'opening_hours' );
$state_postal  = trim( $state . ' ' . $postal_code );
$full_address  = implode( ', ', array_filter( array( $address, $city, $state_postal ) ) );
$contact_settings = function_exists( 'brittos_core_get_clinic_field' ) ? brittos_core_get_clinic_field() : array();
$contact_settings = is_array( $contact_settings ) ? $contact_settings : array();
$contact_text = static function ( $key, $default ) use ( $contact_settings ) {
	return array_key_exists( $key, $contact_settings ) ? $contact_settings[ $key ] : $default;
};
$contact_hero_eyebrow = $contact_text( 'contact_hero_eyebrow', __( 'Get in touch', 'brittos-dentistry' ) );
$contact_hero_lede    = $contact_text( 'contact_hero_lede', __( 'Share a few details below and the clinic will confirm a time that works for you.', 'brittos-dentistry' ) );

while ( have_posts() ) :
	the_post();
	?>

	<section class="contact-hero">
		<div class="container">
			<?php get_template_part( 'template-parts/components/section-heading', null, array(
				'eyebrow' => $contact_hero_eyebrow,
				'heading' => get_the_title() ? get_the_title() : __( 'Book your appointment', 'brittos-dentistry' ),
				'lede'    => $contact_hero_lede,
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
								<p class="contact-info__label"><?php echo esc_html( $contact_text( 'contact_phone_label', __( 'Phone', 'brittos-dentistry' ) ) ); ?></p>
								<p class="contact-info__value"><a href="<?php echo esc_url( brittos_tel_href( $phone ) ); ?>"><?php echo esc_html( $phone ); ?></a></p>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( $whatsapp && function_exists( 'brittos_whatsapp_href' ) ) : ?>
						<div class="contact-info__row">
							<span class="contact-info__icon" aria-hidden="true">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"></path></svg>
							</span>
							<div>
								<p class="contact-info__label"><?php echo esc_html( $contact_text( 'contact_whatsapp_label', __( 'WhatsApp', 'brittos-dentistry' ) ) ); ?></p>
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
								<p class="contact-info__label"><?php echo esc_html( $contact_text( 'contact_email_label', __( 'Email', 'brittos-dentistry' ) ) ); ?></p>
								<p class="contact-info__value"><a href="<?php echo esc_url( 'mailto:' . antispambot( $email ) ); ?>"><?php echo esc_html( antispambot( $email ) ); ?></a></p>
							</div>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $full_address || $opening_hours ) : ?>
				<div class="contact-info__card">
					<?php if ( $full_address ) : ?>
						<div class="contact-info__row">
							<span class="contact-info__icon" aria-hidden="true">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
							</span>
							<div>
								<p class="contact-info__label"><?php echo esc_html( $contact_text( 'contact_address_label', __( 'Address', 'brittos-dentistry' ) ) ); ?></p>
								<p class="contact-info__value"><?php echo esc_html( $full_address ); ?></p>
							</div>
						</div>
					<?php endif; ?>

					<?php if ( $opening_hours ) : ?>
						<div class="contact-info__row">
							<span class="contact-info__icon" aria-hidden="true">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
							</span>
							<div>
								<p class="contact-info__label"><?php echo esc_html( $contact_text( 'contact_hours_label', __( 'Hours', 'brittos-dentistry' ) ) ); ?></p>
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

			<?php
			$map_embed_url = '1' === brittos_clinic_field( 'contact_map_enabled' ) && function_exists( 'brittos_core_get_map_embed_url' )
				? brittos_core_get_map_embed_url()
				: '';
			if ( $map_embed_url ) :
				$map_heading    = $contact_text( 'contact_map_heading', __( 'Find Us', 'brittos-dentistry' ) );
				$directions_url = function_exists( 'brittos_core_get_map_directions_url' ) ? brittos_core_get_map_directions_url() : '';
				?>
				<section
					class="contact-map"
					<?php echo $map_heading ? 'aria-labelledby="contact-map-heading"' : 'aria-label="' . esc_attr__( 'Clinic location map', 'brittos-dentistry' ) . '"'; ?>
				>
					<div class="contact-map__card">
						<div class="contact-map__header">
							<?php if ( $map_heading ) : ?>
								<h2 id="contact-map-heading" class="contact-map__heading"><?php echo esc_html( $map_heading ); ?></h2>
							<?php endif; ?>

							<?php if ( $directions_url ) : ?>
								<a
									class="button button--ghost contact-map__directions"
									href="<?php echo esc_url( $directions_url ); ?>"
									target="_blank"
									rel="noopener noreferrer"
									aria-label="<?php echo esc_attr( sprintf( __( 'Get directions to %s', 'brittos-dentistry' ), $map_heading ? $map_heading : __( 'the clinic', 'brittos-dentistry' ) ) ); ?>"
								>
									<?php esc_html_e( 'Get Directions', 'brittos-dentistry' ); ?>
								</a>
							<?php endif; ?>
						</div>

						<div class="contact-map__frame-wrap">
							<iframe
								class="contact-map__frame"
								src="<?php echo esc_url( $map_embed_url ); ?>"
								title="<?php echo esc_attr( $map_heading ? $map_heading : __( 'Clinic location map', 'brittos-dentistry' ) ); ?>"
								loading="lazy"
								referrerpolicy="no-referrer-when-downgrade"
							></iframe>
						</div>
					</div>
				</section>
			<?php endif; ?>

		</aside>

		<div id="appointment-form" class="contact-form-card" data-reveal>
			<?php if ( function_exists( 'brittos_core_render_appointment_form' ) ) : ?>
				<?php brittos_core_render_appointment_form(); ?>
				<?php $required_note = $contact_text( 'contact_required_note', __( '* Required fields', 'brittos-dentistry' ) ); ?>
				<?php if ( $required_note ) : ?>
					<p class="contact-form-card__required-note"><?php echo esc_html( $required_note ); ?></p>
				<?php endif; ?>
			<?php else : ?>
				<p class="empty-state">
					<?php echo esc_html( $contact_text( 'contact_form_unavailable_text', __( 'The appointment form is temporarily unavailable.', 'brittos-dentistry' ) ) ); ?>
					<?php if ( $phone ) : ?>
						<?php
						$call_text = $contact_text( 'contact_form_call_text', __( 'Please call %s to book.', 'brittos-dentistry' ) );
						printf(
							/* translators: %s: phone number */
							esc_html( $call_text ),
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
