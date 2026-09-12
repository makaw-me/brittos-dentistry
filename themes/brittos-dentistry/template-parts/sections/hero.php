<?php
/**
 * Homepage full-bleed hero: modern editorial positioning, atmospheric backdrop,
 * interactive CTA buttons, clinic stats, and LCP-optimized media candidate.
 * All contents and media are fully configurable from Settings > Clinic Info.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$dentist          = brittos_clinic_field( 'dentist_name' );
$credentials      = brittos_clinic_field( 'credentials' );
$phone            = brittos_clinic_field( 'phone' );
$hero_id          = get_post_thumbnail_id();
$hero_bg_img      = brittos_clinic_field( 'hero_bg_image_id' );
$hero_video       = brittos_clinic_field( 'hero_bg_video_url' );
$has_media        = $hero_bg_img || $hero_video;

$badge_text       = brittos_clinic_field( 'hero_badge_text', __( 'Independent Private Dental Practice', 'brittos-dentistry' ) );
$hero_title       = brittos_clinic_field( 'hero_title' );
if ( ! $hero_title ) {
	$hero_title = get_the_title() && is_front_page() && ! is_home()
		? get_the_title()
		: __( 'Gentle, unhurried dentistry from someone who knows your name.', 'brittos-dentistry' );
}

$hero_lede        = brittos_clinic_field( 'hero_lede' );
if ( ! $hero_lede ) {
	$hero_lede = $dentist
		? sprintf(
			/* translators: %s: dentist name */
			__( 'Personally cared for by %s — no rotating staff, no rushed visits, just calm, honest, and meticulous clinical care.', 'brittos-dentistry' ),
			$dentist
		)
		: __( 'A calm, independent clinic dedicated to unhurried appointments, transparent explanations, and lasting dental health.', 'brittos-dentistry' );
}

$cta_primary_text = brittos_clinic_field( 'hero_cta_primary_text', __( 'Book an appointment', 'brittos-dentistry' ) );
$cta_primary_url  = brittos_clinic_field( 'hero_cta_primary_url' );
if ( ! $cta_primary_url ) {
	$cta_primary_url = function_exists( 'brittos_core_get_booking_url' ) ? brittos_core_get_booking_url() : '#appointment-form';
}

$stat1_val        = brittos_clinic_field( 'hero_stat1_val', '1:1' );
$stat1_label      = brittos_clinic_field( 'hero_stat1_label', __( 'Direct Dentist Care', 'brittos-dentistry' ) );
$stat2_val        = brittos_clinic_field( 'hero_stat2_val', '100%' );
$stat2_label      = brittos_clinic_field( 'hero_stat2_label', __( 'Transparent Plans', 'brittos-dentistry' ) );
$stat3_val        = brittos_clinic_field( 'hero_stat3_val', '0%' );
$stat3_label      = brittos_clinic_field( 'hero_stat3_label', __( 'Rushed Visits', 'brittos-dentistry' ) );

