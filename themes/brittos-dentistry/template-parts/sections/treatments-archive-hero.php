<?php
/**
 * Hero for the Treatments archive page. Visually reuses the exact same
 * `.treatment-hero` system as individual Treatment pages (including the
 * optional full-bleed background image + overlay), but every piece of
 * content is editable from Settings > Clinic Info > Treatments Page Hero
 * instead of being pulled from a single post — this page isn't tied to
 * one Treatment, so its content lives in the clinic settings alongside
 * the homepage hero fields.
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$badge_text = brittos_clinic_field( 'treatments_eyebrow', brittos_clinic_field( 'treatments_hero_badge_text', __( 'Our Treatments', 'brittos-dentistry' ) ) );
$title      = brittos_clinic_field( 'treatments_heading', brittos_clinic_field( 'treatments_hero_title', __( 'Care built around what you actually need', 'brittos-dentistry' ) ) );
$lede       = brittos_clinic_field( 'treatments_lede', brittos_clinic_field( 'treatments_hero_lede', __( 'Explore every treatment we offer, grouped by the kind of care you need.', 'brittos-dentistry' ) ) );
$bg_image_id = absint( brittos_clinic_field( 'treatments_hero_bg_image_id' ) );
$has_image   = $bg_image_id && wp_attachment_is_image( $bg_image_id );
$has_media   = '1' === brittos_clinic_field( 'treatments_hero_fullbleed' ) && $has_image;
?>
<header class="treatment-hero<?php echo $has_media ? ' treatment-hero--overlay' : ''; ?>">
	<?php if ( $has_media ) : ?>
		<div class="treatment-hero__bg" aria-hidden="true">
			<?php echo wp_get_attachment_image( $bg_image_id, 'brittos-hero', false, array( 'class' => 'treatment-hero__bg-image' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<div class="treatment-hero__bg-overlay"></div>
		</div>
	<?php endif; ?>

	<div class="container treatment-hero__inner">
		<div class="treatment-hero__content" data-reveal>
			<span class="hero__badge treatment-hero__badge">
				<span class="hero__badge-dot" aria-hidden="true"></span>
				<span class="hero__badge-text"><?php echo esc_html( $badge_text ); ?></span>
			</span>

			<h1 class="hero__title treatment-hero__title"><?php echo esc_html( $title ); ?></h1>

			<?php if ( $lede ) : ?>
				<p class="hero__lede treatment-hero__lede"><?php echo esc_html( $lede ); ?></p>
			<?php endif; ?>
		</div>

		<?php if ( $has_image && ! $has_media ) : ?>
			<div class="treatment-hero__media" data-reveal>
				<?php echo wp_get_attachment_image( $bg_image_id, 'brittos-hero', false, array( 'class' => 'treatment-hero__image', 'loading' => 'eager', 'fetchpriority' => 'high', 'decoding' => 'async' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>
	</div>
</header>
