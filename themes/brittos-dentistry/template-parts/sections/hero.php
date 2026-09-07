<?php
/**
 * Homepage hero: warm human positioning, primary + secondary CTA.
 * The hero image (if a featured image is set on the front page) is the
 * LCP candidate, so it is never lazy-loaded and gets fetchpriority=high.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dentist     = brittos_clinic_field( 'dentist_name' );
$phone       = brittos_clinic_field( 'phone' );
$hero_id     = get_post_thumbnail_id();
?>
<section class="hero" aria-label="<?php esc_attr_e( 'Introduction', 'brittos-dentistry' ); ?>">
	<div class="container hero__inner">

		<div class="hero__content">
			<p class="hero__eyebrow"><?php esc_html_e( 'Independent dental care', 'brittos-dentistry' ); ?></p>

			<h1 class="hero__title">
				<?php
				echo esc_html(
					get_the_title() && is_front_page() && ! is_home()
						? get_the_title()
						: __( 'Gentle, unhurried dentistry from someone who remembers your name.', 'brittos-dentistry' )
				);
				?>
			</h1>

			<p class="hero__lede">
				<?php
				echo esc_html(
					$dentist
						? sprintf(
							/* translators: %s: dentist name */
							__( 'Personally cared for by %s — no rotating staff, no rushed visits, just steady, honest dentistry.', 'brittos-dentistry' ),
							$dentist
						)
						: __( 'A calm, independently run practice built around unhurried visits and honest, steady care.', 'brittos-dentistry' )
				);
				?>
			</p>

			<div class="hero__actions">
				<?php brittos_button( array(
					'text'  => __( 'Book an appointment', 'brittos-dentistry' ),
					'url'   => '#appointment-form',
					'style' => 'primary',
				) ); ?>
				<?php if ( $phone ) : ?>
					<?php brittos_button( array(
						'text'  => sprintf(
							/* translators: %s: clinic phone number */
							__( 'Call %s', 'brittos-dentistry' ),
							$phone
						),
						'url'   => brittos_tel_href( $phone ),
						'style' => 'ghost',
					) ); ?>
				<?php endif; ?>
			</div>
		</div>

		<div class="hero__media">
			<?php if ( $hero_id ) : ?>
				<?php echo brittos_lcp_image( $hero_id, 'brittos-hero', array( 'class' => 'hero__image', 'alt' => esc_attr( get_the_title() ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<?php else : ?>
				<img
					src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.webp' ); ?>"
					alt=""
					class="hero__image hero__image--logo"
					width="640"
					height="512"
					fetchpriority="high"
					decoding="async"
				>
			<?php endif; ?>
		</div>

	</div>
</section>
