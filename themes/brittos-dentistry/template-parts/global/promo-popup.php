<?php
/**
 * Global promotional popup modal. Shown once per visitor (cookie-gated
 * in JS — see assets/js/promo-popup.js) unless a "don't show again"
 * cookie is already present. Renders nothing at all when disabled in
 * Settings > Clinic Info > Promotional Popup, so a disabled popup adds
 * zero markup, zero CSS, and zero JS to the page (see inc/enqueue.php).
 *
 * @package Brittos_Dentistry
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( '1' !== brittos_clinic_field( 'popup_enabled' ) ) {
	return;
}

$heading     = brittos_clinic_field( 'popup_heading' );
$text        = brittos_clinic_field( 'popup_text' );
$cta_text    = brittos_clinic_field( 'popup_cta_text' );
$cta_url     = function_exists( 'brittos_core_get_popup_cta_url' ) ? brittos_core_get_popup_cta_url() : '';
$image_id    = absint( brittos_clinic_field( 'popup_image_id' ) );
$cookie_days = absint( brittos_clinic_field( 'popup_cookie_days' ) );
if ( $cookie_days < 1 ) {
	$cookie_days = 30;
}

$has_image = $image_id && wp_attachment_is_image( $image_id );
$has_cta   = $cta_text && $cta_url;

// Nothing meaningful to show — stay silent rather than render an empty shell.
if ( ! $heading && ! $text && ! $has_image && ! $has_cta ) {
	return;
}
?>
<div
	class="promo-popup"
	id="promo-popup"
	data-promo-popup
	data-cookie-name="brittos_promo_popup_dismissed"
	data-cookie-days="<?php echo esc_attr( $cookie_days ); ?>"
	hidden
>
	<div class="promo-popup__backdrop" data-promo-popup-backdrop></div>

	<div
		class="promo-popup__dialog"
		role="dialog"
		aria-modal="true"
		<?php echo $heading ? 'aria-labelledby="promo-popup-heading"' : 'aria-label="' . esc_attr__( 'Promotional offer', 'brittos-dentistry' ) . '"'; ?>
		<?php echo $text ? 'aria-describedby="promo-popup-text"' : ''; ?>
	>
		<button type="button" class="promo-popup__close" data-promo-popup-close>
			<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 6 6 18M6 6l12 12"/></svg>
			<span class="screen-reader-text"><?php esc_html_e( 'Close', 'brittos-dentistry' ); ?></span>
		</button>

		<?php if ( $has_image ) : ?>
			<div class="promo-popup__media">
				<?php echo wp_get_attachment_image( $image_id, 'medium_large', false, array(
					'class'    => 'promo-popup__image',
					'alt'      => '',
					'loading'  => 'lazy',
					'decoding' => 'async',
				) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php endif; ?>

		<?php if ( $heading || $text || $has_cta ) : ?>
		<div class="promo-popup__body">
			<?php if ( $heading ) : ?>
				<h2 id="promo-popup-heading" class="promo-popup__heading"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>

			<?php if ( $text ) : ?>
				<p id="promo-popup-text" class="promo-popup__text"><?php echo esc_html( $text ); ?></p>
			<?php endif; ?>

			<?php if ( $has_cta ) : ?>
				<a class="button button--primary promo-popup__cta" href="<?php echo esc_url( $cta_url ); ?>">
					<?php echo esc_html( $cta_text ); ?>
				</a>
			<?php endif; ?>
		</div>
		<?php endif; ?>
	</div>
</div>
