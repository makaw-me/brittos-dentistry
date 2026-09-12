<?php
/** About page hero, using the shared hero visual language and Clinic Info content. */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$eyebrow  = brittos_clinic_field( 'about_hero_eyebrow' );
$title    = brittos_clinic_field( 'about_hero_title', get_the_title() );
$lede     = brittos_clinic_field( 'about_hero_lede' );
$image_id = absint( brittos_clinic_field( 'about_hero_image_id' ) );
$image_id = $image_id && wp_attachment_is_image( $image_id ) ? $image_id : absint( brittos_clinic_field( 'dentist_photo_id' ) );
$image_id = $image_id && wp_attachment_is_image( $image_id ) ? $image_id : get_post_thumbnail_id();
$fullbleed = '1' === brittos_clinic_field( 'about_hero_fullbleed' ) && $image_id && wp_attachment_is_image( $image_id );
$booking  = function_exists( 'brittos_core_get_booking_url' ) ? brittos_core_get_booking_url() : '';
?>

<section class="hero hero--full-bleed about-hero<?php echo $fullbleed ? ' about-hero--fullbleed' : ''; ?>" aria-labelledby="about-hero-title">
	<?php if ( $fullbleed ) : ?>
		<div class="about-hero__background" aria-hidden="true">
			<?php echo wp_get_attachment_image( $image_id, 'brittos-hero', false, array( 'class' => 'about-hero__background-image' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="about-hero__background-overlay"></div>
		</div>
	<?php endif; ?>
	<div class="container hero__inner">
		<div class="hero__content" data-reveal>
			<?php if ( $eyebrow ) : ?><p class="hero__badge"><span class="hero__badge-dot" aria-hidden="true"></span><span class="hero__badge-text"><?php echo esc_html( $eyebrow ); ?></span></p><?php endif; ?>
			<h1 id="about-hero-title" class="hero__title"><?php echo esc_html( $title ); ?></h1>
			<?php if ( $lede ) : ?><p class="hero__lede"><?php echo esc_html( $lede ); ?></p><?php endif; ?>
			<?php if ( $booking ) : ?><div class="hero__actions"><?php brittos_button( array( 'text' => __( 'Book an appointment', 'brittos-dentistry' ), 'url' => $booking, 'style' => 'primary', 'icon' => 'arrow' ) ); ?></div><?php endif; ?>
		</div>
		<?php if ( ! $fullbleed && $image_id && wp_attachment_is_image( $image_id ) ) : ?>
			<div class="hero__media" data-reveal><div class="hero__media-wrapper"><?php echo wp_get_attachment_image( $image_id, 'brittos-hero', false, array( 'class' => 'hero__image', 'loading' => 'eager', 'fetchpriority' => 'high', 'decoding' => 'async' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div></div>
		<?php endif; ?>
	</div>
</section>