$floating_title   = brittos_clinic_field( 'hero_floating_title', __( 'Dedicated Continuity', 'brittos-dentistry' ) );
$floating_sub     = brittos_clinic_field( 'hero_floating_sub', __( 'Same trusted dentist every visit', 'brittos-dentistry' ) );
?>
<section class="hero hero--full-bleed<?php echo $has_media ? ' hero--has-media' : ''; ?>" aria-label="<?php esc_attr_e( 'Introduction', 'brittos-dentistry' ); ?>">
	<div class="hero__bg-ambient" aria-hidden="true">
		<?php if ( $hero_video ) : ?>
			<video
				class="hero__bg-video"
				autoplay
				muted
				loop
				playsinline
				<?php if ( $hero_bg_img ) : ?>poster="<?php echo esc_url( wp_get_attachment_image_url( $hero_bg_img, 'full' ) ); ?>"<?php endif; ?>
			>
				<source src="<?php echo esc_url( $hero_video ); ?>" type="video/mp4">
			</video>
			<div class="hero__bg-overlay"></div>
		<?php elseif ( $hero_bg_img ) : ?>
			<div class="hero__bg-image" style="background-image: url('<?php echo esc_url( wp_get_attachment_image_url( $hero_bg_img, 'full' ) ); ?>');"></div>
			<div class="hero__bg-overlay"></div>
		<?php else : ?>
			<div class="hero__glow hero__glow--1"></div>
			<div class="hero__glow hero__glow--2"></div>
		<?php endif; ?>
	</div>

	<div class="container hero__inner">

		<div class="hero__content">
			<div class="hero__badge">
				<span class="hero__badge-dot" aria-hidden="true"></span>
				<span class="hero__badge-text"><?php echo esc_html( $badge_text ); ?></span>
			</div>

			<h1 class="hero__title"><?php echo esc_html( $hero_title ); ?></h1>

			<p class="hero__lede"><?php echo esc_html( $hero_lede ); ?></p>

			<div class="hero__actions">
				<?php brittos_button( array(
					'text'  => $cta_primary_text,
					'url'   => $cta_primary_url,
					'style' => 'primary',
					'icon'  => 'arrow',
					'class' => 'hero__cta-primary',
				) ); ?>
				
				<?php if ( $phone ) : ?>
					<a class="button button--ghost hero__cta-phone" href="<?php echo esc_url( brittos_tel_href( $phone ) ); ?>">
						<span class="button__icon button__icon--phone" aria-hidden="true">
							<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
						</span>
						<span class="button__text"><?php echo esc_html( $phone ); ?></span>
					</a>
				<?php else : ?>
					<?php brittos_button( array(
						'text'  => __( 'Explore treatments', 'brittos-dentistry' ),
						'url'   => '#treatments-heading',
						'style' => 'ghost',
						'icon'  => 'arrow',
					) ); ?>
				<?php endif; ?>
			</div>

			<div class="hero__trust-metrics" aria-label="<?php esc_attr_e( 'Practice highlights', 'brittos-dentistry' ); ?>">
				<div class="hero__metric">
					<span class="hero__metric-value"><?php echo esc_html( $stat1_val ); ?></span>
					<span class="hero__metric-label"><?php echo esc_html( $stat1_label ); ?></span>
				</div>
				<div class="hero__metric-divider" aria-hidden="true"></div>
				<div class="hero__metric">
					<span class="hero__metric-value"><?php echo esc_html( $stat2_val ); ?></span>
					<span class="hero__metric-label"><?php echo esc_html( $stat2_label ); ?></span>
				</div>
				<div class="hero__metric-divider" aria-hidden="true"></div>
				<div class="hero__metric">
					<span class="hero__metric-value"><?php echo esc_html( $stat3_val ); ?></span>
					<span class="hero__metric-label"><?php echo esc_html( $stat3_label ); ?></span>
				</div>
			</div>
		</div>

		<div class="hero__media">
			<div class="hero__media-wrapper">
				<?php if ( $hero_id ) : ?>
					<?php echo brittos_lcp_image( $hero_id, 'brittos-hero', array( 'class' => 'hero__image', 'alt' => esc_attr( get_the_title() ) ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<?php else : ?>
					<div class="hero__media-card">
						<img
							src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/logo.webp' ); ?>"
							alt="<?php esc_attr_e( 'Britto\'s Dentistry Emblem', 'brittos-dentistry' ); ?>"
							class="hero__image hero__image--logo"
							width="480"
							height="384"
							fetchpriority="high"
							decoding="async"
						>
						<div class="hero__media-caption">
							<p class="hero__media-title"><?php esc_html_e( 'Thoughtful Dental Care', 'brittos-dentistry' ); ?></p>
							<p class="hero__media-sub"><?php esc_html_e( 'Modern technology, gentle technique, patient-first.', 'brittos-dentistry' ); ?></p>
						</div>
					</div>
				<?php endif; ?>

				<div class="hero__floating-card" aria-hidden="true">
					<div class="hero__floating-icon">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
					</div>
					<div class="hero__floating-text">
						<strong><?php echo esc_html( $floating_title ); ?></strong>
						<span><?php echo esc_html( $floating_sub ); ?></span>
					</div>
				</div>
			</div>
		</div>

	</div>
</section>
